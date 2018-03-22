module.exports = (grunt) ->
  # Initializing the configuration object
  grunt.initConfig
    copy: main: files: [
      {
        expand: true
        flatten: true
        src: [ 'bower_components/bootstrap/fonts/*' ]
        dest: 'public/fonts/'
        filter: 'isFile'
      }
      {
        expand: true
        flatten: true
        src: [ 'bower_components/fontawesome/fonts/*' ]
        dest: 'public/fonts/'
        filter: 'isFile'
      }
    ]
    less: development:
      options: compress: true
      files:
        './public/stylesheets/styles.min.css': './bower_components/solstice-assets/stylesheets/solstice/styles.less'
        './public/stylesheets/quicksilver.min.css': './bower_components/solstice-assets/stylesheets/quicksilver/styles.less'
        './public/stylesheets/barebone.min.css': './bower_components/solstice-assets/stylesheets/solstice/_barebone/styles.less'
        './public/stylesheets/barebone-footer.min.css': './bower_components/solstice-assets/stylesheets/solstice/_barebone/footer.less'
        './public/stylesheets/forums.min.css': './bower_components/solstice-assets/stylesheets/solstice/forums.less'
        './public/stylesheets/table.min.css': './bower_components/solstice-assets/stylesheets/solstice/table.less'
        './public/stylesheets/locationtech.min.css': './bower_components/solstice-assets/stylesheets/solstice/locationtech/styles.less'
        './public/stylesheets/locationtech-barebone.min.css': './bower_components/solstice-assets/stylesheets/solstice/locationtech/barebone.less'
        './public/stylesheets/polarsys.min.css': './bower_components/solstice-assets/stylesheets/solstice/polarsys/styles.less'
        './public/stylesheets/polarsys-barebone.min.css': './bower_components/solstice-assets/stylesheets/solstice/polarsys/barebone.less'
    concat:
      js_frontend:
        src: [
          './bower_components/jquery/dist/jquery.min.js'
          './bower_components/bootstrap/dist/js/bootstrap.min.js'
          './src/javascript/lib/solstice-cookies.js'
          './src/javascript/main.js'
          './src/javascript/donate.js'
        ]
        dest: './public/javascript/main.min.js'
      js_barebone:
        src: [
          './bower_components/jquery/dist/jquery.js'
          './bower_components/bootstrap/dist/js/bootstrap.js'
          './src/javascript/main.js'
        ]
        dest: './public/javascript/barebone.min.js'
      js_quicksilver:
        src: [
          './bower_components/jquery/dist/jquery.min.js'
          './bower_components/bootstrap/dist/js/bootstrap.min.js'
          './node_modules/feather-icons/dist/feather.min.js'
          './src/javascript/lib/solstice-cookies.js'
          './src/javascript/main.js'
          './src/javascript/donate.js'
          './src/javascript/quicksilver.js'
        ]
        dest: './public/javascript/quicksilver.min.js'
    uglify:
      options:
        mangle: false
        preserveComments: 'some'
      frontend: files:
        './public/javascript/main.min.js': './public/javascript/main.min.js'
        './public/javascript/quicksilver.min.js': './public/javascript/quicksilver.min.js'
        './public/javascript/barebone.min.js': './public/javascript/barebone.min.js'
    watch:
      all:
        files: ['Gruntfile.coffee']
        tasks: 'default'
      js_frontend:
        files: [
          './bower_components/jquery/jquery.js'
          './bower_components/bootstrap/dist/js/bootstrap.js'
          './src/javascript/main.js'
          './src/javascript/quicksilver.js'
          './src/javascript/donate.js'
          './src/javascript/lib/solstice-cookies.js'
        ]
        tasks: [
          'concat:js_barebone'
          'concat:js_frontend'
          'concat:js_quicksilver'
          'uglify:frontend'
        ]
      less:
        files: [
          './src/stylesheets/*.less'
          './src/stylesheets/**/*.less'
          './bower_components/solstice-assets/less/*.less'
          './bower_components/solstice-assets/**/*.less'
          './html_template/barebone/stylesheets/*.less'
        ]
        tasks: [ 'less' ]
      imgmin:
        files: [
          './bower_components/solstice-assets/images/components/**/*.{png,jpg,gif}'
          './bower_components/solstice-assets/images/forums/**/*.{png,jpg,gif}'
          './bower_components/solstice-assets/images/hudson/**/*.{png,jpg,gif}'
          './bower_components/solstice-assets/images/lists/**/*.{png,jpg,gif}'
          './bower_components/solstice-assets/images/logo/**/*.{png,jpg,gif}'
          './bower_components/solstice-assets/images/template/**/*.{png,jpg,gif}'
        ]
        tasks: [ 'imagemin:dynamic' ]
    imagemin: dynamic:
      files: [ {
        expand: true
        cwd: './bower_components/solstice-assets/images'
        src: [
          'components/**/*.{png,jpg,gif}'
          'forums/**/*.{png,jpg,gif}'
          'hudson/**/*.{png,jpg,gif}'
          'lists/**/*.{png,jpg,gif}'
          'logo/**/*.{png,jpg,gif}'
          'template/**/*.{png,jpg,gif}'
        ]
        dest: 'public/images'
      } ]
      options: optimizationLevel: 3

  # Plugin loading
  grunt.loadNpmTasks 'grunt-contrib-concat'
  grunt.loadNpmTasks 'grunt-contrib-watch'
  grunt.loadNpmTasks 'grunt-contrib-less'
  grunt.loadNpmTasks 'grunt-contrib-uglify'
  grunt.loadNpmTasks 'grunt-contrib-copy'
  grunt.loadNpmTasks 'grunt-contrib-imagemin'

  # Task definition
  grunt.registerTask 'default', ['copy', 'imagemin', 'watch' ]
  return