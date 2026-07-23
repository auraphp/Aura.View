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
 * A helper name is already taken, and the registration did not ask to
 * override it.
 *
 * @package Aura.View
 *
 */
class HelperAlreadyRegistered extends Exception
{
}
