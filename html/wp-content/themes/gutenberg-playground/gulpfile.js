var gulp = require('gulp'),
    plumber = require('gulp-plumber'),
    rename = require('gulp-rename');
var autoprefixer = require('gulp-autoprefixer');
var babel = require('gulp-babel');
var concat = require('gulp-concat');
var jshint = require('gulp-jshint');
var uglify = require('gulp-uglify');
var sass = require('gulp-sass');
var browserSync = require('browser-sync');
const { watch } = require('gulp');

gulp.task('bs-reload', function() {
    browserSync.reload();
});

gulp.task('default', function() {
    // You can use a single task
    watch('src/*.css', style);

});


gulp.task('styles', function() {
    gulp.src(['sass/**/*.scss'])
        .pipe(plumber({
            errorHandler: function(error) {
                console.log(error.message);
                this.emit('end');
            }
        }))
        .pipe(sass())
        .pipe(gulp.dest('sass/dist'))

});

gulp.task('scripts', function() {
    return gulp.src('js/**/*.js')
        .pipe(plumber({
            errorHandler: function(error) {
                console.log(error.message);
                this.emit('end');
            }
        }))
        .pipe(concat('main.js'))
        .pipe(babel())
        .pipe(gulp.dest('js/dist/scripts/'))
        .pipe(rename({ suffix: '.min' }))
        .pipe(uglify())
        .pipe(gulp.dest('js/dist/scripts/'))
        .pipe(browserSync.reload({ stream: true }))
});