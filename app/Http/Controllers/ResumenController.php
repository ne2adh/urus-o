<?php

namespace App\Http\Controllers;

use App\Models\Resumen;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreResumenRequest;
use App\Http\Requests\UpdateResumenRequest;

class ResumenController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q'));
        $uid = $request->input('user_id');
        $fecha = $request->input('fecha');

        $resumenes = Resumen::query()
            ->with(['user'])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('provincia', 'like', "%{$q}%")
                        ->orWhere('municipio', 'like', "%{$q}%")
                        ->orWhere('circunscripcion', 'like', "%{$q}%");
                });
            })
            ->when($uid, fn($qry) => $qry->where('user_id', $uid))
            ->when($fecha, fn($qry) => $qry->where('fecha', $fecha))
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $usuarios = User::orderBy('username')->pluck('username', 'id');

        return view('resumen.index', compact('resumenes','q','uid','fecha','usuarios'));
    }

    public function create()
    {
        $usuarios = User::orderBy('username')->pluck('username','id');
        return view('resumen.create', compact('usuarios'));
    }

    public function store(StoreResumenRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        Resumen::create($data);

        return redirect()->route('resumen.index')->with('success','Registro creado.');
    }

    public function edit(Resumen $resumen)
    {
        $usuarios = User::orderBy('username')->pluck('username','id');
        return view('resumen.edit', compact('resumen','usuarios'));
    }

    public function update(UpdateResumenRequest $request, Resumen $resumen)
    {
        $data = $request->validated();
        $data['updated_by'] = auth()->id();

        $resumen->update($data);

        return redirect()->route('resumen.index')->with('success','Registro actualizado.');
    }

    public function destroy(Resumen $resumen)
    {
        $resumen->delete();
        return redirect()->route('resumen.index')->with('success','Registro eliminado.');
    }
}
