<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Models\Role;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\FirebaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $firebaseService;
    protected $userRepository;

    public function __construct(FirebaseService $firebaseService, UserRepositoryInterface $userRepository)
    {
        $this->firebaseService = $firebaseService;
        $this->userRepository = $userRepository;
    }

    // Enregistrement d'un utilisateur
    public function register(StoreUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $photo = $request->file('photo'); // Récupérer la photo

        // Chercher le rôle de l'utilisateur à créer
        $role = Role::find($data['role_id']);
        $targetUser = new User(['role_id' => $role->id]);

        // Autorisation basée sur la policy
        $this->authorize('create', $targetUser);

        try {
            // Créer l'utilisateur dans Firebase et obtenir le lien de la photo
            $result = $this->firebaseService->createFirebaseUser($data, $photo);
            $firebaseUser = $result['user'];
            $photoUrl = $result['photoUrl']; // Lien de la photo

            // Stocker l'utilisateur localement
            $data['photo'] = $photoUrl; // Stocker le lien de la photo dans la DB locale
            $user = $this->firebaseService->storeUserLocally($data);

            return response()->json([
                'message' => 'User registered successfully',
                'firebase_user' => $firebaseUser,
                'local_user' => $user,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

 
    // Lister les utilisateurs avec filtre par rôle
    public function index(Request $request)
    {
        $role = $request->query('role'); // Récupérer le paramètre de rôle
        $users = $this->userRepository->getAllUsers($role);
        return response()->json($users);
    }

    // Modifier un utilisateur localement et dans Firebase
    public function update(Request $request, $id)
    {
        try {
            // Récupérer l'utilisateur localement
            $user = $this->userRepository->find($id);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $data = $request->all();
            $photo = $request->file('photo'); // Récupérer la photo si mise à jour

            // Mise à jour dans Firebase
            $this->firebaseService->updateFirebaseUser( $data, $user->firebase_uid, $photo);

            // Mise à jour localement
            $this->userRepository->update($id, $data);

            return response()->json(['message' => 'Utilisateur mis à jour avec succès.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Trouver un utilisateur spécifique par ID
    public function find($id): JsonResponse
    {
        try {
            $user = $this->userRepository->find($id);

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getUsersByRole($roleName)
    {
        // Utilisation du scope pour filtrer les utilisateurs par rôle
        $users = User::filterByRole($roleName)->get();

        return response()->json([
            'status' => 'success',
            'data' => $users,
        ]);
    }

    
}
