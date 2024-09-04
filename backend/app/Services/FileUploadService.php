<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{

    /**
     * This handles file uploading
     *
     * @param UploadedFile $file
     * @param string $table_name
     * @return string
     */
    public function upload(UploadedFile $file, String $table_name): string
    {
        try {
            $file_name = $this->getFilename($file);
            $file_path = $this->getFilePath('uploads', $table_name, $file_name);

            if (env('FILESYSTEM_DISK', 'local') == 'local') {
                Storage::disk('public')->put($file_path, $file->get());
            } else {
                /**
                 * add other logic here incase the
                 * client have their own storage bucket
                 */
            }

            return $file_path;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Handles generating of file name
     *
     * @param UploadedFile $file
     * @return string
     */
    private function getFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $rand_str = Str::random(30);
        $date = now()->format('YmdHis');

        return "$date-$rand_str.$extension";
    }

    /**
     * Handles generation of file path
     *
     * @param string $table_name
     * @return string
     */
    private function getFilePath(string $root_folder, string $table_name, string $file_name): string
    {
        return "/$root_folder/$table_name/$file_name";
    }

    /**
     * Get file size either kb or mb
     *
     * @param UploadedFile $file
     * @return string
     */
    public function getFileSize(UploadedFile $file): string
    {
        $bytes = $file->getSize();
        $kb = number_format($bytes / 1000, 2);
        $mb = number_format($bytes / 1048576, 2);

        return $mb >= 1 ? "$mb mb" : "$kb kb";
    }
}
