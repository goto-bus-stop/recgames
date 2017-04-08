<?php

namespace App\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'steam_id',
        'twitch_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function fromSteamId($id)
    {
        return self::where('steam_id', $id)->first();
    }

    /**
     * Relationship to the games uploaded by this user.
     */
    public function uploaded()
    {
        return $this->hasMany(RecordedGame::class, 'uploader_id');
    }

    /**
     * Relationship to the sets created by this user.
     */
    public function sets()
    {
        return $this->hasMany(GameSet::class, 'author_id');
    }

    /**
     * Relationship to the games this user participated in.
     */
    public function participated()
    {
        // Once RecAnalyst supports extracting Steam IDs from records,
        // this relationship can fetch games with players with that Steam ID.
        throw new \Exception('Unimplemented');
    }
}
