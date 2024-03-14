<?php

namespace Tests\Unit;

use App\Services\TokenSetupService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TokenSetupServiceTest extends TestCase
{
    use RefreshDatabase; // データベースをリセットして、テストの影響を分離します

    /** 
     * @test
     */
    public function createが正しくトークンを作成する()
    {
        $credentials = [
            'email' => 'admin@example.com',
            'password' => 'password',
        ];

        $service = new TokenSetupService($credentials);


        $tokens = $service->create();

        // ユーザーがデータベースに作成されたことを検証
        $this->assertDatabaseHas('users', [
            'email' => $credentials['email'],
        ]);

        // トークンが正しく返されていることを検証
        $this->assertIsArray($tokens);
        $this->assertCount(3, $tokens);
        $this->assertArrayHasKey('admin', $tokens);
        $this->assertArrayHasKey('update', $tokens);
        $this->assertArrayHasKey('basic', $tokens);


        // 各トークンの最初の10文字をコンソールに出力
        echo "\n########################################";
        echo "\n";
        echo "\nACTUAL TOKENS LOOKS LIKE:";
        echo "\n";
        echo "\nAdmin:\t" . substr($tokens['admin'], 0, 10) . "...";
        echo "\nUpdate:\t" . substr($tokens['update'], 0, 10) . "...";
        echo "\nBasic:\t" . substr($tokens['basic'], 0, 10) . "...";
        echo "\n";
        echo "\n########################################";
        echo "\n";
    }
}
