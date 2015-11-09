/*!
 * Tea Theme Options
 * https://github.com/crewstyle/TeaThemeOptions
 *
 * Copyright 2015 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

module.exports = function(grunt) {
    //------ [TIME] ------//
    require('time-grunt')(grunt);

    //------ [REGISTER CONFIGURATION] ------//
    grunt.initConfig({
        //pachakes are listed here
        pkg: grunt.file.readJSON('package.json'),

        //project settings
        teato: {
            colors: {
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
                fontsecond: 'Verdana,arial,sans-serif'
            },
            flatten: true,
            path: {
                src: 'src/Resources/assets',
                bow: 'bower_components',
                tar: 'assets'
            },
        },

        //0. JShint validation
        jshint: {
            all: [
                '<%= teato.path.src %>/js/*.js'
            ]
        },

        //1. remove any previously-created files
        clean: {
            first: [
                '<%= teato.path.src %>/css/teato.css',
                '<%= teato.path.src %>/css/teato.admin.*.css',
                '<%= teato.path.src %>/css/teato.login.css',
                '<%= teato.path.tar %>/css/*',
                '<%= teato.path.tar %>/fonts/*',
                '<%= teato.path.tar %>/img/*',
                '<%= teato.path.tar %>/js/*'
            ],
            last: [
                '<%= teato.path.src %>/css/teato.css',
                '<%= teato.path.src %>/css/teato.admin.*.css',
                '<%= teato.path.src %>/css/teato.login.css',
            ]
        },

        //2. move fonts and images into the destination folder
        copy: {
            main: {
                files: [
                    {
                        //Fonts
                        expand: true,
                        flatten: true,
                        src: [
                            '<%= teato.path.bow %>/fontawesome/fonts/*',
                            '<%= teato.path.src %>/fonts/*'
                        ],
                        dest: '<%= teato.path.tar %>/fonts/'
                    },
                    {
                        //Images
                        cwd: '<%= teato.path.src %>/img/',
                        expand: true,
                        flatten: false,
                        src: [
                            '**/*'
                        ],
                        dest: '<%= teato.path.tar %>/img/'
                    },
                    {
                        //Leaflet
                        expand: true,
                        flatten: true,
                        src: [
                            '<%= teato.path.bow %>/leaflet/dist/images/*'
                        ],
                        dest: '<%= teato.path.tar %>/css/images/'
                    }
                ]
            }
        },

        //3. less compilation
        less: {
            teato: {
                options: {
                    modifyVars: {
                        primary: '#91d04d',
                        second: '#e5f7e5',
                        main: '#55bb3a',
                        //normal
                        white: '<%= teato.colors.white %>',
                        black: '<%= teato.colors.black %>',
                        orange: '<%= teato.colors.orange %>',
                        blue: '<%= teato.colors.blue %>',
                        red: '<%= teato.colors.red %>',
                        danger: '<%= teato.colors.danger %>',
                        //gray
                        graylighter: '<%= teato.colors.graylighter %>',
                        graylight: '<%= teato.colors.graylight %>',
                        gray: '<%= teato.colors.gray %>',
                        graymedium: '<%= teato.colors.graymedium %>',
                        graydark: '<%= teato.colors.graydark %>',
                        graydarker: '<%= teato.colors.graydarker %>',
                        grayblack: '<%= teato.colors.grayblack %>',
                        //fonts
                        fontmain: '<%= teato.colors.fontmain %>',
                        fontsecond: '<%= teato.colors.fontsecond %>'
                    },
                    optimization: 2
                },
                files: {
                    '<%= teato.path.src %>/css/teato.css': [
                        //globals
                        '<%= teato.path.src %>/less/_fontface.less',
                        '<%= teato.path.src %>/less/_global.less',
                        '<%= teato.path.src %>/less/_navigation.less',
                        '<%= teato.path.src %>/less/_messages.less',
                        //fields
                        '<%= teato.path.src %>/less/fields/*.less',
                        //responsive
                        '<%= teato.path.src %>/less/_responsive.less'
                    ]
                }
            },
            earth: {
                options: {
                    modifyVars: {
                        primary: '#91d04d',
                        second: '#e5f7e5',
                        main: '#55bb3a',
                        //normal
                        white: '<%= teato.colors.white %>',
                        black: '<%= teato.colors.black %>',
                        orange: '<%= teato.colors.orange %>',
                        blue: '<%= teato.colors.blue %>',
                        red: '<%= teato.colors.red %>',
                        danger: '<%= teato.colors.danger %>',
                        //gray
                        graylighter: '<%= teato.colors.graylighter %>',
                        graylight: '<%= teato.colors.graylight %>',
                        gray: '<%= teato.colors.gray %>',
                        graymedium: '<%= teato.colors.graymedium %>',
                        graydark: '<%= teato.colors.graydark %>',
                        graydarker: '<%= teato.colors.graydarker %>',
                        grayblack: '<%= teato.colors.grayblack %>',
                        //fonts
                        fontmain: '<%= teato.colors.fontmain %>',
                        fontsecond: '<%= teato.colors.fontsecond %>'
                    },
                    optimization: 2
                },
                files: {
                    '<%= teato.path.src %>/css/teato.admin.earth.css': [
                        '<%= teato.path.src %>/less/_fontface.less',
                        '<%= teato.path.src %>/less/_theme.less',
                        '<%= teato.path.src %>/less/themes/*.less'
                    ]
                }
            },
            ocean: {
                options: {
                    modifyVars: {
                        primary: '#4d9dd0',
                        second: '#e5edf7',
                        main: '#3a80bb',
                        //normal
                        white: '<%= teato.colors.white %>',
                        black: '<%= teato.colors.black %>',
                        orange: '<%= teato.colors.orange %>',
                        blue: '<%= teato.colors.blue %>',
                        red: '<%= teato.colors.red %>',
                        danger: '<%= teato.colors.danger %>',
                        //gray
                        graylighter: '<%= teato.colors.graylighter %>',
                        graylight: '<%= teato.colors.graylight %>',
                        gray: '<%= teato.colors.gray %>',
                        graymedium: '<%= teato.colors.graymedium %>',
                        graydark: '<%= teato.colors.graydark %>',
                        graydarker: '<%= teato.colors.graydarker %>',
                        grayblack: '<%= teato.colors.grayblack %>',
                        //fonts
                        fontmain: '<%= teato.colors.fontmain %>',
                        fontsecond: '<%= teato.colors.fontsecond %>'
                    },
                    optimization: 2
                },
                files: {
                    '<%= teato.path.src %>/css/teato.admin.ocean.css': [
                        '<%= teato.path.src %>/less/_fontface.less',
                        '<%= teato.path.src %>/less/_theme.less',
                        '<%= teato.path.src %>/less/themes/*.less'
                    ]
                }
            },
            vulcan: {
                options: {
                    modifyVars: {
                        primary: '#d04d4d',
                        second: '#f7e5e5',
                        main: '#bb3a3a',
                        //normal
                        white: '<%= teato.colors.white %>',
                        black: '<%= teato.colors.black %>',
                        orange: '<%= teato.colors.orange %>',
                        blue: '<%= teato.colors.blue %>',
                        red: '<%= teato.colors.red %>',
                        danger: '<%= teato.colors.danger %>',
                        //gray
                        graylighter: '<%= teato.colors.graylighter %>',
                        graylight: '<%= teato.colors.graylight %>',
                        gray: '<%= teato.colors.gray %>',
                        graymedium: '<%= teato.colors.graymedium %>',
                        graydark: '<%= teato.colors.graydark %>',
                        graydarker: '<%= teato.colors.graydarker %>',
                        grayblack: '<%= teato.colors.grayblack %>',
                        //fonts
                        fontmain: '<%= teato.colors.fontmain %>',
                        fontsecond: '<%= teato.colors.fontsecond %>'
                    },
                    optimization: 2
                },
                files: {
                    '<%= teato.path.src %>/css/teato.admin.vulcan.css': [
                        '<%= teato.path.src %>/less/_fontface.less',
                        '<%= teato.path.src %>/less/_theme.less',
                        '<%= teato.path.src %>/less/themes/*.less'
                    ]
                }
            },
            wind: {
                options: {
                    modifyVars: {
                        primary: '#69d2e7',
                        second: '#e3f6fa',
                        main: '#a7dbd8',
                        //normal
                        white: '<%= teato.colors.white %>',
                        black: '<%= teato.colors.black %>',
                        orange: '<%= teato.colors.orange %>',
                        blue: '<%= teato.colors.blue %>',
                        red: '<%= teato.colors.red %>',
                        danger: '<%= teato.colors.danger %>',
                        //gray
                        graylighter: '<%= teato.colors.graylighter %>',
                        graylight: '<%= teato.colors.graylight %>',
                        gray: '<%= teato.colors.gray %>',
                        graymedium: '<%= teato.colors.graymedium %>',
                        graydark: '<%= teato.colors.graydark %>',
                        graydarker: '<%= teato.colors.graydarker %>',
                        grayblack: '<%= teato.colors.grayblack %>',
                        //fonts
                        fontmain: '<%= teato.colors.fontmain %>',
                        fontsecond: '<%= teato.colors.fontsecond %>'
                    },
                    optimization: 2
                },
                files: {
                    '<%= teato.path.src %>/css/teato.admin.wind.css': [
                        '<%= teato.path.src %>/less/_fontface.less',
                        '<%= teato.path.src %>/less/_theme.less',
                        '<%= teato.path.src %>/less/themes/*.less'
                    ]
                }
            },
            login: {
                options: {
                    modifyVars: {
                        primary: '#91d04d',
                        main: '#55bb3a',
                        //normal
                        white: '<%= teato.colors.white %>',
                        black: '<%= teato.colors.black %>',
                        orange: '<%= teato.colors.orange %>',
                        blue: '<%= teato.colors.blue %>',
                        red: '<%= teato.colors.red %>',
                        danger: '<%= teato.colors.danger %>',
                        //gray
                        graylighter: '<%= teato.colors.graylighter %>',
                        graylight: '<%= teato.colors.graylight %>',
                        gray: '<%= teato.colors.gray %>',
                        graymedium: '<%= teato.colors.graymedium %>',
                        graydark: '<%= teato.colors.graydark %>',
                        graydarker: '<%= teato.colors.graydarker %>',
                        grayblack: '<%= teato.colors.grayblack %>',
                        //fonts
                        fontmain: '<%= teato.colors.fontmain %>',
                        fontsecond: '<%= teato.colors.fontsecond %>'
                    },
                    optimization: 2
                },
                files: {
                    '<%= teato.path.src %>/css/teato.login.css': [
                        '<%= teato.path.src %>/less/_fontface.less',
                        '<%= teato.path.src %>/less/_login.less',
                        '<%= teato.path.src %>/less/login/*.less'
                    ]
                }
            }
        },

        //4. minify CSS files
        cssmin: {
            compress: {
                files: {
                    '<%= teato.path.tar %>/css/teato.min.css': [
                        //Codemirror
                        '<%= teato.path.bow %>/codemirror/lib/codemirror.css',
                        '<%= teato.path.bow %>/codemirror/theme/monokai.css',
                        //Leaflet
                        '<%= teato.path.bow %>/leaflet/dist/leaflet.css',
                        //Pickadate
                        '<%= teato.path.bow %>/pickadate/lib/themes/classic.css',
                        '<%= teato.path.bow %>/pickadate/lib/themes/classic.date.css',
                        '<%= teato.path.bow %>/pickadate/lib/themes/classic.time.css',
                        //Selectize
                        '<%= teato.path.bow %>/selectize/dist/css/selectize.css',
                        '<%= teato.path.bow %>/selectize/dist/css/selectize.default.css',
                        '<%= teato.path.bow %>/selectize/dist/css/selectize.legacy.css',
                        //FontAwesome
                        '<%= teato.path.bow %>/fontawesome/css/font-awesome.css',
                        //TeaTO
                        '<%= teato.path.src %>/css/teato.css'
                    ],
                    '<%= teato.path.tar %>/css/teato.admin.earth.css': [
                        '<%= teato.path.src %>/css/teato.admin.earth.css'
                    ],
                    '<%= teato.path.tar %>/css/teato.admin.ocean.css': [
                        '<%= teato.path.src %>/css/teato.admin.ocean.css'
                    ],
                    '<%= teato.path.tar %>/css/teato.admin.vulcan.css': [
                        '<%= teato.path.src %>/css/teato.admin.vulcan.css'
                    ],
                    '<%= teato.path.tar %>/css/teato.admin.wind.css': [
                        '<%= teato.path.src %>/css/teato.admin.wind.css'
                    ],
                    '<%= teato.path.tar %>/css/teato.login.css': [
                        '<%= teato.path.src %>/css/teato.login.css'
                    ]
                }
            }
        },

        //5. uglify conatenated JS files
        uglify: {
            options: {
                preserveComments: 'some',
            },
            my_target: {
                files: {
                    '<%= teato.path.tar %>/js/teato.min.js': [
                        //HandlebarsJS
                        '<%= teato.path.bow %>/handlebars/handlebars.js',
                        //Codemirror
                        '<%= teato.path.bow %>/codemirror/lib/codemirror.js',
                        '<%= teato.path.bow %>/codemirror/mode/clike/clike.js',
                        '<%= teato.path.bow %>/codemirror/mode/css/css.js',
                        '<%= teato.path.bow %>/codemirror/mode/diff/diff.js',
                        '<%= teato.path.bow %>/codemirror/mode/htmlmixed/htmlmixed.js',
                        '<%= teato.path.bow %>/codemirror/mode/javascript/javascript.js',
                        '<%= teato.path.bow %>/codemirror/mode/markdown/markdown.js',
                        '<%= teato.path.bow %>/codemirror/mode/php/php.js',
                        '<%= teato.path.bow %>/codemirror/mode/python/python.js',
                        '<%= teato.path.bow %>/codemirror/mode/ruby/ruby.js',
                        '<%= teato.path.bow %>/codemirror/mode/shell/shell.js',
                        '<%= teato.path.bow %>/codemirror/mode/sql/sql.js',
                        '<%= teato.path.bow %>/codemirror/mode/xml/xml.js',
                        '<%= teato.path.bow %>/codemirror/mode/yaml/yaml.js',
                        //Leaflet
                        '<%= teato.path.bow %>/leaflet/dist/leaflet-src.js',
                        //Pickadate
                        '<%= teato.path.bow %>/pickadate/lib/picker.js',
                        '<%= teato.path.bow %>/pickadate/lib/picker.date.js',
                        '<%= teato.path.bow %>/pickadate/lib/picker.time.js',
                        '<%= teato.path.bow %>/pickadate/lib/legacy.js',
                        //Selectize
                        '<%= teato.path.bow %>/selectize/dist/js/standalone/selectize.js',
                        //Tea T.O.
                        '<%= teato.path.src %>/js/tto.*.js',
                        '<%= teato.path.src %>/js/teathemeoptions.js'
                    ]
                }
            }
        },

        //6. create POT file
        pot: {
            options:{
                dest: 'languages/',
                encoding: 'UTF-8',
                keywords: [
                    '__'
                ],
                msgid_bugs_address: 'achrafchouk@gmail.com',
                package_name: 'TeaThemeOptions',
                package_version: 'v3.0.0',
                text_domain: 'tea_theme_options'
            },
            files:{
                expand: true,
                src: ['src/**/*.php']
            }
        }

        /*//7. create MO file
        po2mo: {
            files: {
                src: 'languages/*.po',
                expand: true
            }
        }*/
    });

    //------ [REGISTER MODULES] ------//

    //0. JShint validation
    grunt.loadNpmTasks('grunt-contrib-jshint');

    //1. remove any previously-created files
    grunt.loadNpmTasks('grunt-contrib-clean');

    //2. move fonts and images into the destination folder
    grunt.loadNpmTasks('grunt-contrib-copy');

    //3. less compilation
    grunt.loadNpmTasks('grunt-contrib-less');

    //4. minify CSS files
    grunt.loadNpmTasks('grunt-contrib-cssmin');

    //5. uglify conatenated JS files
    grunt.loadNpmTasks('grunt-contrib-uglify');

    //6. create POT file
    grunt.loadNpmTasks('grunt-pot');

    //7. create MO file
    //grunt.loadNpmTasks('grunt-po2mo');

    //------ [REGISTER TASKS] ------//

    //JShint validation task: grunt test
    grunt.registerTask('test',      ['jshint']);

    //all steps tasks: grunt css / grunt js
    grunt.registerTask('start',     ['clean:first']);
    grunt.registerTask('css',       ['less:teato','less:earth','less:ocean','less:vulcan','less:wind','less:login','cssmin']);
    grunt.registerTask('js',        ['uglify']);
    grunt.registerTask('move',      ['copy','clean:last']);

    //languages task: grunt lang
    grunt.registerTask('lang',      [/*'pot','po2mo'*/'pot']);

    //default task: grunt default / grunt
    grunt.registerTask('default',   ['clean:first','less:teato','less:earth','less:ocean','less:vulcan','less:wind','less:login','cssmin','uglify','copy','clean:last']);
};
