<?php

namespace App\Http\Controllers;

class WebsiteController extends Controller
{
    public function home()
    {
        return view('website.home');
    }

    public function about()
    {
        return view('website.about');
    }

    public function courses()
    {
        return view('website.courses');
    }

    public function admissions()
    {
        return view('website.admissions');
    }

    public function jobs()
    {
        return view('website.jobs');
    }

    public function contact()
    {
        return view('website.contact');
    }
    public function campus()
{
    return view('website.campus');
}
}