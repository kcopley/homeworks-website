<?php
/*
 * Mysql Ajax Table Editor
 *
 * Copyright (c) 2008 Chris Kitchen <info@mysqlajaxtableeditor.com>
 * All rights reserved.
 *
 * See COPYING file for license information.
 *
 * Download the latest version from
 * http://www.mysqlajaxtableeditor.com
 */
class Common
{		
	// Mysql Variables
	var $mysqlUser = 'homewot5';
	var $mysqlDb = 'homewot5_cms';
	var $mysqlHost = 'localhost';
	var $mysqlDbPass = 'Ping!!Pong42'; 
	
	var $langVars;
	var $dbc;
	
	function mysqlConnect()
	{
		if($this->dbc = mysql_connect($this->mysqlHost, $this->mysqlUser, $this->mysqlDbPass)) 
		{	
			if(!mysql_select_db ($this->mysqlDb))
			{
				$this->logError(sprintf($this->langVars->errNoSelect,$this->mysqlDb),__FILE__, __LINE__);
			}
		}
		else
		{
			$this->logError($this->langVars->errNoConnect,__FILE__, __LINE__);
		}
	}
	
	function logError($message, $file, $line)
	{
		$message = sprintf($this->langVars->errInScript,$file,$line,$message);
		var_dump($message);
		die;
	}


	function displayHeaderHtml()
	{
		?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
		<html>
		<head>
		<title>Database Manager</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
			<link href="css/table_styles.css" rel="stylesheet" type="text/css" />
			<link href="css/icon_styles.css" rel="stylesheet" type="text/css" />
			
			<script type="text/javascript" src="js/prototype.js"></script>
			<script type="text/javascript" src="js/scriptaculous-js/scriptaculous.js"></script>
			<script type="text/javascript" src="js/lang/lang_vars-en.js"></script>
			<script type="text/javascript" src="js/ajax_table_editor.js"></script>
			
			<!-- calendar files -->
			<link rel="stylesheet" type="text/css" media="all" href="js/jscalendar/skins/aqua/theme.css" title="win2k-cold-1" /> 
			<script type="text/javascript" src="js/jscalendar/calendar.js"></script>
			<script type="text/javascript" src="js/jscalendar/lang/calendar-es.js"></script>
			<script type="text/javascript" src="js/jscalendar/calendar-setup.js"></script>

		</head>	
		<body>
		<?php
	}	
	
	function displayFooterHtml()
	{
		?>
		</body>
		</html>
		<?php
	}	

}
?>
