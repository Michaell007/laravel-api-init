<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
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
            'direction_id' => 'required',
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Erreur de validation.',
                'data' => $validate->errors(),
            ], 403);
        }

        $direction = Service::create([
            'libelle' => $request->libelle,
            'description' => $request->description ?? NULL,
            'direction_id' => $request->direction_id,
        ]);

        $response = [
            'status' => true,
            'message' => 'Service est créé avec succès.'
        ];

        return response()->json($response, 201);
    }

    public function all_services(Request $request) {
        $services = Service::with('direction')->get(); // ->sortByDesc("id")

        $response = [
            'status' => true,
            'data' => $services
        ];

        return response()->json($response, 201);
    }

    public function update_service(Request $request) {
        $validate = Validator::make($request->all(), [
            'libelle' => 'required|string|max:250',
            'direction_id' => 'required',
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Erreur de validation.',
                'data' => $validate->errors(),
            ], 403);
        }

        $service = Service::find($request->id);
        if (!$service) {
            return response()->json([
                'status' => false,
                'message' => 'Identifiant inconnu.'
            ], 403);
        }

        $service->libelle = $request->libelle;
        $service->description = $request->description ?? NULL;
        $service->direction_id = $request->direction_id;
        $service->save();

        $response = [
            'status' => true,
            'message' => "Mise à jour Ok"
        ];

        return response()->json($response, 201);
    }


}
