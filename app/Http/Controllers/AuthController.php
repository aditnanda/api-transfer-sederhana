<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Client as OClient; 
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

     /**
     * @OA\Post(
     *      path="/api/auth/login",
     *      operationId="loginAuth",
     *      tags={"Auth"},
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

     public function login(Request $request) { 
        $validator = Validator::make($request->all(), [
            'username'     => 'required',
            'password'  => 'required'
        ]);

        //if validation fails
        if ($validator->fails()) {
            
            $valid = array(
                'error' => $validator->errors(),
            );

            return response()->json($valid, 401);
        }
        
         if (Auth::attempt(['email' => request('username'), 'password' => request('password')])) { 
             return $this->getTokenAndRefreshToken(request('username'), request('password'));
         } 
         else { 
             return response()->json(['error'=>'Unauthorised'], 401); 
         } 
     }

     /**
     * @OA\Post(
     *      path="/api/auth/register",
     *      operationId="registerAuth",
     *      tags={"Auth"},
     *      summary="Register Auth",
     *      description="Register Auth",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *
     *                @OA\Property(
     *                    property="email",
     *                    type="string",
     *                    example="aditya.nanda0030@gmail.com"
     *                ),
     *                @OA\Property(
     *                    property="password",
     *                    type="string",
     *                    example="12345678"
     *                ),
     *                @OA\Property(
     *                    property="name",
     *                    type="string",
     *                    example="Aditya Nanda"
     *                ),
     *
     *              )
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successful operation"),
     * )
     */

     public function register(Request $request) { 
        $validator = Validator::make($request->all(), [
            'email'     => 'required|unique:users',
            'password'  => 'required',
            'name'  => 'required',
        ], [
            'email.unique' => 'Username/email sudah pernah digunakan di akun lain silahkan menggunakan username/email lain',
        ]);

        //if validation fails
        if ($validator->fails()) {
            
            $valid = array(
                'error' => $validator->errors(),
            );

            return response()->json($valid, 401);
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'name' => $request->name
        ]);
        
        if ($user) {
            # code...
            return response()->json('Berhasil membuat pengguna baru',200);
        }else{
            return response()->json('Gagal membuat pengguna baru',200);

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
     *      tags={"Auth"},
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
        return response()->json(auth()->user(),200);
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
     *      tags={"Auth"},
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
    
    public function update_token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token'     => 'required',
        ]);

        //if validation fails
        if ($validator->fails()) {
            
            $valid = array(
                'error' => $validator->errors(),
            );

            return response()->json($valid, 401);
        }
        $refresh_token = request('token');
        $oClient = OClient::where('password_client', 1)->first();
        try {
            //code...
            $response = Http::post(url('/oauth/token'), 
                [
                    'grant_type' => 'refresh_token',
                    'client_id' => $oClient->id,
                    'client_secret' => $oClient->secret,
                    'refresh_token' => $refresh_token,
                    'scope' => '*',
                ]
            );
            $result = $response->json();
            return response()->json($result, 200);
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
        return response()->json($result, 200);
    }
}
