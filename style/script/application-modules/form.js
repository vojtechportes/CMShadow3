function APITest (data, load, html, e) {
	console.log(data);
}

(function(){
	var form = function () {
		var parent = '.formLoader[data-api-load]';

		if ($(parent).length > 0) {
			var $parent = $(parent);
			$.each($parent, function(k, el){
				var $el = $(el),
					$form = $el.find('form').first(),	
					$submit = $form.find('[type="submit"]').first(),			
					parentData = $el.data('api-load');

				if ($form.length > 0) {
					$form.off('submit.formloader').on('submit.formloader', function(e){			
						var formData = $form.formatForAPI();
						parentData = $.extend({}, parentData, formData, {'form_open': 1});

						$.post('/admin/api/', parentData)
							.done(function(data) {
								$.each(data, function(k, module){
									$el.html(module['__html']);
								});	

								$(window).trigger('apiReload.admin/form');

								if (typeof parentData['arguments']['dependencies'] !== 'undefined') {
									$.each(parentData['arguments']['dependencies'], function(k, module){
										$(window).trigger('apiReloadForce.' + module);
									});
								}
							})
							.fail(function(data) {
								CMSAPI.setStatus(data);
							});

						e.preventDefault();
						e.stopPropagation();
						return false;
					});

					$el.on('click', '[data-api-reload]', function(e){
						e.preventDefault();
						e.stopPropagation();

						var parentData = $.extend({}, $el.data('api-load'), {'form_open': 1});
						APICommands.call($el, parentData, true);
					});
				}
			});
		}		
	}

	$(document).ready(function(){
		form();
	});

	$(window).on('apiReload.admin/form', function(){
		form();
	});	
})();