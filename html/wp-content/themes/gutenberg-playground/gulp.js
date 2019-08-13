var gulp = require('gulp');
var plumber = require('gulp-plumber');
var rename = require('gulp-rename');
var sourcemaps = require('gulp-sourcemaps');
var sass = require('gulp-sass');
var csslint = require('gulp-csslint');
var autoPrefixer = require('gulp-autoprefixer');
//if node version is lower than v.0.1.2
require('es6-promise').polyfill();
var cssComb = require('gulp-csscomb');
var cmq = require('gulp-merge-media-queries');
var cleanCss = require('gulp-clean-css');
var uglify = require('gulp-uglify');
gulp.task('sass', function() {
    gulp.src(['sass/**/*.sass'])
        .pipe(plumber({
            handleError: function(err) {
                console.log(err);
                this.emit('end');
            }
        }))
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(autoPrefixer())
        .pipe(cssComb())
        .pipe(cmq({ log: true }))
        .pipe(csslint())
        .pipe(csslint.formatter())
        .pipe(gulp.dest('css/dist'))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(cleanCss())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('css/dist'))
});
gulp.task('js', function() {
    gulp.src(['js/**/*.js'])
        .pipe(plumber({
            handleError: function(err) {
                console.log(err);
                this.emit('end');
            }
        }))
        .pipe(gulp.dest('js/dist'))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(uglify())
        .pipe(gulp.dest('js/dist'))
});
gulp.task('html', function() {
    gulp.src(['html/**/*.html'])
        .pipe(plumber({
            handleError: function(err) {
                console.log(err);
                this.emit('end');
            }
        }))
        .pipe(gulp.dest('./'))
});
gulp.task('default', function() {
    gulp.watch('js/**/*.js', ['js']);
    gulp.watch('sass/**/*.sass', ['sass']);
    gulp.watch('html/**/*.html', ['html']);
});