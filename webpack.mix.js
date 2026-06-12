// webpack.mix.js

const os = require('os');
const path = require('path');

// Caminho para os certificados do Local by WPEngine
const certPath = path.join(
  os.homedir(),
  os.platform() === 'win32'
    ? 'AppData/Roaming/Local/run/router/nginx/certs'
    : 'Library/Application Support/Local/run/router/nginx/certs'
);


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
    proxy: "https://phwin.digid/",
    host: "phwin.digid",
    open: "external",
    port: 3000,
    ws: true,
    https: {
      key: path.join(certPath, 'phwin.digid.key'),
      cert: path.join(certPath, 'phwin.digid.crt'),
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