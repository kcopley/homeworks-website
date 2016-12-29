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
$catid = (int)$params['catid'];

// Get the info for this category
$query = 'SELECT * FROM '.cms_db_prefix().'module_products_categories WHERE id = ?';
$category = $db->GetRow($query,array($catid));
$smarty->assign('category',$category);

// get the list of fields for this product
$query = 'SELECT * FROM '.cms_db_prefix().'module_products_category_fields
           WHERE category_id = ?
           ORDER BY field_order ASC';
$fields = $db->GetArray($query,array($catid));
for( $i = 0; $i < count($fields); $i++ )
  {
    $fields[$i]['editlink'] = $this->CreateLink($id,'addcategoryfield',$returnid,
						$gCms->variables['admintheme']->DisplayImage('icons/system/edit.gif',$this->Lang('edit'),'','',
											     'systemicon'),
						array('catid'=>$catid,'fldname'=>$fields[$i]['field_name']));
    $fields[$i]['deletelink'] = $this->CreateLink($id,'deletecategoryfield',$returnid,
						$gCms->variables['admintheme']->DisplayImage('icons/system/delete.gif',$this->Lang('delete'),'','',
											     'systemicon'),
						array('catid'=>$catid,'fldname'=>$fields[$i]['field_name']));

    if( $fields[$i]['field_order'] > 0 )
      {
	$fields[$i]['move_up'] = $this->CreateImageLink($id,'movecategoryfield',$returnid,$this->Lang('move_up'),
							'icons/system/sort_up.gif',
							array('catid'=>$catid,'dir'=>'up','fldname'=>$fields[$i]['field_name']));
      }
    if( $i < count($fields) - 1 )
      {
	$fields[$i]['move_down'] = $this->CreateImageLink($id,'movecategoryfield',$returnid,$this->Lang('move_down'),
							  'icons/system/sort_down.gif',
							  array('catid'=>$catid,'dir'=>'down','fldname'=>$fields[$i]['field_name']));
      }
  }
$smarty->assign('fields',$fields);

$smarty->assign('addlink', $this->CreateLink($id, 'addcategoryfield', $returnid, 
					     $gCms->variables['admintheme']->DisplayImage('icons/system/newfolder.gif', $this->Lang('addfielddef'),
											  '','','systemicon'), 
					     array('catid'=>$catid), '', false, false, '').' '.
		           $this->CreateLink($id, 'addcategoryfield', $returnid, $this->Lang('addfielddef'), 
					     array('catid'=>$catid), '', false, false, 'class="pageoptions"'));

$smarty->assign('return_link',
		$this->CreateImageLink($id,'defaultadmin',$returnid,
				       $this->Lang('return'),
				       'icons/system/back.gif',
				       array('cg_activetab'=>'categories'),
				       '','',false));

# Display Template
echo $this->ProcessTemplate('editcategoryfields.tpl');


#
# EOF
#
?>