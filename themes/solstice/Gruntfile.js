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
                    src: ['bower_components/fontawesome/fonts/*'],
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
                    './public/stylesheets/styles.min.css': './bower_components/solstice-assets/stylesheets/eclipse_org/styles.less',
                    './public/stylesheets/barebone.min.css': './bower_components/solstice-assets/stylesheets/_core/barebone/styles.less',
                    './public/stylesheets/barebone-footer.min.css': './bower_components/solstice-assets/stylesheets/_core/barebone/footer.less',
                    './public/stylesheets/forums.min.css': './bower_components/solstice-assets/stylesheets/eclipse_org/forums.less',
                    './public/stylesheets/table.min.css': './bower_components/solstice-assets/stylesheets/eclipse_org/table.less',
                    './public/stylesheets/locationtech.min.css': './bower_components/solstice-assets/stylesheets/locationtech/styles.less',
                    './public/stylesheets/locationtech-barebone.min.css': './bower_components/solstice-assets/stylesheets/locationtech/barebone.less',
                    './public/stylesheets/polarsys.min.css': './bower_components/solstice-assets/stylesheets/polarsys/styles.less',
                    './public/stylesheets/polarsys-barebone.min.css': './bower_components/solstice-assets/stylesheets/polarsys/barebone.less'
                }
            }
        },
        concat: {
            options: {
                separator: ';',
            },
            js_frontend: {
                src: ['./bower_components/jquery/dist/jquery.js', './bower_components/bootstrap/dist/js/bootstrap.js', './src/javascript/lib/solstice-cookies.js', './src/javascript/main.js', './src/javascript/donate.js'],
                dest: './public/javascript/main.min.js',
            },
            js_barebone: {
                src: ['./bower_components/jquery/dist/jquery.js', './bower_components/bootstrap/dist/js/bootstrap.js', './src/javascript/main.js'],
                dest: './public/javascript/barebone.min.js',
            }
        },
        uglify: {
            options: {
                mangle: false,
                // Use if you want the names of your functions and variables
                // unchanged.
                
                // will preserve all comments that start with a bang (!)
                preserveComments:'some'
            },
            frontend: {
                files: {
                    './public/javascript/main.min.js': './public/javascript/main.min.js',
                    './public/javascript/barebone.min.js':'./public/javascript/barebone.min.js'
                }
            },
        },
        watch: {
            js_frontend: {
                files: [
                // watched files
                './bower_components/jquery/jquery.js',
                './bower_components/bootstrap/dist/js/bootstrap.js',
                './src/javascript/main.js',
                './src/javascript/donate.js',
                './src/javascript/lib/solstice-cookies.js'],
                // tasks to run
                tasks: ['concat:js_barebone', 'concat:js_frontend', 'uglify:frontend'],
            },
            less: {
                files: ['./src/stylesheets/*.less',
                        './src/stylesheets/**/*.less',
                        './bower_components/solstice-assets/less/*.less',
                        './bower_components/solstice-assets/**/*.less',
                        './html_template/barebone/stylesheets/*.less'],
                // watched files
                tasks: ['less'],
                // tasks to run
            },
            imgmin: {
              files: [
                './bower_components/solstice-assets/images/components/**/*.{png,jpg,gif}',
                './bower_components/solstice-assets/images/forums/**/*.{png,jpg,gif}',
                './bower_components/solstice-assets/images/hudson/**/*.{png,jpg,gif}',
                './bower_components/solstice-assets/images/lists/**/*.{png,jpg,gif}',
                './bower_components/solstice-assets/images/logo/**/*.{png,jpg,gif}',
                './bower_components/solstice-assets/images/template/**/*.{png,jpg,gif}',
              ],
              tasks: ['imagemin:dynamic'],
            }
        },
        imagemin: {
          dynamic: {
            files: [{
                expand: true,
                cwd: './bower_components/solstice-assets/images',
                src: [
                  'components/**/*.{png,jpg,gif}',
                  'forums/**/*.{png,jpg,gif}',
                  'hudson/**/*.{png,jpg,gif}',
                  'lists/**/*.{png,jpg,gif}',
                  'logo/**/*.{png,jpg,gif}',
                  'template/**/*.{png,jpg,gif}',
                ],
                dest: 'public/images'
            }],
            options: {
              optimizationLevel: 3
          },
        }
      }
    });
    // Plugin loading
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-imagemin');
    // Task definition
    grunt.registerTask('default', ['watch']);
    grunt.registerTask('init', ['copy','imagemin','watch']);
    grunt.registerTask('imgmin', ['imagemin']);
};

