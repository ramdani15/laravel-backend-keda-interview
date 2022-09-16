<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Cores\ApiResponse;
use App\Http\Requests\Api\V1\MessageRequest;
use Illuminate\Http\Request;
use Facades\App\Repositories\MessageRepository;

class MessageController extends Controller
{
    use ApiResponse;

    public function index($chatId, Request $request)
    {
        $data = MessageRepository::datatable($chatId, $request);
        if (isset($data['status']) && ! $data['status']) {
            return $this->responseJson('error', $data['message'], '', $data['code'] ?? 400);
        }

        return $this->responseJson(
            'pagination',
            __('Get list messages successfully'),
            $data,
            200,
            [$request->sortBy, $request->sort]
        );
    }

    public function store($chatId, MessageRequest $request)
    {
        $data = $request->validated();
        $data = MessageRepository::create($chatId, $data);

        return $this->responseJson(
            $data['status'] ? 'success' : 'error',
            $data['message'],
            $data['status'] ? $data['data'] : '',
            $data['status'] ? 201 : ($data['code'] ?? 400),
        );
    }
}
