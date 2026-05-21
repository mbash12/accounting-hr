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

    private static function english($number)
    {
        $number = abs(round($number));
        $ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
                 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen',
                 'Seventeen', 'Eighteen', 'Nineteen'];
        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        $temp = "";

        if ($number == 0) {
            $temp = " Zero";
        } else if ($number < 20) {
            $temp = " " . $ones[$number];
        } else if ($number < 100) {
            $temp = " " . $tens[(int)($number / 10)];
            if ($number % 10 > 0) {
                $temp .= " " . $ones[$number % 10];
            }
        } else if ($number < 1000) {
            $temp = " " . $ones[(int)($number / 100)] . " Hundred";
            if ($number % 100 > 0) {
                $temp .= " and" . self::english($number % 100);
            }
        } else if ($number < 1000000) {
            $temp = self::english((int)($number / 1000)) . " Thousand";
            if ($number % 1000 > 0) {
                if ($number % 1000 < 100) {
                    $temp .= " and" . self::english($number % 1000);
                } else {
                    $temp .= self::english($number % 1000);
                }
            }
        } else if ($number < 1000000000) {
            $temp = self::english((int)($number / 1000000)) . " Million";
            if ($number % 1000000 > 0) {
                $temp .= self::english($number % 1000000);
            }
        } else if ($number < 1000000000000) {
            $temp = self::english((int)($number / 1000000000)) . " Billion";
            if ($number % 1000000000 > 0) {
                $temp .= self::english($number % 1000000000);
            }
        } else if ($number < 1000000000000000) {
            $temp = self::english((int)($number / 1000000000000)) . " Trillion";
            if ($number % 1000000000000 > 0) {
                $temp .= self::english($number % 1000000000000);
            }
        }

        return $temp;
    }

    public static function convert($number)
    {
        if (app()->getLocale() === 'en') {
            return self::convertEnglish($number);
        }
        $result = self::terbilang($number);
        return trim(preg_replace('/\s+/', ' ', $result));
    }

    public static function convertEnglish($number)
    {
        $result = self::english($number);
        return trim(preg_replace('/\s+/', ' ', $result));
    }
}
