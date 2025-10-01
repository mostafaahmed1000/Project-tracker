<?php

namespace App\Policies;

use App\Models\Attachment;
use App\Models\User;

class AttachmentPolicy
{
   
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
       return $user->hasRole('admin') || $user->hasRole('manager') || $user->hasRole('contributor');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Attachment $attachment): bool
    {
    return $user->hasRole('admin') || ($user->hasRole('manager') || $attachment->uploader_id === $user->id);    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Attachment $attachment): bool
    {
        return $user->hasRole('admin') || ($user->hasRole('manager') || $attachment->uploader_id === $user->id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Attachment $attachment): bool
    {
        return $user->hasRole('admin') || ($user->hasRole('manager') || $attachment->uploader_id === $user->id);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Attachment $attachment): bool
    {
        return $user->hasRole('admin') || ($user->hasRole('manager') || $attachment->uploader_id === $user->id);
    }
}
