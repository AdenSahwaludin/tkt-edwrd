<?php

namespace App\Http\Middleware;

use App\Models\LogAktivitas;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogLoginActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Log aktivitas login jika user baru saja melakukan autentikasi
        if (Auth::check() && ! session()->has('login_logged')) {
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'jenis_aktivitas' => 'login',
                'deskripsi' => 'User berhasil login ke sistem',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Tandai bahwa login sudah di-log untuk session ini
            session(['login_logged' => true]);
        }

        return $response;
    }
}
