<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Auth\Factory as AuthManager;

use App\Model\User;

class ProfileController extends Controller
{
    /**
     * Show a user profile.
     */
    public function show(User $user)
    {
        return view('profile', [
            'user' => $user,
        ]);
    }

    /**
     * Show the current user's profile.
     */
    public function showSelf(AuthManager $auth)
    {
        return $this->show($auth->user());
    }

    /**
     * Show the user settings page.
     */
    public function settings(AuthManager $auth)
    {
        $user = $auth->user();

        return view('users.settings', [
            'user' => $user,
            'canDisconnectLocal' => $user->steam_id || $user->twitch_id,
            'canDisconnectSteam' => $user->email || $user->twitch_id,
            'canDisconnectTwitch' => $user->email || $user->steam_id,
        ]);
    }

    /**
     * Change the user's recgam.es login credentials.
     */
    public function changeLocalLogin(AuthManager $auth, Hasher $hasher, Request $request)
    {
        $user = $auth->user();

        $this->validate($request, [
            'email' => [
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => $user->password ? 'required' : '',
            'new_password' => 'min:6|confirmed',
        ]);

        if ($user->password && $hasher->check($request->input('password'), $user->password)) {
            // Passwords don't match.
            return redirect()->back()->withErrors([
                'password' => 'Current password is incorrect.',
            ]);
        }

        $updated = [
            'email' => $user->email != $request->input('email'),
            'password' => $request->input('new_password'),
        ];
        if ($updated['email']) {
            $user->email = $request->input('email');
        }
        if ($updated['password']) {
            $user->password = $hasher->make($request->input('new_password'));
        }

        $user->save();

        $message = 'Changed your recgam.es ';
        if ($updated['email'] && $updated['password']) {
            $message .= 'e-mail address and password.';
        } else if ($updated['email']) {
            $message .= 'e-mail address.';
        } else if ($updated['password']) {
            $message .= 'password.';
        }

        return redirect()->back()->with('local', $message);
    }

    /**
     * Remove the current user's recgam.es login details. They will only be able
     * to use social logins going forward.
     */
    public function removeLocalLogin(AuthManager $auth)
    {
        $user = $auth->user();

        if (!$user->steam_id && !$user->twitch_id) {
            return redirect()->back()->withErrors([
                'local' => 'Removing these login details would make your account inaccessible. ' .
                           'Please add a Social login before removing your recgam.es login details.'
            ]);
        }

        $user->email = null;
        $user->password = null;

        $user->save();

        return redirect()->back()->with('local', 'E-Mail/Password login disabled.');
    }
}
