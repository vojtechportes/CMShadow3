(function(){
	var update = function () {
		var folder = '[data-haschildpages]',
			parent = '[data-api-load]',
			actions = {'edit': '[data-edit]', 'delete': '[data-delete]'};

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

		if ($(actions.edit).length > 0) {
			var $action = $(actions.edit);
			
			$action.off('click.quickedit').on('click.quickedit', function(e){
				e.preventDefault();

				var $this = $(this),
					arguments = $this.data('arguments');
					$parent = $($this.data('target')).find('[data-api-load]');
				
				if ($parent.length > 0) {
					var parentData = $.extend({}, $parent.data('api-load'));
						parentData['arguments'] = $.extend({}, parentData['arguments'], arguments);
					console.log(parentData);
					APICommands.call($parent, parentData, true);
				}
			});
		}
	}

	var reload = function () {
		var parent = '.folderList[data-api-load]';
		$.each($(parent), function(k, el){
			var parentData = $(el).data('api-load');
			APICommands.call($(el), parentData, true);
		});		
	}

	$(document).ready(function(){
		update();
	});

	$(window).on('apiReload.admin/page/folders', function(){
		update();
	});

	$(window).on('apiReloadForce.admin/page/folders', function(){
		reload();
	});
})();