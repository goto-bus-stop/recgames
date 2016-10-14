const gulp = require('gulp')
const postcss = require('gulp-postcss')
const cssnano = require('cssnano')
const cssNext = require('postcss-cssnext')
const cssImport = require('postcss-import')
const options = require('gulp-util').env

gulp.task('css', () => {
  const processors = [
    cssImport(),
    cssNext(),
    options.production && cssnano({
      autoprefixer: false
    })
  ].filter(Boolean)

  return gulp.src('resources/assets/css/app.css')
    .pipe(postcss(processors))
    .pipe(gulp.dest('public/css/'))
})

gulp.task('default', ['css'])
