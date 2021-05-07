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

    gulp.watch(`./htdocs/wp-content/themes/uConnect*/css/**/*.scss`, () => themesStyles().pipe(browserSync.stream({match: '**/*.css'})))

    gulp.watch(paths.deprecated.styles.src).on('change', (file) => {
        return deprecatedStyles(file).pipe(browserSync.stream())
    })

    gulp.watch('./htdocs/wp-content/themes/uConnect*/js/**/*.js*', () => themesScripts())

    gulp.watch(paths.deprecated.scripts.src).on('change', () => {
        browserSync.reload()
    })
}
