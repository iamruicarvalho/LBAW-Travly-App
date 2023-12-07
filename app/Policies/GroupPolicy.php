<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\Owner;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class GroupPolicy
{
    use HandlesAuthorization;

    public function list()
    {   
        return Auth::check();
    }

    public function create(){
        return Auth::check();
    }

    public function join(){
        return Auth::check();
    }

    public function leave(){
        return Auth::check();
    }

    public function delete(Group $group)
    {
        $owner = Owner::where('groupid', $group->id)->where('id', Auth::id())->first();

        return !is_null($owner) || Auth::user()->admin;
    }

    public function edit(Group $group)
    {
        $owner = Owner::where('groupid', $group->id)->where('id', Auth::id())->first();

        return !is_null($owner);
    }

}