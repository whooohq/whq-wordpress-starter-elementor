'use strict';

let gulp            = require('gulp'),
	rename          = require('gulp-rename'),
	notify          = require('gulp-notify'),
	autoprefixer    = require('gulp-autoprefixer'),
	sass            = require('gulp-sass'),
	uglify          = require('gulp-uglify-es').default,
	plumber         = require('gulp-plumber');

gulp.task('jet-smart-filters-admin', () => {
	return gulp.src('./assets/scss/admin.scss')
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

		.pipe(rename('admin.css'))
		.pipe(gulp.dest('./assets/css/admin/'))
		.pipe(notify('Compile Sass Done!'));
});

//watch
gulp.task('watch', () => {
	gulp.watch('./assets/scss/*.scss', gulp.series('jet-smart-filters-admin') );
});
