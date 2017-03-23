<?php
namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class RandomShortcode extends Shortcode
{
    public function init()
    {
        $this->shortcode->getHandlers()->add('random', function(ShortcodeInterface $sc) {
            
            $content = $sc->getContent();
            
            if ($content) {
                $delimeter = $sc->getParameter('delimeter', ',');
                $enclosure = $sc->getParameter('enclosure', '"');
                $escape = $sc->getParameter('escape', "\\");
                $array = str_getcsv($content, $delimeter, $enclosure, $escape);
                $content = $array[mt_rand(0, count($array)-1)];
            }
            
            return $content;

        });
    }
}
