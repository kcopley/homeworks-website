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
$this->SetCurrentTab('categories');

if (!$this->CheckPermission('Modify Products'))
{
	echo $this->ShowErrors($this->Lang('needpermission', array('Modify Products')));
	return;
}
if( !isset($params['catid']) )
{
  echo $this->ShowErrors($this->Lang('error_missingparam'));
  return;
}

#
# Initialization
#
$config = $gCms->GetConfig();
$catid = (int)$params['catid'];
$fieldtype = 'textbox';
$fieldname = '';
$fieldprompt = '';
$fieldvalue = '';
$editing = 0;
$old_fieldname = '';
if( isset($params['fldname']) )
{
  $fieldname = trim($params['fldname']);
  $query = 'SELECT * FROM '.cms_db_prefix().'module_products_category_fields 
             WHERE category_id = ? AND field_name = ?';
  $row = $db->GetRow($query,array($catid,$fieldname));
  $fieldtype = $row['field_type'];
  $fieldprompt = $row['field_prompt'];
  $fieldvalue = $row['field_value'];
  $old_fieldname = $fieldname;
  $editing = 1;
}

#
# Get Parameter Valus
#
if( isset($params['input_fieldtype']) )
  {
    $fieldtype = $params['input_fieldtype'];
  }
if( isset($params['input_fieldname']) )
  {
    $fieldname = munge_string_to_url($params['input_fieldname']);
  }
if( isset($params['input_fieldprompt']) )
  {
    $fieldprompt = $params['input_fieldprompt'];
  }
if( isset($params['input_fieldvalue']) )
  {
    $fieldvalue = $params['input_fieldvalue'];
  }
if( isset($params['input_editing']) )
  {
    $editing = (int)$params['input_editing'];
  }

# Get the category data
$query = 'SELECT * FROM '.cms_db_prefix().'module_products_categories WHERE id = ?';
$category = $db->GetRow($query,array($catid));
$smarty->assign('category',$category);

if( isset($params['cancel']) )
  {
    $this->Redirect($id,'editcategoryfields',$returnid,
		    array('catid'=>$catid));
  }
else if( isset($params['submit']) )
  {
    //
    // Validate Form Input
    //
    
    // Name can be empty (we'll generate one)
    if( empty($fieldname) )
      {
	$query = 'SELECT COUNT(category_id)+1 FROM '.cms_db_prefix().'module_products_category_fields
                   WHERE category_id = ?';
	$fieldname = 'field_'.$db->GetOne($query,array($catid));
      }

    // Prompt can be empty (we'll use the field name)
    if( empty($fieldprompt) )
      {
	$fieldprompt = $fieldname;
      }

    // process uploaded files
    $error = '';
    switch($fieldtype)
      {
      case 'image':
	// if an image has been uploaded
	// process it
	// optionally generate a thumbnail
	// and set the field value.
	$attr = 'default';
	if( isset($params['input_watermark']) )
	  {
	    $attr = $params['input_watermark'];
	  }
	$destdir = cms_join_path($config['uploads_path'],$this->GetName(),'categories',$catid);
	$res = $this->HandleUploadedImage($id,'input_file',$destdir,$error,'',$attr);
	if( $res !== FALSE && $res !== TRUE)
	  {
	    $fieldvalue = $res;
	  }
	break;

      case 'file':
	// if a file has been uploaded
	// process it
	// and set the field value
	$destdir = cms_join_path($config['uploads_path'],$this->GetName(),'categories',$catid);
	cge_dir::mkdirr($destdir);
	if( !is_dir($destdir) ) die('directory still does not exist');
	$handler = new cg_fileupload($id,$destdir);
	$handler->set_accepted_filetypes($this->GetPreference('allowed_filetypes'));
	$res = $handler->handle_upload('input_file');
	$err = $handler->get_error();
	if( !$res && $err != cg_fileupload::NOFILE )
	  {
	    $error = sprintf("%s %s: %s",$this->Lang('field'),'input_file',
			     $this->GetUploadErrorMessage($err));
	  }
	else
	  {
	    $fieldvalue = $res;
	  }
	break;
      }

    // At this point, value cannot be empty
    if( empty($fieldvalue) && empty($error) )
      {
	$error = $this->Lang('error_fieldvalue_empty');
      }

    if( !empty($error) )
      {
	// Handle errors.
	echo $this->ShowErrors($error);
      }
    else
      {
	// Store the field definition
	if( $editing == 1 )
	  {
	    $query = 'UPDATE '.cms_db_prefix().'module_products_category_fields
                         SET field_name = ?, field_type = ?, field_prompt = ?, field_value = ?
                       WHERE category_id = ? AND field_name = ?';
	    $db->Execute($query,array($fieldname,$fieldtype,$fieldprompt,
				      $fieldvalue,$catid,$old_fieldname));
	  }
	else
	  {
	    $query = 'SELECT MAX(field_order) AS num FROM '.cms_db_prefix().'module_products_category_fields
                       WHERE category_id = ?';
	    $tmp = $db->GetRow($query,array($catid));
	    $fieldorder = 0;
	    if( is_array($tmp) )
	      {
		$fieldorder = $tmp['num']+1;
	      }
	    $query = 'INSERT INTO '.cms_db_prefix().'module_products_category_fields 
             (category_id, field_type, field_name, field_prompt, field_value, field_order)
             VALUES (?,?,?,?,?,?)';
	    $db->Execute($query,array($catid,$fieldtype,$fieldname,$fieldprompt,$fieldvalue,$fieldorder));
	  }

	// Redirect.
	$this->Redirect($id,'editcategoryfields',$returnid,
			array('catid'=>$catid));
      }
  }

#
# Create the form
#
$parms = array('catid'=>$catid);
if( $old_fieldname != '' )
  {
    $parms['fldname'] = $old_fieldname;
  }
$smarty->assign('formstart',$this->CGCreateFormStart($id,'addcategoryfield',$returnid,
						     $parms,false,'post','multipart/form-data'));
$smarty->assign('formend',$this->CreateFormEnd());

$fieldtypes = array();
$fieldtypes[$this->Lang('textbox')] = 'textbox';
$fieldtypes[$this->Lang('textarea')] = 'textarea';
$fieldtypes[$this->Lang('imagetext')] = 'image';
$fieldtypes[$this->Lang('file')] = 'file';
$smarty->assign('input_fieldtype',
		$this->CreateInputDropdown($id,'input_fieldtype',$fieldtypes,-1,$fieldtype,
					   'onChange="this.form.submit()"'));
$smarty->assign('hidden1',$this->CreateInputHidden($id,'input_editing',$editing));
$smarty->assign('input_fieldname',
		$this->CreateInputText($id,'input_fieldname',$fieldname,40,255));
$smarty->assign('input_fieldprompt',
		$this->CreateInputText($id,'input_fieldprompt',$fieldprompt,40,255));
$smarty->assign('fieldvalue',$fieldvalue);
$smarty->assign('fieldtype',$fieldtype);
switch( $fieldtype )
{
 case 'textbox':
   $smarty->assign('input_fieldvalue',
		   $this->CreateInputText($id,'input_fieldvalue',$fieldvalue,80,255));
   break;

 case 'textarea':
   $smarty->assign('input_fieldvalue',
		   $this->CreateTextArea(true,$id,$fieldvalue,'input_fieldvalue'));
   break;

 case 'image':
   if( !empty($fieldvalue) )
     {
       $smarty->assign('input_hidden',$this->CreateInputHidden($id,'input_fieldvalue',$fieldvalue));
     }
   $smarty->assign('filelocation','uploads/'.$this->GetName().'/categories/'.$catid);
   if( file_exists( cms_join_path( $config['uploads_path'], $this->GetName(), 'categories', $catid, $fieldvalue)) )
     {
       $smarty->assign('fileexists',1);
     }
   if( $this->GetPreference('autowatermark') == 'adjustable' )
     {
       $wmopts = array();
       $wmopts[$this->Lang('none')] = 'none';
       $wmopts[$this->Lang('default')] = 'default';
       $wmopts[$this->Lang('align_ul')] = '0';
       $wmopts[$this->Lang('align_uc')] = '1';
       $wmopts[$this->Lang('align_ur')] = '2';
       $wmopts[$this->Lang('align_ml')] = '3';
       $wmopts[$this->Lang('align_mc')] = '4';
       $wmopts[$this->Lang('align_mr')] = '5';
       $wmopts[$this->Lang('align_ll')] = '6';
       $wmopts[$this->Lang('align_lc')] = '7';
       $wmopts[$this->Lang('align_lr')] = '8';

       $smarty->assign('prompt_watermarklocation',
		       $this->Lang('watermark_location'));
       $smarty->assign('input_watermarklocation',
		       $this->CreateInputDropdown($id,'input_watermark',$wmopts,-1,'default'));
     }
   $smarty->assign('input_fieldvalue',
		   $this->CreateFileUploadInput($id,'input_file','',40));
   break;

 case 'file':
   if( !empty($fieldvalue) )
     {
       $smarty->assign('input_hidden',$this->CreateInputHidden($id,'input_fieldvalue',$fieldvalue));
     }
   $smarty->assign('filelocation','uploads/'.$this->GetName().'/categories/'.$catid);
   if( file_exists( cms_join_path( $config['uploads_path'], $this->GetName(), 'categories', $catid, $fieldvalue)) )
     {
       $smarty->assign('fileexists',1);
     }
   $smarty->assign('input_fieldvalue',
		   $this->CreateFileUploadInput($id,'input_file','',40));
   break;
}
$smarty->assign('submit',
		$this->CreateInputSubmit($id,'submit',$this->Lang('submit')));
$smarty->assign('cancel',
		$this->CreateInputSubmit($id,'cancel',$this->Lang('cancel')));


# Process template
echo $this->ProcessTemplate('addcategoryfield.tpl');

#
# EOF
#
?>
