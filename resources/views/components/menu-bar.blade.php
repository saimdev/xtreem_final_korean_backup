<div class="left-menu">
    <div id="menu">
        <div>
            <a href="/" class="leftmenu-link @if(Request::is('/')) active @endif" style="border-top: none !important; width: 50% !important">Home</a>
            <a href="/res/gameList" class="leftmenu-link @if(Request::is('res/gameList')) active @endif">Game List</a>
            <a href="/res/bettinglist" class="leftmenu-link @if(Request::is('res/bettinglist')) active @endif">Betting List</a>
            <a href="/res/charge" class="leftmenu-link @if(Request::is('res/charge')) active @endif">Charge Request</a>
            <a href="/res/exchange" class="leftmenu-link @if(Request::is('res/exchange')) active @endif">Exchange Request</a>
            <div class="leftmenu-link"></div>
        </div>
    </div>
    <div id="menuButton">
        <span class="material-symbols-outlined" id="menuOpen">keyboard_arrow_right</span>
        <span class="material-symbols-outlined" id="menuClose">keyboard_arrow_left</span>
    </div>
</div>
