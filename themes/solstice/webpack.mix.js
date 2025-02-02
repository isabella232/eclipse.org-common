/*!
 * Copyright (c) 2018 Eclipse Foundation, Inc.
 * 
 * This program and the accompanying materials are made available under the
 * terms of the Eclipse Public License v. 2.0 which is available at
 * http://www.eclipse.org/legal/epl-2.0.
 * 
 * Contributors:
 *   Christopher Guindon <chris.guindon@eclipse-foundation.org>
 * 
 * SPDX-License-Identifier: EPL-2.0
*/

let mix = require('laravel-mix');
mix.options({uglify: {uglifyOptions: {compress: true, output: {comments: true}}}});
mix.setPublicPath('public');
mix.setResourceRoot('../');

// Default CSS
mix.less('node_modules/eclipsefdn-solstice-assets/less/quicksilver/eclipse-ide/styles.less', 'public/stylesheets/eclipse-ide.min.css');
mix.less('node_modules/eclipsefdn-solstice-assets/less/quicksilver/styles.less', 'public/stylesheets/quicksilver.min.css');
mix.less('node_modules/eclipsefdn-solstice-assets/less/quicksilver/jakarta/styles.less', 'public/stylesheets/jakarta.min.css');
mix.less('node_modules/eclipsefdn-solstice-assets/less/solstice/_barebone/styles.less', 'public/stylesheets/barebone.min.css');
mix.less('node_modules/eclipsefdn-solstice-assets/less/solstice/_barebone/footer.less', 'public/stylesheets/barebone-footer.min.css');
mix.less('node_modules/eclipsefdn-solstice-assets/less/solstice/table.less', 'public/stylesheets/table.min.css');

//mix.less('node_modules/eclipsefdn-solstice-assets/less/solstice/forums.less', 'public/stylesheets/forums.min.css');
//mix.less('node_modules/eclipsefdn-solstice-assets/less/solstice/styles.less', 'public/stylesheets/styles.min.css');

// Copy cookieconsent files
mix.copy('node_modules/cookieconsent/build/cookieconsent.min.css', 'public/stylesheets/vendor/cookieconsent/cookieconsent.min.css');
mix.copy('node_modules/cookieconsent/build/cookieconsent.min.js', 'public/javascript/vendor/cookieconsent/cookieconsent.min.js');

mix.babel([
    'node_modules/cookieconsent/src/cookieconsent.js',
    'node_modules/eclipsefdn-solstice-assets/js/solstice.cookieconsent.js'
  ], 
  'public/javascript/vendor/cookieconsent/default.min.js'
);

// Copy eclipsefdn videos file
mix.less('node_modules/eclipsefdn-solstice-assets/less/_components/eclipsefdn-video.less', 'public/stylesheets/eclipsefdn-video.min.css');
mix.babel([
  'node_modules/eclipsefdn-solstice-assets/js/eclipsefdn.videos.js'
], 
'public/javascript/eclipsefdn.videos.min.js'
);

// JavaScript
mix.babel([
    'node_modules/jquery/dist/jquery.min.js',
    'node_modules/bootstrap/dist/js/bootstrap.min.js',
    'node_modules/cookieconsent/src/cookieconsent.js',
    'node_modules/eclipsefdn-solstice-assets/js/solstice.cookieconsent.js',
    'node_modules/eclipsefdn-solstice-assets/js/eclipsefdn.videos.js',
    'node_modules/jquery-match-height/dist/jquery.matchHeight-min.js',
    'node_modules/feather-icons/dist/feather.min.js',
    'node_modules/owl.carousel/dist/owl.carousel.min.js',
    'node_modules/eclipsefdn-solstice-assets/js/solstice.cookies.js',
    'node_modules/eclipsefdn-solstice-assets/js/solstice.donate.js',
    'node_modules/jquery-eclipsefdn-api/dist/jquery.eclipsefdn-api.min.js',
    'node_modules/eclipsefdn-solstice-assets/js/solstice.js',
    'node_modules/eclipsefdn-solstice-assets/js/eclipsefdn.ads.js'
], 'public/javascript/main.min.js');

mix.babel([
    'node_modules/jquery/dist/jquery.min.js',
    'node_modules/bootstrap/dist/js/bootstrap.js',
], 'public/javascript/barebone.min.js');
