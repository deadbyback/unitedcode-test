<?php

namespace App\Console\Models;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractLaravelNewsImporter implements LaravelNewsImporterInterface
{
    /**
     * @param string $url
     * @return string|null
     * @throws GuzzleException
     */
    protected function sendRequest(string $url): ?string
    {
        $client = new Client([
            'timeout'   => 10,
            'verify'    => false
        ]);
        $response = $client->get($url);

        if ($response->getReasonPhrase() === 'OK') {
            return $response->getBody()->getContents();
        }

        return null;
    }

    /**
     * Check is content available
     * @param Crawler $node
     * @return bool
     */
    protected function hasContent(Crawler $node): bool
    {
        return $node->count() > 0;
    }
}
