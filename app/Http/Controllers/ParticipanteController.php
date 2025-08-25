<?php
// app/Http/Controllers/ParticipanteController.php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use App\Models\Participante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class ParticipanteController extends Controller
{
    public function index(Request $request)
    {
        $municipiosOruro = [
            'Antequera',
            'Belén de Andamarca',
            'Caracollo',
            'Carangas',
            'Challapata',
            'Chipaya',
            'Choquecota',
            'Coipasa',
            'Corque',
            'Cruz de Machacamarca',
            'Curahuara de Carangas',
            'El Choro',
            'Escara',
            'Esmeralda',
            'Eucaliptus',
            'Huachacalla',
            'Huanuni',
            'Huayllamarca',
            'La Rivera',
            'Machacamarca',
            'Oruro',
            'Pampa Aullagas',
            'Pazña',
            'Poopó',
            'Sabaya',
            'Salinas de Garci Mendoza',
            'Santiago de Andamarca',
            'Santiago de Huari',
            'Santuario de Quillacas',
            'Todos Santos',
            'Toledo',
            'Totora',
            'Turco',
            'Soracachi',
            'Yunguyo de Litoral',
            'Otro'
        ];

        \Collator::create('es_BO')->sort($municipiosOruro);

         $provinciasOruro = [
            'Cercado',
            'Sajama',
            'Sur Carangas',
            'Litoral',
            'Nor Carangas',
            'Carangas',
            'Saucari',
            'San Pedro de Totora',
            'Ladislao Cabrera',
            'Poopó',
            'Abaroa',
            'Pantaleón Dalence',
            'Tomás Barrón',
            'Mejillones',
            'Sebastián Pagador',
            'Sabaya',
            'Otro'
        ];

        \Collator::create('es_BO')->sort($provinciasOruro);

        $uid = auth()->id();
        $hoy = Carbon::today();

        $q = trim((string)$request->input('q'));
        $q1 = trim((string)$request->input('q1'));

        $participantes = Participante::with('user')
        ->when($q1, function ($query) use ($q1) {
            $query->where('ci', 'like', "%{$q1}%");
        })
        ->orderByDesc('id')
        ->paginate(8)
        ->withQueryString();

    // Otros usuarios (para reclamar)
    $otros = Participante::with(['user','claimedBy'])
        ->where('user_id','<>',$uid)
        ->orderByDesc('id')
        ->paginate(10, ['*'], 'otros_page') // paginación independiente (param otros_page)
        ->withQueryString();

        $participantes_user = DB::table('participantes')
            ->where('user_id', Auth::id())
            ->when($q, function ($qry) use ($q) {
                $qry->where(function ($sub) use ($q) {
                    $sub->where('ci', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(8)
            ->withQueryString();


        $kpisUser = [
            'totalDia'       => Participante::where('user_id', $uid)->whereDate('created_at', $hoy)->count(),
            'totalAcumulado' => Participante::where('user_id', $uid)->count(),
        ];

        return view('participantes.index', compact('participantes','participantes_user', 'q1','q','municipiosOruro','provinciasOruro', 'otros', 'kpisUser'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'nombre_completo' => ['string','max:150'],
                'ci'             => ['required','regex:/^[0-9]{4,12}$/','unique:participantes,ci'],
                'celular'        => ['regex:/^[0-9]{7,12}$/'],
                'ci_exp'         => ['nullable','string','max:2'],
                'fecha_nac'      => ['nullable','date'],
                'genero'         => ['nullable','string','max:40'],
                'email'          => ['nullable','email','max:120'],
                'provincia'      => ['required','string','max:60'],
                'municipio'      => ['required','string','max:100'],
                'zona'           => ['nullable','in:Urbana,Rural'],
                'direccion'      => ['nullable','string','max:200'],
                'ocupacion'      => ['nullable','string','max:120'],
                'organizacion'   => ['nullable','string','max:120'],
                'observaciones'  => ['nullable','string','max:500'],
                'archivo'         => ['sometimes','file','mimes:pdf','mimetypes:application/pdf,application/x-pdf','max:5120'],
            ],
            [
                'ci.required'             => 'El CI es obligatorio.',
                'ci.regex'                => 'El CI debe tener entre 4 y 12 dígitos.',
                'ci.unique'               => 'El CI ya está registrado.',
                'email.email'             => 'Ingresa un correo válido.',
                'provincia.required' => 'La provincia es obligatoria.',
                'municipio.required' => 'El municipio es obligatorio.',
                'archivo.file'     => 'Debes seleccionar un archivo.',
                'archivo.mimes'    => 'Solo se permiten archivos PDF.',
                'archivo.mimetypes'=> 'Solo se permiten archivos PDF.',
                'archivo.max'      => 'El PDF no debe superar 5MB.',
            ]
        );

        if ($request->hasFile('archivo')) {
            $ci = $validated['ci'];
            $filename = $ci . '.pdf';
            $path = 'participantes/' . $filename;

            // 🚨 Validación: si ya existe, error
            if (Storage::disk('public')->exists($path)) {
                return back()
                    ->withErrors(['archivo' => 'Ya existe un archivo para este CI ('.$filename.').'])
                    ->withInput();
            }

            $validated['archivo'] = $request->file('archivo')->storeAs('participantes', $filename, 'public');
        }

        $validated['user_id'] = auth()->id();

        Participante::create($validated);

        return back()->with('status', 'Registro guardado correctamente.');
    }

    public function edit(Participante $participante)
    {
        $municipiosOruro = [
            'Antequera',
            'Belén de Andamarca',
            'Caracollo',
            'Carangas',
            'Challapata',
            'Chipaya',
            'Choquecota',
            'Coipasa',
            'Corque',
            'Cruz de Machacamarca',
            'Curahuara de Carangas',
            'El Choro',
            'Escara',
            'Esmeralda',
            'Eucaliptus',
            'Huachacalla',
            'Huanuni',
            'Huayllamarca',
            'La Rivera',
            'Machacamarca',
            'Oruro',
            'Pampa Aullagas',
            'Pazña',
            'Poopó',
            'Sabaya',
            'Salinas de Garci Mendoza',
            'Santiago de Andamarca',
            'Santiago de Huari',
            'Santuario de Quillacas',
            'Todos Santos',
            'Toledo',
            'Totora',
            'Turco',
            'Soracachi',
            'Yunguyo de Litoral'
        ];

        \Collator::create('es_BO')->sort($municipiosOruro);

        $provinciasOruro = [
            'Cercado',
            'Sajama',
            'Sur Carangas',
            'Litoral',
            'Nor Carangas',
            'Carangas',
            'Saucari',
            'San Pedro de Totora',
            'Ladislao Cabrera',
            'Poopó',
            'Abaroa',
            'Pantaleón Dalence',
            'Tomás Barrón',
            'Mejillones',
            'Sebastián Pagador',
            'Sabaya'
        ];

        \Collator::create('es_BO')->sort($provinciasOruro);

        return view('participantes.edit', compact('participante', 'municipiosOruro', 'provinciasOruro'));
    }

    public function update(Request $request, Participante $participante)
    {
        $validated = $request->validate(
            [
                'nombre_completo' => ['string','max:150'],
                'ci'              => ['required','regex:/^[0-9]{4,12}$/','unique:participantes,ci,'.$participante->id],
                'celular'         => ['nullable','regex:/^[0-9]{7,12}$/'],
                'ci_exp'          => ['nullable','string','max:2'],
                'fecha_nac'       => ['nullable','date'],
                'genero'          => ['nullable','string','max:40'],
                'email'           => ['nullable','email','max:120'],
                'provincia'       => ['required','string','max:60'],
                'municipio'       => ['required','string','max:100'],
                'zona'            => ['nullable','in:Urbana,Rural'],
                'direccion'       => ['nullable','string','max:200'],
                'ocupacion'       => ['nullable','string','max:120'],
                'organizacion'    => ['nullable','string','max:120'],
                'observaciones'   => ['nullable','string','max:500'],
                'archivo'         => ['sometimes','file','mimes:pdf','mimetypes:application/pdf,application/x-pdf','max:5120'],
            ],
            [
                'ci.unique'        => 'El CI ya está registrado.',
                'provincia.required' => 'La provincia es obligatoria.',
                'municipio.required' => 'El municipio es obligatorio.',
                'archivo.mimes'    => 'Solo se permiten archivos PDF.',
                'archivo.mimetypes'=> 'Solo se permiten archivos PDF.',
                'archivo.max'      => 'El PDF no debe superar 5MB.',
            ]
        );

        $oldPath = $participante->archivo;
        $ci      = $validated['ci'];
        $newFilename = $ci . '.pdf';
        $newPath     = 'participantes/'.$newFilename;

        if ($request->hasFile('archivo')) {
            // reemplazar archivo subido con el nombre {CI}.pdf
            if ($oldPath && $oldPath !== $newPath) {
                Storage::disk('public')->delete($oldPath);
            }
            $stored = $request->file('archivo')->storeAs('participantes', $newFilename, 'public');
            $validated['archivo'] = $stored; // participantes/{CI}.pdf
        } else {
            // no subieron nuevo archivo: si ya había uno y cambió el CI, renombrar
            if ($oldPath && $oldPath !== $newPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->move($oldPath, $newPath);
                $validated['archivo'] = $newPath;
            }
        }

        $participante->update($validated);

        return redirect()->route('participantes.index')->with('status','Participante actualizado.');
    }

    public function destroy(Participante $participante)
    {
        $participante->delete();
        return redirect()->back()->with('status', 'Participante eliminado correctamente.');
    }

    public function claim(Request $request, Participante $participante)
    {
        $request->validate([
            'ci'      => ['required','regex:/^[0-9]{4,12}$/'],
            'claimer' => ['required','integer'],
        ]);

        $uid = Auth::id();

        if ($participante->user_id === $uid) {
            return back()->withErrors(['claim' => 'No puedes reclamar tu propio registro.'])->withInput();
        }
        if ($participante->ci !== $request->ci) {
            return back()->withErrors(['claim' => 'El CI no coincide con el registro.'])->withInput();
        }
        if (!is_null($participante->claimed_by_user_id)) {
            return back()->withErrors(['claim' => 'Este registro ya fue reclamado.'])->withInput();
        }

        $participante->update([
            'claimed_by_user_id' => $uid,
            'claimed_at'         => now(),
        ]);

        return back()->with('status','Registro reclamado correctamente.');
    }
}
