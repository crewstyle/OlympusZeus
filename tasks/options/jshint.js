/*!
 * jshint.js
 *
 * Copyright 2015 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

module.exports = {
  app: {
    src: [
      './src/Resources/assets/js/*.js'
    ]
  },

  tooling: {
    src: [
      './Gruntfile.js',
      './tasks/**/*.js'
    ]
  },

  options: {
    force: true
  }
};
