<?php

if (! function_exists('guzzle_get')) {
    function guzzle_get($url, $headers = [])
    {
        $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);

        $response = $client->get($url);

        return json_decode($response->getBody()->getContents(), true);
    }
}

if (! function_exists('guzzle_post')) {
    function guzzle_post($url, $data = [], $headers = [])
    {
        $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);

        $response = $client->post($url, [
            'json' => $data
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
?>