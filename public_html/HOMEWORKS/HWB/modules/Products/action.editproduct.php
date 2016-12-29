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
if (!isset($gCms)) exit;
$this->SetCurrentTab('products');

//
// A utility function
//
function get_field_def(&$fielddefs,$id)
{
  foreach( $fielddefs as $onedef )
    {
      if( $onedef->id == $id )
	{
	  return $onedef;
	}
    }
  return false;
}

function products_delete_uploaded_file($dir,$filename)
{
  if( empty($filename) ) return;

  $filename = basename($filename);
  @unlink(cms_join_path($dir,$filename));
  @unlink(cms_join_path($dir,'thumb_'.$filename));
  @unlink(cms_join_path($dir,'preview_'.$filename));
}

if (!$this->CheckPermission('Modify Products'))
  {
    echo $this->ShowErrors($this->Lang('needpermission', array('Modify Products')));
    return;
  }

if (isset($params['cancel']))
  {
    $this->RedirectToTab($id);
  }

$compid = '';
if (isset($params['compid']))
  {
    $compid = $params['compid'];
  }

$product_name = '';
if (isset($params['product_name']))
  {
    $product_name = $params['product_name'];
  }

$price = '';
if (isset($params['price']))
  {
    $price = (float)$params['price'];
  }

$weight = '';
if (isset($params['weight']))
  {
    $weight = (float)$params['weight'];
  }

$sku = '';
if (isset($params['sku']))
  {
    $sku = trim($params['sku']);
  }

$alias = '';
if (isset($params['alias']))
  {
    $alias = trim($params['alias']);
  }

$details = '';
if (isset($params['details']))
  {
    $details = $params['details'];
  }

$taxable = 0;
if (isset($params['taxable']))
  {
    $taxable = 1;
  }

$hierarchy_pos = -1;
if (isset($params['hierarchy']) )
  {
    $hierarchy_pos = (int)$params['hierarchy'];
  }

$status = '';
if (isset($params['status']))
  {
    $status = $params['status'];
  }

$origname = '';
if (isset($params['origname']))
  {
    $origname = $params['origname'];
  }


$fieldarray = array();
$userid = get_userid();
$fielddefs = $this->GetFieldDefsForProduct($compid,true);
//$fielddefs = $this->GetFieldDefs(true);

if (isset($params['submit']))
  {
    $duplicate = '';
    $duplicatesku = '';
    $duplicatealias = '';
    if ($product_name != '')
      {
	$query = 'SELECT id FROM '.cms_db_prefix().'module_products
                   WHERE product_name = ? AND id != ?';
	$duplicate = $db->GetOne($query,array($product_name,$compid));
      }

    if( empty($alias) )
      {
	$alias = product_ops::generate_alias($product_name);
      }

    // check for duplicate alias
    if( product_ops::check_alias_used($alias,$compid) )
      {
	$duplicatealias = $alias;
      }

    if( !$duplicate && !empty($sku) )
      {
	// check for duplicate sku
	if( product_ops::check_sku_used($sku,$compid) )
	  {
	    $duplicatesku = $sku;
	  }
      }

    if( $duplicate )
      {
	echo $this->ShowErrors($this->Lang('error_product_nameused'));
      }
    else if( $duplicatealias )
      {
	echo $this->ShowErrors($this->Lang('error_product_aliasused'));
      }
    else if( $duplicatesku )
      {
	echo $this->ShowErrors($this->Lang('error_product_skuused'));
      }
    else if ($product_name == '')
      {
	echo $this->ShowErrors($this->Lang('nonamegiven'));
      }
    else
      {
	// update the original record
	$query = 'UPDATE '.cms_db_prefix().'module_products SET product_name = ?, price = ?, details = ?, modified_date = '.$db->DBTimeStamp(time()).',taxable = ?, status = ?, weight = ?, sku = ?, alias = ? WHERE id = ?';
	$db->Execute($query, array($product_name, $price, $details, 
				   $taxable, $status, $weight, $sku, $alias, $compid));

	// Update the hierarchy stuff
	$query = 'DELETE FROM '.cms_db_prefix().'module_products_prodtohier WHERE product_id = ?';
	$db->Execute( $query, array( $compid ) );
	$query = 'INSERT INTO '.cms_db_prefix().'module_products_prodtohier 
                   (product_id,hierarchy_id)
                   VALUES (?,?)';
	$db->Execute( $query, array( $compid, $hierarchy_pos ) );
       
	// Update custom fields
	$deleted_items = array();
	$db->Execute('DELETE FROM '.cms_db_prefix().'module_products_fieldvals WHERE product_id = ?', array($compid));

	$destdir = cms_join_path($gCms->config['uploads_path'],$this->GetName(),
				 'product_'.$compid);

	$errors = array();
	if (isset($_REQUEST[$id.'customfield']))
	  {
	    foreach ($_REQUEST[$id.'customfield'] as $k=>$v)
	      {
		// handle file deletions
		if (startswith($k, 'deletefield-')) {

		  // get the field index
		  $fid = substr($k, strlen('deletefield-'));

		  // get the field type
		  $def = get_field_def($fielddefs,$fid);
		  if( !isset($def->value) ) continue;
		  if( !$def )
		    {
		      die('could not get field def for '.$fid);
		    }

		  $destdir = cms_join_path($gCms->config['uploads_path'],$this->GetName(),
					   'product_'.$compid);
		  
		  switch( $def->type )
		    {
		    case 'file':
		    case 'image':
		      // delete the file
		      products_delete_uploaded_file($destdir,$def->value);
		      $deleted_items[] = $fid;
		      break;
		    }
		}
	      }

	    foreach ($_REQUEST[$id.'customfield'] as $k=>$v)
	      {
		// handle new values (or hidden values)
		if (startswith($k, 'field-')) { // else

		  // get the field index
		  $fid = substr($k, 6);

		  if( in_array($fid,$deleted_items) ) 
		    {
		      $v = null;
		    }

		  // get the field type
		  $def = get_field_def($fielddefs,$fid);
		  if( !$def )
		    {
		      die('could not get field def for '.$fid);
		      continue;
		    }

		  // handle the upload (if any)
		  switch( $def->type )
		    {
		    case 'file':
		      if( isset($def->value))
			{
			  $str = "field-$fid";
			  if( isset($_FILES[$id.'customfield']['name'][$str]) &&
			      isset($_FILES[$id.'customfield']['size'][$str]) &&
			      $_FILES[$id.'customfield']['size'][$str] > 0 )
			    {
			      products_delete_uploaded_file($destdir,$def->value);
			    }
			}
		      $destdir = cms_join_path($gCms->config['uploads_path'],$this->GetName(),
					       'product_'.$compid);
		      cge_dir::mkdirr($destdir);
		      if( !is_dir($destdir) ) die('directory still does not exist');
		      $handler = new cg_fileupload($id,$destdir);
		      $handler->set_accepted_filetypes($this->GetPreference('allowed_filetypes'));
		      $res = $handler->handle_upload('customfield','','field-'.$fid);
		      $err = $handler->get_error();
		      if( !$res && $err != cg_fileupload::NOFILE )
			{
			  $errors[] = sprintf("%s %s: %s",$this->Lang('field'),$def->name,
					      $this->GetUploadErrorMessage($err));
			}
		      else if( !$res && !empty($v) )
			{
			  true;
			}
		      else if( !$res )
			{
			  $v = null;
			}
		      else
			{
			  $v = $res;
			}
		      break;
		      
		    case 'image':
		      if( isset($def->value) )
			{
			  $str = "field-$fid";
			  if( isset($_FILES[$id.'customfield']['name'][$str]) &&
			      isset($_FILES[$id.'customfield']['size'][$str]) &&
			      $_FILES[$id.'customfield']['size'][$str] > 0 )
			    {
			      products_delete_uploaded_file($destdir,$def->value);
			    }
			}
                      $attr = 'default'; // use default value for wmlocation
                      if( isset($_REQUEST[$id.'customfield_attr']) && isset($_REQUEST[$id.'customfield_attr'][$k]) )
                        {
                          $attr = $_REQUEST[$id.'customfield_attr'][$k];
                        }
		      $destdir = cms_join_path($gCms->config['uploads_path'],$this->GetName(),
					       'product_'.$compid);
                      $res = $this->HandleUploadedImage($id,'customfield',$destdir,$errors,'field-'.$fid,$attr);
                      if( $res === FALSE )
                      {
			$v = null;
                      }
                      else if( $res === TRUE )
                      {
			true;
                      }
                      else
                      {
			$v = $res;
                      }
		      break;

		    case 'subscription':
		    case 'dimensions':
		      if( is_array($v) )
			{
			  $v = serialize($v);
			}
		      break;
		      
		    case 'textbox':
		    case 'checkbox':
		    case 'textarea':
		    case 'dropdown':
		      break;

		    default:
		      die("unknown type: ".$def->type);
		      break;
		    }

		  if( !is_null($v) && !empty($v) ) {		    
		    // commit it if there is a valid value
		    $query = 'INSERT INTO '.cms_db_prefix().'module_products_fieldvals (product_id, fielddef_id, value, create_date, modified_date) VALUES (?,?,?,?,?)';
		    $db->Execute($query, array($compid, $fid, $v, trim($db->DBTimeStamp(time()), "'"), trim($db->DBTimeStamp(time()), "'")));
		  }
		}
	      }
	  }

	// Update categories
	$db->Execute('DELETE FROM '.cms_db_prefix().'module_products_product_categories WHERE product_id = ?', array($compid));
	if (isset($params['categories']))
	  {
	    foreach ($params['categories'] as $v)
	      {
	        $query = 'INSERT INTO '.cms_db_prefix().'module_products_product_categories (product_id, category_id, create_date, modified_date) VALUES (?,?,?,?)';
		$db->Execute($query, array($compid, $v, trim($db->DBTimeStamp(time()), "'"), trim($db->DBTimeStamp(time()), "'")));
	      }
	  }
		
	//Update search index
	$module =& $this->GetModuleInstance('Search');
	if ($module != FALSE)
	  {
	    $module->DeleteWords($this->GetName(), $compid, 'product');
	    if( $status == 'published' )
	      {
		$module->AddWords($this->GetName(), $compid, 'product', 
				  implode(' ', $this->GetSearchableText($compid) ) );
	      }
	  }

	if( count($errors) )
	  {
	    echo $this->ShowErrors($errors);
            echo $this->ShowErrors($this->Lang('info_fieldproblems'));
            return;
	  }
        else
          {
  	    $this->RedirectToTab($id);
          }
      }
  }
 else
   {
     $query = 'SELECT * FROM '.cms_db_prefix().'module_products WHERE id = ?';
     $row = $db->GetRow($query, array($compid));

     if ($row)
       {
	 $product_name = $row['product_name'];
	 $price = (float)$row['price'];
	 $weight = (float)$row['weight'];
	 $sku = $row['sku'];
	 $alias = $row['alias'];
	 $details = $row['details'];
	 $origname = $row['product_name'];
	 $taxable = $row['taxable'];
	 $status = $row['status'];

	 $query = 'SELECT hierarchy_id FROM '.cms_db_prefix().'module_products_prodtohier 
                    WHERE product_id = ?';
	 $hierarchy_pos = $db->GetOne($query, array( $compid) );
       }
   }

$fieldarray = array();
if (count($fielddefs) > 0)
  {
    $subscribe_opts = array();
    $subscribe_opts[-1] = $this->Lang('none');
    $subscribe_opts['monthly'] = $this->Lang('subscr_monthly');
    $subscribe_opts['quarterly'] = $this->Lang('subscr_quarterly');
    $subscribe_opts['semianually'] = $this->Lang('subscr_semianually');
    $subscribe_opts['yearly'] = $this->Lang('subscr_yearly');
    $subscribe_opts = array_flip($subscribe_opts);

    $expire_opts = array();
    $expire_opts[$this->Lang('none')] = -1;
    $expire_opts[$this->Lang('expire_six_months')] = '6';
    $expire_opts[$this->Lang('expire_one_year')] = '12';
    $expire_opts[$this->Lang('expire_two_year')] = '24';

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

    foreach ($fielddefs as $fielddef)
      {
	$field = new stdClass();

	$value = '';
	if (isset($fielddef->value))
	  {
	    $value = $fielddef->value;
	  }

	if (isset($_REQUEST[$id.'customfield']['field-'.$fielddef->id]))
	  $value = $_REQUEST[$id.'customfield']['field-'.$fielddef->id];

	$field->id = $fielddef->id;
	$field->name = $fielddef->name;
	$field->type = $fielddef->type;
	if( isset($fielddef->value) && !empty($fielddef->value) ) $field->value = $fielddef->value;
	$field->prompt = $fielddef->prompt;
	switch ($fielddef->type)
	  {
	  case 'dimensions':
	    if( !is_array($value) )
	      {
		$value = array('length'=>0,'width'=>0,'height'=>0);
	      }
	    $field->prompt .= '&nbsp;('.product_ops::get_length_units().')';
	    $field->input_box = 
	      $this->Lang('abbr_length').':&nbsp'.
	      $this->CreateInputText($id,'customfield[field-'.$fielddef->id.'][length]',
				     $value['length'],3,3).
	      $this->Lang('abbr_width').':&nbsp'.
	      $this->CreateInputText($id,'customfield[field-'.$fielddef->id.'][width]',
				     $value['width'],3,3).
	      $this->Lang('abbr_height').':&nbsp'.
	      $this->CreateInputText($id,'customfield[field-'.$fielddef->id.'][height]',
				     $value['height'],3,3);
	    break;

	  case 'checkbox':
	    $field->input_box = 
                      '<input type="hidden" name="' . $id . 'customfield[field-'.$fielddef->id.']' . '" value="false" />' . 
                      $this->CreateInputCheckbox($id, 'customfield[field-'.$fielddef->id.']', 'true', $value );
	    break;
	  case 'textarea':
	    $field->input_box = $this->CreateTextArea(true, $id, $value, 'customfield[field-'.$fielddef->id.']');
	    break;
	  case 'dropdown':
	    $field->input_box = $this->CreateInputDropdown($id, 'customfield[field-'.$fielddef->id.']',
							   $fielddef->options, -1, $value );
	    break;
	  case 'file':
	    $field->delete = $this->CreateInputCheckbox($id,'customfield[deletefield-'.$fielddef->id.']',
							1,0);
	    $field->input_box = $this->CreateFileUploadInput($id,'customfield[field-'.$fielddef->id.']','',50);
	    $field->hidden = $this->CreateInputHidden($id,'customfield[field-'.$fielddef->id.']',$value);
	    break;
	  case 'image':
	    if ($value) {
	      $destdir = cms_join_path($gCms->config['uploads_path'],$this->GetName(),
				       'product_'.$compid);
	      $url = $gCms->config['uploads_url']."/".$this->GetName()."/product_{$compid}";
	      $fn = cms_join_path($destdir,'thumb_'.$value);
	      if( file_exists($fn) ) $field->image = "{$url}/{$value}";
	      $fn = cms_join_path($destdir,'thumb_'.$value);
	      if( file_exists($fn) ) $field->thumbnail = "{$url}/thumb_{$value}";
	      $fn = cms_join_path($destdir,'preview_'.$value);
	      if( file_exists($fn) ) $field->preview = "{$url}/preview_{$value}";
	    }
            if( $this->GetPreference('autowatermark') == 'adjustable' )
              {
                $field->attribute = $this->Lang('watermark_location').'&nbsp;'.
                    $this->CreateInputDropdown($id,'customfield_attr[field-'.$fielddef->id.']',$wmopts,-1,'default');
              }
	    $field->delete = $this->CreateInputCheckbox($id,'customfield[deletefield-'.$fielddef->id.']',
							1,0);
	    $field->input_box = $this->CreateFileUploadInput($id,'customfield[field-'.$fielddef->id.']','',50);
	    $field->hidden = $this->CreateInputHidden($id,'customfield[field-'.$fielddef->id.']',$value);
	    break;
	  case 'subscription':
	    if( !is_array($value) )
	      {
		$value = array('payperiod'=>-1,'delperiod'=>-1,'expire'=>1);
	      }
	    if( !isset($value['payperiod']) ) $value['payperiod'] = -1;
	    if( !isset($value['delperiod']) ) $value['delperiod'] = -1;
	    if( !isset($value['expire']) ) $value['expire'] = -1;
	    $field->input_box = $this->Lang('subscr_payperiod').':&nbsp;';
	    $field->input_box .= $this->CreateInputDropdown($id,'customfield[field-'.$fielddef->id.'][payperiod]',
							   $subscribe_opts, -1, $value['payperiod']);
	    $field->input_box .= '<br/>'.$this->Lang('subscr_delperiod').':&nbsp;';
	    $field->input_box .= $this->CreateInputDropdown($id,'customfield[field-'.$fielddef->id.'][delperiod]',
							   $subscribe_opts, -1, $value['delperiod']);
	    $field->input_box .= '<br/>'.$this->Lang('subscr_expiry').':&nbsp;';
	    $field->input_box .= $this->CreateInputDropdown($id,'customfield[field-'.$fielddef->id.'][expire]',
							   $expire_opts, -1, $value['expire']);
	    break;
	  case 'textbox':
	  default:
	    $field->input_box = $this->CreateInputText($id, 'customfield[field-'.$fielddef->id.']', $value, 30, 255);
	    break;
	  }

	$fieldarray[] = $field;
      }
  }

$allcategories = $this->GetCategories();
$catarray = array();
foreach( $allcategories as $one )
{
  $catarray[$one->name] = $one->id;
}
$selcategories = $this->GetCategoriesForProduct($compid);
$selcatarray = array();
foreach( $selcategories as $one )
{
  if( !$one->value ) continue;
  $selcatarray[$one->name] = $one->id;
}

#Display template
$this->smarty->assign('startform', $this->CreateFormStart($id, 'editproduct', $returnid, 'post', 'multipart/form-data'));
$this->smarty->assign('endform', $this->CreateFormEnd());
$this->smarty->assign('nametext', $this->Lang('name'));
$this->smarty->assign('inputname', $this->CreateInputText($id, 'product_name', $product_name, 30, 255));
$this->smarty->assign('pricetext', $this->Lang('price'));
$smarty->assign('currency_symbol',product_ops::get_currency_symbol());
$this->smarty->assign('inputprice', $this->CreateInputText($id, 'price', sprintf("%.2f",$price), 10, 12));
$this->smarty->assign('weighttext', $this->Lang('weight'));
$this->smarty->assign('weightunits',$this->GetPreference('products_weightunits'));
$this->smarty->assign('inputweight', $this->CreateInputText($id, 'weight', 
							    sprintf("%.2f",$weight), 10, 12));
$this->smarty->assign('inputsku',$this->CreateInputText($id,'sku',$sku,10,25));
$this->smarty->assign('inputalias',$this->CreateInputText($id,'alias',$alias,40,255));
$this->smarty->assign('detailstext', $this->Lang('details'));
$this->smarty->assign('inputdetails', $this->CreateTextArea(true, $id, $details, 'details', '', '', '', '', '80', '5'));

if( count($catarray) > 0 ) {
  $n = count($catarray)/4;
  $n = min($n,20);
  $n = max($n,5);
$smarty->assign('input_categories',
		$this->CreateInputSelectList($id,'categories[]',$catarray,$selcatarray,$n));;
 }

$smarty->assign('taxabletext',$this->Lang('taxable'));
$smarty->assign('inputtaxable',
		$this->CreateInputCheckbox($id,'taxable',1,$taxable));

$hierarchy_items = $this->BuildHierarchyList();
$smarty->assign('hierarchy_items',$hierarchy_items);
$smarty->assign('hierarchy_pos',$hierarchy_pos);

$statuses = array($this->Lang('published')=>'published',
		  $this->Lang('draft')=>'draft',
		  $this->Lang('disabled')=>'disabled');
$smarty->assign('statustext',$this->Lang('status'));
$smarty->assign('inputstatus',
		$this->CreateInputDropdown($id,'status',
					   $statuses,-1,$status));

$smarty->assign('idtext',$this->Lang('id'));
$smarty->assign('compid',$compid);
$this->smarty->assign('hidden', 
		      $this->CreateInputHidden($id, 'compid', $compid).
		      $this->CreateInputHidden($id, 'origname', $origname));
$this->smarty->assign('submit', $this->CreateInputSubmit($id, 'submit', lang('submit')));
$this->smarty->assign('cancel', $this->CreateInputSubmit($id, 'cancel', lang('cancel')));
$this->smarty->assign_by_ref('customfields', $fieldarray);
$this->smarty->assign('customfieldscount', count($fieldarray));
echo $this->ProcessTemplate('editproduct.tpl');

?>
