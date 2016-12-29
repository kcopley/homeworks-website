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

#The tabs
echo $this->StartTabHeaders();

if ($this->CheckPermission('Modify Products'))
{
  echo $this->SetTabHeader('products',$this->Lang('products'));
  echo $this->SetTabHeader('fielddefs',$this->Lang('fielddefs'));
  echo $this->SetTabHeader('hierarchy',$this->Lang('product_hierarchy'));
  echo $this->SetTabHeader('categories',$this->Lang('categories'));
}

if ($this->CheckPermission('Modify Templates'))
{
  echo $this->SetTabHeader('summary_template',$this->Lang('summarytemplates'));
  echo $this->SetTabHeader('detail_template',$this->Lang('detailtemplates'));
  echo $this->SetTabHeader('byhierarchy_template',$this->Lang('byhierarchytemplates'));
  echo $this->SetTabHeader('categorylist_template',$this->Lang('categorylisttemplates'));
  echo $this->SetTabHeader('search_template',$this->Lang('searchtemplates'));
  echo $this->SetTabHeader('default_templates',$this->Lang('defaulttemplates'));
}

if ($this->CheckPermission('Modify Site Preferences'))
  {
    echo $this->SetTabHeader('prefs',$this->Lang('preferences'));
  }
echo $this->EndTabHeaders();

#The content of the tabs
echo $this->StartTabContent();
if ($this->CheckPermission('Modify Products'))
{	
  echo $this->StartTab('products', $params);
  include(dirname(__FILE__).'/function.admin_productstab.php');
  echo $this->EndTab();
	
  echo $this->StartTab('fielddefs', $params);
  include(dirname(__FILE__).'/function.admin_fielddefs_tab.php');
  echo $this->EndTab();
	
  echo $this->StartTab('hierarchy', $params);
  include(dirname(__FILE__).'/function.admin_hierarchy_tab.php');
  echo $this->EndTab();
	
  echo $this->StartTab('categories', $params);
  include(dirname(__FILE__).'/function.admin_categories_tab.php');
  echo $this->EndTab();
 }

if( $this->CheckPermission( 'Modify Templates' ) )
  {
    include(dirname(__FILE__).'/function.admin_templatestabs.php');
  }

if( $this->CheckPermission('Modify Site Preferences') )
  {
    echo $this->StartTab('prefs',$params);
    include(dirname(__FILE__).'/function.admin_prefstab.php');
    echo $this->EndTab();
  }

echo $this->EndTabContent();

# vim:ts=4 sw=4 noet
?>
