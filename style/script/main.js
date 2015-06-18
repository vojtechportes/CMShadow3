var uid = 'cmshadow3';
var iid = 0;

require.config({
    baseUrl: '/style/script/',
    urlArgs: "d=" + (new Date()).getTime(),
    paths: {
        bootstrap: 'bootstrap',
    }
});

require(['jquery'], function($) {
	require(['api'], function(){
		if ($('[data-api]').length > 0) {
			$('[data-api]').on('click', function(e){
				e.preventDefault();
				e.stopPropagation();

				var data = $(this).data('api');

				console.log(data);

				switch (data['command']) {
					case 'settingsNodeRightsAssign':
						//OCMAP.(data);
						break;
					case 'settingsModuleRightsAssign':
						CMSAPI[data['command']](data);
						break;
					case 'settingsAPIRightsAssign':
						CMSAPI.settingsAPIRightsAssign(data);
						break;
				}
			});
		}
	});
    require(['bootstrap/dropdown', 'bootstrap/modal', 'bootstrap/navigation', 'boostrap/affix', 'bootstrap/dimension'], function($) {
        console.log('BS loaded!')
    });
});