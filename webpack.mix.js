// webpack.mix.js

const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

mix
  .setResourceRoot('./')
  .setPublicPath('dist')
  .autoload({
    jquery: ['$', 'window.jQuery', 'jQuery']
  })

  .js('src/js/main.js', 'js')
  //.js('assets/js/blog-ajax.js', 'js')
  .sass('src/sass/main.sass', 'css')
  .options({
    postCss: [ tailwindcss('./tailwind.config.js') ],
  })

  .browserSync({
    proxy: {
      target: "http://phwin.digid",
      ws: true
    },
    files: ["./**/*.php", "./dist/js/*.js", "./dist/css/*.css"]
  })
  .disableNotifications();
  

if (!mix.inProduction()) {
  mix
    .webpackConfig({
      devtool: "source-map"
    })
    .sourceMaps();
}

if (mix.inProduction()) {
  mix.version();
}