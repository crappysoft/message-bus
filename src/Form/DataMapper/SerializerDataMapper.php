<?php
declare(strict_types = 1);

namespace App\Form\DataMapper;

use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class SerializerDataMapper implements DataMapperInterface
{
    /**
     * @var Serializer
     */
    private $serializer;
    /**
     * @var string
     */
    private $dataClass;

    /**
     * @param Serializer $serializer
     * @param string     $dataClass
     */
    public function __construct(Serializer $serializer, string $dataClass)
    {
        $this->serializer = $serializer;
        $this->dataClass  = $dataClass;
    }

    /**
     * {@inheritDoc}
     */
    public function mapDataToForms($data, $forms)
    {
        $normalizedData = $this->serializer->normalize($data);
        if (!is_array($normalizedData)) {
            throw new \RuntimeException('Something went wrong during normalization');
        }

        foreach ($forms as $name => $form) {
            $data = isset($normalizedData[$name]) ? $normalizedData[$name] : null;
            $form->setData($data);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function mapFormsToData($forms, &$data)
    {
        $formData = [];
        foreach ($forms as $name => $form) {
            $formData[$name] = $form->getData();
        }

        $data = $this->serializer->denormalize($formData, $this->dataClass);
    }
}
