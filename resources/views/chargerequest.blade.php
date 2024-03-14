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
                <h1>Charge Request</h1>
            </div>
            <div class="login-form" style="width: 50%; padding: 25px 25px;">
            <div>
                <form method="GET" action="/chargereq" autocomplete="off">
                    <div class="input">
                        <label for="">Current Balance</label>
                            <?php
                                $balance = json_decode($userDetails)->balance;
                                $formattedBalance = number_format($balance);
                            ?>
                            <input type="text" name="balance" placeholder="Current balance" disabled id="balanceInput" value="{{$formattedBalance}}"/>
                        <span>KRW</span>
                    </div>
                    <div class="input">
                        <label for=""><p style="color:red; font-weight: bolder; margin-right: 6px">*</p>Charge Amount</label>
                        <input type="text" name="charge" placeholder="Charge Amount" required maxlength="6" id="chargeInput" oninput="validateChargeAmount()"/>
                        <span>KRW</span>
                    </div>
                    <div><button type="submit" class="login-btn" style="width: 100%;">Charge Request</button></div>
                </form>
            </div>
        </div>
        </div>
        <div id="chargeCompleteModal" class="modal">
            <div class="modal-content">
                <p>Your <span>charge</span> is complete</p>
                <button onclick="confirmCharge()">확인</button>
            </div>
        </div>
        <div id="overlay" class="overlay"></div>
        <script>

            const modal = document.getElementById('chargeCompleteModal');
            const overlay = document.getElementById('overlay');
            function validateChargeAmount() {
                const chargeInput = document.getElementById('chargeInput');
                const chargeValue = chargeInput.value.trim();
                if (!/^[0-9]+$/.test(chargeValue)) {
                    chargeInput.value = '';
                    event.preventDefault();
                }
            }
            function displayChargeCompleteModal() {
                modal.style.display = 'block';
                overlay.style.display = 'block';
            }

            function refreshPage() {
                location.reload(true);
            }
            function hideChargeCompleteModal() {
                modal.style.display = 'none';
                overlay.style.display = 'none';
            }

            function confirmCharge() {
                hideChargeCompleteModal();
                refreshPage();
                console.log('closed');
            }

            const chargeCompleteMessage = "{{ session('message') }}";
                if (chargeCompleteMessage) {
                displayChargeCompleteModal();
            }
            document.addEventListener("DOMContentLoaded", async  function () {
                
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
                  container.style.marginLeft = '3rem';
                  container.style.transition = 'left 0.3s ease';
                }
            });
            
        });
      </script>
    </body>
    
</html>
