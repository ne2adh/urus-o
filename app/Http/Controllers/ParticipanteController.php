<?php
// app/Http/Controllers/ParticipanteController.php
namespace App\Http\Controllers;

use App\Models\Participante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'Eduardo Avaroa',
            'Pantaleón Dalence',
            'Tomás Barrón',
            'Mejillones',
            'Sebastián Pagador',
            'Atahuallpa',
            'Otro'
        ];

        \Collator::create('es_BO')->sort($provinciasOruro);

        $q = trim((string)$request->input('q'));

        $participantes = DB::table('participantes')
            ->when($q, function ($qry) use ($q) {
                $qry->where('ci', 'like', "%{$q}%")
                    ->orWhere('nombre_completo', 'like', "%{$q}%");
            })
            ->orderByDesc('id')
            ->paginate(8)
            ->withQueryString();

            $uid = auth()->id();
    $hoy = Carbon::today();

    $kpisUser = [
        'totalDia'       => Participante::where('user_id', $uid)->whereDate('created_at', $hoy)->count(),
        'totalAcumulado' => Participante::where('user_id', $uid)->count(),
    ];

        return view('participantes.index', compact('participantes', 'q','municipiosOruro','provinciasOruro', 'kpisUser'));
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
            // guarda en storage/app/public/participantes
            $path = $request->file('archivo')->store('participantes', 'public');
            $validated['archivo'] = $path; // guardar ruta relativa
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
            'Eduardo Avaroa',
            'Pantaleón Dalence',
            'Tomás Barrón',
            'Mejillones',
            'Sebastián Pagador',
            'Atahuallpa'
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

        if ($request->hasFile('archivo')) {
            if ($participante->archivo) {
                Storage::disk('public')->delete($participante->archivo);
            }
            $path = $request->file('archivo')->store('participantes', 'public');
            $validated['archivo'] = $path;
        }

        $participante->update($validated);

        return redirect()->route('participantes.index')->with('status','Participante actualizado.');
    }

    public function destroy(Participante $participante)
    {
        $participante->delete();
        return redirect()->back()->with('status', 'Participante eliminado correctamente.');
    }
}
