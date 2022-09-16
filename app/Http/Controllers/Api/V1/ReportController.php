<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Cores\ApiResponse;
use App\Http\Requests\Api\V1\ReportRequest;
use Illuminate\Http\Request;
use Facades\App\Repositories\ReportRepository;

class ReportController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $data = ReportRepository::datatable($request);
        if (isset($data['status']) && ! $data['status']) {
            return $this->responseJson('error', $data['message'], '', $data['code'] ?? 400);
        }

        return $this->responseJson(
            'pagination',
            __('Get list reports successfully'),
            $data,
            200,
            [$request->sortBy, $request->sort]
        );
    }

    public function store(ReportRequest $request)
    {
        $data = $request->validated();
        $data = ReportRepository::create($data);

        return $this->responseJson(
            $data['status'] ? 'success' : 'error',
            $data['message'],
            $data['status'] ? $data['data'] : '',
            $data['status'] ? 201 : ($data['code'] ?? 400),
        );
    }
}
