<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/MIT-license.php MIT
 *
 */
declare(strict_types=1);

namespace Aura\View\Exception;

use Aura\View\Exception as Exception;

/**
 *
 * parent() had nothing to resume into, and strict parent mode is on.
 *
 * Outside strict mode this is not an exception at all -- parent() returns ''
 * -- because a template that shadows nothing is a normal state. Strict mode
 * exists so that development and CI can tell that apart from a search path
 * that is misconfigured.
 *
 * @package Aura.View
 *
 */
class ParentNotFound extends Exception
{
}
