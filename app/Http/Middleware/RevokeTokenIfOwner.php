<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Passport\TokenRepository;

class RevokeTokenIfOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = \Auth::user();
        $tokenRepository = app(TokenRepository::class);
        $token = $tokenRepository->find($request->token_id);
        if ($user->id == $token->user_id) {
            return $next($request);
        } else {
            return response()->json([
                'message' => 'not authorised'
            ], 401);
        }
    }
}
