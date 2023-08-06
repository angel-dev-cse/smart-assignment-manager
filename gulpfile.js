'use strict';

var gulp = require('gulp');
var browserSync = require('browser-sync').create();
var sass = require('gulp-sass')(require('node-sass'));
var sourcemaps = require('gulp-sourcemaps');
var concat = require('gulp-concat');

// Update the source and destination paths for SCSS and JS files
var paths = {
  srcScss: './resources/scss/**/*.scss',
  srcJs: './startheme/js/**/*.js',
  destCss: './public/startheme/css',
  destJs: './public/startheme/js',
};

gulp.task('sass', function () {
    return gulp.src(paths.srcScss)
        .pipe(sourcemaps.init())
        .pipe(sass({
            outputStyle: 'expanded',
            prependData: `@import "node_modules/bootstrap/scss/bootstrap";`
        }).on('error', sass.logError))
        .pipe(sourcemaps.write('./maps'))
        .pipe(gulp.dest(paths.destCss))
        .pipe(browserSync.stream());
});

gulp.task('js', function () {
    return gulp.src(paths.srcJs)
        .pipe(concat('app.js'))
        .pipe(gulp.dest(paths.destJs))
        .pipe(browserSync.stream());
});

// Static Server + watching scss/html/js files
gulp.task('serve', gulp.series('sass', 'js', function() {
    browserSync.init({
        port: 3000,
        server: "./public",
        ghostMode: false,
        notify: false
    });

    gulp.watch(paths.srcScss, gulp.series('sass'));
    gulp.watch(paths.srcJs, gulp.series('js'));
    gulp.watch('**/*.html').on('change', browserSync.reload);
}));

gulp.task('default', gulp.series('serve'));
