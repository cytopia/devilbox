export const basePath = `./htdocs/wp-content/themes/`
export const paths = {
    deprecated: {
        styles: {
            src: ['./htdocs/wp-content/*plugins/uconnect*/**/*.less'],
            dest: '.',
        },
        scripts: {
            src: ['./htdocs/wp-content/*plugins/uconnect*/**/*.js', './htdocs/wp-content/themes/**/js/uconnect*.js'],
            dest: '.',
        }
    },
    themes: {
        styles: {
            src: ['css/styles.scss', 'css/editor.scss', 'css/admin-styles.scss', 'css/modules/**/index.scss', 'css/modules/**/editor.scss', 'css/**/index.scss'],
            dest: 'dist/css/',
        },
        scripts: {
            src: ['js/**/index.js*', 'js/**/editor.js*'],
            dest: 'dist/js/',
        }
    }
};
