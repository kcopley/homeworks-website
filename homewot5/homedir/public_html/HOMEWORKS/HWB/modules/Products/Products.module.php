<?php  /* -*- Mode: PHP; tab-width: 4; c-basic-offset: 2 -*- */
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

$cgextensions = cms_join_path($gCms->config['root_path'],'modules',
			      'CGExtensions','CGExtensions.module.php');
if( !is_readable( $cgextensions ) )
{
  echo '<h1><font color="red">ERROR: The CGExtensions module could not be found.</font></h1>';
  return;
}
require_once($cgextensions);

define('PRODUCTS_PREF_NEWSUMMARY_TEMPLATE','products_pref_newsummary_template');
define('PRODUCTS_PREF_DFLTSUMMARY_TEMPLATE','products_pref_dfltsummary_template');
define('PRODUCTS_PREF_NEWDETAIL_TEMPLATE','products_pref_newdetail_template');
define('PRODUCTS_PREF_DFLTDETAIL_TEMPLATE','products_pref_dfltdetail_template');
define('PRODUCTS_PREF_NEWCATEGORYLIST_TEMPLATE','products_pref_newcategorylist_template');
define('PRODUCTS_PREF_DFLTCATEGORYLIST_TEMPLATE','products_pref_dfltcategorylist_template');
define('PRODUCTS_PREF_NEWBYHIERARCHY_TEMPLATE','products_pref_newbyhierarchy_template');
define('PRODUCTS_PREF_DFLTBYHIERARCHY_TEMPLATE','products_pref_dfltbyhierarchy_template');
define('PRODUCTS_PREF_NEWSEARCH_TEMPLATE','products_pref_newsearch_template');
define('PRODUCTS_PREF_DFLTSEARCH_TEMPLATE','products_pref_dfltsearch_template');

class Products extends CGExtensions
{
  var $_product_cache;
  var $_hierarchy_cache;
  var $_category_cache;
  var $_admin_loaded;

  public function __construct()
  {
    parent::__construct();
	$this->_product_cache = array();
	$this->_hierarchy_cache = array();
	$this->_category_cache = array();
	$this->_admin_loaded = false;

	$this->AddImageDir('icons');

    $smarty = cmsms()->GetSmarty();
	$smarty->register_function('products_getcategory',
							   array($this,'_smarty_products_getcategory'));
  }

  function GetName()
  {
	return 'Products';
  }

  function GetFriendlyName()
  {
	return $this->Lang('product_manager');
  }

  function GetDependencies()
  {
	return array('CGExtensions'=>'1.21',
		     'CGSimpleSmarty'=>'1.4.4');
  }

  function AllowAutoInstall()
  {
	return FALSE;
  }

  function AllowAutoUpgrade()
  {
	return FALSE;
  }

  function IsPluginModule()
  {
	return true;
  }

  function HasAdmin()
  {
	return true;
  }

  function GetVersion()
  {
	return '2.9.2';
  }

  function MinimumCMSVersion()
  {
	return '1.8.2';
  }

  function GetAdminDescription()
  {
	return $this->Lang('module_description');
  }

  function VisibleToAdminUser()
  {
	return $this->CheckPermission('Modify Products') ||
	  $this->CheckPermission('Modify Templates') ||
	  $this->CheckPermission('Modify Site Preferences');
  }

  /*---------------------------------------------------------
   GetHeaderHTML()
   ---------------------------------------------------------*/
  function GetHeaderHTML()
  {
	$obj = cge_utils::get_module('JQueryTools');
    if( is_object($obj) )
      {
$tmpl = <<<EOT
{JQueryTools action='incjs' exclude='form'}
{JQueryTools action='ready'}
EOT;
        return $this->ProcessTemplateFromData($tmpl);
      }
  }	


  function SetParameters()
  {
	$this->RegisterModulePlugin();
	$this->RestrictUnknownParams();

	$this->CreateParameter('action','default',$this->Lang('param_action'));

	$this->CreateParameter('productid','',$this->Lang('param_productid'));
	$this->SetParameterType('productid',CLEAN_INT);

	$this->CreateParameter('detailpage','',$this->Lang('param_detailpage'));
	$this->SetParameterType('detailpage',CLEAN_STRING);

	$this->CreateParameter('categorylisttemplate','',$this->Lang('param_categorylisttemplate'));
	$this->SetParameterType('categorylisttemplate',CLEAN_STRING);
	$this->CreateParameter('categorylistdtltemplate','',$this->Lang('param_categorylistdtltemplate'));
	$this->SetParameterType('categorylistdtltemplate',CLEAN_STRING);

	$this->CreateParameter('detailtemplate','',$this->Lang('param_detailtemplate'));
	$this->SetParameterType('detailtemplate',CLEAN_STRING);

	$this->CreateParameter('summarytemplate','',$this->Lang('param_summarytemplate'));
	$this->SetParameterType('summarytemplate',CLEAN_STRING);

	$this->CreateParameter('hierarchytemplate','',$this->Lang('param_hierarchytemplate'));
	$this->SetParameterType('hierarchytemplate',CLEAN_STRING);

	$this->CreateParameter('sortby','product_name',$this->Lang('param_sortby'));
	$this->SetParameterType('sortby',CLEAN_STRING);
	$this->CreateParameter('sortorder','asc',$this->Lang('param_sortorder'));
	$this->SetParameterType('sortorder',CLEAN_STRING);
	$this->CreateParameter('sorttype','',$this->Lang('param_sorttype'));
	$this->SetParameterType('sorttype',CLEAN_STRING);
// 	$this->CreateParameter('selectcategory',0,$this->Lang('param_selectcategory'));
// 	$this->SetParameterType('selectcategory',CLEAN_INT);
	$this->CreateParameter('category','',$this->Lang('param_category'));
	$this->SetParameterType('category',CLEAN_STRING);
	$this->SetParameterType('categoryname',CLEAN_STRING);
	$this->CreateParameter('hierarchy','',$this->Lang('param_hierarchy'));
	$this->SetParameterType('hierarchy',CLEAN_STRING);
	$this->CreateParameter('pagelimit','',$this->Lang('param_pagelimit'));
	$this->SetParameterType('pagelimit',CLEAN_INT);
	$this->CreateParameter('parent','',$this->Lang('param_parent'));
	$this->SetParameterType('parent',CLEAN_INT);
	$this->CreateParameter('showall','',$this->Lang('param_showall'));
	$this->SetParameterType('showall',CLEAN_INT);
	$this->CreateParameter('field','',$this->Lang('param_field'));
	$this->SetParameterType('field',CLEAN_STRING);
	$this->SetParameterType('fieldid',CLEAN_INT);
	$this->SetParameterType('fieldval',CLEAN_STRING);
	$this->CreateParameter('fieldval','',$this->Lang('param_fieldval'));

	$this->CreateParameter('inline',0,$this->Lang('param_inline'));
	$this->SetParameterType('inline',CLEAN_INT);
	$this->CreateParameter('resultpage','',$this->Lang('param_resultpage'));
	$this->SetParameterType('resultpage',CLEAN_STRING);
	$this->CreateParameter('searchformtemplate','',$this->Lang('param_searchformtemplate'));
	$this->SetParameterType('searchformtemplate',CLEAN_STRING);
	$this->CreateParameter('searchfield','',$this->Lang('param_searchfield'));
	$this->SetParameterType('searchfield',CLEAN_STRING);

	$this->CreateParameter('summarypage',$this->Lang('param_summarypage'));
	$this->SetParameterType('summarypage',CLEAN_STRING);

	$this->SetParameterType('junk',CLEAN_STRING);
	$this->SetParameterType('page',CLEAN_INT);
	$this->SetParameterType('alias',CLEAN_STRING);
	$this->SetParameterType('hierarchyid',CLEAN_INT);
	$this->SetParameterType('categoryid',CLEAN_INT);
	$this->SetParameterType(CLEAN_REGEXP.'/cd_.*/',CLEAN_STRING);

	// Friendly URL stuff
    $detailpage = $this->GetPreference('detailpage',-1);
    if( $detailpage == -1 )
      {
		global $gCms;
		$contentops =& $gCms->GetContentOperations();
		$detailpage = $contentops->GetDefaultPageID();
      }
	$str = '/'.$this->GetPreference('urlprefix','[Pp]roducts');
	if( $this->GetPreference('usehierpathurls') )
	  {
		$this->RegisterRoute($str.'\/details\/(?P<returnid>[0-9]+)\/([^\/]+\/)+(?P<alias>.*)$/',
							 array('action'=>'details','returnid'=>$detailpage));
		$this->RegisterRoute($str.'\/details\/([^\/]+\/)+(?P<alias>.*)$/',
							 array('action'=>'details','returnid'=>$detailpage));
	  }

	$this->RegisterRoute($str.'\/(?P<productid>[0-9]+)\/(?P<returnid>[0-9]+)\/(?P<junk>.*?)$/',
						 array('action'=>'details'));
	$this->RegisterRoute($str.'\/(?P<productid>[0-9]+)\/(?P<junk>.*?)$/',
						 array('action'=>'details','returnid'=>$detailpage));
	$this->RegisterRoute($str.'\/viewcategory\/(?P<categoryid>[0-9]+)\/(?P<returnid>[0-9]+)$/',
						 array('action'=>'categorylist'));
	$this->RegisterRoute($str.'\/(?P<productid>[0-9]+)$/');
	$this->RegisterRoute($str.'\/summary\/($P<returnid>[0-9]+)\/(?P<junk>.*?)$/');
	$this->RegisterRoute($str.'\/summary\/($P<returnid>[0-9]+)$/');
	$this->RegisterRoute($str.'\/bycategory\/(?P<categoryid>[0-9]+)\/(?P<returnid>[0-9]+)\/(?P<junk>.*?)$/');
	$this->RegisterRoute($str.'\/bycategory\/(?P<categoryid>[0-9]+)\/(?P<returnid>[0-9]+)$/');
	$this->RegisterRoute($str.'\/byhierarchy\/(?P<hierarchyid>[0-9]+)\/(?P<returnid>[0-9]+)\/(?P<junk>.*?)$/');
	$this->RegisterRoute($str.'\/byhierarchy\/(?P<hierarchyid>[0-9]+)\/(?P<returnid>[0-9]+)$/');
	$this->RegisterRoute($str.'\/hierarchy\/(?P<parent>[0-9]+)\/(?P<returnid>[0-9]+)$/');
	$this->RegisterRoute($str.'\/hierarchy\/(?P<parent>[0-9]+)\/(?P<returnid>[0-9]+)\/(?P<junk>.*?)$/');

  }

  function InstallPostMessage()
  {
	return $this->Lang('postinstall');
  }

  function UninstallPostMessage()
  {
	return $this->Lang('postuninstall');
  }

  function UninstallPreMessage()
  {
	return $this->Lang('preuninstall');
  }

  function GetHelp($lang='en_US')
  {
	return $this->Lang('help');
  }

  function GetAdminSection()
  {
	return 'content';
  }

  function GetAuthor()
  {
	return 'calguy1000';
  }

  function GetAuthorEmail()
  {
	return 'calguy1000@cmsmadesimple.org';
  }

  function GetChangeLog()
  {
	return file_get_contents(dirname(__FILE__).'/changelog.html');
  }

  function GetEventDescription( $eventname )
  {
	return $this->lang('eventdesc-' . $eventname);
  }

  function GetEventHelp( $eventname )
  {
	return $this->lang('eventhelp-' . $eventname);
  }
	

  /*---------------------------------------------------------
   DoAction()
   ---------------------------------------------------------*/
  function DoAction($name,$id,$params,$returnid='')
  {
    global $gCms;
    $smarty =& $gCms->GetSmarty();

    $smarty->assign_by_ref('mod',$this);
    $smarty->assign('returnid',$returnid);
    parent::DoAction($name,$id,$params,$returnid);
  }


  function _load_admin()
  {
	if( !$this->_admin_loaded )
	  {
		require_once(dirname(__FILE__).'/functions.admin_tools.php');
		$this->_admin_loaded = true;
	  }
  }


  // deprecated
  function GetTypesDropdown( $id, $name, $selected = '', $addtext = '', $selectone = false )
  {
	$this->_load_admin();
	return products_GetTypesDropdown($this,$id,$name,$selected,$addtext,$selectone);
  }
	

  // deprecated
  function GetCategory($category_id,$full = false)
  {
	global $gCms;
	$db =& $gCms->GetDb();
	$config = $gCms->GetConfig();

	$query = 'SELECT * FROM '.cms_db_prefix().'module_products_categories WHERE id = ?';
    $query2 = 'SELECT * FROM '.cms_db_prefix().'module_products_category_fields 
               WHERE category_id = ?';

	$row = $db->GetRow($query,array($category_id));
	if( !$row ) return FALSE;

	$onerow = new stdClass();
	$onerow->id = $row['id'];
	$onerow->name = $row['name'];
	$onerow->value = false;
	$onerow->file_location = $config['uploads_url'].'/'.$this->GetName().'/categories/'.$onerow->id;

	if( $full )
	  {
		$tmp2 = $db->GetArray($query2,$row['id']);
		if( is_array($tmp2) )
		  {
			$onerow->data = $tmp2;
		  }
	  }

	return $onerow;
  }


  // deprecated
  function GetCategories($full = false)
  {
	global $gCms;
	$db =& $gCms->GetDB();
	$config = $gCms->GetConfig();
		
	if( count($this->_category_cache) )
	  {
		return $this->_category_cache;
	  }

	$entryarray = array();
		
	$query = 'SELECT * FROM '.cms_db_prefix().'module_products_categories';
    $query2 = 'SELECT * FROM '.cms_db_prefix().'module_products_category_fields 
               WHERE category_id = ?';

	$tmp = $db->GetArray($query);
	foreach( $tmp as $row )
	  {
		$onerow = new stdClass();
			
		$onerow->id = $row['id'];
		$onerow->name = $row['name'];
		$onerow->value = false;
		$onerow->file_location = $config['uploads_url'].'/'.$this->GetName().'/categories/'.$onerow->id;

		if( $full )
		  {
			$tmp2 = $db->GetArray($query2,array($row['id']));
			if( is_array($tmp2) && count($tmp2) > 0)
			  {
				$onerow->data = $tmp2;
			  }
		  }
		$entryarray[] = $onerow;
	  }

	$this->_category_cache = $entryarray;
	return $entryarray;
  }


  // deprecated
  function GetCategoriesForProduct($product,$brief = false,$full = false)
  {
	$entryarray = array();

	global $gCms;
	$db =& $gCms->GetDB();
	
	$entryarray = $this->GetCategories($full);

	$query = 'SELECT c.* FROM '.cms_db_prefix().'module_products_product_categories c WHERE c.product_id = ? ORDER BY c.category_id';
	$prodcats = $db->GetArray($query,array($product));
	
	$results = array();
	if( $brief )
	  {
		foreach( $prodcats as $oneprodcat )
		  {
			foreach( $entryarray as $entry )
			  {
				if( $oneprodcat['category_id'] == $entry->id )
				  {
					$entry->value = true;
					$results[] = $entry;
					break;
				  }
			  }
		  }
	  }
	else
	  {
		// full list of categories,
		// set value to true if this product is a member of this category
		foreach( $entryarray as $entry )
		  {
			$entry->value = false;
			foreach( $prodcats as $oneprodcat )
			  {
				if( $oneprodcat['category_id'] == $entry->id )
				  {
					$entry->value = true;
					break;
				  }
				
			  }

			$results[] = $entry;
		  }
	  }
	return $results;
  }
	

  // deprecated
  function GetFieldDefs($admin = false,$public = true)
  {
	$entryarray = array();
		
	global $gCms;
	$db =& $gCms->GetDB();
		
	if( $admin == true && $public == true )
	  {
		$query = 'SELECT * FROM '.cms_db_prefix().'module_products_fielddefs ORDER BY item_order';
	  }
	else if( $public == true )
	  {
		$query = 'SELECT * FROM '.cms_db_prefix().'module_products_fielddefs WHERE public > 0 ORDER BY item_order';
	  }
	else
	  {
		$query = 'SELECT * FROM '.cms_db_prefix().'module_products_fielddefs WHERE admin_only <= 0 ORDER BY item_order';
	  }
	$dbresult = $db->Execute($query);

	while ($dbresult && $row = $dbresult->FetchRow())
	  {
		$onerow = new stdClass();
			
		$onerow->id = $row['id'];
		$onerow->name = $row['name'];
		$onerow->prompt = $row['prompt'];
		$onerow->type = $row['type'];
		$tmp = explode("\n",$row['options']);
		$tmp2 = array();
		foreach( $tmp as $one )
		  {
			$one = trim($one);
			$tmp2[$one] = $one;
		  }
		$onerow->options = $tmp2;
		$onerow->max_length = $row['max_length'];
			
		$entryarray[] = $onerow;
	  }
		
	return $entryarray;
  }


  // deprecated, move to class
  function GetFieldDefsForProduct($id,$admin = false,$public = true)
  {
	$entryarray = array();

	global $gCms;
	$db =& $gCms->GetDB();
		
	$entryarray = $this->GetFieldDefs($admin,$public);

	$query = '';
	if( $admin == true && $public == true )
	  {
		$query = 'SELECT fv.* FROM '.cms_db_prefix().'module_products_fieldvals fv WHERE fv.product_id = ?';
	  }
	else if( $public == true )
	  {
		$query = 'SELECT b.* FROM '.cms_db_prefix().'module_products_fielddefs a, '.cms_db_prefix().'module_products_fieldvals b WHERE a.id = b.fielddef_id AND a.public > 0 and b.product_id = ?';
	  }
	else 
	  {
		$query = 'SELECT b.* FROM  '.cms_db_prefix().'module_products_fielddefs a, '.cms_db_prefix().'module_products_fieldvals b WHERE a.id = b.fielddef_id AND a.admin_only <= 0 and b.product_id = ?';
	  }
	$dbresult = $db->Execute($query, array($id));
	while ($dbresult && $row = $dbresult->FetchRow())
	  {
		$count = 0;
		foreach ($entryarray as $field)
		  {
			if ($row['fielddef_id'] == $field->id)
			  {
				$entryarray[$count]->fielddef_id = $field->id;
				if( $field->type == 'dimensions' || $field->type == 'subscription')
				  {
					$row['value'] = unserialize($row['value']);
				  }
				$entryarray[$count]->value = $row['value'];
			  }
			$count++;
		  }
	  }

	return $entryarray;
  }
	

  function SearchResult($returnid, $productid, $attr = '')
  {
	return product_ops::get_search_result($returnid,$productid,$attr);
  }
	

  function SearchReindex(&$module)
  {
	$this->_load_admin();
	return products_SearchReindex($this,$module);
  }


  // deprecated ... move to class
  function GetSearchableText($product_id)
  {
	// the product name, the description, and all data from
	// text fields, textara fields, and dropdowns
	if( !isset( $this->_product_cache[$product_id] ) )
	  {
		$this->GetProduct( $product_id );
	  }

	$results = array();
	$product =& $this->_product_cache[$product_id];
	if( $product['status'] != 'published' ) return array();
	$defs = $this->GetFieldDefsForProduct($product_id);

	$results[] = $product['product_name'];
	$results[] = $product['details'];
	$results[] = $product['sku'];
	$results[] = $product['alias'];
	foreach( $defs as $onedef )
	  {
		switch( $onedef->type )
		  {
		  case 'textbox':
		  case 'textarea':
		  case 'dropdown':
            if( isset($onedef->value) )
              {
    			$results[] = $onedef->value;
			  }
			break;
		  }
	  }
	
	return $results;
  }


  // deprecated
  function GetProductNameFromId( $id )
  {
	if( !isset( $this->_product_cache[$id] ) )
	  {
		$this->GetProduct( $id );
	  }

	return $this->_product_cache[$id]['product_name'];
  }


  // depreacated
  function is_taxable($product_id)
  {
    $tmp = $this->GetProduct($product_id);
	return $tmp['taxable'];
  }


  function is_shippable($product_id)
  {
	// todo: add something here.
	return TRUE;
  }


  // deprecated
  function GetProduct( $id )
  {
	if( !isset($this->_product_cache[$id]) )
	  {
		$db =& $this->GetDb();
		$query = "SELECT * FROM ".cms_db_prefix()."module_products
                     WHERE id = ?";
		$row = $db->GetRow( $query, array( $id ) );
		if(!$row) return FALSE;
		$this->_product_cache[$id] = $row;
	  }

	return $this->_product_cache[$id];
  }


  // deprecated
  function GetProductAttributes($product_id)
  {
	$results = array();
	$db =& $this->GetDb();
	$query = "SELECT * FROM ".cms_db_prefix()."module_products_attribsets
               WHERE product_id = ?";
	$dbresult = $db->Execute($query,array($product_id));
	while( $dbresult && ($row = $dbresult->FetchRow()) )
	  {
		$results[$row['attrib_set_id']] =  $row['attrib_set_name'];
	  }
	return $results;
  }


  // deprecated
  function GetProductAttributeDataComplete($product_id)
  {
	$db =& $this->GetDb();
	$data = array();

	$query = 'SELECT * FROM '.cms_db_prefix().'module_products_attribsets
              WHERE product_id = ?';
	$tmp = $db->GetArray($query,array($product_id));
	

	$tmp2 = cge_array::extract_field($tmp,'attrib_set_id');
	$q2 = 'SELECT * FROM '.cms_db_prefix().'module_products_attributes
            WHERE attrib_set_id IN ('.implode(',',$tmp2).') ORDER BY attrib_set_id ASC';
	$tmp3 = $db->GetArray($q2);

	for( $i = 0; $i < count($tmp); $i++ )
	  {
		$row =& $tmp[$i];

		$attribs = array();
		for( $j = 0; $j < count($tmp3); $j++ )
		  {
			$row2 =& $tmp3[$j];
			if( $row2['attrib_set_id'] < $row['attrib_set_id'] ) continue;
			if( $row2['attrib_set_id'] > $row['attrib_set_id'] ) break;

			$attribs[$row2['attrib_text']] = $row2['attrib_adjustment'];
		  }
		$data[$row['attrib_set_name']] = $attribs;
	  }

	if( !count($data) ) return false;
	return $data;
  }


  // deprecated
  static public function GetProductIdsFromCategories($str,$delim = ',')
  {
	if( empty($str) ) return FALSE;
	$names = explode($delim,$str);
	for( $i = 0; $i < count($names); $i++ )
	  {
		$names[$i] = trim(trim($names[$i]),"'");
	  }

	global $gCms;
	$db =& $gCms->GetDb();

	// convert names to category ids.
	$query = 'SELECT id FROM '.cms_db_prefix().'module_products_categories
               WHERE name IN ('.implode(',',$names).')';
	$categories = $db->GetCol($query);
	if( !$categories ) return FALSE;
	
	$query = 'SELECT product_id FROM '.cms_db_prefix().'module_products_product_categories
               WHERE category_id IN ('.implode(',',$categories).')';
	$products = $db->GetCol($query);
	return $products;
  }


  // deprecated 
  static public function GetProductIdsFromHierarchy($hier_str,$delim = ' | ')
  {
	if( empty($hier_str) ) return FALSE;
	$hier_str = trim($hier_str);
	if( $delim != ' | ' )
	  {
		$hier_str = str_replace($delim,' | ',$hier_str);
	  }
	
	global $gCms;
	$db =& $gCms->GetDb();
	$hierarchies = array();
	if( endswith($hier_str,'*') )
	  {
		$hier_str = str_replace('*','%',$hier_str);
		
		$query = 'SELECT id FROM '.cms_db_prefix().'module_products_hierarchy 
               WHERE upper(long_name) LIKE upper(?)';
		$hierarchies = $db->GetCol($query,array($hier_str));
	  }
	else
	  {
		$query = 'SELECT id FROM '.cms_db_prefix().'module_products_hierarchy
               WHERE upper(long_name) = upper(?)';
		$hier_id = $db->GetOne($query,array($hier_str));
		if( !$hier_id )
		  {
			return FALSE;
		  }
		$hierarchies[] = $hier_id;
	  }

	$tmp = implode(',',$hierarchies);
	$query = 'SELECT product_id FROM '.cms_db_prefix()."module_products_prodtohier
                 WHERE hierarchy_id IN ($tmp)";
	$products = $db->GetCol($query);
	return $products;
  }


  // deprecated, move to ops
  function UpdateHierarchyPositions()
  {
	$this->_load_admin();
	return products_UpdateHierarchyPositions($this);
  }


  // deprecated, move to ops
  function BuildHierarchyList()
  {
	$this->_load_admin();
	return products_BuildHierarchyList($this);
  }


  // deprecated, move to ops
  function CreateHierarchyDropdown($id,$name,$selectedvalue)
  {
	$this->_load_admin();
	return products_CreateHierarchyDropdown($this,$id,$name,$selectedvalue);
  }


  // deprecated... use get_pretty_url
  function CreatePrettyLink($id, $action, $returnid='', $contents='', $params=array(), 
							$warn_message='', $onlyhref=false, $inline=false, $addtext='', 
							$targetcontentonly=false, $prettyurl='')
  {
	// this method, if overridden, should call CreateLink for all stuff it can't
	// understand
	$products = '';
	$prettyurl = '';

 	switch( $action )
	  {
	  case 'details':
		$prettyurl = product_ops::pretty_url($params['productid'],$returnid,$params);
	 	break;

	  case 'default':
		if( isset($params['categoryid']) )
		  {
			// if the category id parameter is set, use bycategory
			$prettyurl = sprintf("%s/bycategory/%d/%d",
								 $this->GetPreference('urlprefix','products'),
								 (int)$params['categoryid'],$returnid);
			if( isset($params['categoryname']) )
			  {
				$prettyurl .= '/'.munge_string_to_url($params['categoryname']);
			  }
		  }
		else if( isset($params['hierarchyid']) )
		  {
			// if the hierarchy id parameter is set, use byhierarchy
			$prettyurl = sprintf("%s/byhierarchy/%d/%d",
								 $this->GetPreference('urlprefix','products'),
								 (int)$params['hierarchyid'],$returnid);
		  }
		else if( isset($params['fieldid']) )
		  {
			// no pretty urls for this atm.
			$prettyurl = '';
		  }
		else
		  {
			// otherwise use summary
			$prettyurl = sprintf("%s/summary/%d",
								 $this->GetPreference('urlprefix','products'),
								 $returnid);
		  }
	 	break;

	  case 'categorylist':
		{
		  if( isset($params['categoryid']) && !isset($params['categorylistdtltemplate']) )
			{
			  $prettyurl = sprintf("%s/viewcategory/%s/%s",
								   $this->GetPreference('urlprefix','products'),
								   (int)$params['categoryid'],$returnid);
			}
		}
		break;

	  case 'hierarchy':
		$prettyurl = sprintf("%s/hierarchy/%d/%d",
							 $this->GetPreference('urlprefix','products'),
							 (int)$params['parent'],$returnid);
		break;

	  }

	$out = $this->CreateLink($id,$action,$returnid,$contents,$params,$warn_message,
							 $onlyhref,$inline,$addtext,$targetcontentonly,$prettyurl);

	return $out;
  }


  // creates a form-safe alias string
  function make_alias($string, $isForm=false)
  {
	$string = munge_string_to_url($string);
	$string = trim($string, '_');
	return strtolower($string);
  }


  function HandleUploadedImage($id,$name,$destdir,&$errors,$subfield='',$wmlocation='',$overwrite=false)
  {
	$this->_load_admin();
	return products_HandleUploadedImage($this,$id,$name,$destdir,$errors,$subfield,
										$wmlocation,$overwrite);
  }


  function ProcessImage($srcname,$wmlocation='default')
  {
	$this->_load_admin();
	return products_ProcessImage($this,$srcname,$wmlocation);
  }


  // deprecated
  function GetHierarchyInfo($hierarchy_id)
  {
	return hierarchy_ops::get_hierarchy_info($hierarchy_id);
  }


  // deprecated
  function GetHierarchyPath($hiearchy_id)
  {
	return hierarchy_ops::get_hierarchy_path($hierarchy_id);
  }


  // deprecated
  function GetProductHierarchyPath($productid)
  {
	return product_ops::get_product_hierarchy_path($productid);
  }


  function DeleteProduct($productid,$update_search=true)
  {
	$this->_load_admin();
	return products_DeleteProduct($this,$productid,$update_search);
  }


  function _smarty_products_getcategory($params,&$smarty)
  {
	if( !isset($params['categoryid']) ) return;

	$catid = (int)$params['categoryid'];
	$obj =& $this->GetCategory($catid);
	
	if( isset($params['assign']) )
	  {
		$smarty->assign($params['assign'],$obj);
		return;
	  }
	return $obj;
  }


  function get_product_info($product_id)
  {
	$tmp = $this->GetProduct($product_id);
	if( !is_array($tmp) )
	  {
		return FALSE;
	  }
	$tmp2 = $this->GetProductAttributes($product_id);
	if( $tmp2 )
	  {
		$db = $this->GetDb();
		$attr_results = array();
		$query = 'SELECT * FROM '.cms_db_prefix().'module_products_attributes
                   WHERE attrib_set_id = ? ORDER BY attrib_id';
		foreach( $tmp2 as $attr_set_id => $attr_set_name )
		  {
			$attr_data = array('name'=>$attr_set_name);
			$tmp3 = $db->GetArray($query,array($attr_set_id));
			if( is_array($tmp3) )
			  {
				$tmp3 = cge_array::to_hash($tmp3,'attrib_id');
				$attr_data['values'] = $tmp3;
			  }
			$attr_results[$attr_set_id] = $attr_data;
		  }
		$tmp['attributes'] = $attr_results;
	  }
	$tmp3 = $this->GetFieldDefsForProduct($product_id,true,true);
	if( is_array($tmp3) )
	  {
		$tmp['fields'] = $tmp3;
	  }
	return $tmp;
  }
} // class

# vim:ts=4 sw=4 noet
?>
