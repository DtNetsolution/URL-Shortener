<?php

// check for existing config
define('BASE_DIR', dirname(dirname(__FILE__)) . '/');
if (file_exists(BASE_DIR . 'config/config.php')) {
	header('Location: list.php');
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

		// write config
		file_put_contents(BASE_DIR . 'config/config.php', '<?php

$databaseHost = \'' . $_POST['databaseHost'] . '\';
$databaseDB = \'' . $_POST['databaseDB'] . '\';
$databaseUser = \'' . $_POST['databaseUser'] . '\';
$databasePassword = \'' . $_POST['databasePassword'] . '\';');

		// redirect to application setup
		header('Location: create.php');
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
				<div class="form-group">
					<label for="databaseHost" class="col-lg-2 control-label">Datenbank Host</label>

					<div class="col-lg-4">
						<input type="text" id="databaseHost" name="databaseHost"
						       value="<?php if (isset($_POST['databaseHost'])) echo $_POST['databaseHost']; ?>"
						       required="required" autofocus="autofocus" class="form-control"/>
					</div>
				</div>
				<div class="form-group">
					<label for="databaseDB" class="col-lg-2 control-label">Datenbankname</label>

					<div class="col-lg-4">
						<input type="text" id="databaseDB" name="databaseDB"
						       value="<?php if (isset($_POST['databaseDB'])) echo $_POST['databaseDB']; ?>"
						       required="required" class="form-control"/>
					</div>
				</div>
				<div class="form-group">
					<label for="databaseUser" class="col-lg-2 control-label">Datenbank User</label>

					<div class="col-lg-4">
						<input type="text" id="databaseUser" name="databaseUser"
						       value="<?php if (isset($_POST['databaseUser'])) echo $_POST['databaseUser']; ?>"
						       required="required" class="form-control"/>
					</div>
				</div>
				<div class="form-group">
					<label for="databasePassword" class="col-lg-2 control-label">Datenbank Passwort</label>

					<div class="col-lg-4">
						<input type="password" id="databasePassword" name="databasePassword"
						       value="<?php if (isset($_POST['databasePassword'])) echo $_POST['databasePassword']; ?>"
						       required="required" class="form-control"/>
					</div>
				</div>

				<div class="form-group">
					<label for="domainHost" class="col-lg-2 control-label">Domain Host</label>

					<div class="col-lg-4">
						<input type="text" id="domainHost" name="domainHost"
						       value="<?php if (isset($_POST['domainHost'])) echo $_POST['domainHost']; ?>"
						       required="required" class="form-control"/>
						<div class="help-block">Geben Sie hier den Domain Host wie z.B. "www.google.de" oder "go.dtnet.de" ein.</div>
					</div>
				</div>
				<div class="form-group">
					<label for="domainPath" class="col-lg-2 control-label">Domain Path</label>

					<div class="col-lg-4">
						<input type="text" id="domainPath" name="domainPath"
						       value="<?php if (isset($_POST['domainPath'])) echo $_POST['domainPath']; ?>"
						       required="required" class="form-control"/>
						<div class="help-block">Geben Sie hier den Domain Pfad mit abschließenden Schr&auml;gstrich ein, wie z.B. "/" oder "/url-shortener/".</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-lg-offset-2 col-lg-10">
						<input type="submit" value="Konfiguration und Tabellen erstellen" class="btn btn-primary"/>
					</div>
				</div>
			</form>
		</div>
	</body>
</html>