let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.combine([
		'public/vendors/audit_trail/src/angular.js',
		'public/vendors/audit_trail/src/angular-bootstrap-ui.js',
		'public/vendors/audit_trail/src/angular-animate.js',
		'public/vendors/audit_trail/src/AuditTrail.js',
	], 'public/vendors/audit_trail/dist/auditTrail.js')
	.options({
      processCssUrls: false
   }).sourceMaps();


mix.combine([
		'public/vendors/audit_trail/src/angular.js',
		'public/vendors/audit_trail/src/angular-bootstrap-ui.js',
		'public/vendors/audit_trail/src/angular-animate.js',
		'public/vendors/audit_trail/src/ApiSetting.js',
	], 'public/vendors/api_settings/ApiSetting.js')
	.options({
      processCssUrls: false
   }).sourceMaps();
