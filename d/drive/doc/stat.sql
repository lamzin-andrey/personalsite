
-- Most popular files
SELECT url, COUNT(DISTINCT user_id) AS c FROM `stat_ua_view` WHERE url LIKE('%/share/%') GROUP BY url
ORDER BY c DESC 
LIMIT 10;

-- Most popular screen resolution
SELECT screen, COUNT(DISTINCT user_id) AS c FROM `stat_ua_view` AS v
 INNER JOIN stat_screen AS s  
   ON s.id = v.screen_id

GROUP BY screen_id
ORDER BY c DESC 
LIMIT 10;

-- Most popular viewports
SELECT viewport, COUNT(DISTINCT user_id) AS c FROM `stat_ua_view` AS v
 INNER JOIN stat_viewport AS s  
   ON s.id = v.viewport_id

GROUP BY viewport_id
ORDER BY c DESC 
LIMIT 10;

-- Most popular agents
SELECT ua_id, ua, SUBSTRING(ua, 50, 50) AS vrs, COUNT(DISTINCT user_id) AS c FROM `stat_ua_view` AS v
 INNER JOIN drv_ua AS s  
   ON s.id = v.ua_id

GROUP BY ua_id
ORDER BY c DESC 
LIMIT 10;