<?php  /* -*- Mode: PHP; tab-width: 4; c-basic-offset: 2 -*- */
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
if( !isset($gCms) ) exit();
if (!$this->CheckPermission('Modify Products'))
  {
	echo $this->ShowErrors($this->Lang('needpermission', array('Modify Products')));
	return;
  }
if( !isset($params['compid']) )
  {
    echo $this->ShowErrors($this->Lang('error_missingparam'));
    return;
  }

if( isset($params['cancel']) )
  {
    $this->Redirect($id,'edit_attribsets',$returnid,
					array('compid'=>$params['compid']));
    return;
  }

// get product info
$q = 'SELECT * FROM '.cms_db_prefix().'module_products WHERE id = ?';
$product = $db->GetRow($q,array($params['compid']));
if( !$product )
  {
	echo $this->ShowErrors($this->Lang('error_product_notfound'));
	return;
  }

// initialization
$set_name = '';
$attribset_values = array();
if( isset($params['attrsetid']) && !isset($params['value_update']) &&
	!isset($params['submit']))
  {
    // we were passed an attribute set id
    // load the values from the database.
    $query = 'SELECT attrib_set_name FROM '.cms_db_prefix().'module_products_attribsets
               WHERE attrib_set_id = ?';
    $set_name = $db->GetOne($query,array($params['attrsetid']));
    if( !$set_name )
      {
		echo $this->ShowErrors($this->Lang('error_dberror'));
		return;
      }

    $query = 'SELECT * FROM '.cms_db_prefix().'module_products_attributes 
               WHERE attrib_set_id = ?';
    $tmp = $db->GetArray( $query, array($params['attrsetid']) );
	if( $tmp )
	  {
		$attribset_values = cge_array::to_hash($tmp,'attrib_text');
	  }
  }

// Process the input
if( isset($params['attribute_name']) )
  {
    $set_name = trim($params['attribute_name']);
  }


$errors = array();
if( isset($params['opttext']) && isset($params['optvalue']) && 
	isset($params['optsku']) 
    && (count($params['opttext']) == count($params['optvalue'])) )
  {
    for( $i = 0; $i < count($params['opttext']); $i++ )
      {
		$k = trim($params['opttext'][$i]);
		$v = trim($params['optvalue'][$i]);
		$s = trim($params['optsku'][$i]);
		if( empty($k) ) continue;
		if( !preg_match('/[\+\-\*]?\d+(\.\d+)?/',$v) )
		  {
			$errors[] = $this->Lang('error_price_adjustment',$k);
		  }
		if( $s != '' && product_ops::check_sku_used($s) )
		  {
			$errors[] = $this->Lang('error_product_skuused',$s);
		  }
		$tmp = array('attrib_text'=>$k,
					 'attrib_adjustment'=>$v,
					 'sku'=>$s);
		$attribset_values[$k] = $tmp;
      }
  }

// now check for duplicate skus
{
  $tmp = array();
  foreach( $attribset_values as $k => $rec )
	{
	  if( $rec['sku'] == '' ) continue;
	  $tmp[] = trim($rec['sku']);
	}
  $tmp2 = array_unique($tmp);
  if( count($tmp) != count($tmp2) )
	{
	  $errors[] = $this->Lang('error_duplicate_sku');
	}
}

// now show errors.
if( count($errors) )
  {
	echo $this->ShowErrors($errors);
  }

// Process the submit button
if( isset($params['submit']) && !count($errors) )
  {
    // Validate all parameters
    if( empty( $set_name ) )
      {
		echo $this->ShowErrors($this->Lang('error_missingparam'));
      }
    else
      {
		if( isset($params['attrsetid']) )
		  {
			// doing an update

			// Ensure that the name is valid
			// Ensure there is no set name that exists
			$query = 'SELECT attrib_set_id FROM '.cms_db_prefix().'module_products_attribsets 
                       WHERE attrib_set_name = ? 
                         AND product_id = ?
                         AND product_set_id != ?';
			$asid = $db->GetOne($query,
								array($set_name,$params['compid'],
									  $params['attrsetid']));

			if( $asid )
			  {
				// we're adding and the name already exists
				echo $this->ShowErrors($this->Lang('error_attribset_exists'));
			  }
			else
			  {
				// 1.  Delete the attribute values
				$query = 'DELETE FROM '.cms_db_prefix().'module_products_attributes
                           WHERE attrib_set_id = ?';
				$db->Execute( $query, array( $params['attrsetid']) );

				// 2.  Update the attribute record
				$query = 'UPDATE '.cms_db_prefix().'module_products_attribsets
                             SET attrib_set_name = ?
                           WHERE attrib_set_id = ?';
				$db->Execute( $query, array($set_name,$params['attrsetid']) );

				// 3.  Insert the attribute values
				$query = 'INSERT INTO '.cms_db_prefix().'module_products_attributes
                        (attrib_set_id,attrib_text,attrib_adjustment,sku)
                      VALUES(?,?,?,?)';
				foreach($attribset_values as $key => $rec )
				  {
					$dbresult = $db->Execute( $query, 
											  array($params['attrsetid'],
													$key,
													$rec['attrib_adjustment'],
													$rec['sku']));
					if( !$dbresult )
					  {
						echo $db->ErrorMsg().'<br/>'.$db->sql;die();
					  }
				  }

				// 4.  Redirect
				$this->Redirect($id,'edit_attribsets',$returnid,
								array('compid'=>$params['compid']));
				return;
			  }
		  }
		else
		  {
			// doing an insert

			// Ensure there is no set name that exists
			$query = 'SELECT attrib_set_id FROM '.cms_db_prefix().'module_products_attribsets WHERE attrib_set_name = ? AND product_id = ?';
			$asid = $db->GetOne($query,array($set_name,$params['compid']));
			if( $asid )
			  {
				// we're adding and the name already exists
				echo $this->ShowErrors($this->Lang('error_attribset_exists'));
			  }
			else
			  {
				// 1. Commit the new set
				$query = 'INSERT INTO '.cms_db_prefix().'module_products_attribsets
                         (product_id,attrib_set_name)
                      VALUES (?,?)';
				$dbresult = $db->Execute( $query, array($params['compid'],$set_name));
				if( !$dbresult )
				  {
					echo $db->ErrorMsg().'<br/>'.$db->sql;die();
				  }
				$asid = $db->Insert_ID();
		
				// 2. Add the values
				$query = 'INSERT INTO '.cms_db_prefix().'module_products_attributes
                        (attrib_set_id,attrib_text,attrib_adjustment,sku)
                      VALUES(?,?,?,?)';
				foreach($attribset_values as $k => $rec )
				  {
					$dbresult = $db->Execute( $query, array($asid,$k,$rec['attrib_adjustment'],$rec['sku']));
					if( !$dbresult )
					  {
						echo $db->ErrorMsg().'<br/>'.$db->sql;die();
					  }
				  }

				// 3. Redirect
				$this->Redirect($id,'edit_attribsets',$returnid,
								array('compid'=>$params['compid']));
				return;
			  }
		  }
      }
  }

$smarty->assign('product',$product);
$parms = array();
$parms['compid'] = $params['compid'];
if( isset($params['attrsetid']) )
  $parms['attrsetid'] = $params['attrsetid'];
$smarty->assign('formstart',$this->CGCreateFormStart($id,'add_attrib_set',$returnid,$parms));
$smarty->assign('formend',$this->CreateFormEnd());
$smarty->assign('submit',$this->CreateInputSubmit($id,'submit',$this->Lang('submit')));
$smarty->assign('cancel',$this->CreateInputSubmit($id,'cancel',$this->Lang('cancel')));
$smarty->assign('idtext',$this->Lang('id'));
$smarty->assign('keytext',$this->Lang('name'));
$smarty->assign('valuetext',$this->Lang('price_adjustment'));
$smarty->assign('info_valuetext',$this->Lang('info_price_adjustment'));

$smarty->assign('prompt_set_name',$this->Lang('attribute_name'));
$smarty->assign('input_set_name',
				$this->CreateInputText($id,'attribute_name',$set_name,50,255));
if( isset($attribset_values) )
  {
    $i = 0;
    $sm_attribset = array();
    foreach($attribset_values as $key => $rec )
      {
		$obj = new StdClass();
		$obj->idx = $i+1;
		$obj->key = $this->CreateInputText($id,'opttext[]',$key,50);
		$obj->value = $this->CreateInputText($id,'optvalue[]',$rec['attrib_adjustment'],10,50);
		$obj->sku = $this->CreateInputText($id,'optsku[]',$rec['sku'],10,25);
		$sm_attribset[] = $obj;
		$i++;
      }

	$obj = new StdClass();
	$obj->idx = $i+1;
	$obj->key = $this->CreateInputText($id,'opttext[]','',50);
	$obj->value = $this->CreateInputText($id,'optvalue[]','',10,50);
	$obj->sku = $this->CreateInputText($id,'optsku[]','',10,25);
	$sm_attribset[] = $obj;
	$i++;

    $smarty->assign('values',$sm_attribset);
  }

// add fields to add a new value
$smarty->assign('input_update',
				$this->CreateInputSubmit($id,'value_update',$this->Lang('update')));

echo $this->ProcessTemplate('add_attrib_set.tpl');

// EOF
?>
