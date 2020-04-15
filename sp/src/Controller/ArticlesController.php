<?php

namespace App\Controller;

use App\Form\ArticlesListFormType;
use App\Service\AppService;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Response;

class ArticlesController extends AppBaseController
{
	/**
	 * @Route("/articles", name="articles")
	*/
	public function index(AppService $oAppService)
	{
		$this->_oAppService = $oAppService;
		$oQueryBuilder = $oAppService->repository('App:PagesCategories')->createQueryBuilder('pc');
		$e = $oQueryBuilder->expr();
		$aJSONCategoriesData = $oQueryBuilder->where( $e->eq('pc.isDeleted', 0) )
		->select('pc.id, pc.categoryName')
		->orderBy($e->asc('pc.delta'))
		->getQuery()
		->execute();


		$sToken = $this->_getListFormToken();

		return $this->render('articles/articles.html.twig', [
			'jsonCategoriesData' => json_encode($aJSONCategoriesData),
			'sToken' => $sToken

		]);
	}

	/**
	 * @Route("articleslist.json", name="articleslist_json")
	 * @param Request $oRequest
	 * @param TranslatorInterface $t
	 * @param AppService $oAppService
	 * @return Response
	*/
	public function articleslistjson(Request $oRequest, TranslatorInterface $t, AppService $oAppService) : Response
	{
		$this->_oAppService = $oAppService;
		$aResult = [];
		if (!$this->_accessControl()) {
			$aResult['msg'] = $t->trans('You have not access to this page');
			$aResult['status'] = 'error';
			return $this->_json($aResult);
		}
		$sToken = $this->_getListFormToken();
		$sRecievedToken = ( $oRequest->get('articleslist', [])['_token'] ?? '' );
		if ($sToken != $sRecievedToken) {
			$aResult['msg'] = $t->trans('Invalid csrf token', [], 'security');
			$aResult['status'] = 'error';
			$aResult['_token'] = $sToken;
			return $this->_json($aResult);
		}

		/** @var QueryBuilder $oQueryBuilder */
		$oQueryBuilder = $this->getDoctrine()->getRepository('App:Pages')
			->createQueryBuilder('p');
		$perpage = intval($oRequest->get('length') );
		$offset = intval($oRequest->get('start', 0) );
		$e = $oQueryBuilder->expr();

		$oQueryBuilder->where( $e->neq('p.isDeleted', 1) )
			->select('p.id, p.heading, p.url');

		$this->_setJSONListSearchTextCondition($oQueryBuilder);

		$aData = $oQueryBuilder->setMaxResults($perpage)
			->setFirstResult($offset)
			->getQuery()
			->execute();

			/** @var QueryBuilder $oQueryBuilder */
		$oQueryBuilder = $this->getDoctrine()->getRepository('App:Pages')
			->createQueryBuilder('p');
		$e = $oQueryBuilder->expr();

		$oQueryBuilder->where( $e->neq('p.isDeleted', 1) )
			->select('COUNT(p.id)');

		$this->_setJSONListSearchTextCondition($oQueryBuilder);

		$nTotal = $oQueryBuilder
			->getQuery()
			->getSingleResult()[1];

		$aResult = [
			'recordsTotal' => $nTotal,
			'recordsFiltered' => $nTotal,
			'draw' => intval($oRequest->get('draw', 0) ),
			'data' => $aData
		];
		return $this->_json($aResult);
	}

	/**
	 * Устанавливает параметры фильтрации
	 * @param QueryBuilder $oQueryBuilder
	 * @return
	*/
	private function _setJSONListSearchTextCondition(QueryBuilder $oQueryBuilder) : void
	{
		$oRequest = $this->_oAppService->request();
		$sWord = ($oRequest->get('search', [])['value'] ?? '');
		if ($sWord) {
			//Есть мнение, что на сервере придётся раскомментировать.
			/*$sWord = mb_convert_encoding($sWord, 'WINDOWS-1251', 'UTF-8')*/;
			$e = $oQueryBuilder->expr();
			$oQueryBuilder->andWhere(
				$e->orX(
					$e->eq('p.heading', '\'' . $sWord . '\''),
					$e->like( 'p.heading','\'%' . $sWord . '%\''),
					$e->like('p.contentBlock', '\'%' . $sWord . '%\'')
				)
			);
		}
	}

	/**
	 *
	 * @return string Токен формы
	*/
	private function _getListFormToken() : string
	{
		$this->_oForm = $oForm = $this->createForm(ArticlesListFormType::class, null);
		return $this->_oAppService->getFormTokenValue($oForm);
	}
}
