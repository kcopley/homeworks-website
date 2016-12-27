<h3>{$mod->Lang('prompt_add_attribset')}</h3>
<h4>{$product.product_name} ({$product.id}){if $product.sku != ''}&nbsp;&nbsp;{$mod->Lang('sku')}:&nbsp;{$product.sku}{/if}</h4>
{$formstart}
<div class="pageoverflow">
  <p class="pagetext">{$prompt_set_name}:</p>
  <p class="pageinput">{$input_set_name}</p>
</div>

{if count($values)}
<br/>
<div class="pageoverflow">
<table>
  <thead>
    <th>{$idtext}</th>
    <th>{$keytext}</th>
    <th>{$valuetext}<br/>{$info_valuetext}</th>
    <th>{$mod->Lang('sku')}</th>
  </thead>
  <tbody>
  {foreach from=$values item='onevalue'}
  <tr>
    <td>{$onevalue->idx}</td>
    <td>{$onevalue->key}</td>
    <td>{$onevalue->value}</td>
    <td>{$onevalue->sku}</td>
  </tr>
  {/foreach}
  </tbody>
</table>
</div>
<br/>
{/if}

<div class="pageoverflow">
  <p class="pagetext">{$prompt_add}</p>
  <p class="pageinput" border="0">
     {$input_update}
  </p>
</div>
<div class="pageoverflow">
  <p class="pagetext">&nbsp;</p>
  <p class="pageinput">{$submit}&nbsp;{$cancel}</p>
</div>
{$formend}