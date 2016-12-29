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
if( !isset($params['dest_compid']) )
  {
	echo $this->ShowErrors($this->Lang('error_missingparam'));
	return;
  }
echo $this->SetCurrentTab('products');

//
// initialize
//
$dest_compid = (int) $params['dest_compid'];

if( isset($params['cancel']) )
  {
    $this->Redirect($id,'edit_attribsets',$returnid,array('compid'=>$dest_compid));
  }
else if( isset($params['submit']) )
  {
    $src_compid = (int)$params['src_compid'];
    if( $src_compid == $dest_compid )
      {
	$this->SetError($this->Lang('error_invalid_value'));
	$this->Redirect($id,'edit_attribsets',$returnid,array('compid'=>$dest_compid));
      }

    // delete all our current stuff
    $query = 'SELECT attrib_set_id FROM '.cms_db_prefix().'module_products_attribsets 
               WHERE product_id = ?';
    $tmp = $db->GetCol($query,array($dest_compid));
    if( is_array($tmp) && count($tmp) )
      {
	$query = 'DELETE FROM '.cms_db_prefix().'module_products_attributes
                   WHERE attrib_set_id IN ('.implode(',',$tmp).')';
	$db->Execute($query);

	$query = 'DELETE FROM '.cms_db_prefix().'module_products_attribsets
                   WHERE product_id = ?';
	$db->Execute($query,array($dest_compid));
      }


    // get all the source stuff
    $query = 'SELECT att.*,ats.attrib_set_name FROM '.cms_db_prefix().'module_products_attributes att
              LEFT JOIN '.cms_db_prefix().'module_products_attribsets ats
                ON att.attrib_set_id = ats.attrib_set_id
              WHERE ats.product_id = ?
              ORDER BY att.attrib_set_id';
    $tmp = $db->GetArray($query,array($src_compid));
    
    $prev_attr_set_name = '';
    $attrib_set_id = '';
    foreach( $tmp as $row )
      {
	if( $row['attrib_set_name'] != $prev_attr_set_name )
	  {
	    $query = 'INSERT INTO '.cms_db_prefix().'module_products_attribsets 
                       (product_id,attrib_set_name) VALUES (?,?)';
	    $db->Execute($query,array($dest_compid,$row['attrib_set_name']));

	    $attrib_set_id = $db->Insert_ID();
	  }

	if( $attrib_set_id != '' )
	  {
	    $query = 'INSERT INTO '.cms_db_prefix().'module_products_attributes
                      (attrib_set_id,attrib_text,attrib_adjustment)
                      VALUES (?,?,?)';
	  }
	$db->Execute($query,array($attrib_set_id,$row['attrib_text'],$row['attrib_adjustment']));

	$prev_attr_set_name = $row['attrib_set_name'];
      }

    $this->SetMessage($this->Lang('info_attributes_copied'));
    $this->Redirect($id,'edit_attribsets',$returnid,array('compid'=>$dest_compid));
  }

//
// give everything to smarty
//
$query = 'SELECT id,product_name FROM '.cms_db_prefix().'module_products ORDER BY modified_date DESC';
$tmp = $db->GetArray($query);

// remove our current item.
$tmp2 = array();
foreach( $tmp as $row )
{
  $tmp2[$row['id']] = $row['product_name'];
}
if( isset($tmp2[$dest_compid]) ) unset($tmp2[$dest_compid]);
$smarty->assign('product_list',$tmp2);

$smarty->assign('dest_product_name',$this->GetProductNameFromId($dest_compid));
$smarty->assign('formstart',$this->CGCreateFormStart($id,'admin_copy_attribsets','',$params));
$smarty->assign('formend',$this->CreateFormEnd());
echo $this->ProcessTemplate('admin_copyattribsets.tpl');

#
# EOF
#
?>