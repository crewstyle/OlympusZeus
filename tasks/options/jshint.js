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
    ],
    options: {
      jshintrc: './.jshintrc'
    }
  },

  tooling: {
    src: [
      './Gruntfile.js',
      './tasks/**/*.js'
    ],
    options: {
      jshintrc: './tasks/.jshintrc'
    }
  },

  options: {
    force: true
  }
};
