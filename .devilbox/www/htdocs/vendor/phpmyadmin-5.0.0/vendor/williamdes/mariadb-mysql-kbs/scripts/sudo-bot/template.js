'use strict';

/**
 * @param {Array} modifiedFiles The modified files
 * @returns {string} The commit message
 */
const commitMessage = function(modifiedFiles) {
    const nbrMySQLFiles = modifiedFiles.filter(file => file.match(/mysql-[a-z\-\.]+.json$/g)).length;
    const nbrMariaDBFiles = modifiedFiles.filter(file => file.match(/mariadb-[a-z\-\.]+.json$/g)).length;
    const nbrMergedData = modifiedFiles.filter(file => file.match(/merged-(slim|ultraslim|raw).(json|md|php)$/g))
        .length;

    const hasMySQLFiles = nbrMySQLFiles > 0;
    const hasMariaDBFiles = nbrMariaDBFiles > 0;
    const hasOtherFiles = nbrMySQLFiles + nbrMariaDBFiles + nbrMergedData !== modifiedFiles.length;
    if (hasMariaDBFiles && hasMySQLFiles) {
        return 'update: [MariaDB] && [MySQL] updates' + (hasOtherFiles ? ' and other changes' : '');
    } else if (hasMariaDBFiles && !hasMySQLFiles) {
        return 'update: [MariaDB] updates' + (hasOtherFiles ? ' and other changes' : '');
    } else if (!hasMariaDBFiles && hasMySQLFiles) {
        return 'update: [MySQL] updates' + (hasOtherFiles ? ' and other changes' : '');
    }
    return 'update: 🤖 Some updates 🤖';
};

/**
 * @param {Array} modifiedFiles The modified files
 * @returns {string} The pr message
 */
const prMessage = function(modifiedFiles) {
    const nbrMySQLFiles = modifiedFiles.filter(file => file.match(/mysql-[a-z-]+.json$/g)).length;
    const nbrMariaDBFiles = modifiedFiles.filter(file => file.match(/mariadb-[a-z-]+.json$/g)).length;
    const nbrMergedData = modifiedFiles.filter(file => file.match(/merged-(slim|ultraslim|raw).(json|md|php)$/g))
        .length;

    const hasMySQLFiles = nbrMySQLFiles > 0;
    const hasMariaDBFiles = nbrMariaDBFiles > 0;
    const hasOtherFiles = nbrMySQLFiles + nbrMariaDBFiles + nbrMergedData !== modifiedFiles.length;
    if (hasMariaDBFiles && hasMySQLFiles) {
        return '🤖 [MariaDB] && [MySQL] updates' + (hasOtherFiles ? ' 🚨🚨' : '');
    } else if (hasMariaDBFiles && !hasMySQLFiles) {
        return '🤖 [MariaDB] updates' + (hasOtherFiles ? ' 🚨🚨' : '');
    } else if (!hasMariaDBFiles && hasMySQLFiles) {
        return '🤖 [MySQL] updates' + (hasOtherFiles ? ' 🚨🚨' : '');
    }
    return '🤖 Some updates to review 🤖';
};

/**
 * @param {Array} modifiedFiles The modified files
 * @returns {string} The pr content
 */
const prContent = function(modifiedFiles) {
    let message =
        'Dear human 🌻, after running my task the following file' +
        (modifiedFiles.length > 1 ? 's where updated:' : ' was updated:') +
        '\n';
    message += modifiedFiles
        .map(file => {
            let emoji = '👽';
            if (file.match(/mysql-[a-z-]+.json$/g)) {
                emoji = '🐬';
            }
            if (file.match(/mariadb-[a-z-]+.json$/g)) {
                emoji = '🐳';
            }
            if (file.match(/merged-(slim|ultraslim|raw).(json|md|php)$/g)) {
                emoji = '📦';
            }
            return '- `' + file + '` ' + emoji + '\n';
        })
        .join('');
    return message;
};

/**
 * @param {Array} modifiedFiles The modified files
 * @returns {string} The pr branch
 */
const prBranch = function(modifiedFiles) {
    return 'refs/heads/update/' + new Date().getTime();
};

module.exports = {
    commitMessage: commitMessage,
    prMessage: prMessage,
    prContent: prContent,
    prBranch: prBranch,
};
