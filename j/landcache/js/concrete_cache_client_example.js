/***
 * Example customize client functions
 * Пример кастомизации функций клиента кэша
 */
class ConcreteCacheClientExample {
    /**
	 * @description 
	 * @return array
	*/ 
	showUpdateMessage() {
        //I do not show message about updates!
        //alert('New version this page available!');
        
        //I want show nice bootstrap modal message
        //$('#myModalBody').text('New version this page available!');
        //$('#myModal').modal('show');
	}
}