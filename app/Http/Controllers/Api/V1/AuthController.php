<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Cores\ApiResponse;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Resources\Api\V1\LoginResource;
use Facades\App\Repositories\UserRepository;
use Facades\App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return $this->responseJson('error', 'Unauthorized. Email not found', '', 401);
        }

        if (! Auth::attempt($request->validated())) {
            return $this->responseJson('error', 'Unauthorized.', '', 401);
        }

        $data = new LoginResource($user);

        return $this->responseJson(
            'success',
            __('Login successfully'),
            $data
        );
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data = UserRepository::registerUser($data);

        return $this->responseJson(
            $data['status'] ? 'success' : 'error',
            $data['message'],
            '',
            $data['status'] ? 201 : 500
        );
    }
}
