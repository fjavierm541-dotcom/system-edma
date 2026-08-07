<?php

namespace App\Http\Requests;

use App\Models\Estudiante;
use App\Models\EstudianteResponsable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEstudianteResponsableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'parentesco' => $this->normalizeNullableText(
                $this->input('parentesco')
            ),

            'es_principal' => $this->boolean(
                'es_principal'
            ),

            'recibe_notificaciones' => $this->boolean(
                'recibe_notificaciones'
            ),

            'activo' => $this->boolean(
                'activo'
            ),
        ]);
    }

    public function rules(): array
    {
        $estudiante = $this->route('estudiante');

        $responsable = $this->route(
            'responsable'
        );

        $estudianteId = $estudiante instanceof Estudiante
            ? $estudiante->getKey()
            : $estudiante;

        $responsableId =
            $responsable instanceof EstudianteResponsable
                ? $responsable->getKey()
                : $responsable;

        $personaEstudianteId =
            $estudiante instanceof Estudiante
                ? $estudiante->persona_id
                : null;

        return [
            'responsable_persona_id' => [
                'required',
                'integer',

                Rule::exists('personas', 'id')
                    ->where(
                        fn ($query) => $query
                            ->whereNull('deleted_at')
                    ),

                Rule::notIn(
                    array_filter([
                        $personaEstudianteId,
                    ])
                ),

                Rule::unique(
                    'estudiante_responsables',
                    'responsable_persona_id'
                )
                    ->where(
                        fn ($query) => $query
                            ->where(
                                'estudiante_id',
                                $estudianteId
                            )
                    )
                    ->ignore($responsableId),
            ],

            'parentesco' => [
                'required',
                'string',
                'max:50',
            ],

            'es_principal' => [
                'boolean',
            ],

            'recibe_notificaciones' => [
                'boolean',
            ],

            'activo' => [
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'responsable_persona_id.required' =>
                'Debe seleccionar una persona responsable.',

            'responsable_persona_id.integer' =>
                'La persona responsable seleccionada no es válida.',

            'responsable_persona_id.exists' =>
                'La persona responsable seleccionada no existe o fue eliminada.',

            'responsable_persona_id.not_in' =>
                'El estudiante no puede registrarse como su propio responsable.',

            'responsable_persona_id.unique' =>
                'Esta persona ya está registrada como responsable del estudiante.',

            'parentesco.required' =>
                'Debe indicar el parentesco o relación con el estudiante.',

            'parentesco.max' =>
                'El parentesco no puede superar los 50 caracteres.',

            'es_principal.boolean' =>
                'El valor del responsable principal no es válido.',

            'recibe_notificaciones.boolean' =>
                'El valor de recepción de notificaciones no es válido.',

            'activo.boolean' =>
                'El estado del responsable no es válido.',
        ];
    }

    public function attributes(): array
    {
        return [
            'responsable_persona_id' => 'persona responsable',
            'parentesco' => 'parentesco',
            'es_principal' => 'responsable principal',
            'recibe_notificaciones' => 'recepción de notificaciones',
            'activo' => 'estado',
        ];
    }

    private function normalizeNullableText(
        mixed $value
    ): mixed {
        if (!is_string($value)) {
            return $value;
        }

        $value = preg_replace(
            '/\s+/u',
            ' ',
            trim($value)
        );

        return $value === '' ? null : $value;
    }
}