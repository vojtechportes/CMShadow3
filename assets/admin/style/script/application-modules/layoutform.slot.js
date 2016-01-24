(function(){
	var reload = function () {
		var parent = '.layoutSlotForm[data-api-load]';
		$.each($(parent), function(k, el){
			var parentData = $(el).data('api-load');
				parentData = $.extend({}, parentData);
				parentData['arguments'] = $.extend({}, parentData['arguments'], {'type': 'create'});

			$(el).data('api-load', parentData);

			APICommands.call($(el), parentData, true);
		});		
	}

	var update = function () {
		if ($('.layoutControls').length > 0) {
			var $parent = $('.layoutControls');
			var $trigger = $parent.find('a[data-toggle="modal"]'),
				$target = $($trigger.data('target'));

			$trigger.off('click.create').on('click.create', function(e){
				e.preventDefault();

				$(window).trigger('apiReloadForce.admin/layout/api/form.slot');
			});
		}
	}

	$(document).ready(function(){
		update();
	});

	$(window).on('apiReload.admin/layout/controls.detail', function(){
		update();
	});	

	$(window).on('apiReloadForce.admin/layout/api/form.slot', function(){
		reload();
	});	
})();