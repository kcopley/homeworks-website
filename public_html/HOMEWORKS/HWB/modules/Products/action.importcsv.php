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

include(dirname(__FILE__).'/class.csv_importer.php');
include(dirname(__FILE__).'/class.importImageHandler.php');

$cge = $this->GetModuleInstance('CGExtensions');
$fn = $cge->GetModulePath().'/lib/Progress2_Lite.php';
include($fn);

if( !isset($_SESSION['products_import_data']) ) die();
$params = $_SESSION['products_import_data'];
$batchsize = 50;
$linenum = 0;
$fpos = 0;
if( isset($params['batchsize']) )
  {
    $batchsize = (int)$params['batchsize'];
  }
if( isset($params['linenum']) )
  {
    $linenum = (int)$params['linenum'];
  }
if( isset($params['fpos']) )
  {
    $fpos = (int)$params['fpos'];
  }

// setup display

// begin theme
$themeName=get_preference(get_userid(), 'admintheme', 'default');
$themeObjectName = $themeName."Theme";
$userid = get_userid();

if (file_exists(dirname(__FILE__)."/themes/${themeName}/${themeObjectName}.php"))
  {
    include(dirname(__FILE__)."/themes/${themeName}/${themeObjectName}.php");
    $themeObject = new $themeObjectName($gCms, $userid, $themeName);
  }
 else
   {
     $themeObject = new AdminTheme($gCms, $userid, $themeName);
   }

//$gCms->variables['admintheme']=&$themeObject;
if (isset($gCms->config['admin_encoding']) && $gCms->config['admin_encoding'] != '')
  {
    $themeObject->SendHeaders(isset($charsetsent), $gCms->config['admin_encoding']);
  }
 else
   {
     $themeObject->SendHeaders(isset($charsetsent), get_encoding('', false));
   }
$themeObject->PopulateAdminNavigation(isset($CMS_ADMIN_SUBTITLE)?$CMS_ADMIN_SUBTITLE:'');

$themeObject->DisplayDocType();
$themeObject->DisplayHTMLStartTag();
//$themeObject->DisplayHTMLHeader(false, isset($headtext)?$headtext:'');
$themeObject->DisplayBodyTag();
//$themeObject->DoTopMenu();
$themeObject->DisplayMainDivStart();

$opts = array('left'=>50,'top'=>50,'width'=>300,'height'=>25,
	      'min'=>1,'max'=>100);

$pg1 = new HTML_Progress2_Lite($opts);
$pg1->addLabel('text','txt1',$this->Lang('progress'));
$smarty->assign('progressbar',$pg1->toHtml());
echo $this->ProcessTemplate('importcsv.tpl');
$pg1->show();

$themeObject->DisplayMainDivEnd();
//$themeObject->OutputFooterJavascript();
//$themeObject->DisplayFooter();
// end theme



// begin work
$config = $gCms->GetConfig();
$fn = $config['root_path'].'/tmp/cache/'.$params['filename'];
$the_filesize = filesize($fn);
$fh = fopen($fn,'r');
if( !$fh )
  {
    echo $this->Lang('error_fileopen');
    return;
  }

$imageHandler = new importImageHandler($this);
$imageHandler->setSourceLocation(cms_join_path($config['uploads_path'],$params['imagepath']));
$imageHandler->setDestinationBase(cms_join_path($config['uploads_path'],$this->GetName()));
$imageHandler->setUniqueNames();

$importer = new productsCsvImporter($this);
$importer->setImageHandler($imageHandler);
$importer->setDelim($params['delimiter']);
$importer->setPolicyValue('create_fields',(int)$params['createfields']);
$importer->setPolicyValue('handle_images',(int)$params['handleimages']);
$importer->setPolicyValue('create_hierarchy',(int)$params['createhierarchy']);
$importer->setPolicyValue('create_categories',(int)$params['createcategories']);
$importer->setPolicyValue('image_source_location',$params['imagepath']);
$importer->setPolicyValue('skip_existing_products',$params['duplicateproducts'] == 'skip');

$batchlines = 0;
if( $fpos > 0 )
  {
    // process the first line again.
    $line = $importer->get_unparsed_record($fh);
    $line = trim($line);
    if( empty($line) ) continue;
    $res = $importer->handleLine($line);

    // seek to our old position.
    fseek($fh,$fpos);
  }
while( !feof($fh) && $batchlines < $batchsize )
  {
    $line = $importer->get_unparsed_record($fh);
    $line = trim($line);
    $linenum++;
    $batchlines++;
    $pos = ftell($fh);
    $pg1->moveStep((int)($pos / $the_filesize * 100.0));
    if( empty($line) ) continue;

    $res = $importer->handleLine($line);
  }
if( !feof($fh) )
  {
    // ready for the next batch

    // save any errors

    // save our position
    $pos = ftell($fh);
    fclose($fh);
    $_SESSION['products_import_data']['fpos'] = $pos;
    $_SESSION['products_import_data']['linenum'] = $linenum;
    
    // now redirect
    $parms = array();
    $parms['disable_buffer'] = 1;
    $parms['disable_theme'] = 1;
    $url = $this->CreateURL($id,'importcsv','',$parms);
    redirect(cge_url::current_url());
  }

// woot, we're done
fclose($fh);

$errors = $importer->getErrors();
if( is_array($errors) )
  {
    foreach( $errors as $one )
      {
	echo "$one<br/>";
      }
  }

#
# EOF
#
?>