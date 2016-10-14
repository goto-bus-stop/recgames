<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Http\Request;
use RecAnalyst\RecordedGame as RecAnalyst;

use App\RecordedGame;
use App\Http\Requests;

class GamesController extends Controller
{
    public function __construct()
    {
        $this->fs = Storage::disk('local');
    }

    /**
     * Show the recorded game upload form.
     */
    public function uploadForm()
    {
        return view('upload_form');
    }

    /**
     * Upload and save a recorded game file.
     */
    public function upload(Request $request)
    {
        $this->validate($request, [
            'recorded_game' => 'required',
        ]);

        $file = $request->file('recorded_game');

        // Redirect to the analysis page if this exact file was uploaded
        // before.
        $storagePath = 'recordings/' . $file->hashName();
        if ($this->fs->exists($storagePath)) {
            $rec = RecordedGame::where('path', $storagePath)->first();
            if ($rec) {
                return redirect()->action('GamesController@show', $rec->slug);
            }
        }

        $path = $this->fs->putFile('recordings', $file);

        $filename = $file->getClientOriginalName();

        // Attempt to generate a slug based on the players in the game. Fall back
        // to a filename-based slug if the player data can't be extracted properly.
        $defaultSlug = str_slug(substr($filename, 0, strrpos($filename, '.')));
        try {
            // TODO Doesn't work currently, because this path is relative to the
            // local Disk instance, not the current working directory.
            // Should add a ServiceProvider that makes `\RecAnalyst\RecordedGame`s
            // work with laravel Disks.
            $rec = new RecAnalyst($path);

            $teams = $rec->teams();
            $teamSlug = implode(' VS ', array_map(function ($team) {
                return implode(' ', array_column($team->players(), 'name'));
            }, $teams));

            if ($teamSlug) {
                $defaultSlug = str_slug($teamSlug);
            }
        } catch (\Exception $e) {
            // ¯\_(ツ)_/¯
        }

        // If this slug already exists, add some randomness in front so it's unique.'
        $slug = $defaultSlug;
        $attempts = 2;
        while (RecordedGame::where('slug', $slug)->count() > 0) {
            $slug = str_random($attempts) . '_' . $defaultSlug;
            $attempts++;
        }

        // Save the recorded game file metadata.
        $model = new RecordedGame([
            'slug' => $slug,
            'path' => $path,
            'filename' => $filename,
        ]);

        $model->save();

        return redirect()->action('GamesController@show', $model->slug);
    }

    /**
     * Show data about a recorded game file.
     */
    public function show(Request $request, $slug)
    {
        $rec = RecordedGame::where('slug', $slug)->first();

        return $rec;
    }
}
