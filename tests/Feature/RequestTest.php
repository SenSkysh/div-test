<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Request as TestModel;

class RequestTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    


    static $apiPath = 'api/requests';
    
    private $user;

    public function test_the_store(): void
    {

        $data =  [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'message' => fake()->paragraph()
        ];
        
        
        $response = $this->postJson(self::$apiPath,$data);
        $response->assertStatus(201);
        $this->assertDatabaseHas(TestModel::class, $data);

    }




    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }








}
