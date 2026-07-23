<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
declare(strict_types=1);

namespace Aura\View\Exception;

use Aura\View\Exception as Exception;

/**
 *
 * The helpers object is not valid.
 *
 * @package Aura.View
 *
 * @deprecated since 6.0. The helpers parameter is now typed `?object`, so PHP
 * itself rejects a non-object with a \TypeError before this exception could be
 * thrown. Retained only so that existing `catch` blocks still resolve; nothing
 * in this package throws it. It will be removed in 7.0.
 *
 */
class InvalidHelpersObject extends Exception
{
}
