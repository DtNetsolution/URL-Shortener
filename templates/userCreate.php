<?php /** @var $this UserCreateForm */ ?>
<form action="<?php echo SERVICE_BASEURL.'admin/'.($this->action == 'create' ? 'userCreate.php' : 'userEdit.php?id='.$this->user['userID']); ?>" method="post" class="form-horizontal">
	<div class="form-group<?php if ($this->error['field'] == 'username') echo ' has-error' ?>">
		<label for="username" class="col-lg-2 control-label">Benutzername</label>

		<div class="col-lg-10">
			<input type="text" id="username" name="username" value="<?php echo $this->username; ?>" required="required" autofocus="autofocus" class="form-control"/>
			<?php if ($this->error['field'] == 'username') { ?>
				<span class="help-block">Dieser Benutzername wird bereits verwendet.</span>
			<?php } elseif ($this->error['field'] == 'username') { ?>
				<span class="help-block">Dieser Benutzername wird bereits verwendet.</span>
			<?php } ?>
		</div>
	</div>
	<div class="form-group<?php if ($this->error['field'] == 'password') echo ' has-error' ?>">
		<label for="password" class="col-lg-2 control-label">Passwort</label>

		<div class="col-lg-10">
			<input type="password" id="password" name="password" value="<?php echo $this->password; ?>" class="form-control"/>
		</div>
	</div>
	<div class="form-group">
		<label class="col-lg-2 control-label">Rolle</label>
		<div class="col-lg-10">
			<?php foreach (UrlShortener::$roles as $role => $name) { ?>
				<div class="radio">
					<label>
						<input type="radio" name="role" value="<?php echo $role; if($role == $this->role) echo '" checked="checked' ?>"/> <?php echo $name; ?>
					</label>
				</div>
			<?php } ?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<input type="submit" value="<?php echo($this->action == 'create' ? 'Benutzer Erstellen' : 'Änderungen Speichern') ?>" class="btn btn-primary"/>
		</div>
	</div>
</form>