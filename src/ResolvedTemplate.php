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
 * A template together with the search path it was resolved from.
 *
 * The path is what makes resumption possible: to continue past a template --
 * to render the one it shadows -- a caller has to say where in the search path
 * to resume from, so a bare \Closure is not enough to walk a chain more than
 * one step.
 *
 * @package Aura.View
 *
 */
final class ResolvedTemplate
{
    /**
     *
     * Constructor.
     *
     * @param string $name The template name, as asked for.
     *
     * @param \Closure $template The template itself.
     *
     * @param string $path The search path directory it was resolved from.
     *
     */
    public function __construct(
        public readonly string $name,
        public readonly \Closure $template,
        public readonly string $path,
    ) {
    }
}
