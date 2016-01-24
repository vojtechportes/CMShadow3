(function(){
	var reload = function () {
		var parent = '.layoutForm[data-api-load]';
		$.each($(parent), function(k, el){
			var parentData = $('.layoutForm' + parent).data('api-load');
			APICommands.call($(el), parentData, true);
		});		
	}

	$(window).on('apiReloadForce.admin/layout/api/form', function(){
		console.log('apiReloadForce.admin/layout/api/form');
		reload();
	});	
})();