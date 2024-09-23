<?php

namespace App\Services\Auth;

use App\Interface\AuthentificationInterface;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\Request;

class AuthentificationFirebase implements AuthentificationInterface
{
    protected $firebaseApiKey;
    protected $client;

    public function __construct()
    {
        $this->firebaseApiKey = env('FIREBASE_API_KEY');
        $this->client = new Client();
    }

    public function authentificate(Request $request)
    {
        try {
            $email = $request->input('email') ?? null;
            $password = $request->input('password') ?? null;

            if (!$email || !$password) {
                throw new Exception("Email ou mot de passe manquant.");
            }

            Log::info("Tentative de connexion avec l'email : $email");

            $response = $this->client->post('https://identitytoolkit.googleapis.com/v1/accounts:signInWithPassword?key=' . $this->firebaseApiKey, [
                'json' => [
                    'email' => $email,
                    'password' => $password,
                    'returnSecureToken' => true,
                ],
            ]);

            $body = json_decode((string) $response->getBody(), true);

            if (!isset($body['idToken'])) {
                Log::error('Erreur Firebase : ' . json_encode($body));
                throw new Exception('Échec de l\'authentification. Vérifiez vos identifiants.');
            }

            $idToken = $body['idToken'];
            $refreshToken = $body['refreshToken'];
            $userInfo = $this->getUserInfo($idToken);

            // $user = User::updateOrCreate(
            //     ['email' => $email],
            //     [
            //         'nom' => $userInfo['displayName'] ?? $email,
            //         'firebase_uid' => $userInfo['localId'],
            //     ]
            // );

            return [
                'success' => true,
                'token' => $idToken,
                'refresh_token' => $refreshToken,
                // 'user' => $user,
            ];
        } catch (Exception $e) {
            Log::error('Erreur d\'authentification Firebase: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Échec de l\'authentification via Firebase: ' . $e->getMessage(),
            ];
        }
    }

    protected function getUserInfo($idToken)
    {
        $response = $this->client->post('https://identitytoolkit.googleapis.com/v1/accounts:lookup?key=' . $this->firebaseApiKey, [
            'json' => [
                'idToken' => $idToken,
            ],
        ]);

        $body = json_decode((string) $response->getBody(), true);

        if (!isset($body['users'][0])) {
            throw new Exception('Impossible de récupérer les informations de l\'utilisateur.');
        }

        return $body['users'][0];
    }

    public function logout()
    {
        return response()->json(['message' => 'Déconnexion réussie.'], 200);
    }
}
