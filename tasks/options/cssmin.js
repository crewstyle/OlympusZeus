/*!
 * cssmin.js
 *
 * Copyright 2015 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

module.exports = {
  compress: {
    files: {
      './assets/css/olz.min.css': [
        //Codemirror
        './bower_components/codemirror/lib/codemirror.css',
        './bower_components/codemirror/theme/monokai.css',
        //Leaflet
        './bower_components/leaflet/dist/leaflet.css',
        //Pickadate
        './bower_components/pickadate/lib/themes/classic.css',
        './bower_components/pickadate/lib/themes/classic.date.css',
        './bower_components/pickadate/lib/themes/classic.time.css',
        //Selectize
        './bower_components/selectize/dist/css/selectize.css',
        './bower_components/selectize/dist/css/selectize.default.css',
        './bower_components/selectize/dist/css/selectize.legacy.css',
        //main
        './bower_components/fontawesome/css/font-awesome.css',
        './src/Resources/assets/css/olz.css'
      ],
      './assets/css/olz.admin.earth.css': [
        './src/Resources/assets/css/olz.admin.earth.css'
      ],
      './assets/css/olz.admin.ocean.css': [
        './src/Resources/assets/css/olz.admin.ocean.css'
      ],
      './assets/css/olz.admin.vulcan.css': [
        './src/Resources/assets/css/olz.admin.vulcan.css'
      ],
      './assets/css/olz.admin.wind.css': [
        './src/Resources/assets/css/olz.admin.wind.css'
      ],
      './assets/css/olz.login.css': [
        './bower_components/fontawesome/css/font-awesome.css',
        './src/Resources/assets/css/olz.login.css'
      ]
    }
  }
};
