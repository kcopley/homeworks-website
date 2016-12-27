{* search template *}
{* valid fields are:
   {$actionid}cd_submit    - (string) for a submit button
   {$actionid}cd_cancel    - (string) for a cancel button
   {$actionid}cd_prodname  - (string) for text field to search against product name
   {$actionid}cd_proddesc  - (string) for text field to search against product description.
   {$actionid}cd_propprice - (select) for price searching.
     options must be of type string with high low limits separated by a :
     i.e:   1000:2000
     a special value of -1 can be used to indicate any price.
   {$actionid}cd_allany    - (int) to indicate wether all of the 
     conditions much match, or if any one of them may.
   {$actionid}cd_propvalue - (string) property values.
*}

<div id="prod_searchform">
{$formstart}

<div class="row">
  <p class="row_prompt">{$mod->Lang('search_expr')}:</p>
  <p class="row_input">
    <select name="{$actionid}cd_allany">
      <option value="0">{$mod->Lang('all')}</option>
      <option value="1">{$mod->Lang('any')}</option>
    </select>
  </p>
</div>

<div class="row">
  <p class="row_prompt">{$mod->Lang('search_name')}:</p>
  <p class="row_input">
    <input type="text" name="{$actionid}cd_prodname" size="40" maxlength="255"/>
  </p>
</div>

<div class="row">
  <p class="row_prompt">{$mod->Lang('search_description')}:</p>
  <p class="row_input">
    <input type="text" name="{$actionid}cd_proddesc" size="40" maxlength="255"/>
  </p>
</div>

<div class="row">
  <p class="row_prompt">{$mod->Lang('search_price')}:</p>
  <p class="row_input">
    <select name="{$actionid}cd_prodprice">
      <option value="-1">{$mod->Lang('any')}</option>
      <option value="0:99.99">Less Than $100</option>
      <option value="100:999.99">$100 to $1000</option>
      <option value="1000:9999.99">$1000 to $10000</option>
      <option value="10000:9999999">Greater than $10000</option>
    </select>
  </p>
</div>

{if isset($searchprops)}
{foreach from=$searchprops key='propname' item='obj'}
<div class="row">
  <p class="row_prompt">{$obj->prompt}:</p>
  <p class="row_input">
    {if $obj->type == 'text'}
      <input type="text" name="{$actionid}cd_propvalue[{$propname}]" size="40" maxlength="40"/>
    {else if $obj->type == 'dropdown'}
      <select name="{$actionid}cd_propvalue[{$propname}]">
      {html_options options=$obj->options}
      </select>
    {/if}
  </p>
</div>
{/foreach}
{/if}

<div class="row">
  <p class="row_prompt"></p>
  <p class="row_input">
    <input type="submit" name="{$actionid}cd_submit" value="{$mod->Lang('submit')}"/>
    <input type="submit" name="{$actionid}cd_cancel" value="{$mod->Lang('cancel')}"/>
  </p>
</div>


{$formend}
</div>{* prod_searchform *}