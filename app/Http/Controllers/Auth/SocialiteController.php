<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use Illuminate\Http\Request;
use SocialiteProviders\Manager\Config;
use SocialiteProviders\Steam\OpenIDValidationException;
use SocialiteProviders\Manager\Contracts\ConfigInterface;

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
    public function steamCallback()
    {
        try {
            $user = $this->steam()->user();
        } catch (OpenIDValidationException $err) {
            return redirect()->route('login')->withErrors([
                'social' => 'Could not authenticate with Steam.'
            ]);
        }

        abort(500, 'Steam login is not yet implemented.');
    }
}
