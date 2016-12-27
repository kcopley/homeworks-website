<h3>{$mod->Lang('add_category_field',$category.name)}</h3>

{$formstart}
<div>{$hidden1}</div>
<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('type')}:</p>
  <p class="pageinput">{$input_fieldtype}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('name')}:</p>
  <p class="pageinput">{$input_fieldname}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('prompt')}:</p>
  <p class="pageinput">{$input_fieldprompt}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('value')}:</p>
  <p class="pageinput">
  {if $fieldtype == 'file' || $fieldtype == 'image'}
    {if isset($input_hidden)}<div>{$input_hidden}</div>{/if}
    {$mod->Lang('current_value')}:&nbsp;{$fieldvalue}
    {if isset($fileexists)}&nbsp;{$mod->Lang('valid')}{else}<em>({$mod->Lang('error_filenotfound')})</em>{/if}
    <br/>
  {/if}
  {$input_fieldvalue}
  {if $fieldtype == 'image' && isset($input_watermarklocation)}
    <br/>
    {$prompt_watermarklocation}:&nbsp;{$input_watermarklocation}
  {/if}
  </p>
</div>
<div class="pageoverflow">
  <p class="pagetext">&nbsp;</p>
  <p class="pageinput">{$submit}{$cancel}</p>
</div>
{$formend}