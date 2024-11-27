<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MessageRepository extends JsonResponseFormat
{

    /**
     * Get all messages grouped by users for admin.
     *
     * @return array
     */
    public function retrieveAdmin(): array
    {
        $messages = Message::query()
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        $admins = User::whereIn('role_id', [1, 2])->get();
        $adminIds = $admins->pluck('id')->toArray();

        $groupedMessages = $messages->groupBy(function ($message) use ($adminIds) {
            return in_array($message->sender_id, $adminIds) ? $message->receiver_id : $message->sender_id;
        });

        $formattedConversations = $groupedMessages->map(function ($group, $userId) use ($admins) {
            $chatMate = User::find($userId)?->first_name;

            return [
                'chat_mate' => $chatMate,
                'chat_mate_id' => $userId,
                'recent_message_date' => $group->last()?->created_at ?? null,
                'messages' => $group->map(function ($message) {
                    $sender = $message->sender;
                    return [
                        'sender_id' => $message->sender_id,
                        'sender_role' => $sender->role->slug,
                        'sender_name' => $sender->first_name,
                        'receiver_id' => $message->receiver_id,
                        'content' => $message->content,
                        'read' => $message->read,
                        'created_at' => $message->created_at,
                        'updated_at' => $message->updated_at,
                    ];
                })->values(),
            ];
        })->values();

        return [
            'message' => 'Messages retrieved successfully',
            'body' => $formattedConversations,
            'total' => $formattedConversations->count(),
        ];
    }

    /**
     * Get all messages for a user, with admins grouped as a single chat.
     *
     * @return array
     */
    public function retrieveUser(): array
    {
        $user = auth()->user();

        $query = Message::query();
        $query->where(function ($q) use ($user) {
            $q->where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id);
        });

        $messages = $query->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        $messagesGrouped = $messages->map(function ($message) {
            return [
                'sender_id' => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'content' => $message->content,
                'read' => $message->read,
                'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $message->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        $formattedMessages = [
            [
                'chat_mate' => 'ADMIN',
                'chat_mate_id' => null,
                'recent_message_date' => $messages->first()?->created_at->format('Y-m-d H:i:s') ?? null,
                'messages' => $messagesGrouped,
            ]
        ];

        return [
            'message' => 'Messages retrieved successfully',
            'body' => $formattedMessages,
            'total' => 1,
        ];
    }

    /**
     * Send a new message.
     *
     * @param array $data
     * @return array
     */
    public function store(array $data): array
    {
        DB::beginTransaction();
        try {
            $message = Message::create([
                'sender_id' => $data['sender_id'],
                'receiver_id' => $data['receiver_id'],
                'content' => $data['content'],
            ]);

            DB::commit();
            return [
                'message' => 'Message sent successfully',
                'body' => $message
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Mark a message as seen.
     *
     * @param array $data
     * @return array
     */
    public function seen(array $data): array
    {
        try {
            $message = Message::findOrFail($data['id']);
            $message->update(['seen' => true]);

            return [
                'message' => 'Message marked as seen',
                'body' => $message
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Update an existing message.
     *
     * @param array $data
     * @return array
     */
    public function update(array $data): array
    {
        DB::beginTransaction();
        try {
            $message = Message::findOrFail($data['id']);
            $message->update([
                'content' => $data['content'],
            ]);

            DB::commit();
            return [
                'message' => 'Message updated successfully',
                'body' => $message,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Delete a message.
     *
     * @param array $data
     * @return array
     */
    public function delete(array $data): array
    {
        DB::beginTransaction();
        try {
            $message = Message::findOrFail($data['id']);
            $message->delete();

            DB::commit();
            return [
                'message' => 'Message deleted successfully',
                'body' => $message,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }
}
