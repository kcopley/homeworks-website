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
$this->SetCurrentTab('hierarchy');

if( isset($params['cancel']) )
  {
    // we're cancelling
    $this->RedirectToTab($id);
  }

#
# Defaults
#
$parent = -1;
$name = '';
$extra1 = '';
$extra2 = '';
$description = '';
$image = '';

# 
# form actions
#
if( isset($params['submit']) )
  {
    if( isset($params['parent']) )
      {
	$parent = (int)$params['parent'];
      }
    $name = trim($params['name']);
    $extra1 = trim($params['extra1']);
    $extra2 = trim($params['extra2']);
    $description = trim($params['description']);
    $error = 0;
    if( empty($name) )
      {
	$error = 1;
	echo $this->ShowErrors($this->Lang('error_noname'));
      }

    if( !$error )
      {
	$query = 'SELECT id FROM '.cms_db_prefix().'module_products_hierarchy WHERE parent_id = ? AND name = ?';
	$tmp = $db->GetOne($query,array($parent,$name));
	if( $tmp )
	  {
	    $error = 1;
	    echo $this->ShowErrors($this->Lang('error_nameused'));
	  }
      }

    if( !$error )
      {
	// Handle file upload
        $attr = 'default';
        if( isset($params['input_watermark']) )
        {
           $attr = $params['input_watermark'];
        }
        $errors = array();
	$destdir = cms_join_path($gCms->config['uploads_path'],$this->GetName(),
				 'hierarchy');
        $res = $this->HandleUploadedImage($id,'file',$destdir,$errors,'',$attr);
        if( $res === FALSE )
	  {
	    // an error ensued.
            echo $this->ShowErrors($errors);
	  }
	else
	  {
	    $image = $res;
            if( $res === TRUE ) $image = '';

	    $query = 'INSERT INTO '.cms_db_prefix().'module_products_hierarchy
                    (name, parent_id, description, image, extra1, extra2) VALUES(?,?,?,?,?,?)';
	    $dbr = $db->Execute($query,array($name,$parent,$description,$image,$extra1,$extra2));
	    if( !$dbr ) { echo $db->sql.'<br/>'; die( $db->ErrorMsg() ); }
	    $new_id = $db->Insert_ID();
	    $this->UpdateHierarchyPositions();
	    $this->RedirectToTab($id);
	  }
      }
  }

#
# Build the form
#
$hierarchy_items = $this->BuildHierarchyList();
$smarty->assign('hierarchy_items',$hierarchy_items);
$smarty->assign('parent',$parent);
$smarty->assign('name',$name);
$smarty->assign('extra1',$extra1);
$smarty->assign('extra2',$extra2);
$smarty->assign('description',$description);
$smarty->assign('image',$image);

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
  $smarty->assign('watermark_location',
    $this->CreateInputDropdown($id,'input_watermark',$wmopts,-1,'default'));
}

$smarty->assign('formstart',
		$this->CGCreateFormStart($id,'admin_add_hierarchy_item',$returnid,
					 $params,false,'post','multipart/form-data'));
$smarty->assign('formend',$this->CreateFormEnd());

echo $this->ProcessTemplate('admin_add_hierarchy_item.tpl');
#
# EOF
#
?>