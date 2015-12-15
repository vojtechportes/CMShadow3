(function(){
	var projectListReload = function () {
		var parent = '.projectList[data-api-load]';
		$.each($(parent), function(k, el){
			var parentData = $('.projectList' + parent).data('api-load');
			APICommands.call($(el), parentData, true);
		});		
	}

	$(window).on('apiReloadForce.admin/project/list', function(){
		projectListReload();
	});
})();