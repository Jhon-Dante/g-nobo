<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SocialRequest;
use App\Models\Social;

class SocialController extends Controller
{
    public function index()
    {
        $social = Social::get();
        return view('admin.social.index')->with(['social' => $social]);
    }

    public function edit($id)
    {
        $social = Social::where('id', $id)->first();
        return view('admin.social.edit')->with(['social' => $social]);
    }

    public function update(SocialRequest $request, $id)
    {
        $social = Social::find($id);
        $social->facebook = $request->facebook;
        $social->youtube = $request->youtube;
        $social->instagram = $request->instagram;
        $social->twitter = $request->twitter;
        $social->slogan = $request->slogan;
        $social->english_slogan = $request->slogan_english;
        $social->address = $request->address;
        $social->phone = $request->phone;
        $social->email = $request->email;
        $social->horario_atc = $request->horario_atc;
        $social->horario_entrega = $request->horario_entrega;
        $social->save();
    }
}
