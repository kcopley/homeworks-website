<?php
$lang['add'] = 'Přidat';
$lang['addcategory'] = 'Přidat kategorii';
$lang['addfielddef'] = 'Přidat pole';
$lang['addproduct'] = 'Přidat produkt';
$lang['add_attrib_set'] = 'Přidat vlastnost';
$lang['add_hierarchy_item'] = 'Add Hierarchy Item';
$lang['add_option'] = 'Přidat hodnotu vlastnosti';
$lang['admin_only'] = 'M&aacute; tuto vlastnost vidět pouze spr&aacute;vce?';
$lang['allcategories'] = 'V&scaron;echny kategorie';
$lang['areyousure'] = 'Jste si jisti?';
$lang['areyousure_deleteproduct'] = 'Are you sure you really want to delete this product, and all of the accompanying information for it?';
$lang['ascending'] = 'Vzestupne';
$lang['attribute_name'] = 'N&aacute;zev vlastnosti';
$lang['byhierarchytemplates'] = 'Hierarchy Report Templates';
$lang['byhierarchytemplate_addedit'] = 'Add/Edit Hierarchy Report Template';
$lang['cancel'] = 'Zru&scaron;it';
$lang['categories'] = 'Kategorie';
$lang['category'] = 'Kategorie';
$lang['categoryexists'] = 'Kategorie s t&iacute;mto n&aacute;zvem už existuje';
$lang['categorylisttemplates'] = 'CategoryList Templates';
$lang['categorylisttemplate_addedit'] = 'Add/Edit A CategoryList Template';
$lang['changelog'] = '<ul>
<li>Version 1.0 - December, 2007
   <p>Initial Release</p>
</li>
<li>Version 1.0.1 - January, 2008
   <ul>
     <li>Adds pagination in summary view</li>
     <li>Fixes a problem with duplicate links in summary view</li>
   </ul>
</li>
<li>Version 2.0 - April, 2008
  <ul>
     <li>Adds hierarchical categories as well as the original &#039;tag&#039; based categories.<br/>
         Hierarchical categories can have an image as well as a description.</li>
     <li>Adds more custom field types (image, dropdown)<br/>
         Including thumbnail generation.</li>
     <li>Remove the original image fields</li>
     <li>Adds Search module capability</li>
     <li>Adds a hierarchical category view with drill down support</li>
     <li>Much, much more</li>
  </ul>
</li>
<li>Version 2.0.1 - April, 2008
  <ul>
     <li>Fixes numerous small errors.</li>
     <li>Fix to allow spaces and other characters in hierarchy names</li>
  </ul>
</li>
</ul>';
$lang['checkbox'] = 'Checkbox';
$lang['confirm_delete_hierarchy_node'] = 'Are you sure you want to delete this node?  Any products that are using this node will be set to no hierarchy';
$lang['createddate'] = 'Datum vytvořen&iacute;';
$lang['current_value'] = 'Aktu&aacute;ln&iacute; datum';
$lang['defaulttemplates'] = 'V&yacute;choz&iacute; &scaron;ablona';
$lang['default_template_notice'] = '<strong>Note:</strong> The contents of these text areas are used to determine the default content of templates when you click &quot;Add Template&quot; in the appropriate template tab.  Editing one of these text areas will have no immediate effect on your website.';
$lang['delete'] = 'Vymazat';
$lang['delete_attribset'] = 'Zru&scaron;it vlastnost';
$lang['delete_hierarchy_item'] = 'Delete Hierarchy Item';
$lang['descending'] = 'Sestupně';
$lang['description'] = 'Popis';
$lang['details'] = 'Detaily';
$lang['detailtemplates'] = '&Scaron;ablona detailu';
$lang['detailtemplate_addedit'] = 'Přidat/Upravit &scaron;ablonu detailu';
$lang['disabled'] = 'Neaktivn&iacute;';
$lang['draft'] = 'N&aacute;vrh';
$lang['dropdown'] = 'Dropdown';
$lang['dropdown_options'] = 'Dropdown Options <em>(applicable only for dropdown fields)</em><br/><br/><em>(one entry per line)</em>';
$lang['edit'] = 'Edit';
$lang['edit_attribsets'] = 'Edit Attribute Sets';
$lang['edit_attribsets_for'] = 'Edit Attribute Sets For';
$lang['edit_attributes'] = 'Edit Attributes';
$lang['edit_hierarchy_item'] = 'Edit Hierarchy Item';
$lang['error_attribset_exists'] = 'An attribute with that name already exists';
$lang['error_invalidparent'] = 'Invalid Parent (a hierarchy node cannot be it&#039;s own parent)';
$lang['error_invalid_name'] = 'The custom field value entered is invalid (contains non alphanumeric characters)';
$lang['error_missingparam'] = 'A required parameter is missing or invalid';
$lang['error_product_nameused'] = 'Produkt s t&iacute;mto n&aacute;zvem už existuje';
$lang['error_price_adjustment'] = 'Invalid price adjustment value for item %s';
$lang['error_upload_failed'] = 'Upload failed: %s';
$lang['field'] = 'Pole';
$lang['fielddef'] = 'Definice pole';
$lang['fielddefs'] = 'Definice pol&iacute;';
$lang['file'] = 'Soubor';
$lang['filters'] = 'Filtry';
$lang['firstpage'] = '<<';
$lang['help'] = '<h3>What does this do?</h3>
<p>This module provides a way to collect, organize, and display information about products and their categories.  This module can stand on its own, or be part of an e-commerce solution if you wish.  The module supports a product existing in more than one category, supports product weight, price, and attribute sets.  Each item in an attribute set can have a price adjustment.  Also a global flag indicates wether the product is &#039;taxable&#039; or not.</p>
  <p>This module provides for up to two images per product, custom field definitions, and for the ability to descriminate private from public data.</p>
<h3>Features:</h3>
<ul>
  <li>Numerous Views</li>
    <ul>
      <li>Summary view</li>
      <li>Category List View</li>
      <li>Detail view</li> 
      <li>Hierarchy Drill-down View</li>
    </ul>
  <li>Entirely Database Template Driven</li>
  <p>Numerous templates of each type can be defined, defaults specified for each type, and the template used for each particular view can be specified as a parameter</p>
  <li>Sorting and pagination in the summary view</li>
  <li>Products can exist in multiple categories</li>
  <li>Numerous custom fields can be defined, including images</li>
  <li>Automatic thumbnail creation</li>
  <li>Admin defined fields</li>
  <li>Product attributes (including price adjustment)</li>
  <li>Weight and taxable fields (for use in e-commerce solutions)</li>
  <li>Products can have a status (published or draft) in order to hide products from public display at any time</li>
  <li>Pretty URL Support</li>
  <li><strong>More...</strong></li>
</ul>

<h3>How Do I Use It:</h3>
<ul>
<li>Set preferences</li>
<p>Usually this is just a formality.  You should specify your default weight units, and your default currency symbol for your local environment.</p>
<li>Setup users and permissions</li>
<p>This module uses the &#039;Modify Templates&#039; permission to allow users to be able to modify the various templates that can be used by this module.  As well, the &#039;Modify Site Preferences&#039; permission is needed to be able to adjust the modules preferences.  In order to have the ability to modify field definitions, categories, or products, your users will need the &#039;Modify Products&#039; permission.</p>
<li>(optional) Define one or more categories</li>
<p>This is an optional step, but you will probably want to do this early to prevent having to go back to modify each and every product later.  Adding a category is as simple as clicking on the &#039;Categories&#039; tab in the Product Managers admin section, and clicking &#039;Add A Category&#039;.  From there you will be prompted for a category name.</p>
<li>(optional) Define one or more custom fields</li>
<p>This is an optional step.  Custom fields are not needed if this module suits you exactly as it is.  However if you would like to store additional information for each of your products you can create custom fields.  Creating custom fields is similar to creating categories, except that more data is requested.</p>
<p>Currently only three types of custom fields are available: &#039;textbox&#039;,&#039;checkbox&#039;,and &#039;textarea, dropdown, and file&#039;.  Select one of these, give your new field a name, and indicate wether that field should be visible to the public in the various views.  Then hit submit</p>
<li>Create one or more products</li>
<p>You should create one or more products to display in your website content.  Give each product a name, a price (optional) a weight (optional), a status (draft or published), and some information.  Additionally, you can specify if the product is taxable, and it&#039;s weight.</p>
<p>If categories are defined, then a checkbox will appear for each defined category.  You can simply click the checkbox fields for each category that this product should belong to.</p>
<p>If custom fields are defined, then input fields appropriate to the type of custom field will appear and allow you to specify data for each of them.  This data is optional.</p>
<li>Add the appropriate tags into your page templates or page content</li>
<p>This can be as simple as adding the <strong>{Products}</strong> tag into the content area of one of your pages, or into your page template.</p>
<p>If you wish to alter the behaviour of this application from its default, you can do that by adding one or more parameters to the <strong>{Products}</strong> tag.  The complete list of parameters and their behaviour is listed below.  But for example, to display a list of products in a single, existing category, you could use a tag like <code>{Products category=&#039;categoryname&#039;}</code></p>
</ul>
<h3>Support</h3>
<p>This module does not include commercial support.  However there are a number of resources available to help you with it:</p>
<ul>
<li>For the latest version of this module, FAQs or to file a bug report, please visit the cms made simple <a href=&quot;http://dev.cmsmadesimple.org&quot;>Developers Forge</a> and do a search for &#039;Products&#039;</li>
<li>To obtain commercial support, please send an email to the author at <a href=&quot;mailto:calguy1000@cmsmadesimple.org&quot;>Robert Campbell</a>.</li>
<li>Additional discussion of this module may also be found in the <a href=&quot;http://forum.cmsmadesimple.org&quot;>CMS Made Simple Forms.</a></li>
<li>For some questions and limited technical support, the author can often be found in the <a href=&quot;irc://irc.freenode.net/#cms&quot;>CMS IRC Channel</a>.</li>
</ul>';
$lang['hierarchy'] = 'Hierarchy';
$lang['hierarchy_position'] = 'Hierarchy Position';
$lang['id'] = 'Id';
$lang['imagetext'] = 'Obr&aacute;zek';
$lang['include_children'] = 'Include Children?';
$lang['info_alnumonly'] = 'Only alphanumeric characters. No whitespace, quotes, or non letter/number combinations';
$lang['info_fieldproblems'] = 'Problems were encountered when dealing with one or more custom fields.  The action has completed successfully, however you should investigate and correct these problems';
$lang['info_price_adjustment'] = 'Valid values are +-*#.##';
$lang['info_publicfield'] = 'Public fields are available for the public to see';
$lang['label'] = 'Popis';
$lang['lastpage'] = '>>';
$lang['last_modified'] = 'Posledn&iacute; změna';
$lang['logotext'] = 'Druh&yacute; obr&aacute;zek';
$lang['maxlength'] = 'Maximum length <em>(applicable only on textbox fields)</em>';
$lang['modifieddate'] = 'Změněno dne';
$lang['module_description'] = 'A module for managing a catalog of products, their price, images, etc, and for allowing users to build a cart';
$lang['name'] = 'N&aacute;zev';
$lang['needpermission'] = 'Nem&aacute;te požadovan&eacute; opr&aacute;vněn&iacute;: %s';
$lang['nextpage'] = '>';
$lang['nonamegiven'] = 'No name was supplied';
$lang['none'] = 'None';
$lang['of'] = 'z';
$lang['page'] = 'Str&aacute;nka';
$lang['page_of'] = 'Str&aacute;nka %s z %s';
$lang['page_limit'] = 'Page Limit';
$lang['param_action'] = 'Specify the behaviour of the module.  Possible values are:
<ul>
  <li>hierarchy -- Display a product list by hierarchy</li>
  <li>categorylist - Display a list of product categories</li>
  <li><em>default</em> - Display a summary of products</li>
  <li>detail - Display a detail view of a single product</li>
</ul>';
$lang['param_category'] = 'When used in the <em>default</em> summary mode, this parameter, which should match the name of an existing category will be used to display only those companies that are in that categoory. (a comma seperated list can be supplied)';
$lang['param_categorylisttemplate'] = 'Useful only with the categorylist action, this parameter indicates the template that should be used for the display.  If no value is specified then the current default template of that type is used.';
$lang['param_detailpage'] = 'This specifies the pageid or alias that should be used to display the detail report.';
$lang['param_detailtemplate'] = 'This parameter specifies the template that should be used for the detail mode display.  If no value is specified, then the current default template of that type is used.';
$lang['param_hierarchy'] = 'Useful only in the <em>default</em> action, this parameter specifies that only items in that location of the hierarchy should be displayed.  Wildcard character can be used so that you can specify parent/child relationships as well.  i.e {Products hierarchy=&#039;trucks*&#039;} will create a summary report of all trucks, including the children in the hierarchy.  Similarly, {Products hierarchy=&#039;*trucks&#039;} will display a summary report of all trucks, and all products beloging to any parent in the hierarchy.';
$lang['param_hierarchytemplate'] = 'This parameter specifies the template that should be used for the hierarchy display. If no value is specified, then the current default template of that type is used.';
$lang['param_pagelimit'] = 'Useful only with the <em>default</em> action, this parameter specifies how many items should be listed on each page.';
$lang['param_parent'] = 'Useful only in the <em>hierarchy</em> action, this parameter specifies the hierarchy id of the starting location of the report.';
$lang['param_productid'] = 'Useful only with the detail action, this parameter indicates what product to use for the display.';
$lang['param_sortorder'] = 'Specify the order of sorted fields in summary view.  Possible values are:
<ul>
  <li><strong>asc</strong> -- Ascending order</li>
  <li>desc -- Descending order</li>
</ul>';
$lang['param_sortby'] = 'Applicable only in summary mode, this parameter determines the sorting of the output companies.  Possible values are:
<ul>
  <li><strong>product_name</strong></li>
  <li>price -- The product base price</li>
  <li>status -- Draft or published</li>
  <li>random -- Display in random order on each request</li>
  <li>created -- The creation date for this company record</li>
  <li>modified -- The modified date for this company record</li>
</ul>';
$lang['param_summarytemplate'] = 'This parameter specifies the template that should be used for the summary mode display.  If no value is specified, then the current default template of that type is used.';
$lang['parent'] = 'Parent';
$lang['postinstall'] = 'The Products module has been successfully installed';
$lang['postuninstall'] = 'The Products module has been removed along with all associated data';
$lang['preferences'] = 'Preferences';
$lang['preuninstall'] = 'Removing this module will also remove all associated data, are you sure you want to proceed?';
$lang['price'] = 'Cena';
$lang['price_adjustment'] = 'Nastaven&iacute; ceny';
$lang['prevpage'] = '<';
$lang['product'] = 'Produkt';
$lang['productname'] = 'N&aacute;zev produktu';
$lang['products'] = 'Produkty';
$lang['product_hierarchy'] = 'Product Hierarchy';
$lang['product_manager'] = 'Product Manager';
$lang['prompt'] = 'Prompt';
$lang['prompt_allowed_filetypes'] = 'Allow upload of files with these extensions (for file type fields)';
$lang['prompt_allowed_imagetypes'] = 'Allow upload of images with these extensions (for image type fields)';
$lang['prompt_autothumbnail'] = 'Automatically create thumbnails of images';
$lang['prompt_applicable'] = 'Enable by default for all new products';
$lang['prompt_currencysymbol'] = 'Symbol měny';
$lang['prompt_deleteproductfiles'] = 'Delete any files associated with the product when the product is deleted?';
$lang['prompt_detailpage'] = 'Default Detail Page';
$lang['prompt_taxrate'] = 'V&yacute;&scaron;e daně';
$lang['prompt_summarysortorder'] = 'Default Sort Order in summary mode';
$lang['prompt_summarysorting'] = 'Default Sort Field in summary mode';
$lang['prompt_tax1'] = 'Prvn&iacute; daň';
$lang['prompt_tax2'] = 'Druh&aacute; daň';
$lang['prompt_taxname'] = 'N&aacute;zev daně';
$lang['prompt_template'] = 'Template Source';
$lang['prompt_weightunits'] = 'Jednotka v&aacute;hy';
$lang['public'] = 'Is this a public field?';
$lang['published'] = 'Zveřejněno';
$lang['random'] = 'Random';
$lang['resettofactory'] = 'Reset to Factory Values';
$lang['root'] = 'Root';
$lang['select_one'] = 'Select One';
$lang['sort_by'] = 'Řazeno podle';
$lang['sort_order'] = 'Sort Order';
$lang['status'] = 'Status';
$lang['submit'] = 'Submit';
$lang['summarytemplates'] = '&Scaron;ablona přehledu';
$lang['summarytemplate_addedit'] = 'Add/Edit A Summary Template';
$lang['taxable'] = 'Zdaniteln&eacute;';
$lang['tax_module'] = 'Tax Module';
$lang['textarea'] = 'Text Area';
$lang['textbox'] = 'Text Input';
$lang['title_byhierarchy_dflttemplate'] = 'Default New Hierarchy Report Template';
$lang['title_categorylist_dflttemplate'] = 'Default New CategoryList Template';
$lang['title_detail_dflttemplate'] = 'Nov&aacute; v&yacute;choz&iacute; &scaron;ablona detailu';
$lang['title_summary_dflttemplate'] = 'Nov&aacute; v&yacute;choz&iacute; &scaron;ablona přehledu';
$lang['type'] = 'Typ';
$lang['update'] = 'Aktualizovat';
$lang['upload'] = 'Vyberte soubor k nahr&aacute;n&iacute;';
$lang['value'] = 'Hodnota';
$lang['values'] = 'Hodnoty';
$lang['weight'] = 'V&aacute;ha';
$lang['utma'] = '156861353.1812446927.1201250230.1210930608.1210936714.29';
$lang['utmz'] = '156861353.1210539405.25.18.utmccn=(referral)|utmcsr=elektrokomplex.com|utmcct=/cms/admin/addcontent.php|utmcmd=referral';
$lang['utmc'] = '156861353';
$lang['utmb'] = '156861353';
?>