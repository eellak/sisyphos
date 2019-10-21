    

    function nuNextID(){
        window.ID = window.ID + 1;
        return window.ID;
    }
  
    function nuCreateObject(o, sectionTop){

        var d = document.createElement('div');  
        var g = 0;
        var s = 0;

        d.setAttribute('ondblclick',  'nuShowObjectProperties(this)');
        d.setAttribute('onmousedown', 'nuHighlightObject(this, event)');
        d.setAttribute('onmouseup',   'nuStopResize(this)');
        d.setAttribute('onmousemove', 'nuMouseMove(event)');
 
        
        if(arguments.length == 0){                                              //-- new Object
            
            var i = nuNextID();

            d.setAttribute('id', 'object' + i);
            
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
            
            $('#nuSectionIndex20').after(d);
            $('#' + d.id).css({
                'position'         : 'absolute',
                'height'           : '20px',
                'left'             : '100px',
                'top'              : '50px',
                'width'            : '100px',
                'z-index'          : z,
                'border-style'     : 'solid',
                'border-width'     : '0px',
                'font-family'      : 'Arial',
                'font-size'        : '14px',
                'font-weight'      : 'normal',
                'color'            : 'black',
                'background-color' : 'white',
                'text-align'       : 'left',
                'cursor'           : 'cell',
                'overflow'         : 'hidden'
            })
            .addClass( 'nuReportObject');

            var o     = new nuOBJECT('object' + i, '', z);
            
        }else{                                                                  //-- load Object

            d.setAttribute('id', o.id);
            window.ID = Math.max(window.ID, Number(o.id.substr(6)) + 1);
            o.top     = nuGetSectionValue(window.GRP[o.group].sections[o.section].sectionID, 'top');
 
            $('#nuSectionIndex20').after(d);
            $('#' + d.id).css({
                'position'         : 'absolute',
                'height'           : o.height,
                'left'             : o.left + 30,
                'top'              : o.top,
                'width'            : o.width,
                'z-index'          : o.zIndex,
                'border-style'     : o.borderStyle,
                'border-width'     : o.borderWidth,
                'font-family'      : o.familyFont,
                'font-size'        : o.fontSize,
                'font-weight'      : o.fontWeight,
                'color'            : o.color,
                'background-color' : o.backgroundColor,
                'text-align'       : o.textAlign,
                'cursor'           : 'cell',
                'overflow'         : 'hidden',
            })
            .addClass( 'nuReportObject');
            
            g = o.group;
            s = o.section;

        }

        window.REPORT.groups[g].sections[s].objects.push(o);
        
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

        if(arguments.length == 0){                                              //-- new Object
            
            nuReadjustSections();                    //-- make Sections Bigger if an Object overlaps it
            nuMoveAllObjects();
            nuReopenSelectObjects();
            nuSaveReport();
            
        }
    }
  

