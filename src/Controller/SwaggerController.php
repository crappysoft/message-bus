<?php
declare(strict_types = 1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class SwaggerController extends AbstractController
{
    /**
     * @Route(path="/api")
     *
     * @return Response
     */
    public function indexAction(): Response
    {
        return $this->render('Swagger/index.html.twig');
    }

    /**
     * @Route(path="/swagger")
     *
     * @return BinaryFileResponse
     */
    public function swaggerAction(): BinaryFileResponse
    {
        return new BinaryFileResponse(__DIR__ . '/../../swagger.yaml');
    }
}
