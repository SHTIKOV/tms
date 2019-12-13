<?php declare (strict_types=1);

namespace Core;

use Psr\Http\Message\ResponseInterface;

/**
 * @author Константин Штыков (shtykov)
 */
interface BaseControllerInterface {

    /**
     * Render method from twig
     *
     * @param string $template
     * @param array $params
     * @return ResponseInterface
     */
    public function render (string $template, array $params = []): ResponseInterface;

    /**
     * Redirect to route
     *
     * @param string $route
     * @return void
     */
    public function redirectTo (string $route): void;
}
