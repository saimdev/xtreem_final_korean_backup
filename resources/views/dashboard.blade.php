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
        <div class="container-dashboard" id="container" style="height: 90.25vh;">
            <div class="container-heading">
                <span class="material-symbols-outlined">subtitles</span>
                <h1>Xtreem Demo</h1>
            </div>
           <div>
                <ul class="dashboard-points">
                    <li>Using XTREEM Default API</li>
                    <li>Using the XTREEM Extension API</li>
                    <li>Integrated wallet (seamless) approach</li>
                    <li>Provides a list of games</li>
                    <li>Provides betting history</li>
                    <li>Provides site money charging capabilities</li>
                    <li>Provides site money exchange capabilities</li>
                    <li>Provides site money charging / exchange history</li>
                </ul>
           </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
            const menuButton = document.getElementById('menuButton');
            const menu = document.getElementById('menu');
            const menuOpen = document.getElementById('menuOpen');
            const menuClose = document.getElementById('menuClose');
            const container = document.getElementById('container');

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
                  menuButton.style.transition = '.3s all ease-in-out';
                  menuButton.style.transform = 'translateX(0)';
                  container.style.marginLeft = '3rem';
                  container.style.transition = '.3s all ease-in-out';
                }
            });
        });
      </script>

       
    </body>
    
</html>
