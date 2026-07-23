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
     *     $factory->newInstance(
     *         view: new ViewSpec(
     *             paths: ['/path/to/views'],
     *             namespaces: ['blog' => ['/path/to/blog/templates']],
     *         ),
     *         layout: new ViewSpec(paths: ['/path/to/layouts']),
     *     );
     *
     * @param object|null $helpers An arbitrary helper manager for the View; if
     * null, uses the HelperRegistry from this package. This is typed `object`
     * rather than HelperRegistryInterface on purpose -- the View reaches
     * helpers only through `__call()`, so any object with a `__call()` method
     * works, including Aura.Html's _HelperLocator_.
     *
     * @param ViewSpec|null $view The specification for the view registry; if
     * null, an empty registry is built.
     *
     * @param ViewSpec|null $layout The specification for the layout registry;
     * if null, an empty registry is built.
     *
     */
    public function newInstance(
        ?object $helpers = null,
        ?ViewSpec $view = null,
        ?ViewSpec $layout = null
    ): View {
        return new View(
            ($view ?? new ViewSpec())->newRegistry(),
            ($layout ?? new ViewSpec())->newRegistry(),
            $helpers ?? new HelperRegistry()
        );
    }
}
