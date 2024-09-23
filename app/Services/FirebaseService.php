<?php


namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class FirebaseService
{
    protected $auth;
    protected $database;
    protected $storage;
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $factory = (new Factory)
            ->withServiceAccount(config('services.firebase.credentials'))
            ->withDatabaseUri(config('services.firebase.database_url')); // Pas besoin de withStorageBucket()

        $this->auth = $factory->createAuth();
        $this->database = $factory->createDatabase();
        $this->storage = $factory->createStorage(); // Création de l'instance de Storage
        $this->userRepository = $userRepository;
    }


    public function createFirebaseUser(array $data, $photo)
    {
        Log::info("Début de la création de l'utilisateur");

        // Récupérer le dernier ID numérique dans Firebase
        $lastUserIdSnapshot = $this->database->getReference('users')
                                              ->orderByKey()
                                              ->limitToLast(1)
                                              ->getSnapshot();

        // Déterminer le dernier ID et l'incrémenter
        $lastUserId = $lastUserIdSnapshot->exists() ? intval($lastUserIdSnapshot->getKey()) : 0;
        $newUserId = $lastUserId + 1;

        // Créer l'utilisateur dans Firebase Authentication
        $firebaseUser = $this->auth->createUserWithEmailAndPassword($data['email'], $data['password']);
        Log::info("Utilisateur créé dans Firebase", ['uid' => $firebaseUser->uid]);

        // Stocker la photo dans Firebase Storage et obtenir le lien
        $photoPath = $this->uploadPhotoToFirebaseStorage($photo, $newUserId);

        // Enregistrer l'utilisateur dans la Realtime Database avec un ID numérique
        $this->database->getReference('users/' . $newUserId)->set([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'adresse' => $data['adresse'],
            'telephone' => $data['telephone'],
            'fonction' => $data['fonction'],
            'statut' => $data['statut'],
            'role_id' => $data['role_id'],
            'email' => $data['email'],
            'photo' => $photoPath, // Lien de la photo
            'password' => bcrypt($data['password']),
        ]);

        // Retourner les détails de l'utilisateur créé
        return [
            'user' => $firebaseUser,
            'photoUrl' => $photoPath,
            'id' => $newUserId, // Nouvel ID numérique
        ];
    }
    public function uploadPhotoToFirebaseStorage($photo, $firebaseUid)
    {
        // Récupérer le bucket Firebase Storage
        $bucket = $this->storage->getBucket(); // Récupère le bucket par défaut

        $fileName = 'users/' . $firebaseUid . '/' . $photo->getClientOriginalName();

        // Uploader le fichier dans Firebase Storage
        $bucket->upload(
            file_get_contents($photo->getRealPath()),
            [
                'name' => $fileName,
            ]
        );

        // Récupérer le lien public de l'image
        $photoUrl = $bucket->object($fileName)->signedUrl(new \DateTime('+1 year'));

        return $photoUrl;
    }

    // public function storeUserLocally(array $data)
    // {
    //     // Utiliser le repository pour stocker l'utilisateur localement
    //     return $this->userRepository->create($data);
    // }


    public function storeUserLocally(array $data)
    {
        // Vérifier si un utilisateur avec le même email existe déjà
        $existingUser = $this->userRepository->findByEmail($data['email']);

        if ($existingUser) {
            // Si l'utilisateur existe déjà, tu peux soit retourner un message d'erreur
            // ou mettre à jour les informations existantes
            return [
                'error' => 'Cet email est déjà utilisé par un autre utilisateur.',
            ];
        }

        // Si l'utilisateur n'existe pas, enregistrer les nouvelles informations
        return $this->userRepository->create($data);
    }

    public function updateFirebaseUser(array $data, $id, $photo = null)
    {
        try {
            // Récupérer l'utilisateur dans la Realtime Database avec l'ID numérique
            $firebaseUserRef = $this->database->getReference('users/' . $id);
            $firebaseUser = $firebaseUserRef->getValue();
    
            // Vérifier que l'utilisateur existe
            if (!$firebaseUser || !isset($firebaseUser['firebase_uid'])) {
                throw new \Exception('Utilisateur introuvable dans Firebase ou firebase_uid manquant.');
            }
    
            // Récupérer le firebase_uid de l'utilisateur
            $firebaseUid = $firebaseUser['firebase_uid'];
    
            // Mettre à jour les données dans Firebase Authentication
            $updateData = [];
    
            // Mettre à jour l'email si différent
            if (isset($data['email']) && $data['email'] !== $firebaseUser['email']) {
                $updateData['email'] = $data['email'];
            }
    
            // Mettre à jour le mot de passe si fourni
            if (isset($data['password']) && !empty($data['password'])) {
                $updateData['password'] = $data['password'];
            }
    
            // Si les informations d'authentification doivent être mises à jour
            if (!empty($updateData)) {
                $this->auth->updateUser($firebaseUid, $updateData); // Utilise le firebase_uid pour la mise à jour
            }
    
            // Si une nouvelle photo est fournie, la mettre à jour dans Firebase Storage
            if ($photo) {
                $photoUrl = $this->uploadPhotoToFirebaseStorage($photo, $id);
                $data['photo'] = $photoUrl;
            }
    
            // Mettre à jour les informations dans la Realtime Database Firebase
            $firebaseUserRef->update([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'adresse' => $data['adresse'],
                'telephone' => $data['telephone'],
                'fonction' => $data['fonction'],
                'statut' => $data['statut'],
                'role_id' => $data['role_id'],
                'email' => $data['email'],
                'photo' => $data['photo'] ?? $firebaseUser['photo'], // Conserver la photo si non modifiée
            ]);
    
            // Mettre à jour les informations localement
            $this->userRepository->update($id, $data);
    
            return ['message' => 'Utilisateur mis à jour avec succès dans Firebase et localement.'];
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de l\'utilisateur Firebase', ['error' => $e->getMessage()]);
            return ['error' => $e->getMessage()];
        }
    }
    
    

}
