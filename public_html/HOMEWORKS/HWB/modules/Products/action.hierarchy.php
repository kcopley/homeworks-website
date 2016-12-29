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
$config = $gCms->GetConfig();

$summarypage = $returnid;
if( isset($params['summarypage']) )
  {
    $summarypage = $this->resolve_alias_or_id($params['summarypage']);
    if( !$summarypage )
      {
	$summarypage = $returnid;
      }
    unset($params['summarypage']);
  }

if( !function_exists('products_byhierarchy_postprocess') )
  {
    function products_byhierarchy_postprocess(&$data,$params,$summarypage,$thispage)
    {
      if( is_array($data) )
	{
	  $module = cge_utils::get_module('Products');
	  $imgdir = cms_join_path($config['uploads_path'],$module->GetName(),'hierarchy');

	  for( $i = 0; $i < count($data); $i++ )
	    {
	      $rec =& $data[$i];

	      $tn = cms_join_path($imgdir,'thumb_'.$rec['image']);
	      if( file_exists($tn) )
		{
		  $rec['thumbnail'] = 'thumb_'.$rec['image'];
		}
	      
	      $tn = cms_join_path($imgdir,'preview_'.$rec['image']);
	      if( file_exists($tn) )
		{
		  $rec['preview'] = 'preview_'.$rec['image'];
		}

	      $parms = $params;
	      $parms['parent'] = $rec['id'];
	      $rec['down_url'] = $module->CreateURL($id,'hierarchy',$thispage,$parms);

	      $parms = $params;
	      $parms['hierarchyid'] = $rec['id'];
	      $rec['url'] = $module->CreatePrettyLink($id,'default',$summarypage,'',$parms,'',true);

	      if( isset($rec['children']) )
		{
		  products_byhierarchy_postprocess($rec['children'],$params,$summarypage,$thispage);
		}
	    }
	}
    }
  }


$nodes = array();
$parents = array(-1);
if( isset($params['parent'] ) )
  {
    $parents = explode(',',$params['parent']);
  }
else if( isset($params['hierarchy']) )
  {
    $tmp = explode(',',$params['hierarchy']);
    $tmp2 = array();
    foreach( $tmp as $one )
      {
	$tmp2[] = "'".trim($one)."'";
      }
    $tmp2 = implode(',',$tmp2);
    $query = 'SELECT id FROM '.cms_db_prefix().'module_products_hierarchy
               WHERE name IN ('.$tmp2.')';
    $nodes = $db->GetCol($query);
    $nodes = array_unique($nodes);
  }

if( !count($parents) )
  {
    // nothing found to start with
    return;
  }

$data = array();
if( count($nodes) )
  {
    $nodes = implode(',',$nodes);
    $query = 'SELECT ph.*,count(pr.id) AS count FROM '.cms_db_prefix().'module_products_hierarchy ph
                LEFT OUTER JOIN '.cms_db_prefix().'module_products_prodtohier pth
                  ON ph.id = pth.hierarchy_id
                LEFT OUTER JOIN '.cms_db_prefix().'module_products pr
                  ON pth.product_id = pr.id
               WHERE pr.status = \'published\'
                 AND ph.id IN ('.$nodes.')
               GROUP BY ph.id ORDER BY ph.hierarchy';
    $tmp = $db->GetArray($query);
    products_byhierarchy_postprocess($tmp,$params,$summarypage,$returnid);
    $data = $tmp;
  }
else
  {
    foreach( $parents as $parent_id )
      {
	$tmp = product_utils::hierarchy_get_tree($parent_id);
	if( is_array($tmp) && count($tmp) )
	  {
	    products_byhierarchy_postprocess($tmp,$params,$summarypage,$returnid);
	    $data[] = $tmp;
	  }
      }
  }

// if( !count($data) )
//   {
//     // nothing found in results
//     return;
//   }


$hierdata = '';
if( count($data) == 1 && empty($nodes) )
  {
    $data = $data[0];
    $query = 'SELECT * FROM '.cms_db_prefix().'module_products_hierarchy WHERE id = ?';
    $hierdata = $db->GetRow($query,array($data['id']));
  }
$smarty->assign('hierdata',$data);
if( $hierdata )
  {
    $smarty->assign('hierarchy_item',$hierdata);
  }
$smarty->assign('hierarchy_image_location',$config['uploads_url'].'/'.$this->GetName().'/hierarchy');

//
// template
//
$thetemplate = 'byhierarchy_'.$this->GetPreference(PRODUCTS_PREF_DFLTBYHIERARCHY_TEMPLATE);
if( isset($params['hierarchytemplate'] ) )
  {
    $thetemplate = 'byhierarchy_'.$params['hierarchytemplate'];
  }
echo $this->ProcessTemplateFromDatabase($thetemplate);
#
# EOF
#
?>
