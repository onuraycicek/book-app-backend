<?php

namespace App\Services\Api\V1;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class UserPasswordService
{
    public function sendResetLink(array $data)
    {
        $status = Password::sendResetLink($data);

        return $status === Password::RESET_LINK_SENT;
    }

    public function resetPassword(array $data)
    {
        $status = Password::reset(
            $data,
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                $user->tokens()->delete();
            }
        );

        return $status === Password::PASSWORD_RESET;
    }

    public function updatePassword($user, array $data)
    {
        if (! Hash::check($data['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => __('auth.password'),
            ]);
        }

        $user->update([
            'password' => Hash::make($data['new_password']),
        ]);

        $user->tokens()->delete();
    }
}
