<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Factory as AuthManager;
use SocialiteProviders\Steam\OpenIDValidationException;
use SocialiteProviders\Manager\{Contracts\ConfigInterface, Config};

use App\Model\User;
use App\Http\Controllers\Controller;

class SocialiteController extends Controller
{
    private function makeConfig(string $service, array $extra): ConfigInterface
    {
        $config = config('services.' . $service);
        return new Config(
            $config['client_id'],
            $config['client_secret'],
            $config['redirect'],
            $extra
        );
    }

    /**
     * Get a Socialite Steam provider.
     */
    private function steam()
    {
        $config = $this->makeConfig('steam', [
            'redirect' => action('Auth\SocialiteController@steamCallback'),
        ]);
        return Socialite::with('steam')->setConfig($config);
    }

    /**
     * Get a Socialite Twitch provider.
     */
    private function twitch()
    {
        $config = $this->makeConfig('twitch', [
            'redirect' => action('Auth\SocialiteController@twitchCallback'),
        ]);
        return Socialite::with('twitch')->setConfig($config);
    }

    /**
     *
     */
    private function completeAuth(AuthManager $auth, $user, $key)
    {
        $model = User::where($key, $user->id)->first();

        if (!$model && $auth->check()) {
            // Associate logged-in user with this OAuth ID.
            $model = $auth->user();
            $model->update([
                $key => $user->id,
            ]);
        } else if (!$model) {
            // Create a new user for this OAuth ID.
            // TODO deal with the case where the username is taken.
            // TODO Some providers include emails, do we want to use those here?
            $model = User::create([
                'name' => $user->nickname,
                $key => $user->id,
            ]);
        }

        // Log in as this user.
        $auth->login($model);

        return $model;
    }

    /**
     * Disconnect a user's account from a social login.
     */
    private function disconnect($user, $key)
    {
        if ($user) {
            $user->update([$key => null]);
        }
        return redirect()->back();
    }

    /**
     * Redirect to the Steam login page.
     */
    public function steamRedirect()
    {
        return $this->steam()->redirect();
    }

    /**
     * Process a Steam login.
     */
    public function steamCallback(AuthManager $auth)
    {
        try {
            $user = $this->steam()->user();
        } catch (OpenIDValidationException $err) {
            return redirect()->route('login')->withErrors([
                'social' => 'Could not authenticate with Steam.'
            ]);
        }

        $model = $this->completeAuth($auth, $user, 'steam_id');

        return redirect()->action('GamesController@list');
    }

    /**
     * Disconnect the user's Steam account from their recgam.es account.
     */
    public function steamDisconnect(AuthManager $auth)
    {
        return $this->disconnect($auth->user(), 'steam_id');
    }

    /**
     * Redirect to the Twitch login page.
     */
    public function twitchRedirect()
    {
        return $this->twitch()->redirect();
    }

    /**
     * Process a Twitch login.
     */
    public function twitchCallback(AuthManager $auth)
    {
        try {
            $user = $this->twitch()->user();
        } catch (OpenIDValidationException $err) {
            return redirect()->route('login')->withErrors([
                'social' => 'Could not authenticate with Twitch.'
            ]);
        }

        $model = $this->completeAuth($auth, $user, 'twitch_id');

        return redirect()->action('GamesController@list');
    }

    /**
     * Disconnect the user's Twitch.tv account from their recgam.es account.
     */
    public function twitchDisconnect(AuthManager $auth)
    {
        return $this->disconnect($auth->user(), 'twitch_id');
    }
}
