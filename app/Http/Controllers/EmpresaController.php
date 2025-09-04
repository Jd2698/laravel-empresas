<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Empresa;
use Illuminate\Validation\Rule;
use App\Enums\StatusEnum;

class EmpresaController extends Controller
{

    public function index()
    {
        $empresas = Empresa::all();
		return response()->json($empresas, 200);
	}

    public function store(Request $request)
    {
        $rules = [
            'nombre' => [
                'required', 'string', 'max:40',
            ],
            'nit' => [
                'required', 
                'numeric',
                'min_digits:8',
                'unique:empresas,nit'
            ],
            'direccion' => ['required', 'string', 'min:10'],
            'telefono' => ['required', 'numeric', 'digits:10'],
            'estado' => ['nullable', Rule::in(StatusEnum::cases())],
        ];

        $request->merge(['estado' => 'Activo']);

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

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
            return response()->json(['errors' => "Empresa not found"], 404);
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
            return response()->json(['errors' => "Empresa not found"], 404);
        }

        return response()->json($empresa, 200);
    }

    public function update(Request $request, string $id)
    {
         $rules = [
            'nombre' => ['nullable', 'string', 'max:40'],
            'direccion' => ['nullable', 'string', 'min:10'],
            'telefono' => ['nullable', 'numeric', 'digits:10'],
            'estado' => ['nullable', Rule::in(StatusEnum::cases())],
        ];
        
        $data = $request->except('nit');
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $empresa = Empresa::find($id);
        if(!$empresa){
            return response()->json(['errors' => "Empresa not found"], 404);
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
