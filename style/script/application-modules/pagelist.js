(function(){
	var pageList = function () {
		var folder = '[data-haschildpages]',
			parent = '[data-api-load]';

		if ($(folder).length > 0) {
			var $folder = $(folder),
				$parent = $(parent);

				$folder.off('click').on('click', function(e){
					e.preventDefault();
					var arguments = $(this).data('arguments'),
						$parent = $(this).parents(parent);
					
					var parentData = $parent.data('api-load');
					parentData['arguments'] = arguments;

					APICommands.call($parent, parentData, true);
				});
		}		
	}

	$(document).ready(function(){
		pageList();
	});

	$(window).on('apiReload.admin/page/folders', function(){
		pageList();
	});
})();