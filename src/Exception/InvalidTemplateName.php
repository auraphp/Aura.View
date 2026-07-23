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
 * A template name could not be parsed; it has more than one `::` separator.
 *
 * @package Aura.View
 *
 */
class InvalidTemplateName extends Exception
{
}
