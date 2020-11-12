    // Globals
    //


    /*
     * Multiple Selection lists in HTML Document
     */
    var tableColumnList;
    var indexColumnList;

    /*
     * Two Array vars
     */

    var indexColumns, tableColumns;


    function buttonPressed(object) {

             if (object.name == "add") {
                 from = tableColumnList;
                 to = indexColumnList;
             }
             else {
                 to = tableColumnList;
                 from = indexColumnList;
             }

             var selectedOptions = getSelectedOptions(from);

             for (i = 0; i < selectedOptions.length; i++) {
                  option = new Option(selectedOptions[i].text);
                  addToArray(to, option);
                  removeFromArray(from, selectedOptions[i].index);
             }
    }

    function doSelectAll() {
      for(var x = 0; x < indexColumnList.options.length; x++){
         indexColumnList.options[x].selected = true;
      }
    }

    function init() {
             tableColumnList = document.formIndex.TableColumnList;
             indexColumnList = document.getElementById("IndexColumnList");
             indexColumns = indexColumnList.options;
             tableColumns = tableColumnList.options;
    }


    function getSelectedOptions(obj) {
             var selectedOptions = new Array();

             for (i = 0; i < obj.options.length; i++) {
                  if (obj.options[i].selected) {
                      selectedOptions.push(obj.options[i]);
                  }
             }

             return selectedOptions;
    }

    function removeFromArray(obj, index) {
             obj.remove(index);
    }

    function addToArray(obj, item) {
             obj.options[obj.options.length] = item;
    }