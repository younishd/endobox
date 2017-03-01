<?php

/**
 * This file is part of endobox.
 *
 * (c) 2015-2017 YouniS Bensalah <younis.bensalah@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace endobox;

/**
 * Render as a PHP template with data variables.
 */
class EvalRendererDecorator extends RendererDecorator
{

    public function render(Renderable $input, array &$data = null, array $shared = null) : string
    {
        // I shall explain what's going on here:
        //
        // Our goal is to get the result of rendering everything and then pass all that through eval().
        // Except our template is expecting the data to be in form of simple variables.
        //
        // So first, we render everything using the previous renderer.
        // Then we make sure that we actually have some PHP code somewhere within the template
        // (otherwise there'd be no point in eval()ing anything and we just return the code as is).
        //
        // Now comes the interesting part: We create a closure that receives 3 arguments
        // that are our code, data, and shared data. The formal parameter names are intentionally weird underscores,
        // because we don't want to waste good variable names inside the template.
        // The closure extracts the data and shared data arrays as variables.
        //
        // At the end of the day we want to unset any helper variable that we have used inside the closure.
        // For the foreach counter variable, the data, and shared array, that's straightforward:
        // we just call unset and we're good.
        // The trickier one is the variable containing the code. We can't unset it just yet, because we still need
        // it for the eval()ing. The trick was to concat the unset code with the actual template code and pass it
        // to eval().
        // The effect is that PHP will concat the strings first, then pass it to eval where we can now safely unset the
        // temporariy variable.
        // The output of eval() is captured using output buffering.
        //
        // The reason why we use a closure for this job is that we don't want to eval() the templates in the scope
        // of the render() method.
        $code = parent::render($input, $data, $shared);
        if (\strpos($code, '<?') !== false) {
            return (function (&$_, &$__, &$___) {
                if ($__ !== null) {
                    \extract($__, EXTR_SKIP | EXTR_REFS);
                }
                if ($___ !== null) {
                    foreach ($___ as &$____) {
                        \extract($____, EXTR_SKIP | EXTR_REFS);
                    }
                }
                unset($____);
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
