<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Users;

class AdministratorPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

     /**
     * Determine if the given user can create posts.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function create(Users $user)
    {
        return $user->isRestrictedAdmin() || $user->isAdmin() || $user->isDeveloper();
    }

    /**
     * Determine if the given user can create posts.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function edit(Users $user)
    {
        return $user->isRestrictedAdmin() || $user->isAdmin() || $user->isDeveloper();
    }

    /**
     * Determine if the given user can create posts.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function update(Users $user)
    {
        return $user->isRestrictedAdmin() || $user->isAdmin() || $user->isDeveloper();
    }

    /**
     * Determine if the given user can create posts.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function delete(Users $user)
    {
        return $user->isRestrictedAdmin() || $user->isAdmin() || $user->isDeveloper();
    }

    /**
     * Determine if the given user can create posts.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function list(Users $user)
    {
        return $user->isRestrictedAdmin() || $user->isAdmin() || $user->isDeveloper();
    }

    /**
     * Determine if the given user can create posts.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function manage(Users $user)
    {
        return $user->isRestrictedAdmin() || $user->isAdmin() || $user->isDeveloper();
    }


    /**
     * Determine if the given user can create posts.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function manageUsers(Users $user)
    {
        return $user->isAdmin() || $user->isDeveloper();
    }

    /**
     * Determine if the given user can create posts.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function manageSetup(Users $user)
    {
        return $user->isAdmin() || $user->isDeveloper();
    }

    public function manageApi(Users $user)
    {
        return $user->isDeveloper();
    }

}
