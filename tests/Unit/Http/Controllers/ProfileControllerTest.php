<?php

namespace Http\Controllers;

use App\Http\Controllers\ProfileController;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{

    protected ProfileController $controller;

    public function setUp(): void
    {
        $this->controller = new ProfileController();
        parent::setUp();
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
}
