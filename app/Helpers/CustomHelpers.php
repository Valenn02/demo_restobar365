<?php

namespace App\Helpers;

use Illuminate\Support\BigInteger;

class CustomHelpers
{
    public static function calculaDigitoMod11($cadena, $numDig, $limMult, $x10)
    {
        if (!$x10) {
            $numDig = 1;
        }

        for ($n = 1; $n <= $numDig; $n++) {
            $suma = 0;
            $mult = 2;

            for ($i = strlen($cadena) - 1; $i >= 0; $i--) {
                $suma += ($mult * intval(substr($cadena, $i, 1)));

                if (++$mult > $limMult) {
                    $mult = 2;
                }
            }

            if ($x10) {
                $dig = (($suma * 10) % 11) % 10;
            } else {
                $dig = $suma % 11;
            }

            if ($dig == 10) {
                $cadena .= "1";
            }

            if ($dig == 11) {
                $cadena .= "0";
            }

            if ($dig < 10) {
                $cadena .= strval($dig);
            }
        }

        return substr($cadena, strlen($cadena) - $numDig, strlen($cadena));
    }

    public static function base16($pString)
    {
        $vValor = gmp_init($pString, 10); // Convierte decimal a GMP
        return gmp_strval($vValor, 16); // Convierte GMP a hexadecimal
    }

    public static function base10($pString)
    {
        $vValor = gmp_init($pString, 16); // Convierte hexadecimal a GMP
        return gmp_strval($vValor); // Convierte GMP a decimal
    }
}



