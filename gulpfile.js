'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');

gulp.task('sass', function () {
    return gulp.src('./src/View/front/scss/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('./web/assets/css'));
});

gulp.task('sass:watch', function () {
    gulp.watch('./src/View/front/scss', ['sass']);
});