{* Smarty *}
{* The template for the index page *}
{extends file="mainPage.tpl"}

{block name="title" prepend}
View Rota
{/block}

{block name="headers"}
<link rel="stylesheet" type="text/css" href="css/viewRota.css" />
{/block}

{block name="javascript"}
var rotaid = {$rotaid};
var strips = {$strips};
var blocks = {$blocks};
var user = "{$user}";

var tablearray = {$tablearray};
var userlist = {$userlistjson};
{/block}

{block name="body"}
{if $validrota == 'true'}
    <p><span class="rotaName" id="{$rotaid}">{$rotaname}</span> 
        {if $owner == 'true'}- <a href="" id="deleteRota">Delete Rota</a>{/if}
    </p>
    
    {if $owner == 'true'}
        <br /><a href='' id='edit' style="float:left;">Edit rota</a>
        <a href='' id='fillEmpty' 
           style="float:right;margin-left:10px;display:none">Fill empty cells</a>
        <a href='' id='fillRota' style="float:right;display:none;">Fill rota</a>
    {/if}
    <div id='table'>
    {*Only show edit button if logged in. should change it to only rota owner*}
    <br />
    <table id='blockRota'>
        {foreach from=$table item=row key=stripid}
        <tr id='strip{$stripid}'> 
            {foreach from=$row item=cell key=blockid}
                <td id='{$stripid},{$blockid}' 
                    {if {$stripid} < 1 xor {$blockid} < 1}
                        class='tableHeader'>
                    {elseif {$stripid} <1 && {$blockid} <1}
                        >
                    {else}
                        class='tableCell'>
                    {/if}
                    <span id='{$stripid},{$blockid}' class='stuff'>{$cell}</span> 
                    {if {$stripid} < 1 && {$blockid} >= 1}
                        <span class='delete'>
                            <a href='' class='none deleteB' 
                               id='del,{$blockid}'>Del</a></span>
                    {/if} 
                    {if {$stripid} >= 1 && {$blockid} < 1}
                        <span class='delete'>
                            <a href='' class='none deleteS' 
                               id='del,{$stripid}'>Del</a></span>
                    {/if}
                </td> 
            {/foreach}
        </tr> 
        {/foreach}
    </table>
    </div>
    
    {if $owner == 'true'}
    <div id='userlist' style='display:none;'>
        <p> Users in rota: </p>
    {foreach from=$users item=user}
        <p class='username' id='{$user[1]}'>{$user[0]}</p>
    {/foreach}
        <p class='username' id=' '>Blank</p>
        <div class="ui-widget"> <input type="text" value="Add user" name="search" id="userSearch"/> 
                </div> {*TODO - implement this*}
    </div>
    {/if}
{else}
Incorrect rota id: {$rotaid} <br />
Error message: {$error}
{/if}
{/block}


