//import  LandCacheClient from './land_cache_client';
class CacheClient extends LandCacheClient {
        /**
         * @description 
        */ 
        showUpdateMessage() {
                //I do not show message about updates
                //alert('New version this page available!');
                //I want show nice bootstrap modal message
                //$('#myModalBody').text('New version this page available!');
                //$('#myModal').modal('show');
        }

        /**
	 * @description Off show message "Page caching complete".
         * Отключение сообщения "Кэширование завершено"
	*/
	showFirstCachingCompleteMessage() {
		beepClick();
		var s = 'Дождитесь перезагрузки страницы, добавьте читалку на главный экран, чтобы она была под рукой даже без интернета.';
		alert(s);
		location.reload();
	}
	/**
	 * @override For example, you not want cache all requests with '.json' jn end
	 * @return Object {type:Dtring, data:Array} List of resources, which no need cache. For example ['*.json', '/breaking_news.php']
	 * For progressive web
	*/
	getExcludeFilterList() {
		let o = new Object();
		o.type = 'filterlist';
		o.data = ['*.json', this.schemeHost() + '/pagenocache.html'];
		return o;
	}
}
