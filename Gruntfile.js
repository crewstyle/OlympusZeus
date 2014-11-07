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
                    '<%= teato.path.tar %>/css/teato.admin.green.css': [
                        '<%= teato.path.src %>/css/teato.admin.green.css'
                    ],
                    '<%= teato.path.tar %>/css/teato.admin.red.css': [
                        '<%= teato.path.src %>/css/teato.admin.red.css'
                    ],
                    '<%= teato.path.tar %>/css/teato.admin.blue.css': [
                        '<%= teato.path.src %>/css/teato.admin.blue.css'
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

    //------ [REGISTER TASKS] ------//

    //JShint validation task: grunt jshint
    grunt.registerTask('hint', ['jshint']);

    //languages task: grunt lang
    grunt.registerTask('lang', ['pot', 'po2mo']);

    //Default task: grunt default
    grunt.registerTask('default', ['clean', 'cssmin', 'copy', 'uglify']);
};
