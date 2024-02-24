<?php

namespace App\Http\Controllers;

use App\Models\Direction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DirectionController extends Controller
{

    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) {
        $validate = Validator::make($request->all(), [
            'libelle' => 'required|string|max:250|unique:agences',
            'agence_id' => 'required',
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Erreur de validation.',
                'data' => $validate->errors(),
            ], 403);
        }

        $agence = Direction::create([
            'libelle' => $request->libelle,
            'description' => $request->description ?? NULL,
            'agence_id' => $request->agence_id,
        ]);

        $response = [
            'status' => true,
            'message' => 'Direction est créé avec succès.'
        ];

        return response()->json($response, 201);
    }

    public function all_directions(Request $request) {
        $directions = Direction::with('agence')->get(); //->sortByDesc("id")

        $response = [
            'status' => true,
            'data' => $directions
        ];

        return response()->json($response, 201);
    }

}
