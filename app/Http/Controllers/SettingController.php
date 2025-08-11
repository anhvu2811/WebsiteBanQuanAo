<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function displayAbout()
    {
        return view('page.user.about');
    }

    public function displayNews()
    {
        return view('page.user.news');
    }

    public function displayLogin()
    {
        return view('page.user.login');
    }

    public function edit()
    {
        return view('setting.update');
    }

    public function update(Request $request)
    {
        $setting = Setting::first();
        $setting->update([
            'location'          => $request->input('location'),
            'hotline'           => $request->input('hotline'),
            'email'             => $request->input('email'),
            'time_active'       => $request->input('time_active'),
            'site_name'         => $request->input('site_name'),
            'site_description'  => $request->input('site_description'),  
        ]);

        return redirect()->route('setting.edit');
    }



}
