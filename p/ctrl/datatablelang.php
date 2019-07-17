<?php
/**
 * Выводит в поток локализацию для DataTables на странице админки
*/
class DataTablesLang extends BaseApp {
	
	
	public function __construct() {
		parent::__construct();
		$data = [
			'processing'  =>  l('Processing...'),
			'search' 	  =>  l('Search for...') . '&nbsp;:',
			'lengthMenu'  =>  l('Show _MENU_ entries'),
			'info' 		  =>  l('Showing _START_ to _END_ of _TOTAL_ entries'),
			'infoEmpty'   =>  l('Affichage de l\'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments'),
			'infoFiltered' =>  l('filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total'),
			'infoPostFix'  => '',
			'loadingRecords' => l('loadingRecords'),
			'zeroRecords'	=>     l('Zero Records!'),
			'emptyTable'	=>     l('emptyTable'),
			'paginate' 		=> [
				'first'		=>      'Premier',
				'previous'	=>  l('Previous'),
				'next'		=>  l('Next'),
				'last'		=>   'Dernier'
			],
			'aria' => [
				'sortAscending' =>  l('sortAscending'),
				'sortDescending' =>  l('sortDescending')
			]
		];
		echo json_encode($data);
		exit;
	}
	
}
