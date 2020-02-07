<?php

declare(strict_types=1);

namespace App\Service\Factory;

use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xml;

class ReaderFactory
{
    /**
     * @var object
     */
    private $reader;

    /**
     * @param $fileExtension
     * @return object|null
     */
    public function getFileReader($fileExtension)
    {
        switch ($fileExtension) {
            case 'csv':
                $this->reader = new Csv();
                break;
            case 'xlsx':
                $this->reader = new Xlsx();
                break;
            case 'xml':
                $this->reader = new Xml();
                break;
            default:
                return null;
        }
        return $this->reader;
    }
}
