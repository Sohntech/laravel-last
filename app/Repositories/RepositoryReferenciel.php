<?php

// namespace App\Repositories;

// use App\Facades\Referenciel;

// class RepositoryReferenciel
// {
//     public function createReferenciel(array $data)
//     {
//         return Referenciel::create($data);

//     }


//     public function getAllReferenciels()
//     {
//         return Referenciel::all();
//     }

//     public function findReferencielById(string $id)
//     {
//         return Referenciel::find($id);
//     }

//     public function updateReferenciel(string $id, array $data)
//     {
//         return Referenciel::update($id, $data);
//     }

//     public function deleteReferenciel(string $id)
//     {
//         return Referenciel::delete($id);
//     }
// }



namespace App\Repositories;

use App\Models\ReferencielFirebaseModel;

class RepositoryReferenciel
{
    protected $referencielModel;

    public function __construct(ReferencielFirebaseModel $referencielModel)
    {
        $this->referencielModel = $referencielModel;
    }

    public function createReferenciel(array $data)
    {
        return $this->referencielModel->create($data);
    }

    public function getAllReferenciels()
    {
        return $this->referencielModel->all();
    }

    public function updateReferenciel(string $id, array $data)
    {
        return $this->referencielModel->update($id, $data);
    }

    public function deleteReferenciel(string $id)
    {
        return $this->referencielModel->delete($id);
    }
}
