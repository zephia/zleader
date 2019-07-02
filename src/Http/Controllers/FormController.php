<?php

namespace Zephia\ZLeader\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Zephia\ZLeader\Crude\FormCRUD;
use Zephia\ZLeader\Model\Form;
use Illuminate\Support\Facades\Response;

class FormController extends Controller
{
    public function index()
    {
        return view('ZLeader::form.index', [
            'crudeSetup' => [(new FormCRUD)->getCrudeSetupData()]
        ]);
    }

    public function jsonIndex()
    {
        $forms = Form::with('area.company')
            ->get();

        return Response::json($forms);
    }
}
