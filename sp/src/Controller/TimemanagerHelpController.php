<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class TimemanagerHelpController extends AbstractController
{
    /**
     * @Route("/help", name="timemanager_help")
     */
    public function index(TranslatorInterface $t)
    {
        return $this->render('timemanager_help/index.html.twig', [
            'pageHeading' => $t->trans('Cron Friend Help'),
        ]);
    }
}
