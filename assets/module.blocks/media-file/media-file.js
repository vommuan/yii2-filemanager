/**
 * Gallery media file handler
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

$('.media-file__link').on("click", function() {
	var mediaFile = new MediaFile(this);
	
	mediaFile.click();
});