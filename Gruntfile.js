/* ===================================================
 * Tea Theme Options
 * https://github.com/Takeatea/tea_theme_options
 * =====================================================
 * Copyright 20xx Take a Tea (http://takeatea.com)
 * =================================================== */

module.exports = function(grunt) {
    //------ [REGISTER CONFIGURATION] ------//

    grunt.initConfig({
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
                grayblack: '#111111'
            },
            flatten: true,
            path: {
                src: 'src/Assets',
                bow: 'src/Assets/bower',
                tar: 'assets'
            },
        },

        //pachakes are listed here
        pkg: grunt.file.readJSON('package.json'),

        //remove any previously-created files
        clean: {
            first: [
                '<%= teato.path.bow %>/*',
                '<%= teato.path.src %>/css/teato.css',
                '<%= teato.path.src %>/css/teato.admin.*.css',
                '<%= teato.path.src %>/css/teato.login.css',
                '<%= teato.path.tar %>/css/*',
                '<%= teato.path.tar %>/fonts/*',
                '<%= teato.path.tar %>/img/*',
                '<%= teato.path.tar %>/js/*'
            ],
            last: [
                '<%= teato.path.bow %>/*',
                '<%= teato.path.src %>/css/teato.css',
                '<%= teato.path.src %>/css/teato.admin.*.css',
                '<%= teato.path.src %>/css/teato.login.css'
            ]
        },

        //make bower magics
        bower: {
            dev: {
                dest: '<%= teato.path.bow %>',
                options: {
                    expand: true,
                    ignorePackages: [
                        'jquery',
                        'microplugin',
                        'sifter'
                    ],
                    packageSpecific: {
                        //CodeMirror
                        'codemirror': {
                            keepExpandedHierarchy: true,
                            files: [
                                'lib/**',
                                'mode/**',
                                'theme/monokai.css'
                            ]
                        },
                        //FontAwesome
                        'fontawesome': {
                            keepExpandedHierarchy: true,
                            files: [
                                'css/font-awesome.css',
                                'fonts/**'
                            ]
                        },
                        //Pickadate
                        'pickadate': {
                            keepExpandedHierarchy: true,
                            files: [
                                'lib/*.js',
                                'lib/themes/class*.css'
                            ]
                        },
                        //Selectize
                        'selectize': {
                            keepExpandedHierarchy: true,
                            files: [
                                'dist/js/standalone/selectize.js',
                                'dist/css/**',
                            ]
                        }
                    }
                }
            }
        },

        //move fonts and images into the destinated folder
        copy: {
            main: {
                files: [
                    {
                        //Fonts
                        expand: true,
                        flatten: '<%= teato.flatten %>',
                        src: [
                            //FontAwesome
                            '<%= teato.path.bow %>/fontawesome/fonts/*',
                            //global
                            '<%= teato.path.src %>/fonts/*'
                        ],
                        dest: '<%= teato.path.tar %>/fonts/'
                    },
                    {
                        //Images
                        expand: true,
                        flatten: '<%= teato.flatten %>',
                        src: [
                            '<%= teato.path.src %>/img/*'
                        ],
                        dest: '<%= teato.path.tar %>/img/'
                    }
                ]
            },
            bower: {
                files: [
                    {
                        //Leaflet
                        expand: true,
                        flatten: '<%= teato.flatten %>',
                        src: [
                            '<%= teato.path.bow %>/leaflet/dist/images/*'
                        ],
                        dest: '<%= teato.path.tar %>/css/images/'
                    }
                ]
            }
        },

        //less compilation
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
                        grayblack: '<%= teato.colors.grayblack %>'
                    },
                    optimization: 2
                },
                files: {
                    //main css
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
                        grayblack: '<%= teato.colors.grayblack %>'
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
                        grayblack: '<%= teato.colors.grayblack %>'
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
                        grayblack: '<%= teato.colors.grayblack %>'
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
                        grayblack: '<%= teato.colors.grayblack %>'
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
                        grayblack: '<%= teato.colors.grayblack %>'
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

        //minify CSS files
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

        //JShint validation
        jshint: {
            all: [
                '<%= teato.path.src %>/js/*.js'
            ]
        },

        //uglify JS files
        uglify: {
            options: {
                preserveComments: 'some',
            },
            my_target: {
                files: {
                    '<%= teato.path.tar %>/js/teato.min.js': [
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
                        //TeaTO
                        '<%= teato.path.src %>/js/tea.*.js',
                        '<%= teato.path.src %>/js/teato.js'
                    ]
                }
            }
        },

        //create POT file
        pot: {
            options:{
                text_domain: 'tea_theme_options',
                dest: 'languages/',
                keywords: [
                    '__:1',
                    '_e:1',
                    '_x:1,2c',
                    'esc_html__:1',
                    'esc_html_e:1',
                    'esc_html_x:1,2c',
                    'esc_attr__:1',
                    'esc_attr_e:1',
                    'esc_attr_x:1,2c',
                    '_ex:1,2c',
                    '_n:1,2',
                    '_nx:1,2,4c',
                    '_n_noop:1,2',
                    '_nx_noop:1,2,3c'
                ],
            },
            files:{
                src: ['**/*.php'],
                expand: true
            }
        },

        //create MO file
        po2mo: {
            files: {
                src: 'languages/*.po',
                expand: true
            }
        }
    });

    //------ [REGISTER MODULES] ------//

    //make bower magics
    grunt.loadNpmTasks('grunt-bower');

    //remove any previously-created files
    grunt.loadNpmTasks('grunt-contrib-clean');

    //move fonts and images into the destinated folder
    grunt.loadNpmTasks('grunt-contrib-copy');

    //minify CSS files
    grunt.loadNpmTasks('grunt-contrib-cssmin');

    //JShint validation
    grunt.loadNpmTasks('grunt-contrib-jshint');

    //uglify JS files
    grunt.loadNpmTasks('grunt-contrib-uglify');

    //create POT file
    grunt.loadNpmTasks('grunt-pot');

    //create MO file
    grunt.loadNpmTasks('grunt-po2mo');

    //less compilation
    grunt.loadNpmTasks('grunt-contrib-less');

    //------ [REGISTER TASKS] ------//

    //bower magic: grunt bowie
    grunt.registerTask('bow',       ['clean:first','bower','copy:bower']);

    //JShint validation task: grunt hint
    grunt.registerTask('hint',      ['jshint']);

    //languages task: grunt lang
    grunt.registerTask('lang',      ['pot','po2mo']);

    //all steps tasks: grunt css / grunt js
    grunt.registerTask('start',     ['clean:first','bower','copy:bower']);
    grunt.registerTask('css',       ['less:teato','less:earth','less:ocean','less:vulcan','less:wind','less:login','cssmin']);
    grunt.registerTask('js',        ['uglify']);
    grunt.registerTask('move',      ['copy:main','clean:last']);

    //default task: grunt default / grunt
    grunt.registerTask('default',   ['clean:first','bower','copy:bower','less:teato','less:earth','less:ocean','less:vulcan','less:wind','less:login','cssmin','uglify','copy:main','clean:last']);
};
