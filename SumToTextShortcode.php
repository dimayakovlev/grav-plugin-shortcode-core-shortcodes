<?php
namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class SumToTextShortcode extends Shortcode
{
    public function init()
    {
        $this->shortcode->getHandlers()->add('sumtotext', function(ShortcodeInterface $sc) {
            $currency = $sc->getParameter('currency', false);
            if ($currency) {
                $currency = array_map('trim', explode(',', $currency));
                if (count($currency) != 8) return $sc->getContent();
                $currency[3] = intval(boolval($currency[3]));
                $currency[7] = intval(boolval($currency[7]));
                list($currency[1], $currency[0]) = array_chunk($currency, 4);
            } else {
                $currency = [
                    ['копейка', 'копейки', 'копеек', 1],
                    ['рубль', 'рубля', 'рублей', 0]
                ];
            }
            $number = floatval(str_replace(',', '.', $sc->getContent()));
            list($rub, $kop) = explode('.', sprintf("%015.2f", $number));
            $full = filter_var($sc->getParameter('full', false), FILTER_VALIDATE_BOOLEAN);
            $ucfirst = filter_var($sc->getParameter('capital', false), FILTER_VALIDATE_BOOLEAN);
            $nul = 'ноль';
            $ten = [
                ['', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь','девять'],
                ['', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь','девять']
            ];
            $a20 = ['десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать'];
            $tens = ['', '', 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто'];
            $hundreds = ['', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот',  'семьсот', 'восемьсот', 'девятьсот'];
            $unit = [
                $currency[0], # 1/100 of base currency unit
                $currency[1], # Base currency unit
                ['тысяча', 'тысячи', 'тысяч', 1],
                ['миллион', 'миллиона', 'миллионов', 0],
                ['миллиард', 'миллиарда', 'миллиардов', 0]
            ];
            $out = [];
            if (intval($rub) > 0) {
                foreach(str_split($rub, 3) as $key => $value) {
                    if (!intval($value)) continue;
                    $key = count($unit) - $key - 1;
                    $gender = $unit[$key][3];
                    list($i1, $i2, $i3) = array_map('intval', str_split($value, 1));
                    $out[] = $hundreds[$i1];
                    if ($i2 > 1) {
                        $out[] = $tens[$i2] . ' ' . $ten[$gender][$i3];
                    } else {
                        $out[] = $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3];
                    }
                    if ($key > 1) $out[] = $this->morph($value, $unit[$key][0], $unit[$key][1], $unit[$key][2]);
                }
            } else {
                $out[] = $nul;
            }
            $out[] = $this->morph(intval($rub), $unit[1][0], $unit[1][1], $unit[1][2]);
            if ($full) {
                if (intval($kop) > 0) {
                    list($i1, $i2) = array_map('intval', str_split($kop, 1));
                    $gender = $unit[0][3];
                    if ($i1 > 1) {
                        $out[] = $tens[$i1] . ' ' . $ten[$gender][$i2];
                    } else {
                        $out[] = $i1 > 0 ? $a20[$i2] : $ten[$gender][$i2];
                    }
                } else {
                    $out[] = $nul;
                }
                $out[] = $this->morph(intval($kop), $unit[0][0], $unit[0][1], $unit[0][2]);
            } else {
                $out[] = $kop . ' ' . $this->morph($kop, $unit[0][0], $unit[0][1], $unit[0][2]);
            }
            $result = trim(preg_replace('/ {2,}/', ' ', join(' ', $out)));
            if ($ucfirst) $result = $this->mb_ucfirst($result);
            return $result;
        });
    }

    private function morph($n, $f1, $f2, $f5)
    {
        $n = abs(intval($n)) % 100;
        if ($n > 10 && $n < 20) return $f5;
        $n = $n % 10;
        if ($n > 1 && $n < 5) return $f2;
        if ($n == 1) return $f1;
        return $f5;
    }

    private function mb_ucfirst($str)
    {
        $fc = mb_strtoupper(mb_substr($str, 0, 1));
        return $fc.mb_substr($str, 1);
    }

}