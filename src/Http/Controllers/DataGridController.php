<?php

namespace Zephia\ZLeader\Http\Controllers;

use Illuminate\Routing\Controller;
use DataGrid;
 
class DataGridController extends Controller
{
    public function source()
    {
        $data = \Lead::all();

        return DataGrid::make($data, array(
            'id',
            'name',
            'email',
        ));
    }
}