<?php

namespace Zephia\ZLeader\Http\Controllers;

use Illuminate\Routing\Controller;
use Zephia\ZLeader\Crude\CompanyCRUD;

class CompanyController extends Controller
{
    public function index()
    {
        return view('ZLeader::company.index', [
            'crudeSetup' => [(new CompanyCRUD)->getCrudeSetupData()]
        ]);
    }
}
