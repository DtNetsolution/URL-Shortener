(function() {
	var lastURL = null;
	var confirm = function(event) {
		event.preventDefault();
		var $object = $(event.currentTarget);

		lastURL = $object.attr('href');
		$('#shortLink').text($object.data('short-url')).attr('href', $object.data('short-url'));
		$('#confirmRemove').modal();
	};

	$('.glyphicon-remove').click(confirm);
	$('#remove').click(function() {
		location.href = lastURL;
	});
})();