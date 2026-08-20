<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Programa;

class ProgramaController extends Controller
{
    public function index()
    {
        $programas = Programa::query()
            ->activos()
            ->with([
                'niveles' => function ($query) {
                    $query
                        ->activos()
                        ->orderBy('orden');
                },
            ])
            ->orderBy('nombre')
            ->get();

        return view(
            'website.programs',
            compact('programas')
        );
    }
}