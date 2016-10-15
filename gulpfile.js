const path = require('path')
const gulp = require('gulp')
const gulpif = require('gulp-if')
const postcss = require('gulp-postcss')
const cssnano = require('cssnano')
const scss = require('gulp-sass')
const options = require('gulp-util').env
const resolve = require('resolve')

gulp.task('css', () => {
  return gulp.src('resources/assets/scss/app.scss')
    .pipe(scss({
      importer: (url, prev, done) => {
        resolve(url, { basedir: path.dirname(prev) }, (err, result) => {
          if (err) {
            resolve(`./${url}`, { basedir: path.dirname(prev) }, (err, result) => {
              if (err) done(err)
              else done({ file: result })
            })
          } else {
            done({ file: result })
          }
        })
      }
    }))
    .pipe(gulpif(options.production,
      postcss([ cssnano() ])
    ))
    .pipe(gulp.dest('public/css/'))
})

gulp.task('default', ['css'])
