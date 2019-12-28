'use strict';

process.env.TZ = 'UTC';
const templates = require(__dirname + '/templates');
const cleaner = require(__dirname + '/cleaner');
const parser = require(__dirname + '/parser');

suite('MariaDB MySQL KBS', function() {
    templates();
    cleaner();
    parser();
});
