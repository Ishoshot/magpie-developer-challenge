<?php

namespace App;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeHelper
{
    /**
     * Fetches and returns a Symfony Crawler instance for the given URL.
     *
     * @param string $url The URL to fetch.
     *
     * @return Crawler The Symfony Crawler instance.
     *
     * @throws \Exception If an error occurs during the HTTP request or while creating the Crawler.
     */
    public static function fetchDocument(string $url): Crawler
    {
        try {
            $client = new Client();

            $response = $client->get($url);

            return new Crawler($response->getBody()->getContents(), $url);
        } catch (GuzzleException $e) {
            // Handle Guzzle HTTP request exception
            throw new \Exception("Error fetching the document: {$e->getMessage()}", $e->getCode(), $e);
        } catch (\Exception $e) {
            // Handle other exceptions
            throw new \Exception("Error creating Crawler: {$e->getMessage()}", $e->getCode(), $e);
        }
    }
}
