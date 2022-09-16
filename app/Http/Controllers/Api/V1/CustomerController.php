<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Cores\ApiResponse;
use Illuminate\Http\Request;
use Facades\App\Repositories\CustomerRepository;

class CustomerController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $data = CustomerRepository::datatable($request);
        if (isset($data['status']) && ! $data['status']) {
            return $this->responseJson('error', $data['message'], '', $data['code'] ?? 400);
        }

        return $this->responseJson(
            'pagination',
            __('Get list customers successfully'),
            $data,
            200,
            [$request->sortBy, $request->sort]
        );
    }

    public function destroy($id)
    {
        $data = CustomerRepository::deleteById($id);

        return $this->responseJson(
            $data['status'] ? 'success' : 'error',
            $data['message'],
            $data['status'] ? $data['data'] : '',
            $data['status'] ? 200 : ($data['code'] ?? 400),
        );
    }
}
