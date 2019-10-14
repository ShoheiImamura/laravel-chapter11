<?php

namespace App\Http\Controllers;

use App\Services\CustomerService; // 忘れず use
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getCustomers(CustomerService $customer_service)
    {
        // Service を利用
        return response()->json($customer_service->getCustomers());
    }

    public function postCustomer(\Illuminate\Http\Request $request)
    {
        // laravel の validate メソッドを利用
        $this->validate(
            $request,
            ['name' => 'required']
            // ['name.required' => ':attribute は必須項目です'] // validate メソッド 第 3 引数にエラーメッセージを指定できる
        );
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
