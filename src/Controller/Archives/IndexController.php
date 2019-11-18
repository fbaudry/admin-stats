<?php

namespace App\Controller\Archives;

use App\Entity\League;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    public static function getSubscribedServices()
    {
        return \array_merge(parent::getSubscribedServices(), [

        ]);
    }

    /**
     * @Route("/archives", name="app_archives_index")
     */
    public function __invoke(): Response
    {
        return $this->render('archives/index.html.twig');
    }
}
