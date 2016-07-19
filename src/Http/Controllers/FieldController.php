<?php

namespace Zephia\ZLeader\Http\Controllers;

use Illuminate\Routing\Controller;
use Zephia\ZLeader\Crude\FieldCRUD;
 
class FieldController extends Controller
{
    public function index()
    {
        return view('ZLeader::field.index', [
            'crudeSetup' => [(new FieldCRUD)->getCrudeSetupData()]
        ]);
    }
}
