"use strict";

var designerTables = [{
  name: 'pdf_pages',
  key: 'pgNr',
  autoIncrement: true
}, {
  name: 'table_coords',
  key: 'id',
  autoIncrement: true
}]; // eslint-disable-next-line no-unused-vars

var DesignerOfflineDB = function () {
  var designerDB = {};
  var datastore = null;

  designerDB.open = function (callback) {
    var version = 1;
    var request = window.indexedDB.open('pma_designer', version);

    request.onupgradeneeded = function (e) {
      var db = e.target.result;
      e.target.transaction.onerror = designerDB.onerror;
      var t;

      for (t in designerTables) {
        if (db.objectStoreNames.contains(designerTables[t].name)) {
          db.deleteObjectStore(designerTables[t].name);
        }
      }

      for (t in designerTables) {
        db.createObjectStore(designerTables[t].name, {
          keyPath: designerTables[t].key,
          autoIncrement: designerTables[t].autoIncrement
        });
      }
    };

    request.onsuccess = function (e) {
      datastore = e.target.result;

      if (typeof callback !== 'undefined' && callback !== null) {
        callback(true);
      }
    };

    request.onerror = designerDB.onerror;
  };

  designerDB.loadObject = function (table, id, callback) {
    var db = datastore;
    var transaction = db.transaction([table], 'readwrite');
    var objStore = transaction.objectStore(table);
    var cursorRequest = objStore.get(parseInt(id));

    cursorRequest.onsuccess = function (e) {
      callback(e.target.result);
    };

    cursorRequest.onerror = designerDB.onerror;
  };

  designerDB.loadAllObjects = function (table, callback) {
    var db = datastore;
    var transaction = db.transaction([table], 'readwrite');
    var objStore = transaction.objectStore(table);
    var keyRange = IDBKeyRange.lowerBound(0);
    var cursorRequest = objStore.openCursor(keyRange);
    var results = [];

    transaction.oncomplete = function () {
      callback(results);
    };

    cursorRequest.onsuccess = function (e) {
      var result = e.target.result;

      if (Boolean(result) === false) {
        return;
      }

      results.push(result.value);
      result.continue();
    };

    cursorRequest.onerror = designerDB.onerror;
  };

  designerDB.loadFirstObject = function (table, callback) {
    var db = datastore;
    var transaction = db.transaction([table], 'readwrite');
    var objStore = transaction.objectStore(table);
    var keyRange = IDBKeyRange.lowerBound(0);
    var cursorRequest = objStore.openCursor(keyRange);
    var firstResult = null;

    transaction.oncomplete = function () {
      callback(firstResult);
    };

    cursorRequest.onsuccess = function (e) {
      var result = e.target.result;

      if (Boolean(result) === false) {
        return;
      }

      firstResult = result.value;
    };

    cursorRequest.onerror = designerDB.onerror;
  };

  designerDB.addObject = function (table, obj, callback) {
    var db = datastore;
    var transaction = db.transaction([table], 'readwrite');
    var objStore = transaction.objectStore(table);
    var request = objStore.put(obj);

    request.onsuccess = function (e) {
      if (typeof callback !== 'undefined' && callback !== null) {
        callback(e.currentTarget.result);
      }
    };

    request.onerror = designerDB.onerror;
  };

  designerDB.deleteObject = function (table, id, callback) {
    var db = datastore;
    var transaction = db.transaction([table], 'readwrite');
    var objStore = transaction.objectStore(table);
    var request = objStore.delete(parseInt(id));

    request.onsuccess = function () {
      if (typeof callback !== 'undefined' && callback !== null) {
        callback(true);
      }
    };

    request.onerror = designerDB.onerror;
  };

  designerDB.onerror = function (e) {
    // eslint-disable-next-line no-console
    console.log(e);
  }; // Export the designerDB object.


  return designerDB;
}();