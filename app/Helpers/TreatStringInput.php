<?php

namespace App\Helpers;


class TreatStringInput
{
    function convertStringToFloat(string $input): float
    {
        $result =  str_replace(['.', ','], ['', '.'], $input);
        return floatval($result);
    }
}
