(function(){
	var reload = function () {
		var parent = '.layoutList[data-api-load]';
		$.each($(parent), function(k, el){
			var parentData = $('.layoutList' + parent).data('api-load');
			APICommands.call($(el), parentData, true);
		});		
	}

	$(window).on('apiReloadForce.admin/layout/list', function(){
		reload();
	});
})();