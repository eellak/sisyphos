
function nuSelectDialog(reopen){

    nuCreateDialog(600, 500, "Select Objects");
    
    nuDialogMultipleSelect('object_list', 50, 80, 20, nuGetObjectList());

}


function nuDialogMultipleSelect(id, t, l, s, list){

    // Create multiple select listbox

    var e = document.createElement('select');
    e.setAttribute('id', id);
    $('#nuProperties').append(e);
    $('#' + e.id).css({
        'position'    : 'absolute',
        'left'        : l + 'px',
        'top'         : t + 'px',
        'width'       : '440px',
        'font-family' : 'helvetica',
        'font-size'   : '14px',
    })
    .attr('multiple', 'multiple')
    .attr('size', s);

    e.setAttribute('onchange', 'nuSelectObjects()');

//    var option      = document.createElement('option');
//    option.value    = '';
//    option.appendChild(document.createTextNode('Unselect All'));
//    e.appendChild(option);

    for(var i = 0 ; i < list.length ; i++){

        var option   = document.createElement('option');
        if (list[i].split(',')[1] == '1') {
            option.selected = 'selected';
        }
        option.value = list[i].split(',')[0].split('|')[1];
        option.appendChild(document.createTextNode(list[i].split(',')[0].split('|')[2]));
        e.appendChild(option);

    }
}

function nuGetObjectList() {

    // Get objects list for listbox

    var ol = Array();

    ol     = nuGetObjectInSection(1,0,ol);
    ol     = nuGetObjectInSection(2,0,ol);

    for(var  g = 3 ; g < REPORT.groups.length ; g ++){   
        ol = nuGetObjectInSection(g,0,ol);
    }

    ol     = nuGetObjectInSection(0,0,ol);
    for(var  g = REPORT.groups.length - 1 ; g > 2  ; g --){
        ol = nuGetObjectInSection(g,1,ol);
    }    
    ol     = nuGetObjectInSection(2,1,ol);
    ol     = nuGetObjectInSection(1,1,ol);

    return ol;
}

function nuGetObjectInSection(g, s, ol) {

    // Get objects in one section

    os = Array();
    for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
        id      = REPORT.groups[g].sections[s].objects[o].id;
        content = $('#' + id).html();
        if (content != '') {
            content = ' - ' + content;
        }
        if(REPORT.groups[g].sections[s].objects[o].selected == 1){
            os.push( REPORT.groups[g].sections[s].objects[o].top + '|' +id + '|' + REPORT.groups[g].sections[s].label + ' - ' + id + content + ',1');
        } else {
            os.push( REPORT.groups[g].sections[s].objects[o].top + '|' + id + '|' + REPORT.groups[g].sections[s].label + ' - ' + id + content + ',0');
        }
    }
    os.sort(sortObject);
    for(var i = 0; i < os.length; i ++) {
        ol.push(os[i]);
    }

    return ol;
}


function sortObject(a,b) {

    // Sort the object by top value
    return parseInt(a.split('|')[0]) - parseInt(b.split('|')[0]);
    
}

function nuSelectObjects() {

    // Highlight the object or reverse

    $('#object_list option').each(function(i){
        if(this.value != '' ) {
            if (this.selected) {
                $('#'+ this.value).addClass('nuSelected');
                 nuSetObjectValue(this.value, 'selected', 1);

            } else {
                $('#'+ this.value).removeClass('nuSelected');
                nuSetObjectValue(this.value, 'selected', 0);
            }
        }
    });

}

function nuReopenSelectObjects(){

    // Reopen Select Objects dialog
    
    var title = String($('#nuDragTitle').html()).substr(6);

    if(title == 'Select Objects'){
        nuSelectDialog(true);
    }

}