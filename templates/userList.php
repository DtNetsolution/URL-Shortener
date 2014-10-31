<?php /** @var $this UserListPage */ ?>
<table class="table table-hover table-condensed">
	<thead>
		<tr>
			<th class="small-column" colspan="2">ID</th>
			<th>Benutzername</th>
			<th class="small-column">Rolle</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($this->users as $user) { ?>
			<tr>
				<td class="small-column"><?php echo $user['userID']; ?></td>
				<td class="small-column">
					<a href="<?php echo SERVICE_BASEURL; ?>admin/userEdit.php?id=<?php echo $user['userID']; ?>"
					   class="glyphicon glyphicon-pencil" title="Bearbeiten"></a>
					<a href="<?php echo SERVICE_BASEURL; ?>admin/userDelete.php?id=<?php echo $user['userID']; ?>"
					   data-short-url="<?php echo UrlShortener::expandShortUrl($user['userID']); ?>"
					   class="glyphicon glyphicon-remove" title="L&ouml;schen"></a>
				</td>

				<td><?php echo $user['username']; ?></td>
				<td class="small-column"><?php echo UrlShortener::$roles[$user['role']]; ?></td>
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
				M&ouml;chten Sie den Benutzer &raquo;<a id="shortLink" target="blank"></a>&laquo; wirklich l&ouml;schen?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Schlie&szlig;en</button>
				<button type="button" class="btn btn-primary" id="remove">L&ouml;schen</button>
			</div>
		</div>
	</div>
</div>
