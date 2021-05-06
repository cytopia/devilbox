import gulp from 'gulp'
import rename from 'gulp-rename'
import gulpIgnore from 'gulp-ignore'
import gulpif from 'gulp-if'
import cached from 'gulp-cached'
import dependents from 'gulp-dependents'
import named from 'vinyl-named-with-path'
import webpack from 'webpack-stream'
import plumber from 'gulp-plumber'
import notify from 'gulp-notify'
import debug from 'gulp-debug'

import log from './log'
import {paths, basePath} from '../paths'
import {webpackConfig} from '../webpack.config'
import { renameHelper } from './helpers'

export function themesScripts() {
    const isDev = process.env.NODE_ENV === 'development'
    const isProd = process.env.NODE_ENV === 'production'

    return gulp.src(`${basePath}uConnect*/js/**/*.js*`, {
        base: basePath,
        allowEmpty: true
    })
        // prevent caching to force all the files compile for production.
        .pipe(gulpif(isDev, cached('themes_script')))
        // Do not break tasks and gulp in case of error. Just show a notification.
        .pipe(gulpif(isDev, plumber({errorHandler: (error)=>{
            console.log(error);
            notify.onError({
                title:    "Gulp",
                subtitle: "Failure!",
                message:  "Error: <%= error.message %>",
                sound:    "Beep"
            })(error);

            this.emit('end');
            }}))
        )
        // find all the files dependent to the current processing file and add them to the pipeline.
        .pipe(dependents({
            ".js": {
                parserSteps: [
                    /^\s*import.*\s+['|"](.+?)['|"][;|\n]/gm,
                ],
                prefixes: [],
                postfixes: ['.js'],
                basePaths: [],
            }
        }))
        // exclude dependencies and only keep files should be compiled
        .pipe(gulpIgnore.include(paths.themes.scripts.src.map(src => `uConnect*/${src}`)))
        // named required here to make named patches. otherwise Webpack bundle all of the files together.
        .pipe(named())
        // Run webpack to compile and build bundles.
        .pipe(webpack(webpackConfig))
        // prepend theme name and append module name to the destination path. Also rename index.js files to the name of their parent folders.
        .pipe(rename(function (path) {
            path = renameHelper(path, 'script')
        }))
        // Write file to destination
        .pipe(gulp.dest(basePath))
        // write version of file into /themes/js_versions.php (versions are md5 of file contents)
        .pipe(gulpif(isProd, log('js_versions')));
}


// This if for old js files in the plugins and theme folder.
export function deprecatedScripts() {
        return gulp.src(paths.deprecated.scripts.src, {base: "./"})
            // write version of file into /themes/plugins_scripts_versions.php (versions are md5 of file contents)
            .pipe(gulpif(process.env.NODE_ENV === 'production', log('deprecated_scripts_versions')));
}
