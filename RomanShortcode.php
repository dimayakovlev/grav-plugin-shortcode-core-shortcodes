<?php
namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class RomanShortcode extends Shortcode
{
    public function init()
    {
        $this->shortcode->getHandlers()->add('roman', function(ShortcodeInterface $sc) {
            $result = $sc->getContent();
            $number = trim($sc->getContent());
            if (filter_var($number, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
                $result = $this->toRoman($number);
                if (filter_var($sc->getParameter('lowercase'), FILTER_VALIDATE_BOOLEAN)) {
                    $result = strtolower($result);
                }
            } elseif (filter_var($number, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/^(?=[MDCLXVI])M*(C[MD]|D?C{0,3})(X[CL]|L?X{0,3})(I[XV]|V?I{0,3})$/i']])) {
                $result = $this->toArabic(strtoupper($number));
            }
            return $result;
        });
    }

    private function getNumbersArray()
    {
        return [
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        ];
    }

    private function toRoman($number)
    {
        $result = '';
        $numbers = $this->getNumbersArray();
        foreach($numbers as $key => $value) {
            $matches = intval($number / $value);
			$result .= str_repeat($key, $matches);
			$number = $number % $value;
        }
        return $result;
    }

    private function toArabic($number)
    {
        $result = '';
        $numbers = $this->getNumbersArray();
        foreach($numbers as $key => $value) {
            while (strpos($number, $key) === 0) {
                $result += $value;
                $number = substr($number, strlen($key));
            }
        }
        return $result;
    }
}