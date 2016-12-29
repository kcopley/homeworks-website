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

//
// initialization
//
$inline = 0;
$resultpage = $returnid;
$thetemplate = $this->GetPreference(PRODUCTS_PREF_DFLTSEARCH_TEMPLATE);
$searchfield = '';
$sortorder = $this->GetPreference('sortorder','asc');
$sortby = $this->GetPreference('sortby','product_name');
$fields = array();
{
  $tmp = $this->GetFieldDefs();
  if( count($tmp) )
    {
      for( $i = 0; $i < count($tmp); $i++ )
	{
	  $obj =& $tmp[$i];
	  $fields[$obj->name] = $obj;
	}
    }
}


//
// Get parameters
//
if( isset($params['inline']) && $params['inline'] != 0 )
  {
    $inline = 1;
    unset($params['inline']);
  }
if( isset($params['resultpage']) )
  {
    $tmp = $this->resolve_alias_or_id($params['resultpage']);
    if( $tmp )
      {
	$resultpage = $tmp;
	$inline = 0;
      }
    unset($params['resultpage']);
  }
if( isset($params['searchformtemplate']) )
  {
    $thetemplate = trim($params['searchformtemplate']);
    unset($params['searchformtemplate']);
  }
if( isset($params['searchfield']) )
  {
    $searchfield = trim($params['searchfield']);
  }


//
// Get the field definitions.
//
if( !empty($searchfield) )
  {
    $searchfields = array();
    $tfield = explode(',',$searchfield);
    foreach( $tfield as $onefield )
      {
	if( !isset($fields[$onefield]) ) continue;

	$obj =& $fields[$onefield];
	if( $obj->type == 'dropdown' )
	  {
	    $obj->options = cge_array::hash_prepend($obj->options,-1,$this->Lang('any'));
	  }
	$searchfields[$obj->name] = $obj;
      }

    $smarty->assign('searchprops',$searchfields);
  }

$smarty->assign('cd_origpage',$returnid);
$smarty->assign('formstart',$this->CGCreateFormStart($id,'do_search',$resultpage,$params,$inline));
$smarty->assign('formend',$this->CreateFormEnd());

#
# Process the template
#
echo $this->ProcessTemplateFromDatabase('search_'.$thetemplate);

#
# EOF
#
?>