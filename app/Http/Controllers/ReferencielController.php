<?php

namespace App\Http\Controllers;

use App\Models\ReferencielFirebaseModel;
use Illuminate\Http\Request;

class ReferencielController extends Controller
{
    protected $referencielModel;

    public function __construct(ReferencielFirebaseModel $referencielModel)
    {
        $this->referencielModel = $referencielModel;
    }

    public function index()
    {
        return response()->json($this->referencielModel->all());
    }

    public function store(Request $request)
    {
        $data = $request->only(['libelle', 'description', 'code']);
        return response()->json($this->referencielModel->create($data));
    }
}
