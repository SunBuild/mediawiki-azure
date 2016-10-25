<?php

$connectstr_dbhost = '';
$connectstr_dbname = '';
$connectstr_dbusername = '';
$connectstr_dbpassword = '';
foreach ($_SERVER as $key => $value) {
    if (strpos($key, "MYSQLCONNSTR_") !== 0) {
        continue;
    }
    
    $connectstr_dbhost = preg_replace("/^.*Data Source=(.+?);.*$/", "\\1", $value);
    $connectstr_dbname = preg_replace("/^.*Database=(.+?);.*$/", "\\1", $value);
    $connectstr_dbusername = preg_replace("/^.*User Id=(.+?);.*$/", "\\1", $value);
    $connectstr_dbpassword = preg_replace("/^.*Password=(.+?)$/", "\\1", $value);
}

$hostname = gethostname();

$wgWPIOptionStore = array(
		//Screen1
		'lang' => 'en',
		//Screen2
		'dbserver' => $connectstr_dbhost,
		'dbname' => $connectstr_dbname,
		'dbprefix' => 'mw_',
		'dbuser' => $connectstr_dbusername,
		'dbpass' => $connectstr_dbpassword,
		'installdbuser' => $connectstr_dbusername,
		'installdbpass' => $connectstr_dbpassword,
		//Screen3
		'sitename' => 'MyWiki',
		'adminname' => getenv('SITE_ADMIN_USERNAME'),
		'pass' => getenv('SITE_ADMIN_PASSWORD'),
		'scriptpath' => substr($hostname, 0,strrpos($mystring, ".")),
	    'enablefileupload' => 'No',
		'usewindowsazure' => 'No',
		'azureaccount' => 'accountname',
		'azurekey' => 'accountkey',
        'wikiId' => 'mywiki'
	);