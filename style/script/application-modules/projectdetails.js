(function(){
	var projectDetailsReload = function () {
		var parent = '.projectDetails[data-api-load]';
		$.each($(parent), function(k, el){
			var parentData = $('.projectDetails' + parent).data('api-load');
			APICommands.call($(el), parentData, true);
		});		
	}

	$(window).on('apiReloadForce.admin/project/details', function(){
		projectDetailsReload();
	});
})();