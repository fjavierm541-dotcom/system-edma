<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdatePersonaRequest;
use App\Models\Pais;
use App\Models\Persona;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class PersonaController extends Controller
{
    /**
     * Mostrar el listado de personas.
     */
    public function index(Request $request): View
    {
        $termino = trim((string) $request->query('buscar', ''));
        $estado = $request->query('estado');

        $personas = Persona::query()
            ->with('paisResidencia')
            ->buscar($termino)
            ->when(
                in_array($estado, ['activo', 'inactivo'], true),
                fn ($query) => $query->where('estado', $estado)
            )
            ->orderBy('primer_apellido')
            ->orderBy('primer_nombre')
            ->paginate(15)
            ->withQueryString();

        $resumen = [
            'total' => Persona::query()->count(),
            'activas' => Persona::query()
                ->where('estado', 'activo')
                ->count(),
            'inactivas' => Persona::query()
                ->where('estado', 'inactivo')
                ->count(),
        ];

        return view('portal.personas.index', [
            'personas' => $personas,
            'resumen' => $resumen,
            'termino' => $termino,
            'estadoSeleccionado' => $estado,
        ]);
    }

    /**
     * Mostrar el formulario de registro.
        */
        public function create(): View
    {
        $paisPredeterminado = Pais::query()
            ->where('codigo_iso2', 'HN')
            ->value('id');

        return view('portal.personas.create', [
            'paises' => $this->obtenerPaises(),
            'paisPredeterminado' => $paisPredeterminado,
            'tiposDocumento' => $this->tiposDocumento(),
            'sexos' => $this->sexos(),
            'estadosCiviles' => $this->estadosCiviles(),
        ]);
    }

    /**
     * Guardar una nueva persona.
     */
    public function store(
        StorePersonaRequest $request
    ): RedirectResponse {
        $datos = $request->safe()->except('foto_perfil');
        $rutaFoto = null;

        try {
            if ($request->hasFile('foto_perfil')) {
                $rutaFoto = $request
                    ->file('foto_perfil')
                    ->store('personas/fotografias', 'public');

                $datos['foto_perfil'] = $rutaFoto;
            }

            $datos['estado'] = $datos['estado'] ?? 'activo';

            $persona = DB::transaction(
                fn () => Persona::create($datos)
            );

            return redirect()
                ->route('portal.personas.show', $persona)
                ->with(
                    'success',
                    'La persona fue registrada correctamente.'
                );
        } catch (Throwable $exception) {
            if ($rutaFoto) {
                Storage::disk('public')->delete($rutaFoto);
            }

            Log::error('Error al registrar una persona.', [
                'exception' => $exception,
                'usuario_id' => auth()->id(),
            ]);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Ocurrió un error al registrar la persona. Intente nuevamente.'
                );
        }
    }

    /**
     * Mostrar el expediente general de la persona.
     */
    public function show(Persona $persona): View
    {
        $persona->load([
    'paisResidencia',

    'documentos' => fn ($query) => $query
        ->orderByDesc('created_at'),

    'estudiante',
    'empleado',
]);

        return view('portal.personas.show', [
            'persona' => $persona,
        ]);
    }

    /**
     * Mostrar el formulario de edición.
     */
    public function edit(Persona $persona): View
    {
        return view('portal.personas.edit', [
            'persona' => $persona,
            'paises' => $this->obtenerPaises(),
            'tiposDocumento' => $this->tiposDocumento(),
            'sexos' => $this->sexos(),
            'estadosCiviles' => $this->estadosCiviles(),
            'paisPredeterminado' => null,
        ]);
    }

    /**
     * Actualizar la información de una persona.
     */
    public function update(
        UpdatePersonaRequest $request,
        Persona $persona
    ): RedirectResponse {
        $datos = $request->safe()->except([
            'foto_perfil',
            'eliminar_foto_perfil',
        ]);

        $fotoAnterior = $persona->foto_perfil;
        $fotoNueva = null;
        $eliminarFoto = $request->boolean('eliminar_foto_perfil');

        try {
            if ($request->hasFile('foto_perfil')) {
                $fotoNueva = $request
                    ->file('foto_perfil')
                    ->store('personas/fotografias', 'public');

                $datos['foto_perfil'] = $fotoNueva;
            } elseif ($eliminarFoto) {
                $datos['foto_perfil'] = null;
            }

            DB::transaction(
                fn () => $persona->update($datos)
            );

            if (
                ($fotoNueva || $eliminarFoto) &&
                $fotoAnterior
            ) {
                Storage::disk('public')->delete($fotoAnterior);
            }

            return redirect()
                ->route('portal.personas.show', $persona)
                ->with(
                    'success',
                    'La información de la persona fue actualizada correctamente.'
                );
        } catch (Throwable $exception) {
            if ($fotoNueva) {
                Storage::disk('public')->delete($fotoNueva);
            }

            Log::error('Error al actualizar una persona.', [
                'persona_id' => $persona->id,
                'exception' => $exception,
                'usuario_id' => auth()->id(),
            ]);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Ocurrió un error al actualizar la persona. Intente nuevamente.'
                );
        }
    }

    /**
     * Cambiar el estado activo/inactivo de una persona.
     */
    public function cambiarEstado(
        Persona $persona
    ): RedirectResponse {
        $nuevoEstado = $persona->estado === 'activo'
            ? 'inactivo'
            : 'activo';

        try {
            $persona->update([
                'estado' => $nuevoEstado,
            ]);

            $mensaje = $nuevoEstado === 'activo'
                ? 'La persona fue activada correctamente.'
                : 'La persona fue desactivada correctamente.';

            return back()->with('success', $mensaje);
        } catch (Throwable $exception) {
            Log::error('Error al cambiar el estado de una persona.', [
                'persona_id' => $persona->id,
                'exception' => $exception,
                'usuario_id' => auth()->id(),
            ]);

            return back()->with(
                'error',
                'No fue posible cambiar el estado de la persona.'
            );
        }
    }

    /**
     * Obtener países activos ordenados alfabéticamente.
     */
    private function obtenerPaises()
    {
        return Pais::query()
            ->activos()
            ->ordenados()
            ->get([
                'id',
                'nombre',
                'nacionalidad',
                'codigo_iso2',
            ]);
    }

    /**
     * Opciones iniciales de identificación.
     */
    private function tiposDocumento(): array
    {
        return [
            'dni' => 'Documento Nacional de Identificación',
            'identidad_menor' => 'Identidad de menor',
            'pasaporte' => 'Pasaporte',
            'otro' => 'Otro documento',
        ];
    }

    /**
     * Opciones iniciales del campo sexo.
     */
    private function sexos(): array
    {
        return [
            'masculino' => 'Masculino',
            'femenino' => 'Femenino',
            'otro' => 'Otro',
            'no_especificado' => 'Prefiere no especificar',
        ];
    }

    /**
     * Opciones iniciales de estado civil.
     */
    private function estadosCiviles(): array
    {
        return [
            'soltero' => 'Soltero(a)',
            'casado' => 'Casado(a)',
            'union_libre' => 'Unión libre',
            'divorciado' => 'Divorciado(a)',
            'viudo' => 'Viudo(a)',
            'otro' => 'Otro',
        ];
    }
}