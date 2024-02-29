<?php

namespace Tests\Unit;

use App\Services\V1\CustomerQuery;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class CustomerQueryTest extends TestCase
{
    /** 
     * @test
     */
    public function transformが正しいクエリを返す()
    {
        // モックRequestを作成
        $request = Request::create('/', 'GET', [
            'name' => ['eq' => 'John Doe'],
            'postalCode' => ['gt' => '1000', 'lt' => '2000'],
        ]);

        // CustomerQueryサービスをインスタンス化
        $customerQuery = new CustomerQuery();

        // transformメソッドを実行
        $result = $customerQuery->transform($request);

        // 期待される結果を定義
        $expected = [
            ['name', '=', 'John Doe'],
            ['postal_code', '>', '1000'],
            ['postal_code', '<', '2000'],
        ];

        // 結果が期待通りか検証
        $this->assertEquals($expected, $result);
    }

    /** 
     * @test
     * @group transform
     */
    public function transformにパラメータを渡さなかったら空のクエリを返す()
    {
        $request = Request::create('/', 'GET');
        $customerQuery = new CustomerQuery();
        $result = $customerQuery->transform($request);

        $this->assertEmpty($result);
    }

    /** 
     * @test
     * @group transform
     */
    public function transformがサポートされていないパラメータを無視する()
    {
        $request = Request::create('/', 'GET', [
            'unsupportedParam1' => ['eq' => 'value'],
            'name' => ['eq' => 'John Doe'],
            'unsupportedParam2' => ['eq' => 'value'],
        ]);
        $customerQuery = new CustomerQuery();
        $result = $customerQuery->transform($request);

        $expected = [
            ['name', '=', 'John Doe'],
        ];

        $this->assertEquals($expected, $result);
    }
}
