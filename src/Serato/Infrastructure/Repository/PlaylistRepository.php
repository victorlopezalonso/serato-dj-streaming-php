<?php

namespace Src\Serato\Infrastructure\Repository;

interface PlaylistRepository
{
    public function findAll(string $username): array;

    public function findOne(string $username, string $playlistName): array;

    public function getLivePlaylist(string $username): array;

    public function getLiveLastPlayedTrack(string $username): string;
}
