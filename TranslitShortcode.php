<?php
namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class TranslitShortcode extends Shortcode
{
    public function init()
    {
        $this->shortcode->getHandlers()->add('translit', function(ShortcodeInterface $sc) {

            $result = '';

            if (class_exists('\Transliterator')) {
                $content = $sc->getContent();
                $rule = $sc->getParameter('rule', 'Any-Latin; Latin-ASCII');
                $transliterator = \Transliterator::create($rule);
                if ($transliterator) {
                    $result = $transliterator->transliterate($content);
                }
            }

            return $result;
        });
    }
}