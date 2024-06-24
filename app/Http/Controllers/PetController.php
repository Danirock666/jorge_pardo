<?php

namespace App\Http\Controllers;

use App\Models\Pets;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pets = Pets::all();

        return response()->json([
            'code' => Response::HTTP_OK,
            'message' => 'Pets retrieved successfully',
            'data' => $pets
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:50',
            'especie' => 'required|string|max:20',
            'raza' => 'nullable|string|max:20',
            'sexo' => 'required|string|max:1',
            'fechaNacimiento' => 'required|date',
            'numeroAtenciones' => 'required|integer',
            'enTratamiento' => 'required|boolean',
        ]);

        $pets = Pets::create($validatedData);

        return response()->json([
            'code' => Response::HTTP_CREATED,
            'message' => 'Pet created successfully',
            'data' => $pets
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Buscar la mascota por su ID
        $pets = Pets::find($id);

        // Verificar si la mascota existe
        if ($pets) {
            return response()->json([
                'success' => true,
                'data' => $pets
            ], Response::HTTP_CREATED);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Pet not found'
            ], Response::HTTP_CREATED);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validar la solicitud
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:50',
            'especie' => 'required|string|max:20',
            'raza' => 'nullable|string|max:20',
            'sexo' => 'required|string|max:1',
            'fechaNacimiento' => 'required|date',
            'numeroAtenciones' => 'required|integer',
            'enTratamiento' => 'required|boolean',
        ]);

        // Buscar la mascota por su ID
        $pets = Pets::find($id);
        $pets = Pets::create($validatedData);

        if ($pets) {
            // Actualizar los detalles de la mascota
            $pets->update($validatedData);

            return response()->json([
                'success' => true,
                'data' => $pets
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Pets not found'
            ], Response::HTTP_OK);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
      // Buscar la mascota por su ID
      $pets = Pets::find($id);

      if ($pets) {
          // Eliminar la mascota
          $pets->delete();

          return response()->json([
              'success' => true,
              'message' => 'Pets deleted successfully'
          ], 200);
      } else {
          return response()->json([
              'success' => false,
              'message' => 'Pets not found'
          ], 404);
      }
  }

  public function filterBySpecies($especie)
    {
        // Filtrar las mascotas por la especie proporcionada
        $pets = Pets::where('especie', 'LIKE', '%' . $especie . '%')->get();

        if ($pets->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'data' => $pets
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No pets found for the given species'
            ], 404);
        }
    }

}
