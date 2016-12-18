<?php
namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class RandomShortcode extends Shortcode
{
    public function init()
    {
        $this->shortcode->getHandlers()->add('random', function(ShortcodeInterface $sc) {
            $delimeter = $sc->getParameter('delimeter', '|');
            $result = $sc->getContent();
            if ($delimeter) {
                $array = explode($delimeter, $sc->getContent());
                $result = $array[mt_rand(0, count($array)-1)];
            }
            return $result;
        });
    }
}