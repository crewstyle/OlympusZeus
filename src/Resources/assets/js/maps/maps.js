/*!
 * maps.js v2.0.0
 * https://github.com/crewstyle/OlympusZeus
 *
 * This plugin change dynamically the maps preview.
 *
 * Example of JS:
 *      $('.maps').olMaps({
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
 * Copyright 2016 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var OlMaps = function ($el,options){
        //vars
        var _ol = this;
        _ol.$el = $el;
        _ol.options = options;

        //get modal
        _ol.$modal = $(_ol.options.modal);

        //get elements
        _ol.$map = _ol.$el.find(_ol.options.map);
        _ol.$reload = _ol.$modal.find(_ol.options.reload);

        //init icon
        _ol.icon = 'http://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/images/marker-icon.png';

        //initialize maps
        _ol.map_init();

        //bind change event
        _ol.$el.find(_ol.options.updatebutton).on('click', $.proxy(_ol.open_modal, _ol));
        _ol.$reload.on('click', $.proxy(_ol.map_reload, _ol));
    };

    OlMaps.prototype.address = null;
    OlMaps.prototype.icon = null;
    OlMaps.prototype.L = null;
    OlMaps.prototype.lat = 48.882490;
    OlMaps.prototype.layer = null;
    OlMaps.prototype.lng = 2.371027;
    OlMaps.prototype.map = null;
    OlMaps.prototype.marker = null;
    OlMaps.prototype.options = null;
    OlMaps.prototype.zoom = null;
    OlMaps.prototype.$el = null;
    OlMaps.prototype.$map = null;
    OlMaps.prototype.$modal = null;
    OlMaps.prototype.$reload = null;

    OlMaps.prototype.open_modal = function (e){
        e.preventDefault();
        var _ol = this;

        //open modal box
        _ol.$modal.olModal({
            backdrop: '.olz-modal-backdrop'
        });
    };

    OlMaps.prototype.get_datum = function (){
        var _ol = this,
            $el = _ol.$modal,
            _id = _ol.options.id;

        _ol.address = $el.find('#' + _id + '-address').val();
        _ol.zoom = parseInt($el.find('#' + _id + '-zoom').val());

        //return datum
        return {
            //globals
            address: _ol.address,
            width: $el.find('#' + _id + '-width').val(),
            height: $el.find('#' + _id + '-height').val(),
            zoom: _ol.zoom,
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

    OlMaps.prototype.map_init = function (){
        var _ol = this;

        //check leaflet variable
        if ('undefined' == typeof L || null === L) {
            return;
        }

        //update L
        _ol.L = L;

        //create a map
        _ol.map = L.map(_ol.options.leafletid).setView([_ol.lat, _ol.lng], 14);

        //update configurations
        _ol.map_refresh();
    };

    OlMaps.prototype.map_marker = function (){
        var _ol = this;

        //check marker
        if (_ol.marker) {
            _ol.map.removeLayer(_ol.marker);
        }

        //build marker
        var _icon = _ol.L.icon({
            iconUrl: _ol.icon
        });

        //add marker
        _ol.marker = _ol.L.marker([_ol.lat, _ol.lng], {icon: _icon}).addTo(_ol.map);

        //define the view
        _ol.map.setView([_ol.lat, _ol.lng], _ol.zoom);
    };

    OlMaps.prototype.map_refresh = function (){
        var _ol = this,
            _att = 'Built with ♥ by <a href="https://github.com/crewstyle/" target="_blank">Achraf Chouk</a> ~ &copy; <a href="http://osm.org/copyright" target="_blank">OpenStreetMap</a> contributors';

        //get datum and coordinates
        var _datum = _ol.get_datum();

        //update sizes
        _ol.$map.css({
            height: _datum.height
        });

        //change sizes
        _ol.map.invalidateSize();

        //build layers
        if (!_ol.layer) {
            _ol.layer = {
                "Grayscale": _ol.L.tileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}', {
                    id: 'tto.map-grayscale',
                    attribution: _att
                }),
                "Watercolor": _ol.L.tileLayer('http://stamen-tiles-{s}.a.ssl.fastly.net/watercolor/{z}/{x}/{y}.png', {
                    id: 'tto.map-watercolor',
                    attribution: _att,
                    ext: 'png'
                }),
                "Blackcolor": _ol.L.tileLayer('http://{s}.tile.stamen.com/toner/{z}/{x}/{y}.png', {
                    id: 'tto.map-black',
                    attribution: _att
                }),
            };

            //add layers
            _ol.L.control.layers(_ol.layer).addTo(_ol.map);

            //set default layer
            _ol.layer.Grayscale.addTo(_ol.map);
        }

        //define the zoom box
        if (_datum.scrollwheel) {
            _ol.map.scrollWheelZoom.enable();
        }
        else {
            _ol.map.scrollWheelZoom.disable();
        }

        //add marker
        _ol.map_marker();

        //change marker position
        _ol.map_remake();
    };

    OlMaps.prototype.map_reload = function (e){
        e.preventDefault();
        var _ol = this;

        //update maps
        _ol.map_refresh();
        _ol.$modal.find('a.close').click();
    };

    OlMaps.prototype.map_remake = function (){
        var _ol = this,
            _address = encodeURIComponent(_ol.address);

        //check address
        if (_address === '') {
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
                _ol.lat = data.results[0].geometry.location.lat;
                _ol.lng = data.results[0].geometry.location.lng;

                //set well formatted address
                _ol.address = data.results[0].formatted_address;
                _ol.$modal.find('#' + _ol.options.id + '-address').val(_ol.address);

                //add marker
                _ol.map_marker();
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

                new OlMaps($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.olMaps = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on olMaps');
            return false;
        }
    };
})(window.jQuery);
