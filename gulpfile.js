const gulp = require( 'gulp' );
const copy = require( 'gulp-copy' );
const zip = require( 'gulp-zip' );
const del = require( 'del' );
const run = require( 'gulp-run-command' ).default;
const babel = require( 'gulp-babel' );
const uglify = require( 'gulp-uglify' );
const rename = require( 'gulp-rename' );
const checktextdomain = require( 'gulp-checktextdomain' );
const rsync = require( 'gulp-rsync' );
const fs = require( 'fs' );

const project = 'analogwp-templates';
const buildFiles = [
	'./**',
	'!build',
	'!build/**',
	'!node_modules/**',
	'!client/**',
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
	'!phpcs.xml.dist',
	'!composer.lock',
	'!codeception.dist.yml',
	'!tests/**',
	'!vendor/**',
];

const buildDestination = `./build/${ project }/`;
const buildZipDestination = './build/';
const cleanFiles = [ `./build/${ project }/`, `./build/${ project }.zip` ];

gulp.task( 'yarnBuild', run( 'yarn run build' ) );
gulp.task( 'yarnMakePot', run( 'yarn run makePot' ) );
gulp.task( 'yarnConvertPot2json', run('yarn run convertPot2json' ) );

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
			presets: [ '@babel/preset-env' ],
		} ) )
		.pipe( uglify() )
		.pipe( rename( {
			suffix: '.min',
		} ) )
		.pipe( gulp.dest( './inc/elementor/js/' ) );

	done();
} );

gulp.task( 'checktextdomain', ( done ) => {
	gulp
		.src( [ '**/*.php', '!build/**', '!languages/**', '!./inc/class-licensemanager.php' ] )
		.pipe( checktextdomain( {
			text_domain: 'ang',
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
			],
		} ) );

	done();
} );

gulp.task( 'build', gulp.series(
	'scripts',
	'checktextdomain',
	'yarnBuild',
	'yarnMakePot',
	'yarnConvertPot2json',
	'clean',
	'copy',
	'zip',
	function( done ) {
		done();
	} )
);

gulp.task( 'deploy', gulp.series(
	'build',
	function() {
		// Dirs and Files to sync
		const rsyncPaths = [ buildDestination ];
		const config = JSON.parse( fs.readFileSync( './gulp.config.json' ) );

		// Default options for rsync
		const rsyncConf = {
			emptyDirectories: true,
			compress: true,
			archive: true,
			progress: true,
			root: './build/',
			exclude: ['node_modules', '.svn', '.git'],
			hostname: config.play.hostname,
			username: config.play.username,
			destination: `~/files/wp-content/plugins/`,
		};

		// Use gulp-rsync to sync the files
		return gulp.src( rsyncPaths ).pipe( rsync( rsyncConf ) );
	}
) );
