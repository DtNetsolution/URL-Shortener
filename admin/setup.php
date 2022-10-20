<?php

define('BASE_DIR', dirname(dirname(__FILE__)) . '/');
require_once BASE_DIR . 'src/UrlShortener.class.php';

// check for existing config
if (file_exists(BASE_DIR . 'config/config.php')) {
	header('Location: index.php');
	exit;
}

// check for post data
if (isset($_POST['databaseHost']) && isset($_POST['databaseDB']) && isset($_POST['databaseUser']) && isset($_POST['databasePassword']) && isset($_POST['domainHost']) && isset($_POST['domainPath'])) {
	try {
		// write tables
		$pdo = new PDO('mysql:host=' . $_POST['databaseHost'] . ';dbname=' . $_POST['databaseDB'], $_POST['databaseUser'], $_POST['databasePassword']);
		$pdo->exec(file_get_contents(BASE_DIR . 'config/install.sql'));

		// create application
		$pdo->exec('INSERT INTO application (domainHost, domainPath) VALUES ("' . $_POST['domainHost'] . '", "' . $_POST['domainPath'] . '")');

		// create user
		$pdo->exec('INSERT INTO user (username, password, role) VALUES ("' . $_POST['username'] . '", "' . crypt($_POST['password'], '$2y$10$'.bin2hex(openssl_random_pseudo_bytes(22))) . '", "admin")');

		// write config
		file_put_contents(BASE_DIR . 'config/config.php', '<?php

$databaseHost = \'' . $_POST['databaseHost'] . '\';
$databaseDB = \'' . $_POST['databaseDB'] . '\';
$databaseUser = \'' . $_POST['databaseUser'] . '\';
$databasePassword = \'' . $_POST['databasePassword'] . '\';
define(\'LIST_URLS_NO_USER\', false);');

		// redirect to application setup
		header('Location: urlCreate.php');
		exit;
	} catch (PDOException $e) {
	}
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>URL Shortener</title>
		<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<h1>URL Shortener - Setup</h1>
			<?php
			if (!empty($_POST)) {
				echo '<p class="alert alert-danger">Die eingegebenen Logindaten waren nicht korrekt.';
				if (isset($e)) echo '<br>' . $e->getMessage();
				echo '</p>';
			}
			?>

			<form action="setup.php" method="post" class="form-horizontal">
				<fieldset>
					<legend>Datenbank</legend>
					<div class="form-group">
						<label for="databaseHost" class="col-lg-2 control-label">Datenbank Host</label>

						<div class="col-lg-4">
							<input type="text" id="databaseHost" name="databaseHost" value="<?php if (isset($_POST['databaseHost'])) echo $_POST['databaseHost']; ?>" required="required" autofocus="autofocus" class="form-control"/>
						</div>
					</div>
					<div class="form-group">
						<label for="databaseDB" class="col-lg-2 control-label">Datenbankname</label>

						<div class="col-lg-4">
							<input type="text" id="databaseDB" name="databaseDB" value="<?php if (isset($_POST['databaseDB'])) echo $_POST['databaseDB']; ?>" required="required" class="form-control"/>
						</div>
					</div>
					<div class="form-group">
						<label for="databaseUser" class="col-lg-2 control-label">Datenbank User</label>

						<div class="col-lg-4">
							<input type="text" id="databaseUser" name="databaseUser" value="<?php if (isset($_POST['databaseUser'])) echo $_POST['databaseUser']; ?>" required="required" class="form-control"/>
						</div>
					</div>
					<div class="form-group">
						<label for="databasePassword" class="col-lg-2 control-label">Datenbank Passwort</label>

						<div class="col-lg-4">
							<input type="password" id="databasePassword" name="databasePassword" value="<?php if (isset($_POST['databasePassword'])) echo $_POST['databasePassword']; ?>" required="required" class="form-control"/>
						</div>
					</div>
				</fieldset>

				<fieldset>
					<legend>Application</legend>
					<div class="form-group">
						<label for="domainHost" class="col-lg-2 control-label">Domain Host</label>

						<div class="col-lg-4">
							<input type="text" id="domainHost" name="domainHost" value="<?php if (isset($_POST['domainHost'])) echo $_POST['domainHost']; ?>" required="required" class="form-control"/>

							<div class="help-block">Geben Sie hier den Domain Host wie z.B. "www.google.de" oder "go.dtnet.de" ein.</div>
						</div>
					</div>
					<div class="form-group">
						<label for="domainPath" class="col-lg-2 control-label">Domain Path</label>

						<div class="col-lg-4">
							<input type="text" id="domainPath" name="domainPath" value="<?php if (isset($_POST['domainPath'])) echo $_POST['domainPath']; ?>" required="required" class="form-control"/>

							<div class="help-block">Geben Sie hier den Domain Pfad mit abschlie&szlig;enden Schr&auml;gstrich ein, wie z.B. "/" oder "/url-shortener/".</div>
						</div>
					</div>
				</fieldset>

				<fieldset>
					<legend>Administrator</legend>
					<div class="form-group">
						<label for="username" class="col-lg-2 control-label">Benutzername</label>

						<div class="col-lg-4">
							<input type="text" id="username" name="username" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>" required="required" class="form-control"/>
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-lg-2 control-label">Passwort</label>

						<div class="col-lg-4">
							<input type="password" id="password" name="password" value="<?php if (isset($_POST['password'])) echo $_POST['password']; ?>" required="required" class="form-control"/>

							<div class="help-block">Geben Sie hier das Passwort f&uuml;r den Administor ein.</div>
						</div>
					</div>
				</fieldset>

				<div class="form-group">
					<div class="col-lg-offset-2 col-lg-10">
						<input type="submit" value="URL Shortener installieren" class="btn btn-primary"/>
					</div>
				</div>
			</form>
		</div>
	</body>
</html>
