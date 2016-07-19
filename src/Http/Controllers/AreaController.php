<?php

namespace Zephia\ZLeader\Http\Controllers;

use Illuminate\Routing\Controller;
use Zephia\ZLeader\Crude\AreaCRUD;
 
class AreaController extends Controller
{
    public function index()
    {
        return view('ZLeader::area.index', [
            'crudeSetup' => [(new AreaCRUD)->getCrudeSetupData()]
        ]);
    }
}
