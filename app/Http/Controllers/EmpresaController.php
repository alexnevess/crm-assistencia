<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;

class EmpresaController extends Controller
{
    public function create()
    {
        return view('empresa.create');
    }

    public function store(Request $request)
    {
        Empresa::create($request->all());
    }
}
