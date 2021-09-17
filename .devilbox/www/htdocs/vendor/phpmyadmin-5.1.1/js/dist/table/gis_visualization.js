"use strict";

/**
 * @fileoverview    functions used for visualizing GIS data
 *
 * @requires    jquery
 * @requires    vendor/jquery/jquery.svg.js
 * @requires    vendor/jquery/jquery.mousewheel.js
 */

/* global drawOpenLayers */
// templates/table/gis_visualization/gis_visualization.twig
// Constants
var zoomFactor = 1.5;
var defaultX = 0;
var defaultY = 0; // Variables

var x = 0;
var y = 0;
var scale = 1;
var svg;
/**
 * Zooms and pans the visualization.
 */

function zoomAndPan() {
  var g = svg.getElementById('groupPanel');

  if (!g) {
    return;
  }

  g.setAttribute('transform', 'translate(' + x + ', ' + y + ') scale(' + scale + ')');
  var id;
  var circle;
  $('circle.vector').each(function () {
    id = $(this).attr('id');
    circle = svg.getElementById(id);
    $(svg).on('change', circle, {
      r: 3 / scale,
      'stroke-width': 2 / scale
    });
  });
  var line;
  $('polyline.vector').each(function () {
    id = $(this).attr('id');
    line = svg.getElementById(id);
    $(svg).on('change', line, {
      'stroke-width': 2 / scale
    });
  });
  var polygon;
  $('path.vector').each(function () {
    id = $(this).attr('id');
    polygon = svg.getElementById(id);
    $(svg).on('change', polygon, {
      'stroke-width': 0.5 / scale
    });
  });
}
/**
 * Initially loads either SVG or OSM visualization based on the choice.
 */


function selectVisualization() {
  if ($('#choice').prop('checked') !== true) {
    $('#openlayersmap').hide();
  } else {
    $('#placeholder').hide();
  }
}
/**
 * Adds necessary styles to the div that contains the openStreetMap.
 */


function styleOSM() {
  var $placeholder = $('#placeholder');
  var cssObj = {
    'border': '1px solid #aaa',
    'width': $placeholder.width(),
    'height': $placeholder.height(),
    'float': 'right'
  };
  $('#openlayersmap').css(cssObj);
}
/**
 * Loads the SVG element and make a reference to it.
 */


function loadSVG() {
  var $placeholder = $('#placeholder');
  $placeholder.svg({
    onLoad: function onLoad(svgRef) {
      svg = svgRef;
    }
  }); // Removes the second SVG element unnecessarily added due to the above command

  $placeholder.find('svg').eq(1).remove();
}
/**
 * Adds controllers for zooming and panning.
 */


function addZoomPanControllers() {
  var $placeholder = $('#placeholder');

  if ($('#placeholder').find('svg').length > 0) {
    var themeImagePath = $('#themeImagePath').val(); // add panning arrows

    $('<img class="button" id="left_arrow" src="' + themeImagePath + 'west-mini.png">').appendTo($placeholder);
    $('<img class="button" id="right_arrow" src="' + themeImagePath + 'east-mini.png">').appendTo($placeholder);
    $('<img class="button" id="up_arrow" src="' + themeImagePath + 'north-mini.png">').appendTo($placeholder);
    $('<img class="button" id="down_arrow" src="' + themeImagePath + 'south-mini.png">').appendTo($placeholder); // add zooming controls

    $('<img class="button" id="zoom_in" src="' + themeImagePath + 'zoom-plus-mini.png">').appendTo($placeholder);
    $('<img class="button" id="zoom_world" src="' + themeImagePath + 'zoom-world-mini.png">').appendTo($placeholder);
    $('<img class="button" id="zoom_out" src="' + themeImagePath + 'zoom-minus-mini.png">').appendTo($placeholder);
  }
}
/**
 * Resizes the GIS visualization to fit into the space available.
 */


function resizeGISVisualization() {
  var $placeholder = $('#placeholder');
  var oldWidth = $placeholder.width();
  var visWidth = $('#div_view_options').width() - 48; // Assign new value for width

  $placeholder.width(visWidth);
  $('svg').attr('width', visWidth); // Assign the offset created due to resizing to defaultX and center the svg.

  defaultX = (visWidth - oldWidth) / 2;
  x = defaultX;
  y = 0;
  scale = 1;
}
/**
 * Initialize the GIS visualization.
 */


function initGISVisualization() {
  // Loads either SVG or OSM visualization based on the choice
  selectVisualization(); // Resizes the GIS visualization to fit into the space available

  resizeGISVisualization();

  if (typeof ol !== 'undefined') {
    // Adds necessary styles to the div that contains the openStreetMap
    styleOSM();
  } // Loads the SVG element and make a reference to it


  loadSVG(); // Adds controllers for zooming and panning

  addZoomPanControllers();
  zoomAndPan();
}

function drawOpenLayerMap(openLayerCreate) {
  $('#placeholder').hide();
  $('#openlayersmap').show(); // Function doesn't work properly if #openlayersmap is hidden

  if (!openLayerCreate) {
    // Draws openStreetMap with openLayers
    drawOpenLayers();
    return 1;
  }

  return 0;
}

function getRelativeCoords(e) {
  var position = $('#placeholder').offset();
  return {
    x: e.pageX - position.left,
    y: e.pageY - position.top
  };
}
/**
 * Ajax handlers for GIS visualization page
 *
 * Actions Ajaxified here:
 *
 * Zooming in and zooming out on mousewheel movement.
 * Panning the visualization on dragging.
 * Zooming in on double clicking.
 * Zooming out on clicking the zoom out button.
 * Panning on clicking the arrow buttons.
 * Displaying tooltips for GIS objects.
 */

/**
 * Unbind all event handlers before tearing down a page
 */


AJAX.registerTeardown('table/gis_visualization.js', function () {
  $(document).off('click', '#choice');
  $(document).off('mousewheel', '#placeholder');
  $(document).off('dragstart', 'svg');
  $(document).off('mouseup', 'svg');
  $(document).off('drag', 'svg');
  $(document).off('dblclick', '#placeholder');
  $(document).off('click', '#zoom_in');
  $(document).off('click', '#zoom_world');
  $(document).off('click', '#zoom_out');
  $(document).off('click', '#left_arrow');
  $(document).off('click', '#right_arrow');
  $(document).off('click', '#up_arrow');
  $(document).off('click', '#down_arrow');
  $('.vector').off('mousemove').off('mouseout');
});
AJAX.registerOnload('table/gis_visualization.js', function () {
  var openLayerCreate = 0; // If we are in GIS visualization, initialize it

  if ($('#gis_div').length > 0) {
    initGISVisualization();
  }

  if ($('#choice').prop('checked') === true) {
    openLayerCreate = drawOpenLayerMap(openLayerCreate);
  }

  if (typeof ol === 'undefined') {
    $('#choice, #labelChoice').hide();
  }

  $(document).on('click', '#choice', function () {
    if ($(this).prop('checked') === false) {
      $('#placeholder').show();
      $('#openlayersmap').hide();
    } else {
      openLayerCreate = drawOpenLayerMap(openLayerCreate);
    }
  });
  $(document).on('mousewheel', '#placeholder', function (event, delta) {
    var relCoords = getRelativeCoords(event);

    if (delta > 0) {
      // zoom in
      scale *= zoomFactor; // zooming in keeping the position under mouse pointer unmoved.

      x = relCoords.x - (relCoords.x - x) * zoomFactor;
      y = relCoords.y - (relCoords.y - y) * zoomFactor;
      zoomAndPan();
    } else {
      // zoom out
      scale /= zoomFactor; // zooming out keeping the position under mouse pointer unmoved.

      x = relCoords.x - (relCoords.x - x) / zoomFactor;
      y = relCoords.y - (relCoords.y - y) / zoomFactor;
      zoomAndPan();
    }

    return true;
  });
  var dragX = 0;
  var dragY = 0;
  $('svg').draggable({
    helper: function helper() {
      return $('<div>'); // Give a fake element to be used for dragging display
    }
  });
  $(document).on('dragstart', 'svg', function (event, dd) {
    $('#placeholder').addClass('placeholderDrag');
    dragX = Math.round(dd.offset.left);
    dragY = Math.round(dd.offset.top);
  });
  $(document).on('mouseup', 'svg', function () {
    $('#placeholder').removeClass('placeholderDrag');
  });
  $(document).on('drag', 'svg', function (event, dd) {
    var newX = Math.round(dd.offset.left);
    x += newX - dragX;
    dragX = newX;
    var newY = Math.round(dd.offset.top);
    y += newY - dragY;
    dragY = newY;
    zoomAndPan();
  });
  $(document).on('dblclick', '#placeholder', function (event) {
    scale *= zoomFactor; // zooming in keeping the position under mouse pointer unmoved.

    var relCoords = getRelativeCoords(event);
    x = relCoords.x - (relCoords.x - x) * zoomFactor;
    y = relCoords.y - (relCoords.y - y) * zoomFactor;
    zoomAndPan();
  });
  $(document).on('click', '#zoom_in', function (e) {
    e.preventDefault(); // zoom in

    scale *= zoomFactor;
    var $placeholder = $('#placeholder').find('svg');
    var width = $placeholder.attr('width');
    var height = $placeholder.attr('height'); // zooming in keeping the center unmoved.

    x = width / 2 - (width / 2 - x) * zoomFactor;
    y = height / 2 - (height / 2 - y) * zoomFactor;
    zoomAndPan();
  });
  $(document).on('click', '#zoom_world', function (e) {
    e.preventDefault();
    scale = 1;
    x = defaultX;
    y = defaultY;
    zoomAndPan();
  });
  $(document).on('click', '#zoom_out', function (e) {
    e.preventDefault(); // zoom out

    scale /= zoomFactor;
    var $placeholder = $('#placeholder').find('svg');
    var width = $placeholder.attr('width');
    var height = $placeholder.attr('height'); // zooming out keeping the center unmoved.

    x = width / 2 - (width / 2 - x) / zoomFactor;
    y = height / 2 - (height / 2 - y) / zoomFactor;
    zoomAndPan();
  });
  $(document).on('click', '#left_arrow', function (e) {
    e.preventDefault();
    x += 100;
    zoomAndPan();
  });
  $(document).on('click', '#right_arrow', function (e) {
    e.preventDefault();
    x -= 100;
    zoomAndPan();
  });
  $(document).on('click', '#up_arrow', function (e) {
    e.preventDefault();
    y += 100;
    zoomAndPan();
  });
  $(document).on('click', '#down_arrow', function (e) {
    e.preventDefault();
    y -= 100;
    zoomAndPan();
  });
  /**
   * Detect the mousemove event and show tooltips.
   */

  $('.vector').on('mousemove', function (event) {
    var contents = Functions.escapeHtml($(this).attr('name')).trim();
    $('#tooltip').remove();

    if (contents !== '') {
      $('<div id="tooltip">' + contents + '</div>').css({
        position: 'absolute',
        top: event.pageY + 10,
        left: event.pageX + 10,
        border: '1px solid #fdd',
        padding: '2px',
        'background-color': '#fee',
        opacity: 0.90
      }).appendTo('body').fadeIn(200);
    }
  });
  /**
   * Detect the mouseout event and hide tooltips.
   */

  $('.vector').on('mouseout', function () {
    $('#tooltip').remove();
  });
});