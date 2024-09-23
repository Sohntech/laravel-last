<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApiResponseMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Passer la requête au prochain middleware/contrôleur
        $response = $next($request);

        // Gérer seulement les réponses JSON
        if ($response instanceof JsonResponse) {
            // Déterminer le statut en fonction du code de réponse
            $status = $response->getStatusCode() === 200 ? 'SUCCESS' : 'ERROR';

            // Récupérer les données originales
            $data = $response->getData(true);

            // Reformater la réponse
            return response()->json([
                'data' => $data['data'] ?? $data,
                'status' => $status,
                'message' => $data['message'] ?? '',
            ], $response->getStatusCode());
        }

        // Retourner la réponse non modifiée si ce n'est pas une réponse JSON
        return $response;
    }
}
