/*!
 * copy.js
 *
 * Copyright 2015 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

module.exports = {
  main: {
    files: [{
      //Fonts
      expand: true,
      flatten: true,
      src: [
        './bower_components/fontawesome/fonts/*',
        './src/Resources/assets/fonts/*'
      ],
      dest: './assets/fonts/'
    },
    {
      //Images
      cwd: './src/Resources/assets/img/',
      expand: true,
      flatten: false,
      src: [
        '**/*'
      ],
      dest: './assets/img/'
    },
    {
      //Leaflet
      expand: true,
      flatten: true,
      src: [
        './bower_components/leaflet/dist/images/*'
      ],
      dest: './assets/css/images/'
    }]
  },
};
