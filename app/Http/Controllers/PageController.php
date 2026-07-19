<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function helpSupport()
    {
        return view('pages.help');
    }

    public function staff()
    {
        return view('pages.staff');
    }

    public function team()
    {
        return view('pages.team');
    }

    public function careers()
    {
        return view('pages.careers');
    }

    public function blog()
    {
        return view('pages.blog');
    }

    public function security()
    {
        return view('pages.security');
    }

    public function global()
    {
        return view('pages.global');
    }

    public function charts()
    {
        return view('pages.charts');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function compliances()
    {
        return view('pages.compliances');
    }
}
