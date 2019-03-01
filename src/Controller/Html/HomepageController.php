<?php
declare(strict_types = 1);

namespace App\Controller\Html;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage", methods={Request::METHOD_GET})
     * @Template()
     *
     * @return array
     */
    public function indexAction():array
    {
        return [
            'user' => $this->getUser(),
        ];
    }
}
