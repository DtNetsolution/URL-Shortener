<?php /** @var $this UrlEditForm */ ?>
<form action="<?php echo SERVICE_BASEURL . 'admin/' . ($this->action == 'create' ? 'urlCreate.php' : 'urlEdit.php?id=' . $this->urlMapping['shortUrlID']); ?>" method="post" class="form-horizontal">
	<fieldset>
		<legend>Allgemeine Informationen</legend>

		<div class="form-group<?php if ($this->error['field'] == 'content') echo ' has-error' ?>">
			<label for="longUrl" class="col-lg-2 control-label">Lange URL</label>

			<div class="col-lg-10">
				<input type="text" id="longUrl" name="longUrl" value="<?php echo $this->longUrl; ?>" <?php if ($this->secret) echo 'disabled' ?>
				       autofocus="autofocus" class="form-control"/>
				<?php if ($this->error['field'] == 'longUrl') { ?>
					<div class="help-block">Bitte geben Sie eine g&uuml;lltige URL wie zum Beispiel
						<a href="https://google.de/">https://google.de/</a> oder
						<a href="https://www.softwaredemo.com/">softwaredemo.com</a> ein.
					</div>
				<?php } else { ?>
					<div class="help-block">Geben Sie die URL ein, die Sie verk&uuml;rzen m&ouml;chten.</div>
				<?php } ?>
			</div>
		</div>
        <div class="form-group<?php if ($this->error['field'] == 'content') echo ' has-error' ?>">
            <label for="secret" class="col-lg-2 control-label">Einmaliges Geheimnis</label>

            <div class="col-lg-10">
                <textarea id="secret" name="secret" rows="3" class="form-control"<?php if ($this->longUrl) echo ' disabled' ?>><?php echo $this->secret; ?></textarea>
                <div class="help-block">Geben Sie einen Text ein, den der Anwender genau einmal angezeigt wird.</div>
            </div>
        </div>
		<div class="form-group<?php if ($this->error['field'] == 'shortUrl') echo ' has-error' ?>">
			<label for="shortUrl" class="col-lg-2 control-label">Kurze URL</label>

			<div class="col-lg-10">
				<div class="row">
					<div class="col-lg-4 control-label" style="width: auto;">https:<?php echo SERVICE_BASEURL; ?></div>
					<div class="col-lg-6">
                        <div class="input-group">
						    <input type="text" id="shortUrl" name="shortUrl" value="<?php echo $this->shortUrl; ?>" class="form-control"/>
                            <div class="input-group-btn">
                                <div class="btn btn-default">
                                    <span class="glyphicon glyphicon-refresh"></span>
                                    Zufällig
                                </div>
                            </div>
                        </div>
					</div>
				</div>
				<?php if ($this->error['field'] == 'shortUrl' && $this->error['error'] == 'taken') { ?>
					<div class="help-block">Diese kurze URL wird bereits f&uuml;r
						<a href="<?php echo $this->error['url']; ?>"><?php echo $this->error['url']; ?></a> verwendet.
					</div>
				<?php } else { ?>
					<div class="help-block">Geben Sie die gew&uuml;nschte kurze URL ein.</div>
				<?php } ?>
			</div>
		</div>

		<div class="form-group">
			<div class="col-lg-offset-2 col-lg-10">
				<input type="submit" value="<?php echo($this->action == 'create' ? 'URL Verk&uuml;rzen' : 'Änderungen Speichern') ?>" class="btn btn-primary"/>
			</div>
		</div>
	</fieldset>

	<fieldset>
		<legend>Erweiterte Einstellungen</legend>

		<div class="form-group">
			<label class="col-lg-2 control-label">Automatisch L&ouml;schen</label>

			<div class="col-lg-10">
				<div class="radio">
					<label>
						<input type="radio" name="expire" value="0"<?php if (!$this->expire) echo '  checked="checked"'; ?>/> Nicht automatisch l&ouml;schen
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="expire" value="<?php echo time() + 7 * 24 * 60 * 60;
						if ($this->expire == 7) echo '  checked="checked"'; ?>"/> In sieben Tagen automatisch l&ouml;schen
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="expire" value="<?php echo time() + 31 * 24 * 60 * 60;
						if ($this->expire == 31) echo '  checked="checked"'; ?>"/> In 31 Tagen automatisch l&ouml;schen
					</label>
				</div>

				<?php if ($this->action == 'edit' && $this->expire) { ?>
					<div class="radio">
						<label>
							<input type="radio" name="expire" value="<?php echo $this->expire; ?>" checked="checked"/> Am <?php echo date('d.m.Y', $this->expire); ?> automatisch l&ouml;schen
						</label>
					</div>
				<?php } ?>
			</div>
		</div>
		<div class="form-group">
			<label for="details" class="col-lg-2 control-label">Details (optional)</label>

			<div class="col-lg-10">
				<textarea id="details" name="details" rows="3" class="form-control"><?php echo $this->details; ?></textarea>
			</div>
		</div>
		<div class="form-group">
			<div class="col-lg-offset-2 col-lg-10">
				<div class="checkbox">
					<label>
						<input type="checkbox" name="protected"<?php if ($this->protected) echo ' checked="checked"'; ?>/> Diesen Eintrag sch&uuml;tzen
					</label>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-lg-offset-2 col-lg-10">
				<input type="submit" value="<?php echo($this->action == 'create' ? 'URL Verk&uuml;rzen' : 'Änderungen Speichern') ?>" class="btn btn-primary"/>
			</div>
		</div>
	</fieldset>
</form>
