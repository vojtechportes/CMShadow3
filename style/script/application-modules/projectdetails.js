(function(){
	var reload = function () {
		var parent = '.projectDetails[data-api-load]';
		$.each($(parent), function(k, el){
			var parentData = $(el).data('api-load');
			APICommands.call($(el), parentData, true);
		});		
	}

	var update = function () {
		var parent = '.projectDetails[data-api-load]';
		if ($(parent).length > 0) {
			var $parent = $(parent),
				$wrapper = $parent.find('.project-details'),
				title = $wrapper.data('title');

			$('.page-header h1').text(title);
		}		
	}

	$(document).ready(function(){
		update();
	});

	$(window).on('apiReloadForce.admin/project/details', function(){
		reload();
		update();
	});

	$(window).on('apiReload.admin/project/details', function(){
		update();
	});
})();