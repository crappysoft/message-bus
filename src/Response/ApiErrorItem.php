<?php
declare(strict_types = 1);

namespace App\Response;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class ApiErrorItem
{
    /**
     * @param string|integer|null $code
     * @param string              $name
     * @param string              $description
     *
     * @return array
     */
    public static function asArray($code, string $name, string $description): array
    {
        return [
            'code'        => (string) $code,
            'name'        => $name,
            'description' => $description,
        ];
    }
}
