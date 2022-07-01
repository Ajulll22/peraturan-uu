<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    use NavigationList;
    public function index()
    {

        // PAGE SETUP
        $pageTitle = 'Beranda';
        $active = 'Beranda';

        return view('pages.dashboard.index', [
            'user' => Auth::user(),
            'pageTitle' => $pageTitle,
            'active' => $active,
            'navs' => $this->NavigationList(),
        ]);
    }
}
