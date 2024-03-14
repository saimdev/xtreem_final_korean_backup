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
                <h1>Game List</h1>
            </div>
            @if(Cache::has('game_list'))
                @php
                    $cachedData = Cache::get('game_list');
                @endphp
                @foreach($cachedData['uniqueVendors'] as $vendor)
                    <div> 
                        <a href="#" class="game-container evolution-link" data-vendor="{{ $vendor }}">
                            <p>{{ $vendor }}</p>
                            <span>{{ isset($cachedData['vendorGamesCount'][$vendor]) ? $cachedData['vendorGamesCount'][$vendor] : 0 }}</span>
                        </a>  
                    </div>
                @endforeach
            @else
                @foreach($uniqueVendors as $vendor)
                <div> 
                    <a href="#" class="game-container evolution-link" data-vendor="{{ $vendor }}">
                        <p>{{ $vendor }}</p>
                        <span>{{ isset($vendorGamesCount[$vendor]) ? $vendorGamesCount[$vendor] : 0 }}</span>
                    </a>  
                </div>
                @endforeach
            @endif
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
                const evolutionLinks = document.querySelectorAll('.evolution-link');
evolutionLinks.forEach(evolutionLink => {
    evolutionLink.addEventListener('click', async function (event) {
        event.preventDefault();

        const vendorName = evolutionLink.getAttribute('data-vendor');
        try {
            const response = await fetch(`/getGame/${vendorName}`, {
                method: 'GET',
            });

            const results = await response.json();
            console.log(results);

            // Check if a result-container already exists for this evolutionLink
            const existingResultContainer = evolutionLink.nextElementSibling;
            if (existingResultContainer && existingResultContainer.classList.contains('result-container')) {
                existingResultContainer.remove(); // Remove the existing result-container
            } else {
                // Create a new result-container
                const resultContainer = document.createElement('div');
                resultContainer.setAttribute('data-vendor', vendorName);
                resultContainer.className = 'result-container';
                results.forEach(async (result, index) => {
                    const gameId = result.id;

                    const resultLink = document.createElement('a');
                    resultContainer.appendChild(resultLink);

                    const thumbnail = document.createElement('img');
                    thumbnail.setAttribute('loading', 'lazy');

                    thumbnail.src = result.thumbnail;
                    thumbnail.alt = 'Thumbnail';
                    resultLink.appendChild(thumbnail);

                    const providerTitle = document.createElement('span');
                    providerTitle.textContent = `[${result.provider}] ${result.title}`;
                    resultLink.appendChild(providerTitle);

                    const lang = document.createElement('p');
                    lang.textContent = `${result.langs.ko}`;
                    resultLink.appendChild(lang);

                    resultLink.addEventListener('click', async function (event) {
                        event.preventDefault();

                        try {
                            const launchLinkResponse = await fetch(`/game-launch/${vendorName}/${gameId}`);
                            const launchLinkResult = await launchLinkResponse.json();
                            console.log(launchLinkResult);
                            resultLink.href = launchLinkResult.link;

                            window.open(resultLink.href, '_blank');
                        } catch (error) {
                            console.error('Error fetching launch link:', error);
                        }
                    });
                });

                evolutionLink.parentNode.insertBefore(resultContainer, evolutionLink.nextSibling);
            }
        } catch (error) {
            console.error('Error fetching game list:', error);
        }
    });
});

            });
        </script>
    </body>
    
</html>
