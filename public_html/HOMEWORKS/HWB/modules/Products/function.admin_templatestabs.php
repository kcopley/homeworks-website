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
if( !isset($gCms) ) exit();

if( $this->CheckPermission( 'Modify Templates' ) )
{
  echo $this->StartTab('summary_template', $params);
  {
    echo $this->ShowTemplateList($id,$returnid,'summary_',
				 PRODUCTS_PREF_NEWSUMMARY_TEMPLATE,
				 'summary_template',
				 PRODUCTS_PREF_DFLTSUMMARY_TEMPLATE,
				 $this->Lang('summarytemplate_addedit'));
  }
  echo $this->EndTab();
	

  echo $this->StartTab('detail_template', $params);
  {
    echo $this->ShowTemplateList($id,$returnid,'detail_',
				 PRODUCTS_PREF_NEWDETAIL_TEMPLATE,
				 'detail_template',
				 PRODUCTS_PREF_DFLTDETAIL_TEMPLATE,
				 $this->Lang('detailtemplate_addedit'));
  }
  echo $this->EndTab();


  echo $this->StartTab('byhierarchy_template', $params);
  {
    echo $this->ShowTemplateList($id,$returnid,'byhierarchy_',
				 PRODUCTS_PREF_NEWBYHIERARCHY_TEMPLATE,
				 'byhierarchy_template',
				 PRODUCTS_PREF_DFLTBYHIERARCHY_TEMPLATE,
				 $this->Lang('byhierarchytemplate_addedit'));
  }
  echo $this->EndTab();


  echo $this->StartTab('categorylist_template', $params);
  {
    echo $this->ShowTemplateList($id,$returnid,'categorylist_',
				 PRODUCTS_PREF_NEWCATEGORYLIST_TEMPLATE,
				 'categorylist_template',
				 PRODUCTS_PREF_DFLTCATEGORYLIST_TEMPLATE,
				 $this->Lang('categorylisttemplate_addedit'));
  }
  echo $this->EndTab();


  echo $this->StartTab('search_template', $params);
  {
    echo $this->ShowTemplateList($id,$returnid,'search_',
				 PRODUCTS_PREF_NEWSEARCH_TEMPLATE,
				 'search_template',
				 PRODUCTS_PREF_DFLTSEARCH_TEMPLATE,
				 $this->Lang('searchtemplate_addedit'));
  }
  echo $this->EndTab();


  echo $this->StartTab('default_templates');
  {
    $input_summarytemplate =
      $this->GetPreference(PRODUCTS_PREF_NEWSUMMARY_TEMPLATE);
    $input_detailtemplate =
      $this->GetPreference(PRODUCTS_PREF_NEWDETAIL_TEMPLATE);
    $input_byhierarchytemplate =
      $this->GetPreference(PRODUCTS_PREF_NEWBYHIERARCHY_TEMPLATE);
    $input_categorylisttemplate =
      $this->GetPreference(PRODUCTS_PREF_NEWCATEGORYLIST_TEMPLATE);
    $input_searchtemplate =
      $this->GetPreference(PRODUCTS_PREF_NEWSEARCH_TEMPLATE);

    $smarty->assign('info_dflt_template',
		    $this->Lang('default_template_notice'));

    $smarty->assign('formstart',
		    $this->CreateFormStart($id,'admin_setdflttemplates',
					   $returnid));
    $smarty->assign('formend',
		    $this->CreateFormEnd());

    $smarty->assign('legend_summarytemplate',
		    $this->Lang('title_summary_dflttemplate'));
    $smarty->assign('prompt_summarytemplate',
		    $this->Lang('prompt_template'));
    $smarty->assign('input_summarytemplate',
		    $this->CreateTextArea(false,$id,$input_summarytemplate,
					  'input_summarytemplate'));
    $smarty->assign('submit_summarytemplate',
		    $this->CreateInputSubmit($id,'submit_summarytemplate',
					     $this->Lang('submit')));
    $smarty->assign('reset_summarytemplate',
		    $this->CreateInputSubmit($id,'reset_summarytemplate',
					     $this->Lang('resettofactory')));

    $smarty->assign('legend_detailtemplate',
		    $this->Lang('title_detail_dflttemplate'));
    $smarty->assign('prompt_detailtemplate',
		    $this->Lang('prompt_template'));
    $smarty->assign('input_detailtemplate',
		    $this->CreateTextArea(false,$id,$input_detailtemplate,
					  'input_detailtemplate'));
    $smarty->assign('submit_detailtemplate',
		    $this->CreateInputSubmit($id,'submit_detailtemplate',
					     $this->Lang('submit')));
    $smarty->assign('reset_detailtemplate',
		    $this->CreateInputSubmit($id,'reset_detailtemplate',
					     $this->Lang('resettofactory')));


    $smarty->assign('legend_byhierarchytemplate',
		    $this->Lang('title_byhierarchy_dflttemplate'));
    $smarty->assign('prompt_byhierarchytemplate',
		    $this->Lang('prompt_template'));
    $smarty->assign('input_byhierarchytemplate',
		    $this->CreateTextArea(false,$id,$input_byhierarchytemplate,
					  'input_byhierarchytemplate'));
    $smarty->assign('submit_byhierarchytemplate',
		    $this->CreateInputSubmit($id,'submit_byhierarchytemplate',
					     $this->Lang('submit')));
    $smarty->assign('reset_byhierarchytemplate',
		    $this->CreateInputSubmit($id,'reset_byhierarchytemplate',
					     $this->Lang('resettofactory')));
    

    $smarty->assign('legend_categorylisttemplate',
		    $this->Lang('title_categorylist_dflttemplate'));
    $smarty->assign('prompt_categorylisttemplate',
		    $this->Lang('prompt_template'));
    $smarty->assign('input_categorylisttemplate',
		    $this->CreateTextArea(false,$id,$input_categorylisttemplate,
					  'input_categorylisttemplate'));
    $smarty->assign('submit_categorylisttemplate',
		    $this->CreateInputSubmit($id,'submit_categorylisttemplate',
					     $this->Lang('submit')));
    $smarty->assign('reset_categorylisttemplate',
		    $this->CreateInputSubmit($id,'reset_categorylisttemplate',
					     $this->Lang('resettofactory')));

    $smarty->assign('legend_searchtemplate',
		    $this->Lang('title_search_dflttemplate'));
    $smarty->assign('prompt_searchtemplate',
		    $this->Lang('prompt_template'));
    $smarty->assign('input_searchtemplate',
		    $this->CreateTextArea(false,$id,$input_searchtemplate,
					  'input_searchtemplate'));
    $smarty->assign('submit_searchtemplate',
		    $this->CreateInputSubmit($id,'submit_searchtemplate',
					     $this->Lang('submit')));
    $smarty->assign('reset_searchtemplate',
		    $this->CreateInputSubmit($id,'reset_searchtemplate',
					     $this->Lang('resettofactory')));
    
    echo $this->ProcessTemplate('dflt_templates.tpl');
  }
  echo $this->EndTab();

}

// EOF
?>