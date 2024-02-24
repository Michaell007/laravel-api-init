<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgenceController extends Controller
{
    
    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) {
        $validate = Validator::make($request->all(), [
            'libelle' => 'required|string|max:250|unique:agences'
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Erreur de validation.',
                'data' => $validate->errors(),
            ], 403);
        }

        $agence = Agence::create([
            'libelle' => $request->libelle,
            'description' => $request->description ?? NULL
        ]);

        $response = [
            'status' => true,
            'message' => 'Agence est créé avec succès.'
        ];

        return response()->json($response, 201);
    }


    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function all_agences(Request $request) {
        $agences = Agence::all()->sortByDesc("createdAt");

        $response = [
            'status' => true,
            'data' => $agences
        ];

        return response()->json($response, 201);
    }

    public function update_agence(Request $request) {
        $validate = Validator::make($request->all(), [
            'libelle' => 'required|string|max:250|unique:agences'
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Erreur de validation.',
                'data' => $validate->errors(),
            ], 403);
        }

        $agence = Agence::find($request->id);
        if (!$agence) {
            return response()->json([
                'status' => false,
                'message' => 'Identifiant inconnu.'
            ], 403);
        }

        $agence->libelle = $request->libelle;
        $agence->description = $request->description ?? NULL;
        $agence->save();

        $response = [
            'status' => true,
            'message' => "Mise à jour Ok"
        ];

        return response()->json($response, 201);
    }

    public function delete_agence(Request $request, $id)
    {
        $agence = Agence::where("id", $id)->first();
        if (!$agence) {
            return response()->json([
                'status' => false,
                'message' => 'Identifiant inconnu.'
            ], 403);
        }
        $agence->delete();

        $response = [
            'status' => true,
            'message' => "Suppression Ok"
        ];

        return response()->json($response, 201);
    }

}
