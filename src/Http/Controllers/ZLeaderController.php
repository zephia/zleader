<?php

namespace Zephia\ZLeader\Http\Controllers;

use App\Http\Controllers\Controller;
use Zephia\ZLeader\Http\Models\Lead ;
 
class ZLeaderController extends Controller
{
    public function index()
    {
        return view('ZLeader::index',[]);
    }
}