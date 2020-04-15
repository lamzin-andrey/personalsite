<?php

namespace App\Controller;

use App\Entity\PhdMessages;
use App\Form\PhdMessagesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/phd/messages")
 */
class PhdMessagesController extends AbstractController
{
    /**
     * @Route("/", name="phd_messages_index", methods={"GET"})
     */
    public function index(Request $oRequest): Response
    {
		if (!$this->_hasPermissions()) {
			return $this->redirectToRoute('home');
		}
    	$bOnlyPayed = intval($oRequest->get('payed'));
    	$aFilter = ['isPayed' => false];

    	if ($bOnlyPayed) {
			$aFilter = ['isPayed' => true];
		}
        $phdMessages = $this->getDoctrine()
            ->getRepository(PhdMessages::class)
            ->findBy($aFilter, ['createdAt' => 'DESC']);

        return $this->render('phd_messages/articles.html.twig', [
            'phd_messages' => $phdMessages,
			'bOnlyPayed' => $bOnlyPayed
        ]);
    }

    /**
     * @Route("/new", name="phd_messages_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
		if (!$this->_hasPermissions()) {
			return $this->redirectToRoute('home');
		}
        $phdMessage = new PhdMessages();
        $form = $this->createForm(PhdMessagesType::class, $phdMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($phdMessage);
            $entityManager->flush();

            return $this->redirectToRoute('phd_messages_index');
        }

        return $this->render('phd_messages/new.html.twig', [
            'phd_message' => $phdMessage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="phd_messages_show", methods={"GET"})
     */
    public function show(PhdMessages $phdMessage): Response
    {
		if (!$this->_hasPermissions()) {
			return $this->redirectToRoute('home');
		}
        return $this->render('phd_messages/show.html.twig', [
            'phd_message' => $phdMessage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="phd_messages_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PhdMessages $phdMessage): Response
    {
		if (!$this->_hasPermissions()) {
			return $this->redirectToRoute('home');
		}
        $form = $this->createForm(PhdMessagesType::class, $phdMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('phd_messages_index');
        }

        return $this->render('phd_messages/edit.html.twig', [
            'phd_message' => $phdMessage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="phd_messages_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PhdMessages $phdMessage): Response
    {
		if (!$this->_hasPermissions()) {
			return $this->redirectToRoute('home');
		}
        if ($this->isCsrfTokenValid('delete'.$phdMessage->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($phdMessage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('phd_messages_index');
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
