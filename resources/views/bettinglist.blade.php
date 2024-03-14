<!DOCTYPE html>
<html lang="ko">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
        <title>XTREEM DEMO</title>
        <link rel="stylesheet" href="{{asset('/app.css')}}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,400,0,0" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,1,0" />
        <link href='//spoqa.github.io/spoqa-han-sans/css/SpoqaHanSansNeo.css' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <x-header :user-details="$userDetails"/>
        <x-menu-bar />
        <div class="container" id="container">
            <div class="container-heading">
                <span class="material-symbols-outlined">subtitles</span>
                <h1>Betting List</h1>
            </div>
            <p class="total-transc-count">Total {{$totalTransactions}}</p>
            <table class="betting-table">
                <thead>
                    <tr>
                        <th style="width: 65px;">No</th>
                        <th>Game</th>
                        <th style="width: 110px;">Betting Amount</th>
                        <th style="width: 110px;">Result Amount</th>
                        <th style="width: 150px;">created_at</th>
                        <th style="width: 150px;">processed_at</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->no }}</td>
                            <td style="">
                                {{ $transaction->gametitle }}
                                <p class="game-data">{{ $transaction->gamevendor }}</p>
                                <p class="game-data">{{ $transaction->gametype }}</p>
                                <p class="game-round">{{ $transaction->gameround }}</p>
                            </td>
                            <td>
                                @if($transaction->type === 'bet')
                                    {{ abs($transaction->amount) }}
                                @elseif($transaction->type === 'win')
                                    {{ abs($transactions->where('gameround', $transaction->gameround)->where('type', 'bet')->first()->amount ?? '') }}
                                @endif
                            </td>
                            <td class="@if($transaction->amount < 0) negative-amount @elseif($transaction->amount > 0) positive-amount @else zero-amount @endif">
                                {{ $transaction->amount }}
                            </td>
                            <td>{{ $transaction->created_at }}</td>
                            <td>{{ $transaction->processed_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            {{ $transactions->links('vendor.pagination.bootstrap-4') }}
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const menuButton = document.getElementById('menuButton');
                const menu = document.getElementById('menu');
                const menuOpen = document.getElementById('menuOpen');
                const menuClose = document.getElementById('menuClose');
                const container = document.getElementById('container');
                const gameListResults = document.getElementById('gameListResults');

                function openMenu() {
                    menu.style.left = '0';
                    menuOpen.style.display = 'none';
                    menuClose.style.display = 'block';
                    menuButton.style.left = '153px';
                    menuButton.style.background = '#868d97';
                    menuButton.style.boxShadow = '0 0 5px 0 rgba(0, 0, 0, 0.5) inset';
                    menuButton.style.borderRadius = '5px 0 0 5px';
                    container.style.marginLeft = '220px';
                    container.style.transition = 'left 0.3s ease';
                }

                openMenu();

                menuButton.addEventListener('click', () => {
                    const currentLeft = parseInt(getComputedStyle(menu).left);

                    if (currentLeft < 0) {
                        openMenu();
                    } else {
                        menu.style.left = '-250px';
                        menuOpen.style.display = 'block';
                        menuClose.style.display = 'none';
                        menuButton.style.left = '0';
                        menuButton.style.backgroundColor = '#282d35';
                        menuButton.style.color = 'white';
                        menuButton.style.padding = '0.5rem 0.1rem 0.3rem 0.1rem';
                        menuButton.style.borderRadius = '0 5px 5px 0';
                        menuButton.style.boxShadow = '0 0 5px 0 rgba(0, 0, 0, 0.5)';
                        container.style.marginLeft = '3rem';
                        container.style.transition = 'left 0.3s ease';
                    }
                });
            });
        </script>
    </body>
    
</html>
