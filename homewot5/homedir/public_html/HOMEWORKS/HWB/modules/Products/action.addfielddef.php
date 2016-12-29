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

$this->SetCurrentTab('fielddefs');
if (!$this->CheckPermission('Modify Products'))
{
	echo $this->ShowErrors($this->Lang('needpermission', array('Modify Products')));
	return;
}

if (isset($params['cancel']))
{
  $this->RedirectToTab($id);
}

$name = '';
if (isset($params['name']))
{
	$name = $params['name'];
}

$prompt = '';
if (isset($params['prompt']))
{
	$prompt = $params['prompt'];
}

$options = '';
if (isset($params['options']))
{
  $options = trim($params['options']);
}

$type = '';
if (isset($params['type']))
{
	$type = $params['type'];
}

$max_length = 255;
if (isset($params['max_length']))
{
  $max_length = (int)$params['max_length'];
}

$public = 1;
if( isset($params['public']) )
  {
    $public = (int)$params['public'];
  }

$userid = get_userid();

if (isset($params['submit']))
{
  if( $type == '' )
    {
      echo $this->ShowErrors($this->Lang('error_nofieldtype'));
    }
  else if ($name == '')
    {
      echo $this->ShowErrors($this->Lang('nonamegiven'));
    }
  else if( !$this->is_alias($name) )
    {
      echo $this->ShowErrors($this->Lang('error_invalid_name'));
    }
  else if( !empty($max_length) && !is_numeric($max_length) )
    {
      echo $this->ShowErrors($this->Lang('notanumber'));
    }
  else if( $type == 'subscription' && $db->GetOne('SELECT id FROM '.cms_db_prefix().'module_products_fielddefs WHERE type = ?',array($type)) )
    {
      echo $this->ShowErrors($this->Lang('error_onesubscriptionfield'));
    }
  else
    {
      $max = $db->GetOne('SELECT max(item_order) + 1 FROM ' . cms_db_prefix() . 'module_products_fielddefs');
      if( !$max ) $max = 0;
      $query = 'INSERT INTO '.cms_db_prefix().'module_products_fielddefs (name, prompt, type, max_length, item_order, create_date, modified_date, public, options) VALUES (?,?,?,?,?,?,?,?,?)';
      $parms = array($name, $prompt, $type, $max_length, $max, 
		     trim($db->DBTimeStamp(time()), "'"), 
		     trim($db->DBTimeStamp(time()), "'"),
		     $public,$options);
      $dbr = $db->Execute($query, $parms );
      if( !$dbr ) {
	echo "DEBUG: result = ".$db->ErrorMsg()."<br/>&nbsp;&nbsp;".$db->sql."<br/>"; die();
      }
      
      $this->RedirectToTab($id);
    }
}

#Display template
$smarty->assign('options',$options);
$smarty->assign('startform', 
		$this->CGCreateFormStart($id, 'addfielddef', $returnid));
$smarty->assign('endform', $this->CreateFormEnd());
$smarty->assign('inputname', $this->CreateInputText($id, 'name', $name, 30, 255));
$smarty->assign('inputprompt',$this->CreateInputText($id,'prompt',$prompt,50,255));
$smarty->assign('showinputtype', true);

$addtext = "onChange='this.form.submit()'";
$smarty->assign('inputtype', $this->GetTypesDropdown($id, 'type', $type, $addtext, true));
$smarty->assign('type',$type);

if( $type == 'textbox' )
  {
    $smarty->assign('inputmaxlength', $this->CreateInputText($id, 'max_length', $max_length, 30, 255));
  }

$smarty->assign('userviewtext',$this->Lang('public'));
$smarty->assign('input_userview',
		$this->CreateInputcheckbox($id, 'public', 1, $public));

$smarty->assign('hidden', '');
$smarty->assign('submit', $this->CreateInputSubmit($id, 'submit', lang('submit')));
$smarty->assign('cancel', $this->CreateInputSubmit($id, 'cancel', lang('cancel')));

echo $this->ProcessTemplate('editfielddef.tpl');

?>