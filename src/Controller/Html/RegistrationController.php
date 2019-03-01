<?php
declare(strict_types = 1);

namespace App\Controller\Html;

use App\Form\Type\RegisterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="registration", methods={Request::METHOD_GET, Request::METHOD_POST})
     * @Template()
     *
     * @param Request             $request
     * @param MessageBusInterface $bus
     *
     * @return array|RedirectResponse
     */
    public function registerAction(Request $request, MessageBusInterface $bus)
    {
        $form = $this->createForm(RegisterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $command = $form->getData();
            $bus->dispatch($command);

            return $this->redirectToRoute('homepage');
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
