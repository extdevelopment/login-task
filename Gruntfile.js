'use strict';

// # Globbing
// for performance reasons we're only matching one level down:
// 'test/spec/{,*/}*.js'
// use this if you want to recursively match all subfolders:
// 'test/spec/**/*.js'

module.exports = function (grunt) {

	// Load grunt tasks automatically
	require('load-grunt-tasks')(grunt);

	require('phplint').gruntPlugin(grunt);

	// Time how long tasks take. Can help when optimizing build times
	require('time-grunt')(grunt);

	global.karmaRun = {
		jasmine: false,
		qunit: false
	};

	var appConfig = {};

	appConfig = {
		symfonySrc: ['src/**/*.php', 'features/**/*.php'],
		// only dir accepts
		symfonySrcPhpCsFixer: ['src', 'features'],
		jsSrc: ['src/**/*.js', 'app/Resources/public/js/**/*.js'],
		scssCwd: 'app/Resources/scss/',
		scssSrc: '*.scss',
		qunitSrc: 'test/qunit/**/*.js',
		jasmineSrc: 'test/jasmine/**/*.js',
		jsConfsSrc: ['Gruntfile.js', 'karma.conf*.js'],
		antReportLogPath: 'app/build/jslogs/',
		phpcsStandard: 'app/ruleset.xml'
	};

	appConfig.testSrc = [appConfig.jasmineSrc, appConfig.qunitSrc];

	// Define the configuration for all the tasks
	grunt.initConfig({

		// Watches files for changes and runs tasks based on the changed files
		watch: {
			js: {
				files: [appConfig.jsSrc],
				tasks: ['newer:jsbeautifier', 'newer:jshint:src', 'newer:eslint:src', 'newer:jscs:src'],
				options: {
					livereload: '<%= connect.options.livereload %>',
					spawn: false, //i think need this
				},
				force: true,
			},

			jsConfs: {
				files: [appConfig.jsConfsSrc],
				tasks: ['newer:jsbeautifier', 'newer:jshint:src'],
				options: {
					spawn: false, //i think need this
				}
			},

			php: {
				files: [appConfig.symfonySrc],
				// phpcsfixer not work with newer:(
				// request: https://github.com/mgmcintyre/grunt-php-cs-fixer/issues/8
				// tasks: ['newer:phpcbf', 'newer:phpcsfixer', 'newer:phplint', 'newer:phpcs', 'newer:phpmd-runner'],
				tasks: ['newer:phpcbf', 'newer:phplint', 'newer:phpcs', 'newer:phpmd-runner'],
				options: {
					livereload: '<%= connect.options.livereload %>',
					spawn: false, //i think need this
				},
				force: true,
			},
			sass: {
				files: [appConfig.scssCwd + appConfig.scssSrc],
				tasks: ['scss'],
				options: {
					livereload: '<%= connect.options.livereload %>',
				},
			},
			karmaQunit: {
				files: [appConfig.qunitSrc],
				tasks: ['newer:jsbeautifier', 'newer:jshint:test', 'karmarun:qunit'],
				options: {
					spawn: false
				}
			},
			karmaJasmine: {
				files: [appConfig.jasmineSrc],
				//tasks: ['karmarun:jasmine'],
				tasks: ['newer:jsbeautifier', 'newer:jshint:test', 'karmarun:jasmine'],

				// https://stackoverflow.com/questions/20526920/are-there-global-variables-in-grunt/20528955#20528955
				// you could run watch the tasks inside the same process by using options: { spawn: false }
				options: {
					spawn: false
				}
			},
			gruntfile: {
				files: ['Gruntfile.js']
			},
			livereload: {
				options: {
					livereload: '<%= connect.options.livereload %>'
				},
				files: [
					//'src/{,*/}*.html'
					'src/**/*.*',
					'app/config/**/*.*',
					'app/Resources/view/**/*.*',
					'app/Resources/public/*.*'
				]
			}
		},

		//https://github.com/SaschaGalley/grunt-phpcs#usage-example
		phpcs: {
			application: {
				//dir: ['application/classes/*.php', 'application/lib/**/*.php']
				src: [appConfig.symfonySrc]
			},
			options: {
				bin: 'phpcs',
				//http://symfony.com/doc/current/contributing/code/standards.html
				//https://richardmiller.co.uk/2012/08/07/symfony2-coding-standards-update/
				// standard: 'PSR2',
				standard: appConfig.phpcsStandard,
				verbose: true,
				showSniffCodes: true
			}
		},

		// https://github.com/mducharme/grunt-phpcbf
		phpcbf: {
			options: {
				bin: 'phpcbf',
				standard: appConfig.phpcsStandard,
			},
			files: {
				src: [appConfig.symfonySrc]
			}
		},

		// https://github.com/mgmcintyre/grunt-php-cs-fixer
		phpcsfixer: {
			options: {
				bin: 'php-cs-fixer',
				level: 'symfony',
				verbose: true,
				//framework: 'sf23'
			},
			files: {
				dir: appConfig.symfonySrcPhpCsFixer
			}
		},

		//https://github.com/jgable/grunt-phplint
		//    phplint: {
		//        options: {
		//            phpCmd: "php.exe", // Or "c:\EasyPHP-5.3.8.1\PHP.exe" 
		//            phpArgs: {
		//                "-l": null
		//            },
		//            //spawnLimit: 10
		//        },
		// 
		//        good: ['../src/**/*.php']
		//    },

		//https://github.com/wayneashleyberry/node-phplint
		phplint: {
			options: {
				limit: 2,
				phpCmd: 'php.exe', // Defaults to php 
				stdout: true,
				stderr: true,
				useCache: false
			},
			files: {
				src: [appConfig.symfonySrc]
			}
		},

		// https://github.com/tailored-tunes/grunt-phpmd-runner
		'phpmd-runner': {
			options: {
				phpmd: 'phpmd',
				rulesets: [
					'app/phpmd.xml'
				],
			},
			files: appConfig.symfonySrc
		},

		phpdoc: {
			options: {
				verbose: true
			},
			application: {
				// src: ['../src/**/*.php'],
				src: [appConfig.symfonySrc],
				dest: 'doc/phpdoc'
			}
		},

		// The actual grunt server settings
		connect: {
			options: {
				port: 9000,
				// Change this to '0.0.0.0' to access the server from outside.
				hostname: 'localhost',
				livereload: 35729
			},
			livereload: {
				options: {
					open: true,
					middleware: function (connect) {
						return [
							connect.static('.tmp'),
							connect().use(
								'/bower_components',
								connect.static('./bower_components')
							),
							connect.static(appConfig.app)
						];
					}
				}
			},
			test: {
				options: {
					port: 9001,
					middleware: function (connect) {
						return [
							connect.static('.tmp'),
							connect.static('test'),
							connect().use(
								'/bower_components',
								connect.static('./bower_components')
							),
							connect.static(appConfig.app)
						];
					}
				}
			},
			dist: {
				options: {
					open: true,
					base: '<%= yeoman.dist %>'
				}
			}
		},

		/**
		 * Make sure code styles are up to par and there are no obvious mistakes
		 * @see {@link https://github.com/gruntjs/grunt-contrib-jshint}
		 */
		jshint: {
			options: {
				jshintrc: '.jshintrc',
				reporter: require('jshint-stylish')
			},
			src: {
				src: [
					appConfig.jsConfsSrc,
					appConfig.jsSrc
				]
			},
			spec: {
				options: {
					//jshintrc: 'spec/.jshintrc'
				},
				src: ['spec/{,*/}*.js']
			},
			test: {
				options: {
					jshintrc: 'test/.jshintrc'
				},
				src: [appConfig.testSrc]
			},
			ant: {
				options: {
					jshintrc: '.jshintrc',
					reporter: 'checkstyle',
					reporterOutput: appConfig.antReportLogPath + 'checkstyle-jslint.xml'
				},
				src: [
					appConfig.jsConfsSrc,
					appConfig.jsSrc
				]
			},
			antTest: {
				options: {
					jshintrc: 'test/.jshintrc',
					reporter: 'checkstyle',
					reporterOutput: appConfig.antReportLogPath + 'checkstyle-test-jslint.xml'
				},
				src: [appConfig.testSrc]
			}
		},

		/**
		 * @see {@link https://github.com/sindresorhus/grunt-eslint}
		 * @see {@link https://github.com/sindresorhus/grunt-eslint/issues/53|Support multiple targets}
		 */
		eslint: {
			src: {
				options: {
					configFile: '.eslintrc'
				},
				src: [appConfig.jsSrc]
			},
			ant: {
				options: {
					configFile: '.eslintrc',
					format: 'checkstyle',
					outputFile: appConfig.antReportLogPath + 'checkstyle-eslint.xml'
				},
				src: [appConfig.jsSrc]
			}
		},

		// https://github.com/jscs-dev/grunt-jscs
		jscs: {
			src: {
				src: [appConfig.jsSrc],
				options: {
					config: '.jscsrc',
					//requireCurlyBraces: [ "if" ]
				}
			},
			ant: {
				src: [appConfig.jsSrc],
				options: {
					config: '.jscsrc',
					reporter: 'checkstyle',
					reporterOutput: appConfig.antReportLogPath + 'checkstyle-jscs.xml'
				}
			}
		},

		// https://github.com/vkadam/grunt-jsbeautifier
		jsbeautifier: {
			files: [appConfig.jsSrc, appConfig.qunitSrc, appConfig.jasmineSrc, appConfig.jsConfsSrc],
			options: {
				// https://github.com/beautify-web/js-beautify#options
				config: '.jsbeautifyrc'
			}
		},

		jsdoc: {
			dist: {
				src: [appConfig.jsSrc],
				options: {
					destination: 'doc/jsdoc',
					//template : "node_modules/grunt-jsdoc/node_modules/ink-docstrap/template",
					//configure : "node_modules/grunt-jsdoc/node_modules/ink-docstrap/template/jsdoc.conf.json"
					verbose: true,
					//debug not work yet in current version 3.2.2
					//debug: true
				}
			}
		},

		/**
		 * grunt-contrib-sass
		 * @see https://github.com/gruntjs/grunt-contrib-sass
		 *
		 * Compile Sass to CSS using Sass.
		 */
		sass: {
			watch: {
				files: [{
					expand: true,
					cwd: appConfig.scssCwd,
					src: [appConfig.scssSrc],
					dest: '.tmp/css',
					ext: '.css'
				}],
				debugInfo: true,
				lineNumbers: true,
				noCache: true
			},
			dist: {
				files: [{
					expand: true,
					cwd: appConfig.scssCwd,
					src: [appConfig.scssSrc],
					dest: '.tmp/css',
					ext: '.css'
				}],
				noCache: true
			}
		},

		/**
		 * grunt-contrib-cssmin
		 * @see https://github.com/gruntjs/grunt-contrib-cssmin
		 *
		 * Run predefined tasks whenever watched file patterns are added, changed or deleted.
		 */
		cssmin: {
			combine: {
				options: {
					report: 'gzip',
					keepSpecialComments: 0,
					sourceMap: true
				},
				files: {
					'web/built/min.css': [
						'.tmp/css/**/*.css'
					]
				}
			}
		},

		/**
		 * @see {@link https://github.com/karma-runner/grunt-karma}
		 */
		karma: {
			qunit: {
				configFile: 'karma.conf.qunit.js',
				// https://github.com/karma-runner/grunt-karma#karma-server-with-grunt-watch
				background: true,
				singleRun: false
			},
			jasmine: {
				configFile: 'karma.conf.jasmine.js',
				// https://github.com/karma-runner/grunt-karma#karma-server-with-grunt-watch
				// Karma is blocking another tasks
				// https://github.com/karma-runner/grunt-karma/issues/4
				// Delay in starting 'karma:unit:run' in background mode
				// https://github.com/yeoman/grunt-regarde
				// When using background mode, task initialisation does not run tests
				// https://github.com/karma-runner/grunt-karma/issues/41
				background: true,
				singleRun: false
			},
			qunitSingle: {
				configFile: 'karma.conf.qunit.js',
				background: false,
				singleRun: true
			},
			jasmineSingle: {
				configFile: 'karma.conf.jasmine.js',
				background: false,
				singleRun: true
			}
		}
	});

	//simply run all the tests and exits, run karma signleRun mode and no backgroud 
	grunt.registerTask('testjs', [
		'karma:qunitSingle',
		'karma:jasmineSingle'
	]);

	grunt.registerTask('serve', 'Compile then start a connect web server', function (target) {
		if (target === 'dist') {
			return grunt.task.run(['build', 'connect:dist:keepalive']);
		}

		grunt.task.run([
			'clean:server',
			'wiredep',
			'concurrent:server',
			'autoprefixer',
			'connect:livereload',
			'watch'
		]);
	});

	grunt.registerTask('server', 'DEPRECATED TASK. Use the "serve" task instead', function (target) {
		grunt.log.warn('The `server` task has been deprecated. Use `grunt serve` to start a server.');
		grunt.task.run(['serve:' + target]);
	});

	/**
	 * @see {@link https://stackoverflow.com/questions/20526920/are-there-global-variables-in-grunt/20528955#20528955}
	 * @see {@link http://gruntjs.com/frequently-asked-questions#globals-and-configs}
	 */
	grunt.registerTask('karmarun', 'karmarun', function (target) {

		// you could run watch the tasks inside the same process by using options: { spawn: false }
		console.log('karmarun func', global.karmaRun);
		if (global.karmaRun[target] !== true) {
			global.karmaRun[target] = true;
			return grunt.task.run(['karma:' + target + ':start']);
		}
		// balck magic, it looks like some kind of cache, only run test change second time
		grunt.task.run(['karma:' + target + ':run', 'karma:' + target + ':run']);
		//grunt.task.run(['karma:' + target + ':run']);
	});

	grunt.registerTask('default', [
		'newer:jshint',
		'test',
		'build'
	]);

	// grunt.registerTask('scss', ['sass:watch', 'cssmin']);
	// symfony2 asstic handle css, not necessary cssmin
	grunt.registerTask('scss', ['sass:watch']);

	grunt.registerTask('ant-js-linters', ['jshint:ant', 'jshint:antTest', 'eslint:ant', 'jscs:ant']);
	grunt.registerTask('checkphp', ['phpcbf', 'phpcsfixer', 'phplint', 'phpcs', 'phpmd-runner']);
	grunt.registerTask('checkjs', ['jsbeautifier', 'jshint:src', 'eslint:src', 'jscs:src', 'jshint:test']);
};
