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

{$startform}
<div class="pageoverflow">
  <p class="pagetext">*{$mod->Lang('name')}:</p>
  <p class="pageinput">{$inputname}&nbsp;{$mod->Lang('info_alnumonly')}</p>
</div>

<div class="pageoverflow">
  <p class="pagetext">*{$mod->Lang('prompt')}:</p>
  <p class="pageinput">{$inputprompt}</p>
</div>

{if $showinputtype eq true}
<div class="pageoverflow">
  <p class="pagetext">*{$mod->Lang('type')}:</p>
  <p class="pageinput">{$inputtype}</p>
</div>
{else}
  {$inputtype}
{/if}

{if $type == 'textbox'}
<div class="pageoverflow">
  <p class="pagetext">*{$mod->Lang('maxlength')}:</p>
  <p class="pageinput">{$inputmaxlength}</p>
</div>
{/if}

{if $type == 'dropdown'}
<div class="pageoverflow">
  <p class="pagetext">*{$mod->Lang('dropdown_options')}:</p>
  <p class="pageinput"><textarea name="{$actionid}options">{$options}</textarea></p>
</div>
{/if}

<div class="pageoverflow">
  <p class="pagetext">*{$userviewtext}:</p>
  <p class="pageinput">{$input_userview}<br/>{$mod->Lang('info_publicfield')}</p>
</div>

<div class="pageoverflow">
  <p class="pagetext">&nbsp;</p>
  <p class="pageinput">{$hidden}{$submit}{$cancel}</p>
</div>
{$endform}
