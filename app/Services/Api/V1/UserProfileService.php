<?php

namespace App\Services\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserProfileService
{
    public function updateProfile($user, array $data)
    {
        $user->update($data);

        return $user;
    }

    public function updatePicture($user, Request $request)
    {
        if ($request->hasFile('picture')) {
            if ($user->picture) {
                Storage::disk('public')->delete($user->picture);
            }

            $path = $request->file('picture')->storeAs(
                'user/profile_pictures',
                $user->id.'.'.$request->file('picture')->extension(),
                'public'
            );

            $user->update([
                'picture' => $path,
            ]);

            return [
                'success' => true,
                'picture_url' => Storage::url($path),
            ];
        }

        return ['success' => false];
    }

    public function deleteAccount($user)
    {
        return $user->delete();
    }
}
