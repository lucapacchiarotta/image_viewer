<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class FilesUploader
{
    public function uploadFile(Request $request, string $fileName, string $uploadDirectory): ?string
    {
        /** @var UploadedFile $fileToUpload */
        $fileToUpload = $request->files->get($fileName);

        if ($fileToUpload) {
            $filename = sprintf(
                '%s.%s',
                pathinfo($fileToUpload->getClientOriginalName(), PATHINFO_FILENAME),
                pathinfo($fileToUpload->getClientOriginalName(), PATHINFO_EXTENSION),
            );

            $fileToUpload->move(
                $uploadDirectory,
                $filename,
            );

            return $filename;
        }

        return null;
    }
}
