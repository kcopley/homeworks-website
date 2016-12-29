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

$db =& $this->GetDb();

$dict = NewDataDictionary($db);
$taboptarray = array('mysql' => 'TYPE=MyISAM');
$flds = "
	id I KEY AUTO,
	product_name C(255) NOT NULL,
	details X,
        price F,
	create_date " . CMS_ADODB_DT . ",
	modified_date " . CMS_ADODB_DT . ",
        taxable I,
        status C(50),
        weight F,
        sku    C(25),
        alias  C(255)
";
$sqlarray = $dict->CreateTableSQL(cms_db_prefix()."module_products", 
		$flds, $taboptarray);
$dict->ExecuteSQLArray($sqlarray);


$flds = "
	id I KEY AUTO,
	name C(255) NOT NULL,
	create_date " . CMS_ADODB_DT . ",
	modified_date " . CMS_ADODB_DT . "
";

$sqlarray = $dict->CreateTableSQL(cms_db_prefix()."module_products_categories", 
		$flds, $taboptarray);
$dict->ExecuteSQLArray($sqlarray);


$flds = "
	product_id I NOT NULL,
	category_id I NOT NULL,
	create_date " . CMS_ADODB_DT . ",
	modified_date " . CMS_ADODB_DT . "
";
$sqlarray = $dict->CreateTableSQL(cms_db_prefix()."module_products_product_categories", 
		$flds, $taboptarray);
$dict->ExecuteSQLArray($sqlarray);


$flds = "
        category_id I,
        field_type  C(20),
        field_name  C(255),
        field_prompt C(255),
        field_value  X,
        field_order  I
";
$sqlarray = $dict->CreateTableSQL(cms_db_prefix()."module_products_category_fields", 
		$flds, $taboptarray);
$dict->ExecuteSQLArray($sqlarray);


$flds = "
	id I KEY AUTO,
	name C(255) NOT NULL,
        prompt C(255),
	type C(50),
	max_length I,
        options X,
	create_date " . CMS_ADODB_DT . ",
	modified_date " . CMS_ADODB_DT . ",
        item_order I,
        public I
";
$sqlarray = $dict->CreateTableSQL(cms_db_prefix()."module_products_fielddefs", 
		$flds, $taboptarray);
$dict->ExecuteSQLArray($sqlarray);


$flds = "
	product_id I,
	fielddef_id I,
	value X,
	create_date " . CMS_ADODB_DT . ",
	modified_date " . CMS_ADODB_DT . "
";
$sqlarray = $dict->CreateTableSQL(cms_db_prefix()."module_products_fieldvals", 
		$flds, $taboptarray);
$dict->ExecuteSQLArray($sqlarray);


$flds = "
        attrib_set_id I KEY AUTO,
        product_id I KEY,
        attrib_set_name C(255) NOT NULL
";
$sqlarray = $dict->CreateTableSQL(cms_db_prefix()."module_products_attribsets", 
		$flds, $taboptarray);
$dict->ExecuteSQLArray($sqlarray);


$flds = "
        attrib_id I KEY AUTO,
        attrib_set_id I KEY,
        attrib_text   C(255) KEY,
        attrib_adjustment C(50),
        sku C(25)
";
$sqlarray = $dict->CreateTableSQL(cms_db_prefix()."module_products_attributes", 
		$flds, $taboptarray);
$dict->ExecuteSQLArray($sqlarray);


$flds = "id I KEY AUTO,
         name C(255) NOT NULL,
         parent_id I,
         hierarchy C(255),
         image C(255),
         long_name X,
         description X,
         extra1 C(255),
         extra2 C(255)";
$sqlarray = $dict->CreateTableSQL(cms_db_prefix().'module_products_hierarchy',
				  $flds,$taboptarray);
$dict->ExecuteSQLArray($sqlarray);

$flds = "product_id I KEY,
         hierarchy_id I KEY";
$sqlarray = $dict->CreateTableSQL(cms_db_prefix().'module_products_prodtohier',
				  $flds,$taboptarray);
$dict->ExecuteSQLArray($sqlarray);

#
# Indexes
#
$sqlarray = $dict->CreateIndexSQL('products_name',cms_db_prefix().'module_products','product_name');
$dict->ExecuteSQLArray($sqlarray);
$sqlarray = $dict->CreateIndexSQL('products_status',cms_db_prefix().'module_products','product_status');
$dict->ExecuteSQLArray($sqlarray);
$sqlarray = $dict->CreateIndexSQL('products_price',cms_db_prefix().'module_products','product_price');
$dict->ExecuteSQLArray($sqlarray);
$sqlarray = $dict->CreateIndexSQL('products_dates',cms_db_prefix().'module_products','create_date,modified_date');
$dict->ExecuteSQLArray($sqlarray);
$sqlarray = $dict->CreateIndexSQL('products_cat_name',cms_db_prefix().'module_products_categories','name');
$dict->ExecuteSQLArray($sqlarray);
$sqlarray = $dict->CreateIndexSQL('products_prod_cat',cms_db_prefix().'module_products_product_categories','product_id,category_id');
$dict->ExecuteSQLArray($sqlarray);
$sqlarray = $dict->CreateIndexSQL('products_cat_prod',cms_db_prefix().'module_products_product_categories','category_id,product_id');
$dict->ExecuteSQLArray($sqlarray);
$sqlarray = $dict->CreateIndexSQL('products_cat_fld_name',cms_db_prefix().'module_products_category_fields','category_id,field_name');
$dict->ExecuteSQLArray($sqlarray);
$sqlarray = $dict->CreateIndexSQL('products_flddef_name',cms_db_prefix().'module_products_fielddefs','name');
$dict->ExecuteSQLArray($sqlarray);
$sqlarray = $dict->CreateIndexSQL('products_flddef_type',cms_db_prefix().'module_products_fielddefs','type');
$dict->ExecuteSQLArray($sqlarray);
$sqlarray = $dict->CreateIndexSQL('products_fldval_prod_def',cms_db_prefix().'module_products_fielvals','product_id,fielddef_id');
$dict->ExecuteSQLArray($sqlarray);
$sqlarray = $dict->CreateIndexSQL('products_fldval_def_prod',cms_db_prefix().'module_products_fielvals','fielddef_id,product_id');
$dict->ExecuteSQLArray($sqlarray);
$sqlarray = $dict->CreateIndexSQL('products_hier_name',cms_db_prefix().'module_products_hierarchy','name');
$dict->ExecuteSQLArray($sqlarray);
$sqlarray = $dict->CreateIndexSQL('products_hier_name',cms_db_prefix().'module_products_hierarchy','name');
$dict->ExecuteSQLArray($sqlarray);
$sqlarray = $dict->CreateIndexSQL('products_hier_parent',cms_db_prefix().'module_products_hierarchy','parent_id');
$dict->ExecuteSQLArray($sqlarray);
$sqlarray = $dict->CreateIndexSQL('products_hier_longname',cms_db_prefix().'module_products_hierarchy','long_name');
$dict->ExecuteSQLArray($sqlarray);
$sqlarray = $dict->CreateIndexSQL('products_hier_hierarchy',cms_db_prefix().'module_products_hierarchy','hierarchy');
$dict->ExecuteSQLArray($sqlarray);
$sqlarray = $dict->CreateIndexSQL('products_name',cms_db_prefix().'module_products','product_alias');
$dict->ExecuteSQLArray($sqlarray);



#
# Templates
#


# Setup summary template
$fn = cms_join_path(dirname(__FILE__),'templates','orig_summary_template.tpl');
if( file_exists( $fn ) )
  {
    $template = file_get_contents( $fn );
    $this->SetPreference(PRODUCTS_PREF_NEWSUMMARY_TEMPLATE,$template);
    $this->SetTemplate('summary_Sample',$template);
    $this->SetPreference(PRODUCTS_PREF_DFLTSUMMARY_TEMPLATE,'Sample');
  }

# Setup detail template
$fn = cms_join_path(dirname(__FILE__),'templates','orig_detail_template.tpl');
if( file_exists( $fn ) )
  {
    $template = file_get_contents( $fn );
    $this->SetPreference(PRODUCTS_PREF_NEWDETAIL_TEMPLATE,$template);
    $this->SetTemplate('detail_Sample',$template);
    $this->SetPreference(PRODUCTS_PREF_DFLTDETAIL_TEMPLATE,'Sample');
  }

# Setup default hierarchy report template
$fn = cms_join_path(dirname(__FILE__),'templates','orig_byhierarchy_template.tpl');
if( file_exists( $fn ) )
  {
    $template = file_get_contents( $fn );
    $this->SetPreference(PRODUCTS_PREF_NEWBYHIERARCHY_TEMPLATE,$template);
    $this->SetTemplate('byhierarchy_Sample',$template);
    $this->SetPreference(PRODUCTS_PREF_DFLTBYHIERARCHY_TEMPLATE,'Sample');
  }

# Setup default category list template
$fn = cms_join_path(dirname(__FILE__),'templates','orig_categorylist_template.tpl');
if( file_exists( $fn ) )
  {
    $template = file_get_contents( $fn );
    $this->SetPreference(PRODUCTS_PREF_NEWCATEGORYLIST_TEMPLATE,$template);
    $this->SetTemplate('categorylist_Sample',$template);
    $this->SetPreference(PRODUCTS_PREF_DFLTCATEGORYLIST_TEMPLATE,'Sample');
  }

# Setup default search template
$fn = cms_join_path(dirname(__FILE__),'templates','orig_search_template.tpl');
if( file_exists( $fn ) )
  {
    $template = file_get_contents( $fn );
    $this->SetPreference(PRODUCTS_PREF_NEWSEARCH_TEMPLATE,$template);
    $this->SetTemplate('search_Sample',$template);
    $this->SetPreference(PRODUCTS_PREF_DFLTSEARCH_TEMPLATE,'Sample');
  }


#Set Permission
$this->CreatePermission('Modify Products', 'Modify Products');

#Preferences
$this->SetPreference('products_currencysymbol','$');
$this->SetPreference('products_weightunits','kg');
$this->SetPreference('allowed_imagetypes','jpg,jpeg,gif,png');
$this->SetPreference('allowed_filetypes','pdf,doc,txt,jpg,jpeg,gif,png');
$this->SetPreference('autothumbnail',1);
$this->SetPreference('deleteproductfiles',1);
$this->SetPreference('cartmoduleparams','product=$entry->id');

// find a cart module
$modules =& $gCms->modules;
foreach( $modules as $one )
{
  if( !isset($one['object']) ) continue;
  $obj =& $one['object'];
  if( !is_object($obj) ) continue;
  if( !method_exists($obj,'IsCartModule') ) continue;
  if( !$obj->IsCartModule() ) continue;
  $this->SetPreference('cartmodule',$obj->GetName());
  break;
}
?>
