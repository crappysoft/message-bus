<?php
declare(strict_types = 1);

namespace Dredd\Hook;

use Dredd\Hook;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class BadRequest extends Hook
{
    /**
     * {@inheritDoc}
     */
    public function beforeEach(&$transaction): void
    {
        if ($transaction->expected->statusCode != Response::HTTP_BAD_REQUEST) {
            return ;
        }

        $transaction->skip = false;
        $payload = json_decode($transaction->request->body, true);
        array_pop($payload);

        $transaction->request->body = json_encode($payload);
    }
}
