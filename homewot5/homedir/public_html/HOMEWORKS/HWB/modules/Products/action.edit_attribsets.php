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
echo $this->SetCurrentTab('products');

//SELECT A.*,count(B.attrib_text) AS count FROM cms_module_products_attribsets A LEFT JOIN cms_module_products_attributes B ON A.attrib_set_id = B.attrib_set_id GROUP BY attrib_set_id;
$query = "SELECT A.*,count(B.attrib_text) AS count 
          FROM ".cms_db_prefix()."module_products_attribsets A 
          LEFT JOIN ".cms_db_prefix()."module_products_attributes B 
          ON A.attrib_set_id = B.attrib_set_id 
          WHERE product_id = ?
          GROUP BY attrib_set_id";
$dbresult = $db->Execute( $query, array($params['compid']) );
$objects = array();
while( $dbresult && ($row = $dbresult->FetchRow()) )
  {
	$obj = cge_array::to_object($row);
	$obj->deletelink = $this->CreateImageLink($id,'delete_attribset',$returnid,
											  $this->Lang('delete_attribset'),
											  'icons/system/delete.gif',
											  array('compid'=>$params['compid'],
													'attrsetid'=>$row['attrib_set_id']));

	$obj->editlink = $this->CreateImageLink($id,'add_attrib_set',$returnid,
											  $this->Lang('edit_attribset'),
											  'icons/system/edit.gif',
											  array('compid'=>$params['compid'],
													'attrsetid'=>$row['attrib_set_id']));

	$objects[] = $obj;
  }

$query = 'SELECT product_name FROM '.cms_db_prefix().'module_products
           WHERE id = ?';
$product_name = $db->GetOne($query,array($params['compid']));
$smarty->assign('title_attribsets_for',$this->Lang('edit_attribsets_for'));
$smarty->assign('product_name',$product_name);

$smarty->assign('items',$objects);
$smarty->assign('itemcount',count($objects));
$smarty->assign('idtext',$this->Lang('id'));
$smarty->assign('nametext',$this->Lang('name'));
$smarty->assign('counttext',$this->Lang('values'));

$smarty->assign('add_link',
				$this->CreateImageLink($id,'add_attrib_set',$returnid,
									   $this->Lang('add_attrib_set'),
									   'icons/system/newobject.gif',
									   array('compid'=>$params['compid']),
									   '','',false));

$smarty->assign('copy_link',
				$this->CreateImageLink($id,'admin_copy_attribsets',$returnid,
									   $this->Lang('copy_attrib_sets'),
									   'icons/system/copy.gif',
									   array('dest_compid'=>$params['compid']),
									   '','',false));
$smarty->assign('return_link',
				$this->CreateImageLink($id,'defaultadmin',$returnid,
									   $this->Lang('return'),
									   'icons/system/back.gif',
									   array('compid'=>$params['compid']),
									   '','',false));


echo $this->ProcessTemplate('edit_attribsets.tpl');

// EOF
?>