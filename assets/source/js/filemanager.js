/**
 * 
 */
function MediaFile(item) {
	var _item = item;
	
	this.click = function() {
		if ($(_item).closest('.file-gallery').data('multiple')) {
			$(_item).toggleClass('media-file__link_checked');
		} else {
			$('.media-file__link').removeClass('media-file__link_checked');
			$(_item).addClass('media-file__link_checked');
		}
	};
}

/**
 * 
 */
function FileGallery(item) {
	var _item = item;
	var _ajaxRequest = null;
	
	this.itemClick = function() {
		toggleChecker();
		loadDetails();
	};
	
	function toggleChecker() {
		var checker = $(_item).find('.file-gallery__checker');
		
		if (0 == checker.length) {
			checker = $(_item).closest('.file-gallery').find('.file-gallery__checker:last').clone().appendTo(_item);
		}
		
		if ($(_item).closest('.file-gallery').data('multiple')) {
			checker.toggleClass('file-gallery__checker_checked');
		} else {
			$('.gallery-items__item .file-gallery__checker_checked').removeClass('file-gallery__checker_checked');
			checker.addClass('file-gallery__checker_checked');
		}
	}
	
	function setAjaxLoader() {
		$("#fileinfo").html('<div class="loading"><span class="glyphicon glyphicon-refresh spin"></span></div>');
	}
	
	function loadDetails() {
		if (_ajaxRequest) {
			_ajaxRequest.abort();
			_ajaxRequest = null;
		}

		_ajaxRequest = $.ajax({
			type: "GET",
			url: $(_item).closest('.file-gallery').data("details-url"),
			data: "id=" + $(_item).closest('.gallery-items__item').data("key"),
			beforeSend: setAjaxLoader,
			success: function(html) {
				$("#fileinfo").html(html);
			}
		});
	}
}

$(document).ready(function() {
    var fileInfoContainer = $("#fileinfo");

    $('.media-file__link').on("click", function(event) {
		event.preventDefault();
	});
    
    $('.media-file__link').on("click", function() {
		var mediaFile = new MediaFile(this);
		
		mediaFile.click();
	});
	
	$('.media-file__link').on("click", function() {
		var fileGallery = new FileGallery(this);
		
		fileGallery.itemClick();
	});
	
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
