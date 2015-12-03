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
		require(['application']);
	});
    require(['bootstrap/dropdown', 'bootstrap/modal', 'bootstrap/navigation', 'boostrap/affix', 'bootstrap/dimension', 'bootstrap/transition', 'bootstrap/collapse'], function($) {
        console.log('BS loaded!')
    });
});