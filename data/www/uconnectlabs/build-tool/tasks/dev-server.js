import gulp from 'gulp'
import Browser from 'browser-sync'
import {paths} from '../paths'
import {themesStyles, deprecatedStyles} from './styles'
import {themesScripts} from './scripts'

const browserSync = Browser.create()

export function devServer() {

    let config = {
        proxy: "https://one.uconnectlabs.test",
        open: false // 'external'
    }

    browserSync.init(config)

    gulp.watch(`./htdocs/wp-content/themes/uConnect*/css/**/*.scss`).on('change', (file) => {
        themesStyles().pipe(browserSync.stream({match: '**/*.css'}));
    })

    // TODO: enable it when less compiler in the docker removed
    // gulp.watch(paths.deprecated.styles.src).on('change', (file) => {
    //     deprecatedStyles(file, paths.deprecated.styles.dest).pipe(browserSync.stream())
    // })

    gulp.watch('./htdocs/wp-content/themes/uConnect*/js/**/*.js*').on('change', (file) => themesScripts().pipe(browserSync.reload()))
}
