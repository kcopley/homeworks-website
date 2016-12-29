<?php
#BEGIN_LICENSE
#-------------------------------------------------------------------------
# Module: Products (c) 2008 by Robert Campbell 
#         (calguy1000@cmsmadesimple.org)
#  An addon module for CMS Made Simple to allow users to create, manage
#  and display products in a variety of ways.
# 
# Version: 1.1.5
#
#-------------------------------------------------------------------------
# CMS - CMS Made Simple is (c) 2005 by Ted Kulp (wishy@cmsmadesimple.org)
# This project's homepage is: http://www.cmsmadesimple.org
#
#-------------------------------------------------------------------------
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# However, as a special exception to the GPL, this software is distributed
# as an addon module to CMS Made Simple.  You may not use this software
# in any Non GPL version of CMS Made simple, or in any version of CMS
# Made simple that does not indicate clearly and obviously in its admin 
# section that the site was built with CMS Made simple.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
# Or read it online: http://www.gnu.org/licenses/licenses.html#GPL
#
#-------------------------------------------------------------------------
#END_LICENSE
if( !isset($gCms) ) exit;
$this->SetCurrentTab('products');
if( !$this->CheckPermission('Modify Products') )
  {
    $this->SetError($this->Lang('error_permissiondenied'));
    $this->RedirectToTab($id);
    return;
  }

#
# Initialization
#
$flag_createfields = 0;
$flag_handleimages = 0;
$imagepath = '';
$flag_createhierarchy = 0;
$flag_createcategories = 0;
$flag_duplicateproducts = 'skip';
$delemeter = '|';
$batchsize = 50;

#
# Setup
#
$flag_createfields = $this->GetPreference('import_createfields',1);
$flag_handleimages = $this->GetPreference('import_handleimages',0);
$imagepath = $this->GetPreference('import_imagepath','');
$flag_createhierarchy = $this->GetPreference('import_createhierarchy',1);
$flag_createcategories = $this->GetPreference('import_createcategories',1);
$flag_duplicateproducts = $this->GetPreference('import_duplicateproducts','overwrite');
$delimiter = $this->GetPreference('import_delimiter','|');
$batchsize = $this->GetPreference('import_batchsize',50);

#
# Handle Form Data
#
if( isset($params['cancel']) )
  {
    $this->RedirectToTab($id);
  }
else if( isset($params['test']) )
  {
    // get the form data
    $flag_createfields = (int)$params['createfields'];
    $flag_handleimages = (int)$params['handleimages'];
    $imagepath = trim($params['imagepath']);
    $flag_createhierarchy = (int)$params['createhierarchy'];
    $flag_createcategories = (int)$params['createcategories'];
    $flag_duplicateproducts = $params['duplicateproducts'];
    $delimiter = $params['delimiter'];
    $batchsize = (int)$params['batchsize'];

    // validate the form data
    $error = '';
    $messages = array();
    $import_errors = array();
    $config = $gCms->GetConfig();
    $uh = new cg_fileupload($id,$config['root_path'].'/tmp/cache');
    $uh->set_accepted_filetypes('csv');
    $uh->set_allow_overwrite();
    $res = $uh->handle_upload('csvfile','products');
    if( $res === false )
      {
	$tmp = $uh->get_error();
        $error = $this->GetUploadErrorMessage($tmp);
      }

    if( empty($error) )
      {
	// ready to do some testing on the csv file.
	include(dirname(__FILE__).'/class.csv_importer.php');
	$fn = $config['root_path'].'/tmp/cache/products.csv';
	$the_filesize = filesize($fn);
	$fh = fopen($fn,'r');
	if( !$fh )
	  {
	    $error = $this->Lang('error_fileopen',$fn);
	  }
	$linenum = 0;
	$importer = new productsCsvImporter($this);
	$importer->setDelim($delimiter);
	while( !feof($fh) && $linenum < 20 )
	  {
	    $line = $importer->get_unparsed_record($fh);
	    $line = trim($line);
	    $linenum++;
	    $tmp = array();

	    if( empty($line) ) continue;

	    $res = $importer->testLine($line,$tmp);
	    if( $res === FALSE )
	      {
		$error = $this->Lang('error_fileformat');
	      }
	    $messages = array_merge($messages,$tmp);
	  }

	if( !empty($error) )
	  {
	    $import_errors = $importer->getErrors();
	  }
      }

    if( empty($error) )
      {
	// save the preferences
	$this->SetPreference('import_createfields',$flag_createfields);
	$this->SetPreference('import_handleimages',$flag_handleimages);
	$this->SetPreference('import_imagepath',$imagepath);
	$this->SetPreference('import_createhierarchy',$flag_createhierarchy);
	$this->SetPreference('import_createcategories',$flag_createcategories);
	$this->SetPreference('import_duplicateproducts',$flag_duplicateproducts);
	$this->SetPreference('import_delimiter',$delimiter);
	$this->SetPreference('import_batchsize',$batchsize);

	// build the url.
	$parms = array();
	$parms['disable_buffer'] = 1;
	$parms['disable_theme'] = 1;
	$import_url = $this->CreateURL($id,'importcsv',$returnid,$parms);
	$smarty->assign('import_url',$import_url);

	// save parameters in session
	$params['filename'] = 'products.csv';
	$_SESSION['products_import_data'] = $params;

	// Get ready for the go action
	$smarty->assign('csvfile',$params['filename']);
	if( count($messages) > 0 )
	  {
	    $smarty->assign('messages',$messages);
	  }
	echo $this->ShowMessage($this->Lang('info_import_test_passed'));
      }
    else
      {
	$smarty->assign('errors',$import_errors);
	echo $this->ShowErrors($error);
      }
  }

#
# Give Everything to Smarty
#
$smarty->assign('flag_createfields',$flag_createfields);
$smarty->assign('flag_handleimages',$flag_handleimages);
$smarty->assign('imagepath',$imagepath);
$smarty->assign('flag_createhierarchy',$flag_createhierarchy);
$smarty->assign('flag_createcategories',$flag_createcategories);
$smarty->assign('flag_duplicateproducts',$flag_duplicateproducts);
$smarty->assign('delimiter',$delimiter);
$smarty->assign('batchsize',$batchsize);

$smarty->assign('formstart',$this->CGCreateFormStart($id,'importproducts',$returnid,array(),false,'post','multipart/form-data'));
$smarty->assign('formend',$this->CreateFormEnd());
$yesno = array('0'=>$this->Lang('no'),'1'=>$this->Lang('yes'));
$smarty->assign('yesno',$yesno);

#
# Process The Template 
#
echo $this->ProcessTemplate('importproducts.tpl');

#
# EOF
#
?>