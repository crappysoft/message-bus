<?php
declare(strict_types = 1);

namespace Dredd\Hook;

use Dredd\Hook;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class Unauthorized extends Hook
{
    /**
     * {@inheritDoc}
     */
    public function beforeEach(&$transaction): void
    {
        if ($transaction->expected->statusCode != Response::HTTP_UNAUTHORIZED) {
            return ;
        }

        $transaction->skip = false;
    }
}
