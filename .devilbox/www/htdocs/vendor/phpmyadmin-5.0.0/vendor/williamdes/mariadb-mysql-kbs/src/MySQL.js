'use strict';

const common = require(__dirname + '/common');
const cleaner = require(__dirname + '/cleaner');

/**
 * Complete a doc element with info found in table
 * @param {HTMLTableRowElement[]} rows The table rows
 * @param {Object} doc The doc object
 */
function completeDoc($, rows, doc) {
    $(rows).each((i, elem) => {
        let tds = $(elem).find('td'); // first is key and last is value
        var name = tds
            .first()
            .text()
            .toLowerCase()
            .trim();
        var value = tds.last();
        switch (name) {
            case 'dynamic':
                doc.dynamic =
                    value
                        .text()
                        .toLowerCase()
                        .trim() === 'yes';
                break;
            case 'name':
                doc.name = value.text().trim();
                break;
            case 'system variable':
                // Do not overwrite the name
                if (typeof doc.name === 'undefined') {
                    doc.name = value
                        .text()
                        .toLowerCase()
                        .trim();
                }
                break;
            case 'scope':
                let scope = value.text().toLowerCase();
                if (scope === 'both') {
                    // found on mysql-cluster-options-variables.html
                    doc.scope = ['global', 'session'];
                } else if (scope != '') {
                    doc.scope = scope.split(',').map(item => {
                        if (item.match(/session/)) {
                            return 'session';
                        } else if (item.match(/global/)) {
                            return 'global';
                        } else {
                            return item.trim();
                        }
                    });
                }
                if (doc.scope !== undefined) {
                    doc.scope = doc.scope.filter(function(e) {
                        return e === 0 || e;
                    });
                }
                break;
            case 'type':
                let type = value
                    .text()
                    .toLowerCase()
                    .trim();
                if (type != '') {
                    doc.type = cleaner.cleanType(type);
                }
                break;
            case 'default value':
            case 'default, range':
                doc.default = cleaner.cleanDefault(value.text().trim());
                break;
            case 'valid values':
                doc.validValues = $(value)
                    .find('code')
                    .get()
                    .map(el => $(el).text());
                break;
            case 'minimum value':
                if (doc.range == undefined) {
                    doc.range = {};
                }
                doc.range.from = parseFloat(value.text().trim());
                break;
            case 'maximum value':
                if (doc.range == undefined) {
                    doc.range = {};
                }
                doc.range.to = parseFloat(value.text().trim());
                break;
            case 'command-line format':
                doc.cli = cleaner.cleanCli(value.text().trim());
                break;
            case 'command line':
                if (typeof doc.cli !== 'string') {
                    doc.cli =
                        value
                            .text()
                            .toLowerCase()
                            .trim() === 'yes';
                }
                break;
        }
    });
}

/**
 * Create a doc element
 * @param {Element} element The root element
 * @returns object The doc object
 */
function createDoc($, element, doc) {
    completeDoc($, $(element).find('tbody > tr'), doc);
    if (doc.range !== undefined) {
        doc.range = cleaner.cleanRange(doc.range);
    }

    if (doc.name && doc.name.match(cleaner.regexCli)) {
        delete doc.name;
    }

    return doc;
}

function parsePage($, cbSuccess) {
    var anchors = [];
    $('.informaltable, .table')
        .filter(function(i, elem) {
            return (
                $(elem)
                    .find('th')
                    .first()
                    .text() === 'Property'
            );
        })
        .each(function(i, elem) {
            let doc = {
                id: $(elem)
                    .prevAll()
                    .find('a')
                    .filter(function(i, el) {
                        return typeof $(el).attr('name') === 'string' && typeof $(el).attr('class') === 'undefined';
                    })
                    .first()
                    .attr('name'),
            };
            if (typeof doc.id !== 'string') {
                doc.id = $(elem)
                    .prevAll()
                    .find('.link')
                    .first()
                    .attr('href')
                    .split('#')[1];
            }
            createDoc($, elem, doc);
            if (typeof doc.cli === 'boolean') {
                doc.cli = $(elem)
                    .prevAll()
                    .find('.option')
                    .first()
                    .text();
                if (doc.cli === '') {
                    delete doc.cli;
                }
            }
            if (!doc.name && doc.cli) {
                var matches = doc.cli.match(cleaner.regexCli);
                doc.name = matches[2].replace(/-/g, '_');
            }
            anchors.push(doc);
        });

    cbSuccess(anchors);
}

const KB_URL = 'https://dev.mysql.com/doc/refman/8.0/en/';
const KB_URL57 = 'https://dev.mysql.com/doc/refman/5.7/en/';

const pages = [
    {
        url: KB_URL + 'server-system-variables.html',
        name: 'server-system-variables',
    },
    {
        url: KB_URL + 'innodb-parameters.html',
        name: 'innodb-parameters',
    },
    {
        url: KB_URL + 'performance-schema-system-variables.html',
        name: 'performance-schema-system-variables',
    },
    {
        url: KB_URL + 'x-plugin-options-system-variables.html',
        name: 'x-plugin-options-system-variables',
    },
    {
        url: KB_URL + 'replication-options-binary-log.html',
        name: 'replication-options-binary-log',
    },
    {
        url: KB_URL57 + 'replication-options-binary-log.html',
        name: 'replication-options-binary-log_5.7',
    },
    {
        url: KB_URL + 'pluggable-authentication-system-variables.html',
        name: 'pluggable-authentication-system-variables',
    },
    {
        url: KB_URL + 'audit-log-reference.html',
        name: 'audit-log-reference',
    },
    {
        url: KB_URL + 'replication-options-gtids.html',
        name: 'replication-options-gtids',
    },
    {
        url: KB_URL + 'replication-options-slave.html',
        name: 'replication-options-slave',
    },
    {
        url: KB_URL + 'replication-options-master.html',
        name: 'replication-options-master',
    },
    {
        url: KB_URL + 'replication-options.html',
        name: 'replication-options',
    },
    {
        url: KB_URL57 + 'mysql-cluster-options-variables.html',
        name: 'mysql-cluster-options-variables',
    },
    {
        url: KB_URL + 'server-options.html',
        name: 'server-options',
    },
    {
        url: KB_URL + 'version-tokens-reference.html',
        name: 'version-tokens-reference',
    },
];

module.exports = {
    parsePage: parsePage,
    createDoc: createDoc,
    completeDoc: completeDoc,
    run: () => {
        return common.processDataExtraction(pages, 'mysql-', parsePage);
    },
};
