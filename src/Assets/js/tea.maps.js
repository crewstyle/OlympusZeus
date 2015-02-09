/* =====================================================
 * tea.maps.js v1.0.0
 * https://github.com/Takeatea/tea_theme_options
 * =====================================================
 * ~ Copyright since 2014 ~
 * Take a Tea (http://takeatea.com)
 * Tea Theme Options (http://teato.me)
 * =====================================================
 * This plugin change dynamically the maps preview.
 * =====================================================
 * Example:
 *      $('.maps').tea_maps();
 * ===================================================== */

(function ($){
    "use strict";

    var Tea_maps = function ($el){
        //vars
        var _tea = this;
        _tea.$el = $el;
        _tea.$map = _tea.$el.find('.tea-maps');
        _tea.$rel = _tea.$el.find('a.tea-reload');

        //initialize maps
        _tea.initMap();

        //bind change event
        _tea.$el.find('.tea-conf :input').on('change', $.proxy(_tea.onchange, _tea));
        _tea.$rel.on('click', $.proxy(_tea.onclick, _tea));
    };

    Tea_maps.prototype.map = null;
    Tea_maps.prototype.L = null;
    Tea_maps.prototype.lat = 48.882490;
    Tea_maps.prototype.lng = 2.371027;
    Tea_maps.prototype.$el = null;
    Tea_maps.prototype.$map = null;
    Tea_maps.prototype.$rel = null;

    Tea_maps.prototype.getCoordinates = function (address){
        var _tea = this,
            _add = encodeURIComponent(address);

        //send JSON
        $.ajax({
            url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' + _add,
            dataType: 'json',
            success: function (data){
                _tea.lat = data.results[0].geometry.location.lat;
                _tea.lng = data.results[0].geometry.location.lng;
            }
        });
    };

    Tea_maps.prototype.getDatum = function (id){
        var _tea = this;

        //return datum
        return {
            //globals
            address: _tea.$el.find('#'+id+'-address').val(),
            marker: _tea.$el.find('#'+id+'-marker-url').val(),
            width: _tea.$el.find('#'+id+'-width').val(),
            height: _tea.$el.find('#'+id+'-height').val(),
            //configurations
            zoom: parseInt(_tea.$el.find('#'+id+'-zoom').val()),
            type: _tea.$el.find('#'+id+'-type').val(),
            //options
            dragndrop: _tea.$el.find('#'+id+'-params_dragndrop').is(':checked'),
            streetview: _tea.$el.find('#'+id+'-params_streetview').is(':checked'),
            zoomcontrol: _tea.$el.find('#'+id+'-params_zoomcontrol').is(':checked'),
            mapcontrol: _tea.$el.find('#'+id+'-params_mapcontrol').is(':checked'),
            scalecontrol: _tea.$el.find('#'+id+'-params_scalecontrol').is(':checked'),
            pancontrol: _tea.$el.find('#'+id+'-params_pancontrol').is(':checked'),
            rotatecontrol: _tea.$el.find('#'+id+'-params_rotatecontrol').is(':checked'),
            rotatecontroloptions: _tea.$el.find('#'+id+'-params_rotatecontroloptions').is(':checked'),
            scrollwheel: _tea.$el.find('#'+id+'-params_scrollwheel').is(':checked'),
            overviewmapcontrol: _tea.$el.find('#'+id+'-params_overviewmapcontrol').is(':checked'),
            overviewmapcontroloptions: _tea.$el.find('#'+id+'-params_overviewmapcontroloptions').is(':checked')
        };
    };

    Tea_maps.prototype.initMap = function (){
        var _tea = this,
            _id = _tea.$el.attr('data-id');

        //check leaflet variable
        if ('undefined' == typeof L || null == L) {
            return;
        }

        //update L
        _tea.L = L;

        //create a map
        _tea.map = L.map('tea-leaflet-'+_id).setView([_tea.lat, _tea.lng], 14);

        //update configurations
        _tea.refreshMap();
    };

    Tea_maps.prototype.onchange = function (e){
        e.preventDefault();
        var _tea = this;

        //update reload button
        _tea.$rel.addClass('enable');
    };

    Tea_maps.prototype.onclick = function (e){
        e.preventDefault();
        var _tea = this;

        //update maps
        _tea.refreshMap();
    };

    Tea_maps.prototype.refreshMap = function (){
        var _tea = this,
            _id = _tea.$el.attr('data-id'),
            _url = 'http://{s}.tiles.mapbox.com/v3/{id}/{z}/{x}/{y}.png',
            _att = 'brewed by <a href="http://www.takeatea.com">Take a tea</a> ~ &copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors';

        //hide reload button
        _tea.$rel.removeClass('enable');

        //get datum and coordinates
        var _datum = _tea.getDatum(_id);
        _tea.getCoordinates(_datum.address);

        //update sizes
        _tea.$map.css({
            height: _datum.height,
            width: _datum.width
        });
        _tea.$rel.css({
            lineHeight: _datum.height + 'px'
        });

        //change sizes
        _tea.map.invalidateSize();

        //build marker
        var teaicon = L.icon({
            iconUrl: _datum.marker,
            iconSize: [60, 83]
        });

        //add marker
        _tea.L.marker([_tea.lat, _tea.lng], {icon: teaicon}).addTo(_tea.map);

        //build layers
        var tealayers = {
            "Grayscale": _tea.L.tileLayer(_url, {
                id: 'examples.map-20v6611k',
                attribution: _att
            }),
            "Streets": _tea.L.tileLayer(_url, {
                id: 'examples.map-i875mjb7',
                attribution: _att
            })
        };

        //add layers
        if (_datum.mapcontrol) {
            _tea.L.control.layers(tealayers).addTo(_tea.map);
        }
        else {
            _tea.L.tileLayer(_url, {
                attribution: _att,
                id: 'examples.map-20v6611k'
            }).addTo(_tea.map);
        }

        //define the view
        _tea.map.setView([_tea.lat, _tea.lng], _datum.zoom);

        //define the zoom box
        if (_datum.scrollwheel) {
            _tea.map.scrollWheelZoom.enable();
        }
        else {
            _tea.map.scrollWheelZoom.disable();
        }
    };

    var methods = {
        init: function (){
            if (!this.length) {
                return false;
            }

            return this.each(function (){
                new Tea_maps($(this));
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tea_maps = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method ' + method + ' does not exist on tea_maps');
            return false;
        }
    };
})(window.jQuery);
