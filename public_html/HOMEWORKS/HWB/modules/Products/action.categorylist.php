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

$thetemplate = 'categorylist_'.$this->GetPreference(PRODUCTS_PREF_DFLTCATEGORYLIST_TEMPLATE);
if( isset($params['categorylisttemplate'] ) )
  {
    $thetemplate = 'categorylist_'.$params['categorylisttemplate'];
  }

$sortby = 'A.name';
$sortorder = 'asc';
$category_id = '';
$category = '';
if( isset($params['categoryid']) )
  {
    $category_id = (int)$params['categoryid'];
  }
else if( isset($params['category']) )
  {
    $category = trim($params['category']);
  }

$resultpage = $this->GetPreference('resultpage','');
if (isset($params['resultpage']))
  {
    $resultpage = trim($params['resultpage']);
  }
if( !empty($resultpage) )
  {
    $manager =& $gCms->GetHierarchyManager();
    $node =& $manager->sureGetNodeByAlias($resultpage);
    if (isset($node))
      {
	$content = $node->GetContent();	
	if (isset($content))
	  {
	    $resultpage = $content->Id();
	  }
      }
    else
      {
	$node = $manager->sureGetNodeById($resultpage);
	if (!isset($node))
	  {
	    $resultpage = '';
	  }
      }
  }

if( isset($params['sortby']) )
  {
    $tmp = trim(strtolower($params['sortby']));
    switch( $tmp )
      {
      case 'id':
      case 'name':
	$sortby = 'A.'.$tmp;
	break;
      }
  }
if( isset($params['sortorder']) )
  {
    $tmp = trim(strtolower($params['sortby']));
    switch( $tmp )
      {
      case 'asc':
      case 'desc':
	$sortorder = $tmp;
	break;
      }
  }

//$query = "SELECT b.*,count(product_id) AS count FROM ".cms_db_prefix()."module_products_product_categories a, ".cms_db_prefix()."module_products_categories b WHERE b.id = a.category_id GROUP BY b.id";
$query = "SELECT A.id,A.name,count(B.product_id) AS count FROM ".cms_db_prefix()."module_products_categories A LEFT OUTER JOIN ".cms_db_prefix()."module_products_product_categories B ON A.id = B.category_id";
$qparms = array();
if( $category_id )
  {
    $query .= " WHERE A.id = ?";
    $qparms[] = $category_id;
  }
else if( $category) 
  {
    $q2 = 'SELECT id FROM '.cms_db_prefix().'module_products_categories WHERE name IN (';
    $tmp = explode(',',$category);
    for( $i = 0; $i < count($tmp); $i++ )
      {
	$tmp[$i] = '"'.$tmp[$i].'"';
      }
    $q2 .= implode(',',$tmp).')';
    $ids = $db->GetCol($q2);
    if( is_array($ids) && count($ids) )
      {
	$query .= ' WHERE A.id IN ('.implode(',',$ids).')';
      }
  }
$query .= " GROUP BY A.id ORDER BY $sortby $sortorder";
$categories = $db->GetArray($query,$qparms);
if( !$categories )
  {
    return;
  }

$tmp = cge_array::extract_field($categories,'id');
$query2 = 'SELECT * FROM '.cms_db_prefix().'module_products_category_fields
            WHERE category_id IN ('.implode(',',$tmp).') ORDER BY category_id ASC, field_order ASC';
$tmp2 = $db->GetArray($query2);
$results = array();
for( $i = 0; $i < count($categories); $i++ )
  {
    $row =& $categories[$i];
    if( (!isset($params['showall']) || $params['showall'] < 1) && $row['count'] <= 0 )
      {
	continue;
      }

    $obj = new StdClass();
    foreach( $row as $k => $v )
      {
	$obj->$k = $v;
      }

    // extract all of the rows that have this category id
    $tmpa = array();
    for( $j = 0; $j < count($tmp2); $j++ )
      {
	if( $tmp2[$j]['category_id'] < $row['id'] ) continue;
	if( $tmp2[$j]['category_id'] > $row['id'] ) break;

	$tmpa[] = $tmp2[$j];
      }
    if( is_array($tmpa) && count($tmpa) > 0 )
      {
	$obj->fields = cge_array::to_hash($tmpa,'field_name');
      }

    $params['categoryid'] = $obj->id;
    if( !$category_id )
      {
	$obj->detail_url = $this->CreatePrettyLink($id,'categorylist',($resultpage!='')?$resultpage:$returnid,
						'',$params,'',true);
      }
    $params['categoryname'] = $obj->name;
    $obj->summary_url = $this->CreatePrettyLink($id,'default',($resuxltpage!='')?$resultpage:$returnid,
						'',$params,'',true);
    $results[] = $obj;
  }

$smarty->assign('categorylist',$results);

echo $this->ProcessTemplateFromDatabase($thetemplate);
// EOF
?>
