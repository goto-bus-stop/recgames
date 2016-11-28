const path = require('path')
const gulp = require('gulp')
const uglify = require('gulp-uglify')
const gulpif = require('gulp-if')
const postcss = require('gulp-postcss')
const cssnano = require('cssnano')
const scss = require('gulp-sass')
const watch = require('gulp-watch')
const options = require('gulp-util').env
const resolve = require('resolve')
const rollup = require('rollup').rollup
const buble = require('rollup-plugin-buble')
const commonjs = require('rollup-plugin-commonjs')
const replace = require('rollup-plugin-replace')
const nodeResolve = require('rollup-plugin-node-resolve')

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

gulp.task('js', () => {
  return rollup({
    entry: './resources/assets/js/app.js',
    plugins: [
      buble({
        transforms: {
          dangerousForOf: true,
          dangerousTaggedTemplateString: true
        },
        objectAssign: 'Object.assign'
      }),
      nodeResolve(),
      commonjs(),
      replace({
        'process.env.NODE_ENV': JSON.stringify(process.env.NODE_ENV || 'development')
      })
    ]
  }).then((bundle) => {
    return bundle.write({
      exports: 'none',
      dest: './public/js/app.js',
      format: 'iife'
    })
  }).then(() => {
    if (options.production) {
      gulp.start('js:min')
    }
  })
})

gulp.task('js:min', () => {
  return gulp.src('./public/js/app.js')
    .pipe(uglify())
    .pipe(gulp.dest('./public/js/'))
})

gulp.task('watch', () => {
  watch('resources/assets/scss/**/*.scss', () => {
    gulp.start('css')
  })
  watch('resources/assets/js/**/*.js', () => {
    gulp.start('js')
  })
})

gulp.task('default', ['css', 'js'])
