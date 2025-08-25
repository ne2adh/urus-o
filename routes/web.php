<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParticipanteController;
use App\Http\Controllers\AuthWebController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ResumenController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;

// Auth (web)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthWebController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthWebController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [AuthWebController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/dashboard', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    $rol = auth()->user()->rol;
    if (!in_array($rol, ['jefe', 'superadministrador'], true)) {
        return redirect()->route('participantes.index');
    }
    return app(\App\Http\Controllers\DashboardController::class)->index(request());
})->name('dashboard')->middleware('auth');

Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    $rol = auth()->user()->rol;
    if (in_array($rol, ['jefe', 'superadministrador'], true)) {
        return redirect()->route('dashboard');
    }
    if ($rol === 'tecnico') {
        return redirect()->route('participantes.index');
    }
    return redirect()->route('login');
})->middleware('auth');

// CRUD Usuarios
Route::middleware('auth')->group(function () {
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('resumen', ResumenController::class);
});

// CRUD Participantes
Route::middleware('auth')->group(function () {
    Route::get('/participantes', [\App\Http\Controllers\ParticipanteController::class, 'index'])->name('participantes.index');
    Route::get('/participantes/crear', fn() => redirect()->route('participantes.index')); // mantener compatibilidad
    Route::post('/participantes', [\App\Http\Controllers\ParticipanteController::class, 'store'])->name('participantes.store');
    Route::get('/participantes/{participante}/edit', [\App\Http\Controllers\ParticipanteController::class, 'edit'])->name('participantes.edit');
    Route::put('/participantes/{participante}', [\App\Http\Controllers\ParticipanteController::class, 'update'])->name('participantes.update');
    Route::delete('/participantes/{participante}', [\App\Http\Controllers\ParticipanteController::class, 'destroy'])->name('participantes.destroy');
    Route::post('participantes/{participante}/claim', [ParticipanteController::class, 'claim'])->name('participantes.claim');
});

// Fallback
Route::fallback(function () {
    return redirect()->route('login');
});

Route::get('/files/{path}', [FileController::class, 'show'])
    ->where('path', '.*')
    ->name('files.show')
    ->middleware('auth');
