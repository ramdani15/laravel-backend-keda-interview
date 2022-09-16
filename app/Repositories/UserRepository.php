<?php

namespace App\Repositories;

use Facades\App\Models\User;
use App\Resources\Api\V1\UserResource;
use App\Traits\DatatableTrait;
use Illuminate\Support\Facades\Log;

class UserRepository extends BaseRepository
{
    use DatatableTrait;

    /**
     * Create / Register User
     *
     * @param  array  $data
     * @return array
     */
    public function registerUser($data)
    {
        \DB::beginTransaction();
        try {
            $data['password'] = bcrypt($data['password']);
            $user = User::create($data);
            $data = new UserResource($user);

            \DB::commit();

            return $this->setResponse(true, __('Register user successfully'), $data);
        } catch (\Throwable $th) {
            //throw $th;
            \DB::rollback();
            Log::error($th);

            return $this->setResponse(false, __('Register user user'), '', $th->getMessage(), 400);
        }
    }
}
