<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Models\UploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\DemandeNoteFrais;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class DemandeNoteFraisController extends Controller
{
    
    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) {
        $validate = Validator::make($request->all(), [
            'description' => 'required|string|max:250',
            'montant' => 'required',
            'date_depense' => 'required',
            'user_id' => 'required',
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Erreur de validation.',
                'data' => $validate->errors(),
            ], 403);
        }

        $demande = DemandeNoteFrais::create([
            'description' => $request->description,
            'montant' => $request->montant,
            'user_id' => $request->user_id,
            'date_depense' => Carbon::parse($request->date_depense)
        ]);

        if ($request->has('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = time() . '_' . str_replace(" ", "", $file->getClientOriginalName());
                $fileSize = $file->getSize();
                $fileUrl = 'storage/' . $fileName;
                $pathDirectory = public_path('upload/conges');
                $uploadFile = new UploadFile();
                $uploadFile->file_name = $fileName;
                $uploadFile->file_type =  pathinfo($fileName, PATHINFO_EXTENSION);
                $uploadFile->demande_note_frais_id = $demande->id;
                $file->move($pathDirectory, $fileName);
                $uploadFile->save();
            }
        }

        Mail::to(Auth::user()->email)
            ->send(new SendMail('Confirmation de réception de votre demande (Note de frais)', Auth::user()->name ));

        $response = [
            'status' => true,
            'message' => 'Demande est créé avec succès.'
        ];

        return response()->json($response, 201);
    }

    public function all_frais(Request $request) {
        $conges = DemandeNoteFrais::with('user', 'upload_files')->get(); // ->sortByDesc("id")

        $response = [
            'status' => true,
            'data' => $conges
        ];

        return response()->json($response, 201);
    }

    public function attribution_frais(Request $request) {
        $validate = Validator::make($request->all(), [
            'validateur_id' => 'required|integer'
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Erreur de validation.',
                'data' => $validate->errors(),
            ], 403);
        }

        $demande = DemandeNoteFrais::find($request->id);
        if (!$demande) {
            return response()->json([
                'status' => false,
                'message' => 'Identifiant inconnu.'
            ], 403);
        }

        if ($demande->statut != 0) {
            return response()->json([
                'status' => false,
                'message' => "Attribution impossible."
            ], 403);
        }

        $demande->validateur_id = $request->validateur_id;
        $demande->date_attribution_validateur = Carbon::now();
        $demande->save();

        $response = [
            'status' => true,
            'message' => "Attribution éffectuée avec succès"
        ];

        return response()->json($response, 201);
    }

    public function validate_frais(Request $request) {
        
        $demande = DemandeNoteFrais::find($request->id);
        if (!$demande) {
            return response()->json([
                'status' => false,
                'message' => 'Identifiant inconnu.'
            ], 403);
        }

        if ($demande->statut != 0) {
            return response()->json([
                'status' => false,
                'message' => "Attribution impossible."
            ], 403);
        }

        $demande->statut = 2;
        $demande->etat = "Termine";
        $demande->save();

        $response = [
            'status' => true,
            'message' => "Autorisation Ok"
        ];

        return response()->json($response, 201);
    }

    public function refus_frais(Request $request) {
        
        $demande = DemandeNoteFrais::find($request->id);
        if (!$demande) {
            return response()->json([
                'status' => false,
                'message' => 'Identifiant inconnu.'
            ], 403);
        }

        if ($demande->statut != 0) {
            return response()->json([
                'status' => false,
                'message' => "Attribution impossible."
            ], 403);
        }

        $demande->statut = 3;
        $demande->etat = "Refuse";
        $demande->save();

        $response = [
            'status' => true,
            'message' => "Refus Ok"
        ];

        return response()->json($response, 201);
    }

}
