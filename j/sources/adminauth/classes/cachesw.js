import LandCacheClient from './../../landcache/sources/js/land_cache_client'

class CacheSw extends LandCacheClient {
	/**
	 * @override in child
	 * @return Не кэшируем запросы, заканчивающиеся на '*.jn/'
	 * For progressive web
	*/
	getExcludeFilterList() {
		let o = new Object();
		o.type = 'filterlist';
		o.data = ['*.jn/', '*.jn'];
		return o;
	}
}
export default CacheSw;