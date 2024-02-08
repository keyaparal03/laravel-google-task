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
if (! function_exists('refreshAccessToken')) {
    function refreshAccessToken($refreshToken) {
        $client = new \GuzzleHttp\Client();

        $response = $client->post('https://oauth2.googleapis.com/token', [
            'form_params' => [
                'client_id' => 'your-client-id', // Replace with your client ID
                'client_secret' => 'your-client-secret', // Replace with your client secret
                'refresh_token' => $refreshToken,
                'grant_type' => 'refresh_token',
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}