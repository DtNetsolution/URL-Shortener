<form action="<?php echo SERVICE_BASEURL; ?>admin/create.php" method="post" class="form-horizontal">
	<div class="form-group<?php if ($this->error['field'] == 'longURL') echo ' has-error' ?>">
		<label for="longURL" class="col-lg-2 control-label">Lange URL</label>

		<div class="col-lg-10">
			<input type="text" id="longURL" name="longURL" value="<?php echo $this->longURL; ?>" required="required"
			       autofocus="autofocus" class="form-control"/>
			<?php if ($this->error['field'] == 'longURL') { ?>
				<span class="help-block">Bitte geben Sie eine g&uuml;lltige URL wie zum Beispiel
					<a href="http://google.de/">http://google.de/</a> oder <a href="http://www.softwaredemo.com/">softwaredemo.com</a> ein.
				</span>
			<?php } else { ?>
				<span class="help-block">Geben Sie die URL ein, die Sie verk&uuml;rzen m&ouml;chten.</span>
			<?php } ?>
		</div>
	</div>
	<div class="form-group<?php if ($this->error['field'] == 'shortURL') echo ' has-error' ?>">
		<label for="shortURL" class="col-lg-2 control-label">Kurze URL</label>

		<div class="col-lg-10">
			<div class="row">
				<div class="col-lg-4 control-label" style="width: auto;"><?php echo SERVICE_BASEURL; ?></div>
				<div class="col-lg-6">
					<input type="text" id="shortURL" name="shortURL" value="<?php echo $this->shortURL; ?>"
					       class="form-control"/>
				</div>
			</div>
			<?php if ($this->error['field'] == 'shortURL') { ?>
				<span class="help-block">Diese kurze URL wird bereits f&uuml;r <a
						href="<?php echo $this->error['url']; ?>"><?php echo $this->error['url']; ?></a> verwendet.</span>
			<?php } else { ?>
				<span class="help-block">Geben Sie optional die gew&uuml;nschte URL ein.</span>
			<?php } ?>
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<input type="submit" value="URL Verk&uuml;rzen" class="btn btn-primary"/>
		</div>
	</div>
</form>