<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Report;

class IdeHelperController extends Controller
{

    public function index()
    {
        $report = new \App\Report();
        
        return $report->customer;
    }
}
