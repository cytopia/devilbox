var gulp = require('gulp');
var less = require('gulp-less');

const paths = {
    styles:{
        src: ['htdocs/wp-content/*plugins/uconnect*/**/*.less'],
        dest: '.'
    }
};

//A one-off task to convert all less files, rung gulp convert_all_less
gulp.task('convert_all_less', async function(){
    less_to_css( paths.styles.src );
});

function less_to_css(file) {
    console.log('converting: ' , file);
    return gulp.src(file, { base: "./" })
        .pipe(less())
        .pipe(gulp.dest(paths.styles.dest));
}
gulp.task('watch', function() {
    // Watch all the .less files, then run the less task
    gulp.watch(paths.styles.src).on('change', less_to_css);
});

//The default task watches for changed less files and converts them to css
gulp.task('default', gulp.series('watch'));
