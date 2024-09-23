<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReferencielRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Autorisation pour tous les utilisateurs
    }

    public function rules()
    {
        return [
            'code' => 'required|string|unique:referentiels,code',
            'libelle' => 'required|string|unique:referentiels,libelle',
            'description' => 'required|string',
            'photo' => 'required|image|max:2048', // Taille maximale de 2MB pour l'image
            'statut' => 'required|in:Actif,Inactif,Archiver',
            // Ajoutez ici d'autres validations pour les compétences, etc.
        ];
    }
}
