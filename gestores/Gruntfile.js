module.exports = function(grunt){

    require('jit-grunt')(grunt);

    grunt.initConfig({

        less:{
            development: {
                files: {
                    'assets/css/estilo.css' : 'assets/css/estilo.less',
                    'assets/css/boot.css' : 'assets/css/boot.less',
                }
            }
        },

        watch: {
            styles: {
                files: ['**/*.less'],
                tasks: ['less']
            }
        }

    });

    grunt.registerTask('default', ['less','watch']);

};
