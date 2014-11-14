/* ===================================================
 * Tea Theme Options jQuery
 * https://github.com/Takeatea/tea_theme_options
 * ===================================================
 * Copyright 2014 Take a Tea (http://takeatea.com)
 * =================================================== */

module.exports = function(grunt) {
    //------ [REGISTER CONFIGURATION] ------//

    grunt.initConfig({
        //project settings
        teato: {
            path: {
                src: 'src/Assets',
                tar: 'assets'
            },
            flatten: true
        },

        //pachakes are listed here
        pkg: grunt.file.readJSON('package.json'),

        //remove any previously-created files
        clean: [
            '<%= teato.path.src %>/css/teato.css',
            '<%= teato.path.src %>/css/teato.admin.*.css',
            '<%= teato.path.src %>/css/teato.login.css',
            '<%= teato.path.tar %>/css/*',
            '<%= teato.path.tar %>/fonts/*',
            '<%= teato.path.tar %>/img/*',
            '<%= teato.path.tar %>/js/*'
        ],

        //move fonts and images into the destinated folder
        copy: {
            main: {
                files: [
                    {
                        //Fonts
                        expand: true,
                        flatten: '<%= teato.flatten %>',
                        src: [
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
            }
        },

        //minify CSS files
        cssmin: {
            compress: {
                files: {
                    '<%= teato.path.tar %>/css/teato.min.css': [
                        '<%= teato.path.src %>/css/pickadate/classic.css',
                        '<%= teato.path.src %>/css/pickadate/classic.date.css',
                        '<%= teato.path.src %>/css/pickadate/classic.time.css',
                        '<%= teato.path.src %>/css/font-awesome.css',
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
            my_target: {
                files: {
                    '<%= teato.path.tar %>/js/teato.min.js': [
                        '<%= teato.path.src %>/js/pickadate/picker.js',
                        '<%= teato.path.src %>/js/pickadate/picker.date.js',
                        '<%= teato.path.src %>/js/pickadate/picker.time.js',
                        '<%= teato.path.src %>/js/pickadate/legacy.js',
                        '<%= teato.path.src %>/js/tea.modal.js',
                        '<%= teato.path.src %>/js/tea.checkall.js',
                        '<%= teato.path.src %>/js/tea.checkit.js',
                        '<%= teato.path.src %>/js/tea.color.js',
                        '<%= teato.path.src %>/js/tea.date.js',
                        '<%= teato.path.src %>/js/tea.gallery.js',
                        '<%= teato.path.src %>/js/tea.labelize.js',
                        '<%= teato.path.src %>/js/tea.link.js',
                        '<%= teato.path.src %>/js/tea.range.js',
                        '<%= teato.path.src %>/js/tea.social.js',
                        '<%= teato.path.src %>/js/tea.textarea.js',
                        '<%= teato.path.src %>/js/tea.upload.js',
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
        },

        //less compilation
        less: {
            teato: {
                options: {
                    //compress: true,
                    //yuicompress: true,
                    modifyVars: {
                        primary: '#91d04d',
                        second: '#e5f7e5',
                        main: '#55bb3a',

                        white: '#ffffff',
                        black: '#000000',
                        orange: '#ffba00',
                        blue: '#2ea2cc',
                        red: '#ff0000',
                        danger: '#dd3d36',

                        graylighter: '#fbfbfb',
                        graylight: '#f1f1f1',
                        gray: '#aaaaaa',
                        graydark: '#3b3d3c',
                        graydarker: '#303231',
                        grayblack: '#111111'
                    },
                    optimization: 2
                },
                files: {
                    //main css
                    '<%= teato.path.src %>/css/teato.css': [
                        '<%= teato.path.src %>/less/_fontface.less',
                        '<%= teato.path.src %>/less/_global.less',
                        '<%= teato.path.src %>/less/_navigation.less',
                        '<%= teato.path.src %>/less/_messages.less',
                        '<%= teato.path.src %>/less/plugins/*.less',
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

                        white: '#ffffff',
                        black: '#000000',
                        orange: '#ffba00',
                        red: '#ff0000',

                        graylighter: '#fbfbfb',
                        graylight: '#f1f1f1',
                        gray: '#aaaaaa',
                        graydark: '#3b3d3c',
                        graydarker: '#303231',
                        grayblack: '#111111'
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

                        white: '#ffffff',
                        black: '#000000',
                        orange: '#ffba00',
                        red: '#ff0000',

                        graylighter: '#fbfbfb',
                        graylight: '#f1f1f1',
                        gray: '#aaaaaa',
                        graydark: '#3b3d3c',
                        graydarker: '#303231',
                        grayblack: '#111111'
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

                        white: '#ffffff',
                        black: '#000000',
                        orange: '#ffba00',
                        red: '#ff0000',

                        graylighter: '#fbfbfb',
                        graylight: '#f1f1f1',
                        gray: '#aaaaaa',
                        graydark: '#3b3d3c',
                        graydarker: '#303231',
                        grayblack: '#111111'
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

                        white: '#ffffff',
                        black: '#000000',
                        orange: '#ffba00',
                        red: '#ff0000',

                        graylighter: '#fbfbfb',
                        graylight: '#f1f1f1',
                        gray: '#aaaaaa',
                        graydark: '#3b3d3c',
                        graydarker: '#303231',
                        grayblack: '#111111'
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

                        white: '#ffffff',
                        black: '#000000',
                        blue: '#2ea2cc',
                        danger: '#dd3d36',

                        gray: '#aaaaaa',
                        graydark: '#999999',
                        graydarker: '#3b3d3c',
                        grayblack: '#111111'
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

        //watch for compiling
        watch: {
            styles: {
                files: ['<%= teato.path.src %>/less/**/*.less'],
                tasks: ['clean', 'less:teato', 'less:earth', 'less:ocean', 'less:vulcan', 'less:wind', 'less:login', 'cssmin', 'copy', 'uglify'],
                options: {
                    nospawn: true
                }
            }
        }
    });

    //------ [REGISTER MODULES] ------//

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

    //watch for compiling
    grunt.loadNpmTasks('grunt-contrib-watch');

    //------ [REGISTER TASKS] ------//

    //JShint validation task: grunt jshint
    grunt.registerTask('hint',      ['jshint']);

    //languages task: grunt lang
    grunt.registerTask('lang',      ['pot','po2mo']);

    //Watch task: grunt default
    grunt.registerTask('default',   ['clean','less:teato','less:earth','less:ocean','less:vulcan','less:wind','less:login','cssmin','uglify','copy']);

    //CSS and JS tasks: grunt css / grunt js
    grunt.registerTask('start',     ['clean']);
    grunt.registerTask('css',       ['less:teato','less:earth','less:ocean','less:vulcan','less:login','cssmin']);
    grunt.registerTask('js',        ['uglify']);
    grunt.registerTask('move',      ['copy']);

    //watch less compiling
    grunt.registerTask('sniper',    ['watch']);
};
