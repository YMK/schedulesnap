{* Smarty *}
{* The template for the index page *}
{extends file="mainPage.tpl"}

{block name="title" prepend}
Homepage
{/block}


{block name="body"}
{if $loggedin == 'true'}
    <p>Your rotas:</p>
    {if isset($rotas)}
    <ul>
    {foreach from=$rotas item=rota key=rotanum}
        
        <li><a href='viewRota.php?rota={$rota[1]}'>{$rota[0]}</a></li>
        
    {/foreach}
    </ul>
    {else}
        <p> You don't have any rotas yet, why don't you 
            <a href='createRota.php'>create one</a></p>
    {/if}
    
{else}
    
    <h2 id="indexInfo">Welcome to Schedule Snap</h2>
    <p id="indexPage">This is a system for creating generic timetables.
        Do you need a timetable for something that normal timetabling
        programs do not work with? Think there is a lot of wasted space? Then
        you need to try out Schedule Snap. </p>
    <div id="image">
        <img src='images/index/rotaView.png' class='indexImg' id='index0'/>
        <img src='images/index/rotaView2.png' class='indexImg' id='index1'
             style='display: none;'/>
        <img src='images/index/editView.png' class='indexImg' id='index2'
             style='display: none;'/>
        <img src='images/index/createView.png' class='indexImg' id='index3'
             style='display: none;'/>
    </div>
    
{/if}
{/block}