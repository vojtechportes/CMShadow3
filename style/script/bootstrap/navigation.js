/* ========================================================================
 * Bootstrap: tab.js v3.3.1
 * http://getbootstrap.com/javascript/#tabs
 * ========================================================================
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // TAB CLASS DEFINITION
  // ====================

  var Navigation = function (element, options) {
    this.element  = $(element)
    this.options  = $.extend({}, Navigation.DEFAULTS, options)
    this.$trigger = $(this.options.trigger).filter('[href="#' + element.id + '"], [data-target="#' + element.id + '"]')
  }

  Navigation.prototype.toggle = function () {
    var controls = this.options.controls;
    var $target = $(this.options.target);
    var $parent = $(controls.container);
    var $navigation = $(controls.navigationContainer).first();
    var $this = $(this.element);
    var settings = {};
    settings.parent = {}; 
    settings.nav = {};     
    $target.toggleClass('in');

    if ($target.hasClass('in')) {
      settings.parent.left = controls.closed;
      settings.parent.right = -controls.closed;
      
      $target.animate(settings.nav.start, 600); 
      $navigation.animate(settings.parent, 600); 
      $parent.animate(settings.parent, 600);  
    } else {
      settings.parent.left = controls.opened;
      settings.parent.right = controls.opened;

      $target.animate(settings.nav, 600);       
      $navigation.animate(settings.parent, 600); 
      $parent.animate(settings.parent, 600);  
    }
  }

  Navigation.prototype.init = function () {
    var controls = this.options.controls;
    var $target = $(this.options.target);
    var $parent = $(controls.container);
    var $this = $(this.element);   
    var css = {};

    css['position'] = 'relative';
    css[controls.align] = 0;
    $parent.css(css);
  }

  Navigation.DEFAULTS = {
    toggle: true,
    trigger: '[data-toggle="navigation"]'
  } 

  // TAB PLUGIN DEFINITION
  // =====================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('bs.navigation')
      var options = $.extend({}, Navigation.DEFAULTS, $this.data(), typeof option == 'object' && option)

      if (!data && options.toggle && option == 'init') options.toggle = false
      if (!data) $this.data('bs.navigation', (data = new Navigation(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  var old = $.fn.navigation

  $.fn.navigation             = Plugin
  $.fn.navigation.Constructor = Navigation


  // TAB NO CONFLICT
  // ===============

  $.fn.navigation.noConflict = function () {
    $.fn.navigation = old
    return this
  }


  // TAB DATA-API
  // ============

  var clickHandler = function (e) {
    e.preventDefault()
    Plugin.call($(this), 'toggle')
  }

  var readyHandler = function (e) {
    var els = '[data-toggle="navigation"]'
    Plugin.call($(els), 'init')
  }

  $(document)
    .on('click.bs.naviagtion.data-api', '[data-toggle="navigation"]', clickHandler)
    .ready(readyHandler)

}(jQuery);
