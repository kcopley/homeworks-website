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

<div>
{$formstart}
<fieldset style="width: 49%; float: left;">
<legend>{$mod->Lang('filters')}:&nbsp;</legend>
<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('hierarchy')}:</p>
  <p class="pageinput">{$input_hierarchy}&nbsp;{$mod->Lang('include_children')} {$input_children}</p>
</div>
{if isset($category_list)}
<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('category')}:</p>
  <p class="pageinput">
    <select name="{$actionid}categories[]" multiple="multiple" size="5">
      {html_options options=$category_list selected=$categories}
    </select>
  </p>
</div>
{/if}
<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('sort_by')}:</p>
  <p class="pageinput">{$input_sortby}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('sort_order')}:</p>
  <p class="pageinput">{$input_sortorder}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('page_limit')}:</p>
  <p class="pageinput">{$input_pagelimit}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">&nbsp;</p>
  <p class="pageinput"><input type="submit" name="{$mod->GetActionId()}submit" value="{$mod->Lang('submit')}"></p>
</div>
</fieldset>
<fieldset style="width: 47%; float: right;">
<legend>{$mod->Lang('view')}</legend>
{if isset($fields_viewable)}
<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('viewable_fields')}:</p>
  <p class="pageinput">
    <select name="{$actionid}custom_fields[]" size="3" multiple="multiple">
    {html_options options=$fields_viewable selected=$custom_fields}
    </select>
  </p>
</div>
<div class="pageoverflow">
  <p class="pagetext">&nbsp;</p>
  <p class="pageinput"><input type="submit" name="{$mod->GetActionId()}submit" value="{$mod->Lang('submit')}"></p>
</div>
{/if}
</fieldset>
<div style="clear: both;"></div>
{$formend}
</div>



{if $itemcount > 0}
<div class="pageoptions"><p class="pageoptions">{$addlink}</p></div>
{if isset($firstpage_url)}
 <a href="{$firstpage_url}" title="{$mod->Lang('firstpage')}">{$mod->Lang('firstpage')}</a>
 <a href="{$prevpage_url}" title="{$mod->Lang('prevpage')}">{$mod->Lang('prevpage')}</a>
{/if}
{if isset($firstpage_url) || isset($lastpage_url)}
  {$mod->Lang('page_of',$pagenumber,$pagecount)}
{/if}
{if isset($lastpage_url)}
 <a href="{$nextpage_url}" title="{$mod->Lang('nextpage')}">{$mod->Lang('nextpage')}</a>
 <a href="{$lastpage_url}" title="{$mod->Lang('lastpage')}">{$mod->Lang('lastpage')}</a>
{/if}
<table cellspacing="0" class="pagetable cms_sortable tablesorter">
	<thead>
		<tr>
			<th>{$idtext}</th>
			<th>{$producttext}</th>
	                <th>{$mod->Lang('sku')}</th>
			<th>{$pricetext}</th>
			<th>{$weighttext} ({$weight_units})</th>
			<th>{$mod->Lang('status')}</th>
			<th>{$mod->Lang('last_modified')}</th>
{if isset($custom_fields)}
{foreach from=$custom_fields item='fid'}
                        <th>{$fields_viewable.$fid}</th>
{/foreach}
{/if}
			<th class="pageicon {literal}{sorter: false}{/literal}">&nbsp;</th>
			<th class="pageicon {literal}{sorter: false}{/literal}">&nbsp;</th>
			<th class="pageicon {literal}{sorter: false}{/literal}">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
{foreach from=$items item=entry}
	{cycle values="row1,row2" assign='rowclass'}
		{*<tr class="{$rowclass}" onmouseover="this.className='{$rowclass}hover';" onmouseout="this.className='{$rowclass}';">*}
                <tr>
			<td>{$entry->id}</td>
			<td><a href="{$entry->edit_url}" title="{$mod->Lang('edit')}">{$entry->product_name}</a></td>
	                <td>{$entry->sku}</td>
			<td>{$entry->price|number_format:2}</td>
                        <td>{$entry->weight|number_format:2}</td>
			<td>{$mod->Lang($entry->status)}</td>
	                <td>{$entry->modified_date|cms_date_format}</td>
{if isset($custom_fields)}
{foreach from=$custom_fields item='fid'}
                        <td>{capture assign='tmp'}{$field_names.$fid}{/capture}{$entry->$tmp}</td>
{/foreach}
{/if}
			<td>{$entry->attribslink}</td>
			<td>{$entry->editlink}</td>
			<td>{$entry->deletelink}</td>
		</tr>
{/foreach}
	</tbody>
</table>
{/if}

<div class="pageoptions"><p class="pageoptions">{$addlink}&nbsp;{$importlink}</p></div>
