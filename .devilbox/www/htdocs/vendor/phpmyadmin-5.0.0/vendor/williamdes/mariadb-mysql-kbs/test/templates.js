'use strict';

const expect = require('chai').expect;
const templates = require(__dirname + '/../scripts/sudo-bot/template.js');

module.exports = function() {
    suite('pr message', function() {
        test('prMessage for lambda files', function(done) {
            const commmitMsg = templates.prMessage(['a.json', 'ab/cd/ef.json', 'README.md']);
            expect(commmitMsg).to.equal('🤖 Some updates to review 🤖');
            done();
        });
        test('prMessage for MariaDB files', function(done) {
            const commmitMsg = templates.prMessage(['data/mariadb-aria-server-status-variables.json']);
            expect(commmitMsg).to.equal('🤖 [MariaDB] updates');
            done();
        });
        test('prMessage for MariaDB files and merged', function(done) {
            const commmitMsg = templates.prMessage([
                'dist/merged-raw.json',
                'dist/merged-raw.md',
                'dist/merged-slim.json',
                'dist/merged-ultraslim.json',
                'dist/merged-ultraslim.php',
                'data/mariadb-aria-server-status-variables.json',
            ]);
            expect(commmitMsg).to.equal('🤖 [MariaDB] updates');
            done();
        });
        test('prMessage for MariaDB files and others', function(done) {
            const commmitMsg = templates.prMessage([
                'a.json',
                'ab/cd/ef.json',
                'README.md',
                'data/mariadb-aria-server-status-variables.json',
            ]);
            expect(commmitMsg).to.equal('🤖 [MariaDB] updates 🚨🚨');
            done();
        });
        test('prMessage for MariaDB files and others and merged', function(done) {
            const commmitMsg = templates.prMessage([
                'dist/merged-raw.json',
                'dist/merged-raw.md',
                'dist/merged-slim.json',
                'dist/merged-ultraslim.json',
                'dist/merged-ultraslim.php',
                'a.json',
                'ab/cd/ef.json',
                'README.md',
                'data/mariadb-aria-server-status-variables.json',
            ]);
            expect(commmitMsg).to.equal('🤖 [MariaDB] updates 🚨🚨');
            done();
        });
        test('prMessage for MySQL files', function(done) {
            const commmitMsg = templates.prMessage(['data/mysql-server-options.json']);
            expect(commmitMsg).to.equal('🤖 [MySQL] updates');
            done();
        });
        test('prMessage for MySQL files and merged', function(done) {
            const commmitMsg = templates.prMessage([
                'dist/merged-raw.json',
                'dist/merged-raw.md',
                'dist/merged-slim.json',
                'dist/merged-ultraslim.json',
                'dist/merged-ultraslim.php',
                'data/mysql-server-options.json',
            ]);
            expect(commmitMsg).to.equal('🤖 [MySQL] updates');
            done();
        });
        test('prMessage for MySQL files and others', function(done) {
            const commmitMsg = templates.prMessage([
                'a.json',
                'ab/cd/ef.json',
                'README.md',
                'data/mysql-server-options.json',
            ]);
            expect(commmitMsg).to.equal('🤖 [MySQL] updates 🚨🚨');
            done();
        });
        test('prMessage for MySQL files and others and merged', function(done) {
            const commmitMsg = templates.prMessage([
                'dist/merged-raw.json',
                'dist/merged-raw.md',
                'dist/merged-slim.json',
                'dist/merged-ultraslim.json',
                'dist/merged-ultraslim.php',
                'a.json',
                'ab/cd/ef.json',
                'README.md',
                'data/mysql-server-options.json',
            ]);
            expect(commmitMsg).to.equal('🤖 [MySQL] updates 🚨🚨');
            done();
        });
        test('prMessage for MySQL and MariaDB files', function(done) {
            const commmitMsg = templates.prMessage([
                'data/mariadb-aria-server-status-variables.json',
                'data/mysql-server-options.json',
            ]);
            expect(commmitMsg).to.equal('🤖 [MariaDB] && [MySQL] updates');
            done();
        });
        test('prMessage for MySQL and MariaDB files and merged', function(done) {
            const commmitMsg = templates.prMessage([
                'dist/merged-raw.json',
                'dist/merged-raw.md',
                'dist/merged-slim.json',
                'dist/merged-ultraslim.json',
                'dist/merged-ultraslim.php',
                'data/mariadb-aria-server-status-variables.json',
                'data/mysql-server-options.json',
            ]);
            expect(commmitMsg).to.equal('🤖 [MariaDB] && [MySQL] updates');
            done();
        });
        test('prMessage for MySQL and MariaDB files and others', function(done) {
            const commmitMsg = templates.prMessage([
                'a.json',
                'ab/cd/ef.json',
                'README.md',
                'data/mariadb-aria-server-status-variables.json',
                'data/mysql-server-options.json',
            ]);
            expect(commmitMsg).to.equal('🤖 [MariaDB] && [MySQL] updates 🚨🚨');
            done();
        });
    });
    suite('commit message', function() {
        test('commitMessage for lambda files', function(done) {
            const commmitMsg = templates.commitMessage(['a.json', 'ab/cd/ef.json', 'README.md']);
            expect(commmitMsg).to.equal('update: 🤖 Some updates 🤖');
            done();
        });
        test('commitMessage for MariaDB files', function(done) {
            const commmitMsg = templates.commitMessage(['data/mariadb-aria-server-status-variables.json']);
            expect(commmitMsg).to.equal('update: [MariaDB] updates');
            done();
        });
        test('commitMessage for MariaDB files and others', function(done) {
            const commmitMsg = templates.commitMessage([
                'a.json',
                'ab/cd/ef.json',
                'README.md',
                'data/mariadb-aria-server-status-variables.json',
            ]);
            expect(commmitMsg).to.equal('update: [MariaDB] updates and other changes');
            done();
        });
        test('commitMessage for MySQL files', function(done) {
            const commmitMsg = templates.commitMessage(['data/mysql-server-options.json']);
            expect(commmitMsg).to.equal('update: [MySQL] updates');
            done();
        });
        test('commitMessage for MySQL files and others', function(done) {
            const commmitMsg = templates.commitMessage([
                'a.json',
                'ab/cd/ef.json',
                'README.md',
                'data/mysql-server-options.json',
            ]);
            expect(commmitMsg).to.equal('update: [MySQL] updates and other changes');
            done();
        });
        test('commitMessage for MySQL and MariaDB files', function(done) {
            const commmitMsg = templates.commitMessage([
                'data/mariadb-aria-server-status-variables.json',
                'data/mysql-server-options.json',
            ]);
            expect(commmitMsg).to.equal('update: [MariaDB] && [MySQL] updates');
            done();
        });
        test('commitMessage for MySQL and MariaDB files and others', function(done) {
            const commmitMsg = templates.commitMessage([
                'a.json',
                'ab/cd/ef.json',
                'README.md',
                'data/mariadb-aria-server-status-variables.json',
                'data/mysql-server-options.json',
            ]);
            expect(commmitMsg).to.equal('update: [MariaDB] && [MySQL] updates and other changes');
            done();
        });
    });
    suite('pr', function() {
        test('prContent', function(done) {
            const prContent = templates.prContent([
                'a.json',
                'ab/cd/ef.json',
                'data/mariadb-aria-server-status-variables.json',
                'dist/merged-raw.json',
                'dist/merged-raw.md',
                'dist/merged-slim.json',
                'dist/merged-ultraslim.json',
                'dist/merged-ultraslim.php',
                'data/mysql-server-options.json',
                'README.md',
            ]);
            expect(prContent).to.equal(
                'Dear human 🌻, after running my task the following files where updated:\n- `a.json` 👽\n- `ab/cd/ef.json` 👽\n- `data/mariadb-aria-server-status-variables.json` 🐳\n- `dist/merged-raw.json` 📦\n- `dist/merged-raw.md` 📦\n- `dist/merged-slim.json` 📦\n- `dist/merged-ultraslim.json` 📦\n- `dist/merged-ultraslim.php` 📦\n- `data/mysql-server-options.json` 🐬\n- `README.md` 👽\n'
            );
            done();
        });
        test('prContent one file', function(done) {
            const prContent = templates.prContent(['README.md']);
            expect(prContent).to.equal(
                'Dear human 🌻, after running my task the following file was updated:\n- `README.md` 👽\n'
            );
            done();
        });
        test('prBranch', function(done) {
            const prBranch = templates.prBranch([]);
            expect(prBranch).to.match(/^refs\/heads\/update\/[0-9]{13}$/);
            done();
        });
    });
};
