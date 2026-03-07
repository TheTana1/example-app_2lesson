<?php

if (!function_exists('getUserByCache'))
{
    function getUserByCache():bool
    {
        return auth()->user()->id;
    }
}
