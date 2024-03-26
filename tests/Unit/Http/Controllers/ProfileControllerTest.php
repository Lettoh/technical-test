<?php

namespace Http\Controllers;

use App\Http\Controllers\ProfileController;
use App\Http\Resources\ProfileResource;
use App\Models\Administrator;
use App\Models\Profile;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{

    protected ProfileController $controller;
    protected Administrator $administrator;


    public function setUp(): void
    {
        $this->controller = new ProfileController();
        parent::setUp();

        $this->administrator = Administrator::factory()->create();
    }

    public function test_index_returns_list_of_all_profiles_with_active_status()
    {
        Profile::factory()->count(2)->create(['status' => 'active']);
        Profile::factory()->count(4)->create(['status' => 'inactive']);
        Profile::factory()->count(3)->create(['status' => 'awaiting']);

        $this->assertCount(9, Profile::all());

        $json_resource = $this->controller->index();

        $this->assertCount(2, $json_resource);
    }

    public function test_index_returns_collection_of_ProfileResource_resource()
    {
        Profile::factory()->count(2)->create(['status' => 'active']);

        $collection = $this->controller->index();

        $this->assertInstanceOf(Collection::class, $collection->resource);

        $this->assertInstanceOf(ProfileResource::class, $collection[0]);
        $this->assertInstanceOf(ProfileResource::class, $collection[1]);
    }

    public function test_store_add_new_profile_entry_if_admin()
    {
        $request = [
            'last_name' => 'Doe',
            'first_name' => 'John',
            'status' => 'awaiting'
        ];

        $this->actingAs($this->administrator);

        $response = $this->post('/api/profiles', $request);
        $data = $response->getData();

        $this->assertCount(1, Profile::all());
        $this->assertDatabaseHas('profiles', [
            'last_name' => 'Doe',
            'first_name' => 'John',
            'status' => 'awaiting'
        ]);

        $this->assertEquals("Doe", $data->last_name);
        $this->assertEquals("John", $data->first_name);
        $this->assertEquals("awaiting", $data->status);
    }

    public function test_store_throw_forbidden_code_if_not_logged_in()
    {
        $request = [
            'last_name' => 'Doe',
            'first_name' => 'John',
            'status' => 'awaiting'
        ];

        $response = $this->post('/api/profiles', $request);

        $this->assertEquals(403, $response->status());
        $this->assertDatabaseMissing('profiles', [
            'last_name' => 'Doe',
            'first_name' => 'John',
            'status' => 'awaiting'
        ]);
    }

    public function test_update_can_update_a_profile_if_admin()
    {
        $profile = Profile::factory()->create([
            'last_name' => 'Doe',
            'first_name' => 'John',
            'status' => 'awaiting'
        ]);

        $request = [
            'status' => 'active'
        ];

        $this->actingAs($this->administrator);

        $response = $this->patch('/api/profiles/'.$profile->id, $request);
        $data = $response->getData();

        $this->assertEquals($profile->id, $data->id);
        $this->assertEquals("active", $data->status);

        $this->assertDatabaseMissing("profiles", [
            'last_name' => 'Doe',
            'first_name' => 'John',
            'status' => 'awaiting'
        ]);
        $this->assertDatabaseHas("profiles", [
            'last_name' => 'Doe',
            'first_name' => 'John',
            'status' => 'active'
        ]);
    }

    public function test_update_throw_forbidden_code_if_not_logged_in()
    {
        $profile = Profile::factory()->create([
            'last_name' => 'Doe',
            'first_name' => 'John',
            'status' => 'awaiting'
        ]);

        $request = [
            'status' => 'active'
        ];

        $response = $this->patch('/api/profiles/'.$profile->id, $request);
        $this->assertEquals(403, $response->status());

        $this->assertDatabaseHas("profiles", [
            'last_name' => 'Doe',
            'first_name' => 'John',
            'status' => 'awaiting'
        ]);
    }

    public function test_delete_delete_a_profile_if_admin()
    {
        $profile = Profile::factory()->create([
            'last_name' => 'Doe',
            'first_name' => 'John',
            'status' => 'awaiting'
        ]);

        $this->actingAs($this->administrator);

        $this->assertDatabaseHas('profiles', [
            'last_name' => 'Doe',
            'first_name' => 'John',
            'status' => 'awaiting'
        ]);

        $response = $this->delete('/api/profiles/'.$profile->id);

        $this->assertEquals(200, $response->status());
        $this->assertDatabaseMissing('profiles', [
            'last_name' => 'Doe',
            'first_name' => 'John',
            'status' => 'awaiting'
        ]);
    }

    public function test_delete_throw_forbidden_code_if_not_logged_in()
    {

        $profile = Profile::factory()->create([
            'last_name' => 'Doe',
            'first_name' => 'John',
            'status' => 'awaiting'
        ]);

        $this->assertDatabaseHas('profiles', [
            'last_name' => 'Doe',
            'first_name' => 'John',
            'status' => 'awaiting'
        ]);

        $response = $this->delete('/api/profiles/'.$profile->id);

        $this->assertEquals(403, $response->status());
        $this->assertDatabaseHas('profiles', [
            'last_name' => 'Doe',
            'first_name' => 'John',
            'status' => 'awaiting'
        ]);
    }
}
