<?php

namespace Zephia\ZLeader\Http\Controllers;

use App\Http\Controllers\Controller;
use Zephia\ZLeader\Http\Models\Lead ;
 
class DataGridController extends Controller
{
    public function source()
    {
        $data = \Lead::all();

        return \DataGrid::make($data, array(
            'id',
            'name',
            'email',
        ));
    }
}