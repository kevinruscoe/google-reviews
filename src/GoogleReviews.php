<?php

namespace KevinRuscoe;

class GoogleReviews
{
    /**
     * The API key.
     * 
     * @var string
     */
    private $apiKey;

    /**
     * The location of the cache.
     * 
     * @var string
     */
    private $cacheLocation = './cache';

    /**
     * The Cache.
     * 
     * @var \Wruczek\PhpFileCache\PhpFileCache
     */
    private $cache;

    /**
     * The API key.
     * @return GoogleReviews
     */
    public function __construct($apiKey = null)
    {
        if (is_null($apiKey)) {
            throw new \Exception('A Google API key is required.');
        }

        $this->apiKey = $apiKey;

        $this->cache = new \Wruczek\PhpFileCache\PhpFileCache($this->cacheLocation);

        return $this;
    }

    /**
     * Get the reviews from Google Places.
     * 
     * @param string $placeId
     * @param int $cacheFor
     * 
     * @return GoogleReviews
     */
    public function getReviewsForPlace($placeId = null, $cacheFor = 86400)
    {
        if (is_null($placeId)) {
            throw new \Exception('A Place ID is required.');
        }

        $url = sprintf("%s?%s", "https://maps.googleapis.com/maps/api/place/details/json", http_build_query([
            'placeid' => $placeId,
            'key' => $this->apiKey,
            'fields' => implode(",", [
                'rating',
                'reviews',
                'user_ratings_total'
            ])
        ]));

        $response = $this->cache->refreshIfExpired($url, function () use ($url) {
            $response = \Zttp\Zttp::get($url);

            return $response->json();
        }, $cacheFor);

        if (isset($response['error_message'])) {
            throw new \Exception(sprintf('Google replied with "%s".', $response['error_message']));
        }

        return $response['result'];
    }
}
