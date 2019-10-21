
function nuAdjustDialog(){

    var top = 50;
    nuCreateDialog(410, 500, "Adjust Objects");
    nuDialogAdjustButton('Align To Left',           '0', top);
    top = top + 30;
    nuDialogAdjustButton('Align To Right',          '1', top);
    top = top + 30;
    nuDialogAdjustButton('Align To Top',            '2', top);
    top = top + 30;
    nuDialogAdjustButton('Align To Bottom',         '3', top);
    top = top + 50;
    nuDialogAdjustButton('Resize To Narrowest',     '4', top);
    top = top + 30;
    nuDialogAdjustButton('Resize To Widest',        '5', top);
    top = top + 30;
    nuDialogAdjustButton('Resize To Shortest',      '6', top);
    top = top + 30;
    nuDialogAdjustButton('Resize To Tallest',       '7', top);
    top = top + 50;
    nuDialogAdjustButton('Even Space Horizontally', '8', top);
    top = top + 30;
    nuDialogAdjustButton('Even Space Vertically',   '9', top);
    
}


function nuAdjustObjects(t){

    var id = t.id;
    if(id == 'nu_adjust_0'){                //-- Align To Left
        nuAlignLeft();
    }

    else if(id == 'nu_adjust_1'){                //-- Align To Right
        nuAlignRight();
    }

    else if(id == 'nu_adjust_2'){                //-- Align To Top
        nuAlignTop();
    }

    else if(id == 'nu_adjust_3'){                //-- Align To Bottom
        nuAlignBottom();
    }

    else if(id == 'nu_adjust_4'){                //-- Resize To Narrowest
        nuNarrowest();
    }

    else if(id == 'nu_adjust_5'){                //-- Resize To Widest
        nuWidest();
    }

    else if(id == 'nu_adjust_6'){                //-- Resize To Shortest
        nuShortest();
    }

    else if(id == 'nu_adjust_7'){                //-- Resize To Tallest
        nuTallest();
    }

    else if(id == 'nu_adjust_8'){                //-- Even Space Horizontally
        nuEvenHorizontal();
    }

    else if(id == 'nu_adjust_9'){                //-- Even Space Vertically
        nuEvenVertical();
    }

    nuSaveReport();

}


function nuAlignLeft(){

    var v  = 100000;
    var l  = 0;
    var id = '';
    for(var  g = 0 ; g < REPORT.groups.length ; g ++){    //-- unselect all Objects
        for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
            for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
                if(REPORT.groups[g].sections[s].objects[o].selected == 1){
                    l   = parseInt(REPORT.groups[g].sections[s].objects[o].left);
                    v   = Math.min(v, l);
                }
            }
        }
    }
    for(var  g = 0 ; g < REPORT.groups.length ; g ++){    //-- unselect all Objects
        for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
            for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
                if(REPORT.groups[g].sections[s].objects[o].selected == 1){
                    id = REPORT.groups[g].sections[s].objects[o].id;
                    REPORT.groups[g].sections[s].objects[o].left = v;
                    $('#' + id).css('left', v + 30);
                }
            }
        }
    }
}


function nuTallest(){

    var v  = 0;
    var l  = 0;
    var id = '';
    for(var  g = 0 ; g < REPORT.groups.length ; g ++){    //-- unselect all Objects
        for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
            for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
                if(REPORT.groups[g].sections[s].objects[o].selected == 1){
                    l   = parseInt(REPORT.groups[g].sections[s].objects[o].height);
                    v   = Math.max(v, l);
                }
            }
        }
    }
    for(var  g = 0 ; g < REPORT.groups.length ; g ++){    //-- unselect all Objects
        for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
            for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
                if(REPORT.groups[g].sections[s].objects[o].selected == 1){
                    id = REPORT.groups[g].sections[s].objects[o].id;
                    REPORT.groups[g].sections[s].objects[o].height = v;
                    $('#' + id).css('height', v);
                }
            }
        }
    }
}


function nuShortest(){

    var v  = 100000;
    var l  = 0;
    var id = '';
    for(var  g = 0 ; g < REPORT.groups.length ; g ++){    //-- unselect all Objects
        for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
            for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
                if(REPORT.groups[g].sections[s].objects[o].selected == 1){
                    l   = parseInt(REPORT.groups[g].sections[s].objects[o].height);
                    v   = Math.min(v, l);
                }
            }
        }
    }
    for(var  g = 0 ; g < REPORT.groups.length ; g ++){    //-- unselect all Objects
        for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
            for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
                if(REPORT.groups[g].sections[s].objects[o].selected == 1){
                    id = REPORT.groups[g].sections[s].objects[o].id;
                    REPORT.groups[g].sections[s].objects[o].height = v;
                    $('#' + id).css('height', v);
                }
            }
        }
    }
}




function nuNarrowest(){

    var v  = 100000;
    var l  = 0;
    var id = '';
    for(var  g = 0 ; g < REPORT.groups.length ; g ++){    //-- unselect all Objects
        for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
            for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
                if(REPORT.groups[g].sections[s].objects[o].selected == 1){
                    l   = parseInt(REPORT.groups[g].sections[s].objects[o].width);
                    v   = Math.min(v, l);
                }
            }
        }
    }
    for(var  g = 0 ; g < REPORT.groups.length ; g ++){    //-- unselect all Objects
        for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
            for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
                if(REPORT.groups[g].sections[s].objects[o].selected == 1){
                    id = REPORT.groups[g].sections[s].objects[o].id;
                    REPORT.groups[g].sections[s].objects[o].width = v;
                    $('#' + id).css('width', v);
                }
            }
        }
    }
}


function nuWidest(){

    var v  = 0;
    var l  = 0;
    var id = '';
    for(var  g = 0 ; g < REPORT.groups.length ; g ++){    //-- unselect all Objects
        for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
            for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
                if(REPORT.groups[g].sections[s].objects[o].selected == 1){
                    l   = parseInt(REPORT.groups[g].sections[s].objects[o].width);
                    v   = Math.max(v, l);
                }
            }
        }
    }
    for(var  g = 0 ; g < REPORT.groups.length ; g ++){    //-- unselect all Objects
        for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
            for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
                if(REPORT.groups[g].sections[s].objects[o].selected == 1){
                    id = REPORT.groups[g].sections[s].objects[o].id;
                    REPORT.groups[g].sections[s].objects[o].width = v;
                    $('#' + id).css('width', v);
                }
            }
        }
    }
}



function nuAlignRight(){

    var v    = 0;
    var l    = 0;
    var w    = 0; 
    var b    = 0;
    
    for(var  g = 0 ; g < REPORT.groups.length ; g ++){    //-- unselect all Objects
        for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
            for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
                if(REPORT.groups[g].sections[s].objects[o].selected == 1){
                    l = parseInt(REPORT.groups[g].sections[s].objects[o].left);
                    w = parseInt(REPORT.groups[g].sections[s].objects[o].width);
                    b = parseInt(REPORT.groups[g].sections[s].objects[o].borderWidth) * 2;
                    v = Math.max(v, l + w + b);
                }
            }
        }
    }
    for(var  g = 0 ; g < REPORT.groups.length ; g ++){    //-- unselect all Objects
        for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
            for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
                if(REPORT.groups[g].sections[s].objects[o].selected == 1){
                    l = parseInt(REPORT.groups[g].sections[s].objects[o].left);
                    w = parseInt(REPORT.groups[g].sections[s].objects[o].width);
                    b = parseInt(REPORT.groups[g].sections[s].objects[o].borderWidth) * 2;
                    REPORT.groups[g].sections[s].objects[o].left = v - w - b;
                    id = REPORT.groups[g].sections[s].objects[o].id;
                    $('#' + id).css('left', v - w - b + 30);
                }
            }
        }
    }

}



function nuAlignTop(){

    var v  = 100000;
    var t  = 0;
    var st = 0;
    var m  = 0;
    var id = '';
    for(var  g = 0 ; g < REPORT.groups.length ; g ++){    //-- unselect all Objects
        for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
            for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
                if(REPORT.groups[g].sections[s].objects[o].selected == 1){
                    t  = parseInt(REPORT.groups[g].sections[s].objects[o].top);
                    v  = Math.min(v, t);
                }
            }
        }
    }
    for(var  g = 0 ; g < REPORT.groups.length ; g ++){    //-- unselect all Objects
        for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
            for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
                if(REPORT.groups[g].sections[s].objects[o].selected == 1){
                    id = REPORT.groups[g].sections[s].objects[o].id;
                    m  = parseInt(REPORT.groups[g].sections[s].margins);
                    st = parseInt(REPORT.groups[g].sections[s].top);
                    b  = parseInt(REPORT.groups[g].sections[s].objects[o].borderWidth);
                    REPORT.groups[g].sections[s].objects[o].top = v - b;
                    $('#' + id).css('top', v + st + m);
                }
            }
        }
    }

}



function nuAlignBottom(){

    var v  = 0;
    var t  = 0;
    var h  = 0;
    var b  = 0;
    var st = 0;
    var m  = 0;
    var id = '';

    for(var  g = 0 ; g < REPORT.groups.length ; g ++){    //-- unselect all Objects
        for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
            for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
                if(REPORT.groups[g].sections[s].objects[o].selected == 1){
                    t = parseInt(REPORT.groups[g].sections[s].objects[o].top);
                    h = parseInt(REPORT.groups[g].sections[s].objects[o].height);
                    b = parseInt(REPORT.groups[g].sections[s].objects[o].borderWidth) * 2;
                    v = Math.max(v, t + h + b);
                }
            }
        }
    }
    for(var  g = 0 ; g < REPORT.groups.length ; g ++){    //-- unselect all Objects
        for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
            for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
                if(REPORT.groups[g].sections[s].objects[o].selected == 1){
                    id = REPORT.groups[g].sections[s].objects[o].id;
                    m  = parseInt(REPORT.groups[g].sections[s].margins);
                    st = parseInt(REPORT.groups[g].sections[s].top);
                    t  = parseInt(REPORT.groups[g].sections[s].objects[o].top);
                    h  = parseInt(REPORT.groups[g].sections[s].objects[o].height);
                    b  = parseInt(REPORT.groups[g].sections[s].objects[o].borderWidth) * 2;
                    REPORT.groups[g].sections[s].objects[o].top = v - h - b;
                    $('#' + id).css('top', v  - h + st + m);
                }
            }
        }
    }


}

function nuEvenHorizontal(){
    // Adjust the horizontal space between objects evenly and keep the most left and the most right one unchanged.
    lmin          = 0;
    lmax          = 0;
    onum          = 0;
    idmin         = "";
    idmax         = "";
    space         = 0;
    var width_sum = 0;
    // Calculate the space
    for(var  g = 0 ; g < REPORT.groups.length ; g ++){    
        for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
            for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
                if(REPORT.groups[g].sections[s].objects[o].selected == 1){
                    id = REPORT.groups[g].sections[s].objects[o].id;
                    l  = parseInt($('#' + id).css('left'));
                    onum++;
                    width_sum += parseInt($('#' + id).css('width'));
                    if (onum == 1) {
                        lmin  = l;
                        lmax  = l;
                        idmin = id;
                        idmax = id;
                    } else {
                        if (l<lmin){
                            lmin  = l;
                            idmin = id;
                        }
                        if (l > lmax) {
                            lmax  = l;
                            idmax = id;
                        }
                    }
                }
            }
        }
    }
    width_sum -= parseInt($('#' + idmax).css('width'));

    if (onum > 2) {
        space = ((lmax - lmin) - width_sum)/(onum -1);
    }
    
    if(space < 0 ) {
        return;
    }
    // Adjust the value of left property.
    onum = 0;
    var prev_id = idmin;
    var prev_l  = lmin;
    var prev_w  = 0;
    for(var  g = 0 ; g < REPORT.groups.length ; g ++){
        for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
            for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
                if(REPORT.groups[g].sections[s].objects[o].selected == 1){
                    id     = REPORT.groups[g].sections[s].objects[o].id;
                    prev_l = parseInt($('#' + prev_id).css('left'));
                    prev_w = parseInt($('#' + prev_id).css('width'));
                    if (id != idmin && id != idmax) {
                        onum++;
                        $('#' + id).css('left', (prev_l+prev_w) + space);
                        nuSetObjectValue(id, 'left', parseInt($('#'+id).css('left'))-30);
                        prev_id = id;
                    }
                }
            }
        }
    }
}

function nuEvenVertical(){
    // Adjust the vertical space between the objects in the same section evenly and keep the most up and bottom one unchanged.
    for(var  g = 0 ; g < REPORT.groups.length ; g ++){    
        for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
            // calculate the space
            tmin  = 0;
            tmax  = 0;
            onum  = 0;
            idmin = "";
            idmax = "";
            space = 0;

            for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
                if(REPORT.groups[g].sections[s].objects[o].selected == 1){
                    id = REPORT.groups[g].sections[s].objects[o].id;
                    t  =  REPORT.groups[g].sections[s].objects[o].top;
                    onum++;
                    if (onum == 1) {
                        tmin  = t;
                        tmax  = t;
                        idmin = id;
                        idmax = id;
                    } else {
                        if (t<tmin){
                            tmin  = t;
                            idmin = id;
                        }
                        if (t > tmax) {
                            tmax  = t;
                            idmax = id;
                        }
                    }
                }
            }

            if (onum > 2) {
                space = (tmax - tmin)/(onum -1);
            }
            onum = 0;

            // Adjust the value of top
            for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
                if(REPORT.groups[g].sections[s].objects[o].selected == 1){
                    id = REPORT.groups[g].sections[s].objects[o].id;
                    if (id != idmin && id != idmax) {
                        onum++;
                        st  = parseInt(REPORT.groups[g].sections[s].top);
                        m   = parseInt(REPORT.groups[g].sections[s].margins);
                        $('#' + id).css('top', tmin + space*onum + st + m);
                        REPORT.groups[g].sections[s].objects[o].top = tmin + space*onum;
                    }
                }
            }
        }
    }
}