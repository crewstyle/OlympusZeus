/*!
 * copy.js
 *
 * Copyright 2016 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

module.exports = {
  app: {
    files: [{
      //Fonts
      expand: true,
      flatten: true,
      src: [
        './bower_components/font-awesome/fonts/*',
        './bower_components/material-design-raleway-font/webfonts/*.woff'
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
