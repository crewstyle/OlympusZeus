/*!
 * tto.maps.js v1.0.2
 * https://github.com/crewstyle/TeaThemeOptions 
 *
 * This plugin change dynamically the maps preview.
 *
 * Example of JS:
 *      $('.maps').tto_maps({
 *          enable: 'enable',                           //selected class for reload button
 *          id: 'map-id',                               //container's Leaflet id
 *          inputs: ':input',                           //fields to get all configurations
 *          leafletid: 'leaflet-map-id',                //id used by Leaflet plugin
 *          map: '.maps',                               //map's container
 *          modal: '#modal-maps',                       //modal block ID
 *          reload: 'a.reload',                         //reload button
 *          updatebutton: 'a.get-maps'                  //add social network button
 *      });
 *
 * Example of HTML:
 *      --
 *
 * Copyright 2015 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var TTOMaps = function ($el,options){
        //vars
        var _tto = this;
        _tto.$el = $el;
        _tto.options = options;

        //get modal
        _tto.$modal = $(_tto.options.modal);

        //get elements
        _tto.$map = _tto.$el.find(_tto.options.map);
        _tto.$reload = _tto.$modal.find(_tto.options.reload);

        //init icon
        _tto.icon = 'http://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/images/marker-icon.png';

        //initialize maps
        _tto.map_init();

        //bind change event
        _tto.$el.find(_tto.options.updatebutton).on('click', $.proxy(_tto.open_modal, _tto));
        _tto.$reload.on('click', $.proxy(_tto.map_reload, _tto));
    };

    TTOMaps.prototype.address = null;
    TTOMaps.prototype.icon = null;
    TTOMaps.prototype.L = null;
    TTOMaps.prototype.lat = 48.882490;
    TTOMaps.prototype.layer = null;
    TTOMaps.prototype.lng = 2.371027;
    TTOMaps.prototype.map = null;
    TTOMaps.prototype.marker = null;
    TTOMaps.prototype.options = null;
    TTOMaps.prototype.zoom = null;
    TTOMaps.prototype.$el = null;
    TTOMaps.prototype.$map = null;
    TTOMaps.prototype.$modal = null;
    TTOMaps.prototype.$reload = null;

    TTOMaps.prototype.open_modal = function (e){
        e.preventDefault();
        var _tto = this;

        //open Tea TO modal box
        _tto.$modal.tto_modal({
            backdrop: '.tea-to-modal-backdrop'
        });
    };

    TTOMaps.prototype.get_datum = function (){
        var _tto = this,
            $el = _tto.$modal,
            _id = _tto.options.id;

        _tto.address = $el.find('#' + _id + '-address').val();
        _tto.zoom = parseInt($el.find('#' + _id + '-zoom').val());

        //return datum
        return {
            //globals
            address: _tto.address,
            width: $el.find('#' + _id + '-width').val(),
            height: $el.find('#' + _id + '-height').val(),
            zoom: _tto.zoom,
            //configurations
            type: $el.find('#' + _id + '-type').val(),
            //options
            dragndrop: $el.find('#' + _id + '-options-dragndrop').is(':checked'),
            streetview: $el.find('#' + _id + '-options-streetview').is(':checked'),
            zoomcontrol: $el.find('#' + _id + '-options-zoomcontrol').is(':checked'),
            mapcontrol: $el.find('#' + _id + '-options-mapcontrol').is(':checked'),
            scalecontrol: $el.find('#' + _id + '-options-scalecontrol').is(':checked'),
            pancontrol: $el.find('#' + _id + '-options-pancontrol').is(':checked'),
            rotatecontrol: $el.find('#' + _id + '-options-rotatecontrol').is(':checked'),
            rotatecontroloptions: $el.find('#' + _id + '-options-rotatecontroloptions').is(':checked'),
            scrollwheel: $el.find('#' + _id + '-options-scrollwheel').is(':checked'),
            overviewmapcontrol: $el.find('#' + _id + '-options-overviewmapcontrol').is(':checked'),
            overviewmapcontroloptions: $el.find('#' + _id + '-options-overviewmapcontroloptions').is(':checked')
        };
    };

    TTOMaps.prototype.map_init = function (){
        var _tto = this;

        //check leaflet variable
        if ('undefined' == typeof L || null == L) {
            return;
        }

        //update L
        _tto.L = L;

        //create a map
        _tto.map = L.map(_tto.options.leafletid).setView([_tto.lat, _tto.lng], 14);

        //update configurations
        _tto.map_refresh();
    };

    TTOMaps.prototype.map_marker = function (){
        var _tto = this;

        //check marker
        if (_tto.marker) {
            _tto.map.removeLayer(_tto.marker);
        }

        //build marker
        var _icon = _tto.L.icon({
            iconUrl: _tto.icon
        });

        //add marker
        _tto.marker = _tto.L.marker([_tto.lat, _tto.lng], {icon: _icon}).addTo(_tto.map);

        //define the view
        _tto.map.setView([_tto.lat, _tto.lng], _tto.zoom);
    };

    TTOMaps.prototype.map_refresh = function (){
        var _tto = this,
            _att = 'Built with ♥ by <a href="https://github.com/crewstyle/" target="_blank">Achraf Chouk</a> ~ &copy; <a href="http://osm.org/copyright" target="_blank">OpenStreetMap</a> contributors';

        //get datum and coordinates
        var _datum = _tto.get_datum();

        //update sizes
        _tto.$map.css({
            height: _datum.height
        });

        //change sizes
        _tto.map.invalidateSize();

        //build layers
        if (!_tto.layer) {
            _tto.layer = {
                "Grayscale": _tto.L.tileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}', {
                    id: 'tto.map-grayscale',
                    attribution: _att
                }),
                "Watercolor": _tto.L.tileLayer('http://stamen-tiles-{s}.a.ssl.fastly.net/watercolor/{z}/{x}/{y}.png', {
                    id: 'tto.map-watercolor',
                    attribution: _att,
                    ext: 'png'
                }),
                "Blackcolor": _tto.L.tileLayer('http://{s}.tile.stamen.com/toner/{z}/{x}/{y}.png', {
                    id: 'tto.map-black',
                    attribution: _att
                }),
            };

            //add layers
            _tto.L.control.layers(_tto.layer).addTo(_tto.map);

            //set default layer
            _tto.layer.Grayscale.addTo(_tto.map);
        }

        //define the zoom box
        if (_datum.scrollwheel) {
            _tto.map.scrollWheelZoom.enable();
        }
        else {
            _tto.map.scrollWheelZoom.disable();
        }

        //add marker
        _tto.map_marker();

        //change marker position
        _tto.map_remake();
    };

    TTOMaps.prototype.map_reload = function (e){
        e.preventDefault();
        var _tto = this;

        //update maps
        _tto.map_refresh();
        _tto.$modal.find('a.close').click();
    };

    TTOMaps.prototype.map_remake = function (){
        var _tto = this,
            _address = encodeURIComponent(_tto.address);

        //check address
        if (_address == '') {
            return;
        }

        //send JSON
        $.ajax({
            url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' + _address,
            dataType: 'json',
            success: function (data){
                if ('OK' !== data.status) {
                    return;
                }

                //set latitude and longitude
                _tto.lat = data.results[0].geometry.location.lat;
                _tto.lng = data.results[0].geometry.location.lng;

                //set well formatted address
                _tto.address = data.results[0].formatted_address;
                _tto.$modal.find('#' + _tto.options.id + '-address').val(_tto.address);

                //add marker
                _tto.map_marker();
            }
        });
    };

    var methods = {
        init: function (options){
            if (!this.length) {
                return false;
            }

            var settings = {
                enable: 'enable',
                id: 'map-id',
                inputs: ':input',
                leafletid: 'leaflet-id',
                map: '.maps',
                modal: '#modal-maps',
                reload: 'a.reload',
                updatebutton: 'a.get-maps',
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new TTOMaps($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tto_maps = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on tto_maps');
            return false;
        }
    };
})(window.jQuery);
