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

$fieldefs = '';
{
  $tmp = $this->GetFieldDefs();
  if( is_array($tmp) )
    {
      $fielddefs = array();
      for( $i = 0; $i < count($tmp); $i++ )
	{
	  $obj = $tmp[$i];
	  $fielddefs[$obj->name] = $obj;
	}
    }
}

$sorttype = '';
$countjoins = array();
$joins = array();
$sortfield = 1;
unset($params['assign']);
$inline = false;
$default_detailpage = $this->GetPreference('detailpage','');
$detailpage = $default_detailpage;
if (isset($params['detailpage']))
  {
    $detailpage = trim($params['detailpage']);
  }
if( !empty($detailpage) )
  {
    $manager =& $gCms->GetHierarchyManager();
    $node =& $manager->sureGetNodeByAlias($detailpage);
    if (isset($node))
      {
	$content =& $node->GetContent();	
	if (isset($content))
	  {
	    $detailpage = $content->Id();
	  }
      }
    else
      {
	$node =& $manager->sureGetNodeById($detailpage);
	if (!isset($node))
	  {
	    $detailpage = '';
	  }
      }
    if( $detailpage != '' )
      {
	$params['cd_origpage'] = $returnid;
      }
  }

$thetemplate = 'summary_'.$this->GetPreference(PRODUCTS_PREF_DFLTSUMMARY_TEMPLATE);
if( isset($params['summarytemplate'] ) )
  {
    $thetemplate = 'summary_'.$params['summarytemplate'];
  }


$sortorder = $this->GetPreference('sortorder','asc');
if( isset( $params['sortorder'] ) )
  {
	switch( $params['sortorder'] )
	  {
	  case 'asc':
	  case 'desc':
		$sortorder = $params['sortorder'];
	  }
  }

$sortby = $this->GetPreference('sortby','product_name');
if( isset( $params['sortby'] ) )
  {
    $tmp = strtolower(trim($params['sortby']));
    switch( $tmp )
      {
      case 'id':
	$sortby = 'id';
	break;
	
      case 'product_name':
	$sortby = 'product_name';
	break;
      case 'price':
	$sortby = 'price';
	break;
      case 'created':
	$sortby = 'create_date';
	break;
      case 'modified':
	$sortby = 'modified_date';
	break;
      case 'status':
	$sortby = 'status';
	break;
      case 'weight':
	$sortby = 'weight';
	break;
      case 'random':
	$sortby = 'RAND()';
	$sortorder = '';
	break;
      default:
	if( startswith($tmp,'f:') )
	  {
	    $fieldname = substr($tmp,strlen('f:'));
	    if( isset($fielddefs[$fieldname]) )
	      {
		$fieldid = $fielddefs[$fieldname]->id;
		$as = 'FV'.$sortfield++;
		$joins[] = cms_db_prefix()."module_products_fieldvals {$as} ON c.id = {$as}.product_id AND $as.fielddef_id = '{$fieldid}'";
		$sortby = "{$as}.value";
	      }
	  }
	break;
      }
  }
if( $sortby == 'random' )
  {
    $sortby = 'RAND()';
    $sortorder = '';
  }

if( isset($params['sorttype']) )
  {
    $tmp = trim($params['sorttype']);
    $tmp = strtoupper($tmp);
    switch( $tmp )
      {
      case 'STRING':
	$sorttype = '';
	break;
      case 'SIGNED':
      case 'UNSIGNED':
	$sorttype = $tmp;
      }
  }
$limit = $this->GetPreference('summary_pagelimit',10000);
if( isset($params['pagelimit']) )
  {
    $limit = (int)$params['pagelimit'];
  }
$limit = max($limit,1);
$limit = min($limit,10000);

$page = 1;
if( isset($params['page']) )
  {
    $page = (int)$params['page'];
    if( $page < 1 ) $page = 1;
  }
$startelement = ($page-1)*$limit;

$category = '';
$inputcat = '';
if( isset( $params['category'] ) )
  {
    $category = trim($params['category']);
    $category = cms_html_entity_decode($category);
  }
else if (isset($params['categoryid']))
  {
    $categoryid = $params['categoryid'];
  }

$hierarchy = '';
if( isset( $params['hierarchy'] ) )
  {
    $hierarchy = trim($params['hierarchy']);
  }
$hierarchyid = -100;
if( isset($params['hierarchyid']) )
  {
    $hierarchyid = (int)$params['hierarchyid'];
  }

$fieldid = -100;
if( isset( $params['fieldid']) )
  {
    $fieldid = (int)$params['fieldid'];
  }
$fieldval = '';
if( isset( $params['fieldval'] ) )
  {
    $fieldval = trim($params['fieldval']);
  }


//
// Build the pretty urls
//

//
// Build the queries
//
$entryarray = array();
$paramarray = array();
$where = array();
$query = "SELECT c.* FROM ".cms_db_prefix()."module_products c";
$query2 = "SELECT count(*) as count FROM ".cms_db_prefix()."module_products c";
$where[] = 'c.status = \'published\'';
if ( isset($categoryid) && $categoryid != '')
{
  $str = " INNER JOIN ".cms_db_prefix()."module_products_product_categories cc ON cc.product_id = c.id";
  $query .= $str;
  $query2 .= $str;
  $where[] = 'cc.category_id = ?';
  $paramarray[] = $categoryid;
}
else if( isset($category) && $category != '' )
{
  $str = " INNER JOIN ".cms_db_prefix()."module_products_product_categories cc ON cc.product_id = c.id";
  $query .= $str;
  $query2 .= $str;
  $str = " INNER JOIN ".cms_db_prefix()."module_products_categories cs ON cs.id = cc.category_id";
  $query .= $str;
  $query2 .= $str;

  $arr1 = explode(',',$category);
  $arr2 = array();
  foreach( $arr1 as $xx )
	{
	  $arr2[] = "'".$xx."'";
	}
  $txt = implode(',',$arr2);
  $where[] = 'cs.name IN ('.$txt.')';
}

if ( isset($hierarchy) && $hierarchy != '' )
  {
    $str = " INNER JOIN ".cms_db_prefix()."module_products_prodtohier ph ON ph.product_id = c.id";
    $query .= $str;
    $query2 .= $str;

    $str = " INNER JOIN ".cms_db_prefix()."module_products_hierarchy h ON ph.hierarchy_id = h.id";
    $query .= $str;
    $query2 .= $str;

    $tmp2 = array();
    $tmp = explode(',',$hierarchy);
    foreach( $tmp as $one )
      {
	if( strstr($one,'*') !== FALSE )
	  {
	    $tmp2[] = "upper(h.long_name) LIKE upper(?)";
	  }
	else
	  {
	    $tmp2[] = "upper(h.name) = upper(?)";
	  }

	$one = cms_html_entity_decode($one);
	$one = trim(str_replace('*','%',str_replace('"','_',$one)));
	$paramarray[] = $one;
      }
    $str = '(' . implode(' OR ',$tmp2) . ')';
    $where[] = $str;
  }
else if( $hierarchyid > -100 )
  {
    $str = " INNER JOIN ".cms_db_prefix()."module_products_prodtohier ph ON ph.product_id = c.id";
    $query .= $str;
    $query2 .= $str;

    $where[] = "ph.hierarchy_id = ?";
    $paramarray[] = $hierarchyid;
  }

if( isset($fieldid) && $fieldid > 0 && isset($fieldval) && !empty($fieldval) )
  {
    // handle gathering products that have a certain field id.
    if( $fieldval == '::null::' )
      {
	// handle a case when a field is not set for a product.
	$countjoins[] = cms_db_prefix().'module_products_fieldvals FVA ON c.id = FVA.product_id AND FVA.fielddef_id = ?';
	$joins[] = cms_db_prefix().'module_products_fieldvals FVA ON c.id = FVA.product_id AND FVA.fielddef_id = ?';
	$where[] = '(FVA.value IS NULL)';
        array_unshift($paramarray,$fieldid);
	//$paramarray[] = $fieldid;
      }
    if( $fieldval == '::notnull::' )
      {
	// handle a case when a field is not set for a product.
	$countjoins[] = cms_db_prefix().'module_products_fieldvals FVA ON c.id = FVA.product_id AND FVA.fielddef_id = ?';
	$joins[] = cms_db_prefix().'module_products_fieldvals FVA ON c.id = FVA.product_id AND FVA.fielddef_id = ?';
	$where[] = '(FVA.value != \'\')';
        array_unshift($paramarray,$fieldid);
      }
    else
      {
	// limit results to all of the items that have this field value.
	$countjoins[] = cms_db_prefix().'module_products_fieldvals FVA ON c.id = FVA.product_id AND FVA.fielddef_id = ?';
	$joins[] = cms_db_prefix().'module_products_fieldvals FVA ON c.id = FVA.product_id AND FVA.fielddef_id = ?';
        $where[] = 'FVA.value = ?';
        array_unshift($paramarray,$fieldid);
	//$paramarray[] = $fieldid;
	$paramarray[] = $fieldval;
      }
  }


if( count($joins) )
  {
    $query .= ' LEFT JOIN '.implode(' LEFT JOIN ',$joins);
  }
if( count($countjoins) )
  {
    $query2 .= ' LEFT JOIN '.implode(' LEFT JOIN ',$countjoins);
  }
$query = $query . ' WHERE ' . implode(' AND ',$where );
$query2 = $query2 . ' WHERE ' . implode(' AND ',$where );
if( $sorttype == '' )
  {
    $query .= " ORDER BY ".$sortby." ".$sortorder;
  }
else
  {
    $query .= ' ORDER BY CAST('.$sortby.' AS '.$sorttype.') '.$sortorder;
  }



// Execute the Queries
$count = $db->GetOne($query2,$paramarray);
if( $count == 0 ) return;
$dbresult = $db->SelectLimit($query, $limit, $startelement, $paramarray);
if( !$dbresult ) 
  {
    echo $db->sql.'<br/>'; die( $db->ErrorMsg() );
  }

// Determine the number of pages
$npages = intval($count / $limit);
if( $count % $limit != 0 ) $npages++;

// build the object list
global $gCms;
$config = $gCms->GetConfig();
while ($dbresult && ($row = $dbresult->FetchRow()))
{
  $filedir = cms_join_path($config['uploads_path'],$this->GetName(),'product_'.$row['id']);
  $onerow = cge_array::to_object($row);
  $prettyurl = product_ops::pretty_url($row['id'],($detailpage!='')?$detailpage:$returnid);
  
  $parms = $params;
  $parms['productid'] = $row['id'];
  $onerow->detail_url = $this->CreateLink($id,'details',($detailpage!=''?$detailpage:$returnid),'',$parms,
					  '',true,$inline,'',false,$prettyurl);
  $onerow->product_name_link = $this->CreateLink($id, 'details', $returnid, $row['product_name'], $parms);
  $onerow->file_location = $config['uploads_url'].'/'.$this->GetName().'/product_'.$row['id'];
  $onerow->details = $row['details'];

  $onerow->hierarchy_id = product_ops::get_product_hierarchy_id($row['id']);
  $onerow->breadcrumb = product_ops::create_hierarchy_breadcrumb($id,$row['id'],$returnid);

  // add custom fields
  $fielddefs = $this->GetFieldDefsForProduct($row['id'],true,true);
  $fields = array();
  foreach( $fielddefs as $onedef )
    {
      if( $onedef->type == 'image' )
	{
	  if( isset($onedef->value) && file_exists(cms_join_path($filedir,'thumb_'.$onedef->value)) )
	    {
	      $onedef->thumbnail = 'thumb_'.$onedef->value;
	    }

	  if( isset($onedef->value) && file_exists(cms_join_path($filedir,'preview_'.$onedef->value)) )
	    {
	      $onedef->preview = 'preview_'.$onedef->value;
	    }
	}
      $fields[$onedef->name] = $onedef;
    }
  $onerow->fields = $fields;

  // add categories
  $catarray = $this->GetCategoriesForProduct($params['productid'],true,true);
  if( count($catarray) )
    {
      $catnamearray = array();
      foreach( $catarray as $onecat )
	{
	  $catnamearray[] = $onecat->name;
	}
      $onerow->categories = $catarray;
      $onerow->categorynames = $catnamearray;
    }

  // add attributes
  $tmp = $this->GetProductAttributeDataComplete($row['id']);
  if( is_array($tmp) )
    {
      $onerow->attributes = $tmp;
    }

  $entryarray[] = $onerow;
}


//
// Give everything to smarty
//
$smarty->assign('items', $entryarray);
$smarty->assign('totalcount',$count);
$smarty->assign('itemcount', count($entryarray));
$smarty->assign('pagetext',$this->Lang('page'));
$smarty->assign('oftext',$this->Lang('of'));
$smarty->assign('pagecount',$npages);
$smarty->assign('curpage',$page);
if( $page == 1 )
  {
    $smarty->assign('firstlink',$this->Lang('firstpage'));
    $smarty->assign('prevlink',$this->Lang('prevpage'));
  }
else
  {
    $parms = $params;
    $parms['page'] = 1;
    $smarty->assign('firstlink',$this->CreateLink($id,'default',$returnid,
						  $this->Lang('firstpage'),
						  $parms));
    $parms['page'] = $page - 1;
    $smarty->assign('prevlink',$this->CreateLink($id,'default',$returnid,
						  $this->Lang('prevpage'),
						  $parms));
  }
if( $page == $npages )
  {
    $smarty->assign('lastlink',$this->Lang('lastpage'));
    $smarty->assign('nextlink',$this->Lang('nextpage'));
  }
else
  {
    $parms = $params;
    $parms['page'] = $npages;
    $smarty->assign('lastlink',$this->CreateLink($id,'default',$returnid,
						  $this->Lang('lastpage'),
						  $parms));
    $parms['page'] = $page + 1;
    $smarty->assign('nextlink',$this->CreateLink($id,'default',$returnid,
						  $this->Lang('nextpage'),
						  $parms));
  }

if( isset( $params['selectcategory'] ) )
  {
	$query = "SELECT id, name FROM ".cms_db_prefix()."module_products_categories ORDER BY name ASC";
	$dbresult = $db->Execute($query);
	$catarray = array('(Select One)' => '');
	while ($dbresult && $row = $dbresult->FetchRow())
	  {
		$catarray[$row['name']] = $row['id'];
	  }
	$smarty->assign('catformstart', $this->CreateFrontendFormStart($id, $returnid));
	$smarty->assign('catdropdown', $this->CreateInputDropdown($id, 'categoryid', $catarray, -1, $categoryid, ''));
	$smarty->assign('catbutton', $this->CreateInputSubmit($id, 'inputsubmit', 'Submit'));
	$smarty->assign('catformend', $this->CreateFormEnd());
  }

$cartmod = $this->GetPreference('cartmodule','');
if( !empty($cartmod) )
  {
    $cmparams = $this->GetPreference('cartmoduleparams','');
    if( !empty($cmparams) )
      {
	$str = sprintf("{%s %s}",$cartmod,$cmparams);
	$smarty->assign('cart_module_tag',$str);
      }
  }
$smarty->assign('currency_symbol',product_ops::get_currency_symbol());
$smarty->assign('weight_units',product_ops::get_weight_units());

//
// Process the template
//
echo $this->ProcessTemplateFromDatabase($thetemplate);

#
# EOF
#
?>
