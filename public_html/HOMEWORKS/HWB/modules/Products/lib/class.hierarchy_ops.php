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

class hierarchy_ops
{
  public static function get_all_hierarchy_info()
  {
    if( cge_tmpdata::exists('products_allhierarchy') ) return;

    $db = cmsms()->GetDb();
    $query = 'SELECT * FROM '.cms_db_prefix().'module_products_hierarchy';
    $tmp = $db->GetAll($query);
    if( is_array($tmp) )
      {
	foreach( $tmp as $row )
	  {
	    $key = 'products_hierarchy_'.$row['id'];
	    cge_tmpdata::set($key,$row);
	  }
	cge_tmpdata::set('products_allhierarchy',1);
      }
  }

  public static function get_hierarchy_info($hierarchy_id,$load_all = false)
  {
    if( $load_all )
      {
	if( !cge_tmpdata::exists('products_allhierarchy') )
	  {
	    self::get_all_hierarchy_info();
	  }
      }

    // could do some caching here
    if( cge_tmpdata::exists('products_hierarchy_'.$hierarchy_id) )
      {
	return cge_tmpdata::get('products_hierarchy_'.$hierarchy_id);
      }
    $db = cmsms()->GetDb();
    $query = 'SELECT * FROM '.cms_db_prefix().'module_products_hierarchy
               WHERE id = ?';
    $row = $db->GetRow($query,array($hierarchy_id));
    if( $row )
      {
	cge_tmpdata::set('products_hierarchy_'.$hierarchy_id,$row);
      }
    return $row;
  }


  public static function get_hierarchy_path($hier_id)
  {
    $hier_info = self::get_hierarchy_info($hier_id);
    if( !$hier_info ) return FALSE;
  
    $tmp = explode('.',$hier_info['hierarchy']);
    if( !count($tmp) ) return FALSE;
  
    $breadcrumbs = array();
    foreach($tmp as $one)
      {
	$breadcrumbs[] = (int)$one;
      }
    return $breadcrumbs;
  }

} // end of class

#
# EOF
#
?>