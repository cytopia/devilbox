import {paths} from '../paths'

export function renameHelper(path, type) {

  const pathSplit = path.dirname.split('/')
  const themeName = pathSplit[0]
  if (path.dirname.includes('modules')) {
      console.log(path.dirname)
      const regex = /\/modules\/(.*)/gm;
      const m = regex.exec(path.dirname)
      if (m && path.basename.startsWith('index')) {
          console.log(m)
          path.basename =  path.basename.replace('index', m[1])
      }
      if (m && path.basename.startsWith('editor')) {
          path.basename =  path.basename.replace('editor', `${m[1]}-editor`)
      }
  } else {
      // Rename index.[js/css]  to the parent folder name if not a module
      const parent_folder = pathSplit[pathSplit.length - 1]
      if (parent_folder !== 'js' && parent_folder !== 'css' && parent_folder !== 'modules' && path.basename.startsWith('index')) {
          path.basename = path.basename.replace('index', parent_folder)
      }
  }
  if (process.env.NODE_ENV === 'production' && path.basename.indexOf('.min') === -1) path.extname = `.min${path.extname}`
  path.dirname = `${themeName}/${type==='script' ? paths.themes.scripts.dest : paths.themes.styles.dest}`

  return path;
}