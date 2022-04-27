const gulp=require('gulp');
const sass=require('gulp-sass')(require('sass'));
const sourcemaps=require('gulp-sourcemaps');
const postcss=require('gulp-postcss');
const autoprefixer=require('autoprefixer');
const cssnano=require('cssnano');
const clean=require('gulp-clean');
const imagemin=require('gulp-imagemin');
const babel=require('gulp-babel');


gulp.task("sass", ()=>{
    return gulp.src('./public/assets/scss/*.scss',{allowEmpty:true})
        .pipe(sourcemaps.init())
        .pipe(sass().on('error',sass.logError))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('./public/assets/css'))
});

gulp.task("sass:Watch", ()=>{
    gulp.watch('./public/assets/scss/**/*',gulp.series('sass'))
});

gulp.task("postcss:prefix", ()=>{
    return gulp.src('./public/assets/css/*.css')
        .pipe(postcss([autoprefixer]))
        .pipe(gulp.dest('./public/assets/css'))
});

gulp.task("postcss:min", ()=>{
    return gulp.src('./public/assets/css/*.css')
        .pipe(postcss([cssnano]))
        .pipe(gulp.dest('./public/dist/assets/css'));
})

gulp.task('copy-html',() =>{

    return gulp.src('./templates/**/*.html.twig')
        .pipe(gulp.dest('./public/dist'));
})
gulp.task('nettoyer', () =>{
    return gulp.src('./public/dist',{read:false, allowEmpty:true})
        .pipe(clean());
})
gulp.task('imagemin', ()=>{
    return gulp.src('./public/assets/data/img/**/*.+(jpg|png|gif|jpeg)')
        .pipe(imagemin([
            imagemin.mozjpeg({quality:75, progressive:true})
        ]))
        .pipe(gulp.dest('./public/dist/assets/data/img'));
})
gulp.task('babel', () =>{
    return gulp.src('./public/assets/js/app.js',{allowEmpty:true})
        .pipe(babel({presets: ['@babel/preset-env'],}))
        .pipe(gulp.dest('./public/dist/js/'))
});
gulp.task('build',gulp.series('nettoyer',gulp.parallel('copy-html',gulp.series('sass','postcss:prefix','postcss:min'),'imagemin', 'babel')));