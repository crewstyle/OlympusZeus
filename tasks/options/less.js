/*!
 * less.js
 *
 * Copyright 2016 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

var grunt = require('grunt'),
  _ = grunt.util._,
  _configs = {
    //normal
    white: '#ffffff',
    black: '#000000',
    blue: '#2ea2cc',
    danger: '#dd3d36',
    orange: '#ffba00',
    red: '#ff0000',
    //gray
    graylighter: '#fbfbfb',
    graylight: '#f1f1f1',
    gray: '#aaaaaa',
    graymedium: '#999999',
    graydark: '#3b3d3c',
    graydarker: '#303231',
    grayblack: '#111111',
    //fonts
    fontmain: '"Raleway","Open Sans",sans-serif',
    fontsecond: 'Verdana,arial,sans-serif',
    fonticon: 'FontAwesome'
  };

module.exports = {
  main: {
    options: {
      modifyVars: _.extend({}, {
        primary: '#75cd45',
        second: '#e5f7e5',
        main: '#55bb3a'
      }, _configs),
      optimization: 2
    },
    files: {
      './src/Resources/assets/css/olz.css': [
        //globals
        './src/Resources/assets/less/_fontface.less',
        './src/Resources/assets/less/_global.less',
        './src/Resources/assets/less/_navigation.less',
        './src/Resources/assets/less/_messages.less',
        './src/Resources/assets/less/_footer.less',
        //fields
        './src/Resources/assets/less/fields/*.less',
        //responsive
        './src/Resources/assets/less/_responsive.less'
      ]
    }
  },

  earth: {
    options: {
      modifyVars: _.extend({}, {
        primary: '#75cd45',
        second: '#e5f7e5',
        main: '#55bb3a',
      }, _configs),
      optimization: 2
    },
    files: {
      './src/Resources/assets/css/olz.admin.earth.css': [
        './src/Resources/assets/less/_fontface.less',
        './src/Resources/assets/less/_theme.less',
        './src/Resources/assets/less/themes/*.less'
      ]
    }
  },

  ocean: {
    options: {
      modifyVars: _.extend({}, {
        primary: '#4d9dd0',
        second: '#e5edf7',
        main: '#3a80bb'
      }, _configs),
      optimization: 2
    },
    files: {
      './src/Resources/assets/css/olz.admin.ocean.css': [
        './src/Resources/assets/less/_fontface.less',
        './src/Resources/assets/less/_theme.less',
        './src/Resources/assets/less/themes/*.less'
      ]
    }
  },

  vulcan: {
    options: {
      modifyVars: _.extend({}, {
        primary: '#d04d4d',
        second: '#f7e5e5',
        main: '#bb3a3a'
      }, _configs),
      optimization: 2
    },
    files: {
      './src/Resources/assets/css/olz.admin.vulcan.css': [
        './src/Resources/assets/less/_fontface.less',
        './src/Resources/assets/less/_theme.less',
        './src/Resources/assets/less/themes/*.less'
      ]
    }
  },

  wind: {
    options: {
      modifyVars: _.extend({}, {
        primary: '#69d2e7',
        second: '#e3f6fa',
        main: '#a7dbd8'
      }, _configs),
      optimization: 2
    },
    files: {
      './src/Resources/assets/css/olz.admin.wind.css': [
        './src/Resources/assets/less/_fontface.less',
        './src/Resources/assets/less/_theme.less',
        './src/Resources/assets/less/themes/*.less'
      ]
    }
  },

  login: {
    options: {
      modifyVars: _.extend({}, {
        primary: '#75cd45',
        main: '#55bb3a'
      }, _configs),
      optimization: 2
    },
    files: {
      './src/Resources/assets/css/olz.login.css': [
        './src/Resources/assets/less/_fontface.less',
        './src/Resources/assets/less/_login.less',
        './src/Resources/assets/less/login/*.less'
      ]
    }
  }
};
