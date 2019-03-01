<?php
declare(strict_types = 1);

namespace App\Form\Type;

use App\Message\ChangeNameMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class ChangeNameType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName')
            ->add('lastName')
            ->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ChangeNameMessage::class,
        ]);
    }

    public function mapDataToForms($data, $forms)
    {
        if (null === $data) {
            return;
        }
        if (!$data instanceof ChangeNameMessage) {
            throw new UnexpectedTypeException($data, ChangeNameMessage::class);
        }

        $forms = iterator_to_array($forms);
        $forms['firstName']->setData($data->getFirstName());
        $forms['lastName']->setData($data->getLastName());
    }

    public function mapFormsToData($forms, &$data)
    {
        $forms = iterator_to_array($forms);
        return new ChangeNameMessage(
            $forms['firstName']->getData(),
            $forms['lastName']->getData()
        );
    }
}
