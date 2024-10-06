<?php

namespace HS\Inventory\Traits;

use Auth;
use Exception;
use HS\Inventory\Exceptions\NoUserLoggedInException;

/**
 * Trait HasUser.
 */
trait HasUser
{
    /**
     * Attempt to find the user id of the currently logged-in user
     * Supports Cartalyst Sentry/Sentinel based authentication, as well as stock Auth.
     *
     * @return int|null
     * @throws NoUserLoggedInException
     *
     */
    protected static function getCurrentUserId(): int|null
    {
        /*
         * Check if we're allowed to return no user ID to the model, if so we'll return NULL
         */
        if (config('hs.inventory.allow_no_user')) {
            return null;
        }

        /*
         * Accountability is enabled, let's try and retrieve the current users ID
         */
        try {
            if (class_exists($class = '\Cartalyst\Sentry\Facades\Laravel\Sentry') || class_exists($class = '\Cartalyst\Sentinel\Laravel\Facades\Sentinel')) {
                if ($class::check()) {
                    return $class::getUser()->id;
                }
            } elseif (class_exists('Illuminate\Auth') || class_exists('Illuminate\Support\Facades\Auth')) {
                if (Auth::check()) {
                    return Auth::user()->getAuthIdentifier();
                }
            }
        } catch (Exception $e) {
            return null;
        }

        return null;
    }
}
