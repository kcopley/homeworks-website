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

class product_utils
{
  static public function get_field_types($selectone = false)
  {
    $mod = cge_utils::get_module('Products');
    $items = array();
    if( $selectone )
      {
	$items[$mod->Lang('select_one')] = '';
      }
    $items[$mod->Lang('textbox')] = 'textbox';
    $items[$mod->Lang('checkbox')] = 'checkbox';
    $items[$mod->Lang('textarea')] = 'textarea';
    $items[$mod->Lang('dropdown')] = 'dropdown';
    $items[$mod->Lang('imagetext')] = 'image';
    $items[$mod->Lang('file')] = 'file';
    $items[$mod->Lang('dimensions')] = 'dimensions';
    $items[$mod->Lang('subscription')] = 'subscription';
    return array_flip($items);
  }


  public static function get_displayable_fieldval($fieldname,$fieldval)
  {
    $mod = cge_utils::get_module('Products');
    $fields = product_ops::get_fields();
    $fields = cge_array::to_hash($fields,'name');
    $fieldtype = $fields[$fieldname]['type'];
    $res = $fieldval;
    switch( $fieldtype )
      {
      case 'checkbox':
	if( !is_null($res) )
	  $res = $mod->Lang('prompt_'.$res);
	break;
      case 'textarea':
	$res = '';
	break;
      case 'dropdown':
      case 'image':
      case 'file':
	break;
      case 'dimensions':
	{
	  if( is_array($res) && $res['length'] > 0 && $res['width'] > 0 && $res['height'] > 0 )
	    {
	      $res = sprintf('%s: %d, %s: %d, %s: %d',
			     $mod->Lang('abbr_length'),$res['length'],
			     $mod->Lang('abbr_width'),$res['width'],
			     $mod->Lang('abbr_height'),$res['height']);
	    }
	  else
	    {
	      $res = $mod->Lang('none');
	    }
	}
	break;
      case 'subscription':
	{
	  if( is_array($res) && $res['payperiod'] != -1 && $res['delperiod'] != -1 )
	    {
	      $subscribe_opts = array();
	      $subscribe_opts[-1] = $mod->Lang('none');
	      $subscribe_opts['monthly'] = $mod->Lang('subscr_monthly');
	      $subscribe_opts['quarterly'] = $mod->Lang('subscr_quarterly');
	      $subscribe_opts['semianually'] = $mod->Lang('subscr_semianually');
	      $subscribe_opts['yearly'] = $mod->Lang('subscr_yearly');

	      $expire_opts = array();
	      $expire_opts[$mod->Lang('none')] = -1;
	      $expire_opts[$mod->Lang('expire_six_month')] = '6';
	      $expire_opts[$mod->Lang('expire_one_year')] = '12';
	      $expire_opts[$mod->Lang('expire_two_year')] = '24';
	      $expire_opts = array_flip($expire_opts);

	      $expiry = 'none';
	      if( $fieldval['expire'] != -1 )
		{
		  $expiry = $fieldval['expire'];
		}
	      $res = sprintf('%s: %s, %s: %s, %s: %s',
			     $mod->Lang('subscr_payperiod2'),$fieldval['payperiod'],
			     $mod->Lang('subscr_delperiod2'),$fieldval['delperiod'],
			     $mod->Lang('subscr_expiry2'),$expiry);
	    }
	  else
	    {
	      $res = $mod->Lang('none');
	    }
	}
	break;
      }

    return $res;
  }


  static public function hierarchy_get_tree($parent_id = -1,$showall = 0,$callback_fn = '')
  {
    global $gCms;
    $db = $gCms->GetDb();

    $where = array();
    $parms = array();

    $where[] = 'ph.parent_id = ?';
    $parms[] = $parent_id;
    if( $showall )
      {
	$where[] = 'pr.status = ?';
	$parms[] = 'published';
      }

    $query = 'SELECT ph.*,count(pr.id) AS count FROM '.cms_db_prefix().'module_products_hierarchy ph
                LEFT OUTER JOIN '.cms_db_prefix().'module_products_prodtohier pth
                  ON ph.id = pth.hierarchy_id
                LEFT OUTER JOIN '.cms_db_prefix().'module_products pr
                  ON pth.product_id = pr.id';
    if( count($where) )
      {
	$query .= ' WHERE '.implode(' AND ',$where);
      }
    $query .= ' GROUP BY ph.id ORDER BY ph.hierarchy';
    $data = $db->GetArray($query,$parms);

    if( is_array($data) )
      {
	for( $i = 0; $i < count($data); $i++ )
	  {
	    if( $callback_fn != '' && function_exists($callback_fn) )
	      {
		$callback_fn($data[$i]);
	      }

	    $tmp = self::hierarchy_get_tree($data[$i]['id'],$showall,$callback_fn);
	    if( is_array($tmp) && count($tmp) )
	      {
		$data[$i]['children'] = $tmp;
	      }
	  }

	return $data;
      }

    return FALSE;
  }

} // product opts

#
# EOF
#
?>