<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetBahasa
{
    public function handle(Request $request, Closure $next): Response
    {
        $bahasa = Session::get('bahasa', 'id');

        if (! in_array($bahasa, ['id', 'en'], true)) {
            $bahasa = 'id';
        }

        App::setLocale($bahasa);

        return $next($request);
    }
}
