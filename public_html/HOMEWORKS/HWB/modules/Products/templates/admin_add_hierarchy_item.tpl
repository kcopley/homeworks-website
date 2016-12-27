{if isset($hierarchy_id)}
<h3>{$mod->Lang('edit_hierarchy_item')}</h3>
{else}
<h3>{$mod->Lang('add_hierarchy_item')}</h3>
{/if}

{$formstart}
<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('name')}</p>
  <p class="pageinput"><input type="text" name="{$actionid}name" value="{$name}" size="50" maxlength="255"></p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('parent')}</p>
  <p class="pageinput">{$mod->CreateInputDropdown($actionid,'parent',$hierarchy_items,'-1',$parent)}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('description')}</p>
  <p class="pageinput">{$mod->CreateTextArea('true',$actionid,$description,'description')}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('imagetext')}</p>
  <p class="pageinput">
     {if isset($image) && !empty($image) && $image != '0'}{$mod->Lang('current_value')}:&nbsp;{$image}<br/>
       {$mod->Lang('delete')}:<input type="checkbox" name="{$mod->GetActionId()}deleteimg" value="1"><br/>
     {/if}
     <input type="file" name="{$actionid}file" size="50" maxlength="255">
     {if isset($watermark_location)}
       <br/>
       {$mod->Lang('watermark_location')}:&nbsp;{$watermark_location}
     {/if}
  </p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('extra1')}</p>
  <p class="pageinput"><input type="text" name="{$actionid}extra1" value="{$extra1}" size="50" maxlength="255"></p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('extra2')}</p>
  <p class="pageinput"><input type="text" name="{$actionid}extra2" value="{$extra2}" size="50" maxlength="255"></p>
</div>

<div class="pageoverflow">
  <p class="pagetext">&nbsp;</p>
  <p class="pageinput"><input type="submit" name="{$actionid}submit" value="{$mod->Lang('submit')}">&nbsp;<input type="submit" name="{$actionid}cancel" value="{$mod->Lang('cancel')}"></p>
</div>
{$formend}