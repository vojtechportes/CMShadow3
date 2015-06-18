var CMSAPI = function () {
	this.path = '/admin/api/';
	this.actions = ['get', 'set', 'delete'];

	if ($('[data-api]').length > 0) {
		if ($('#APILoader').length === 0)
			$('body').append('<div id="APILoader"></div>');

		if ($('#APIStatus').length === 0 && $('.page-header').length > 0)
			$('.page-header').after('<div id="APIStatus" class="alert info hide"><p></p></div>');	
	}
}

CMSAPI.prototype = {
	"validate": function (query) {
		if (query instanceof Object && Object.keys(query).length === 4)
			return true;
		return false;
	},
	"settingsNodeRightsAssign": function (query) {
		if (!this.validate(item, action))
			return;		

		$.post(this.path + "?" + $.param(query), function(data) {
			console.log(data);
		});
	},
	"settingsModuleRightsAssign": function (query) {
		if (!this.validate(item, action))
			return;	

		$.post(this.path + "?" + $.param(query), function(data) {
			
		});
	},
	"settingsAPIRightsAssign": function (query) {
		console.log(query);

		if (!this.validate(query))
			return;	

		$.post(this.path + "?" + $.param(query), function(data) {
			console.log(data);
		});
	}
}

CMSAPI = new CMSAPI();