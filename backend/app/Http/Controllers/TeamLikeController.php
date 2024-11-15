<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TeamLike\Like;
use App\Repositories\TeamLikeRepository;
use App\Models\TeamLike;
use Illuminate\Http\JsonResponse;

class TeamLikeController extends Controller
{

    /**
     * @var App\Repositories\TeamLikeRepository
     */
    protected $teamLikeRepository;

    public function __construct()
    {
        $this->teamLikeRepository = new TeamLikeRepository;
    }

    public function like(Like $request): JsonResponse
    {
        $userId = auth()->id();

        $data = [
            'team_id' => $request->input('team_id'),
            'game_id' => $request->input('game_id'),
            'user_id' => $userId,
        ];

        $existingLike = TeamLike::where('team_id', $data['team_id'])
            ->where('game_id', $data['game_id'])
            ->where('user_id', $userId)
            ->first();

        if ($existingLike) {
            $response = $this->teamLikeRepository->unlike($existingLike->id);
        } else {
            $response = $this->teamLikeRepository->like($data);
        }

        return $this->teamLikeRepository->getJsonResponse($response);
    }
}
