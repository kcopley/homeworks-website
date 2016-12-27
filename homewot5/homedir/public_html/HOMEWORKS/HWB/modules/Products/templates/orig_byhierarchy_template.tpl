{* hierarchy report template *}
{if !isset($hdepth) && isset($hierarchy_item)}
<h3>Hierarchy Data for {$hierarchy_item.name} ({$hierarchy_item.id})</h3>
{/if}

{if !isset($hdepth)}{assign var='hdepth' value='0'}{/if}
{*
 // create a nested set of unordered lists 
 // if the active_hierarchy smarty variable exists
 // and matches the current hierarchy id
 // the active class will be given
 // to the ul.  You may want to modify your summary template
 // to set this variable
*}
<ul class="products_hierarchy_level{$hdepth}">
{foreach from=$hierdata key='key' item='item'}
{strip}
  <li {if isset($active_hierarchy) and $item.id == $active_hierarchy} class="active"{/if}>
  {if $item.count gt 0}
     <a href="{$item.url}">{$item.name} ({$item.count})</a>
  {else}
     {$item.name} ({$item.count})
  {/if}
  
  {if isset($item.children) }
    {* there are children call this template again *}
    {include file=$smarty.template hierdata=$item.children hdepth=$hdepth+1}
  {/if}
  
  </li>
{/strip}
{/foreach}
</ul>
