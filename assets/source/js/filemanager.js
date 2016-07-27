function mediaFileLinkClick(event) {
	event.preventDefault();
	
	(new MediaFile(this)).click();
	(new FileGallery(this)).itemClick();
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
			
			var gallery = $("[data-key=\'" + response.id + "\']").closest(".file-gallery");
			var galleryPager = new GalleryPager(gallery);
			var gallerySummary = new GallerySummary(gallery);
			
			$("#fileinfo").html('');
			$('[data-key="' + response.id + '"]').fadeOut(function() {
				$(this).remove();
			});
			
			galleryPager.update(response.pagination);
			gallerySummary.update(response.pagination);
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
