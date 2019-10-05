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
}
