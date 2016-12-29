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


function products_SearchReindex(&$mod,$module)
{
  $db =& $mod->GetDb();
  
  $query = 'SELECT id FROM '.cms_db_prefix().'module_products where status = ?';
  $result = &$db->Execute($query,array('published'));
  
  while ($result && !$result->EOF)
    {
      $data = $mod->GetSearchableText($result->fields['id']);
      $module->AddWords($mod->GetName(), $result->fields['id'], 'product', 
			implode(' ', $data ) );
      $result->MoveNext();
    }
}

function products_UpdateHierarchyPositions(&$mod)
{
  $db =& $mod->GetDb();
	
  $query = "SELECT id, name FROM ".cms_db_prefix()."module_products_hierarchy";
  $dbresult = $db->Execute($query);
  if( !$dbresult ) { echo $db->sql.'<br/>'; die( $db->ErrorMsg() ); }
  while ($dbresult && $row = $dbresult->FetchRow())
    {
      $current_hierarchy_position = "";
      $current_long_name = "";
      $content_id = $row['id'];
      $current_parent_id = $row['id'];
      $count = 0;
		
      while ($current_parent_id > -1)
	{
	  $query = "SELECT id, name, parent_id FROM ".cms_db_prefix()."module_products_hierarchy 
                     WHERE id = ?";
	  $row2 = $db->GetRow($query, array($current_parent_id));
	  if ($row2)
	    {
	      $current_hierarchy_position = str_pad($row2['id'], 5, '0', STR_PAD_LEFT) . "." . $current_hierarchy_position;
	      $current_long_name = $row2['name'] . ' | ' . $current_long_name;
	      $current_parent_id = $row2['parent_id'];
	      $count++;
	    }
	  else
	    {
	      $current_parent_id = 0;
	    }

	}
		  
      if (strlen($current_hierarchy_position) > 0)
	{
	  $current_hierarchy_position = substr($current_hierarchy_position, 0, strlen($current_hierarchy_position) - 1);
	}
		  
      if (strlen($current_long_name) > 0)
	{
	  $current_long_name = substr($current_long_name, 0, strlen($current_long_name) - 3);
	}
	  
      $query = "UPDATE ".cms_db_prefix()."module_products_hierarchy 
                  SET hierarchy = ?, long_name = ? WHERE id = ?";
      $db->Execute($query, array($current_hierarchy_position, $current_long_name, $content_id));
    }
}

function products_BuildHierarchyList(&$mod)
{
  global $gCms;
  $db =& $gCms->GetDb();
  
  $hierarchy_items = array();
  $hierarchy_items[$mod->Lang('none')] = -1;
  $query = 'SELECT hierarchy, long_name FROM '.cms_db_prefix().'module_products_hierarchy WHERE id = -1';
  $dbr = $db->Execute( $query );
  $longname = '';
  while( $dbr && ($row = $dbr->FetchRow()) )
    {
      $longname = $row['hierarchy'] . '%';
    }
  $query = 'SELECT id, long_name FROM '.cms_db_prefix().'module_products_hierarchy
           WHERE hierarchy NOT LIKE ?
           ORDER BY hierarchy';
  $dbr = $db->Execute($query,array($longname));
  while( $dbr && ($row = $dbr->FetchRow()) )
    {
      $hierarchy_items[$row['long_name']] = $row['id'];
    }
  
  return $hierarchy_items;
}


function products_CreateHierarchyDropdown(&$mod,$id,$name,$selectedvalue)
{
  $db =& $mod->GetDb();
  $list = array();
  $list[$mod->Lang('any')] = '';
  $query = 'SELECT * FROM '.cms_db_prefix().'module_products_hierarchy
               ORDER by hierarchy';
  $dbresult = $db->Execute($query);
  while( $dbresult && $row = $dbresult->FetchRow() )
    {
      //$list[$row['long_name']] = $row['long_name'];
      $list[$row['long_name']] = $row['id'];
    }

  return $mod->CreateInputDropdown($id,$name,$list,-1,$selectedvalue);
}


function products_GetTypesDropdown( &$mod, $id, $name, $selected = '', $addtext = '', 
				    $selectone = false )
{
  $items = product_utils::get_field_types($selectone);

  // CreateInputDropdown wants the labels first for some reason.
  $items = array_flip($items);
  
  return $mod->CreateInputDropdown($id, $name, $items, -1, $selected,$addtext);
}
	

function products_HandleUploadedImage(&$mod,$id,$name,$destdir,&$errors,$subfield='',$wmlocation='',$overwrite=false)
{
    cge_dir::mkdirr($destdir);
    if( !is_dir($destdir) ) return FALSE;
    
    $handler = cge_setup::get_uploader($id,$destdir);
	$handler->set_accepted_filetypes($mod->GetPreference('allowed_filetypes'));
    $handler->set_accepted_imagetypes($mod->GetPreference('allowed_imagetypes'));
    $handler->set_allow_overwrite($overwrite);
	$handler->set_delete_orig(false);
	$handler->set_preview($mod->GetPreference('autopreviewimg'));
	$size = $mod->GetPreference('auto_previewimg_size',150);
	$handler->set_preview_size($size);
    $handler->set_thumbnail($mod->GetPreference('autothumbnail'));
	$handler->set_thumbnail_size($mod->GetPreference('auto_thumbnail_size',75));

    $handler->set_watermark($mod->GetPreference('autowatermark','none') != 'none' && $wmlocation != 'none');
    if( !empty($wmlocation) && $wmlocation != 'default' ) 
	  {
		$handler->get_watermark_obj()->set_alignment($wmlocation);
	  }

    $res = $handler->handle_upload($name,'',$subfield);
    $err = $handler->get_error();
   
    if( !$res && $err != cg_fileupload::NOFILE )
      {
		// upload error
		$errors[] = sprintf("%s %s: %s",$mod->Lang('field'),$name,
							$mod->GetUploadErrorMessage($err).' - '.$handler->get_errormsg());
		return FALSE;
      }
    if( empty($res) || $res === false )
      {
		// no file was uploaded
		// do nothing
		return TRUE;
      }
    
    $filename = $res;
    return $filename;
}


function products_ProcessImage(&$mod,$srcname,$wmlocation='default')
{
  $errors = array();
  if( empty($srcname) ) return FALSE;
  if( !file_exists($srcname) ) return FALSE;
  $destdir = dirname($srcname);
  $filename = basename($srcname);

  // optional watermarking
  if( empty($wmlocation) ) $wmlocation == 'default';
  if( $mod->GetPreference('autowatermark') != 'none' && $wmlocation != 'none')
	{
	  $tmpname = cms_join_path($destdir,'wm_'.$filename);
	
	  // watermark the image
	  $wmobj = cge_setup::get_watermarker();
	  if( $wmlocation != 'default' )
		{
		  $val = (int)$wmlocation;
		  // change the watermark location
		  $wmobj->set_alignment($val);
		}
	
	  $res = $wmobj->create_watermarked_image($srcname,$tmpname);
	
	  if( FALSE !== $res )
		{
		  // watermarking worked I guess
		  
		  // delete the original
		  @unlink($srcname);
		  
		  // rename the watermaked file
		  @rename($tmpname,$srcname);
		}
	  else
		{
		  // watermarking failed
		  $tmp = $wmobj->get_error();
		  $tmp2 = $mod->GetWatermarkError($tmp);
		  if( is_array($errors) )
			{
			  $errors[] = $tmp2;
			}
		  else
			{
			  $errors = $tmp2;
			}
		  @unlink($srcname);
		  return FALSE;
		}
      }

    // optional preview image
    if( $mod->GetPreference('autopreview') == 1 )
      {
		$destname = cms_join_path($destdir,'preview_'.$filename);
		if( !file_exists($destname) )
		  {
			$size = $mod->GetPreference('auto_previewimg_size',75);
			$mod->TransformImage($srcname,$destname,$size);
		  }
      }  

    // optional thumbnailing
    if( $mod->GetPreference('autothumbnail') == 1 )
      {
		$destname = cms_join_path($destdir,'thumb_'.$filename);
		if( !file_exists($destname) )
		  {
			$size = $mod->GetPreference('auto_thumbnail_size',75);
			$mod->TransformImage($srcname,$destname,$size);
		  }
      }  

	return TRUE;
}


function products_DeleteProduct(&$mod,$product_id,$update_search=true)
{
  global $gCms;
  $db =& $gCms->GetDb();

  // Get the category details
  $query = 'SELECT * FROM '.cms_db_prefix().'module_products WHERE id = ?';
  $row = $db->GetRow($query, array($product_id));
  
  //Now remove the category
  $query = "DELETE FROM ".cms_db_prefix()."module_products WHERE id = ?";
  $db->Execute($query, array($product_id));
  
  //And remove it from any custom fields
  $query = "DELETE FROM ".cms_db_prefix()."module_products_fieldvals WHERE product_id = ?";
  $db->Execute($query, array($product_id));
  
  //And remove it from any categories
  $query = "DELETE FROM ".cms_db_prefix()."module_products_product_categories WHERE product_id = ?";
  $db->Execute($query, array($product_id));
  
  // and remove the hierarchy stuff
  $query = 'DELETE FROM '.cms_db_prefix().'module_products_prodtohier WHERE product_id = ?';
  $db->Execute($query, array($product_id));
  
  // and (optionally) remove all files
  if( $mod->GetPreference('deleteproductfiles') )
	{
	  $dir = cms_join_path($gCms->config['uploads_path'],$mod->GetName(),'product_'.$product_id);
	  cge_dir::recursive_remove_directory($dir);
	}
  
  //Update search index
  if( $update_search )
	{
	  $module =& $mod->GetModuleInstance('Search');
	  if ($module != FALSE)
		{
		  $module->DeleteWords($mod->GetName(), $product_id, 'product');
		}
	}
}


#
# EOF
#
?>
