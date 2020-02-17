<?php

declare(strict_types=1);

namespace App\Service\Uploader;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Psr\Log\LoggerInterface;

class FileUploader
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * FileUploader constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param $uploadDir
     * @param $file
     * @param $filename
     */
    public function upload($uploadDir, $file, $filename): void
    {
        try {

            $file->move($uploadDir, $filename);
        } catch (FileException $e){
            $this->logger->error('failed to upload file: ' . $e->getMessage());
            throw new FileException('Failed to upload file');
        }
    }
}