<?php

namespace Zephia\ZLeader\Http\Controllers;

use Illuminate\Routing\Controller;
use Zephia\ZLeader\Engine\Crude\FormCRUD;
 
class FormController extends Controller
{
    public function index()
    {
        return view('ZLeader::form.index', [
            'crudeSetup' => [(new FormCRUD)->getCrudeSetupData()]
        ]);
    }
}
