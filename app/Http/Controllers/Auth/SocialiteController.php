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

        $model = User::fromSteamId($user->id);
        if (!$model && $auth->check()) {
            // Associate logged-in user with this Steam ID.
            $model = $auth->user();
            $model->update([
                'steam_id'=> $user->id,
            ]);
        } else if (!$model) {
            // Create a new user for this Steam ID.
            // TODO deal with the case where the username is taken.
            $model = User::create([
                'name' => $user->nickname,
                'steam_id' => $user->id,
            ]);
        }

        // Log in as this Steam ID.
        $auth->login($model);

        return redirect()->action('GamesController@list');
    }
}
