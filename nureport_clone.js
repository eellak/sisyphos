function nuOBJECTCopy(i,s){
        var z = 99;
        for(var v = 0; v< REPORT.groups.length; v++) {                           //-- find highest z-index
            for(var x = 0; x< REPORT.groups[v].sections.length;x++) {
                for(var y = 0; y< REPORT.groups[v].sections[x].objects.length;y++) {
                    if(REPORT.groups[v].sections[x].objects[y].zIndex > z) {
                        z = REPORT.groups[v].sections[x].objects[y].zIndex;
                    }
                }
            }
        }
        z++;
        

        this.id              = i;

        this.group           = s.group;                         //-- Group it belongs to.
        this.section         = s.section;                         //-- Header or Footer.
        this.objectType      = s.objectType;
        this.left            = s.left + 3;
        this.top             = s.top;                         //-- relative to the Section it belongs to.
        this.height          = s.height;
        this.width           = s.width;
        this.backgroundColor = s.backgroundColor;
        this.borderColor     = s.borderColor;
        this.borderWidth     = s.borderWidth;
        this.fieldName       = s.fieldName;
        this.fontColor       = s.fontColor;
        this.fontFamily      = s.fontFamily;
        this.fontSize        = s.fontSize;
        this.fontWeight      = s.fontWeight;
        this.format          = s.format;
        this.textAlign       = s.textAlign;
        this.phpCall         = s.phpCall;
        this.zIndex          = z;
        this.minRows         = s.minRows;
        this.maxRows         = s.maxRows;
        this.selected        = 0;
        this.toselect        = 1;
        this.name            = i;

}
function nuCopyObjects(){


    for(var  g = 0 ; g < REPORT.groups.length ; g ++){    //-- unselect all Objects
        for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
            for(var  ob = 0 ; ob < REPORT.groups[g].sections[s].objects.length ; ob ++){
                if(REPORT.groups[g].sections[s].objects[ob].selected == 1){
                    REPORT.groups[g].sections[s].objects[ob].tocopy = 1;
                } else {
                    REPORT.groups[g].sections[s].objects[ob].tocopy = 0;
                }
            }
        }
    }
}



function nuCloneObjects(paste){

    for(var  g = 0 ; g < REPORT.groups.length ; g ++){    //-- unselect all Objects
        for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
            for(var  ob = 0 ; ob < REPORT.groups[g].sections[s].objects.length ; ob ++){
                if (paste) tocopy = REPORT.groups[g].sections[s].objects[ob].tocopy;
                else tocopy = REPORT.groups[g].sections[s].objects[ob].selected;
                if(tocopy == 1){
                
                    var st     = parseInt(REPORT.groups[g].sections[s].top);
                    var m      = parseInt(REPORT.groups[g].sections[s].margins);

                    var i      = nuNextID();
                    var o      = new nuCopyObject(REPORT.groups[g].sections[s].objects[ob]);
                    o.id       = 'object' + i;
                    o.left     = REPORT.groups[g].sections[s].objects[ob].left + 3;
                    var z = 99;
                    for(var v = 0; v< REPORT.groups.length; v++) {                           //-- find highest z-index
                        for(var x = 0; x< REPORT.groups[v].sections.length;x++) {
                            for(var y = 0; y< REPORT.groups[v].sections[x].objects.length;y++) {
                                if(REPORT.groups[v].sections[x].objects[y].zIndex > z) {
                                    z = REPORT.groups[v].sections[x].objects[y].zIndex;
                                }
                            }
                        }
                    }
                    z++;
                    o.zIndex   = z;
                    o.minRows  = REPORT.groups[g].sections[s].objects[ob].minRows;
                    o.maxRows  = REPORT.groups[g].sections[s].objects[ob].maxRows;
                    o.selected = 0;
                    o.toselect = 1;
                    o.name     = o.id;

                    window.REPORT.groups[g].sections[s].objects.push(o);

                    var d = document.createElement('div');
                    d.setAttribute('id', 'object' + i);
                    d.setAttribute('ondblclick',  'nuShowObjectProperties(this)');
                    d.setAttribute('onMousedown', 'nuHighlightObject(this, event)');
                    d.setAttribute('onmouseup',   'nuMouseUp(this,event)');
                    d.setAttribute('onmousemove', 'nuMouseMove(event)');

                    $('#nuSectionIndex20').after(d);
                    $('#' + d.id).css({
                        'cursor'           : 'cell',
                        'overflow'         : 'hidden',
                        'position'         : 'absolute',
                        'z-index'          : o.zIndex,
                        'height'           : o.height,
                        'left'             : o.left + 30,
                        'top'              : o.top + st + m,
                        'width'            : o.width,
                        'border-style'     : 'solid',
                        'border-width'     : o.borderWidth,
                        'font-family'      : o.fontFamily,
                        'font-size'        : o.fontSize + 'px',
                        'color'            : o.fontColor,
                        'background-color' : o.backgroundColor,
                        'text-align'       : o.textAlign,
                    })
                    .addClass( 'nuReportObject');

                    switch(o.fontWeight) {
                        case "I":
                            $('#' + o.id).css('font-style', 'italic');
                            break;
                        case "B":
                            $('#' + o.id).css('font-weight', 'bold');
                            break;
                        default:
                            $('#' + o.id).css('font-style', 'normal');
                            break;
                    }
                    
                    $('#' + d.id).html( $("#"+REPORT.groups[g].sections[s].objects[ob].id).html());

                    $('#' + d.id).draggable({

                        start: function( event, ui ){
                            nuSetOffset(this.id);
                        },

                        drag: function( event, ui ){
                            nuMoveSelected(this.id);
                        },

                        stop: function( event, ui ){
                            nuReadjustSections();                    //-- make Sections Bigger if an Object overlaps it
                            nuMoveAllObjects();
                            nuReopenSelectObjects();
                            nuSaveReport();
                        }

                    });

                    nuReadjustSections();                    //-- make Sections Bigger if an Object overlaps it
                    nuMoveAllObjects();

                }
            }
        }
    }

    for(var  g = 0 ; g < REPORT.groups.length ; g ++){    //-- unselect all Objects
        for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
            for(var  ob = 0 ; ob < REPORT.groups[g].sections[s].objects.length ; ob ++){
                if(REPORT.groups[g].sections[s].objects[ob].toselect == 1){
                    REPORT.groups[g].sections[s].objects[ob].toselect = 0;
                    REPORT.groups[g].sections[s].objects[ob].selected = 1;
                    $("#"+REPORT.groups[g].sections[s].objects[ob].id).addClass("nuSelected");
                } else {
                    REPORT.groups[g].sections[s].objects[ob].selected = 0;
                    $("#"+REPORT.groups[g].sections[s].objects[ob].id).removeClass("nuSelected");
                }
            }
        }
    }

    nuReopenSelectObjects();
    nuSaveReport();

}


