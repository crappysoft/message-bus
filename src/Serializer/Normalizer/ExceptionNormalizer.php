<?php
declare(strict_types = 1);

namespace App\Serializer\Normalizer;

use App\Exception\NotFoundException;
use App\Exception\InvalidArgumentException;
use Assert\InvalidArgumentException as AssertInvalidArgumentException;
use App\Response\ApiErrorItem;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class ExceptionNormalizer implements NormalizerInterface
{
    private const EXCEPTION_WHITE_LIST = [
        NotFoundException::class,
        InvalidArgumentException::class,
        AssertInvalidArgumentException::class,
    ];

    private const DEFAULT_ERROR_MESSAGE = 'Something went wrong';
    private const DEFAULT_CODE = 0;

    /**
     * {@inheritDoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
        if (!$object instanceof \Exception) {
            throw new InvalidArgumentException(sprintf(
                '$object must be instanceof "%s". "%s" given',
                \Exception::class,
                is_object($object) ? get_class($object) : gettype($object)
            ));
        }

        if (in_array(get_class($object), self::EXCEPTION_WHITE_LIST)) {
            return [
                ApiErrorItem::asArray($object->getCode(), '', $object->getMessage()),
            ];
        }

        return [
            ApiErrorItem::asArray(self::DEFAULT_CODE, '', $object->getMessage()),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof \Exception;
    }
}
