/*!
 * copy.js
 *
 * Copyright 2015 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

module.exports = {
  app: {
    files: [{
      //CSS
      cwd: './bower_components/olympus.hera/dist/css/',
      expand: true,
      flatten: false,
      src: [
        '**/*'
      ],
      dest: './assets/css/'
    },

    {
      //Fonts
      expand: true,
      flatten: true,
      src: ['./bower_components/olympus.hera/dist/fonts/*'],
      dest: './assets/fonts/'
    },

    {
      //Images
      cwd: './bower_components/olympus.hera/dist/img/',
      expand: true,
      flatten: false,
      src: [
        '**/*'
      ],
      dest: './assets/img/'
    },

    {
      //JS
      expand: true,
      flatten: true,
      src: ['./bower_components/olympus.hera/dist/js/olz.min.js'],
      dest: './assets/js/'
    }]
  },
};
