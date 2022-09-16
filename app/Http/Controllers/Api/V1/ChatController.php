<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Cores\ApiResponse;
use App\Http\Requests\Api\V1\ChatRequest;
use Illuminate\Http\Request;
use Facades\App\Repositories\ChatRepository;

class ChatController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $data = ChatRepository::datatable($request);
        if (isset($data['status']) && ! $data['status']) {
            return $this->responseJson('error', $data['message'], '', $data['code'] ?? 400);
        }

        return $this->responseJson(
            'pagination',
            __('Get list chats successfully'),
            $data,
            200,
            [$request->sortBy, $request->sort]
        );
    }

    public function store(ChatRequest $request)
    {
        $data = $request->validated();
        $data = ChatRepository::create($data);

        return $this->responseJson(
            $data['status'] ? 'success' : 'error',
            $data['message'],
            $data['status'] ? $data['data'] : '',
            $data['status'] ? 201 : ($data['code'] ?? 400),
        );
    }
}
