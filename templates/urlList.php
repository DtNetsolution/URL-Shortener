<?php if (count($this->urls)) { ?>
	<table class="table table-hover table-condensed">
		<thead>
			<tr>
				<th class="small-column" colspan="2">
					<a href="<?php echo SERVICE_BASEURL; ?>admin/list.php?sortField=shortUrlID">ID</a>
					<?php if ($this->sortField == 'shortUrlID') { ?>
						<span class="glyphicon glyphicon-sort-by-alphabet"></span>
					<?php } ?>
				</th>
				<th>
					<a href="<?php echo SERVICE_BASEURL; ?>admin/list.php?sortField=longUrl">Lange URL</a>
					<?php if ($this->sortField == 'longUrl') { ?>
						<span class="glyphicon glyphicon-sort-by-alphabet"></span>
					<?php } ?>
				</th>
				<th class="small-column">
					<a href="<?php echo SERVICE_BASEURL; ?>admin/list.php?sortField=shortUrl">Kurze URL</a>
					<?php if ($this->sortField == 'shortUrl') { ?>
						<span class="glyphicon glyphicon-sort-by-alphabet"></span>
					<?php } ?>
				</th>
				<th class="small-column">
					<a href="<?php echo SERVICE_BASEURL; ?>admin/list.php?sortField=creator">Ersteller</a>
					<?php if ($this->sortField == 'creator') { ?>
						<span class="glyphicon glyphicon-sort-by-alphabet"></span>
					<?php } ?>
				</th>
				<th class="small-column">
					<a href="<?php echo SERVICE_BASEURL; ?>admin/list.php?sortField=createdTime">Zeitpunkt</a>
					<?php if ($this->sortField == 'createdTime') { ?>
						<span class="glyphicon glyphicon-sort-by-attributes-alt"></span>
					<?php } ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($this->urls as $url) { ?>
				<tr>
					<td class="small-column"><?php echo $url['shortUrlID']; ?></td>
					<td class="small-column">
						<a href="<?php echo SERVICE_BASEURL; ?>admin/edit.php?id=<?php echo $url['shortUrlID']; ?>"
						   class="glyphicon glyphicon-pencil" title="Bearbeiten"></a>

						<?php if($url['protected']) { ?>
							<span class="glyphicon glyphicon-lock" title="Gesch&ouml;tzt"></span>
						<?php } else { ?>
							<a href="<?php echo SERVICE_BASEURL; ?>admin/delete.php?id=<?php echo $url['shortUrlID']; ?>"
							   data-short-url="<?php echo UrlShortener::expandShortUrl($url['shortUrl']); ?>"
							   class="glyphicon glyphicon-remove" title="L&ouml;schen"></a>
						<?php } ?>
					</td>

					<td><a href="<?php echo $url['longUrl']; ?>" target="blank"><?php echo $url['longUrl']; ?></a></td>
					<td class="small-column">
						<a href="<?php echo UrlShortener::expandShortUrl($url['shortUrl']); ?>" target="blank">
							<?php echo $url['shortUrl']; ?>
						</a>
					</td>
					<td class="small-column"><?php echo $url['creator']; ?></td>
					<td class="small-column"><?php echo date('Y-m-d H:i:s', $url['createdTime']); ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<div class="modal fade" id="confirmRemove" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">x</button>
					<h4 class="modal-title">Best&auml;tigung erforderlich</h4>
				</div>
				<div class="modal-body">
					M&ouml;chten Sie den kurzen Link &raquo;<a id="shortLink" target="blank"></a>&laquo; wirklich l&ouml;schen?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Schlie&szlig;en</button>
					<button type="button" class="btn btn-primary" id="remove">L&ouml;schen</button>
				</div>
			</div>
		</div>
	</div>
<?php } else { ?>
	<p class="text-danger">Es wurden keine URLs verk&uuml;rzt.</p>
<?php }
