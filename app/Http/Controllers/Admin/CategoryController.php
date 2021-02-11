<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategorySize;
use App\Models\Subcategory;
use App\Models\Size;
use App\Models\Filter;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::where('status', '1')
            ->with([
                'subcategories' => function ($q) {
                    $q->select('id', 'name', 'name_english', 'category_id')->withCount('products')
                        ->where('status', '1');
                },
                'sizes' => function ($q) {
                    $q->select('sizes.id', 'sizes.name');
                },
                'filters' => function ($q) {
                    $q->select('filters.id');
                }
            ])
            ->withCount('products')
            ->get();

        $sizes = Size::where('status', '1')->get();
        $filters = Filter::get();

        return view('admin.categories.index')->with(['categories' => $categories, 'sizes' => $sizes, 'filters' => $filters]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->all());

        foreach ($request->sizes as $key => $size) {
            $category->sizes()->attach($category->id, ['size_id' => $size]);
        }

        foreach ($request->filters as $key => $filter) {
            $category->filters()->attach($category->id, ['filter_id' => $filter]);
        }

        foreach ($request->subcategories as $key => $subcategory) {
            $category->subcategories()->create($subcategory);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        $category_sizes = CategorySize::select('category_sizes.id', 'sizes.name')
            ->where('category_id', $id)
            ->whereNotIn('size_id', $request->sizes)
            ->join('sizes', 'category_sizes.size_id', '=', 'sizes.id')
            ->withCount('productAmount')
            ->get();


            

        $hasProduct = false;
        $sizeName = "";

        foreach ($category_sizes as $size) {
            if ($size->product_amount_count > 0) {
                $hasProduct = true;
                $sizeName = $size->name;
            }
        }

        if ($hasProduct) {
            return response()->json(["error" => "La talla $sizeName tiene productos registrados, proceda ha eliminarlos"], 422);
        }

        $category = Category::find($id);
        $category->name = $request->name;
        $category->name_english = $request->name_english;
        $category->save();

        $category->filters()->detach();

        $category_sizes = CategorySize::select('category_sizes.id', 'sizes.name')
            ->where('category_id', $id)
            ->whereNotIn('size_id', $request->sizes)
            ->delete();

        foreach ($request->sizes as $key => $size) {
            if (CategorySize::select('category_sizes.id', 'sizes.name')->where('category_id', $id)->where('size_id', $size)->count() == 0) {
                $category->sizes()->attach($category->id, ['size_id' => $size]);
            }
        }

        foreach ($request->filters as $key => $filter) {
            $category->filters()->attach($category->id, ['filter_id' => $filter]);
        }

        $sub_ids = [];
        foreach ($request->subcategories as $key => $subcategory) {
            if ($subcategory['id'] > 0) {
                Subcategory::find($subcategory['id'])->update(['name' => $subcategory['name'], 'name_english' => $subcategory['name_english']]);
            } else {
                $subcategory = $category->subcategories()->create($subcategory);
            }

            $sub_ids[] = $subcategory['id'];
        }

        Subcategory::where('category_id', $id)->whereNotIn('id', $sub_ids)->doesnthave('products')->update(['status' => '2']);
    }

    public function getSubCategory($id)
    {
        $subcategory=Subcategory::find($id);

        return response()->json([
            'result' => true,
            'subcategory' => $subcategory

        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::where('id', $id)->withCount(['products' => function ($q) {
            $q->where('status', '1');
        }])->first();

        if ($category->products_count == 0) {
            $category->status = "2";
            $category->save();

            Subcategory::where('category_id', $id)->doesnthave('products')->update(['status' => '2']);
        } else {
            return response()->json([
                'error' => "La categoría tiene productos activos",
                'result' => false
            ]);
        }
    }
}
