function folderBrowser () {
	console.log('start');
	if ($('.folders-wrapper').length > 0) {
		$('.folders-wrapper input').on('change', function(){
			console.log('click');
			$('.folders-wrapper label').removeClass('selected');
			$(this).siblings('label').addClass('selected');			
		});
	}
}

function removeResItem () {
	//var html = '<div class="resolution-item"><div class="col-md-5"><div class=""><div class="form-group"><label for="form-element-2" class="">Key</label><input class="form-control" id="form-element-2" name="resolution_k_1" type="text" required="" value="320.480">	</div></div></div><div class="col-md-5"></div><div class="col-md-2"></div></div>'

	if ($('.resolution-settings-- a.delete').length > 0) {
		$('.resolution-settings-- a.delete').off('click').on('click', function(){
			console.log('click');
			$(this).parents('.resolution-item').remove();
			return false;
		})
	}
}

function addResItem () {

	if ($('.resolution-settings-- .add-resolution a').length > 0) {
		
		$('.resolution-settings-- .add-resolution a').off('click').on('click', function(){
			console.log('click');
			var last = $('.resolution-settings-- .resolution-item:not(.hide) input').last().attr('name');
			last = (parseInt(last.split('-')[1]) + 1);
			var hidden = $('.resolution-settings-- .resolution-item.hide').last().clone();
			var col = $(hidden).find('.col-md-11');
			var input = $(col).find('input');
			$(input).attr('name', "resolution_v-" + last);
			$(input).attr('id', "resolution_v-" + last);
			$(input).siblings('label').attr('for', "resolution_v-" + last);

			$(hidden).removeClass('hide').insertBefore('.resolution-item.hide');
			removeResItem();
			return false;
		})
	}	
}