(function(){
	var changeState = function () {
		var $workflow = $('.workflow[data-api-load]');

		if ($workflow.length > 0) {
			var $elements = $workflow.find('a[data-state]');

			$elements.off('click.workflow').on('click.workflow', function(e){
				e.preventDefault();
				e.stopPropagation();

				var $this = $(this),
					state = $this.data('state'),
					$parent = $this.parents('[data-api-load]'),
					parentData = $.extend({}, $parent.data('api-load'), {'changeState': state});

				APICommands.call($parent, parentData, true, false, function(){
					if (typeof parentData['arguments']['dependencies'] !== 'undefined') {
						$.each(parentData['arguments']['dependencies'], function(k, module){
							$(window).trigger('apiReloadForce.' + module);
						});
					}				
				});
			});
		}
	}

	var workflowPreview = function () {	
		if ($('#workflowpreview').length > 0) {
			var $workflow = $('#workflowpreview');
			var $canvas = $workflow.find('.workflow_canvas');

			$(window).on('shown.bs.modal', function(e){
				if ($(e.target)[0] == $workflow[0]) {
					$.each($canvas, function(k, item){
						console.log(item);
						var $item = $(item),
							data = $item.data('directions'),
							width = $item.outerWidth(),
							_width = width * 0.33334,
							height = $item.height(),
							offsetv = height * 0.075,
							offseth = width * 0.01,
							dirTargets =  {
								'left': (_width * 0.5) - 10,
								'center': (_width + (_width * 0.5)),
								'right': (2 * _width + (_width * 0.5)) + 10
							};

						$item.attr('width', width);
						$item.attr('height', height);

						$.each(data, function(k, v){
							//fromx, fromy, tox, toy

							var offset = 0;
							if (v[0] === 'center' && v[1] === 'left')
								offset = offseth * -1;

							if (v[0] === 'center' && v[1] === 'right')
								offset = offseth;

							if (typeof v[2] === 'undefined')
								v[2] = false;
							drawArrow.call($item, dirTargets[v[0]] + offset, offsetv, dirTargets[v[1]] + offset, height - offsetv, 1, '#555555', v[2]);
						});
					});
				}
			});
		}
	}

	$(document).ready(function(){
		workflowPreview();
		changeState();
	});

	$(window).on('apiReload.admin/project/workflow', function(){
		workflowPreview();
		changeState();
	});

	$(window).on('resize.workflow', function(){
		workflowPreview();
	});

})();