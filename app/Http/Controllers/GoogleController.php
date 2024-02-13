<?php
namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    public function GetAccessToken($client_id, $redirect_uri, $client_secret, $code) {	
       
        $client = new \GuzzleHttp\Client();

        $response = $client->post('https://www.googleapis.com/oauth2/v4/token', [
            'form_params' => [
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'redirect_uri' => $redirect_uri,
                'grant_type' => 'authorization_code',
                'code' => $code,
            ],
        ]);

        $tokens = json_decode($response->getBody()->getContents(), true);

        // $accessToken = $tokens['access_token'];
        // $refreshToken = $tokens['refresh_token'];
        
        return $tokens;
    }
    public function GetUserProfileInfo($access_token) {	
        $url = 'https://www.googleapis.com/oauth2/v2/userinfo?fields=name,email,gender,id,picture,verified_email';	
        
        $ch = curl_init();		
        curl_setopt($ch, CURLOPT_URL, $url);		
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);		
        if($http_code != 200) 
            throw new Exception('Error : Failed to get user information');
            
        return $data;
    }
    
    public function signInwithGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    public function callbackToGoogle()
    {
        if(isset($_GET['code'])) {
            try {

                $data = $this->GetAccessToken( env('GMAIL_CLIENT_ID'),  env('GMAIL_REDIRECT'),  env('GMAIL_CLIENT_SECRET'), $_GET['code']);
               
                // Access Token
                $access_token = $data['access_token'];
                $refresh_token = $data['refresh_token'];
                
                
                // Get user information
                $user_info = $this->GetUserProfileInfo($access_token);

                //print_r($user_info['id']);

                if($user_info['id'])
                {
                    $user = User::where('gauth_id', $user_info['id'])->first();
                    
                    if($user)
                    {
                        /**
                         * If user already exists then update the access token
                         */
                        $user->access_token = $access_token;
                        $user->save();
                        Auth::login($user);
                        return redirect()->to('/dashboard');
                    }
                    else
                    {
                        /**
                         * If user does not exists then create a new user
                         */
                        $newUser = User::create([
                            'name' => $user_info['name'],
                            'email' => $user_info['email'],
                            'gauth_id' => $user_info['id'],
                            'gauth_type' => 'google',
                            'password' => encrypt('123456dummy'),
                            'access_token' => $access_token,
                            'refresh_token' => $refresh_token
                        ]);
                        
                        Auth::login($newUser);
                        return redirect()->to('/dashboard');
                    }
                }
            }
            catch(Exception $e) {
                echo $e->getMessage();
                exit();
            }
        }


    }
}