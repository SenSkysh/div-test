<?php

namespace Tests\Feature;

use Carbon\Carbon;
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


    public function test_the_index(): void
    {
        TestModel::factory()->count(3)->create();

        $response = $this->getJson(self::$apiPath);
        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->getJson(self::$apiPath);

        $response->assertStatus(200);
        $response->assertJsonCount(3, "data");
    }

    public function test_the_index_by_status(): void
    {
        TestModel::factory()->count(2)->create(['status' => 'Active']);
        TestModel::factory()->count(1)->create(['status' => 'Resolved']);


        $response = $this->actingAs($this->user)->getJson(self::$apiPath.'?status=Active');
        $response->assertStatus(200);
        $response->assertJsonCount(2, "data");

        $response = $this->actingAs($this->user)->getJson(self::$apiPath.'?status=Resolved');
        $response->assertStatus(200);
        $response->assertJsonCount(1, "data");

        $response = $this->actingAs($this->user)->getJson(self::$apiPath.'?status=test');
        $response->assertStatus(422);
    }

    public function test_the_index_by_created(): void
    {

        $from = new Carbon('2016-01-23 11:53:20');
        $between = new Carbon('2016-01-23 12:53:20');
        $to = new Carbon('2016-01-23 13:53:20');

        TestModel::factory()->count(2)->create(['created_at' => Carbon::today()]);
        TestModel::factory()->count(1)->create(['created_at' => $between]);


        $response = $this->actingAs($this->user)->getJson(self::$apiPath.'?from='.$from->toDateTimeString());
        $response->assertStatus(200);
        $response->assertJsonCount(3, "data");

        $response = $this->actingAs($this->user)->getJson(self::$apiPath.'?to='.$to->toDateTimeString());
        $response->assertStatus(200);
        $response->assertJsonCount(1, "data");

        $response = $this->actingAs($this->user)->getJson(self::$apiPath.'?from='.$from->toDateTimeString().'&to='.$to->toDateTimeString());
        $response->assertStatus(200);
        $response->assertJsonCount(1, "data");
    }

    public function test_the_update(): void
    {

  
        $model = TestModel::factory()->create();

        $data =  [
            'comment' => fake()->paragraph(),
        ];

        $response = $this->putJson(self::$apiPath . '/'.$model->id, $data);
        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->putJson(self::$apiPath . '/'.$model->id, $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas(TestModel::class, $data);
    }

    



    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }








}
