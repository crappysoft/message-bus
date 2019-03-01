<?php
declare(strict_types = 1);

namespace App\Serializer\Normalizer;

use App\Response\ApiErrorItem;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class ValidationFailedExceptionNormalizer implements NormalizerInterface
{
    /**
     * {@inheritDoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
        if (!$object instanceof ValidationFailedException) {
            throw new InvalidArgumentException(sprintf(
                '$object must be instanceof "%s". "%s" given',
                ValidationFailedException::class,
                is_object($object) ? get_class($object) : gettype($object)
            ));
        }

        $errors = [];
        /** @var ConstraintViolationInterface $violation */
        foreach ($object->getViolations() as $violation) {
            $code         = $violation->getCode();
            $propertyPath = $violation->getPropertyPath();
            $message      = $violation->getMessage();

            $errors[] = ApiErrorItem::asArray($code, $propertyPath, $message);
        }

        return $errors;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof ValidationFailedException;
    }
}
