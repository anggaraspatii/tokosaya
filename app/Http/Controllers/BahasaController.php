<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BahasaController extends Controller
{
    public function ubah(Request $request, string $bahasa): RedirectResponse
    {
        if (! in_array($bahasa, ['id', 'en'], true)) {
            abort(404);
        }

        $request->session()->put('bahasa', $bahasa);

        return redirect()->back();
    }
}
