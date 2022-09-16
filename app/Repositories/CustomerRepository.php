<?php

namespace App\Repositories;

use Facades\App\Models\User;
use App\Resources\Api\V1\UserResource;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerRepository extends BaseRepository
{
    use DatatableTrait;

    /**
     * Get Datatables Customers
     *
     * @return Json|array
     */
    public function datatable(Request $request)
    {
        try {
            $query = User::where('user_type_id', 1);
            $filters = [
                [
                    'field' => 'id',
                    'value' => $request->id,
                ],
                [
                    'field' => 'email',
                    'value' => $request->email,
                    'query' => 'like',
                ],
            ];
            $request->sortBy = $request->sortBy ?? 'id';
            $request->sort = $request->sort ?? -1;
            $data = $this->filterDatatable($query, $filters, $request);

            return UserResource::collection($data);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);

            return $this->setResponse(false, __('Failed get customers'));
        }
    }

    /**
     * Delete Customer By ID
     *
     * @return Json|array
     */
    public function deleteById($id)
    {
        \DB::beginTransaction();
        try {
            $user = User::where([
                'id' => $id,
                'user_type_id' => 1
            ])->first();
            if (!$user) {
                return $this->setResponse(false, __('Customer not found'));
            }

            $data = $user->delete();

            \DB::commit();

            return $this->setResponse(true, __('Delete customer successfully'), $data);
        } catch (\Throwable $th) {
            //throw $th;
            \DB::rollback();
            Log::error($th);

            return $this->setResponse(false, __('Register customer failed'));
        }
    }
}
