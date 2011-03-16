<?php
 define('CLAIM_URL','http://secure1.ncix.com/newsletterrewards.cfm');
 define('DB_HOST', 'localhost');
 define('DB_USER', 'NCIXAutoClaimer');
 define('DB_DB', 'NCIXAutoClaimer');
 define('DB_PASS', 'NCIXAutoClaimer8080');
 
 mysql_connect(DB_HOST, DB_USER, DB_PASS);
 mysql_select_db(DB_DB);
  
?>