<?php

namespace App\Repositories;

use Facades\App\Models\Message;
use App\Resources\Api\V1\MessageResource;
use App\Traits\DatatableTrait;
use Facades\App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MessageRepository extends BaseRepository
{
    use DatatableTrait;

    /**
     * Validate get message
     *
     * @param \App\Models\Chat::id $chatId
     * @return array
     */
    private function validate($chatId)
    {
        $chat = Chat::find($chatId);
        if (!$chat) {
            return $this->setResponse(false, __("Chat not found"), '', '', 404);
        }

        $user = auth()->user();
        if ($user->id != $chat->user_id1 && $user->id != $chat->user_id2) {
            return $this->setResponse(false, __("You don't have permission"), '', '', 403);
        }

        return $this->setResponse(true, __('Ok'));
    }

    /**
     * Get Datatables Messages
     *
     * @param int $chatId
     * @param \Illuminate\Http\Request $request
     * @return Json|array
     */
    public function datatable($chatId, Request $request)
    {
        try {
            $validate = $this->validate($chatId);
            if (!$validate['status']) {
                return $validate;
            }

            $query = Message::with(['author', 'chat']);
            $filters = [
                [
                    'field' => 'chat_id',
                    'value' => $chatId,
                ],
            ];
            $request->sortBy = $request->sortBy ?? 'id';
            $request->sort = $request->sort ?? -1;
            $data = $this->filterDatatable($query, $filters, $request);

            return MessageResource::collection($data);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);

            return $this->setResponse(false, __('Failed get messages'));
        }
    }

    /**
     * Create Message
     *
     * @param int $chatId
     * @return Json|array
     */
    public function create($chatId, $data)
    {
        \DB::beginTransaction();
        try {
            $validate = $this->validate($chatId);
            if (!$validate['status']) {
                return $validate;
            }

            $data['chat_id'] = $chatId;
            $message = Message::create($data);
            $data = new MessageResource($message);

            \DB::commit();

            return $this->setResponse(true, __('Create message successfully'), $data);
        } catch (\Throwable $th) {
            //throw $th;
            \DB::rollback();
            Log::error($th);

            return $this->setResponse(false, __('Create message failed'));
        }
    }
}
