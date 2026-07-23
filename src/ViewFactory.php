<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/MIT-license.php MIT
 *
 */
declare(strict_types=1);

namespace Aura\View;

/**
 *
 * Factory to create View objects.
 *
 * @package Aura.View
 *
 */
class ViewFactory
{
    /**
     *
     * Returns a new View instance.
     *
     * @param object|null $helpers An arbitrary helper manager for the View; if
     * not specified, uses the HelperRegistry from this package. This is typed
     * `object` rather than HelperRegistryInterface on purpose -- the View
     * reaches helpers only through `__call()`, so any object with a `__call()`
     * method works, including Aura.Html's _HelperLocator_.
     *
     * @param array<string, string|callable> $view_map A map of explicit
     * template names and locations in the view registry.
     *
     * @param list<string> $view_paths Filesystem paths to search for templates
     * in the view registry.
     *
     * @param array<string, string|callable> $layout_map A map of explicit
     * template names and locations in the layout registry.
     *
     * @param list<string> $layout_paths Filesystem paths to search for
     * templates in the layout registry.
     *
     */
    public function newInstance(
        ?object $helpers = null,
        array $view_map = [],
        array $view_paths = [],
        array $layout_map = [],
        array $layout_paths = []
    ): View {
        return new View(
            new TemplateRegistry($view_map, $view_paths),
            new TemplateRegistry($layout_map, $layout_paths),
            $helpers ?? new HelperRegistry()
        );
    }
}
