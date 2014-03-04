module.exports = function(grunt) {
    // Initializing the configuration object
    grunt.initConfig({
        copy: {
            main: {
                files: [
                // includes files within path
                {
                    expand: true,
                    flatten: true,
                    src: ['bower_components/bootstrap/fonts/*'],
                    dest: 'public/fonts/',
                    filter: 'isFile'
                }, 
                {
                    expand: true,
                    flatten: true,
                    src: ['bower_components/font-awesome/fonts/*'],
                    dest: 'public/fonts/',
                    filter: 'isFile'
                },
                ]
            }
        },
        // Task configuration  
        less: {
            development: {
                options: {
                    compress: true,
                    // minifying the result
                },
                files: {
                    // compiling styles.less into styles.css
                    "./public/stylesheets/styles.min.css": "./app/stylesheets/styles.less",
                }
            }
        },
        concat: {
            options: {
                separator: ';',
            },
            js_frontend: {
                src: ['./bower_components/jquery/dist/jquery.js', './bower_components/bootstrap/dist/js/bootstrap.js', './app/javascript/main.js'],
                dest: './public/javascript/main.min.js',
            },
        },
        uglify: {
            options: {
                mangle: false
                // Use if you want the names of your functions and variables
                // unchanged.
            },
            frontend: {
                files: {
                    './public/javascript/main.min.js': './public/javascript/main.min.js',
                }
            },
        },
        watch: {
            js_frontend: {
                files: [
                // watched files
                './bower_components/jquery/jquery.js', './bower_components/bootstrap/dist/js/bootstrap.js', './app/javascript/main.js'],
                // tasks to run
                tasks: ['concat:js_frontend', 'uglify:frontend'],
                options: {
                    livereload: true
                    // reloads the browser
                }
            },
            less: {
                files: ['./app/stylesheets/*.less', './app/stylesheets/**/*.less'],
                // watched files
                tasks: ['less'],
                // tasks to run
                options: {
                    livereload: true
                    // reloads the browser
                }
            },
        }
    });
    // Plugin loading
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-copy');
    // Task definition
    grunt.registerTask('default', ['watch']);
};