function ChangeCursor(cell) {
    cell.style.cursor="pointer";
}

function RestoreCursor(cell) {
    cell.style.cursor="auto";
}

function toggle(source,class_name) {
  checkboxes = document.getElementsByClassName(class_name);
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].indeterminate = false;
    checkboxes[i].checked = source.checked;
  }
}

function make_indeterminate(chk_name) {
    var all_chk = document.getElementById(chk_name);
    all_chk.indeterminate = true;
}

function adjustIframeHeight(minHeight=350) {
    if (typeof parent.adjustHeight === "function") { 
        var frm = window.frameElement;
         if(frm){
            parent.adjustHeight(frm,minHeight); 
         }
    }
}