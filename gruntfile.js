module.exports = function(grunt) {

	grunt.initConfig( {

		makepot: {
	        target: {
	            options: {
	                cwd: '.',
	                domainPath: 'languages',
	                mainFile: 'notification-flamingo.php',
	                exclude: [
		                'node_modules/',
		                'assets',
		                'bin',
		                'tests',
		                '.git/'
		            ],
	                potHeaders: {
	                    poedit: true,
	                    'x-poedit-keywordslist': true
	                },
	                type: 'wp-plugin',
	                updatePoFiles: true
	            }
	        }
	    },

	    addtextdomain: {
	        options: {
	            textdomain: 'notification-flamingo'
	        },
	        target: {
	            files: {
	                src: [
	                    'notification-flamingo.php',
	                    './inc/**/*.php',
	                    './class/**/*.php',
	                    './views/**/*.php'
	                ]
	            }
	        }
	    }

	} );

	grunt.loadNpmTasks( 'grunt-wp-i18n' );

	grunt.registerTask( 'textdomain', ['addtextdomain'] );

};
