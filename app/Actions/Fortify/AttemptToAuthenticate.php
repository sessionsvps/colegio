<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Actions\AttemptToAuthenticate as BaseAttemptToAuthenticate;
use Illuminate\Validation\ValidationException;

class AttemptToAuthenticate extends BaseAttemptToAuthenticate
{
    public function handle($request, $next)
    {
        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user && $user->esActivo == 0) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }

        return parent::handle($request, $next);
    }
}
