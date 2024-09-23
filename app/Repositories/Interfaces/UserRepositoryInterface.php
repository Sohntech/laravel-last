<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function create(array $data);
    public function find(int $id);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getAllUsers(string $role = null); // Méthode pour lister les utilisateurs avec filtre
    public function findByEmail(string $email);

}
