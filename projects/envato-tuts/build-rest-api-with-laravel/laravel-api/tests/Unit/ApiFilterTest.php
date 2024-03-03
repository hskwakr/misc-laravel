<?php

namespace Tests\Unit;

use App\Filters\ApiFilter;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class ApiFilterTest extends TestCase
{
    protected $apiFilter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->apiFilter = new class extends ApiFilter
        {
            protected $safeParams = [
                'name' => ['eq'],
                'postalCode' => ['gt', 'lt']
            ];

            protected $columnMap = [
                'postalCode' => 'postal_code'
            ];

            protected $operatorMap = [
                'eq' => '=',
                'gt' => '>',
                'lt' => '<',
            ];
        };
    }

    /** 
     * @test
     */
    public function transformが正しいクエリを返す()
    {
        $request = Request::create('/', 'GET', [
            'name' => ['eq' => 'John Doe'],
            'postalCode' => ['gt' => '1000', 'lt' => '2000'],
        ]);

        $result = $this->apiFilter->transform($request);

        $expected = [
            ['name', '=', 'John Doe'],
            ['postal_code', '>', '1000'],
            ['postal_code', '<', '2000'],
        ];

        $this->assertEquals($expected, $result);
    }

    /** 
     * @test
     */
    public function transformにパラメータを渡さなかったら空のクエリを返す()
    {
        $request = Request::create('/', 'GET');

        $result = $this->apiFilter->transform($request);

        $this->assertEmpty($result);
    }

    /** 
     * @test
     */
    public function transformがサポートされていないパラメータを無視する()
    {
        $request = Request::create('/', 'GET', [
            'unsupported' => ['eq' => 'value'],
            'name' => ['eq' => 'John Doe'],
        ]);

        $result = $this->apiFilter->transform($request);

        $expected = [
            ['name', '=', 'John Doe'],
        ];

        $this->assertEquals($expected, $result);
    }
}
