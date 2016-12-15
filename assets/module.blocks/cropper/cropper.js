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
		/*crop: function(event) {
			// Output the result data for cropping image.
			console.log(event.x);
			console.log(event.y);
			console.log(event.width);
			console.log(event.height);
			console.log(event.rotate);
			console.log(event.scaleX);
			console.log(event.scaleY);
		}*/
	});
});