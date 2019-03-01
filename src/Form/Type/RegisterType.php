<?php
declare(strict_types = 1);

namespace App\Form\Type;

use App\Message\RegisterUserMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class RegisterType extends AbstractType implements DataMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username')
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('firstName')
            ->add('lastName')
            ->add('submit', SubmitType::class)
            ->setDataMapper($this);
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RegisterUserMessage::class,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function mapDataToForms($data, $forms)
    {
        if (null === $data) {
            return;
        }
        if (!$data instanceof RegisterUserMessage) {
            throw new UnexpectedTypeException($data, RegisterUserMessage::class);
        }

        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);
        $forms['username']->setData($data->getUsername());
        $forms['email']->setData($data->getEmail());
        $forms['password']->setData($data->getPassword());
        $forms['firstName']->setData($data->getFirstName());
        $forms['lastName']->setData($data->getLastName());
    }

    /**
     * {@inheritDoc}
     */
    public function mapFormsToData($forms, &$data)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        $data = new RegisterUserMessage(
            $forms['username']->getData(),
            $forms['email']->getData(),
            $forms['password']->getData(),
            $forms['firstName']->getData(),
            $forms['lastName']->getData()
        );
    }
}
