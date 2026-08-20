<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Programa;

class ProgramaController extends Controller
{
    public function index()
    {
        $programas = Programa::query()
            ->where('estado', 'activo')
            ->with([
                'niveles' => function ($query) {
                    $query
                        ->where('estado', 'activo')
                        ->orderBy('orden');
                }
            ])
            ->orderBy('nombre')
            ->get();

        return view('website.programs', compact('programas'));
    }
}