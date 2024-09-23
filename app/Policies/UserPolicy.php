<?php

// namespace App\Policies;

// use App\Models\User;
// use App\Models\Role;

// class UserPolicy
// {
//     /**
//      * Determine whether the user can create another user based on their role.
//      */
//     public function create(User $user, User $targetUser)
//     {
//         // L'utilisateur connecté doit être Admin ou Manager
//         if ($user->role->name === 'Admin') {
//             // Admin peut créer un utilisateur avec les rôles Admin, Manager, Coach, CM
//             return in_array($targetUser->role->name, ['Admin', 'Manager', 'Coach', 'CM']);
//         }

//         if ($user->role->name === 'Manager') {
//             // Manager peut créer un utilisateur avec les rôles Manager, Coach, CM
//             return in_array($targetUser->role->name, ['Manager', 'Coach', 'CM']);
//         }

//         return false;
//     }
// }



namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can create another user based on their role.
     */
    public function create(User $user, User $targetUser)
    {
        // L'utilisateur connecté doit être Admin, Manager ou CM
        if ($user->role->name === 'Admin') {
            // Admin peut créer un utilisateur avec les rôles Admin, Manager, Coach, CM ou inscrire des apprenants
            return in_array($targetUser->role->name, ['Admin', 'Manager', 'Coach', 'CM', 'Apprenant']);
        }

        if ($user->role->name === 'Manager') {
            // Manager peut créer un utilisateur avec les rôles Manager, Coach, CM ou inscrire des apprenants
            return in_array($targetUser->role->name, ['Manager', 'Coach', 'CM', 'Apprenant']);
        }

        if ($user->role->name === 'CM') {
            // CM ne peut inscrire que des apprenants
            return $targetUser->role->name === 'Apprenant';
        }

        return false;
    }
}

