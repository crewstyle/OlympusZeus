/*!
 * uglify.js
 *
 * Copyright 2015 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

module.exports = {
  options: {
    preserveComments: 'some'
  },
  main: {
    files: {
      './assets/js/teato.min.js': [
        //HandlebarsJS
        './bower_components/handlebars/handlebars.js',
        //Codemirror
        './bower_components/codemirror/lib/codemirror.js',
        './bower_components/codemirror/mode/clike/clike.js',
        './bower_components/codemirror/mode/css/css.js',
        './bower_components/codemirror/mode/diff/diff.js',
        './bower_components/codemirror/mode/htmlmixed/htmlmixed.js',
        './bower_components/codemirror/mode/javascript/javascript.js',
        './bower_components/codemirror/mode/markdown/markdown.js',
        './bower_components/codemirror/mode/php/php.js',
        './bower_components/codemirror/mode/python/python.js',
        './bower_components/codemirror/mode/ruby/ruby.js',
        './bower_components/codemirror/mode/shell/shell.js',
        './bower_components/codemirror/mode/sql/sql.js',
        './bower_components/codemirror/mode/xml/xml.js',
        './bower_components/codemirror/mode/yaml/yaml.js',
        //Leaflet
        './bower_components/leaflet/dist/leaflet-src.js',
        //Pickadate
        './bower_components/pickadate/lib/picker.js',
        './bower_components/pickadate/lib/picker.date.js',
        './bower_components/pickadate/lib/picker.time.js',
        './bower_components/pickadate/lib/legacy.js',
        //Selectize
        './bower_components/selectize/dist/js/standalone/selectize.js',
        //Tea T.O.
        './src/Resources/assets/js/tto.*.js',
        './src/Resources/assets/js/teathemeoptions.js'
      ]
    }
  }
};
