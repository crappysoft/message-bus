<?php
declare(strict_types = 1);

namespace App\Response;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class Api
{
    private const STATUS_KEY = 'status';
    private const DATA_KEY = 'response';
    private const ERRORS_KEY = 'errors';

    private const STATUS_SUCCESS = 'success';
    private const STATUS_ERROR = 'error';

    /**
     * @param mixed|null $data
     *
     * @return array
     */
    public static function success($data = null): array
    {
        return [
            self::STATUS_KEY => self::STATUS_SUCCESS,
            self::DATA_KEY   => $data,
        ];
    }

    /**
     * @param array|null $errors
     *
     * @return array
     */
    public static function error(array $errors = null): array
    {
        return [
            self::STATUS_KEY => self::STATUS_ERROR,
            self::ERRORS_KEY => $errors,
        ];
    }
}
