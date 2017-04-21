/*
 * # /gulpfile.js
 *  ################# Commands #####################
 *
 *  watch -> (general listener for css & js & img)
 *      build:css ->
 *          clean:webcss    (clean css in web/assets/css)
 *          clean:srccss    (clean css in src/AppBundle/Resources/public/css)
 *          scss            (convert scss to css in src/AppBundle/Resources/public/)
 *          unsued:css      (delete unused css in homepage)
 *          autofix         (add prefix in src/AppBundle/Resources/public/)
 *          uglify:css      (uglify css in src/AppBundle/Resources/public/css to web/assets/css)
 *
 *      build:js
 *          clean:webjs     (clean js in web/assets/js) ??
 *          uglify:js       (uglify js in src/AppBundle/Resources/public/js to web/assets/js)
 *
 *      img:compact
 *          clean:webimg    (clean img in web/assets/img) ??
 *          img:min         (compress img in src/AppBundle/Resources/public/img to web/assets/img)
 *
 *  ___________________________________________________________________________________________________
 *
 *  unused:bootstrap:css    (delete unused in web/assets/vendor/bootstrap-3.3.7-dist/css/*.css
 *                              to web/assets/vendor/bootstrap-3.3.7-dist/css/bootstrap.transform/)
 *
 */
// -> Global
var gulp = require('gulp');
var runSequence = require('run-sequence');
var del = require('del');
// -> Css
var sass            = require('gulp-sass');
var uglifycss       = require('gulp-uglifycss');
var autoprefixer    = require('gulp-autoprefixer');
var uncss           = require('gulp-uncss');
// -> Js
var minify          = require('gulp-minify');
// -> Other
var imagemin        = require('gulp-imagemin');

// -> General watcher
gulp.task('watch', function() {
    gulp.watch('src/AppBundle/Resources/public/sass/*.scss', ['build:css']);
    gulp.watch('src/AppBundle/Resources/public/js/*.js', ['build:js']);
    gulp.watch('src/AppBundle/Resources/public/img/*', ['img:compact']);
});

// -> Css Builder
gulp.task('build:css', function(callback) {
   runSequence(
       'clean:webcss',
       'clean:srccss',
       'scss',
       'unused:css',
       'autofix',
       'uglify:css',
   callback);
});
// -> Js Builder
gulp.task('build:js', function(callback) {
    runSequence(
        'clean:webjs',
        'uglify:js',
    callback);
});
// -> Img Builder
gulp.task('img:compact', function(callback) {
    runSequence(
        'clean:webimg',
        'img:min',
    callback);
});

/* ---------- Tasks ---------- */
/* --------------------------- */
// -> Css Cleaner
gulp.task('clean:srccss', function() {
    del('src/AppBundle/Resources/public/css/')
});
gulp.task('clean:webcss', function() {
    del('web/assets/css/')
});

// -> Js Cleaner
gulp.task('clean:webjs', function() {
    del('web/assets/js/')
});

// -> Img Cleaner
gulp.task('clean:webimg', function() {
    del('web/assets/img/')
});

//-> Scss to Css
gulp.task('scss', function () {
    return gulp.src('src/AppBundle/Resources/public/sass/main.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('src/AppBundle/Resources/public/css/'));
});
// -> Delete unused css
gulp.task('unused:css', function () {
    return gulp.src('src/AppBundle/Resources/public/css/*.css')
        .pipe(uncss({
            html: ['http://localhost:8000', 'http://localhost:8000/historique']
        }))
        .pipe(gulp.dest('src/AppBundle/Resources/public/css'));
});
// -> Autoprefixer CSS
gulp.task('autofix', function() {
    gulp.src('src/AppBundle/Resources/public/css/*.css')
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: false
        }))
        .pipe(gulp.dest('src/AppBundle/Resources/public/css'))
});
// -> Get ugly Css
gulp.task('uglify:css', function () {
    gulp.src('src/AppBundle/Resources/public/css/*.css')
        .pipe(uglifycss({
            "maxLineLen": 0,
            "uglyComments": true
        }))
        .pipe(gulp.dest('web/assets/css'));
});

// -> Get ugly Js
gulp.task('uglify:js', function() {
    gulp.src('src/AppBundle/Resources/public/js/*.js')
        .pipe(minify({
            exclude: ['tasks'],
            ignoreFiles: ['.combo.js', '-min.js']
        }))
        .pipe(gulp.dest('web/assets/js'));
});

// -> Get compact img
gulp.task('img:compact', function () {
    gulp.src('src/AppBundle/Resources/public/img/*')
        .pipe(imagemin())
        .pipe(gulp.dest('web/assets/img'));
});

// -> Delete unused css
gulp.task('unused:bootstrap:css', function () {
    return gulp.src('web/assets/vendor/bootstrap-3.3.7-dist/css/*.css')
        .pipe(uncss({html: ['http://localhost:8000']}))
        .pipe(gulp.dest('web/assets/vendor/bootstrap-3.3.7-dist/css/bootstrap.transform'));
});
