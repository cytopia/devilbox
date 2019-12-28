'use strict';

const fs = require('fs');
const Crawler = require('crawler');
const path = require('path');

/**
 * Sort the object keys
 * @see https://stackoverflow.com/a/48112249/5155484
 * @param {Object} obj The object
 * @param {Function} arraySorter The sorter callback
 */
const sortObject = function(obj, arraySorter) {
    if (typeof obj !== 'object') {
        return obj;
    }
    if (Array.isArray(obj)) {
        if (arraySorter) {
            obj.sort(arraySorter);
        }
        for (var i = 0; i < obj.length; i++) {
            obj[i] = sortObject(obj[i], arraySorter);
        }
        return obj;
    }
    var temp = {};
    var keys = [];
    for (var key in obj) {
        keys.push(key);
    }
    keys.sort();
    for (var index in keys) {
        temp[keys[index]] = sortObject(obj[keys[index]], arraySorter);
    }
    return temp;
};

const writeJSON = function(filename, data, cbSuccess = null) {
    fs.writeFile(filename, JSON.stringify(sortObject(data), null, 2) + '\n', function(err) {
        if (err) {
            return console.log(err);
        } else {
            if (cbSuccess !== null) {
                cbSuccess();
            }
        }
    });
};

const readJSON = function(filename, callbackSuccess) {
    fs.readFile(filename, 'utf8', function(err, data) {
        if (err) {
            return console.log(err);
        }
        callbackSuccess(JSON.parse(data), filename);
    });
};

const listDirectory = function(dirname, callbackSuccess) {
    fs.readdir(dirname, (err, files) => {
        if (err) {
            return console.log(err);
        }
        callbackSuccess(files, dirname);
    });
};

const writePage = function(filePrefix, name, url, data, onWriteSuccess) {
    let pageKB = {
        url: url,
        name: name,
        data: data,
    };
    writeJSON(path.join(__dirname, '../', 'data', filePrefix + pageKB.name + '.json'), pageKB, onWriteSuccess);
};

const processDataExtraction = function(pages, filePrefix, parsePage) {
    return new Promise(resolve => {
        var nbrPagesProcessed = 0;
        var crawler = new Crawler({
            maxConnections: 1,
            // This will be called for each crawled page
            callback: function(error, res, done) {
                if (error) {
                    console.log(error);
                } else {
                    console.log('URL : ' + res.options.url);
                    parsePage(res.$, anchors => {
                        writePage(filePrefix, res.options.name, res.options.url, anchors, () => {
                            nbrPagesProcessed++;
                            if (nbrPagesProcessed === pages.length) {
                                resolve();
                            }
                        });
                    });
                }
                done();
            },
        });
        crawler.queue(
            pages.map(page => {
                return { uri: page.url, name: page.name, url: page.url };
            })
        );
    });
};

module.exports = {
    processDataExtraction: processDataExtraction,
    listDirectory: listDirectory,
    readJSON: readJSON,
    writeJSON: writeJSON,
};
