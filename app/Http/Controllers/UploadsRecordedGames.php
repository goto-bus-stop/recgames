<?php

namespace App\Http\Controllers;

use RarArchive;
use ZipArchive;
use Illuminate\{
    Http\File,
    Support\Collection
};
use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\File\{
    UploadedFile,
    File as SymfonyFile
};

use App\Model\RecordedGame;
use App\Exceptions\JsonApiException;

trait UploadsRecordedGames
{
    protected function storeRecordedGame(SymfonyFile $file, $fs): RecordedGame
    {
        $tmpPath = $file->getRealPath();
        $hash = md5_file($tmpPath);
        $storageName = $hash . '.bin';

        // Reuse a previous recorded game resource if this same game was
        // uploaded before.
        if ($fs->exists('recordings/' . $storageName)) {
            $model = RecordedGame::where('path', $storageName)->first();
            if ($model) {
                return $model;
            }
        }

        $path = $fs->putFileAs('recordings', $file, $storageName, 'public');

        $filename = $file instanceof UploadedFile ? $file->getClientOriginalName() :
            $file->getFileName();

        // Save the recorded game file metadata.
        return (new RecordedGame([
            'path' => $path,
            'filename' => $filename,
            'hash' => $hash,
        ]))->generatedSlug();
    }

    protected function extract(SymfonyFile $file, FilesystemInterface $temp): Collection
    {
        $files = collect();

        if ($file->getMimeType() === 'application/zip' && class_exists(ZipArchive::class)) {
            $zip = new ZipArchive();
            $zip->open($file->getRealPath());

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                $temp->writeStream($name, $zip->getStream($name));
                $files->push(new File($temp->getPath() . $name));
            }

            $zip->close();
            unlink($file->getRealPath());
        } else if ($file->getMimeType() === 'application/x-rar' && class_exists(RarArchive::class)) {
            $rar = new RarArchive();
            $rar->open($file->getRealPath());

            foreach ($rar as $entry) {
                $temp->writeStream($entry->getName(), $entry->getStream());
                $files->push(new File($temp->getPath() . $entry->getName()));
            }

            $rar->close();
            unlink($file->getRealPath());
        } else {
            $files->push($file);
        }

        return $files;
    }
}
