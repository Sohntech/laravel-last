<?php
// namespace App\Services;

// use App\Repositories\RepositoryReferenciel;
// use Illuminate\Support\Facades\Storage;

// class ReferencielService
// {
//     protected $repositoryReferenciel;

//     public function __construct(RepositoryReferenciel $repositoryReferenciel)
//     {
//         $this->repositoryReferenciel = $repositoryReferenciel;
//     }

//     public function createReferencielWithPhoto(array $data, $photo)
//     {
//         // Récupérer le bucket Firebase Storage
//         $bucket = app('firebase.storage')->getBucket(); // Utilise le bucket par défaut de Firebase Storage

//         // Générer un nom unique pour la photo
//         $fileName = 'referentiels/' . uniqid() . '/' . $photo->getClientOriginalName();

//         // Uploader la photo dans Firebase Storage
//         $bucket->upload(
//             file_get_contents($photo->getRealPath()),
//             [
//                 'name' => $fileName,
//             ]
//         );

//         // Récupérer le lien public de la photo
//         $photoUrl = $bucket->object($fileName)->signedUrl(new \DateTime('+1 year'));

//         // Ajouter le lien de la photo dans les données
//         $data['photoUrl'] = $photoUrl;

//         // Créer le référentiel dans Firebase via le repository
//         return $this->repositoryReferenciel->createReferenciel($data);
//     }
// }



namespace App\Services;

use App\Repositories\RepositoryReferenciel;

class ReferencielService
{
    protected $repositoryReferenciel;

    public function __construct(RepositoryReferenciel $repositoryReferenciel)
    {
        $this->repositoryReferenciel = $repositoryReferenciel;
    }

    public function createReferencielWithPhoto(array $data, $photo)
    {
        // Assurez-vous que l'URL de Firebase Storage est correctement configurée
        $bucket = app('firebase.storage')->getBucket();
        // Ajoutez les vérifications et le traitement des erreurs si nécessaire
    }
}
