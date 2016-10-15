<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Http\Request;

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

        $tmpPath = $file->path();
        $path = $this->fs->putFile('recordings', $file);

        $filename = $file->getClientOriginalName();

        do {
            $slug = str_random(6);
        } while (RecordedGame::where('slug', $slug)->count() > 0);

        // Save the recorded game file metadata.
        $model = new RecordedGame([
            'slug' => $slug,
            'path' => $path,
            'filename' => $filename,
        ]);

        $model->save();

        return redirect()->action('GamesController@show', $model->slug);
    }

    public function list()
    {
        $recs = RecordedGame::orderBy('created_at', 'desc')->paginate(32);
        return view('recorded_games_list', [
            'recordings' => $recs,
        ]);
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
