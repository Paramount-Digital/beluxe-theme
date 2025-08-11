const { src, dest, watch, series, parallel } = require('gulp');
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');
const sourcemaps = require('gulp-sourcemaps');
const rename = require('gulp-rename');
const terser = require('gulp-terser');

// Paths
const paths = {
  styles: {
    src: './style.css',
    dest: './'
  },
  scripts: {
    src: './assets/script.js',
    dest: './assets/'
  }
};

// CSS task: minify and autoprefix
function cssTask() {
  return src(paths.styles.src)
    .pipe(sourcemaps.init())
    .pipe(postcss([autoprefixer(), cssnano()]))
    .pipe(rename({ suffix: '.min' }))
    .pipe(sourcemaps.write('.'))
    .pipe(dest(paths.styles.dest));
}

// JS task: minify
function jsTask() {
  return src(paths.scripts.src)
    .pipe(sourcemaps.init())
    .pipe(terser())
    .pipe(rename({ suffix: '.min' }))
    .pipe(sourcemaps.write('.'))
    .pipe(dest(paths.scripts.dest));
}

// Watch files
function watchTask() {
  watch(paths.styles.src, cssTask);
  watch(paths.scripts.src, jsTask);
}

// Default task
exports.default = series(
  parallel(cssTask, jsTask),
  watchTask
);
exports.build = parallel(cssTask, jsTask);
exports.watch = watchTask;
