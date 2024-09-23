<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReferencielResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'libelle' => $this->libelle,
            'description' => $this->description,
            'photoUrl' => $this->photoUrl,
            'statut' => $this->statut,
            'competences' => $this->competences ?? [],
        ];
    }
}
