
function nuObjectDialog(){
    
    nuCreateDialog(500, 575, "Object Properties");

    nuCreateInputs();

    $("[id^='property_']").each(function(n) {                       //-- move selected
        var pid = $("[id^='property_']")[n].id;
        document.getElementById(pid).setAttribute('onfocus', 'window.nuSaveObject=this.id');
        document.getElementById(pid).setAttribute('onChange', 'nuSetOnBlur("'+pid+'")');
        document.getElementById(pid).setAttribute('onKeyDown', 'focusField(event, "'+pid+'")');
    });

    $('#property_1').change(function() {
        nuHideProperties();
        nuReopenObjectProperties(true);
    });
    
    var all = nuGetSelectedPropertyValues();
    
    for(var i = 0 ; i < all.length ; i ++){
        $('#property_' + i).val(all[i].split('#nuSep#')[1]);
    }

    nuHideProperties();
}    

function focusField(e, pid) {
    if(e.keyCode == 9) {
        if(e.shiftKey) {
            var nextId = parseInt(pid.split("_")[1])-1;
            if(nextId < 0) nextId = 19;
        } else {
            var nextId = parseInt(pid.split("_")[1])+1;
            if(nextId > 19) nextId = 0;
        }
        setTimeout(function(){$("#property_" + nextId).select();},0);
    }
    else if(e.keyCode == 13) {
        setTimeout(function(){$("#"+pid).select();},0);
    }
}

function nuSetOnBlur(pid){
    
    if(window.nuSaveObject==0){return;}                                     //-- already saved
    
    nuSetObjectProperties(pid);
    window.nuSaveObject = 0;                                                //-- does not need saving
    nuSaveReport();
}


function nuCreateInputs() {


    var ty = Array();
    ty.push('field|Field');
    ty.push('label|Label');
    //ty.push('pagebreak|Page Break');
    ty.push('image|Image');

    var ta = Array();
    ta.push('left|Left');
    ta.push('right|Right');
    ta.push('center|Center');

    var yn = Array();
    yn.push('1|Yes');
    yn.push('0|No');

    var fo = Array();
    fo.push('0|10000');
    fo.push('1|10000.0');
    fo.push('2|10000.00');
    fo.push('3|10000.000');
    fo.push('4|10000.0000');
    fo.push('5|10000.00000');
    fo.push('6|13-Jan-2007');
    fo.push('7|13-01-2007');
    fo.push('8|Jan-13-2007');
    fo.push('9|01-13-2007');
    fo.push('10|13-Jan-07');
    fo.push('11|13-01-07');
    fo.push('12|Jan-13-07');
    fo.push('13|01-13-07');
    fo.push('14|10,000');
    fo.push('15|10,000.0');
    fo.push('16|10,000.00');
    fo.push('17|10,000.000');
    fo.push('18|10,000.0000');
    fo.push('19|10,000.00000');
    fo.push('20|10000');
    fo.push('21|10000,0');
    fo.push('22|10000,00');
    fo.push('23|10000,000');
    fo.push('24|10000,0000');
    fo.push('25|10000,00000');
    fo.push('26|10.000');
    fo.push('27|10.000,0');
    fo.push('28|10.000,00');
    fo.push('29|10.000,000');
    fo.push('30|10.000,000');
    fo.push('31|10.000,0000');
    fo.push('33|2007-01-13');

    var fw = Array();
    fw.push('|Normal');
    fw.push('B|Bold');
    fw.push('I|Italic');

    var ff = Array();
    ff.push('Helvetica|Helvetica|Helvetica');
    ff.push('Arial|Arial|Arial');
    ff.push('Courier|Courier|Courier');
    ff.push('Times|Times|Times');
    ff.push('Symbol|Symbol|Symbol');
    ff = ff.concat(window.nuFONTS);

    var top = 20;
    var ind = 0;
    nuDialogInput('Object Name',        'property_'+ind, top, 200);
    top = top + 25;
    ind ++;
    nuDialogSelect('Object Type',       'property_'+ind, top, 200, ty);
    top = top + 25;
    ind ++;
    nuDialogInput('Top',                'property_'+ind, top, 200);
    top = top + 25;
    ind ++;
    nuDialogInput('Left',               'property_'+ind, top, 200);
    top = top + 25;
    ind ++;
    nuDialogInput('Height',             'property_'+ind, top, 200);
    top = top + 25;
    ind ++;
    nuDialogInput('Width',              'property_'+ind, top, 200);
    top = top + 25;
    ind ++;
    nuDialogInput('Background Color',   'property_'+ind, top, 200);
    top = top + 25;
    ind ++;
    nuDialogInput('Border Color',       'property_'+ind, top, 200);
    top = top + 25;
    ind ++;
    nuDialogInput('Border Width',       'property_'+ind, top, 200);
    top = top + 25;
    ind ++;
    nuDialogInput('Field',              'property_'+ind, top, 200);
    top = top + 25;
    ind ++;
    nuDialogInput('Minimum Rows',       'property_'+ind, top, 200);
    top = top + 25;
    ind ++;
    nuDialogInput('Maximum Rows',       'property_'+ind, top, 200);
    top = top + 25;
    ind ++;
    nuDialogInput('Font Color',         'property_'+ind, top, 200);
    top = top + 25;
    ind ++;
    nuDialogSelect('Font Family',       'property_'+ind, top, 200, ff);
    top = top + 25;
    ind ++;
    nuDialogInput('Font Size',          'property_'+ind, top, 200);
    top = top + 25;
    ind ++;
    nuDialogSelect('Font Style',        'property_'+ind, top, 200, fw);
    top = top + 25;
    ind ++;
    nuDialogSelect('Format',            'property_'+ind, top, 200, fo);
    top = top + 25;
    ind ++;
    nuDialogSelect('Text Align',        'property_'+ind, top, 200, ta);
    top = top + 25;
    ind ++;
    nuDialogInput('Field, Code or URL', 'property_'+ind, top, 200);
    top = top + 25;
    ind ++;
    nuDialogInput('Z Index',            'property_'+ind, top, 200);
}

function nuHideProperties(){

    var to = 45;
    
    for (var i = 0; i < 20 ; i ++ ) {
        
        $('#title_property_' + i).css({
            'top'        : to,
            'visibility' : 'visible'
        });
        $('#property_' + i).css({
            'top'        : to,
            'visibility' : 'visible'
        });
        to = to + 25;

    }

    var v = $('#property_1').val();

    if(v == ''){return;}
    
    var arrayName = 'nu' + v.split('|')[0];

    for(h = 0 ; h < window[arrayName].length ; h ++){
    
        var p = window[arrayName][h];

        $('#title_property_' + p).css('visibility','hidden');
        $('#property_' + p).css('visibility','hidden');
        
        for (i = parseInt(p) ; i < 20 ; i++) {
            t = parseInt($('#property_' + i).css('top').split("px")[0]) - 25;
            $('#title_property_' + i).css('top',t);
            $('#property_' + i).css('top',t);
        }
    }

}

function nuReopenObjectProperties(){

    var title = String($('#nuDragTitle').html()).substr(6);
    
    if(title == 'Object Properties'){

        nuObjectDialog(true);
    }

}

function nuSetObjectProperties(id){

    var index = Number(id.substr(9));
    var prop  = window.OBJ[index].split('|')[0];
    var style = window.OBJ[index].split('|')[1];
    var value = $('#' + id).val();

    if(style == 'z-index') {
        if (value >= 1000 || value < 100) {
            alert("Must be between 100 and 999");
            value = 100;
            $('#' + id).val(value);
        }
    }

    for(var i = 0 ; i < $('.nuSelected').length ; i++){
        var oid = $('.nuSelected')[i].id;
        nuSetObjectValue(oid, prop, value);
    }

    if (prop == 'objectType') {
        for(var i = 0 ; i < $('.nuSelected').length ; i++){
            var oid = $('.nuSelected')[i].id;
            $('#' + oid).css('border-style', 'solid');
        }
        return;
    }

//========================================html
    if(style == 'html'){
        $('.nuSelected').html(value);
        return;
        
    }

//========================================top
    if(style == 'top'){

        for(var i = 0 ; i < $('.nuSelected').length ; i++){
        
            var oid      = $('.nuSelected')[i].id;
            var O        = nuGetObjectFamilyTree(oid);   // -- returns [group, section, object]
            var sTop     = REPORT.groups[O[0]].sections[O[1]].top;
            var sMargins = REPORT.groups[O[0]].sections[O[1]].margins;

            $('#' + oid).css('top', Number(sTop) + Number(sMargins) +  Number(value));
            
        }

    }



    
//========================================(x axis related)
    if(style == 'border-width' || style == 'left' || style == 'width'){

        nuCheckSideMargins();
        if(style == 'left'){
            
            value=Number(value)+30;
            $('.nuSelected').css(style, Number(value));
            
        }else{
            
            $('.nuSelected').css(style, Number(value));
            
        }
        nuReadjustSections();
        nuMoveAllObjects();
        return;

    }

//========================================(y axis related)
    if(style == 'border-width' || style == 'height' || style == 'top'){

        if (style == 'height') {
            $('.nuSelected').css(style, value);
        }
        nuReadjustSections();
        nuMoveAllObjects();
        return;
    }

    if(style == 'font-size'){
        $('.nuSelected').css(style, parseInt(value));
        return;
    }

    if (style == 'font-family') {
        $('.nuSelected').css(style, $('#' + id + ' option:selected').attr('cssv'));
        return;
    }

    if (style == 'font-weight') {
        if (value == 'B') {
            $('.nuSelected').css(style, 'bold').css('font-style','normal');
        }
        else if (value == 'I') {
            $('.nuSelected').css(style, 'normal').css('font-style','italic');
        }
        else {
            $('.nuSelected').css(style, 'normal').css('font-style','normal');
        }
        return;
    }

    if(style != ''){
        $('.nuSelected').css(style, value);
    }
    
}


function nuCheckSideMargins(style){
    for(var i = 0 ; i < $('.nuSelected').length ; i++){

        var id = $('.nuSelected')[i].id;
        var b  = parseInt(nuGetObjectValue(id, 'borderWidth')) * 2;
        var w  = parseInt(nuGetObjectValue(id, 'width'));
        var l  = parseInt(nuGetObjectValue(id, 'left'));
        if(l < 0){                                                   //-- too far left
            nuSetObjectValue(id, 'left', 1);
            $('#' + id).css('left', 31);
        } else if(b + w > nuChangeWidth(REPORT.width)){                                    //-- too wide for report
        
            nuSetObjectValue(id, 'left', 0);
            nuSetObjectValue(id, 'width', REPORT.width - w);
            $('#' + id).css({
                'left'  : 0,
                'width' : nuChangeWidth(REPORT.width) - w
            });
            
        } else if(l + b + w > nuChangeWidth(REPORT.width) + 30){                           //-- too far right
            l = parseInt(REPORT.width) + 30 - b - w;
            l = (l >= 0) ? l : 30;
            nuSetObjectValue(id, 'left', l );
            $('#' + id).css({
                'left'  : l,
                'width' : w
            });
            
        } else {
            $('#' + id).css({
            'left'         : l + 30,
            'width'        : w,
            'border-width' : b/2
        });

        }
    }
}

    
    
    function nuGetSelectedPropertyValues(){     //-- this returns an array to populate the Object Dialog
    
        var ALL = [];
        var sep = '#nuSep#';
        
        for(var i = 0 ; i < window.OBJ.length ; i ++){
            ALL[i]  = window.OBJ[i].split('|')[0];
        }
        
        for(var  g = 0 ; g < REPORT.groups.length ; g ++){
            for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
                for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
                
                    if(REPORT.groups[g].sections[s].objects[o].selected == 1){
//=====check to see if all values are the same========================================  
                        for(var i = 0 ; i < ALL.length ; i ++){
                        
                            if(ALL[i].split(sep).length == 1){
                                ALL[i] = ALL[i].split(sep)[0] + sep + REPORT.groups[g].sections[s].objects[o][ALL[i]];   //-- set first value
                            }else{
                                var p  = ALL[i].split(sep)[0];
                                var v  = ALL[i].split(sep)[1];
                                
                                if(REPORT.groups[g].sections[s].objects[o][p] != v){
                                    v = '';                                                                     //-- set value as blank
                                }
                                ALL[i] = ALL[i].split(sep)[0] + sep + v;                              
                            }
                        }
//====================================================================================  
                    }
                }
            }
        }
        
        return ALL;
    }
    
