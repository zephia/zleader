<?php

namespace Zephia\ZLeader\Http\Controllers;

use Illuminate\Routing\Controller;
 
class DashboardController extends Controller
{
    public function index()
    {
        return view('ZLeader::dashboard', []);
    }
}
