'use strict';

let gulp         = require( 'gulp' ),
	rename       = require( 'gulp-rename' ),
	notify       = require( 'gulp-notify' ),
	autoprefixer  = require( 'gulp-autoprefixer' ),
	sass         = require( 'gulp-sass')(require('sass'));

//css
gulp.task( 'css-admin', () => {
	return gulp.src('./assets/admin/scss/admin.scss')
		.pipe( sass( { outputStyle: 'compressed' } ) )
		.pipe( autoprefixer({
				browsers: ['last 10 versions'],
				cascade: false
		} ) )
		.pipe( rename('admin.css' ) )
		.pipe( gulp.dest('./assets/admin/css/') )
		.pipe( notify( 'Compile Sass Done!' ) );
} );

gulp.task( 'css-public', () => {
	return gulp.src( './assets/public/scss/public.scss')
		.pipe( sass( { outputStyle: 'compressed' } ))
		.pipe( autoprefixer({
				browsers: ['last 10 versions'],
				cascade: false
		} ) )
		.pipe( rename('public.css') )
		.pipe( gulp.dest( './assets/public/css/' ) )
		.pipe( notify( 'Compile Sass Done!' ) );
}  );

gulp.task( 'css-editor', () => {
	return gulp.src( './includes/elementor/assets/editor/scss/editor.scss' )
		.pipe( sass( { outputStyle: 'compressed' } ) )
		.pipe( autoprefixer( {
			browsers: ['last 10 versions'],
			cascade:  false
		} ) )
		.pipe( rename( 'editor.css' ) )
		.pipe( gulp.dest( './assets/editor/css/' ) )
		.pipe( notify( 'Compile Sass Done!' ) );
} );

//watch
gulp.task( 'watch', () => {
	gulp.watch( './assets/admin/scss/**', gulp.series( 'css-admin' ) );
	gulp.watch( './assets/public/scss/**', gulp.series( 'css-public' ) );
	gulp.watch( './includes/elementor/assets/editor/scss/**', gulp.series( 'css-editor' ) );
} );
