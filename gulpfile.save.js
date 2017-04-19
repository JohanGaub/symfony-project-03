var gulp = require('gulp');

var sass = require('gulp-sass');
var uglifycss = require('gulp-uglifycss');
var minify = require('gulp-minify');
var autoprefixer = require('gulp-autoprefixer');
var uncss = require('gulp-uncss');
var imagemin = require('gulp-imagemin');

gulp.task('mywatch', function() {
    gulp.watch('src/AppBundle/Resources/public/sass/*.scss', ['scss', 'autofixer', 'unusedcss', 'uglifycss']);
    gulp.watch('src/AppBundle/Resources/public/css/*.css', ['autofixer']);
    gulp.watch('src/AppBundle/Resources/public/css/*.css', ['unusedcss']);
    gulp.watch('src/AppBundle/Resources/public/css/*.css', ['uglifycss']);
    gulp.watch('src/AppBundle/Resources/public/js/*.js', ['ulgifyjs']);
});

gulp.task('scss', function () {
    return gulp.src('src/AppBundle/Resources/public/sass/main.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('src/AppBundle/Resources/public/css/'));
});

gulp.task('autofixer', function(){
    gulp.src('src/AppBundle/Resources/public/css/*.css')
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: false
        }))
        .pipe(gulp.dest('src/AppBundle/Resources/public/css'))
});

gulp.task('unusedcss', function () {
    return gulp.src('src/AppBundle/Resources/public/css/*.css')
        .pipe(uncss({
            html: ['http://localhost:8000']
        }))
        .pipe(gulp.dest('src/AppBundle/Resources/public/css'));
});

gulp.task('uglifycss', function () {
    gulp.src('src/AppBundle/Resources/public/css/*.css')
        .pipe(uglifycss({
            "maxLineLen": 0,
            "uglyComments": true
        }))
        .pipe(gulp.dest('web/assets/css'));
});

gulp.task('uglifyjs', function() {
    gulp.src('src/AppBundle/Resources/public/js/*.js')
        .pipe(minify({
            exclude: ['tasks'],
            ignoreFiles: ['.combo.js', '-min.js']
        }))
        .pipe(gulp.dest('web/assets/js'));
});

gulp.task('imgmin', function () {
    gulp.src('src/AppBundle/Resources/public/img/*')
        .pipe(imagemin())
        .pipe(gulp.dest('web/assets/img'));
});
