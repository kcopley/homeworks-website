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
$this->SetCurrentTab('categories');

if (!$this->CheckPermission('Modify Products'))
{
	echo $this->ShowErrors($this->Lang('needpermission', array('Modify Products')));
	return;
}

if (isset($params['cancel']))
{
  $this->RedirectToTab($id);
}

$catid = '';
if (isset($params['catid']))
{
	$catid = $params['catid'];
}

$name = '';
if (isset($params['name']))
{
	$name = $params['name'];
}

$origname = '';
if (isset($params['origname']))
{
	$origname = $params['origname'];
}

if (isset($params['submit']))
{
	if ($name != '')
	{
		$query = 'UPDATE '.cms_db_prefix().'module_products_categories SET name = ?, modified_date = '.$db->DBTimeStamp(time()).' WHERE id = ?';
		$db->Execute($query, array($name, $catid));
						
		$this->RedirectToTab($id);
	}
	else
	{
		echo $this->ShowErrors($this->Lang('nonamegiven'));
	}
}
else
{
	$query = 'SELECT * FROM '.cms_db_prefix().'module_products_categories WHERE id = ?';
	$row = $db->GetRow($query, array($catid));

	if ($row)
	{
		$name = $row['name'];
		$origname = $row['name'];
	}
}

#Display template
$this->smarty->assign('startform', $this->CreateFormStart($id, 'editcategory', $returnid));
$this->smarty->assign('endform', $this->CreateFormEnd());
$this->smarty->assign('nametext', $this->Lang('name'));
$this->smarty->assign('inputname', $this->CreateInputText($id, 'name', $name, 80, 255));
$this->smarty->assign('hidden', 
		      $this->CreateInputHidden($id, 'catid', $catid).
		      $this->CreateInputHidden($id, 'origname', $origname));
$this->smarty->assign('submit', $this->CreateInputSubmit($id, 'submit', lang('submit')));
$this->smarty->assign('cancel', $this->CreateInputSubmit($id, 'cancel', lang('cancel')));

echo $this->ProcessTemplate('editcategory.tpl');
?>
