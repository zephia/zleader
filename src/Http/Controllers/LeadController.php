<?php

namespace Zephia\ZLeader\Http\Controllers;

use Illuminate\Routing\Controller;
 
class LeadController extends Controller
{
    public function index()
    {
        return view('ZLeader::index', []);
    }
}
