<?php

namespace App\Policies;

use App\Models\TutorSession;
use App\Models\User;

class TutorSessionPolicy
{
    /**
     * Determine whether the user owns the tutor session.
     */
    private function isOwner(User $user, TutorSession $tutorSession): bool
    {
        return $user->id === $tutorSession->tutor_id;
    }

    /**
     * Determine whether the session is currently in scheduled status.
     */
    private function isScheduledSession(TutorSession $tutorSession): bool
    {
        return $tutorSession->status === 'scheduled';
    }

    /**
     * Determine whether the session is currently in progress.
     */
    private function isLiveSession(TutorSession $tutorSession): bool
    {
        return $tutorSession->status === 'inprogress';
    }

    /**
     * Determine whether the session has been completed.
     */
    private function isSessionCompleted(TutorSession $tutorSession): bool
    {
        return $tutorSession->status === 'completed';
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TutorSession $tutorSession): bool
    {
        return $this->isOwner($user, $tutorSession);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TutorSession $tutorSession): bool
    {
        return $this->isOwner($user, $tutorSession) && $this->isScheduledSession($tutorSession);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TutorSession $tutorSession): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TutorSession $tutorSession): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TutorSession $tutorSession): bool
    {
        return false;
    }

    /** Determine whether the tutor can start the scheduled session. */
    public function startSession(User $user, TutorSession $tutorSession): bool
    {
        return $this->isOwner($user, $tutorSession)
            &&  $this->isScheduledSession($tutorSession);
    }

    /** Determine whether the tutor can access the live session. */
    public function liveSession(User $user, TutorSession $tutorSession): bool
    {
        return $this->isOwner($user, $tutorSession)
            && $this->isLiveSession($tutorSession);
    }

    /** Determine whether the tutor can end the live session. */
    public function endSession(User $user, TutorSession $tutorSession): bool
    {
        return $this->isOwner($user, $tutorSession)
            && $this->isLiveSession($tutorSession);
    }

    /** Determine whether the tutor can save notes for the live session. */
    public function saveLiveNotes(User $user, TutorSession $tutorSession): bool
    {
        return $this->isOwner($user, $tutorSession)
            && $this->isLiveSession($tutorSession);
    }

    /** Determine whether the tutor can generate a completed-session debrief. */
    public function generateDebrief(User $user, TutorSession $tutorSession): bool
    {
        return $this->isOwner($user, $tutorSession)
            && $this->isSessionCompleted($tutorSession);
    }

    /** Determine whether the session belongs to the authenticated student. */
    public function isStudentSession(User $user, TutorSession $session): bool
    {
        return $user->id === $session->student_id;
    }
}
