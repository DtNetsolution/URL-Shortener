<html lang="de">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>URL Shortener</title>

		<link href="<?php echo SERVICE_BASEURL; ?>assets/css/bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo SERVICE_BASEURL; ?>assets/custom/additional.css" rel="stylesheet">
		<style type="text/css">
			.glyphicon {
				color: #000;
				text-decoration: none !important;
			}

			.small-column {
				width: 1px !important;
				white-space: nowrap;
				overflow: hidden;
			}

			th {
				white-space: nowrap;
				overflow: hidden;
			}

			#mainContainer {
				position: relative;
				min-height: 100%;
				padding-bottom: 50px;
			}
		</style>
	</head>

	<body>
		<header class="navbar navbar-inverse">
			<div class="container">
				<div class="navbar-header">
					<a href="<?php echo SERVICE_BASEURL; ?>admin/urlCreate.php" class="navbar-brand">URL Shortener</a>
				</div>
				<nav>
					<ul class="nav navbar-nav">
						<li<?php if ($parameter == 'urlCreate') echo ' class="active"'; ?>>
							<a href="<?php echo SERVICE_BASEURL; ?>admin/urlCreate.php">URL Verk&uuml;rzen</a>
						</li>
						<li<?php if ($parameter == 'urlList') echo ' class="active"'; ?>>
							<a href="<?php echo SERVICE_BASEURL; ?>admin/">Verk&uuml;rzte URLs</a>
						</li>

						<?php if ($this->urlShortener->getRole() == 'admin') { ?>
							<li<?php if ($parameter == 'userCreate') echo ' class="active"'; ?>>
								<a href="<?php echo SERVICE_BASEURL; ?>admin/userCreate.php">Benutzer Erstellen</a>
							</li>
							<li<?php if ($parameter == 'userList') echo ' class="active"'; ?>>
								<a href="<?php echo SERVICE_BASEURL; ?>admin/userList.php">Benutzer Auflisten</a>
							</li>
						<?php } ?>

						<li>
							<a href="<?php echo str_replace('://', '://go:go@', SERVICE_BASEURL); ?>admin/">Abmelden</a>
						</li>
					</ul>
				</nav>
			</div>
		</header>
		<div id="mainContainer" class="container">
