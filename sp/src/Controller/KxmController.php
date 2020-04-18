<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class KxmController extends AppBaseController
{
    /**
     * @Route("/kxm", name="kxm")
     */
    public function index()
    {
        return $this->render('kxm/index.html.twig', [
            'controller_name' => 'KxmController',
        ]);
    }
}
