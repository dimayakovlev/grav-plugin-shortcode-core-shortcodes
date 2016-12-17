<?php
namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class ColorShortcode extends Shortcode
{
    public function init()
    {
        $this->shortcode->getHandlers()->add('color', function(ShortcodeInterface $sc) {
            $color = $sc->getParameter('color', trim($sc->getParameterAt(0), '="\''));
            $background = $sc->getParameter('background');
            $style = '';
            if ($color) $style = 'color: '.$color.';';
            if ($background) $style .= 'background-color: '.$background.';';
            return '<span style="'.$style.'">'.$sc->getContent().'</span>';
        });
    }
}