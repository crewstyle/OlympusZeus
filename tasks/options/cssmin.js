/*!
 * cssmin.js
 *
 * Copyright 2015 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

module.exports = {
  compress: {
    files: {
      './assets/css/teato.min.css': [
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
        //FontAwesome
        './bower_components/fontawesome/css/font-awesome.css',
        //TeaTO
        './src/Resources/assets/css/teato.css'
      ],
      './assets/css/teato.admin.earth.css': [
        './src/Resources/assets/css/teato.admin.earth.css'
      ],
      './assets/css/teato.admin.ocean.css': [
        './src/Resources/assets/css/teato.admin.ocean.css'
      ],
      './assets/css/teato.admin.vulcan.css': [
        './src/Resources/assets/css/teato.admin.vulcan.css'
      ],
      './assets/css/teato.admin.wind.css': [
        './src/Resources/assets/css/teato.admin.wind.css'
      ],
      './assets/css/teato.login.css': [
        //FontAwesome
        './bower_components/fontawesome/css/font-awesome.css',
        //TeaTO
        './src/Resources/assets/css/teato.login.css'
      ]
    }
  }
};
