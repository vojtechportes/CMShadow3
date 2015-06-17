/* ========================================================================
 * Bootstrap: dimension.js v3.3.1
 * ========================================================================
 * Copyright 2011-2015 CMShadow
 * ======================================================================== */


+function ($) {
  'use strict';

  var Dimension = function (element) {
    this.element = $(element);
  }

  Dimension.prototype.setFullHeight = function (parent) {
    var $this = this.element;
    var height = {}
    height.parent = $(parent).height();
    console.log($this);
    $this.css('height', height.parent);
    console.log($this);
  }

  Dimension.prototype.setEqualHeights = function (elements) {
    var height = {}
  } 


  function Plugin(els) {
    $.each($(els), function () {
      console.log(this);
      var data = new Dimension (this);
      switch ($(this).data('action')) {
        case 'fullHeight':
          var parent = $(this).data('parent');
          parent = $(parent).first();
          data.setFullHeight(parent);
          break;
      }
    })
  }

  var old = $.fn.dimension

  $.fn.dimension              = Plugin
  $.fn.dimension.Constructor  = Dimension


  // TAB NO CONFLICT
  // ===============

  $.fn.dimension.noConflict = function () {
    $.fn.tab = old
    return this
  }

  // TAB DATA-API
  // ============

  var eventHandler = function (els) {
    console.log('eventHandler');
    Plugin(els)
  }

  $(window)
    .load(eventHandler('[data-action][data-parent]'))
  $(window)
    .resize(function(){ eventHandler('[data-action][data-parent]')})

  $(document)
      

}(jQuery);
