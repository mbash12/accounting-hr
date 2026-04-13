<?php

namespace App\Helpers;

class TerbilangHelper
{
    private static function terbilang($number)
    {
        $number = abs($number);
        $read = array('', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas');
        
        $temp = "";
        
        if ($number < 12) {
            $temp = " " . $read[$number];
        } else if ($number < 20) {
            $temp = self::terbilang($number - 10) . " Belas";
        } else if ($number < 100) {
            $temp = self::terbilang((int)($number / 10)) . " Puluh" . self::terbilang($number % 10);
        } else if ($number < 200) {
            $temp = " Seratus" . self::terbilang($number - 100);
        } else if ($number < 1000) {
            $temp = self::terbilang((int)($number / 100)) . " Ratus" . self::terbilang($number % 100);
        } else if ($number < 2000) {
            $temp = " Seribu" . self::terbilang($number - 1000);
        } else if ($number < 1000000) {
            $temp = self::terbilang((int)($number / 1000)) . " Ribu" . self::terbilang($number % 1000);
        } else if ($number < 1000000000) {
            $temp = self::terbilang((int)($number / 1000000)) . " Juta" . self::terbilang($number % 1000000);
        } else if ($number < 1000000000000) {
            $temp = self::terbilang((int)($number / 1000000000)) . " Miliar" . self::terbilang($number % 1000000000);
        } else if ($number < 1000000000000000) {
            $temp = self::terbilang((int)($number / 1000000000000)) . " Triliun" . self::terbilang($number % 1000000000000);
        }

        return $temp;
    }

    public static function convert($number)
    {
        $result = self::terbilang($number);
        return trim(preg_replace('/\s+/', ' ', $result));
    }
}
