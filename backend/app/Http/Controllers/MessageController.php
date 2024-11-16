<?php

namespace App\Http\Controllers;

use App\Http\Requests\Message\Retrieve;
use App\Http\Requests\Message\Store;
use App\Http\Requests\Message\Update;
use App\Http\Requests\Message\Seen;
use App\Http\Requests\Message\Delete;
use App\Repositories\MessageRepository;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    /**
     * @var App\Repositories\MessageRepository
     */
    protected $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    /**
     * Retrieves all messages.
     *
     * @return Illuminate\Http\JsonResponse The message's data in JSON format.
     */
    public function retrieveAdmin(Retrieve $request): JsonResponse
    {
        $params = [
            'search' => $request->input('search'),
        ];

        $response = $this->messageRepository->retrieveAdmin($params);
        return $this->messageRepository->getJsonResponse($response);
    }

    /**
     * Retrieves all messages.
     *
     * @return Illuminate\Http\JsonResponse The message's data in JSON format.
     */
    public function retrieveUser(): JsonResponse
    {

        $response = $this->messageRepository->retrieveUser();
        return $this->messageRepository->getJsonResponse($response);
    }

    /**
     * Send a message.
     *
     * @return Illuminate\Http\JsonResponse The message's data in JSON format.
     */
    public function send(Store $request): JsonResponse
    {
        $userId = auth()->id();

        $data = [
            'sender_id' => $userId,
            'receiver_id' => $request->input('receiver_id'),
            'content' => $request->input('content'),
        ];

        $response = $this->messageRepository->store($data);
        return $this->messageRepository->getJsonResponse($response);
    }

    /**
     * Mark a message as seen.
     *
     * @return Illuminate\Http\JsonResponse The message's data in JSON format.
     */
    public function seen(Seen $request): JsonResponse
    {
        $data = [
            'id' => $request->input('id'),
        ];

        $response = $this->messageRepository->seen($data);
        return $this->messageRepository->getJsonResponse($response);
    }

    /**
     * Update a message.
     *
     * @return Illuminate\Http\JsonResponse The updated message's data in JSON format.
     */
    public function update(Update $request): JsonResponse
    {
        $data = [
            'id' => $request->input('id'),
            'content' => $request->input('content'),
        ];

        $response = $this->messageRepository->update($data);
        return $this->messageRepository->getJsonResponse($response);
    }

    /**
     * Delete a message.
     *
     * @return Illuminate\Http\JsonResponse The result of the delete action.
     */
    public function delete(Delete $request): JsonResponse
    {
        $data = [
            'id' => $request->input('id'),
        ];

        $response = $this->messageRepository->delete($data);
        return $this->messageRepository->getJsonResponse($response);
    }
}
