'use strict';

let gulp            = require('gulp'),
	rename          = require('gulp-rename'),
	notify          = require('gulp-notify'),
	autoprefixer    = require('gulp-autoprefixer'),
	sass            = require('gulp-sass'),
	uglify          = require('gulp-uglify-es').default,
	plumber         = require('gulp-plumber'),
	checktextdomain = require('gulp-checktextdomain');

//frontend
gulp.task('jet-tabs-frontend', () => {
	return gulp.src('./assets/scss/jet-tabs-frontend.scss')
		.pipe(
			plumber( {
				errorHandler: function ( error ) {
					console.log('=================ERROR=================');
					console.log(error.message);
					this.emit( 'end' );
				}
			})
		)
		.pipe(sass( { outputStyle: 'compressed' } ))
		.pipe(autoprefixer({
				browsers: ['last 10 versions'],
				cascade: false
		}))

		.pipe(rename('jet-tabs-frontend.css'))
		.pipe(gulp.dest('./assets/css/'))
		.pipe(notify('Compile Sass Done!'));
});

gulp.task('jet-tabs-admin', () => {
	return gulp.src('./assets/scss/jet-tabs-admin.scss')
		.pipe(
			plumber( {
				errorHandler: function ( error ) {
					console.log('=================ERROR=================');
					console.log(error.message);
					this.emit( 'end' );
				}
			})
		)
		.pipe(sass( { outputStyle: 'compressed' } ))
		.pipe(autoprefixer({
				browsers: ['last 10 versions'],
				cascade: false
		}))

		.pipe(rename('jet-tabs-admin.css'))
		.pipe(gulp.dest('./assets/css/'))
		.pipe(notify('Compile Sass Done!'));
});

gulp.task('jet-tabs-editor', () => {
	return gulp.src('./assets/scss/jet-tabs-editor.scss')
		.pipe(
			plumber( {
				errorHandler: function ( error ) {
					console.log('=================ERROR=================');
					console.log(error.message);
					this.emit( 'end' );
				}
			})
		)
		.pipe(sass( { outputStyle: 'compressed' } ))
		.pipe(autoprefixer({
				browsers: ['last 10 versions'],
				cascade: false
		}))

		.pipe(rename('jet-tabs-editor.css'))
		.pipe(gulp.dest('./assets/css/'))
		.pipe(notify('Compile Sass Done!'));
});

gulp.task( 'js-editor-minify', () => {
	return gulp.src( './assets/js/jet-tabs-editor.js' )
		.pipe( uglify() )
		.pipe( rename({ extname: '.min.js' }) )
		.pipe( gulp.dest( './assets/js/') )
		.pipe( notify('js Minify Done!') );
});

gulp.task( 'js-frontend-minify', () => {
	return gulp.src( './assets/js/jet-tabs-frontend.js' )
		.pipe( uglify() )
		.pipe( rename({ extname: '.min.js' }) )
		.pipe( gulp.dest( './assets/js/') )
		.pipe( notify('js Minify Done!') );
});

//watch
gulp.task('watch', () => {
	gulp.watch('./assets/scss/*.scss', gulp.series('jet-tabs-frontend') );
	gulp.watch('./assets/scss/*.scss', gulp.series('jet-tabs-admin') );
	gulp.watch('./assets/scss/*.scss', gulp.series('jet-tabs-editor') );
	gulp.watch('./assets/js/jet-tabs-editor.js', gulp.series('js-editor-minify') );
	gulp.watch('./assets/js/jet-tabs-frontend.js', gulp.series('js-frontend-minify') );
});

gulp.task( 'checktextdomain', () => {
	return gulp.src( ['**/*.php', '!framework/**/*.php'] )
		.pipe( checktextdomain( {
			text_domain: 'jet-tabs',
			keywords:    [
				'__:1,2d',
				'_e:1,2d',
				'_x:1,2c,3d',
				'esc_html__:1,2d',
				'esc_html_e:1,2d',
				'esc_html_x:1,2c,3d',
				'esc_attr__:1,2d',
				'esc_attr_e:1,2d',
				'esc_attr_x:1,2c,3d',
				'_ex:1,2c,3d',
				'_n:1,2,4d',
				'_nx:1,2,4c,5d',
				'_n_noop:1,2,3d',
				'_nx_noop:1,2,3c,4d',
				'translate_nooped_plural:1,2c,3d'
			]
		} ) );
} );
