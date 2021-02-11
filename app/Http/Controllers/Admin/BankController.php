<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Http\Requests\BankRequest;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = BankAccount::where('status', '!=', 2)->with('bank')->get();
        $banks = Bank::all();
        // dd($banks);
        return view('admin.banks.index', [
            'banks' => $banks, 
            'accounts' => $accounts
        ]);
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
    public function store(BankRequest $request)
    {
        $bank = new BankAccount;

        $bank->name = $request->name;
        $bank->bank_id = $request->bank_id;
        $bank->number = $request->number;
        $bank->identification = $request->identification;
        $bank->method = $request->bank_id == 1 ? BankAccount::ZELLE : BankAccount::NACIONAL;
        $bank->type = $request->type;
        $bank->email = $request->email;
        $bank->phone = $request->phone;

        $bank->save();
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
    public function update(BankRequest $request, $id)
    {
        $bank = BankAccount::find($id);
        $bank->name = $request->name;
        $bank->bank_id = $request->bank_id;
        $bank->method = $request->bank_id == 1 ? BankAccount::ZELLE : BankAccount::NACIONAL;
        $bank->identification = $request->identification;
        $bank->number = $request->number;
        $bank->type = $request->type;
        $bank->email = $request->email;
        $bank->phone = $request->phone;
        $bank->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bank = BankAccount::find($id);
        $bank->delete();
    }

    public function estatus($id)
    {
        $bank = BankAccount::find($id);
        $bank->status = !$bank->status; 
        $bank->save();
    }
}
