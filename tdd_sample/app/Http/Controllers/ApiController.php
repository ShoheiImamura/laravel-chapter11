<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getCustomers()
    {
        return response()->json(\App\Customer::query()->select(['id', 'name'])->get());
    }
}
