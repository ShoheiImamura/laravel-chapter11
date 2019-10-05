<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportTest extends TestCase
{
    /**
     * @test
     */
    public function api_customersにGETメソッドでアクセスできる() // TODOリストの項目をそのままメソッド名にする
    {
        // 次に実行部分を記述
        $response = $this->get('api/customers');
        // 先に検証部分を記述
        $response->assertStatus(200);
    }
    /**
     * @test
     */
    public function api_customersにPOSTメソッドでアクセスできる()
    {
        // 実行部分
        $response = $this->post('api/customers');
        // 検証部分
        $response->assertStatus(200);
    }
    /**
     * @test
     */
    public function api_customers_customer_idにGETメソッドでアクセスできる()
    {
        // 実装部分
        $response = $this->get('api/customers/1'); // 動的は URL にアクセスする場合は、具体的な値とする
        // 検証部分
        $response->assertStatus(200);
    }
    /**
     * @test
     */
    public function api_customers_customer_idにPUTメソッドでアクセスできる()
    {
        // 実行部分
        $response = $this->put('api/customers/1');
        // 検証部分
        $response->assertStatus(200);
    }
    /**
     * @test
     */
    public function api_cusomers_customer_idにDELETEメソッドでアクセスできる()
    {
        // 実行部分
        $response = $this->delete('api/customers/1');
        // 検証部分
        $response->assertStatus(200);
    }
    /**
     * @test
     */
    public function api_reportsにGETメソッドでアクセスできる()
    {
        // 実行部分
        $response = $this->get('api/reports');
        // 検証部分
        $response->assertStatus(200);
    }
    /**
     * @test
     */
    public function api_reportsにPOSTメソッドでアクセスできる()
    {
        // 実行部分
        $response = $this->post('api/reports');
        // 検証部分
        $response->assertStatus(200);
    }
    /**
     * @test
     */
    public function api_reports_reports_idにGETメソッドでアクセスできる()
    {
        // 実行部分
        $response = $this->get('api/reports/1');
        // 検証部分
        $response->assertStatus(200);
    }
    /**
     * @test
     */
    public function api_reports_report_idにPUTメソッドでアクセスできる()
    {
        // 実行部分
        $response = $this->put('api/reports/1');
        // 検証部分
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function api_reports_report_idにDELETEメソッドでアクセスできる()
    {
        // 実行部分
        $response = $this->delete('api/reports/1');
        // 検証部分
        $response->assertStatus(200);
    }
}
