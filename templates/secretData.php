<html lang="de">
<head>
    <title>URL Shortener</title>
    <link href="<?php echo SERVICE_BASEURL; ?>assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container" style="margin-top: 20px;">
    <h1>Einmaliges Geheimnis</h1>
    <pre style="cursor:pointer;" title="In Zwischenablage kopieren"><?php echo $parameter; ?></pre>
    <div class="help-block"><strong>Achtung:</strong> Die Information wurde gelöscht und kann <em>nicht</em> erneut
        angezeigt werden!
    </div>
</div>
<script>
	const pre = document.querySelector('pre');
	pre.addEventListener('click', event => {
		navigator.clipboard.writeText(pre.textContent);
	})
</script>
</body>
</html>
