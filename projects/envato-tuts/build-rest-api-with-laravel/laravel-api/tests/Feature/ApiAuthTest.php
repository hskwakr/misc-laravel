<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    /** @test */
    public function 未認証ユーザがアクセスに失敗する()
    {
        // 未認証の状態で保護されたルートにアクセスを試みる
        $response = $this->getJson('/api/v1/customers');

        // 401 Unauthorizedが返されることを確認
        $response->assertStatus(401);
    }

    /** @test */
    public function 認証済みユーザーがアクセスに成功する()
    {
        // 認証済みユーザーを作成し、Sanctumトークンを生成
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // 認証済みユーザーとして保護されたルートにアクセスを試みる
        $response = $this->getJson('/api/v1/customers');

        // レスポンスが成功している（例：200 OK）ことを確認
        $response->assertSuccessful();
    }
}
