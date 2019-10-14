<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getCustomers()
    {
        return response()->json(\App\Customer::query()->select(['id', 'name'])->get());
    }

    public function postCustomer(\Illuminate\Http\Request $request)
    {
        // 独自 validation
        // if(!$request->json('name')){
        //     return response()->json([], \Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
        // }
        // $customer = new \App\Customer();
        // $customer->name = $request->json('name');
        // $customer->save();
        
        // laravel の validate メソッドを利用
        $this->validate($request, ['name' => 'required']);
        $customer = new \App\Customer();
        $customer->name = $request->json('name');
        $customer->save();
    }
    public function getCustomer()
    {

    }
    public function putCustomer()
    {

    }
    public function deleteCustomer()
    {
        
    }

    public function getReports()
    {

    }
    public function postReport()
    {

    }
    public function getReport()
    {

    }
    public function putReport()
    {

    }
    public function deleteReport()
    {

    }
}
