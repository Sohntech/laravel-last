<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'adresse' => $this->adresse,
            'telephone' => $this->telephone,
            'fonction' => $this->fonction,
            'email' => $this->email,
            'statut' => $this->statut,
            'photo' => $this->photo,
            'role_id' => $this->role_id,
            'role' => $this->when($this->role_id, function () {
                return new RoleResource($this->role);
            })
        ];
    }
}
