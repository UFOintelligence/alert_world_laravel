<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    // App\Http\Controllers\AlertController.php

public function index()
{
    $alertas = \App\Models\Alert::with('user:id,name,avatar') // solo cargamos los campos necesarios
                    ->latest()
                    ->get();

    $response = $alertas->map(function ($alerta) {
        return [
            'id' => $alerta->id,
            'titulo' => $alerta->titulo,
            'descripcion' => $alerta->descripcion,
            'ubicacion' => $alerta->ubicacion,
            'archivo' => $alerta->archivo,
            'usuario_nombre' => $alerta->user->name ?? 'Anónimo',
            'usuario_avatar' => $alerta->user->avatar 
                ? url('storage/avatars/' . $alerta->user->avatar)
                : null, // Puedes dejarlo null si aún no hay imagen
            'comentarios' => [], // si vas a agregar comentarios más adelante
        ];
    });

    return response()->json($response);
}



   public function store(Request $request)
{
    $data = $request->validate([
        'user_id' => 'required|exists:users,id',
        'titulo' => 'required|string|max:255',
        'tipo_alerta' => 'required|string|max:100',
        'descripcion' => 'nullable|string',
        'latitud' => 'required|numeric',
        'longitud' => 'required|numeric',
        'archivo' => 'nullable|file|mimes:jpeg,jpg,png,mp4,mov,avi,wmv',
    ]);

    $archivoNombre = null;
    if ($request->hasFile('archivo')) {
        $archivoPath = $request->file('archivo')->store('alertas', 'public');
        $archivoNombre = basename($archivoPath);
    }

    $alerta = Alert::create([
        'user_id' => $data['user_id'],
        'titulo' => $data['titulo'],
        'tipo_alerta' => $data['tipo_alerta'],
        'descripcion' => $data['descripcion'] ?? null,
        'latitud' => $data['latitud'],
        'longitud' => $data['longitud'],
        'archivo' => $archivoNombre,
        // 'estado' se asigna automáticamente como 'pendiente'
    ]);

    return response()->json($alerta, 201);
}

}
