<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        return view('favoritos');
    }

    public function ajax()
    {
        return Product::whereHas('favorites', function($q){
            $q->where('user_id', Auth::user()->id);
        })->with([
            'images',
            'categories' => function($q) {
                $q->with(['sizes' => function($q) {
                    $q->where('status','1');
                }]);
            },
            'colors' => function($q) {
                $q->with('amounts')->where('status','1');
            },
        ])
        ->where('status', '1')
        ->get();
    }

    public function store(Request $request)
    {
        return Favorite::create([
            'product_id' => $request->product_id,
            'user_id' => Auth::user()->id
        ]);
    }

    public function destroy(Request $request)
    {
        return Favorite::where('product_id', $request->product_id)
            ->where('user_id', Auth::user()->id)
            ->delete();
    }
}
