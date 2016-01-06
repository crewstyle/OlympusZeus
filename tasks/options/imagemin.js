/*!
 * imagemin.js
 *
 * Copyright 2015 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

module.exports = {
  dist: {
    options: {
      cache: false
    },
    files: [{
      expand: true,
      src: './assets/img/*.{png,gif,jpg,jpeg}',
      dest: './assets/img/'
    }]
  }
};
