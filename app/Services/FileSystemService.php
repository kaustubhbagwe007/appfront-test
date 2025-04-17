<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileSystemService
{
    public function store($file, $folder = 'uploads'): string
    {
        $name = str_replace(" ", "_", $file->getClientOriginalName());

        $fileName = time().'_'.$name;
        
        $file->storeAs($folder, $fileName, 'public');

        return 'storage/' . $folder . '/' . $fileName;
    }

    public function remove($fileName, $folder = 'uploads'): void
    {
        $file = "public/{$folder}/{$fileName}";

        Storage::delete($file);
    }
}