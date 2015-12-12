var CMSAPI = function () {
	this.path = '/admin/api/';
	this.actions = ['get', 'set', 'delete', 'update'];

	if ($('[data-api], [data-api-load]').length > 0) {
		if ($('#APILoader').length === 0)
			$('body').append('<div id="APILoader"></div>');

		if ($('#APIStatus').length === 0 && $('.page-header').length > 0)
			$('.page-header').after('<div id="APIStatus"></div>');	
	}
}

CMSAPI.prototype = {
	"validate": function (query) {
		if (query instanceof Object)
			return true;
		return false;
	},
	"setStatus": function (data) {
		var $wrapper = $('#APIStatus');
		$wrapper.html('');

		console.log(data);
		$.each(data, function(k, el){
			if (el instanceof Object) {
			if (!('class' in el))
				el.class = '';

			if (!('text' in el))
				return;


			var text = $('<div/>').html(el.text).text();
			var elm = $('<div class="' + el.class + '"></div>');
			elm.html(text);
			$wrapper.append(elm);
			}
		});
	},
	"settingsNodeRightsAssign": function (query, cberror, cbsuccess) {
		if (!this.validate(query))
			return;		

		$.post(this.path, query)
			.done(function(data) {
				if (typeof cbsuccess !== 'undefined')
					cbsuccess.call(data);
			})
			.fail(function(data) {
				if (typeof cberror !== 'undefined')
					cberror.call(data.responseJSON);
			});
	},
	"settingsModuleRightsAssign": function (query, cberror, cbsuccess) {
		if (!this.validate(query))
			return;	

		$.post(this.path, query)
			.done(function(data) {
				if (typeof cbsuccess !== 'undefined')
					cbsuccess.call(data);
			})
			.fail(function(data) {
				if (typeof cberror !== 'undefined')
					cberror.call(data.responseJSON);
			});
	},
	"settingsAPIRightsAssign": function (query, cberror, cbsuccess) {
		if (!this.validate(query))
			return;	

		$.post(this.path, query)
			.done(function(data) {
				if (typeof cbsuccess !== 'undefined')
					cbsuccess.call(data);
			})
			.fail(function(data) {
				if (typeof cberror !== 'undefined')
					cberror.call(data.responseJSON);
			});
	},
	"gadgets": function (query, cberror, cbsuccess) {
		if (!this.validate(query))
			return;

		$.post(this.path, query)
			.done(function(data) {
				if (typeof cbsuccess !== 'undefined')
					cbsuccess.call(data);
			})
			.fail(function(data) {
				if (typeof cberror !== 'undefined')
					cberror.call(data.responseJSON);
			});
	},
	"loadModule": function (query, cberror, cbsuccess) {
		if (!this.validate(query))
			return;

		var modulePath = query.module.split('/'),
			modulePathLength = modulePath.length;

		$.post(this.path, query)
			.done(function(data) {
				if (typeof cbsuccess !== 'undefined') {
					cbsuccess.call(data);
					if (modulePath[modulePathLength -1] === 'form') {
						$(window).trigger('apiReload.' + 'admin/form');
					} else {
						$(window).trigger('apiReload.' + query.module);
					}
				}
			})
			.fail(function(data) {
				if (typeof cberror !== 'undefined')
					cberror.call(data.responseJSON);
			});	
	}			
}

CMSAPI = new CMSAPI();