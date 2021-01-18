import fs from 'fs'
import through2 from 'through2'
import md5 from 'md5-jkmyers'
import {basePath} from "../paths";

export default function log(name) {
    let files = [];
    return through2.obj(function (file, enc, callback) {
        files.push(file)
        this.push(file);
        return callback();
    }, function (cb) {
        let phpString = `<?php \n $${name}=[`
        phpString += files.filter(file => !file.path.endsWith('.map')).map(file => {
            const regex = /\/wp-content\/(.*)/gm;
            return `'${regex.exec(file.path)[1]}' => '${file.contents !== '' ? md5(file.contents.toString('utf8')) : 'empty'}'`
        }).join(',\n')
        phpString += ']; \n'
        fs.writeFileSync(`${basePath}${name}.php`, phpString);
        cb();
    });
}
