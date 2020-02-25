<?php

namespace App\Controller;

use App\Form\TaskManagerType;
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
		$oForm = $this->createForm(TaskManagerType::class);
    	$aData = [
    		'pageHeading' => $t->trans('Add task', [], 'tasks'),
			'formtoken' => $oForm->createView()->children['_token']->vars['value']
		];
        return $this->render('default/index.html.twig', $aData);
    }
}
