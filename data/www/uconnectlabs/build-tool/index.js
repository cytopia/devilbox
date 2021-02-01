import gulp from 'gulp'

import {devServer} from './tasks/dev-server'
import {themesStyles,deprecatedStyles} from './tasks/styles'
import {themesScripts, deprecatedScripts} from './tasks/scripts'

export const dev = gulp.series(devServer)
export const build = gulp.series(deprecatedStyles, themesStyles, themesScripts, deprecatedScripts)
export const build_dev = gulp.series(deprecatedStyles, themesStyles, themesScripts)

export default dev
