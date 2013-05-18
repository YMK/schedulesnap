{* Smarty *}
{* The template for the index page *}
{extends file="mainPage.tpl"}

{block name="title" prepend}
Create New Rota
{/block}


{block name="body"}
<div id="rotaImages">

    <p> You can either choose one of the following pre-made rotas, or create
        a custom one built to your specification. Note that you can modify any
        of the premade ones to your complete specification afterwards, the 
        ones here are just to get you started </p>

    <div class="leftImage">
        <a href="createRota.php?type=2" class="none">
            <p class="title leftTitle">4 items a week</p>
            <img src="images/leftTop.png" class="rotaImg rotaImgLeft" />
        </a>
    </div>
    
    <div class="rightImage">
        <a href="createRota.php?type=3" class="none">
            <p class="title rightTitle">6 items a day</p>
            <img src="images/rightTop.png" class="rotaImg rotaImgRight" />
        </a>
    </div>
    
    <div id="breaker"></div>
    
    <div class="leftImage">
        <a href="createRota.php?type=4" class="none">
            <p class="title leftTitle">5 items each week, on a specific date</p>
            <img src="images/bottomLeft.png" class="rotaImg rotaImgLeft" />
        </a>
    </div>
    
    <div class="rightImage">
        <a href="createRota.php?type=1" class="none">
            <p class="title rightTitle">Custom</p>
            <img src="images/bottomRight.png" class="rotaImg rotaImgRight" />
        </a>
    </div>

</div>
{/block}