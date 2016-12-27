{* this is a sample product detail template *}
{assign var='products' value=$mod}
<div class="ProductDirectoryItem">

{if is_array($entry->breadcrumb)}
Breadcrumb:  {' >> '|implode:$entry->breadcrumb}<br/>
{/if}

Name: <a name="product_name" style="text-decoration: none;">{$entry->product_name}</a><br />
File Location: {$entry->file_location}<br/>


{if $entry->weight ne ''}
Weight {$weight_units}: {$entry->weight}<br />
{/if}

Breadcrumb: {$entry->breadcrumb}

{if $entry->details ne ''}
Details:<br />
{$entry->details}<br />
{/if}

{* uncomment the following line if the Promotions module is installed *}
{* promo_get_prod_discount product_id=$entry->id assign='foo' *}
{if isset($foo.promo_id)}
<span style="color: red;">Discount:  {$currency_symbol}{$foo.discount|number_format:2} ({$foo.percentage|number_format:2}%)</span><br/>
{if $entry->price ne ''}
Price {$currency_symbol}: {$entry->price * $foo.decimal|number_format:2}<br />
{/if}
{elseif $entry->price ne ''}
Price {$currency_symbol}: {$entry->price}<br />
{/if}

{* accessing all of the fields in a list *}
{if count($entry->fields)}
  <h4>Custom Fields</h4>
  {foreach from=$entry->fields key='name' item='field'}
     <div class="product_detail_field"><p>
       {$mod->Lang('name')}: {$name}<br/>
       {$mod->lang('type')}: {$field->type}<br/>
       {$mod->lang('value')}: {$field->value}<br/>
       {if $field->type == 'image' && isset($field->thumbnail)}
         <img src="{$entry->file_location}/{$field->thumbnail}" alt="{$field->value}"/>
       {/if}
     </p></div>
  {/foreach}
{/if}

{* print out attributes *}
{if isset($entry->attributes)}
  <h4>Attributes</h4>
  {foreach from=$entry->attributes key='name' item='attribset'}
     <h6>{$name}</h6>
     <div class="product_detail_field"><p>
       {foreach from=$attribset key='label' item='adjustment'}
         {$label}: {$adjustment}<br/>
       {/foreach}
     </p></div>
  {/foreach}
{/if}

{* print out the categories *}
{if isset($entry->categories)}
  <h4>Categories</h4>
  {foreach from=$entry->categories item='category'}
    <div class="product_detail_category"><p>
      {$mod->Lang('id')}: {$category->id}<br/>
      {$mod->Lang('name')}: {$category->name}<br/>
      {* if there are data fields associated with this category, display them too *}
      {if isset($category->data) && count($category->data)}
        <div class="product_detail_category_fields">
        <strong>{$mod->Lang('data')}</strong><br/>
        {foreach from=$category->data item='onedataitem'}
           <div class="product_detail_category_onefield">
           {if $onedataitem.field_type == 'image'}
             <a href="{$category->file_location}/{$onedataitem.field_value}"><img src="{$category->file_location}/thumb_{$onedataitem.field_value}" alt="thumb" /></a>
           {elseif $onedataitem.field_type == 'file'}
             <a href="{$category->file_location}/{$onedataitem.field_value}">{$onedataitem.field_value}</a>
           {else}
             <strong>{$onedataitem.field_prompt}</strong>: {$onedataitem.field_value}<br/>
           {/if}
           </div>
        {/foreach}
        </div>
      {/if}
    </p></div> 
  {/foreach}
{/if}

{* include the cart *}
{* include the cart *}
{cge_have_module m='CGEcommerceBase' assign='tmp'}
{if $tmp}
<div>
{cgecomm_form_addtocart product=$entry->id} 
</div>
{/if}

{* create a link back to the top of the page *}
{anchor anchor='product_name' text=$products->Lang('return_to_top') title=$products->Lang('return_to_top')}

</div>
