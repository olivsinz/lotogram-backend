<!DOCTYPE html>
<html>
<head>
    <title>Pusher Test Client</title>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif; /* Modern font */
        }

        /* Console görünümü için CSS stilleri */
        .console-style {
            background-color: #333; /* Koyu arka plan */
            color: #fff; /* Beyaz yazı rengi */
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            overflow: auto;
        }

        /* JSON anahtarları için renk */
        .console-style .key {
            color: #ff8c00; /* Açık turuncu */
        }

        /* JSON değerleri için renk */
        .console-style .string {
            color: #9acd32; /* Yeşil */
        }

        .console-style .number {
            color: #add8e6; /* Açık mavi */
        }

        .console-style .boolean {
            color: #dc143c; /* Kırmızı */
        }
    </style>
</head>
<body>
    <h2>Pusher Test Client</h2>
    <label for="token">API Token:</label>
    <input type="text" id="token" value={{request('api_token')}} style="width:40%" placeholder="Enter your API token here">
    <input type="text" id="userId" value={{request('user_id')}} style="width:40%" placeholder="Enter your UserId here">
    <button onclick="connectPusher()">Connect</button>
    <button onclick="clear()">Clear</button>

    <div id="notifications"></div>

    <script>
        function connectPusher() {
            var token = document.getElementById('token').value;
            var userId = document.getElementById('userId').value;

            Pusher.logToConsole = true;

            var pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
                cluster: 'eu',
                authEndpoint: '{{config('app.url')}}/api/broadcasting/auth',
                auth: {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept' : 'application/json',
                    }
                }
            });

            var channelCompetition = pusher.subscribe('presence-competition');
            var channelUser = pusher.subscribe('private-user.' + userId);

            // Anlık olarak competition'ların sonuçlarını yayınlar.
            channelCompetition.bind('competition_result_announced', (data) => newLine(data, 'presence-competition', 'competition_result_announced'));

            // Competition statü değişikilği olduğunda bildirilir
            channelCompetition.bind('competition_status_changed', (data) => newLine(data, 'presence-competition', 'competition_status_changed'));

            // Bir competition için bilet satın alındığında yayınlanır.
            channelCompetition.bind('competition_ticket_purchased', (data) => newLine(data, 'presence-competition', 'competition_ticket_purchased'));

            // Bir competition için bilet kazanıldığında yayınlanır.
            channelCompetition.bind('competition_ticket_won', (data) => newLine(data, 'presence-competition', 'competition_ticket_won'));

            // Bir competition için yeni bir rewatd başladığı zaman yayınlanır.
            channelCompetition.bind('competition_reward_started', (data) => newLine(data, 'presence-competition', 'competition_reward_started'));

            // Kullanıcının bakiyesi değiştiğinde yayınlanır.
            channelUser.bind('balance', (data) => newLine(data, 'private-user.{user_uuid}', 'balance'));

            // Kullanıcı email adresini onayladığında yayınlanır.
            channelUser.bind('email-verified', (data) => newLine(data, 'private-user.{user_uuid}', 'email-verified'));

            // User Deposit yaptığında
            channelUser.bind('deposit_approved', (data) => newLine(data, 'private-user.{user_uuid}', 'deposit_approved'));

            // User withdraw yaptığında
            channelUser.bind('withdraw_approved', (data) => newLine(data, 'private-user.{user_uuid}', 'withdraw_approved'));

            // User bonus aldığında
            channelUser.bind('bonus_approved', (data) => newLine(data, 'private-user.{user_uuid}', 'bonus_approved'));

            // User'ın satın aldığı bir bilet iptal olduğunda
            channelUser.bind('competition_ticket_cancelled', (data) => newLine(data, 'private-user.{user_uuid}', 'competition_ticket_cancelled'));

            channelCompetition.bind('channelCompetition:subscription_error', function(status) {
                console.error('Abonelik hatası oluştu. Durum kodu:', status);
            });

            channelUser.bind('channelUser:subscription_error', function(status) {
                console.error('Abonelik hatası oluştu. Durum kodu:', status);
            });
        }

        function clear()
        {
            var notifications = document.getElementById('notifications');
            notifications.innerHTML = '';
        }

        function syntaxHighlight(json) {
            json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function(match) {
                var cls = 'number';
                if (/^"/.test(match)) {
                    if (/:$/.test(match)) {
                        cls = 'key';
                    } else {
                        cls = 'string';
                    }
                } else if (/true|false/.test(match)) {
                    cls = 'boolean';
                }
                return '<span class="' + cls + '">' + match + '</span>';
            });
        }

        function newLine (data, channelName, eventName)
        {
            var notifications = document.getElementById('notifications');

            var container = document.createElement('div');

            var header = document.createElement('h4');
            header.innerText = 'Channel: ' + channelName + ', Event: ' + eventName;
            container.appendChild(header);

            var message = document.createElement('pre');
            message.classList.add('console-style');
            var formattedData = syntaxHighlight(JSON.stringify(data, null, 2));
            message.innerHTML = formattedData;
            container.appendChild(message);

            notifications.insertBefore(container, notifications.firstChild);
        }
    </script>
</body>
</html>
