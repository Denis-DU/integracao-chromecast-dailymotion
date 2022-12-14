<?php
require_once "Dailymotion.php";

$api       = new Dailymotion();
$username  = "INSERIR EMAIL DA CONTA";
$password  = "INSERIR SENHA DA CONTA";
$apiKey    = "INSERIR API KEY";
$apiSecret = "INSERIR API SECRET";
$videoId   = "INSERIR ID DO VÍDEO QUE CONTÉM O STREAM";

$api->setGrantType(
    Dailymotion::GRANT_TYPE_PASSWORD,
    $apiKey,
    $apiSecret,
    [],
    [
        "username" => $username,
        "password" => $password,
    ]
);

$result = $api->get(
    "/video/$videoId",
    ["fields" => ["stream_live_hls_url"]]
);
$stream_live_hls_url = $result["stream_live_hls_url"];
?>

<html lang="pt-BR">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Chromecast - Exemplo de transmissão básico</title>
    <style>
        google-cast-launcher {
            position: inline;
        }

        #chromecast-container {
            width: 50px;
            height: 50px;
        }

        button {
            padding: 20px;
        }
    </style>
</head>

<body>
    <header>
        <h1>Chromecast - Exemplo de transmissão básico</h1>
    </header>

    <main>
        <div id="chromecast-container">
            <google-cast-launcher>Transmitir!</google-cast-launcher>
        </div>
        <p>
            <label>
                URL
                <input type="url" id="url" size="150" value="<?= $stream_live_hls_url ?>" />
            </label>
        </p>
        </p>
            <label>
                Mimetype
                <input type="text" id="mimetype" size="100" value="application/x-mpegURL" />
            </label>
        </p>
        <p>
            <button id="cast" onclick="castSession()">
                Cast
            </button>
        </p>


        <h2>Provedores</h2>
        <ul>
            <li>
                <h3>Dailymotion</h3>
                <?= $stream_live_hls_url ?>
                <br />
                application/x-mpegURL
            </li>
            <li>
                <h3>Exemplo</h3>
                https://cph-p2p-msl.akamaized.net/hls/live/2000341/test/master.m3u8
                <br />
                application/x-mpegURL
            </li>
        </ul>
    </main>

    <script src="https://www.gstatic.com/cv/js/sender/v1/cast_sender.js?loadCastFramework=1"></script>
    <script>
        function castSession(currentMediaURL) {
            var currentMediaURL = document.getElementById("url").value;
            var contentType = document.getElementById("mimetype").value;

            var castSession = cast.framework.CastContext.getInstance().getCurrentSession();
            var mediaInfo = new chrome.cast.media.MediaInfo(currentMediaURL, contentType);
            var request = new chrome.cast.media.LoadRequest(mediaInfo);
            castSession.loadMedia(request).then(
                function() {
                    console.log("Load succeed");
                },
                function(errorCode) {
                    console.log("Error code: " + errorCode);
                });
            console.log(castSession);
        }

        initializeCastApi = function() {
            cast.framework.CastContext.getInstance().setOptions({
                receiverApplicationId: chrome.cast.media.DEFAULT_MEDIA_RECEIVER_APP_ID,
                autoJoinPolicy: chrome.cast.AutoJoinPolicy.ORIGIN_SCOPED,
            });
        };

        window["__onGCastApiAvailable"] = function(isAvailable) {
            if (isAvailable) {
                initializeCastApi();
            }
        };
    </script>
</body>

</html>