'use strict';

let gulp         = require('gulp'),
	rename       = require('gulp-rename'),
	notify       = require('gulp-notify'),
	autoprefixer = require('gulp-autoprefixer'),
	sass         = require('gulp-sass'),
	uglify       = require('gulp-uglify'),
	plumber      = require('gulp-plumber' ),
	checktextdomain = require('gulp-checktextdomain');

//frontend
gulp.task( 'jet-blocks', () => {
	return gulp.src( './assets/scss/jet-blocks.scss' )
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

		.pipe( rename( 'jet-blocks.css' ) )
		.pipe( gulp.dest( './assets/css/' ) )
		.pipe( notify( 'Compile Sass Done!' ) );
} );

//frontend-rtl
gulp.task( 'jet-blocks-rtl', () => {
	return gulp.src( './assets/scss/jet-blocks-rtl.scss' )
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

	   .pipe( rename( 'jet-blocks-rtl.css' ) )
	   .pipe( gulp.dest( './assets/css/' ) )
	   .pipe( notify( 'Compile Sass Done!' ) );
} );

//frontend-template
gulp.task( 'jet-blocks-template', () => {
	return gulp.src( './assets/scss/jet-blocks-template.scss' )
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

		.pipe( rename( 'jet-blocks.css' ) )
		.pipe( gulp.dest( './assets/css/templates' ) )
		.pipe( notify( 'Compile Sass Done!' ) );
} );

//frontend-template-rtl
gulp.task( 'jet-blocks-template-rtl', () => {
	return gulp.src( './assets/scss/jet-blocks-template-rtl.scss' )
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

		.pipe( rename( 'jet-blocks-rtl.css' ) )
		.pipe( gulp.dest( './assets/css/templates' ) )
		.pipe( notify( 'Compile Sass Done!' ) );
} );

//icons
gulp.task( 'jet-blocks-icons', () => {
	return gulp.src( './assets/scss/jet-blocks-icons.scss' )
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

		.pipe( rename( 'jet-blocks-icons.css' ) )
		.pipe( gulp.dest( './assets/css/' ) )
		.pipe( notify( 'Compile Sass Done!' ) );
} );

//editor
gulp.task( 'jet-blocks-editor', () => {
	return gulp.src( './assets/scss/jet-blocks-editor.scss' )
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

		.pipe( rename( 'jet-blocks-editor.css' ) )
		.pipe( gulp.dest( './assets/css/' ) )
		.pipe( notify( 'Compile Sass Done!' ) );
} );

//admin
gulp.task( 'jet-blocks-admin', () => {
	return gulp.src( './assets/scss/jet-blocks-admin.scss' )
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

		.pipe( rename( 'jet-blocks-admin.css' ) )
		.pipe( gulp.dest( './assets/css/' ) )
		.pipe( notify( 'Compile Sass Done!' ) );
} );

// Minify JS
gulp.task( 'jet-blocks-js-minify', function() {
	return gulp.src( './assets/js/jet-blocks.js' )
		.pipe( uglify() )
		.pipe( rename( { extname: '.min.js' } ) )
		.pipe( gulp.dest( './assets/js/' ) )
		.pipe( notify( 'js Minify Done!' ) );
} );

// Minify Editor JS
gulp.task( 'jet-blocks-editor-js-minify', function() {
	return gulp.src( './assets/js/jet-blocks-editor.js' )
		.pipe( uglify() )
		.pipe( rename( { extname: '.min.js' } ) )
		.pipe( gulp.dest( './assets/js/' ) )
		.pipe( notify( 'js editor Minify Done!' ) );
} );

//watch
gulp.task( 'watch', () => {
	gulp.watch( './assets/scss/**', gulp.series( 'jet-blocks' ) );
	gulp.watch( './assets/scss/**', gulp.series( 'jet-blocks-rtl' ) );
	gulp.watch( './assets/scss/**', gulp.series( 'jet-blocks-template' ) );
	gulp.watch( './assets/scss/**', gulp.series( 'jet-blocks-template-rtl' ) );
	gulp.watch( './assets/scss/**', gulp.series( 'jet-blocks-editor' ) );
	gulp.watch( './assets/scss/**', gulp.series( 'jet-blocks-admin' ) );
	gulp.watch( './assets/scss/**', gulp.series( 'jet-blocks-icons' ) );
	gulp.watch( './assets/js/jet-blocks.js', gulp.series( 'jet-blocks-js-minify' ) );
	gulp.watch( './assets/js/jet-blocks-editor.js', gulp.series( 'jet-blocks-editor-js-minify' ) );
} );

//check text-domain
gulp.task( 'checktextdomain', () => {
	return gulp.src( '**/*.php' )
		.pipe( checktextdomain( {
			text_domain: 'jet-blocks',
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
			],
		} ) );
} );
