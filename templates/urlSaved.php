<p class="alert alert-success container">Ihre &Auml;nderungen wurden gespeichert. Die kurze URL
	<a href="<?php echo $parameter; ?>" target="blank"><?php echo $parameter; ?></a> wurde in die Zwischenablage kopiert.</p>
<script>
	navigator.clipboard.writeText('<?php echo $parameter; ?>');
</script>
