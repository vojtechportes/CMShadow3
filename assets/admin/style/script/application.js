function APIToggleState(icons, actions) {
	var data = this.data('api');

	if (actions !== false) {
		if (data['action'] === actions[1]) {
			data['action'] = actions[0];
			this.data('api', data);
		} else {
			data['action'] = actions[1];
			this.data('api', data);
		}
	}

	if (icons !== false) {
		if (this.hasClass(icons[1])) {
			this.addClass(icons[0]).removeClass(icons[1]);
		} else {
			this.addClass(icons[1]).removeClass(icons[0]);
		}	
	}
}

function APICommands (data, load, html, callback) {
	var $el = this;
	var callback = callback;

	if (typeof load === 'undefined')
		load = false;

	if (typeof html === 'undefined')
		html = false;	

	if (typeof callback === 'undefined')
		callback = function(){};

	switch (data['command']) {
		case 'settingsNodeRightsAssign':
			CMSAPI.settingsNodeRightsAssign(data,
				function(){
					CMSAPI.setStatus(this);				
				},
				function(){
					CMSAPI.setStatus(this);	
					APIToggleState.call($el, ['glyphicon-ok', 'glyphicon-plus'], ['set', 'delete']);
				}
			);
			break;
		case 'settingsModuleRightsAssign':
			CMSAPI.settingsModuleRightsAssign(data,
				function(){
					CMSAPI.setStatus(this);				
				},
				function(){
					CMSAPI.setStatus(this);
					APIToggleState.call($el, ['glyphicon-ok', 'glyphicon-plus'], ['set', 'delete']);
				}
			);
			break;
		case 'settingsAPIRightsAssign':
			CMSAPI.settingsAPIRightsAssign(data,
				function(){
					CMSAPI.setStatus(this);				
				},
				function(){
					CMSAPI.setStatus(this);	
					APIToggleState.call($el, ['glyphicon-ok', 'glyphicon-plus'], ['set', 'delete']);
				}
			);
			break;
		case 'gadgets':
			CMSAPI.gadgets(data,
				function(){
					CMSAPI.setStatus(this);				
				},
				function(){
					if (!load) {
						CMSAPI.setStatus(this);
					} else {
						$el.text(JSON.stringify(this));
					}
				}
			);
			break;	
		case 'loadModule':
			CMSAPI.loadModule(data,
				function(){
					CMSAPI.setStatus(this);				
				},
				function(){
					if (!load) {
						CMSAPI.setStatus(this);
					} else {
						$.each(this, function(k, module){
							$el.html(module['__html']);
						});	
					}

					callback.call(this);
				}
			);
			break;	
	}
}

if ($('[data-api]').length > 0) {
	$('[data-api]').on('click', function(e){
		e.preventDefault();
		e.stopPropagation();

		var data = $(this).data('api');
		var $el = $(this);

		APICommands.call($el, data);
	});
}

if ($('[data-api-load]').length > 0) {
	$.each($('[data-api-load]'), function(k, el) {
		if (typeof $(el).attr('data-api-prevent') === 'undefined') {
			var data = $(el).data('api-load');
			if (typeof data['load-to'] !== 'undefined') {
				var $el = $(data['load-to']);
			} else {
				var $el = $(el);
			}

			APICommands.call($el, data, true);
		}
	});
}