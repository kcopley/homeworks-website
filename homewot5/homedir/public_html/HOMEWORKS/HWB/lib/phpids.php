<?php
/*
set_include_path(
   get_include_path()
   . PATH_SEPARATOR
   . '/home8/homewot5/public_html/lib/'
  );

  require_once '/home8/homewot5/public_html/lib/IDS/Init.php';
  $request = array(
      'REQUEST' => $_REQUEST,
      'GET' => $_GET,
      'POST' => $_POST,
      'COOKIE' => $_COOKIE
  );
  
  $init = IDS_Init::init('/home8/homewot5/public_html/lib/IDS/Config/Config.ini.php');
  $ids = new IDS_Monitor($request, $init);
  $result = $ids->run();

  if (!$result->isEmpty()) {
//mail("mikerametta@gmail.com	","Possible Attack","Someone one with the IP address of ".$_SERVER['REMOTE_ADDR']." has attempted to gain access to homewot5","From: info@retail-hive.com");
   // Take a look at the result object

  // require_once '/var/www/vhosts/retail-hive.com/httpdocs/IDS/Log/File.php';
  // require_once '/var/www/vhosts/retail-hive.com/httpdocs/IDS/Log/Composite.php';

  // $compositeLog = new IDS_Log_Composite();
   //$compositeLog->addLogger(IDS_Log_File::getInstance($init));
  // $compositeLog->execute($result);
   //die('<h1>ACCESS DENIED.</h1><h2>Your IP addreess:'.$_SERVER['REMOTE_ADDR'].' has been logged</h2>');
   
  }*/
?>