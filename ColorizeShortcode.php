<?php
namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class ColorizeShortcode extends Shortcode
{
    public function init()
    {
        $this->shortcode->getHandlers()->add('colorize', function(ShortcodeInterface $sc) {
            $color = $sc->getParameter('color', $sc->getBbCode());
            $background = $sc->getParameter('background');
            $style = '';
            if ($color) $style = 'color: '.$color.';';
            if ($background) $style .= 'background-color: '.$background.';';
            return '<span style="'.$style.'">'.$sc->getContent().'</span>';
        });
    }
}
