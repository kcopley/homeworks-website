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
$name_expr = '';
$desc_expr = '';
$price_expr = '';
$allany = 0;
$searchdata = array();
$where = array();
$qparms = array();
$joins = array();
$detailpage = $this->GetPreference('detailpage',$returnid);
$thetemplate = 'summary_'.$this->GetPreference(PRODUCTS_PREF_DFLTSUMMARY_TEMPLATE);
$destpage = $returnid;
$pagelimit = 1000;
$pagenum = 1;
$offset = 0;
$count = 0;
$entryarray = '';
$npages = 0;


//
// handle params
//
if( isset($params['summarytemplate']) )
  {
    $thetemplate = 'summary_'.$params['summarytemplate'];
  }
if( isset($params['cd_origpage']) )
  {
    $destpage = (int)$params['cd_origpage'];
  }
if( isset($params['pagelimit']) )
  {
    $pagelimit = (int)$params['pagelimit'];
    $pagelimit = max($pagelimit,1);
    $pagelimit = min(1000,$pagelimit);
  }
if( isset($params['pagenum']) )
  {
    $pagenum = (int)$params['pagenum'];
    $pagenum = max($pagenum,1);
    $pagenum = min(10000,$pagenum);
  }
if( isset($params['detailpage']) )
  {
    $str = trim($params['detailpage']);
    if( $str )
      {
	$str = $this->resolve_alias_or_id($str);
      }
    if( $str )
      {
	$detailpage = $str;
      }
  }



//
// handle form submission
//
if( isset($params['cd_cancel']) )
  {
    $this->RedirectContent($destpage);
  }

if( isset($params['sortorder']) )
  {
    $str = strtolower(trim($params['sortorder']));
    switch( $str )
      {
      case 'asc':
      case 'desc':
	$sortorder = $str;
	break;
      }
  }

if( isset($params['sortby']) )
  {
    $str = strtolower(trim($params['sortby']));
    switch( $str )
      {
      case 'id':
      case 'product_name':
      case 'price':
      case 'created':
      case 'modified':
      case 'status':
      case 'weight':
      case 'random':
	$sortby = $str;
	break;
      default:
	if( startswith( $str, 'f:' ) )
	  {
	    $fieldname = substr($str,strlen('f:'));
	    if( isset($fields[$fieldname]) )
	      {
		$fieldid = $fielddefs[$fieldname]->id;
		$as = 'FV'.$sortfield++;
		$joins[] = 'LEFT JOIN '.cms_db_prefix()."module_products_fieldvals {$as} ON c.id = {$as}.product_id AND $as.fielddef_id = '{$fieldid}'";
		$sortby = "{$as}.value";
	      }
	  }
      }
  }

if( isset($params['cd_prodname']) )
  {
    $name_expr = trim($params['cd_prodname']);
  }
if( isset($params['cd_proddesc']) )
  {
    $desc_expr = trim($params['cd_proddesc']);
  }
if( isset($params['cd_prodprice']) )
  {
    $price_expr = trim($params['cd_prodprice']);
    if( $price_expr == -1 ) $price_expr = '';
  }
if( isset($params['cd_allany']) )
  {
    $allany = (int)$params['cd_allany'];
  }
if( isset($params['cd_propvalue']) )
  {
    if( !is_array($params['cd_propvalue']) )
      {
	$params['cd_propvalue'] = unserialize($params['cd_propvalue']);
      }
    $tfields = array_keys($params['cd_propvalue']);
    foreach ($tfields as $one )
      {
	if( $params['cd_propvalue'][$one] != '-1' &&
	    !empty($params['cd_propvalue'][$one]) )
	  {
	    $searchdata[$one] = trim($params['cd_propvalue'][$one]);
	  }
      }
    $params['cd_propvalue'] = serialize($params['cd_propvalue']);
  }

// build the query
if( !empty($name_expr) )
  {
    $where[] = 'P.product_name REGEXP ?';
    $qparms[] = $name_expr;
  }
if( !empty($desc_expr) )
  {
    $where[] = 'P.details REGEXP ?';
    $qparms[] = $desc_expr;
  }
if( !empty($price_expr) )
  {
    list($low,$high) = explode(':',$price_expr,2);
    $low = (float)$low;
    $high = (float)$high;
    if( $low < $high )
      {
	$where[] = '(P.price BETWEEN ? AND ?)';
	$qparms[] = $low;
	$qparms[] = $high;
      }
  }
if( !empty($searchdata) )
  {
    $joins[] = 'LEFT JOIN '.cms_db_prefix().'module_products_fieldvals FV ON P.id = FV.product_id';
    foreach( $searchdata as $propname => $propval )
      {
	$where[] = '(FV.fielddef_id = ? AND FV.value REGEXP ?)';
	$qparms[] = $fields[$propname]->id;
	$qparms[] = $propval;
      }
  }
$where[] = 'P.status = ?';
$qparms[] = 'published';

$qu = 'SELECT P.* FROM '.cms_db_prefix().'module_products P';
$qc = 'SELECT count(P.id) FROM '.cms_db_prefix().'module_products P';
if( count($joins) )
  {
    foreach( $joins as $one )
      {
	$qu .= ' '.$one;
	$qc .= ' '.$one;
      }
  }
if( count($where) )
  {
    $expr = ' AND ';
    if( $allany )
      {
	$expr = ' OR ';
      }
    $qu .= ' WHERE '.implode($expr,$where);
    $qc .= ' WHERE '.implode($expr,$where);
  }

$count = $db->GetOne($qc,$qparms);
if( $count )
  {
    $npages = (int)($count / $pagelimit);
    if( $count % $pagelimit != 0 ) $npages++;

    // the following code is copied from the default action.
    $dbresult = $db->SelectLimit($qu,$pagelimit,$offset,$qparms);
    if( !$dbresult ) return FALSE;

    global $gCms;
    $config = $gCms->GetConfig();
    $parms = array();
    if( isset($params['detailtemplate']) )
      {
	$parms['detailtemplate'] = $params['detailtemplate'];
      }
    while ($dbresult && ($row = $dbresult->FetchRow()))
      {
	$filedir = cms_join_path($config['uploads_path'],$this->GetName(),'product_'.$row['id']);
	$onerow = cge_array::to_object($row);
	$prettyurl = product_ops::pretty_url($row['id'],($detailpage!='')?$detailpage:$returnid);
  
	$parms['productid'] = $row['id'];
	$onerow->detail_url = $this->CreateLink($id,'details',($detailpage!=''?$detailpage:$returnid),'',
						$parms,
						'',true,$inline,'',false,$prettyurl);

	$onerow->product_name_link = $this->CreateLink($id, 'details', $returnid, 
						       $row['product_name'], 
						       $parms);
	$onerow->file_location = $config['uploads_url'].'/'.$this->GetName().'/product_'.$row['id'];
	$onerow->details = $row['details'];

	// hierarchy information
	$onerow->hierarchy_id = product_ops::get_product_hierarchy_id($params['productid']);
	$onerow->breadcrumb = product_ops::create_hierarchy_breadcrumb($id,$params['productid'],$returnid);

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
	$catarray = $this->GetCategoriesForProduct($parms['productid'],true,true);
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
  }

//
// Give everything to smarty
//
if( $count )
  {
    $smarty->assign('items', $entryarray);
    $smarty->assign('itemcount', count($entryarray));
  }
$smarty->assign('totalcount',$count);
$smarty->assign('pagetext',$this->Lang('page'));
$smarty->assign('oftext',$this->Lang('of'));
$smarty->assign('pagecount',$npages);
$smarty->assign('curpage',$pagenum);
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