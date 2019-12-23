//import  LandCacheClient from './land_cache_client';
/***
 * Example customize client functions
 * Пример кастомизации функций клиента кэша
 */
class FxCacheClient extends LandCacheClient {
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
	        //alert('All resources loaded, add us page on main screen and use it offline.');
	}
	/**
	 * @override For example, you not want cache all requests with '.json' jn end
	 * @return Object {type:Dtring, data:Array} List of resources, which no need cache. For example ['*.json', '/breaking_news.php']
	 * For progressive web
	*/
	getExcludeFilterList() {
		let o = new Object();
		o.type = 'filterlist';
		o.data = [
			'*.json', 
			'https://andryuxa.ru/portfolio/web-razrabotka/saity/fastxampp/f/upload.php',
			'https://andryuxa.ru/portfolio/web-razrabotka/saity/fastxampp/f/state.php',
			'https://andryuxa.ru/portfolio/web-razrabotka/saity/fastxampp/f/save.php',
			'/portfolio/web-razrabotka/saity/fastxampp/f/upload.php',
			'/portfolio/web-razrabotka/saity/fastxampp/f/state.php',
			'https://andryuxa.ru/portfolio/web-razrabotka/saity/fastxampp/extensions_for_php_7.3.12_linux/en/?c=en',
			'https://andryuxa.ru/portfolio/web-razrabotka/saity/fastxampp/extensions_for_php_7.3.12_linux/en/?c=ru',
			'https://andryuxa.ru/portfolio/web-razrabotka/saity/fastxampp/extensions_for_php_7.3.12_linux/?c=en',
			'https://andryuxa.ru/portfolio/web-razrabotka/saity/fastxampp/extensions_for_php_7.3.12_linux/?c=ru',
			'https://andryuxa.ru/portfolio/web-razrabotka/saity/fastxampp/download.php?c=ru',
			'https://andryuxa.ru/portfolio/web-razrabotka/saity/fastxampp/download.php?c=en',
			'/portfolio/web-razrabotka/saity/fastxampp/f/save.php'
		];
		return o;
	}
}

