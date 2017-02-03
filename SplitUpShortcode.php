<?php
namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class SplitUpShortcode extends Shortcode
{
    public function init()
    {
        $this->shortcode->getHandlers()->add('splitup', function(ShortcodeInterface $sc) {

            $content = $sc->getContent();
            $tag = $sc->getParameter('tag', 'span');
            $wrap_spaces = filter_var($sc->getParameter('wrapspace', false), FILTER_VALIDATE_BOOLEAN);

            $result = '';
            $chars = preg_split('/(?<!^)(?!$)/u', $content);
            foreach ($chars as $char) {
                if ($char == ' ' && !$wrap_spaces) {
                    $result .= $char;
                } else {
                    $result .= '<'.$tag.'>'.$char.'</'.$tag.'>';
                }
            }

            return $result;
        });
    }
}
