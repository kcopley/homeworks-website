{*
#CMS - CMS Made Simple
#(c)2004-6 by Ted Kulp (ted@cmsmadesimple.org)
#This project's homepage is: http://cmsmadesimple.org
#
#This program is free software; you can redistribute it and/or modify
#it under the terms of the GNU General Public License as published by
#the Free Software Foundation; either version 2 of the License, or
#(at your option) any later version.
#
#This program is distributed in the hope that it will be useful,
#but WITHOUT ANY WARRANTY; without even the implied warranty of
#MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#GNU General Public License for more details.
#You should have received a copy of the GNU General Public License
#along with this program; if not, write to the Free Software
#Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
#
#$Id$	
*}
{literal}
<script type="text/javascript">
jQuery(document).ready(function(){
 jQuery('.fancybox').fancybox();
});
</script>
{/literal}

{$startform}
{if isset($compid)}
	<div class="pageoverflow">
		<p class="pagetext">{$idtext}:</p>
		<p class="pageinput">{$compid}</p>
	</div>
{/if}
	<div class="pageoverflow">
		<p class="pagetext">*{$nametext}:</p>
		<p class="pageinput">{$inputname}</p>
	</div>
	<div class="pageoverflow">
		<p class="pagetext">{$mod->Lang('url_alias')}:</p>
		<p class="pageinput">{$inputalias}</p>
	</div>
	<div class="pageoverflow">
		<p class="pagetext">{$mod->Lang('sku')}:</p>
		<p class="pageinput">{$inputsku}</p>
	</div>
	<div class="pageoverflow">
		<p class="pagetext">{$pricetext}:</p>
		<p class="pageinput">{$currency_symbol}{$inputprice}<br/>
	          {$mod->Lang('info_decimal_units')}</p>
	</div>
	<div class="pageoverflow">
		<p class="pagetext">{$weighttext} ({$weightunits}):</p>
		<p class="pageinput">{$inputweight}<br/>
                  {$mod->Lang('info_decimal_units')}</p>
	</div>
	<div class="pageoverflow">
		<p class="pagetext">{$statustext}:</p>
		<p class="pageinput">{$inputstatus}</p>
	</div>
	<div class="pageoverflow">
		<p class="pagetext">{$detailstext}:</p>
		<p class="pageinput">{$inputdetails}</p>
	</div>

	{* the hierarchy stuff *}
	{if count($hierarchy_items)}
	<div class="pageoverflow">
          <p class="pagetext">{$mod->Lang('hierarchy_position')}:</p>
          <p class="pageinput">{$mod->CreateInputDropdown($actionid,'hierarchy',$hierarchy_items,'-1',$hierarchy_pos)}</p>
	                  
	</div>
        {/if}

	{* taxable? *}
	<div class="pageoverflow">
		<p class="pagetext">{$taxabletext}:</p>
	        <p class="pageinput">{$inputtaxable}</p>
	</div>
	
        {* categories *}
        {if isset($input_categories)}
        <div class="pageoverflow">
           <p class="pagetext">{$mod->Lang('categories')}</p>
           <p class="pageinput">{$input_categories}</p>
        </div>
        {/if}

	{* display custom fields *}
	{if $customfieldscount gt 0}
		{foreach from=$customfields item=customfield}
			<div class="pageoverflow">
				<p class="pagetext">{if isset($customfield->prompt)}{$customfield->prompt}{else}{$customfield->name}{/if}:</p>
				<p class="pageinput">
                                  {if isset($customfield->value)} 
                                    {if $customfield->type == 'image' && isset($customfield->image) && isset($customfield->thumbnail)}
                                    <a href="{$customfield->image}" class="fancybox"><img src="{$customfield->thumbnail}" alt="{$customfield->value}"/></a>
                                    {elseif $customfield->type != 'textarea' && $customfield->type != 'dimensions'}{$mod->Lang('current_value')}:&nbsp;{$customfield->value}<br/>
                                    {/if}

                                    {if isset($customfield->delete)}{$mod->Lang('delete')}&nbsp;{$customfield->delete}<br/>{/if}
                                  {/if}         
                                  {if isset($customfield->hidden)}{$customfield->hidden}{/if}{$customfield->input_box}
                                  {if isset($customfield->attribute)}<br/>{$customfield->attribute}{/if}
                                </p>
			</div>
		{/foreach}
	{/if}

	<div class="pageoverflow">
		<p class="pagetext">&nbsp;</p>
		<p class="pageinput">{$hidden}{$submit}{$cancel}</p>
	</div>
{$endform}
