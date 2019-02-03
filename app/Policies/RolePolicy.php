<?php

namespace App\Policies;

use App\User;
use App\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /*
     * Only Super-Admin is authorized
     */
    public function before(User $user, $ability)
    {
        if(!$user->isSuperAdmin()) {
            return false;
        }

        // Else allow other specific methods authorization
    }

    public function view(User $user, Role $role)
    {
        return true;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Role $role)
    {
        return true;
    }

    public function delete(User $user, Role $role)
    {
        return ! $role->isDefault();
    }

}
