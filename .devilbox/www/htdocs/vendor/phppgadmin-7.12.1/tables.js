var predefined_lengths = null;
var sizesLength = false;

function checkLengths(sValue,idx) {
        if(predefined_lengths) {
                if(sizesLength==false) {
                        sizesLength = predefined_lengths.length;
                }
                for(var i=0;i<sizesLength;i++) {
                        if(sValue.toString().toUpperCase()==predefined_lengths[i].toString().toUpperCase()) {
                                document.getElementById("lengths"+idx).value='';
                                document.getElementById("lengths"+idx).disabled='on';
                                return;
                        }
                }
                document.getElementById("lengths"+idx).disabled='';
        }
}
