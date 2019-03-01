/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');
require('../../node_modules/swagger-ui-dist/swagger-ui.css');
require('bootstrap/dist/css/bootstrap.css');
require('bootstrap/dist/css/bootstrap-grid.css');
require('bootstrap/dist/css/bootstrap-reboot.css');
require('bootstrap/dist/js/bootstrap.bundle');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

const SwaggerUIBundle = require('swagger-ui-dist/swagger-ui-bundle');
const SwaggerUIStandalonePreset = require('swagger-ui-dist/swagger-ui-standalone-preset');

window.onload = function() {

  const $ = require('jquery');

  if($('#swagger-ui').length) {
    // Begin Swagger UI call region
    const ui = SwaggerUIBundle({
      url: "/swagger",
      dom_id: '#swagger-ui',
      deepLinking: true,
      presets: [
        SwaggerUIBundle.presets.apis,
        SwaggerUIStandalonePreset
      ],
      plugins: [
        SwaggerUIBundle.plugins.DownloadUrl
      ],
      layout: "StandaloneLayout"
    });
    // End Swagger UI call region

    window.ui = ui
  }

};

