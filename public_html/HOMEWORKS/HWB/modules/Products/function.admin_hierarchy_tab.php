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
if( !$this->CheckPermission('Modify Products') ) exit;

$query = 'SELECT * FROM '.cms_db_prefix().'module_products_hierarchy 
           ORDER by hierarchy';
$dbr = $db->Execute($query);

$entryarray = array();
while( $dbr && ($row = $dbr->FetchRow()) )
  {
    $row['edit_url'] = $this->CreateURL($id,'admin_edit_hierarchy_item',
					$returnid,
					array('hierarchy_id'=>$row['id']));
    $row['depth'] = count(split('\.', $row['hierarchy'])) - 1;
    $row['edit_link'] = $this->CreateImageLink($id,'admin_edit_hierarchy_item',
					       $returnid,
					       $this->Lang('edit_hierarchy_item'),
					       'icons/system/edit.gif',
					       array('hierarchy_id'=>$row['id']));
    $row['delete_link'] = $this->CreateImageLink($id,'admin_delete_hierarchy_item',
						 $returnid,
						 $this->Lang('delete_hierarchy_item'),
						 'icons/system/delete.gif',
						 array('hierarchy_id'=>$row['id']),'',
						 $this->Lang('confirm_delete_hierarchy_node'));
    $entryarray[] = $row;
  }

$smarty->assign('entries',$entryarray);
$smarty->assign('add_hierarchy_link',
		$this->CreateImageLink($id,'admin_add_hierarchy_item',
				       $returnid,
				       $this->Lang('add_hierarchy_item'),
				       'icons/system/newobject.gif',
				       array(),'','',false));

echo $this->ProcessTemplate('admin_hierarchy_tab.tpl');
#
# EOF
#
?>