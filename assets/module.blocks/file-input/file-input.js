function getFormData(form) {
	var formArray = form.serializeArray();
	var modelMap = {
			'UpdateFileForm[alt]': 'alt',
			'UpdateFileForm[description]': 'description',
			url: 'url',
			id: 'id'
		};
	var data = [];

	for (var i = 0; i < formArray.length; i++) {
		if (modelMap[formArray[i].name]) {
			data[modelMap[formArray[i].name]] = formArray[i].value;
		}
	}

	return data;
}

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
	
	$("#fileinfo").on("click", "#insert-btn", function(e) {
		e.preventDefault();
		
		var modal = $(this).closest('#filemanager-modal');
		var imageContainer = $(modal.attr("data-image-container"));
		var pasteData = modal.attr("data-paste-data");
		var input = $("#" + modal.attr("data-input-id"));
		
		var data = getFormData($(this).parents("#control-form"));
		
		input.trigger("fileInsert", [data]);

		if (imageContainer) {
			imageContainer.html('<img src="' + data.url + '" alt="' + data.alt + '">');
		}

		input.val(data[pasteData]);
		modal.modal("hide");
	});
});