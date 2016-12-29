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
require_once('../pos/security.php');
require_once('Common.php');
require_once('php/lang/LangVars-en.php');
require_once('php/AjaxTableEditor.php');
class Customers extends Common
{
	var $Editor;
	
	function displayHtml()
	{
		?>
			<br />
	
			<div align="left" style="position: relative;"><div id="ajaxLoader1"><img src="images/ajax_loader.gif" alt="Loading..." /></div></div>
			
			<br />
			
			<div id="historyButtonsLayer" align="left">
			</div>
	
			<div id="historyContainer">
				<div id="information">
				</div>
		
				<div id="titleLayer" style="padding: 2px; font-weight: bold; font-size: 18px; text-align: center;">
				</div>
		
				<div id="tableLayer" align="center">
				</div>
				
				<div id="recordLayer" align="center">
				</div>		
				
				<div id="searchButtonsLayer" align="center">
				</div>
			</div>
			
			<script type="text/javascript">
				trackHistory = false;
				var ajaxUrl = '<?php echo $_SERVER['PHP_SELF']; ?>';
				toAjaxTableEditor('update_html','');
			</script>
		<?php
	}

	function initiateEditor()
	{
		$tableColumns['customer_id'] = array('display_text' => 'ID', 'perms' => 'TVQSXO');
		$tableColumns['customer_name'] = array('display_text' => 'Customer Name', 'perms' => 'EVCTAXQSHO');
		$tableColumns['address'] = array('display_text' => 'Address', 'perms' => 'EVCTAXQSHO');
		$tableColumns['address_2'] = array('display_text' => 'Address ', 'perms' => 'EVCTAXQSHO');
		$tableColumns['city'] = array('display_text' => 'City', 'perms' => 'EVCTAXQSHO');
		$tableColumns['state'] = array('display_text' => 'State', 'perms' => 'EVCTAXQSHO');
		$tableColumns['zip'] = array('display_text' => 'Zip', 'perms' => 'EVCTAXQSHO');
		$tableColumns['phone'] = array('display_text' => 'Phone', 'perms' => 'EVCTAXQSHO');
		$tableColumns['email'] = array('display_text' => 'E-Mail', 'perms' => 'EVCTAXQSHO');
		$tableColumns['balance'] = array('display_text' => 'Balance', 'perms' => 'EVCTAXQSHO');
		 
		$tableName = 'customers';
		$primaryCol = 'customer_id';
		$errorFun = array(&$this,'logError');
		$permissions = 'EAVIDQCSXHO';
		
		$this->Editor = new AjaxTableEditor($tableName,$primaryCol,$errorFun,$permissions,$tableColumns);
		$this->Editor->setConfig('tableInfo','cellpadding="1" width="1000" class="mateTable"');
		$this->Editor->setConfig('orderByColumn','customer_name');
		$this->Editor->setConfig('addRowTitle','Add Customer');
		$this->Editor->setConfig('editRowTitle','Edit Customer');
		$this->Editor->setConfig('iconTitle','Edit Customer');
	}
	
	
	function Customers()
	{
		if(isset($_POST['json']))
		{
			session_start();
			// Initiating lang vars here is only necessary for the logError, and mysqlConnect functions in Common.php. 
			// If you are not using Common.php or you are using your own functions you can remove the following line of code.
			$this->langVars = new LangVars();
			$this->mysqlConnect();
			if(ini_get('magic_quotes_gpc'))
			{
				$_POST['json'] = stripslashes($_POST['json']);
			}
			if(function_exists('json_decode'))
			{
				$data = json_decode($_POST['json']);
			}
			else
			{
				require_once('php/JSON.php');
				$js = new Services_JSON();
				$data = $js->decode($_POST['json']);
			}
			if(empty($data->info) && strlen(trim($data->info)) == 0)
			{
				$data->info = '';
			}
			$this->initiateEditor();
			$this->Editor->main($data->action,$data->info);
			if(function_exists('json_encode'))
			{
				echo json_encode($this->Editor->retArr);
			}
			else
			{
				echo $js->encode($this->Editor->retArr);
			}
		}
		else if(isset($_GET['export']))
		{
            session_start();
            ob_start();
            $this->mysqlConnect();
            $this->initiateEditor();
            echo $this->Editor->exportInfo();
            header("Cache-Control: no-cache, must-revalidate");
            header("Pragma: no-cache");
            header("Content-type: application/x-msexcel");
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="'.$this->Editor->tableName.'.csv"');
            exit();
        }
		else
		{
			$this->displayHeaderHtml();
			$this->displayHtml();
			$this->displayFooterHtml();
		}
	}
}
$lte = new Customers();
?>