$(document).ready(function() {
	$('[role="filemanager-launch"]').on("click", function(e) {
		e.preventDefault();
		$($(this).data('target')).modal('show');
	});

	$('[role="clear-input"]').on("click", function(e) {
		e.preventDefault();

		$("#" + $(this).data('clear-element-id')).val('');
		
		var imageContainer = $($(this).data('image-container'));
		
		imageContainer.empty();
		
		if ('' != $(this).data('default-image')) {
			var defaultImage = $('<img/>', {'src': $(this).data('default-image')});
			
			imageContainer.append(defaultImage);
		}
	});
});