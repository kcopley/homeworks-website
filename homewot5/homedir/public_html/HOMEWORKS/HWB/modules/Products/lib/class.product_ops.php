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


class product_ops
{
  static private $_fielddefs;

  static private function &_get_db()
  {
    global $gCms;
    $db =& $gCms->GetDb();
    return $db;
  }


  static private function &_get_module()
  {
    global $gCms;
    $res = null;
    if( isset($gCms->modules['Products']['object']) )
      {
	$res = $gCms->modules['Products']['object'];
      }
    return $res;
  }


  static public function is_valid_category($name)
  {
    $db =& self::_get_db();
    $query = 'SELECT id FROM '.cms_db_prefix().'module_products_categories
               WHERE name = ?';
    $tmp = $db->GetOne($query,array($name));
    if( $tmp ) return TRUE;
    return FALSE;
  }


  public static function is_valid_product_id($pid)
  {
    $db =& self::_get_db();
    $query = 'SELECT id FROM '.cms_db_prefix().'module_products 
               WHERE id = ?';
    $tmp = $db->Execute($query,array($pid));
    if( $tmp ) return TRUE;
    return FALSE;
  }


  public static function is_valid_hierarchy($str)
  {
    // accept string of "name.name.name.name" and 
    // convert into "name | name | name | name";
    $tmp = explode('.',$str);
    $tmp2 = array();
    foreach( $tmp as $one )
      {
	$tmp2[] = trim(trim($one,'"'));
      }
    $tmp3 = implode(' | ',$tmp2);

    $db =& self::_get_db();
    $query = 'SELECT id FROM '.cms_db_prefix().'module_products_hierarchy
               WHERE long_name = ?';
    $tmp = $db->GetOne($query,array($tmp3));
    if( !$tmp ) return FALSE;
    return TRUE;
  }


  public static function check_sku_used($sku,$productid = '',$productonly = false)
  {
    $db =& self::_get_db();
    $query = 'SELECT id FROM '.cms_db_prefix().'module_products
               WHERE sku = ?';
    $parms = array($sku);
    if( !empty($productid) )
      {
	$query .= ' AND id != ?';
	$parms[] = $productid;
      }
    $tmp = $db->GetOne($query,$parms);
    if( $tmp ) return TRUE;

    if( !$productonly )
      {
	$query = 'SELECT id FROM '.cms_db_prefix().'module_products_attributes
                   WHERE sku = ?';
	$tmp = $db->GetOne($query,array($sku));
	if( $tmp ) return TRUE;
      }

    return FALSE;
  }


  static public function check_alias_used($alias,$productid = '')
  {
    global $gCms;
    $db = $gCms->GetDb();

    $parms = array();
    $parms[] = $alias;
    $query = 'SELECT id FROM '.cms_db_prefix().'module_products
               WHERE alias = ?';
    if( !empty($productid) )
      {
	$query .= 'AND id != ?';
	$parms[] = (int)$productid;
      }
    $tmp = $db->GetOne($query,$parms);
    if( !$tmp ) return FALSE;
    return TRUE;
  }


  static public function generate_alias($product_name)
  {
    $str = munge_string_to_url($product_name);
    $postfix = '';
    
    while( $postfix < 1000 )
      {
	$alias = $str.$postfix;
	if( !self::check_alias_used($alias) ) return $alias;
	if( $postfix == '' ) $postfix = 1;
	$postfix++;
      }

    return FALSE;
  }


  static public function pretty_url($pid,$returnid = '')
  {
    $module = self::_get_module();
    $product = $module->GetProduct($pid);
    if( !$product ) return;
    $db = $module->GetDB();
    
    $usereturnid = true;    
    if( $returnid == -1 )
      {
	global $gCms;
	$contentops = $gCms->GetContentOperations();
	$returnid = $contentops->GetDefaultContent();
      }
    $dfltreturnid = $module->GetPreference('detailpage',-1);
    if( $dfltreturnid == $returnid || $returnid == '' )
      {
	$usereturnid = false;
	$returnid = $dfltreturnid;
      }

    // get the hierarchy id
    $query = 'SELECT hierarchy_id FROM '.cms_db_prefix().'module_products_prodtohier 
               WHERE product_id = ?';
    $hier_id = $db->GetOne($query,array($pid));
    
    $pretty_url = $module->GetPreference('urlprefix',$module->GetName());
    $done = false;
    if( $module->GetPreference('usehierpathurls',0) && !empty($product['alias']) && ($hier_id > 0) )
      {
	$tmp = hierarchy_ops::get_hierarchy_info($hier_id);
	if( $tmp )
	  {
	    $tmp2 = explode(' | ',$tmp['long_name']);
	    for( $i = 0; $i < count($tmp2); $i++ )
	      {
		$tmp2[$i] = munge_string_to_url($tmp2[$i]);
	      }
	    $path = implode('/',$tmp2);

	    if( $usereturnid )
	      {
		$pretty_url .= "/$returnid";
	      }

	    if( !empty($path) )
	      {
		$pretty_url .= "/details/$path";
	      }

	    $pretty_url .= "/".$product['alias'];
	    $done = true;
	  }
      }

    if( !$done )
      {
	$pretty_url .= "/$pid";
	if( $usereturnid )
	  {
	    $pretty_url .= "/$returnid";
	  }
	$alias = $product['alias'];
	if( empty($alias) )
	  {
	    $alias = $module->make_alias($product['product_name']);
	  }
	$pretty_url .= "/$alias";
      }
    return $pretty_url;
  }


  public static function get_product_hierarchy_id($productid)
  {
    $db = cmsms()->GetDb();
  
    $query = 'SELECT hierarchy_id FROM '.cms_db_prefix().'module_products_prodtohier
              WHERE product_id = ? LIMIT 1';
    $hier_id = $db->GetOne($query,array($productid));
    return $hier_id;
  }


  public static function get_product_hierarchy_path($productid)
  {
    $hier_id = self::get_product_hierarchy_id($productid);
    if( $hier_id )
      return hierarchy_ops::get_hierarchy_path($hier_id);
  }


  public static function create_hierarchy_breadcrumb($id,$product_id, $hierpage, $delim = ' &gt; ')
  {
    $module = self::_get_module();
    $tmp = array();
    $hierarchy_path = self::get_product_hierarchy_path($product_id);
    if( !$hierarchy_path ) return false;
    foreach( $hierarchy_path as $one )
      {
	$info = hierarchy_ops::get_hierarchy_info($one);
	$link = $module->CreatePrettyLink($id,'hierarchy',$hierpage,
					  $info['name'],array('parent'=>$info['id']));
	$tmp[] = $link;
      }
    return implode($delim,$tmp);
  }


  public static function get_search_result($returnid, $productid, $attr = '')
  {
    $result = array();
    $mod = self::_get_module();
    
    if ($attr != 'product')
      {
	return $result;
      }

    if( $mod->GetPreference('use_detailpage_for_search',0) )
      {
	$returnid = '';
      }

    $db =& $mod->GetDb();
    $q = "SELECT product_name FROM ".cms_db_prefix()."module_products WHERE
			      id = ?";
    $dbresult = $db->Execute( $q, array( $productid ) );
    if ($dbresult)
      {
	$row = $dbresult->FetchRow();
	
	//0 position is the prefix displayed in the list results.
	$result[0] = $mod->GetFriendlyName();
	
	//1 position is the title
	$result[1] = $row['product_name'];
	
	//2 position is the URL to the title.
	$prettyurl = self::pretty_url($productid,$returnid);
	$result[2] = $mod->CreateLink('cntnt01', 'details', $returnid, '', array('productid' => $productid) ,
				      '', true, false, '', true, $prettyurl);
      }
    
    return $result;
  }
	

  public static function get_currency_symbol()
  {
    if( class_exists('cg_ecomm') )
      {
	return cg_ecomm::get_currency_symbol();
      }
    $mod = self::_get_module();
    return $mod->GetPreference('products_currencysymbol','$');
  }


  public static function get_weight_units()
  {
    if( class_exists('cg_ecomm') )
      {
	return cg_ecomm::get_weight_units();
      }
    $mod = self::_get_module();
    return $mod->GetPreference('products_weightunits','kg');
  }

  
  public static function get_length_units()
  {
    if( class_exists('cg_ecomm') )
      {
	return cg_ecomm::get_length_units();
      }
    $mod = self::_get_module();
    return $mod->GetPreference('products_lengthunits','kg');
  }

  
  public static function get_fields()
  {
    if( !is_array(self::$_fielddefs) )
      {
	global $gCms;
	$db = $gCms->GetDb();
	
	$query = 'SELECT * FROM '.cms_db_prefix().'module_products_fielddefs';
	self::$_fielddefs = $db->GetArray($query);
      }
    return self::$_fielddefs;
  }


  public static function get_field_options($type = '')
  {
    $tmp = self::get_fields();
    if( !is_array($tmp) ) return;

    $result = array();
    for( $i = 0; $i < count($tmp); $i++ )
      {
	if( $type == '' || $tmp[$i]['type'] == $type )
	  {
	    $result[$tmp[$i]['id']] = $tmp[$i]['prompt'];
	  }
      }
    return $result;
  }

  
} // class


#
# EOF
#
?>