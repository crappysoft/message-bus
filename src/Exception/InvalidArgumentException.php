<?php
declare(strict_types = 1);

namespace App\Exception;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class InvalidArgumentException extends \InvalidArgumentException implements ExceptionInterface
{
}
