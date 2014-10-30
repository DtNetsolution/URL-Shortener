<html lang="de">
	<head>
		<title>URL Shortener</title>
		<link href="<?php echo SERVICE_BASEURL; ?>assets/css/bootstrap.min.css" rel="stylesheet">
	</head>
	
	<body>
		<header class="navbar navbar-inverse">
			<div class="container">
				<div class="navbar-header">
					<a href="create.php" class="navbar-brand">URL Shortener</a>
				</div>
				<nav>
					<ul class="nav navbar-nav">
						<li<?php if ($parameter == 'create') echo ' class="active"'; ?>>
							<a href="<?php echo SERVICE_BASEURL; ?>admin/create.php">URL Verk&uuml;rzen</a>
						</li>
						<li<?php if ($parameter == 'list') echo ' class="active"'; ?>>
							<a href="<?php echo SERVICE_BASEURL; ?>admin/">Verk&uuml;rzte URLs</a>
						</li>
						<li>
							<a href="<?php echo str_replace('://', '://go:go@', SERVICE_BASEURL); ?>admin/create.php">Abmelden</a>
						</li>
					</ul>
				</nav>
			</div>
		</header>
		<div class="container">