<html lang="de">
<head>
    <title>URL Shortener</title>
    <link href="<?php echo SERVICE_BASEURL; ?>assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container" style="margin-top: 20px;">
    <div class="text-center">
        <a href="<?php
        /** @var GoAction $this */
        echo SERVICE_BASEURL . '?url=' . $this->shortUrl . '&secretConfirm';
        ?>" class="btn btn-primary">Geheimnis jetzt einmalig anzeigen</a>
    </div>
    <div class="help-block"><strong>Achtung:</strong> Dieser Link ist nur einmal gültig. Sobald das Passwort abgerufen
        wird, kann es nicht erneut angezeigt werden.
    </div>
</div>
</body>
</html>
