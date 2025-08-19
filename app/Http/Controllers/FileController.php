<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class FileController extends Controller
{
    public function show(string $path)
    {
        // seguridad básica: solo carpeta participantes
        if (!str_starts_with($path, 'participantes/')) {
            abort(404);
        }

        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        // inline en el navegador
        return Storage::disk('public')->response($path, null, [
            'Cache-Control' => 'private, max-age=0, no-cache',
        ]);
    }
}
