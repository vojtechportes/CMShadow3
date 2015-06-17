var uid = 'cmshadow3';
var iid = 0;

require.config({
    baseUrl: '/style/script/',
    urlArgs: "d=" + (new Date()).getTime(),
    paths: {
        bootstrap: 'bootstrap',
    }
});

require(['jquery', 'script'], function($) {
    require(['bootstrap/dropdown', 'bootstrap/modal', 'bootstrap/navigation', 'boostrap/affix', 'bootstrap/dimension'], function($) {
        console.log('BS loaded!')
    });
});