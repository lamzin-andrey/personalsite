<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PhdAdminController extends AbstractController
{

	//phdagetmessages.json

	/**
	 * @Route("/phdadmin/request/{requestId}", name="phdadmin_request")
	 */
	public function phpadminRequest($requestId)
	{
		return $this->render('phd_admin/index.html.twig', [
			'controller_name' => 'PhdAdminController',
		]);
	}

    /**
     * @Route("/phdadmin", name="phd_admin")
     */
    public function index()
    {
        return $this->render('phd_admin/index.html.twig', [
            'controller_name' => 'PhdAdminController',
        ]);
    }



}
