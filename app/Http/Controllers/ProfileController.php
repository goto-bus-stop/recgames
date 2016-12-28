<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Factory as AuthManager;

use App\Model\User;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        return view('profile', [
            'user' => $user,
        ]);
    }

    public function showSelf(AuthManager $auth)
    {
        return $this->show($auth->user());
    }
}
