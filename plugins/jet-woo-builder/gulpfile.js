'use strict';

let gulp = require( 'gulp' ),
	rename = require( 'gulp-rename' ),
	notify = require( 'gulp-notify' ),
	autoprefixer = require( 'gulp-autoprefixer' ),
	uglify = require( 'gulp-uglify-es' ).default,
	sass = require( 'gulp-sass' ),
	plumber = require( 'gulp-plumber' ),
	checktextdomain = require( 'gulp-checktextdomain' ),
	sassSettings = {
		outputStyle: 'compressed',
		linefeed: 'crlf',
		indentType: 'tab',
		indentWidth: 1
	};

//css
gulp.task('css-frontend', () => {
	return gulp.src('./assets/scss/frontend.scss')
		.pipe(
			plumber( {
				errorHandler: function ( error ) {
					console.log('=================ERROR=================');
					console.log( error.message );
					this.emit( 'end' );
				}
			})
		)
		.pipe( sass( sassSettings ) )
		.pipe( autoprefixer({
				browsers: ['last 10 versions'],
				cascade: false
		} ) )

		.pipe( rename( 'frontend.css' ) )
		.pipe( gulp.dest( './assets/css/' ) )
		.pipe( notify( 'Compile Sass Done!' ) );
});

// for integrated theme styles
gulp.task('css-frontend-themes', () => {
	return gulp.src( './includes/compatibility/packages/themes/kava/assets/scss/style.scss' )
		.pipe(
			plumber( {
				errorHandler: function ( error ) {
					console.log('=================ERROR=================');
					console.log( error.message );
					this.emit( 'end' );
				}
			} )
		)
		.pipe( sass( sassSettings ) )
		.pipe(autoprefixer( {
			browsers: ['last 10 versions'],
			cascade: false
		} ) )

		.pipe( rename( 'style.css' ) )
		.pipe( gulp.dest( './includes/compatibility/packages/themes/kava/assets/css/' ) )
		.pipe( notify( 'Compile Sass Done!' ) );
} );

gulp.task( 'css-admin', () => {
	return gulp.src( './assets/scss/admin/admin.scss' )
		.pipe(
			plumber(  {
				errorHandler: function ( error ) {
					console.log('=================ERROR=================');
					console.log( error.message );
					this.emit( 'end' );
				}
			} )
		)
		.pipe( sass( sassSettings ) )
		.pipe( autoprefixer( {
				browsers: ['last 10 versions'],
				cascade: false
		} ) )

		.pipe( rename( 'admin.css' ) )
		.pipe( gulp.dest( './assets/css/admin/' ) )
		.pipe( notify( 'Compile Sass Done!' ) );
} );

gulp.task( 'css-templates', () => {
	return gulp.src( './assets/scss/templates.scss' )
		.pipe(
			plumber(  {
				errorHandler: function ( error ) {
					console.log('=================ERROR=================');
					console.log( error.message );
					this.emit( 'end' );
				}
			} )
		)
		.pipe( sass( sassSettings ) )
		.pipe( autoprefixer( {
			browsers: ['last 10 versions'],
			cascade: false
		} ) )

		.pipe( rename('templates.css' ) )
		.pipe( gulp.dest('./assets/css/' ) )
		.pipe( notify( 'Compile Sass Done!' ) );
} );

gulp.task( 'css-templates-popups', () => {
	return gulp.src( './assets/scss/admin/templates-popups.scss' )
		.pipe(
			plumber( {
				errorHandler: function ( error ) {
					console.log('=================ERROR=================');
					console.log( error.message );
					this.emit( 'end' );
				}
			} )
		)
		.pipe( sass( sassSettings ) )
		.pipe( autoprefixer( {
			browsers: ['last 10 versions'],
			cascade: false
		} ) )

		.pipe( rename( 'templates-popups.css' ) )
		.pipe( gulp.dest( './assets/css/admin' ) )
		.pipe( notify( 'Compile Sass Done!' ) );
} );

gulp.task( 'css-editor', () => {
	return gulp.src( './assets/scss/editor/editor.scss' )
		.pipe(
			plumber(  {
				errorHandler: function ( error ) {
					console.log('=================ERROR=================');
					console.log( error.message );
					this.emit( 'end' );
				}
			} )
		)
		.pipe( sass( sassSettings ) )
		.pipe( autoprefixer( {
			browsers: ['last 10 versions'],
			cascade: false
		} ) )

		.pipe( rename( 'editor.css' ) )
		.pipe( gulp.dest( './assets/css/editor/' ) )
		.pipe( notify( 'Compile Sass Done!' ) );
} );

gulp.task( 'css-editor-icons', () => {
	return gulp.src( './assets/scss/editor/icons.scss' )
		.pipe(
			plumber( {
				errorHandler: function( error ) {
					console.log( '=================ERROR=================' );
					console.log( error.message );
					this.emit( 'end' );
				}
			} )
		)
		.pipe( sass( sassSettings ) )
		.pipe( autoprefixer( {
			browsers: ['last 10 versions'],
			cascade:  false
		} ) )

		.pipe( rename( 'icons.css' ) )
		.pipe( gulp.dest( './assets/css/editor/' ) )
		.pipe( notify( 'Compile Sass Done!' ) );
} );

// Minify JS
gulp.task( 'js-frontend', function() {
	return gulp.src( './assets/js/frontend.js' )
		.pipe( uglify() )
		.pipe( rename( { extname: '.min.js' } ) )
		.pipe( gulp.dest( './assets/js/' ) )
		.pipe( notify( 'js Minify Done!' ) );
} );

//watch
gulp.task( 'watch', () => {
	gulp.watch( './assets/scss/**', gulp.series( ...[ 'css-frontend', 'css-admin', 'css-editor', 'css-templates', 'css-templates-popups', 'css-editor-icons' ] ) );
	gulp.watch( './assets/js/**', gulp.series( ...[ 'js-frontend' ] ) );
} );

gulp.task( 'checktextdomain', () => {
	return gulp.src( [ '**/*.php', '!cherry-framework/**/*.php' ] )
		.pipe( checktextdomain( {
			text_domain: 'jet-woo-builder',
			keywords: [
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
