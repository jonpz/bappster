var elixir = require('laravel-elixir');

elixir.config.publicPath = 'httpdocs/assets';
elixir.config.assetsPath = 'resources/assets';

elixir(function(mix) {
  mix.copy('node_modules/bootstrap-sass/assets/fonts/bootstrap/', 'httpdocs/assets/fonts/bootstrap/');
  mix.copy('node_modules/font-awesome/fonts/', 'httpdocs/assets/fonts/');
  mix.sass('app/app.scss');
  mix.sass('admin/admin.scss');
  mix.browserify('app/app.js');
  mix.browserify('admin/admin.js');
});
