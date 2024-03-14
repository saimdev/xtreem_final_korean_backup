<div class="header">
    <h1>XTREEM DEMO</h1>
    <div class="header-right">
        <?php
            $balance = json_decode($userDetails)->balance;
            $formattedBalance = number_format($balance);
        ?>
        <span class="money">￦ {{$formattedBalance}}</span>
        <a href="/logout" class="logout"><span class="material-symbols-outlined">logout</span> Log-out</a>
    </div>
</div>