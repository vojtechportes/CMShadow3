function toggleState(icons, actions) {
	var data = this.data('api');
	if (data['action'] === actions[1]) {
		data['action'] = actions[0];
		this.data('api', data);
	} else {
		data['action'] = actions[1];
		this.data('api', data);
	}

	if (this.hasClass(icons[1])) {
		this.addClass(icons[0]).removeClass(icons[1]);
	} else {
		this.addClass(icons[1]).removeClass(icons[0]);
	}	
}

if ($('[data-api]').length > 0) {
	$('[data-api]').on('click', function(e){
		e.preventDefault();
		e.stopPropagation();

		var data = $(this).data('api');
		var $el = $(this);

		switch (data['command']) {
			case 'settingsNodeRightsAssign':
				CMSAPI.settingsNodeRightsAssign(data,
					function(){
						CMSAPI.setStatus(this);				
					},
					function(){
						CMSAPI.setStatus(this);	
						toggleState.call($el, ['glyphicon-ok', 'glyphicon-plus'], ['set', 'delete']);
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
						toggleState.call($el, ['glyphicon-ok', 'glyphicon-plus'], ['set', 'delete']);
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
						toggleState.call($el, ['glyphicon-ok', 'glyphicon-plus'], ['set', 'delete']);
					}
				);
				break;
		}
	});
}