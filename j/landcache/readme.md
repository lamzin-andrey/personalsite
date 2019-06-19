It will one repository...

Next sdteps
 1 Test compile.
 2 Add showFirstCachingCompleteMessage function

#About
It caching like to standart browser behavior.
It release the strategy "If resource exists in cache, get it from cache, else get it from server. After update resource in cache".

Service worker and class CacheClient caching all content on all pages your site.

Every request first serach in cache, if it not found in cache, service worker send request to the server.
After 1 seconds servicve worker in background mode refresh cache data.

You can extends class CacheClient.js for customize or disable "alert" about updates on page.
(Override method showUpdateMessage)

You can extends class CacheClient.js for customize or disable "alert" about first time caching all page resources.
It alert helpfull for progressive web applications, but for site, like as blog it not need.
(Override method showFirstCachingCompleteMessage)

#Installation

1 Place script landcachersw.js in the root directory your site.
It must be available for link https://yoursite.com/landcachersw.js, it important!

2 Add in begin html code all pages your site code:
  <script src="/js/landcacherswinstaller.js"></script>
You can copy it script as inline script, it provide best work cacher.

3 Create javascript class, extends LandCacheClient (like as class defined in concrete_cache_client_example.js)

4 Use webpack or gulp or "pure babel" for compile land_cache_client.js and  your_concrete_cache_client.js and add it 
in your result bundle. 

5 In start point your js app add initalization CacheClient like as 

  let cacheClient = new YourCacheClient();
  
(If you do not want use messages about updates and first caching in your site, you can use ConcreteCacheClientExample.js as YourConcreteCacheClient.js)


