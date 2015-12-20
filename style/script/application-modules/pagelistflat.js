(function(){
	var reload = function () {
		var parent = '.pageList.flatList[data-api-load]';
		$.each($(parent), function(k, el){
			var parentData = $(parent).data('api-load');
			APICommands.call($(el), parentData, true);
		});		
	}

	$(window).on('apiReloadForce.admin/page/pages', function(){
		reload();
	});
})();