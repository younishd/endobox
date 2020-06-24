<?php

/**
 * This file is part of endobox.
 *
 * (c) 2015-2020 Younis Bensalah <younis.bensalah@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace endobox;

class EvalRendererDecorator extends RendererDecorator
{

    public function render(Renderable $input, array &$data = null, array $shared = null) : string
    {
        $code = parent::render($input, $data, $shared);

        $context = $input->getContext();

        \set_error_handler(function ($errno, $errstr, $errfile, $errline, $errcontext) {
            if (\error_reporting() !== 0) {
                throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
            }
        });

        try {
            if (\strpos($code, '<?') !== false) {
                $code = (function (&$_, &$__, &$___) {
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
        } catch (\Throwable $e) {
            $code = \sprintf('%s<p>%s <strong>"%s"</strong> in %s line <strong>%d</strong></p>',
                    \ob_get_clean(),
                    \get_class($e),
                    $e->getMessage(),
                    $context,
                    $e->getLine());
        }

        \restore_error_handler();

        return $code;
    }

}
