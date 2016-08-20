<?php

/**
 * This file is part of endobox.
 *
 * (c) 2015-2016 YouniS Bensalah <younis.bensalah@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace endobox;

/**
 *
 */
class EvalRendererDecorator extends RendererDecorator
{

    /**
     *
     */
    public function render(Renderable $input, array &$data = null, array $shared = null) : string
    {
        $code = parent::render($input, $data, $shared);
        if (\strpos($code, '<?') !== false) {
            return (function (&$_, &$__, &$___) {
                if ($__ !== null) {
                    \extract($__, EXTR_SKIP | EXTR_REFS);
                }
                if ($___ !== null) {
                    foreach ($___ as &$x) {
                        \extract($x, EXTR_SKIP | EXTR_REFS);
                    }
                }
                unset($___);
                unset($__);
                \ob_start();
                eval('unset($_)?>' . $_);
                return \ob_get_clean();
            })($code, $data, $shared);
        }
        return $code;
    }

}
