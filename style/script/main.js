console.info('---------------- page has reloaded ------------------');

var uid = 'cmshadow3';
var iid = 0;
var baseUrl = '/style/script/';

require.config({
    baseUrl: baseUrl,
    urlArgs: "d=" + (new Date()).getTime(),
    paths: {
        'bootstrap': 'bootstrap',
        'aplication-modules': 'application-modules'
    }
});

var customScriptLoader = {
    'require': function(callback){
        var scripts = this.getScripts();
        if (scripts) {
            require(scripts, callback);
        }
    },
    'getScripts': function(){
        var $scripts = $('#__customScripts'), scripts = [];
        if ($scripts.length === 1) {
            $.each($scripts.data('scripts'), function(k, el){
                scripts[k] = el.replace(baseUrl.substring(1,baseUrl.length), '').replace('.js', '');
            });

            return scripts;
        }
        return false;
    }
}

require(['jquery'], function($) {
	require(['api', 'jquery.form'], function(){
		require(['application'], function(){
            //$(document).ajaxStop(function(){
                customScriptLoader.require(function(){
                    console.log('Custom Scripts loaded');
                });
            //});
        });
	});
    require(['bootstrap/dropdown', 'bootstrap/modal', 'bootstrap/navigation', 'boostrap/affix', 'bootstrap/dimension', 'bootstrap/transition', 'bootstrap/collapse'], function($) {
        console.log('BS loaded!')
    });
});