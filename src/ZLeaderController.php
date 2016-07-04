<?php

namespace Zephia\ZLeader;

use App\Http\Controllers\Controller;
 
class ZLeaderController extends Controller
{
    public function index()
    {
        return view('zleader::layouts.master',[]);
    }
}