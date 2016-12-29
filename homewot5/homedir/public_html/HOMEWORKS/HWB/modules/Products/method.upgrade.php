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

switch( $oldversion )
  {
  case '1.0':
  case '1.0.1':
    {
      $db =& $this->GetDb();
      $dict = NewDataDictionary($db);
      $taboptarray = array('mysql' => 'TYPE=MyISAM');

      $flds = "
        attrib_set_id I KEY AUTO,
        product_id I KEY,
        attrib_set_name C(255)
      ";
      $taboptarray = array('mysql' => 'TYPE=MyISAM');
      $sqlarray = $dict->CreateTableSQL(cms_db_prefix()."module_products_attribsets", 
					$flds, $taboptarray);
      $dict->ExecuteSQLArray($sqlarray);

      $flds = "
        attrib_set_id I KEY,
        attrib_text   C(255) KEY,
        attrib_adjustment C(50)
      ";
      $taboptarray = array('mysql' => 'TYPE=MyISAM');
      $sqlarray = $dict->CreateTableSQL(cms_db_prefix()."module_products_attributes", 
					$flds, $taboptarray);
      $dict->ExecuteSQLArray($sqlarray);
    }


  case '2.0':
  case '2.0.1':
  case '2.0.2':
    {
      $db =& $this->GetDb();
      $dict = NewDataDictionary($db);
      $taboptarray = array('mysql' => 'TYPE=MyISAM');

      $flds = "
          category_id I,
          field_type  C(20),
          field_name  C(255),
          field_prompt C(255),
          field_value  X
      ";
      $sqlarray = $dict->CreateTableSQL(cms_db_prefix()."module_products_category_fields", 
	     $flds, $taboptarray);
      $dict->ExecuteSQLArray($sqlarray);
    }

  case '2.1':
    {
      $db =& $this->GetDb();
      $query = 'SELECT * FROM '.cms_db_prefix().'module_products_attributes';
      $dict = NewDataDictionary($db);
      $data = $db->GetArray($query);

      $sqlarray = $dict->DropTableSQL(cms_db_prefix().'module_products_attributes');
      $dict->ExecuteSQLArray($sqlarray);

      $flds = "
        attrib_id I KEY AUTO,
        attrib_set_id I,
        attrib_text   C(255),
        attrib_adjustment C(50)
      ";
      $sqlarray = $dict->CreateTableSQL(cms_db_prefix()."module_products_attributes", 
		$flds, $taboptarray);
      $dict->ExecuteSQLArray($sqlarray);

      $query = 'INSERT INTO '.cms_db_prefix().'module_products_attributes
                (attrib_set_id, attrib_text, attrib_adjustment)
         	    VALUES (?,?,?)';
      foreach( $data as $one )
      {
        $db->Execute($query,
           array($one['attrib_set_id'],$one['attrib_text'],$one['attrib_adjustment']));
      }
    }
 
  case '2.1.2':
	{
	  $this->SetPreference('cartmoduleparams','product=$entry->id');

	  // find a cart module
	  $modules =& $gCms->modules;
	  $found = false;
	  foreach( $modules as $one )
		{
		  if( !isset($one['object']) ) continue;
		  $obj =& $one['object'];
		  if( !is_object($obj) ) continue;
		  if( !method_exists($obj,'IsCartModule') ) continue;
		  if( !$obj->IsCartModule() ) continue;
		  $this->SetPreference('cartmodule',$obj->GetName());
		  $found = true;
		  break;
		}
	  if( $found === false )
		{
		  // Set a default cart module to 'None'... just in case
		  $this->SetPreference('cartmodule',-1);
		}
	  
	  $newname = str_replace('.','_','upgrade_to_'.$this->GetVersion());
	  
	  // setup a new default summary template.
	  // but don't destroy any others.
	  $fn = cms_join_path(dirname(__FILE__),'templates','orig_summary_template.tpl');
	  if( file_exists( $fn ) )
		{
		  $template = file_get_contents( $fn );
		  $this->SetTemplate('summary_'.$newname,$template);
		  $this->SetPreference(PRODUCTS_PREF_DFLTSUMMARY_TEMPLATE,$newname);
		}

	  // setup a new default detail template.
	  // but don't destroy any others.
	  $fn = cms_join_path(dirname(__FILE__),'templates','orig_detail_template.tpl');
	  if( file_exists( $fn ) )
		{
		  $template = file_get_contents( $fn );
		  $this->SetTemplate('detail_'.$newname,$template);
		  $this->SetPreference(PRODUCTS_PREF_DFLTDETAIL_TEMPLATE,$newname);
		}

	  // setup a new default byhierarchy template.
	  // but don't destroy any others.
	  $fn = cms_join_path(dirname(__FILE__),'templates','orig_byhierarchy_template.tpl');
	  if( file_exists( $fn ) )
		{
		  $template = file_get_contents( $fn );
		  $this->SetTemplate('byhierarchy_'.$newname,$template);
		  $this->SetPreference(PRODUCTS_PREF_DFLTBYHIERARCHY_TEMPLATE,$newname);
		}

	  // setup a new default categorylist template.
	  // but don't destroy any others.
	  $fn = cms_join_path(dirname(__FILE__),'templates','orig_categorylist_template.tpl');
	  if( file_exists( $fn ) )
		{
		  $template = file_get_contents( $fn );
		  $this->SetTemplate('categorylist_'.$newname,$template);
		  $this->SetPreference(PRODUCTS_PREF_DFLTCATEGORYLIST_TEMPLATE,$newname);
		}

	} // 2.1.1

  case '2.2':
  case '2.2.1':
  case '2.2.2':
  case '2.2.3':
  case '2.2.4':
	{
      $db =& $this->GetDb();
      $dict = NewDataDictionary($db);

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

	  $sqlarray = $dict->AddColumnSQL(cms_db_prefix()."module_products_hierarchy", "extra1 C(255),extra2 C(255)");
	  $dict->ExecuteSQLArray($sqlarray);

	}

  case '2.3':
  case '2.3.1':
	{
	  $this->SetPreference('auto_thumbnail_size',75);
	  $this->SetPreference('autopreviewimg',0);
	  $this->SetPreference('auto_previewimg_size',150);
	  $this->SetPreference('urlprefix','products');
	}

  case '2.4':
  case '2.4.1':
  case '2.4.2':
  case '2.4.3':
  case '2.4.4':
  case '2.4.5':
  case '2.4.6':
  case '2.4.7':
  case '2.4.8':
	{
      $db =& $this->GetDb();
      $dict = NewDataDictionary($db);

	  $sqlarray = $dict->AddColumnSQL(cms_db_prefix()."module_products", "sku C(25)");
	  $dict->ExecuteSQLArray($sqlarray);

	  $sqlarray = $dict->AddColumnSQL(cms_db_prefix()."module_products_attributes", "sku C(25)");
	  $dict->ExecuteSQLArray($sqlarray);
	}

  case '2.5':
	{
      $db =& $this->GetDb();
      $dict = NewDataDictionary($db);

	  $sqlarray = $dict->AddColumnSQL(cms_db_prefix()."module_products", "alias C(255)");
	  $dict->ExecuteSQLArray($sqlarray);

	  $sqlarray = $dict->CreateIndexSQL('products_name',cms_db_prefix().'module_products','product_alias');
	  $dict->ExecuteSQLArray($sqlarray);

	}

  case '2.6':
  case '2.6.1':
  case '2.6.2':
  case '2.7':
	{
	  // Setup default search template
	  $this->RemovePreference(PRODUCTS_PREF_NEWSEARCH_TEMPLATE);
	  $this->RemovePreference(PRODUCTS_PREF_DFLTSEARCH_TEMPLATE);
	  $fn = cms_join_path(dirname(__FILE__),'templates','orig_search_template.tpl');
	  if( file_exists( $fn ) )
		{
		  $template = file_get_contents( $fn );
		  $this->SetPreference(PRODUCTS_PREF_NEWSEARCH_TEMPLATE,$template);
		  $this->SetTemplate('search_Sample',$template);
		  $this->SetPreference(PRODUCTS_PREF_DFLTSEARCH_TEMPLATE,'Sample');
		}
	}

  case '2.7.1':
  case '2.7.2':
  case '2.7.3':
  case '2.7.4':
	{
	  // Setup default search template
	  $this->RemovePreference(PRODUCTS_PREF_NEWSEARCH_TEMPLATE);
	  $this->RemovePreference(PRODUCTS_PREF_DFLTSEARCH_TEMPLATE);
	  $fn = cms_join_path(dirname(__FILE__),'templates','orig_search_template.tpl');
	  if( file_exists( $fn ) )
		{
		  $template = file_get_contents( $fn );
		  $this->SetPreference(PRODUCTS_PREF_NEWSEARCH_TEMPLATE,$template);
		  $this->SetTemplate('search_Sample',$template);
		  $this->SetPreference(PRODUCTS_PREF_DFLTSEARCH_TEMPLATE,'Sample');
		}
	}

	// add a new field to the category fields table.
	$db =& $this->GetDb();
	$dict = NewDataDictionary($db);
	$sqlarray = $dict->AddColumnSQL(cms_db_prefix()."module_products_category_fields", "field_order I");
	$dict->ExecuteSQLArray($sqlarray);

	// now update the category fields stuff.
	$query = 'SELECT * FROM '.cms_db_prefix().'module_products_category_fields
               ORDER BY category_id,field_name';
	$data = $db->GetArray($query);
	$prev_category_id = -1;
	$order = 0;
	foreach($data as $row)
	  {
		if( $row['category_id'] != $prev_category_id )
		  {
			$order = 0;
		  }

		$row['field_order'] = $order;
		$query = 'UPDATE '.cms_db_prefix().'module_products_category_fields
                     SET field_order = ? WHERE category_id = ? AND field_name = ?';
		$db->Execute($query,array($order,$row['category_id'],$row['field_name']));

		$order++;
		$prev_category_id = $row['category_id'];
	  }

  case '2.7.5':
	// now re-order category field stuff again.
	$query = 'SELECT * FROM '.cms_db_prefix().'module_products_category_fields
               ORDER BY category_id,field_name';
	$data = $db->GetArray($query);
	$prev_category_id = -1;
	$order = 0;
	foreach($data as $row)
	  {
		if( $row['category_id'] != $prev_category_id )
		  {
			$order = 0;
		  }

		$row['field_order'] = $order;
		$query = 'UPDATE '.cms_db_prefix().'module_products_category_fields
                     SET field_order = ? WHERE category_id = ? AND field_name = ?';
		$db->Execute($query,array($order,$row['category_id'],$row['field_name']));

		$order++;
		$prev_category_id = $row['category_id'];
	  }
  }

// EOF
?>
