const gulp = require( 'gulp' );
const copy = require( 'gulp-copy' );
const zip = require( 'gulp-zip' );
const del = require( 'del' );
const run = require( 'gulp-run-command' ).default;
const babel = require( 'gulp-babel' );
const uglify = require( 'gulp-uglify' );
const rename = require( 'gulp-rename' );

const project = 'analogwp-templates';
const buildFiles = [
	'./**',
	'!build',
	'!build/**',
	'!node_modules/**',
	'!src/js/**',
	'!*.json',
	'!*.map',
	'!*.xml',
	'!gulpfile.js',
	'!*.log',
	'!*.DS_Store',
	'!*.gitignore',
	'!TODO',
	'!*.git',
	'!*.DS_Store',
	'!yarn.lock',
	'!*.md',
	'!package.lock',
	'!.babelrc',
	'!.eslintignore',
	'!.eslintrc.json',
	'!webpack.config.js',
];

const buildDestination = './build/' + project + '/';
const buildZipDestination = './build/';
const cleanFiles = [ './build/' + project + '/', './build/' + project + '.zip' ];
const jsPotFile = [ './languages/ang-js.pot', './build/' + project + '/languages/ang-js.pot' ];

gulp.task( 'yarnBuild', run( 'yarn run build' ) );
gulp.task( 'yarnMakePot', run( 'yarn run makepot' ) );
gulp.task( 'yarnMakePotPHP', run( 'yarn run makepot:php' ) );

gulp.task( 'removeJSPotFile', function( done ) {
	return del( jsPotFile );
	done(); // eslint-disable-line
} );

gulp.task( 'clean', function( done ) {
	return del( cleanFiles );
	done(); // eslint-disable-line
} );

gulp.task( 'copy', function( done ) {
	return gulp.src( buildFiles )
		.pipe( copy( buildDestination ) );
	done(); // eslint-disable-line
} );

gulp.task( 'zip', function( done ) {
	return gulp.src( buildDestination + '/**', { base: 'build' } )
		.pipe( zip( project + '.zip' ) )
		.pipe( gulp.dest( buildZipDestination ) );
	done(); // eslint-disable-line
} );

gulp.task( 'scripts', function( done ) {
	gulp.src( [ './inc/elementor/js/*.js', '!./inc/elementor/js/*.min.js' ] )
		.pipe( babel( {
			presets: [ 'babel-preset-env' ],
		} ) )
		.pipe( uglify() )
		.pipe( rename( {
			suffix: '.min',
		} ) )
		.pipe( gulp.dest( './inc/elementor/js/' ) );

	done();
} );

gulp.task( 'build', gulp.series(
	'scripts',
	'yarnBuild',
	'yarnMakePot',
	'yarnMakePotPHP',
	'removeJSPotFile',
	'clean',
	'copy',
	'zip',
	function( done ) {
		done();
	} )
);
