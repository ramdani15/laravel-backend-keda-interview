<?php

namespace App\Repositories;

use Facades\App\Models\Chat;
use App\Resources\Api\V1\ChatResource;
use App\Traits\DatatableTrait;
use Facades\App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatRepository extends BaseRepository
{
    use DatatableTrait;

    /**
     * Get Datatables Chats
     *
     * @return Json|array
     */
    public function datatable(Request $request)
    {
        try {
            $user = auth()->user();
            $query = Chat::with(['user1', 'user2']);

            // Only own chat
            $query = $query->where(function ($q) use ($user) {
                $q->where('user_id1', $user->id)->orWhere('user_id2', $user->id);
            });

            $filters = [];
            $request->sortBy = $request->sortBy ?? 'id';
            $request->sort = $request->sort ?? -1;
            $data = $this->filterDatatable($query, $filters, $request);

            return ChatResource::collection($data);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);

            return $this->setResponse(false, __('Failed get chats'));
        }
    }

    /**
     * Create Chat
     *
     * @return Json|array
     */
    public function create($data)
    {
        \DB::beginTransaction();
        try {
            $user = auth()->user();
            $data['user_id1'] = $user->id;
            $reciever = User::findOrFail($data['user_id2']);

            $chat = Chat::where([
                'user_id1' => $user->id,
                'user_id2' => $reciever->id
            ])->first();
            if ($chat) {
                return $this->setResponse(false, __('Chat already exists'), '', '', 400);
            }

            // Reciever only for customer (for Customer role)
            if ($user->user_type_id == 1 && $reciever->user_type_id != 1) {
                return $this->setResponse(false, __('Only for customer'), '', '', 400);
            } elseif ($user->id == $reciever->id) {
                return $this->setResponse(false, __('Receiver invalid'), '', '', 400);
            }

            $chat = Chat::create($data);
            $data = new ChatResource($chat);

            \DB::commit();

            return $this->setResponse(true, __('Create chat successfully'), $data);
        } catch (\Throwable $th) {
            //throw $th;
            \DB::rollback();
            Log::error($th);

            return $this->setResponse(false, __('Create chat failed'));
        }
    }
}
