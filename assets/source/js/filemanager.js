function mediaFileLinkClick(event) {
	event.preventDefault();
	
	var mediaFile = new MediaFile(this);
	var fileGallery = new FileGallery(this);
	
	mediaFile.click();
	fileGallery.itemClick();
}

$(document).ready(function() {
    var fileInfoContainer = $("#fileinfo");
	
	$('.media-file__link').on("click", mediaFileLinkClick);
	
    fileInfoContainer.on("click", '[role="delete"]', function(e) {
        e.preventDefault();

        var url = $(this).attr("href");
        var id = $(this).data("id");
        var confirmMessage = $(this).data("message");

        $.ajax({
            type: "POST",
            url: url,
            data: "id=" + id,
            beforeSend: function() {
                if (!confirm(confirmMessage)) {
                    return false;
                }
                $("#fileinfo").html('<div class="loading"><span class="glyphicon glyphicon-refresh spin"></span></div>');
            },
            success: function(json) {
                if (json.success) {
                    $("#fileinfo").html('');
                    $('[data-key="' + id + '"]').fadeOut();
                }
            }
        });
    });

    fileInfoContainer.on("submit", "#control-form", function(e) {
        e.preventDefault();

        var url = $(this).attr("action");
        var data = $(this).serialize();

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            beforeSend: function() {
                setAjaxLoader();
            },
            success: function(html) {
                $("#fileinfo").html(html);
            }
        });
    });
});
