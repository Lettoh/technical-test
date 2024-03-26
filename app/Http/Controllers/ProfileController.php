<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $profiles = Profile::where('status', 'active')->get();

        return ProfileResource::collection($profiles);
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        //
    }

    public function edit(Profile $profile)
    {
        //
    }

    public function update(Request $request, Profile $profile)
    {
        //
    }

    public function destroy(Profile $profile)
    {
        //
    }
}
