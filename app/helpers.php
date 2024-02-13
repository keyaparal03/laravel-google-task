<?php
use Illuminate\Support\Facades\Auth;

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
                'client_id' => env('GMAIL_CLIENT_ID'), // Replace with your client ID
                'client_secret' => env('GMAIL_CLIENT_SECRET'), // Replace with your client secret
                'refresh_token' => $refreshToken,
                'grant_type' => 'refresh_token',
            ],
        ]);

        $tokens = json_decode($response->getBody()->getContents(), true);

        if (isset($tokens['access_token'])) {
            return $accessToken = $tokens['access_token'];
            // Use the new access token
        } else {
            // Handle the error
            echo 'Could not refresh the access token.';
        }
    }
}
if (! function_exists('getAccessToken')) {
    function getAccessToken() {
        try{
            $access_token = Auth::user()->access_token;
            $refresh_token = Auth::user()->refresh_token;
     
            $server_output = guzzle_get('https://tasks.googleapis.com/tasks/v1/users/@me/lists', ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $access_token]);
            return $access_token;

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            if ($e->getResponse()->getStatusCode() == 401) {
               

                $client = new \GuzzleHttp\Client();
      
                $response = $client->post('https://oauth2.googleapis.com/token', [
                    'form_params' => [
                        'client_id' => env('GMAIL_CLIENT_ID'), // Replace with your client ID
                        'client_secret' => env('GMAIL_CLIENT_SECRET'), // Replace with your client secret
                        'refresh_token' => $refresh_token,
                        'grant_type' => 'refresh_token',
                    ],
                ]);

                $tokens = json_decode($response->getBody()->getContents(), true);

                if (isset($tokens['access_token'])) {
                    return $accessToken = $tokens['access_token'];
                    // Use the new access token
                } else {
                    // Handle the error
                    return '';
                }


            } else {
                // Handle other exceptions
                throw $e;
            }
        }
        
    }
}