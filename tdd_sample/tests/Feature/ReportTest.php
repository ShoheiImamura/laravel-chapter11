<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportTest extends TestCase
{
    use RefreshDatabase; // テストメソッド実行ごとに、DB をリフレッシュ

    public function setUp()
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'TestDataSeeder']);
    }

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
    public function api_customersにGETメソッドでアクセスするとJSONが返却される()
    {
        $response = $this->get('api/customers');
        // 検証部分
        $this->assertThat($response->content(), $this->isJson());
    }

    /**
     * @test
     */
    public function api_customersにGETメソッドで取得できる顧客情報のJSON形式は要件通りである()
    {
        // 実行部分
        $response = $this->get('api/customers');
        $customers = $response->json();
        $customer = $customers[0];
        // 検証部分
        $this->assertSame(['id', 'name'], array_keys($customer));
    }

    /**
     * @test
     */
    public function api_customersにGETメソッドでアクセスすると2件の顧客リストが返却される()
    {
        // 実行部分
        $response = $this->get('api/customers');
        // 検証部分
        $response->assertJsonCount(2);
    }



    /**
     * @test
     */
    public function api_customersにPOSTメソッドでアクセスできる()
    {
        // 実行部分 action 
        $customer = [
            'name' => 'customer_name',
        ];
        $response = $this->postJson('api/customers', $customer);

        // 検証部分 assert
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function api_customersに顧客名をPOSTするとcustomersテーブルにそのデータが追加される()
    {
        $params = [
            'name' => '顧客名',
        ];
        $this->postJson('api/customers', $params);
        // 検証部分
        $this->assertDatabaseHas('customers', $params);
    }

    /**
     * @test
     */
    public function POST_api_customersにnameがふくまれない場合422UnprocessableEntityが返却される()
    {
        $params = [];
        $response = $this->postJson('api/customers', $params);
        $response->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    /**
     * @test
     */
    public function POST_api_customersにnameが空の場合422UnprocessableEntityが返却される()
    {
        $params = ['name' => ''];
        $response = $this->postJson('api/customers', $params);
        $response->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     * 
     */
    public function POST_api_customersのエラーレスポンスの確認()
    {
        $params = ['name' => ''];
        $response = $this->postJson('api/customers', $params);
        // $error_response = [];        // name の値を null で送信した場合のエラーレスポンスが不明
        $error_response = [
            'message' => "The given data was invalid.",
            'errors' => [
                'name' => [
                    'name は必須項目です'
                ]
            ]
        ];
        $response->assertExactJson($error_response);
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
