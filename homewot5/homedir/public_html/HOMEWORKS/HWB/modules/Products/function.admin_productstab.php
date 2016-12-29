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
// Setup
//
$pagelimits = array('2'=>2,'5'=>5,'25'=>25,'100'=>100,'500'=>500);
$sortitems = array();
$sortitems[$this->Lang('productname')] = 'product_name';
$sortitems[$this->Lang('price')] = 'price';
$sortitems[$this->Lang('weight')] = 'weight';
$sortitems[$this->Lang('sku')] = 'sku';
$sortitems[$this->Lang('createddate')] = 'create_date';
$sortitems[$this->Lang('modifieddate')] = 'modified_date';
$sortorders = array();
$sortorders[$this->Lang('ascending')] = 'asc';
$sortorders[$this->Lang('descending')] = 'desc';
$uid = get_userid(false);
$hierarchy = '';
$children  = 0;
$pagelimit = 25;
$sortby    = 'create_date';
$sortorder = 'desc';
$pagenumber = 1;
$fields_viewable = array();
$field_names = array();

//
// Get preferences
//
$hierarchy  = get_preference($uid,'products_sel_hierarchy','');
$children   = get_preference($uid,'products_sel_children',0);
$pagelimit  = get_preference($uid,'products_sel_pagelimit',25);
$sortby     = get_preference($uid,'products_sel_sortby','create_date');
$sortorder  = get_preference($uid,'products_sel_sortorder','desc');
$categories = '';
$tmp= get_preference($uid,'products_sel_categories','');
if( !empty($tmp) ) $categories = explode(',',$tmp);
$custom_fields = '';
$tmp = get_preference('uid','products_sel_customfields','');
if( !empty($tmp) ) $custom_fields = explode(',',$tmp);

//
// Handle Get parameters
//
if( isset($params['pagenumber']) )
  {
    $pagenumber = (int)$params['pagenumber'];
  }


//
// Handle form submit
//
if( isset($params['submit']) )
  {
    $hierarchy = trim($params['input_hierarchy']);
    $children = (int)$params['input_children'];
    $pagelimit = (int)$params['input_pagelimit'];
    $sortby = trim($params['input_sortby']);
    $sortorder = trim($params['input_sortorder']);
    if( isset($params['custom_fields']) )
      {
	$custom_fields = $params['custom_fields'];
      }
    else
      {
	$custom_fields = array();
      }
    if( isset($params['categories']) )
      {
	$categories = $params['categories'];
      }
    else
      {
	$categories = array();
      }

    // store them as user preferences
    set_preference($uid,'products_sel_hierarchy',$hierarchy);
    set_preference($uid,'products_sel_children',$children);
    set_preference($uid,'products_sel_pagelimit',$pagelimit);
    set_preference($uid,'products_sel_sortby',$sortby);
    set_preference($uid,'products_sel_sortorder',$sortorder);
    set_preference($uid,'products_sel_customfields',implode(',',$custom_fields));
    set_preference($uid,'products_sel_categories',implode(',',$categories));
  }

//
// Begin the form
//
{
  $tmp = $this->GetCategories();
  if( is_array($tmp) )
    {
      $category_list = array();
      for( $i = 0; $i < count($tmp); $i++ )
	{
	  $category_list[$tmp[$i]->id] = $tmp[$i]->name;
	}
      $smarty->assign('category_list',$category_list);
    }
}

$fielddefs = $this->GetFieldDefs();
foreach( $fielddefs as $onedef )
{
  switch( $onedef->type )
    {
    case 'textbox':
    case 'dropdown':
      $sortitems[$onedef->prompt] = '-F-'.$onedef->id;
      break;
    }
}

$all_fields = product_ops::get_fields();
if( is_array($all_fields) )
  {
    for( $i = 0; $i < count($all_fields); $i++ )
      {
	switch( $all_fields[$i]['type'] )
	  {
	  case 'textarea':
	    break;
	  default:
	    $fields_viewable[$all_fields[$i]['id']] = $all_fields[$i]['prompt'];
	    $field_names[$all_fields[$i]['id']] = $all_fields[$i]['name'];
	    break;
	  }
      }
    if( count($fields_viewable) && is_array($custom_fields) )
      {
	// now trim down the custom fields
	// to make sure that something hasn't been deleted.
	$tmp = array();
	foreach( $custom_fields as $fid )
	  {
	    if( in_array($fid,array_keys($fields_viewable)) )
	      {
		$tmp[] = $fid;
	      }
	  }
	$custom_fields = $tmp;
      }
    else
      {
	$custom_fields = array();
      }
  }
$all_fields = cge_array::to_hash($all_fields,'name');
if( count($fields_viewable) )
  {
    $smarty->assign('fields_viewable',$fields_viewable);
    $smarty->assign('field_names',$field_names);
  }
if( count($categories) )
  {
    $smarty->assign('categories',$categories);
  }
$smarty->assign('custom_fields',$custom_fields);
$smarty->assign('formstart',$this->CGCreateFormStart($id,'defaultadmin'));
$smarty->assign('formend',$this->CreateFormEnd());
$smarty->assign('input_hierarchy',$this->CreateHierarchyDropdown($id,'input_hierarchy',$hierarchy));
$smarty->assign('input_children',$this->CreateInputYesNoDropdown($id,'input_children',$children));
$smarty->assign('input_sortby',$this->CreateInputDropdown($id,'input_sortby',$sortitems,-1,$sortby));
$smarty->assign('input_sortorder',$this->CreateInputDropdown($id,'input_sortorder',$sortorders,-1,$sortorder));
$smarty->assign('input_pagelimit',$this->CreateInputDropdown($id,'input_pagelimit',$pagelimits,-1,$pagelimit));


//
// Build the query
//
$fields = array();
$fields[] = 'p.*';
$prefix1 = "SELECT <FIELDS> FROM ".cms_db_prefix()."module_products p ";
$prefix2 = "SELECT count(p.id) AS count FROM ".cms_db_prefix().'module_products p ';
$where = array();
$joins = array();
$qparms = array();
if( is_array($custom_fields) && count($custom_fields) && count($fields_viewable) )
  {
    for( $j = 0; $j < count($custom_fields); $j++ )
      {
	$fid = $custom_fields[$j];
	$fields[] = "Fv{$j}.value AS 'Fld__{$field_names[$fid]}'";
	$joins[] = 'LEFT OUTER JOIN '.cms_db_prefix()."module_products_fieldvals Fv{$j}
                    ON Fv{$j}.product_id = p.id AND Fv{$j}.fielddef_id = ?";
	$qparms[] = $custom_fields[$j];
      }
  }
if( !empty($hierarchy) )
  {
    $tquery = 'SELECT long_name FROM '.cms_db_prefix().'module_products_hierarchy
               WHERE id = ?';
    $long_name = $db->GetOne($tquery,array($hierarchy));

    $joins[] = 'LEFT JOIN '.cms_db_prefix().'module_products_prodtohier ph 
                  ON ph.product_id = p.id';
    $joins[] = 'LEFT JOIN '.cms_db_prefix().'module_products_hierarchy h
                  ON h.id = ph.hierarchy_id';
    $where[] = 'h.long_name LIKE ?';
    if( $children )
      {
	$qparms[] = $long_name.'%';
      }
    else
      {
	$qparms[] = $long_name;
      }
  }
if( is_array($categories) && count($categories) > 0 )
  {
    $joins[] = 'LEFT JOIN '.cms_db_prefix().'module_products_product_categories pc
                  ON p.id = pc.product_id';
    $where[] = 'pc.category_id IN ('.implode(',',$categories).')';
  }

// handle funky custom field sort orders
if( startswith($sortby,'-F-') )
  {
    $fid = (int)substr($sortby,3);
    $joins[] = 'LEFT JOIN '.cms_db_prefix().'module_products_fieldvals fv
                  ON fv.product_id = p.id';
    $where[] = 'fv.fielddef_id = ?';
    $qparms[] = $fid;
    $order = " ORDER BY fv.value $sortorder";
  }
else
  {
    $order = " ORDER BY $sortby $sortorder";
  }

$str = implode(' ',$joins);
if( count($where) )
  {
    $str .= ' WHERE ' . implode(' AND ',$where) . $order;
  }
else
  {
    $str .= $order;
  }
$query1 = str_replace('<FIELDS>',implode(',',$fields),$prefix1) . $str;
$query2 = $prefix2 . $str;

//
// Setup start element, and count pages
//
$totalcount = $db->GetOne($query2,$qparms);
$pagecount = (int)($totalcount/$pagelimit);
if( ($totalcount % $pagelimit) != 0 ) $pagecount++;
$startelement = ($pagenumber-1) * $pagelimit;

//
// Begin the output
//
$smarty->assign('pagenumber',$pagenumber);
$smarty->assign('pagecount',$pagecount);
$smarty->assign('totalrows',$totalcount);
if( $pagenumber > 1 )
  {
    $parms = array('pagenumber'=>1);
    $smarty->assign('firstpage_url',
		    $this->CreateURL($id,'defaultadmin','',$parms));
    $parms = array('pagenumber'=>$pagenumber -1);
    $smarty->assign('prevpage_url',
		    $this->CreateURL($id,'defaultadmin','',$parms));
  }
if( $pagenumber < $pagecount )
  {
    $parms = array('pagenumber'=>$pagenumber + 1);
    $smarty->assign('nextpage_url',
		    $this->CreateURL($id,'defaultadmin','',$parms));
    $parms = array('pagenumber'=>$pagecount);
    $smarty->assign('lastpage_url',
		    $this->CreateURL($id,'defaultadmin','',$parms));
  }
$entryarray = array();	
$dbresult = $db->SelectLimit($query1,$pagelimit,$startelement,$qparms);
	
while ($dbresult && $row = $dbresult->FetchRow())
  {
    foreach( $row as $key => $value )
      {
	if( startswith($key,'Fld__') )
	  {
	    unset($row[$key]);
	    $key = substr($key,strlen('Fld__'));
	    $row[$key] = $value;

	    $tmp = @unserialize($value);
	    if( $tmp !== FALSE )
	      {
		$row[$key] = product_utils::get_displayable_fieldval($key,$tmp);
	      }
	    else
	      {
		$row[$key] = product_utils::get_displayable_fieldval($key,$value);
	      }
	  }
      }
    $onerow = cge_array::to_object($row);

    $onerow->edit_url = $this->CreateURL($id,'editproduct',$returnid,
					 array('compid'=>$row['id']));
    $onerow->attribslink = $this->CreateImageLink($id,'edit_attribsets',$returnid,
						  $this->Lang('edit_attribsets'),
						  'table_relationship.png',
						  array('compid'=>$row['id']));

    $onerow->editlink = $this->CreateLink($id, 'editproduct', $returnid, $gCms->variables['admintheme']->DisplayImage('icons/system/edit.gif', $this->Lang('edit'),'','','systemicon'), array('compid'=>$row['id']));
		
    $onerow->deletelink = $this->CreateLink($id, 'deleteproduct', $returnid, $gCms->variables['admintheme']->DisplayImage('icons/system/delete.gif', $this->Lang('delete'),'','','systemicon'), array('compid'=>$row['id']), $this->Lang('areyousure_deleteproduct'));
		
    $entryarray[] = $onerow;
  }
	
$this->smarty->assign_by_ref('items', $entryarray);
$this->smarty->assign('itemcount', count($entryarray));
	
$smarty->assign('importlink',
		$this->CreateImageLink($id,'importproducts',$returnid,
				       $this->Lang('import_from_csv'),
				       'icons/system/import.gif',array(),'','',false));
$this->smarty->assign('addlink', 
		      $this->CreateLink($id, 'addproduct', $returnid, 
					$gCms->variables['admintheme']->DisplayImage('icons/system/newfolder.gif', $this->Lang('addproduct'),'','','systemicon'), 
					array('hierarchy'=>$hierarchy), '', false, false, '') .' '. 
		      $this->CreateLink($id, 'addproduct', $returnid, $this->Lang('addproduct'), 
					array('hierarchy'=>$hierarchy), '', false, false, 'class="pageoptions"'));

$this->smarty->assign('idtext',$this->Lang('id'));
$this->smarty->assign('producttext', $this->Lang('product'));
$this->smarty->assign('pricetext', $this->Lang('price'));
$smarty->assign('weight_units',product_ops::get_weight_units());
$smarty->assign('weighttext',$this->Lang('weight'));
	
#Display template
echo $this->ProcessTemplate('productlist.tpl');

// EOF
?>
