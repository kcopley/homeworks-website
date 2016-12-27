<h3>{$mod->Lang('edit_category_fields',$category.name)}</h3>
{if isset($fields) && count($fields)}
<div class="pageoverflow">
<table cellspacing="0" class="pagetable">
  <thead>
    <tr>
      <th>{$mod->Lang('name')}</th>
      <th>{$mod->Lang('type')}</th>
      <th>{$mod->Lang('value')}</th>
      <th class="pageicon">&nbsp;</th>
      <th class="pageicon">&nbsp;</th>
      <th class="pageicon">&nbsp;</th>
      <th class="pageicon">&nbsp;</th>
    </tr>
  </thead>
  <tbody>
  {foreach from=$fields item='onefield'}
     {cycle values="row1,row2" assign='rowclass'}
     <tr class="{$rowclass}" onmouseover="this.className='{$rowclass}hover';" onmouseout="this.className='{$rowclass}';">
       <td>{$onefield.field_name}</td>
       <td>{$onefield.field_type}</td>
       <td>{$onefield.field_value|strip_tags|summarize:10}</td>
       <td>{$onefield.move_up}</td>
       <td>{$onefield.move_down}</td>
       <td>{$onefield.editlink}</td>
       <td>{$onefield.deletelink}</td>
     </tr>
  {/foreach}
  </tbody>
</table>
</div>
{/if}

<p class="pageoverflow">
  {$addlink}&nbsp;{$return_link}
</p>