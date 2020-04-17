<?php

namespace App\Controller;

use App\Entity\PhdOperations;
use App\Form\PhdOperationsType;
use App\Service\AppService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/phd/operations")
 */
class PhdOperationsController extends AbstractController
{
    /**
     * @Route("/", name="phd_operations_index", methods={"GET"})
     */
    public function index(): Response
    {
		if (!$this->_hasPermissions()) {
			return $this->redirectToRoute('home');
		}
        $phdOperations = $this->getDoctrine()
            ->getRepository(PhdOperations::class)
            ->findAll();

        return $this->render('phd_operations/articles.html.twig', [
            'phd_operations' => $phdOperations,
        ]);
    }

    /**
     * @Route("/new", name="phd_operations_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
		if (!$this->_hasPermissions()) {
			return $this->redirectToRoute('home');
		}
        $phdOperation = new PhdOperations();
        $form = $this->createForm(PhdOperationsType::class, $phdOperation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($phdOperation);
            $entityManager->flush();

            return $this->redirectToRoute('phd_operations_index');
        }

        return $this->render('phd_operations/new.html.twig', [
            'phd_operation' => $phdOperation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="phd_operations_show", methods={"GET"})
     */
    public function show(PhdOperations $phdOperation, AppService $oAppService): Response
    {
		if (!$this->_hasPermissions()) {
			return $this->redirectToRoute('home');
		}
		$oTransaction = $this->getDoctrine()->getRepository('App:PhdPayTransaction')
			->find($phdOperation->getPayTransactionId());
		$sPhone = '';
		if ($oTransaction) {
			$sPhone = $oAppService->formatPhone($oTransaction->getPhone() );
		}
        return $this->render('phd_operations/show.html.twig', [
            'phd_operation' => $phdOperation,
			'sPhone' => $sPhone
        ]);
    }

    /**
     * @Route("/{id}/edit", name="phd_operations_edit", methods={"GET","POST"})
     */
    /*public function edit(Request $request, PhdOperations $phdOperation): Response
    {
		if (!$this->_hasPermissions()) {
			return $this->redirectToRoute('home');
		}
        $form = $this->createForm(PhdOperationsType::class, $phdOperation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('phd_operations_index');
        }

        return $this->render('phd_operations/edit.html.twig', [
            'phd_operation' => $phdOperation,
            'form' => $form->createView(),
        ]);
    }*/

    /**
     * @Route("/{id}", name="phd_operations_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PhdOperations $phdOperation): Response
    {
		if (!$this->_hasPermissions()) {
			return $this->redirectToRoute('home');
		}
        if ($this->isCsrfTokenValid('delete'.$phdOperation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($phdOperation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('phd_operations_index');
    }

	/**
	 * @return bool true когда пользователь имеет роль админ или оператор phd сообщений
	 */
	private function _hasPermissions() : bool
	{
		$oUser = $this->getUser();
		if (!$oUser) {
			return false;
		}
		$nRole = $oUser->getRole();
		return ( $nRole == 3 || $nRole == 2 );
	}
}
