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
						parentData['arguments']['formData'] = formData;
						APICommands.call($parent, parentData, true);
						
						e.preventDefault();
					});
				}
			});
		}		
	}

	$(document).ready(function(){
		form();
	});

	$(window).on('apiReload.admin/*/*/form', function(){
		form();
	});	
})();