<?php

namespace Arrilot\Sessions;

class SessionProvider
{
    /**
     * Register sessions.
     *
     * @return void
     */
    public static function register()
    {
        Session::removeOldFlashData();
        Session::ageFlashData();
    }
}
