function mediaFileLinkClick(event) {
	event.preventDefault();
	
	var mediaFile = new MediaFile(this);
	var fileGallery = new FileGallery(this);
	
	mediaFile.click();
	fileGallery.itemClick();
}

function deleteFile(event) {
	event.preventDefault();
        
	var confirmMessage = $(this).data("message");

	$.ajax({
		type: "POST",
		url: $(this).attr("href"),
		beforeSend: function() {
			if (!confirm(confirmMessage)) {
				return false;
			}
			
			FileGallery().setAjaxLoader();
		},
		success: function(response) {
			if (!response.success) {
				return;
			}
			
			var galleryPager = new GalleryPager(
				$("[data-key=\'" + response.id + "\']").closest(".file-gallery")
			);
			
			$("#fileinfo").html('');
			$('[data-key="' + response.id + '"]').fadeOut(function() {
				$(this).remove();
			});
			
			galleryPager.update(response.pagination);
		}
	});
}

$(function() {
	$('.file-gallery').on("click", '.media-file__link', mediaFileLinkClick);
	
    $("#fileinfo").on("click", '[role="delete"]', deleteFile);

    $("#fileinfo").on("submit", "#control-form", function(event) {
        event.preventDefault();

        $.ajax({
            type: "POST",
            url: $(this).attr("action"),
            data: $(this).serialize(),
            beforeSend: function() {
                FileGallery().setAjaxLoader();
            },
            success: function(html) {
                $("#fileinfo").html(html);
            }
        });
    });
});
