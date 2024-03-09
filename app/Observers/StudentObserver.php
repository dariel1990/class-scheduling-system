<?php

namespace App\Observers;

use App\Models\Students;

class StudentObserver
{
    /**
     * Handle the Students "created" event.
     */
    public function created(Students $students): void
    {

    }

    /**
     * Handle the Students "updated" event.
     */
    public function updated(Students $students): void
    {
        //
    }

    /**
     * Handle the Students "deleted" event.
     */
    public function deleted(Students $students): void
    {
        //
    }

    /**
     * Handle the Students "restored" event.
     */
    public function restored(Students $students): void
    {
        //
    }

    /**
     * Handle the Students "force deleted" event.
     */
    public function forceDeleted(Students $students): void
    {
        //
    }
}
