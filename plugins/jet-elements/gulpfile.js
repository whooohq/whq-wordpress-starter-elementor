'use strict';

let gulp         = require('gulp'),
	rename       = require('gulp-rename'),
	notify       = require('gulp-notify'),
	autoprefixer = require('gulp-autoprefixer'),
	uglify       = require('gulp-uglify-es').default,
	sass         = require('gulp-sass'),
	plumber      = require('gulp-plumber'),
	checktextdomain = require('gulp-checktextdomain');

//css
gulp.task('css', () => {
	return gulp.src('./assets/scss/jet-elements.scss')
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

		.pipe(rename('jet-elements.css'))
		.pipe(gulp.dest('./assets/css/'))
		.pipe(notify('Compile Sass Done!'));
});

gulp.task('css-skin', () => {
	return gulp.src('./assets/scss/jet-elements-skin.scss')
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

		.pipe(rename('jet-elements-skin.css'))
		.pipe(gulp.dest('./assets/css/'))
		.pipe(notify('Compile Sass Done!'));
});

gulp.task('css-skin-rtl', () => {
	return gulp.src('./assets/scss/jet-elements-skin-rtl.scss')
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
			.pipe(rename('jet-elements-skin-rtl.css'))
			.pipe(gulp.dest('./assets/css/'))
			.pipe(notify('Compile Sass Done!'));
});

gulp.task('css-admin', () => {
	return gulp.src('./assets/scss/jet-elements-admin.scss')
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

		.pipe(rename('jet-elements-admin.css'))
		.pipe(gulp.dest('./assets/css/'))
		.pipe(notify('Compile Sass Done!'));
});

gulp.task('css-rtl', () => {
	return gulp.src('./assets/scss/jet-elements-rtl.scss')
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
		   .pipe(rename('jet-elements-rtl.css'))
		   .pipe(gulp.dest('./assets/css/'))
		   .pipe(notify('Compile Sass Done!'));
});

//css-icons
gulp.task( 'css-icons', () => {
	return gulp.src( './assets/scss/jet-elements-icons.scss' )
		.pipe(
			plumber( {
				errorHandler: function( error ) {
					console.log( '=================ERROR=================' );
					console.log( error.message );
					this.emit( 'end' );
				}
			} )
		)
		.pipe( sass( { outputStyle: 'compressed' } ) )
		.pipe( autoprefixer( {
			browsers: ['last 10 versions'],
			cascade:  false
		} ) )

		.pipe( rename( 'jet-elements-icons.css' ) )
		.pipe( gulp.dest( './assets/css/' ) )
		.pipe( notify( 'Compile Sass Done!' ) );
} );

//css-editor
gulp.task( 'css-editor', () => {
	return gulp.src( './assets/scss/jet-elements-editor.scss' )
		.pipe(
			plumber( {
				errorHandler: function( error ) {
					console.log( '=================ERROR=================' );
					console.log( error.message );
					this.emit( 'end' );
				}
			} )
		)
		.pipe( sass( { outputStyle: 'compressed' } ) )
		.pipe( autoprefixer( {
			browsers: ['last 10 versions'],
			cascade:  false
		} ) )

		.pipe( rename( 'jet-elements-editor.css' ) )
		.pipe( gulp.dest( './assets/css/' ) )
		.pipe( notify( 'Compile Sass Done!' ) );
} );

// Minify JS
gulp.task( 'js-minify', function() {
	return gulp.src( './assets/js/jet-elements.js' )
		.pipe( uglify() )
		.pipe( rename( { extname: '.min.js' } ) )
		.pipe( gulp.dest( './assets/js/' ) )
		.pipe( notify( 'js Minify Done!' ) );
} );

gulp.task( 'js-editor-minify', function() {
	return gulp.src( './assets/js/jet-elements-editor.js' )
		.pipe( uglify() )
		.pipe( rename( { extname: '.min.js' } ) )
		.pipe( gulp.dest( './assets/js/' ) )
		.pipe( notify( 'js Minify Done!' ) );
} );

//watch
gulp.task('watch', () => {
	gulp.watch( './assets/scss/**', gulp.series( ...['css', 'css-skin', 'css-admin', 'css-rtl', 'css-skin-rtl', 'css-icons', 'css-editor'] ) );

	gulp.watch( './assets/js/jet-elements.js', gulp.series( 'js-minify' ) );
	gulp.watch( './assets/js/jet-elements-editor.js', gulp.series( 'js-editor-minify' ) );
});

gulp.task( 'checktextdomain', () => {
	return gulp.src( ['**/*.php', '!cherry-framework/**/*.php'] )
		.pipe( checktextdomain( {
			text_domain: 'jet-elements',
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

