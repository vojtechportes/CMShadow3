var CMSAPI = function () {
	this.path = '/admin/api/';
	this.actions = ['get', 'set', 'delete'];

	if ($('[data-api]').length > 0) {
		if ($('#APILoader').length === 0)
			$('body').append('<div id="APILoader"></div>');

		if ($('#APIStatus').length === 0 && $('.page-header').length > 0)
			$('.page-header').after('<div id="APIStatus"></div>');	
	}
}

CMSAPI.prototype = {
	"validate": function (query) {
		if (query instanceof Object && Object.keys(query).length === 4)
			return true;
		return false;
	},
	"setStatus": function (data) {
		var $wrapper = $('#APIStatus');
		$wrapper.html('');

		$.each(data, function(k, el){
			console.log(el);
			if (!('class' in el))
				el.class = '';

			if (!('text' in el))
				return;

			var text = $('<div/>').html(el.text).text();
			var elm = $('<div class="' + el.class + '"></div>');
			elm.html(text);
			$wrapper.append(elm);
		});
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
	"settingsAPIRightsAssign": function (query, cberror, cbsuccess) {
		if (!this.validate(query))
			return;	

		$.post(this.path + "?" + $.param(query))
			.done(function(data) {
				if (typeof cbsuccess !== 'undefined')
					cbsuccess.call(data);
			})
			.fail(function(data) {
				if (typeof cberror !== 'undefined')
					cberror.call(data.responseJSON);
			});
	}
}

CMSAPI = new CMSAPI();