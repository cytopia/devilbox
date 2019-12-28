'use strict';

const expect = require('chai').expect;
const cleaner = require(__dirname + '/../src/cleaner');

module.exports = function() {
    suite('cleaner', function() {
        test('clean cli html code', function(done) {
            const cli = cleaner.cleanCli('<code>--test-argument</code>');
            expect(cli).to.equal('--test-argument');
            done();
        });
        test('clean cli html code not closed', function(done) {
            const cli = cleaner.cleanCli('<code>--test-argument');
            expect(cli).to.equal('--test-argument');
            done();
        });
        test('clean cli nothing to clean', function(done) {
            const cli = cleaner.cleanCli('--test-argument');
            expect(cli).to.equal('--test-argument');
            done();
        });
        test('clean cli undefined', function(done) {
            const cli = cleaner.cleanCli(undefined);
            expect(cli).to.equal(undefined);
            done();
        });
        test('clean range undefined', function(done) {
            const range = cleaner.cleanRange(undefined);
            expect(range).to.deep.equal(undefined);
            done();
        });
        test('clean range.from typeof object (dataset-1)', function(done) {
            const range = cleaner.cleanRange({
                from: null,
                to: null,
            });
            expect(range).to.deep.equal({});
            done();
        });
        test('clean range.from typeof object (dataset-2)', function(done) {
            const range = cleaner.cleanRange({
                to: null,
            });
            expect(range).to.deep.equal({});
            done();
        });
        test('clean range.from typeof object (dataset-3)', function(done) {
            const range = cleaner.cleanRange({
                from: null,
            });
            expect(range).to.deep.equal({});
            done();
        });
        test('clean range.from typeof object (dataset-4)', function(done) {
            const range = cleaner.cleanRange({
                from: undefined,
                to: undefined,
            });
            expect(range).to.deep.equal({});
            done();
        });
        test('clean range.from typeof object (dataset-5)', function(done) {
            const range = cleaner.cleanRange({
                to: undefined,
            });
            expect(range).to.deep.equal({});
            done();
        });
        test('clean range.from typeof object (dataset-6)', function(done) {
            const range = cleaner.cleanRange({
                from: undefined,
            });
            expect(range).to.deep.equal({});
            done();
        });
        test('clean range.from typeof object (dataset-7)', function(done) {
            const range = cleaner.cleanRange({
                from: NaN,
                to: NaN,
            });
            expect(range).to.deep.equal({});
            done();
        });
        test('clean range.from typeof int', function(done) {
            const range = cleaner.cleanRange({
                from: 1024,
            });
            expect(range).to.deep.equal({
                from: 1024,
            });
            done();
        });
        test('clean range.from typeof string', function(done) {
            const range = cleaner.cleanRange({
                from: '1024',
            });
            expect(range).to.deep.equal({});
            done();
        });
        test('clean range.to typeof int', function(done) {
            const range = cleaner.cleanRange({
                to: 1024,
            });
            expect(range).to.deep.equal({
                to: 1024,
            });
            done();
        });
        test('clean range.to typeof string', function(done) {
            const range = cleaner.cleanRange({
                to: '1024',
            });
            expect(range).to.deep.equal({});
            done();
        });
        test('clean range.to typeof object', function(done) {
            const range = cleaner.cleanRange({
                to: {},
            });
            expect(range).to.deep.equal({});
            done();
        });
        test('clean range to upwards', function(done) {
            const range = cleaner.cleanRange({
                to: 'upwards',
            });
            expect(range).to.deep.equal({
                to: 'upwards',
            });
            done();
        });
        test('clean range to upwards match', function(done) {
            const range = cleaner.cleanRange({
                to: '(128KB) upwards',
            });
            expect(range).to.deep.equal({
                to: 'upwards',
            });
            done();
        });
        test('clean binary types in bytes', function(done) {
            const type = cleaner.cleanType('in bytes');
            expect(type).to.deep.equal('byte');
            done();
        });
        test('clean binary types size in mb', function(done) {
            const type = cleaner.cleanType('size in mb');
            expect(type).to.deep.equal('byte');
            done();
        });
        test('clean binary types number of bytes', function(done) {
            const type = cleaner.cleanType('number of bytes');
            expect(type).to.deep.equal('byte');
            done();
        });
        test('clean binary types number of', function(done) {
            const type = cleaner.cleanType('number of');
            expect(type).to.deep.equal('integer');
            done();
        });
        test('clean binary types size of', function(done) {
            const type = cleaner.cleanType('size of');
            expect(type).to.deep.equal('integer');
            done();
        });
        test('clean binary types in microseconds', function(done) {
            const type = cleaner.cleanType('in microseconds');
            expect(type).to.deep.equal('integer');
            done();
        });
        test('clean binary types in seconds', function(done) {
            const type = cleaner.cleanType('in seconds');
            expect(type).to.deep.equal('integer');
            done();
        });
        test('clean wtf type', function(done) {
            const type = cleaner.cleanType('wtf');
            expect(type).to.deep.equal(undefined);
            done();
        });
        test('clean enumeration type', function(done) {
            const type = cleaner.cleanType('enumeration');
            expect(type).to.deep.equal('enumeration');
            done();
        });
        test('clean undefined type', function(done) {
            const type = cleaner.cleanType(undefined);
            expect(type).to.deep.equal(undefined);
            done();
        });
    });
};
