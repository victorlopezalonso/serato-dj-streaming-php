<?php

namespace Src\Serato\Infrastructure\Laravel\Controller;

use App\Http\Controllers\Controller;
use Src\Serato\Infrastructure\Repository\PlaylistRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class SeratoTrackListController extends Controller
{
    public function __construct(private readonly PlaylistRepository $trackListRepository)
    {
    }

    public function __invoke(): JsonResponse
    {
        $username = 'vla_dsound';

        $playlists = $this->trackListRepository->findAll($username);
        $livePlaylist = $this->trackListRepository->getLivePlaylist($username);
        $lastPlayedTrack = $this->trackListRepository->getLiveLastPlayedTrack($username);
        $firstPlaylist = $this->trackListRepository->findOne($username, end($playlists)['url'] ?? '');

        return response()->json([
            'playlists' => $playlists,
            'livePlaylist' => $livePlaylist,
            'lastPlayedTrack' => $lastPlayedTrack,
            'firstPlaylist' => $firstPlaylist,
        ], 200, [], JSON_PRETTY_PRINT);

        //        return view('welcome');
    }
}
