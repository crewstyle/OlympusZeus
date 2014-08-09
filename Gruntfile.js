module.exports = function(grunt) {
    //------ [REGISTER CONFIGURATION] ------//

    grunt.initConfig({
        //pachakes are listed here
        pkg: grunt.file.readJSON('package.json'),

        // Before generating any new files, remove any previously-created files.
        clean: [
            'assets/css/*',
            'assets/js/*'
        ],

        // JShint validation
        jshint: {
            all: [
                'assets/js/*.js'
            ]
        },

        // Uglify JS files
        uglify: {
            my_target: {
                files: {
                    'assets/js/teato.min.js': [
                        'src/Assets/js/tea.modal.js',
                        'src/Assets/js/tea.checkall.js',
                        'src/Assets/js/tea.checkit.js',
                        'src/Assets/js/tea.color.js',
                        'src/Assets/js/tea.gallery.js',
                        'src/Assets/js/tea.labelize.js',
                        'src/Assets/js/tea.range.js',
                        'src/Assets/js/tea.social.js',
                        'src/Assets/js/tea.upload.js',
                        'src/Assets/js/teato.js',
                    ]
                }
            }
        },

        // Minify CSS files
        cssmin: {
            compress: {
                files: {
                    'assets/css/teato.min.css': [
                        'src/Assets/css/font-awesome.css',
                        'src/Assets/css/teato.css'
                    ],
                    'assets/css/teato.admin.green.css': [
                        'src/Assets/css/teato.admin.green.css',
                    ],
                    'assets/css/teato.admin.red.css': [
                        'src/Assets/css/teato.admin.red.css',
                    ],
                    'assets/css/teato.admin.blue.css': [
                        'src/Assets/css/teato.admin.blue.css',
                    ],
                    'assets/css/teato.login.css': [
                        'src/Assets/css/teato.login.css',
                    ]
                }
            }
        }
    });

    //------ [REGISTER MODULES] ------//

    // Clean files
    grunt.loadNpmTasks('grunt-contrib-clean');

    // JShint validation
    grunt.loadNpmTasks('grunt-contrib-jshint');

    // Uglify JS files
    grunt.loadNpmTasks('grunt-contrib-uglify');

    // Minify CSS files
    grunt.loadNpmTasks('grunt-contrib-cssmin');

    //------ [REGISTER TASKS] ------//

    // JShint validation
    grunt.registerTask('jshint', ['jshint']);

    // Default task(s): grunt default
    grunt.registerTask('default', ['clean', 'uglify', 'cssmin']);
};
