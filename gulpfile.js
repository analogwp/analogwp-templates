const gulp = require( 'gulp' );
const copy = require( 'gulp-copy' );
const zip = require( 'gulp-zip' );
const del = require( 'del' );
const run = require( 'gulp-run-command' ).default;

const project = 'analogwp-templates';
const buildFiles = [ './**',
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

gulp.task( 'yarnBuild', run( 'yarn run build' ) );

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

gulp.task( 'build', gulp.series( 'yarnBuild', 'clean', 'copy', 'zip', function( done ) {
	done();
} ) );
