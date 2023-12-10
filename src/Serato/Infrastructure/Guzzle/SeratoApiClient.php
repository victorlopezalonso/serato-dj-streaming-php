<?php

namespace Src\Serato\Infrastructure\Guzzle;

use DOMDocument;
use DOMXPath;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Src\Serato\Infrastructure\Repository\PlaylistRepository;

class SeratoApiClient implements PlaylistRepository
{
    public const PLAYLISTS_XPATH_QUERY = '//a[@class="playlist-title"]';
    public const PLAYLIST_TRACKS_XPATH_QUERY = '//div[@class="playlist-trackname"]';
    public const SERATO_PLAYLISTS_BASE_URL = 'https://serato.com/playlists';
    public const SHOW_PLAYLIST_TITLE_SEPARATOR = false;

    public function __construct(private readonly Client $client)
    {
    }

    private function parseDom(string $html, string $xpathQuery): mixed
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($html);

        $xpath = new DOMXPath($dom);

        return $xpath->query($xpathQuery);
    }

    /**
     * @throws Exception|GuzzleException
     */
    private function get(string $username, ?string $uri = null): ResponseInterface
    {
        $baseUrl = self::SERATO_PLAYLISTS_BASE_URL. "/$username";

        return $this->client->get($baseUrl . $uri);
    }

    /**
     * @throws Exception|GuzzleException
     */
    public function findAll(string $username): array
    {
        $response = $this->get($username);

        $nodes = $this->parseDom($response->getBody()->getContents(), self::PLAYLISTS_XPATH_QUERY);

        $result = [];

        foreach ($nodes as $node) {
            $title = self::SHOW_PLAYLIST_TITLE_SEPARATOR ? $node->nodeValue : explode('|', $node->nodeValue)[1];
            $url = preg_replace('/\/playlists\/\w+/', '$1', $node->attributes->getNamedItem('href')->nodeValue);

            $result[] = [
                'title' => trim(str_replace("&amp;", "&", $title)),
                'url' => $url,
            ];
        }

        return $result;
    }

    /**
     * @throws GuzzleException
     */
    public function findOne(string $username, string $playlistName): array
    {
        $response = $this->get($username, $playlistName);

        $nodes = $this->parseDom($response->getBody()->getContents(), self::PLAYLIST_TRACKS_XPATH_QUERY);

        $result = [];

        foreach ($nodes as $node) {
            $result[] = trim(str_replace("&amp;", "&", $node->nodeValue));
        }

        return $result;
    }

    /**
     * @throws Exception | GuzzleException
     */
    public function getLivePlaylist(string $username): array
    {
        $response = $this->get($username, '/live');

        $nodes = $this->parseDom($response->getBody()->getContents(), self::PLAYLIST_TRACKS_XPATH_QUERY);

        $result = [];

        foreach ($nodes as $node) {
            $result[] = trim(str_replace("&amp;", "&", $node->nodeValue));
        }

        return $result;

    }

    /**
     * @throws Exception
     */
    public function getLiveLastPlayedTrack(string $username): string
    {
        $tracks = $this->getLivePlaylist($username);

        return end($tracks);
    }
}
