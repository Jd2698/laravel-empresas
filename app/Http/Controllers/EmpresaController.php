<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Empresa;
use App\Http\Requests\EmpresaRequest;

class EmpresaController extends Controller
{

    public function index()
    {
        $empresas = Empresa::all();
		return response()->json($empresas, 200);
	}

    public function store(EmpresaRequest $request)
    {

        $request->merge(['estado' => 'Activo']);

        $empresa = new Empresa($request->all());
		$empresa->save();
		return response()->json($empresa, 201);
    }

    /**
     * Buscar empresa por id, recibiendo lo por parametro
     */
    public function show(string $id)
    {
        $empresa = Empresa::find($id);

        if(!$empresa){
            return response()->json([
                'error' => "Not found",
                'message' => "Empresa no encontrada"
            ], 404);
        }

        return response()->json($empresa, 200);
    }

    /**
     * Buscar empresa por nit, recibiendo lo por parametro
     */
    public function showByNit(string $nit)
    {
        $empresa = Empresa::where('nit', $nit)->first();

        if(!$empresa){
            return response()->json([
                'error' => "Not found",
                'message' => "Empresa no encontrada"
            ], 404);
        }

        return response()->json($empresa, 200);
    }

    public function update(EmpresaRequest $request, string $id)
    {
        $data = $request->except('nit');

        $empresa = Empresa::find($id);
        if(!$empresa){
            return response()->json([
                'error' => "Not found",
                'message' => "Empresa no encontrada"
            ], 404);
        }

        $empresa->update($data);
        return response()->json($empresa, 200);
    }

    /**
     * Eliminar todas las empresas que estén inactivas
     */
    public function destroyInactive()
    {
        $empresas = Empresa::where('estado', 'Inactivo')->update(['deleted_at' => now()]);

        return response()->json([
            'message' => 'Empresas inactivas borradas: ' . $empresas
        ], 200);
    }
}
