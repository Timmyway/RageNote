<?php

namespace App\Http\Controllers\Api\Notes;

use App\Http\Controllers\Controller;
use App\Services\CharacterService;

class CharacterController extends Controller
{
    protected $service;

    public function __construct(CharacterService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json($this->service->all());
    }

    public function show($id)
    {
        return response()->json($this->service->find($id));
    }
}
