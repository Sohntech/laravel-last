<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepositoryImplement implements UserRepositoryInterface
{
    public function create(array $data)
    {
        return User::create($data);
    }

    public function find(int $id)
    {
        return User::find($id);
    }

    public function update(int $id, array $data)
    {
        return User::where('id', $id)->update($data);
    }

    public function delete(int $id)
    {
        return User::destroy($id);
    }

    public function getAllUsers(string $role = null)
    {
        return User::filterByRole($role)->get(); // Utilisation de la scope globale pour filtrer par rôle
    }

    public function findByEmail(string $email)
{
    return User::where('email', $email)->first();
}

}
