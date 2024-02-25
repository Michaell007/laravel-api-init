<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\SendMail;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    
    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:250',
            'type_contrat' => 'required|string|max:250',
            'date_embauche_debut' => 'required',
            'email' => 'required|string|email:rfc,dns|max:250|unique:users,email',
            'password' => 'required|string|min:8|confirmed'
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Erreur de validation.',
                'data' => $validate->errors(),
            ], 403);
        }

        $user = User::create([
            'name' => $request->name,
            'type_contrat' => $request->type_contrat,
            'date_embauche_debut' => Carbon::parse($request->date_embauche_debut),
            'date_embauche_fin' => $request->date_embauche_fin ? Carbon::parse($request->date_embauche_fin) : NULL,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'service_id' => $request->service_id ?? NULL
        ]);

        $service = NULL;
        if ($request->service_id) {
            $service = Service::find($request->service_id);
            if (!$service) {
                return response()->json([
                    'status' => false,
                    'message' => 'Identifiant inconnu.'
                ], 403);
            }
        }

        $data['token'] = $user->createToken($request->email)->plainTextToken;
        $data['user'] = $user;
        $data['user']['service'] = $service ?? NUll;

        $response = [
            'status' => true,
            'message' => 'User est créé avec succès.',
            'data' => $data,
        ];

        return response()->json($response, 201);
    }

    /**
     * Authenticate the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
        $validate = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Validation Error !',
                'data' => $validate->errors(),
            ], 403);  
        }

        // Check email exist
        $user = User::where('email', $request->email)->first();

        // Check password
        if(!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
                ], 401);
        }

        $data['token'] = $user->createToken($request->email)->plainTextToken;
        $data['user'] = $user;
        
        $response = [
            'status' => true,
            'data' => $data,
        ];

        return response()->json($response, 200);
    }

    /**
     * Log out the user from application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request) {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'User is logged out successfully'
            ], 200);
    }

    public function one_user(Request $request, $id) {
        $user = User::where('id', $id)->with('service')->first();

        $response = [
            'status' => true,
            'data' => $user
        ];

        return response()->json($response, 201);
    }

}
