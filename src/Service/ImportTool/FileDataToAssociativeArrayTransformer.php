<?php

declare(strict_types=1);

namespace App\Service\ImportTool;

class FileDataToAssociativeArrayTransformer
{
    /**
     * @param array $someCasualArray
     * @return array
     */
    public function transformArrayToAssociative(array $someCasualArray): array
    {
        $headers = $someCasualArray[0];
        unset($someCasualArray[0]);
        $associativeArray = [];

        foreach ($someCasualArray as $row) {
            $newRow = [];

            foreach ($headers as $key => $value) {
                $newRow[$value] = $row[$key];
            }
            $associativeArray[] = $newRow;
        }

        return $associativeArray;
    }
}