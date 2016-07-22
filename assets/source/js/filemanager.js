function setAjaxLoader() {
	$("#fileinfo").html('<div class="loading"><span class="glyphicon glyphicon-refresh spin"></span></div>');
}

function mediaFileClick(event) {
	event.preventDefault();
	
	if (ajaxRequest) {
		ajaxRequest.abort();
		ajaxRequest = null;
	}

	$(".gallery-items__item a").removeClass("active");
	$(this).addClass("active");
	
	ajaxRequest = $.ajax({
		type: "GET",
		url: $(this).closest('.file-gallery').data("details-url"),
		data: "id=" + $(this).closest('.gallery-items__item').data("key"),
		beforeSend: function() {
			setAjaxLoader();
		},
		success: function(html) {
			$("#fileinfo").html(html);
		}
	});
}

var ajaxRequest = null;

$(document).ready(function() {
    var fileInfoContainer = $("#fileinfo");

    $('[href="#mediafile"]').bind("click", mediaFileClick);

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
