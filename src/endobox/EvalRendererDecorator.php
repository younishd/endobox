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

class EvalRendererDecorator extends RendererDecorator
{

    public function render(Renderable $input, array &$data = null) : string
    {
        $code = parent::render($input, $data);
        if (\strpos($code, '<?php') !== false) {
            return (function (&$_) use (&$data) {
                \extract($data, EXTR_SKIP | EXTR_REFS);
                \ob_start();
                eval('?>' . $_);
                return \ob_get_clean();
            })($code);
        }
        return $code;
    }

}
