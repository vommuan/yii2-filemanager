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
	
	$('[id^="file-info_"]').on('click', '.insert-btn', function(e) {
		e.preventDefault();
		
		var modal = $(this).closest('.filemanager-modal');
		var imageContainer = $(modal.attr("data-image-container"));
		var input = $("#" + modal.attr("data-input-id"));
		
		var data = modal.find('.media-file__link_checked img');
		
		input.trigger("fileInsert", [data]);

		if (imageContainer) {
			imageContainer.empty();
			
			for (var i = 0; i < data.length; i++) {
				imageContainer.append(
					$('<img/>', {
						src: data.eq(i).attr('src'),
						alt: data.eq(i).attr('alt'),
						class: 'selected-image'
					})
				);
			};
		}
		
		if (false == modal.find('.file-gallery').eq(0).data('multiple')) {
			input.val(data.eq(0).closest('.media-file').data('key'));
		} else {
			var inputData = [];
			
			for (var i = 0; i < data.length; i++) {
				inputData[i] = data.eq(i).closest('.media-file').data('key');
			}
			
			input.val(JSON.stringify(inputData));
		}
		
		modal.modal("hide");
	});
});