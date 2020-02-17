<?php

declare(strict_types=1);

namespace App\Service\Uploader;

class FileUploader
{
    /**
     * @param $uploadDir
     * @param $form
     * @return string
     */
    public function upload($uploadDir, $form): string
    {
        $file = $form->get('file')->getData();
        $fileName = $file->getClientOriginalName();
        $file->move($uploadDir, $fileName);

        return $uploadDir.'/'.$fileName;
    }
}