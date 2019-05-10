import gulp from 'gulp';
import webpackConfig from './webpack.config.js';
import webpack from 'webpack-stream';
import browserSync from 'browser-sync';
import notify from 'gulp-notify';
import plumber from 'gulp-plumber';
import eslint from 'gulp-eslint';
//sass用追加
import sass from 'gulp-sass';
import autoprefixer from 'gulp-autoprefixer';
gulp.task('sass', function(){
    return gulp.src('public/assets/css/scss/*.scss')
    .pipe(plumber({
        errorHandler: notify.onError("Error: <%= error.message %>")
    }))
    .pipe(sass())
    .pipe(autoprefixer())
    .pipe(gulp.dest('public/assets/css/dist'))
});

gulp.task('build', function(){
    gulp.src('public/assets/js/app.js')
    .pipe(plumber({
        errorHandler: notify.onError("Error: <%= error.message %>")
    }))
    .pipe(webpack(webpackConfig))
    .pipe(gulp.dest('public/assets/js/dist'));
});
gulp.task('browser-sync', function(){
    browserSync.init({
        proxy: 'localhost:8888/fuel-vue/public/top/index'
    });
});
gulp.task('bs-reload', function(){
    browserSync.reload();
});
gulp.task('eslint', function(){
    return gulp.src(['public/assets/js/app.js'])
    .pipe(plumber({
        errorHandler: function(error){
            const taskName = 'eslint';
            const title = '[task]' + taskName + ' ' + error.plugin;
            const errorMsg = 'error: ' + error.message;
            console.error(title + '\n' + errorMsg);
            notify.onError({
                title: title,
                message: errorMsg,
                time: 3000
            });
        }
    }))
    .pipe(eslint({ useEslintrc: true}))
    .pipe(eslint.format())
    .pipe(eslint.failOnError())
    .pipe(plumber.stop());
});
gulp.task('default', ['eslint', 'build', 'sass', 'browser-sync'], function(){
    gulp.watch('public/assets/js/*.js', ['eslint', 'build', 'bs-reload']);
    gulp.watch('fuel/app/classes/**/*.php', ['bs-reload']);
    gulp.watch('fuel/app/views/**/*.php', ['bs-reload']);
    //gulp.watch("public/assets/js/*.js", ['eslint']);
    //sass用追加
    gulp.watch('public/assets/css/scss/**/**/*.scss', ['sass', 'bs-reload']);
});