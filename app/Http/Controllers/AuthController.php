<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Laravel\Passport\Client as OClient; 
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public $successStatus = 200;


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

     /**
     * @OA\Post(
     *      path="/api/auth/login",
     *      operationId="loginAuth",
     *      tags={"auth"},
     *      summary="Login Auth",
     *      description="Login Auth",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *
     *                @OA\Property(
     *                    property="username",
     *                    type="string",
     *                    example="aditya.nanda0030@gmail.com"
     *                ),
     *                @OA\Property(
     *                    property="password",
     *                    type="string",
     *                    example="12345678"
     *                ),
     *
     *              )
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successful operation"),
     * )
     */

     public function login() { 
         if (Auth::attempt(['email' => request('username'), 'password' => request('password')])) { 
             return $this->getTokenAndRefreshToken(request('username'), request('password'));
         } 
         else { 
             return response()->json(['error'=>'Unauthorised'], 401); 
         } 
     }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */

     /**
     * @OA\Post(
     *      path="/api/auth/me",
     *      operationId="meAuth",
     *      tags={"auth"},
     *      summary="Me Auth",
     *      description="Me Auth",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successful operation"),
     *      security={{"bearerAuth" : {}}}

     * )
     */

    public function me()
    {
        return response()->json(auth()->user(),$this->successStatus);
    }


    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */

     /**
     * @OA\Post(
     *      path="/api/auth/update-token",
     *      operationId="updateTokenAuth",
     *      tags={"auth"},
     *      summary="Update Token Auth",
     *      description="Update Token Auth",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *
     *                @OA\Property(
     *                    property="token",
     *                    type="string",
     *                    example="dhaiuhqijdqjdowd"
     *                ),
     *
     *              )
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successful operation"),
     * )
     */
    
    public function update_token()
    {
        $refresh_token = request('token');
        $oClient = OClient::where('password_client', 1)->first();
        $http = new Client;
        try {
            //code...
            $response = $http->request('POST', url('/oauth/token'), [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'client_id' => $oClient->id,
                    'client_secret' => $oClient->secret,
                    'refresh_token' => $refresh_token,
                    'scope' => '*',
                ],
            ]);
            $result = json_decode((string) $response->getBody(), true);
            return response()->json($result, $this->successStatus);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json('Unathorized', 401);

        }
        
    }

 
    public function getTokenAndRefreshToken($username, $password) { 
        $oClient = OClient::where('password_client', 1)->first();
        $response = Http::post(url('/oauth/token'), [
            'grant_type' => 'password',
            'client_id' => $oClient->id,
            'client_secret' => $oClient->secret,
            'username' => $username,
            'password' => $password,
            'scope' => '*',
        ]);
        $result = $response->json();
        return response()->json($result, $this->successStatus);
    }
}
