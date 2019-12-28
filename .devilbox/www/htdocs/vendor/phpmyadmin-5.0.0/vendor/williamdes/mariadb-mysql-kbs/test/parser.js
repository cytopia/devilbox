'use strict';

const expect = require('chai').expect;
const MySQL = require(__dirname + '/../src/MySQL');
const cheerio = require('cheerio');
const fs = require('fs');

module.exports = function() {
    suite('parser', function() {
        test('test case 1', function(done) {
            const $ = cheerio.load(fs.readFileSync(__dirname + '/data/mysql_test_case_1.html'));
            MySQL.parsePage($, function(resultData) {
                expect(resultData).to.deep.equal([
                    {
                        cli: '--ndbcluster',
                        default: 'FALSE (Version: NDB 7.5-7.6)',
                        dynamic: false,
                        id: 'option_mysqld_ndbcluster',
                        name: 'ndbcluster',
                    },
                    {
                        cli: '--ndb-allow-copying-alter-table=[ON|OFF]',
                        default: 'ON (Version: NDB 7.5-7.6)',
                        dynamic: true,
                        id: 'option_mysqld_ndb-allow-copying-alter-table',
                        name: 'ndb-allow-copying-alter-table',
                        scope: ['global', 'session'],
                    },
                ]);
                done();
            });
        });
        test('test case 2', function(done) {
            const $ = cheerio.load(fs.readFileSync(__dirname + '/data/mysql_test_case_2.html'));
            MySQL.parsePage($, function(resultData) {
                expect(resultData).to.deep.equal([
                    {
                        cli: '--binlog-gtid-simple-recovery[={OFF|ON}]',
                        default: 'ON',
                        dynamic: false,
                        id: 'sysvar_binlog_gtid_simple_recovery',
                        name: 'binlog_gtid_simple_recovery',
                        scope: ['global'],
                        type: 'boolean',
                    },
                    {
                        cli: '--enforce-gtid-consistency[=value]',
                        default: 'OFF',
                        dynamic: true,
                        id: 'sysvar_enforce_gtid_consistency',
                        name: 'enforce_gtid_consistency',
                        scope: ['global'],
                        type: 'enumeration',
                        validValues: ['OFF', 'ON', 'WARN'],
                    },
                    {
                        dynamic: false,
                        id: 'sysvar_gtid_executed',
                        name: 'gtid_executed',
                        scope: ['global', 'session'],
                        type: 'string',
                    },
                    {
                        cli: '--gtid-executed-compression-period=#',
                        default: '1000',
                        dynamic: true,
                        id: 'sysvar_gtid_executed_compression_period',
                        name: 'gtid_executed_compression_period',
                        range: {
                            from: 0,
                            to: 4294967295,
                        },
                        scope: ['global'],
                        type: 'integer',
                    },
                    {
                        cli: '--gtid-mode=MODE',
                        default: 'OFF',
                        dynamic: true,
                        id: 'sysvar_gtid_mode',
                        name: 'gtid_mode',
                        scope: ['global'],
                        type: 'enumeration',
                        validValues: ['OFF', 'OFF_PERMISSIVE', 'ON_PERMISSIVE', 'ON'],
                    },
                    {
                        default: 'AUTOMATIC',
                        dynamic: true,
                        id: 'sysvar_gtid_next',
                        name: 'gtid_next',
                        scope: ['session'],
                        type: 'enumeration',
                        validValues: ['AUTOMATIC', 'ANONYMOUS', 'UUID:NUMBER'],
                    },
                    {
                        dynamic: false,
                        id: 'sysvar_gtid_owned',
                        name: 'gtid_owned',
                        scope: ['global', 'session'],
                        type: 'string',
                    },
                    {
                        dynamic: true,
                        id: 'sysvar_gtid_purged',
                        name: 'gtid_purged',
                        scope: ['global'],
                        type: 'string',
                    },
                ]);
                done();
            });
        });
        test('test case 3', function(done) {
            const $ = cheerio.load(fs.readFileSync(__dirname + '/data/mysql_test_case_3.html'));
            MySQL.parsePage($, function(resultData) {
                expect(resultData).to.deep.equal([
                    {
                        default: 'TRUE (Version: 5.1.51-ndb-7.2.0)',
                        dynamic: true,
                        id: 'sysvar_ndb_join_pushdown',
                        name: 'ndb_join_pushdown',
                        scope: ['global', 'session'],
                    },
                ]);
                done();
            });
        });
    });
};
