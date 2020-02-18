<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(TranslatorInterface $t)
    {
    	if (!$this->getUser()) {
    		return $this->redirectToRoute('login');
		}
    	$aData = [
    		'pageHeading' => $t->trans('Add task', [], 'tasks')
		];
        return $this->render('default/index.html.twig', $aData);
    }
}
