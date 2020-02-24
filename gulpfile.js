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
// For fetching & updating Google fonts.
const jeditor = require( 'gulp-json-editor' );
const _ = require( 'lodash' );
const exec = require( 'gulp-exec' );
const download = require( 'gulp-download' );

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
	'!phpcs.xml.dist',
	'!composer.lock',
	'!codeception.dist.yml',
	'!tests/**',
	'!vendor/**',
	'!fonts-massager.php',
];

const buildDestination = `./build/${ project }/`;
const buildZipDestination = './build/';
const cleanFiles = [ `./build/${ project }/`, `./build/${ project }.zip` ];
const jsPotFile = [ './languages/ang-js.pot', `./build/${ project }/languages/ang-js.pot` ];
const fontsAPIKey = 'AIzaSyDkCdyJYJyc7AGqE-nkolyU0Ikx832b8gI';
const jsonMassagerSRC = './assets/fonts/google-fonts.json';
const jsonFontsDST = './assets/fonts';

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

/**
 * Task: `jsonMassager`.
 *
 * Filters through gfonts api data and creates a json object.
 */
gulp.task( 'jsonMassager', () => {
	return gulp
		.src( jsonMassagerSRC )
		.pipe(
			jeditor( json => {
				var fonts = json.items,
					newObj = {};

				_.forEach( fonts, function( data ) {
					var label = data.family,
						font = {
							label: label,
							variants: data.variants.sort(),
							subsets: data.subsets.sort(),
							category: data.category
						};

					newObj[label] = 'googlefonts';
				});

				return newObj;
			})
		)
		.pipe( gulp.dest( jsonFontsDST ) );
});

/**
 * Task: `phpMassager`.
 *
 * Runs and executes Fonts.php template for json processing.
 */
gulp.task( 'phpMassager', () => {
	return gulp
		.src( jsonMassagerSRC )
		.pipe( exec( 'php -f fonts-massager.php' ) )
		.pipe( exec( 'phpcbf -s ./inc/elementor/Google_Fonts.php' ) );
});

/**
 * Task: `googleFonts`.
 *
 * Gets fonts data from Google fonts API and sources it to google-fonts.json.
 */
gulp.task(
	'googleFonts',
	gulp.series( function() {
		const api = fontsAPIKey;
		if ( api ) {
			const url = `https://www.googleapis.com/webfonts/v1/webfonts?sort=alpha&key=${api}`;

			return download( url )
				.pipe(
					rename({
						basename: 'google-fonts',
						extname: '.json'
					})
				)
				.pipe( gulp.dest( jsonFontsDST ) );
		}
		console.error( 'Ok, not building.' ); // eslint-disable-line
		process.exit( 1 );
	}, gulp.parallel( 'jsonMassager', 'phpMassager' ) )
);

gulp.task( 'build', gulp.series(
	'scripts',
	'checktextdomain',
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
