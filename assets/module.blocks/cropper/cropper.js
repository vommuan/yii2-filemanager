$(function() {
	$('.cropper').on('click', '.controls .controls__rotate', function (event) {
		event.preventDefault();
		
		var controlButton = $(event.currentTarget);
		
		if (controlButton.hasClass('controls__rotate_left')) {
			controlButton.closest('.cropper').find('.crop-image').eq(0).cropper('rotate', -90);
		} else {
			controlButton.closest('.cropper').find('.crop-image').eq(0).cropper('rotate', 90);
		}
	})
	
	$('.crop-image').cropper({
		autoCrop: false,
		dragMode: 'none',
		toggleDragModeOnDblclick: false,
		movable: false,
		zoomable: false,
		crop: function(event) {
			$(event.currentTarget).closest('.cropper').find('.cropper__rotate-input').eq(0).val(event.rotate);
		}
	});
});