<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $profiles = Profile::where('status', 'active')->get();

        return ProfileResource::collection($profiles);
    }

    public function store(Request $request)
    {
        if (!Auth::user()) abort(403);

        $data = $request->validate([
            'last_name' => 'string|required',
            'first_name' => 'string|required',
            'img' => 'file|nullable',
            'status' => [Rule::in(['active', 'inactive', 'awaiting'])],
        ]);

        return Profile::create($data);
    }

    public function update(Request $request, Profile $profile)
    {
        if (!Auth::user()) abort(403);

        $data = $request->validate([
            'last_name' => 'string|filled',
            'first_name' => 'string|filled',
            'img' => 'file|filled',
            'status' => [
                'filled',
                Rule::in(['active', 'inactive', 'awaiting'])
            ],
        ]);

        $profile->update($data);

        return $profile;
    }

    public function destroy(Profile $profile)
    {
        if (!Auth::user()) abort(403);

        $profile->delete();

        return 200;
    }
}
