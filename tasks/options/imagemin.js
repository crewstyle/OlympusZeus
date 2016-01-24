/*!
 * imagemin.js
 *
 * Copyright 2016 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

module.exports = {
  app: {
    files: [{
      expand: true,
      cwd: './assets/img/',
      src: [
        '**/*.{png,gif,jpg,jpeg}'
      ],
      dest: './assets/img/'
    }],

    options: {
      cache: false
    }
  }
};
