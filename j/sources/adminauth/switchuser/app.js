//cache
import CacheSw from '../classes/cachesw';
window.cacheClient = new CacheSw();

//For REST request server
require('../../landlib/net/rest.js');

class SwitchUserClient {
    constructor() {
      Rest._token = 'rand';
    	if ($('#signinformsw')[0]) {
    		  $.get('/sp/public/logout', () => {this.onLogout();});
    	}
    }

    onLogout() {
      $.get('/sp/public/swid?id=' + $('#hash').val(), (data) => {this.onData(data)});
    }

    onData(data) {
      $('#csrf').val(data.csrf_token);
      $('#password').val(data.password);
      $('#signinformsw').submit();
    }
}

window.addEventListener('load', () => {new SwitchUserClient();});

