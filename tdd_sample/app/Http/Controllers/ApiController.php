<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getCustomers()
    {
        return response()->json(\App\Customer::query()->select(['id', 'name'])->get());
    }

    public function postCustomers(\Illuminate\Http\Request $request)
    {
        if(!$request->json('name')){
            return response()->json([], \Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $customer = new \App\Customer();
        $customer->name = $request->json('name');
        $customer->save();
    }
}
