var gulp = require('gulp');//Подключение gulp
/*SASS*/
var sass = require('gulp-sass');//Подключние sass
/*SASS*/

/*JS*/
var browserSync = require('browser-sync');//Подключение browser-sync
var concat = require('gulp-concat');//Подключение gulp-concat
var uglify = require('gulp-uglifyjs');//Подключение uglify
/*JS*/

/*CSS*/
var postcss = require('gulp-postcss');
var cssnano = require('gulp-cssnano');
var autoprefixer = require('autoprefixer');//Подключение автопрефикса
var sourcemaps   = require('gulp-sourcemaps');
/*CSS*/

/*gulp.task('mytask',function(){
	return gulp.src("source-files")//Берем файл
	.pipe(plugin())//Выполняем плагин на нем
	.pipe(gulp.dest('folder'))//Сохраняем результат 
});*///Базовые принципы gulp

gulp.task('sass',function(){//Компиляция SCSS
	return gulp.src('app/sass/main.scss')
	.pipe(sass().on('error', sass.logError))
	.pipe(gulp.dest('app/css'))
	.pipe(browserSync.reload({stream: true}))
});

gulp.task('minCss', function () {
    return gulp.src('app/css/main.css')
   		.pipe(cssnano())
        .pipe(sourcemaps.init())
        .pipe(postcss([ autoprefixer({ browsers: ['last 2 versions'] }) ]))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('app/css'));
});

gulp.task('scripts', function() {
    return gulp.src([ // Берем все необходимые библиотеки
        'app/libs/jquery/dist/jquery.min.js', // Берем jQuery
        'app/libs/magnific-popup/dist/jquery.magnific-popup.min.js' // Берем Magnific Popup
        ])
        .pipe(concat('libs.min.js')) // Собираем их в кучу в новом файле libs.min.js
        .pipe(uglify()) // Сжимаем JS файл
        .pipe(gulp.dest('app/js')); // Выгружаем в папку app/js
});

gulp.task('browser-sync',function(){//task browser-sync
	browserSync({
		server: {
			baseDir:'app'//Путь к серверу 
		},
		notify:false
	});
})
;
gulp.task('watch',['browser-sync','sass'],function(){//Автоматическая компиляция SCSS ['browser-sync',sass]
	//Выполнение до watch в ['']!!!{
    gulp.watch('app/sass/**/*.scss',['sass']);
	gulp.watch('app/css/main.css');
	gulp.watch('app/*.html',browserSync.reload);
	gulp.watch('app/js/*/*.js',browserSync.reload);//Перезагрузка страницы при изменении html
});
