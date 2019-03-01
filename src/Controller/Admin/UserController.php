<?php
declare(strict_types = 1);

namespace App\Controller\Admin;

use App\Form\DataMapper\SerializerDataMapper;
use App\Form\Type\EasyAdminMessageFormType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class UserController extends EasyAdminController
{
    /**
     * @var Serializer
     */
    private $serializer;
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @param Serializer          $serializer
     * @param MessageBusInterface $messageBus
     */
    public function __construct(Serializer $serializer, MessageBusInterface $messageBus)
    {
        $this->serializer = $serializer;
        $this->messageBus = $messageBus;
    }

    /**
     * @inheritDoc
     */
    protected function createNewEntity()
    {
        $dataClass = $this->entity['new']['message'];
        return new $dataClass();
    }

    /**
     * @inheritDoc
     */
    protected function createNewForm($message, array $entityProperties)
    {
        return $this->createForm(EasyAdminMessageFormType::class, $message, [
            'data_class' => get_class($message),
            'fields'     => $entityProperties,
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function newAction()
    {
        $message = $this->createNewEntity();
        $easyadmin = $this->request->attributes->get('easyadmin');
        $easyadmin['item'] = $message;
        $this->request->attributes->set('easyadmin', $easyadmin);

        $fields = $this->entity['new']['fields'] ?? [];

        $newForm = $this->createNewForm($message, $fields);
        $newForm->handleRequest($this->request);
        if ($newForm->isSubmitted() && $newForm->isValid()) {
            $this->messageBus->dispatch($newForm->getData());

            return $this->redirectToReferrer();
        }

        $parameters = [
            'form' => $newForm->createView(),
            'entity_fields' => $fields,
            'entity' => $message,
        ];


        return $this->executeDynamicMethod('render<EntityName>Template', ['new', $this->entity['templates']['new'], $parameters]);
    }
}
