<?php require_once('nucommon.php'); ?>
<!doctype html>
 
<html>
<head>
    <meta charset="utf-8" />
    <title>Sisyphos Report Builder</title>
    
    <link rel="stylesheet" href="jquery/jquery-ui.css" />
    <script src="jquery/jquery-1.8.3.js" type='text/javascript'></script>
    <script src="jquery/jquery-ui.js" type='text/javascript'></script>



<?php

jsinclude('nuformat.js');
jsinclude('nucommon.js');
jsinclude('nureport_new.js');
jsinclude('nureport_clone.js');
jsinclude('nureport_select.js');
jsinclude('nureport_adjust.js');
jsinclude('nureport_group.js');
jsinclude('nureport_object.js');
jsinclude('nureport_report.js');
jsinclude('nureport_copy.js');

$fonts      = explode("\n", trim($GLOBALS['nuSetup']->set_fonts));
$array      = '';

for($i = 0 ; $i < count($fonts) ; $i ++){

    if(trim($fonts[$i]) != ''){
        $font   = $fonts[$i];
        $array .= "    window.nuFONTS.push('$font|$font|$font');\n";
    }

}

$script    = "

<script>

    window.nuFONTS = [];

$array

</script>

";

print $script;



?>




  
  <style>
  body                     {}
  .nuSelected              {cursor:move;outline:2px solid red}
  .nuDialogFocus           {outline:2px solid red}
  .nuReportObject          {cursor:cell}
  .nuSection               {}
  .nuShadeHolder           {border-bottom-left-radius:5px;border-bottom-right-radius:5px;border-top-right-radius:5px;border-top-left-radius:5px;box-shadow: 5px 5px 5px #888888;position:absolute;top:70px;left:0px;margin:auto;width:1002px;height:542px}
  .nuToolbar               {cursor: pointer; list-style-type: none; text-align: center;display: inline-block;width:130px;height:50px;padding:10x}
  </style>
  
  
  
  <script>

    window.REPORT              = new nuREPORT();
    window.REPORTS             = [];
    window.nuShiftKey          = false;
    window.nuControlKey        = false;
    window.nuResize            = false;
    window.nuHasMoved          = false;
    window.nuMouseStartX       = 0;
    window.nuMouseStartY       = 0;
    window.nuSaveObject        = 0;
    window.nuSavingObjects     = 1;
    window.nuGroupWas          = '';
    window.ID                  = 1000;
    window.OBJ                 = [];
    window.PRP                 = [];
    window.GRP                 = [];
    window.PRP.push('position');
    window.PRP.push('width');
    window.PRP.push('height');
    window.PRP.push('top');
    window.PRP.push('left');
    window.PRP.push('border-style');
    window.PRP.push('border-width');
    window.PRP.push('font-family');
    window.PRP.push('font-size');
    window.PRP.push('font-weight');
    window.PRP.push('color');
    window.PRP.push('background-color');
    window.PRP.push('text-align');
    window.PRP.push('padding');
    
    window.OBJ.push('name|');                                       //-- 0
    window.OBJ.push('objectType|');                                 //-- 1
    window.OBJ.push('top|top');                                     //-- 2
    window.OBJ.push('left|left');                                   //-- 3
    window.OBJ.push('height|height');                               //-- 4
    window.OBJ.push('width|width')                                  //-- 5
    window.OBJ.push('backgroundColor|background-color');            //-- 6
    window.OBJ.push('borderColor|border-color');                    //-- 7
    window.OBJ.push('borderWidth|border-width')                     //-- 8
    window.OBJ.push('fieldName|html');                              //-- 9
    window.OBJ.push('minRows|');                                    //-- 10
    window.OBJ.push('maxRows|');                                    //-- 11
    window.OBJ.push('fontColor|color');                             //-- 12
    window.OBJ.push('fontFamily|font-family');                      //-- 13
    window.OBJ.push('fontSize|font-size');                          //-- 14
    window.OBJ.push('fontWeight|font-weight');                      //-- 15
    window.OBJ.push('format|');                                     //-- 16
    window.OBJ.push('textAlign|text-align');                        //-- 17
    window.OBJ.push('image|');                                      //-- 18
    window.OBJ.push('zIndex|z-index');                              //-- 19

    window.nufield      = [18];
    window.nulabel      = [10,11,18];
    window.nuimage      = [6,7,8,9,10,11,12,13,14,15];
    

    function nuGRPSection(sectionHeader){
    
        this.sectionID    = 'nuSectionIndex' + sectionHeader;
        this.sectionLabel = 'nuSectionSpan' + sectionHeader;

    }
    
    function nuGRP(sectionHeader, groupField, groupSort){
    
        var s             = {};
        this.sections     = [];
        this.groupField   = groupField;
        this.groupSort    = groupSort;
        
        s                 = new nuGRPSection(sectionHeader);
        this.sections.push(s);
        
        if(sectionHeader == 10){return;}                            //-- detail section

        s                 = new nuGRPSection(20 - sectionHeader);
        this.sections.push(s);

    }
    
    function nuSetGroupInfo(){                                     //-- a static array used when updating Group Details Dialog

        var g           = {};
        g               = new nuGRP(10, 'nu_group_detail', '');
        GRP.push(g);
        g               = new nuGRP(0, 'nu_group_report', '');
        GRP.push(g);
        g               = new nuGRP(1, 'nu_group_page', '');
        GRP.push(g);
        g               = new nuGRP(2, 'nu_group_1', 'nu_sort_1');
        GRP.push(g);
        g               = new nuGRP(3, 'nu_group_2', 'nu_sort_2');
        GRP.push(g);
        g               = new nuGRP(4, 'nu_group_3', 'nu_sort_3');
        GRP.push(g);
        g               = new nuGRP(5, 'nu_group_4', 'nu_sort_4');
        GRP.push(g);
        g               = new nuGRP(6, 'nu_group_5', 'nu_sort_5');
        GRP.push(g);
        g               = new nuGRP(7, 'nu_group_6', 'nu_sort_6');
        GRP.push(g);
        g               = new nuGRP(8, 'nu_group_7', 'nu_sort_7');
        GRP.push(g);
        g               = new nuGRP(9, 'nu_group_8', 'nu_sort_8');
        GRP.push(g);

    }

    function nuREPORT(){

        this.top          = 30;
        this.left         = 30;
        this.bottom       = 30;
        this.right        = 30;
        this.width        = 210;
        this.height       = 297;
        this.paper        = 'A4';
        this.orientation  = 'P';
        this.groups       = [];
        this.currentGroup = 0;
        
        var g;
        var h;
        
        for (i = 0 ; i < 11 ; i++){
        
            g            = new nuGROUP(i, i < 3 ? 40 : 0);  //-- set Detail, Page and Report to 40 height
            this.groups.push(g);
            
        }

    }

    function nuGROUP(group, height){

        var f   = '';
        var s;


        if(group == 0){
            f = 'Detail';
        }
            
        if(group == 1){
            f = 'Report';
        }
        if(group == 2){
            f = 'Page';
        }
        
        this.sortField     = f;
        this.sortBy        = 'a';
        this.sections      = [];
        s                  =  new nuSECTION(height, f + ' Header');
        this.sections.push(s);
        if(f != 'Detail'){
            s              =  new nuSECTION(height, f + ' Footer');
            this.sections.push(s);
        }

    }

    
    function nuSECTION(height, label){

        this.id         = '';
        this.top        = 0;
        this.height     = height;
        this.label      = label;
        this.page_break = 0;
        this.margins    = 0;
        this.color      = '#FFFFFF';

        this.objects = [];

    }

    function nuOBJECT(i,o,z){

        this.id                = i;
        
        if(arguments.length == 1 || arguments.length == 3){                               //-- create a new Object
                
            $('#nuSectionIndex10').css('visibility', 'visible');
            
            if(parseInt($('#nuSectionIndex10').css('height')) < 2){
                $('#nuSectionIndex10').css('height', 2);
            }

            this.group           = 0;                         //-- Group it belongs to.
            this.section         = 0;                         //-- Header or Footer.
            this.objectType      = 'field';
            this.left            = 70;
            this.top             = 1;                         //-- relative to the Section it belongs to.
            this.height          = 20;
            this.width           = 100;
            this.backgroundColor = 'white';
            this.borderColor     = 'black';
            this.borderWidth     = 0;
            this.fieldName       = '';
            this.fontColor       = 'black';
            this.fontFamily      = 'Arial';
            this.fontSize        = 14;
            this.fontWeight      = '';
            this.format          = '';
            this.textAlign       = 'left';
            this.image           = '';
            this.zIndex          = z;
            this.minRows         = 0;
            this.maxRows         = 0;
            this.selected        = 0;
            this.name            = this.id;

        }else{

            this.section       = o;                              //-- JSON Object
         
        }

    }


    
    function disableSelect(el){			
        if(el.addEventListener){
            el.addEventListener("mousedown",disabler,"false");
        } else {
            el.attachEvent("onselectstart",disabler);
        }
    }
     
    function enableSelect(el){
        if(el.addEventListener){
        el.removeEventListener("mousedown",disabler,"false");
        } else {
            el.detachEvent("onselectstart",disabler);
        }
    }
     
    function disabler(e){
        if(e.preventDefault){ e.preventDefault(); }
        return false;
    }
    
    
$(document).ready(function() {

    nuSetGroupInfo();
    nuCreateSection(0,  1, 40, 'Report Header');
    nuCreateSection(1,  2, 40, 'Page Header');
    nuCreateSection(2,  3);
    nuCreateSection(3,  4);
    nuCreateSection(4,  5);
    nuCreateSection(5,  6);
    nuCreateSection(6,  7);
    nuCreateSection(7,  8);
    nuCreateSection(8,  9);
    nuCreateSection(9, 10);
    nuCreateSection(10, 0, 40, 'Detail');
    nuCreateSection(11,10);
    nuCreateSection(12, 9);
    nuCreateSection(13, 8);
    nuCreateSection(14, 7);
    nuCreateSection(15, 6);
    nuCreateSection(16, 5);
    nuCreateSection(17, 4);
    nuCreateSection(18, 3);
    nuCreateSection(19, 2, 40, 'Page Footer');
    nuCreateSection(20, 1, 40, 'Report Footer');
    nuMoveAllObjects();
    nuReopenSelectObjects();
    nuSaveReport();
    nuRecalcSectionTops();
    nuToolbar();
    disableSelect(document);    //-- stops trying to select stuff when dragging
    nuLoadReport();
    nuSaveReport();

});
    


function nuLoadReport(){

    if(window.opener){
        if(String(window.opener.document.getElementById('sre_layout').value) == ''){return;}              //-- nothing to load
    }else{
        return;
    }
    
    var report                                             = $.parseJSON(window.opener.sre_layout.value);
    window.REPORT                                          = new nuREPORT();
    window.REPORT.top                                      = report.top;
    window.REPORT.left                                     = report.left;
    window.REPORT.bottom                                   = report.bottom;
    window.REPORT.right                                    = report.right;
    window.REPORT.width                                    = report.width;
    window.REPORT.height                                   = report.height;
    window.REPORT.paper                                    = report.paper;
    window.REPORT.orientation                              = report.orientation;
    nuChangeWidth(REPORT.width)
    
    for(var g = 0 ; g < report.groups.length ; g++){
        
        window.REPORT.groups[g].sortField                  = report.groups[g].sortField;
        window.REPORT.groups[g].sortBy                     = report.groups[g].sortBy;

        for(var s = 0 ; s < report.groups[g].sections.length ; s++){
        
            window.REPORT.groups[g].sections[s].height     = report.groups[g].sections[s].height;
            window.REPORT.groups[g].sections[s].color      = report.groups[g].sections[s].color;
            window.REPORT.groups[g].sections[s].page_break = report.groups[g].sections[s].page_break;
            
            var sectionID                                  = GRP[g].sections[s].sectionID;
            var sectionSpan                                = GRP[g].sections[s].sectionLabel;
            
            
            $('#'+sectionID).css('height',report.groups[g].sections[s].height);
            $('#'+sectionID).css('background-color',nuWhiteGrey(report.groups[g].sections[s].color));
            $('#'+sectionID).css('visibility',report.groups[g].sections[s].height > 0 ? 'visible' : 'hidden');
            $('#'+sectionID).css('border-width',report.groups[g].sections[s].height > 0 ? '1px' : '0px');

            if(window.REPORT.groups[g].sortField == ''){
                $('#'+sectionSpan).html('');
            }else{
                $('#'+sectionSpan).html(window.REPORT.groups[g].sortField+' '+ (s == 0 ? 'Header' : 'Footer'));
            }

            for(var o = 0 ; o < report.groups[g].sections[s].objects.length ; o++){
                
                var ob             = new nuOBJECT(report.groups[g].sections[s].objects[o]);
                ob.id              = report.groups[g].sections[s].objects[o].id;
                ob.group           = g;
                ob.section         = s;
                
                nuCreateObject(ob);

                ob.objectType      = report.groups[g].sections[s].objects[o].objectType;
                ob.left            = report.groups[g].sections[s].objects[o].left;
                ob.top             = report.groups[g].sections[s].objects[o].top;
                ob.height          = report.groups[g].sections[s].objects[o].height;
                ob.width           = report.groups[g].sections[s].objects[o].width;
                ob.backgroundColor = report.groups[g].sections[s].objects[o].backgroundColor;
                ob.borderColor     = report.groups[g].sections[s].objects[o].borderColor;
                ob.borderWidth     = report.groups[g].sections[s].objects[o].borderWidth;
                ob.fieldName       = report.groups[g].sections[s].objects[o].fieldName;
                ob.fontColor       = report.groups[g].sections[s].objects[o].fontColor;
                ob.fontFamily      = report.groups[g].sections[s].objects[o].fontFamily;
                ob.fontSize        = report.groups[g].sections[s].objects[o].fontSize;
                ob.fontWeight      = report.groups[g].sections[s].objects[o].fontWeight;
                ob.format          = report.groups[g].sections[s].objects[o].format;
                ob.textAlign       = report.groups[g].sections[s].objects[o].textAlign;
                ob.image           = report.groups[g].sections[s].objects[o].image;
                ob.zIndex          = report.groups[g].sections[s].objects[o].zIndex;
                ob.minRows         = report.groups[g].sections[s].objects[o].minRows == undefined ? 0 : report.groups[g].sections[s].objects[o].minRows;
                ob.maxRows         = report.groups[g].sections[s].objects[o].maxRows == undefined ? 0 : report.groups[g].sections[s].objects[o].maxRows;
                ob.selected        = report.groups[g].sections[s].objects[o].selected;
                ob.name            = report.groups[g].sections[s].objects[o].name;

            }
            
        }

    }
    nuRecalcSectionTops();    
    
    for(var g = 0 ; g < REPORT.groups.length ; g++){
        
        for(var s = 0 ; s < REPORT.groups[g].sections.length ; s++){
        
            for(var o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o++){
            
                nuRedrawObjectFromProperties(REPORT.groups[g].sections[s].objects[o]);
                
            }
            
        }

    }
    
    nuRecalcSectionTops();    
}


function nuRedrawObjectFromProperties(ob){                        
    
    var g             = ob.group;
    var s             = ob.section;
    var offset        = REPORT.groups[g].sections[s].top + REPORT.groups[g].sections[s].margins;
    var newTop        = Number(ob.top) + Number(offset);

    $('#' + ob.id).css('top',  newTop);
    $('#' + ob.id).css('left', Number(ob.left) + 30);
    $('#' + ob.id).css('height', ob.height);
    $('#' + ob.id).css('width', ob.width);
    $('#' + ob.id).css('background-color', ob.backgroundColor);
    $('#' + ob.id).css('border-color', ob.borderColor);
    $('#' + ob.id).css('border-width', ob.borderWidth);
    $('#' + ob.id).css('border-style', 'solid');
    $('#' + ob.id).html(ob.fieldName);
    $('#' + ob.id).css('color', ob.fontColor);
    $('#' + ob.id).css('font-family', ob.fontFamily);
    $('#' + ob.id).css('font-size', ob.fontSize + 'px');
    
    $('#' + ob.id).css('text-align', ob.textAlign);
    $('#' + ob.id).css('z-index', ob.zIndex);
    
    $('#' + ob.id).css('font-weight', ob.fontWeight);
    
    switch(ob.fontWeight) {
        case "I":
            $('#' + ob.id).css('font-style', 'italic');
            break;
        case "B":
            $('#' + ob.id).css('font-weight', 'bold');
            break;
        default:
            $('#' + ob.id).css('font-style', 'normal');
            break;
    }
    
}

    
function nuBelowThis(index){

    var bottom = 0;
    for(var i = 0 ; i < $('.nuSection').length ; i++){
        var id = $('.nuSection')[i].id;
        if($('#'+id).data('index') < index){          //-- add up all heights under the current Section
            bottom = bottom + parseInt($('#'+id).css('height'));
        }
    }
    return bottom + 30;

}    
    
function nuChangeWidth(mm){

    var w = parseInt(Number(mm) * 4);
    $("[id^='nuSectionIndex']").css('width', w);
    $("[id^='nuSectionIndex']").resizable( "option", "minWidth", w);
    $("[id^='nuSectionIndex']").resizable( "option", "maxWidth", w);
    return w;
}

function nuMM2PX(mm){
    $("[id^='nuSectionIndex']").css('width', Number(mm) * 4);
}

function nuPX2MM(mm){
    $("[id^='nuSectionIndex']").css('width', Number(mm) * 4);
}



function nuCreateSection(index, group, h, title){

    if(arguments.length == 2){
        var h      = 0;
        var title  = '';
    }
    
    var e          = document.createElement('div');              //-- create section div
    e.setAttribute('id', 'nuSectionIndex' + index);
    e.setAttribute('onclick', 'nuUnSelect()');
    e.setAttribute('ondblclick', 'nuOpenGroupDialog(this)');
    $('body').append(e);
    $('#' + e.id).css( 'width',  REPORT.width);
    $('#' + e.id).css( 'height', h);
    
    if(h == 0){
        $('#' + e.id).css( 'border-width', 0);
        $('#' + e.id).css( 'visibility', 'hidden');
    }else{
        $('#' + e.id).css( 'border-width', 1);
        $('#' + e.id).css( 'visibility', 'visible');
    }
    
    $('#' + e.id).data( 'group', group);
    $('#' + e.id).css( 'top', 0);
    $('#' + e.id).css( 'left', 0);
    $('#' + e.id).css( 'position', 'relative');
    $('#' + e.id).css( 'background-color', '#DADADA');
    $('#' + e.id).css( 'border-color', '#888888');
    $('#' + e.id).css( 'border-style', 'solid');
    $('#' + e.id).css( 'border-radius', '2px');
    $('#' + e.id).css( 'font-family',  'helvetica');
    $('#' + e.id).addClass( 'nuSection');
    $('#' + e.id).resizable();
    $('#' + e.id).resizable({
    
        resize: function(){
        
            nuMoveAllObjects();

        },

        stop: function(){
        
            nuReadjustSections();                    //-- make Sections Bigger if an Object overlaps it
            nuMoveAllObjects();
            nuSaveReport(); 
            
        }
        
    });

	s = document.createElement('span');              //-- create section div
	s.setAttribute('id', 'nuSectionSpan'+index);
	$('#'+e.id).append(s);
	$('#'+s.id).html(title);
    
}


    function nuOpenGroupDialog(t){

        nuGroupDialog();
        var g = $('#' + t.id).data('group');
        $('#' + GRP[g].groupField).focus();
    
    }

    function nuMoveAllObjects(){

        nuRecalcSectionTops();
        for(var  g = 0 ; g < REPORT.groups.length ; g ++){

            for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){

                for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
                
                    var id            = REPORT.groups[g].sections[s].objects[o].id;
                    var sectionTop    = REPORT.groups[g].sections[s].top;
                    var objectTop     = REPORT.groups[g].sections[s].objects[o].top;
                    var margin        = REPORT.groups[g].sections[s].margins;

                    $('#'+id).css('top', parseInt(sectionTop) + parseInt(objectTop) + parseInt(margin));
                    
                }
            }
        }
        nuCheckSideMargins();
        
        nuChangeWidth(REPORT.width);
        nuReopenObjectProperties();                           //-- refresh Object Properties Dialog

    }


    
    function nuResetDimensions(section){

        var s  = nuGetSectionFamilyTree(section);
        var o  = REPORT.groups[s[0]].sections[s[1]].objects;
        var h  = REPORT.groups[s[0]].sections[s[1]].height;
        var t  = REPORT.groups[s[0]].sections[s[1]].top;
        var m  = REPORT.groups[s[0]].sections[s[1]].margins;
        var b  = 0;
        var nt = 0;

        for(var i = 0 ; i < o.length ; i++){
            b  = parseInt(o[i].borderWidth) * 2;
            h  = Math.max(h, parseInt(o[i].top) + parseInt(o[i].height) + b);             //-- make taller if needed
            nt =  parseInt(t) + parseInt(m) + parseInt(o[i].top);
            $('#'+ o[i].id).css('top', nt);                                               //-- new top
            
        }
        REPORT.groups[s[0]].sections[s[1]].height = h;

        $('#'+ section).css('height', parseInt(h));
        $('#'+ section).css('visibility',   parseInt(h) > 0 ? 'visible' : 'hidden');
        $('#'+ section).css('border-width', parseInt(h) > 0 ? '1px' : '0px');

    }


    
    function nuRecalcSectionTops(){

        var a                   = [];
        var top                 = 30;
        var margin              = 0;
        var height              = 0;
        
        for(var i = 0 ; i < 21 ; i++){
        
            var id              = 'nuSectionIndex' + i;
            var vis             = $('#' + id).css( 'visibility') 
            var height          = parseInt($('#' + id).css( 'height'));
            
            if(vis == 'visible'){                                     //-- add pixels for border
                margin          = margin + 2;
            }
            
            nuSetSectionValue(id, 'top', top);
            nuSetSectionValue(id, 'height', height);
            nuSetSectionValue(id, 'margins', margin);
            
            top                 = top + height;
        }

        REPORT.top              = 30;
        REPORT.bottom           = top + 30;;

        return a;
    }

    
    function nuObjectObject(i, t, b, l, r){
    
        this.id     = i;
        this.top    = t;
        this.bottom = b;
        this.left   = l;
        this.right  = r;
        
    }
    
    function nuSectionObject(i, t, h, m){
    
        this.id      = i;
        this.top     = t;
        this.height  = h;
        this.margins = m;   //--- cumulative margin surrounding Section
        
    }
    
    

    function nuMoveSections(below, moveBy){

        for(var i = 0 ; i < $('.nuReportObject').length ; i++){
            var id  = $('.nuReportObject')[i].id;
            var top = parseInt($('#'+id).css('top'));
            if(top > below){                          //-- add up all heights under the current Section
                $('#'+id).css('top', moveBy + $('#'+id).data('top'));
            }
        }
    }
    
function nuToolbar(){

    e = document.createElement('div');                //-- create draggable/resizable div
    e.setAttribute('id', 'nuTools');
    $('body').append(e);
    $('#' + e.id).css( 'width',  '1060px');
    $('#' + e.id).css( 'height', '20px');
    $('#' + e.id).css( 'top', '0px');
    $('#' + e.id).css( 'left', '5px');
    $('#' + e.id).css( 'position', 'absolute');
    $('#' + e.id).css( 'background-color', 'silver');
    $('#' + e.id).css( 'border-width', '1px');
    $('#' + e.id).css( 'border-style', 'solid');
    $('#' + e.id).css( 'border-radius', '2px');
    $('#' + e.id).css( 'overflow', 'hidden');
    $('#' + e.id).css( 'z-index', '1000');
    $('#' + e.id).addClass('nuShadeHolder');
    $('#' + e.id).draggable({
        stop: function( event, ui ) {
            var pos = $(this).position();
            if(pos.left < 0){
                $(this).css('left', '0px');
            }
            if(pos.top < 0){
                $(this).css('top', '0px');
            }
        }
    });	

    $('#' + e.id).css( 'font-family',  'helvetica');
    $('#' + e.id).css( 'font-size',  '14px');

    e = document.createElement('div');
    e.setAttribute('id', 'nuItem0');
    e.setAttribute('onclick', 'nuCreateObject()');
    $('#nuTools').append(e);
    $('#' + e.id).html('New Object');
    $('#' + e.id).addClass('nuToolbar');

    e = document.createElement('div');
    e.setAttribute('id', 'nuItem1');
    e.setAttribute('onclick', 'nuCloneObjects(false)');
    $('#nuTools').append(e);
    $('#' + e.id).html('Clone Object');
    $('#' + e.id).addClass('nuToolbar');

    e = document.createElement('div');
    e.setAttribute('id', 'nuItem7');
    e.setAttribute('onclick', 'nuSelectDialog()');
    $('#nuTools').append(e);
    $('#' + e.id).html('Select Objects');
    $('#' + e.id).addClass('nuToolbar');

    e = document.createElement('div');
    e.setAttribute('id', 'nuItem2');
    e.setAttribute('onclick', 'nuAdjustDialog()');
    $('#nuTools').append(e);
    $('#' + e.id).html('Adjust Objects');
    $('#' + e.id).addClass('nuToolbar');

    e = document.createElement('div');
    e.setAttribute('id', 'nuItem4');
    e.setAttribute('onclick', 'nuObjectDialog()');
    $('#nuTools').append(e);
    $('#' + e.id).html('Object Properties');
    $('#' + e.id).addClass('nuToolbar');

    e = document.createElement('div');
    e.setAttribute('id', 'nuItem3');
    e.setAttribute('onclick', 'nuGroupDialog()');
    $('#nuTools').append(e);
    $('#' + e.id).html('Group Properties');
    $('#' + e.id).addClass('nuToolbar');

    e = document.createElement('div');
    e.setAttribute('id', 'nuItem5');
    e.setAttribute('onclick', 'nuReportDialog()');
    $('#nuTools').append(e);
    $('#' + e.id).html('Report Properties');
    $('#' + e.id).addClass('nuToolbar');

    e = document.createElement('div');
    e.setAttribute('id', 'nuItem6');
    e.setAttribute('onclick', 'nuStringify()');
    $('#nuTools').append(e);
    $('#' + e.id).html('Copy Changes');
    $('#' + e.id).addClass('nuToolbar');
    

}    
    
    function nuSortSections(){
    
        for(var i = 1 ; i < 11 ; i++){
            nuBuildGroupFromREPORT(i);
        }

    }
    
    function nuWhiteGrey(c){    //-- makes white backgrounds grey
    
        var u = String(c).toUpperCase();
        
        if(u == '#FFFFFF' || u == '#FFF' || u == 'WHITE'){
            u = '#DADADA';
        }
        
        return u;
    }

    function nuBuildGroupFromREPORT(g){
    
        var GROUP = REPORT.groups[g];

        if(GROUP.sortField == ''){                                                  //-- No Field to Group On
            GROUP.sortBy   = 'a';
            for(var i = 0 ; i < GRP[g].sections.length ; i++){
                nuHideSection(GRP[g].sections[i].sectionID);
            }
        }else{

            for(var i = 0 ; i < GRP[g].sections.length ; i++){

                if(GROUP.sections[i].height == 0){
                    nuHideSection(GRP[g].sections[i].sectionID);
                }else{
                    nuShowSection(GRP[g].sections[i].sectionID)
                }
                $('#' + GRP[g].sections[i].sectionID).css('height',           parseInt(GROUP.sections[i].height));
                $('#' + GRP[g].sections[i].sectionID).css('background-color', nuWhiteGrey(GROUP.sections[i].color));
                $('#' + GRP[g].sections[i].sectionLabel).html(GROUP.sections[i].label);

            }
        }
    }




function nuDialogAdjustButton(title, id, t){

    var e = document.createElement('input');  
    e.setAttribute('id', 'nu_adjust_'+id);
    e.setAttribute('type', 'button');
    e.setAttribute('value', title);
    e.setAttribute('onclick', 'nuAdjustObjects(this)');
    $('#nuProperties').append(e);
    $('#' + e.id).css( 'position', 'absolute');
    $('#' + e.id).css( 'left',  '50px');
    $('#' + e.id).css( 'top',   t + 'px');
    $('#' + e.id).css( 'width', '300px');
    $('#' + e.id).css( 'height', '30px');
    $('#' + e.id).css( 'font-family',  'helvetica');
    $('#' + e.id).css( 'font-size',  '14px');

}
    


function nuDialogInput(title, id, t, l, data){

    if(title != ''){
        var e = document.createElement('span');  
        e.setAttribute('id', 'title_' + id);
        $('#nuProperties').append(e);
        $('#' + e.id).css( 'position', 'absolute');
        $('#' + e.id).css( 'left',  (l-210) + 'px');
        $('#' + e.id).css( 'top',   t + 'px');
        $('#' + e.id).css( 'width', '200px');
        $('#' + e.id).css( 'font-family',  'helvetica');
        $('#' + e.id).css( 'text-align', 'right');
        $('#' + e.id).html(title);
    }

    var e = document.createElement('input');  
    e.setAttribute('id', id);
    
    $('#nuProperties').append(e);
    $('#' + e.id).css( 'position', 'absolute');
    $('#' + e.id).css( 'left',  l + 'px');
    $('#' + e.id).css( 'top',   t + 'px');
    $('#' + e.id).css( 'width', '200px');
    $('#' + e.id).css( 'font-family',  'helvetica');
    $('#' + e.id).css( 'font-size',  '14px');
    
    if(arguments.length == 5){                             //-- Report Groups
        $('#' + e.id).data('group', data);
    }
    

}
    

function nuDialogSelect(title, id, t, l, list, d){

    if(title != ''){
        var e = document.createElement('span');  
        e.setAttribute('id', 'title_' + id);
        $('#nuProperties').append(e);
        $('#' + e.id).css( 'position', 'absolute');
        $('#' + e.id).css( 'left',  (l-200) + 'px');
        $('#' + e.id).css( 'top',   t + 'px');
        $('#' + e.id).css( 'width', '190px');
        $('#' + e.id).css( 'font-family',  'helvetica');
        $('#' + e.id).css( 'text-align', 'right');
        $('#' + e.id).html( title);
    }

    var e = document.createElement('select');  
    e.setAttribute('id', id);
    $('#nuProperties').append(e);
    $('#' + e.id).css( 'position', 'absolute');
    $('#' + e.id).css( 'left',  l + 'px');
    $('#' + e.id).css( 'top',   t + 'px');
    $('#' + e.id).css( 'width', '205px');
    $('#' + e.id).css( 'font-family',  'helvetica');
    $('#' + e.id).css( 'font-size',  '14px');

    var option      = document.createElement('option');
    if(d != false ){
        option.value    = '';
        option.appendChild(document.createTextNode(''));
        e.appendChild(option);
    } 
    for(var i = 0 ; i < list.length ; i++){
        
		var option = document.createElement('option');
		option.value = list[i].split('|')[0];
		option.appendChild(document.createTextNode(list[i].split('|')[1]));
                if (list[i].split('|')[2])
                    option.setAttribute('cssv', list[i].split('|')[2]);   //-- font family
		e.appendChild(option);

    }
    
    
}
    

    

function nuCreateDialog(w, h, title){

        if($('#nuDrag').length > 0){
            var pos  = $('#nuDrag').position();
            var left = pos.left;
            var top  = pos.top;
        }else{
            var pos = $('#nuTools').position();
            var left = pos.left + 300;
            var top  = pos.top + 30;
        }
        $('#nuDrag').remove();
        e = document.createElement('div');              //-- create draggable div
        e.setAttribute('id', 'nuDrag');
        e.setAttribute('onmouseover', 'enableSelect(document)');
        e.setAttribute('onmouseout', 'disableSelect(document)');

	$('body').append(e);
	$('#' + e.id).css( 'width',  w + 'px');
	$('#' + e.id).css( 'height', h + 'px');
	$('#' + e.id).css( 'top', top );
	$('#' + e.id).css( 'left', left);
	$('#' + e.id).css( 'position', 'absolute');
	$('#' + e.id).css( 'background-color', 'grey');
	$('#' + e.id).css( 'border-width', '2px');
	$('#' + e.id).css( 'border-style', 'solid');
	$('#' + e.id).css( 'border-radius', '5px');
	$('#' + e.id).css( 'z-index', '1000');
	$('#' + e.id).addClass('nuShadeHolder');
	$('#' + e.id).draggable({ handle: '#nuDragBar' });

	var parent = 'nuDrag';
	
	var e = document.createElement('div');              //-- create draggable div
	e.setAttribute('id', 'nuDragBar');
	$('#'+parent).append(e);
	$('#' + e.id).css( 'width',  w+'px');
	$('#' + e.id).css( 'height', '25px');
	$('#' + e.id).css( 'top', '0px');
	$('#' + e.id).css( 'left', '0px');
	$('#' + e.id).css( 'position', 'absolute');
	$('#' + e.id).css( 'background-color', 'grey');
	$('#' + e.id).css( 'border-radius', '5px');
        $('#' + e.id).css( 'font-family',  'helvetica');
        $('#' + e.id).css( 'font-size',  '16px');
        $('#' + e.id).css( 'font-weight',  'bold');
	$('#' + e.id).html('<span id="nuDragTitle" style="color:white">&nbsp;'+title+'</span>');

	e = document.createElement('div');              //-- create draggable div
	e.setAttribute('id', 'nuDragBarClose_');
	$('#nuDragBar').append(e);
	$('#' + e.id).css( 'width',  '23px');
	$('#' + e.id).css( 'height', '22px');
	$('#' + e.id).css( 'top', '2px');
	$('#' + e.id).css( 'left', (w-24)+'px');
	$('#' + e.id).css( 'position', 'absolute');
	$('#' + e.id).css( 'background-color', 'lightgrey');
	$('#' + e.id).css( 'border-radius', '1px');
	$('#' + e.id).css( 'font-size', '20px');
	$('#' + e.id).css( 'font-family', 'arial');
	$('#' + e.id).html('&nbsp;X');
        $('#' + e.id).css( 'cursor',  'context-menu');
	$('#' + e.id).mousedown(function() {
		nuCloseDialog();
	});

	e = document.createElement('div');              
	e.setAttribute('id', 'nuProperties');
	$('#'+parent).append(e);
	$('#nuProperties').css( 'width',  w+'px');
	$('#nuProperties').css( 'height', (h-27)+'px');
	$('#nuProperties').css( 'top', '26px');
	$('#nuProperties').css( 'left', '0px');
	$('#nuProperties').css( 'position', 'absolute');
	$('#nuProperties').css( 'background-color', '#EDEDED');
}







    function nuCloseDialog(){
    
        var title = $('#nuDragTitle').html().substr(6);    //-- exclude &nbsp;
        
        if(title == 'Group Properties'){
        }
        
        if(title == 'Report Properties'){
            if(!nuReportPropertiesOK()){return;}
        }
        
        $("[id^='property_']").each(function(n) {                       //-- move selected
            $('#property_'+n).trigger("change");
        });
        
        $('#nuDrag').remove();
        
    }

function nuReadjustSections(oid, ui){                         //-- make Section Bigger if an Object overlaps it

    nuRecalcSectionTops();
    for(var i   = 0 ; i < $('.nuSelected').length ; i ++){
    
        var object  = $('.nuSelected')[i].id;
        var t       = parseInt($('#' + object).css('top'));      //-- top relative to the whole HTML page
        var l       = parseInt($('#' + object).css('left'));
        
        nuSetObjectValue(object, 'left', l - 30);

        for(var  g = 0 ; g < REPORT.groups.length ; g ++){

            for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
            
                var section   = REPORT.groups[g].sections[s];
                var sectionID = GRP[g].sections[s].sectionID;

                if(nuIsInsideSection(section, t)){

                    nuSetObjectSection(object, sectionID);                    //-- set Object Section
                    nuSetObjectTop(object);                                    //-- set Object Section top
                    
                }
            }
        }

    }
        for(var  g = 0 ; g < REPORT.groups.length ; g ++){

            for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){

                var id = GRP[g].sections[s].sectionID;

                nuResetDimensions(id);
            }
        }
}



function nuIsInsideSection(section, objectTop){

    return section.top <= objectTop &&  parseInt(section.top) + parseInt(section.height) > objectTop;

}

function nuGetSectionValue(s,p){          //-- object, property

    var S  = nuGetSectionFamilyTree(s);  // -- returns [group, section]
    
    return REPORT.groups[S[0]].sections[S[1]][p];

}

function nuSetSectionValue(s,p,v){          //-- object, property, value

    var S  = nuGetSectionFamilyTree(s);  // -- returns [group, section]
    
    REPORT.groups[S[0]].sections[S[1]][p] = v;

}


function nuGetObjectValue(o,p){          //-- object, property

    var O  = nuGetObjectFamilyTree(o);   // -- returns [group, section, object]

    return REPORT.groups[O[0]].sections[O[1]].objects[O[2]][p];

}

function nuSetObjectValue(o,p,v){        //-- object, property, value

    var O  = nuGetObjectFamilyTree(o);   // -- returns [group, section, object]
    REPORT.groups[O[0]].sections[O[1]].objects[O[2]][p] = v;

}

function nuSetObjectTop(id){

    var O               = nuGetObjectFamilyTree(id);   // -- returns [group, section, object]

    var actualTop       = parseInt($('#' + id).css('top'));
    var relativeTop     = actualTop - REPORT.groups[O[0]].sections[O[1]].top - REPORT.groups[O[0]].sections[O[1]].margins;
    
    REPORT.groups[O[0]].sections[O[1]].objects[O[2]].top = relativeTop;

}

function nuSetObjectSection(o, s){

    var O          = nuGetObjectFamilyTree(o);   // -- returns [group, section, object]
    var S          = nuGetSectionFamilyTree(s);  // -- returns [group, section]

    if(O[0] != S[0] || O[1] != S[1]){     //-- new parent

        var foster = REPORT.groups[O[0]].sections[O[1]].objects.splice(O[2],1);                 //-- remove from current parent
        var adopt  = REPORT.groups[S[0]].sections[S[1]].objects.push(foster[0]);                //-- adopt by new parent (section)
    }
}

function nuSetOffset(id){

    var focus_pos    = $('#' + id).position();
    for(var i = 0 ; i < $('.nuSelected').length ; i ++){
        var id       = $('.nuSelected')[i].id;
        var this_pos = $('#' + id).position();
        $('#' + id).data('offset_left',this_pos.left - focus_pos.left);
        $('#' + id).data('offset_top' ,this_pos.top  - focus_pos.top);
    }

}
    
function nuMoveSelected(id){

    if(nuMoveUpAndDown()){
        if ( nuMovePagebreak() == false )
                $( '#'+ id ).draggable({ axis: '' });
            else
                 $( '#'+ id ).draggable({ axis: 'y' });
    }else{
        if ( nuMovePagebreak() == false ) {
            $( '#'+ id ).draggable({ axis: 'x'});
        }
    }
    
    var focus_pos    = $('#' + id).offset();

    for(var i = 0 ; i < $('.nuSelected').length ; i ++){
        window.nuHasMoved = true;
        var id       = $('.nuSelected')[i].id;
        var l        = focus_pos.left + $('#' + id).data('offset_left');
        var t        = focus_pos.top  + $('#' + id).data('offset_top');
        $('#' + id).offset({left: l, top: t})
    }

}
    
function nuMoveUpAndDown(){

    var section  = '';
    var group  = '';
    var O        = REPORT.objects;
    for(var i    = 0 ; i < $('.nuSelected').length ; i ++){
        var id   = $('.nuSelected')[i].id;

        var oFam = nuGetObjectFamilyTree(id);

        if(i > 0 && section != oFam[0] + '' + oFam[1]){   //-- Objects are in different Sections
        return false;
        }
        section  = oFam[0] + '' + oFam[1];
    }
    return true;

}

function nuMovePagebreak() {
    for(var i    = 0 ; i < $('.nuSelected').length ; i ++){
        var id   = $('.nuSelected')[i].id;
        var ot = nuGetObjectValue(id,'objectType');
        if (ot == 'pagebreak')
            return true;
    }
    return false;
}

function nuGetObjectFamilyTree(id){

    for(var  g = 0 ; g < REPORT.groups.length ; g ++){

        for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){

            for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
            
                if(REPORT.groups[g].sections[s].objects[o].id == id){
                
                    return [g, s, o];
                }
            }
        }
    }
    return [];
    
}

function nuGetSectionFamilyTree(id){

    for(var  g = 0 ; g < REPORT.groups.length ; g ++){
    
        for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){

            if(GRP[g].sections[s].sectionID == id){
                return [g, s];
            }
        }
    }
 
    return [];
    
}
function nuGetTotalMargin(section){

    var m = 0;

    for(var  g = 0 ; g < REPORT.groups.length ; g ++){
    
        for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
        
            if(REPORT.groups[g].sections[s].height > 0){

                m = m + 2;
                
                if(REPORT.groups[g].sections[s].id == section){
                
                    return m;
                }
            }
        }
    }
 
    return 0;
    
}


    
  function nuShowObjectProperties(t){

        var p = $('#'+t.id).position();
        nuObjectDialog();

        $('#nuDrag').css('left', p.left - 10);
        $('#nuDrag').css('top',  p.top  + 25);
        
    }


    function nuStopResize(t){
        if (window.nuResize){
            nuSaveReport();
        }
        
        window.nuResize      = false;
        
        $('#' + t.id).draggable('enable');

    }

    
    function nuMakeReadOnlyObject(){

        if($('#nu_edit_field').length > 0){
            var saveTo = $('#nu_edit_field').attr('data-editing');
            $('#'+saveTo).html($('#nu_edit_field').val());
            nuSetObjectValue(saveTo, 'fieldName', $('#nu_edit_field').val());
            $('#nu_edit_field').remove();
        }
        disableSelect(document);

    }


  function nuUnSelect(){
      
        if(window.nuSaveObject != 0){                                           //-- does need saving
            nuSetOnBlur(window.nuSaveObject);
        }
        
        $('.nuSelected').removeClass('nuSelected');
        for(var  g = 0 ; g < REPORT.groups.length ; g ++){                     //-- unselect all Objects
            for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
                for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
                    REPORT.groups[g].sections[s].objects[o].selected = 0;
                }
            }
        }

        nuReopenSelectObjects(true);
        nuReopenObjectProperties(true);

    }
  
    function nuHighlightObject(t, e){
        if(!e){e = window.event;}
        if(window.nuSaveObject != 0){                                           //-- does need saving
            nuSetOnBlur(window.nuSaveObject);
        }
        
        var has = $('#'+ t.id).hasClass('nuSelected');

        if(window.nuControlKey){                                               //-- add this object to selection
            if( e.type === "mousedown") {
                if(has){
                    $('#'+ t.id).removeClass('nuSelected');
                    nuSetObjectValue(t.id, 'selected', 0);

                }else{
                    $('#'+ t.id).addClass('nuSelected');
                    nuSetObjectValue(t.id, 'selected', 1);
                }
            }
        }else{                                                                 //-- make this the only selected Object        
            
            if(!has || (e.type === "mouseup" && !window.nuHasMoved) ){
                $('.nuSelected').removeClass('nuSelected');
            
                for(var  g = 0 ; g < REPORT.groups.length ; g ++){                 //-- unselect all other Objects
                    for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
                        for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
                            var currentObject = REPORT.groups[g].sections[s].objects[o]; 
                            nuSetObjectValue(currentObject.id, 'selected', 0); 
                        }
                    }
                }
                $('#'+ t.id).addClass('nuSelected');
                nuSetObjectValue(t.id, 'selected', 1);
            }
        }


        if( e.type === "mousedown" ) {
            if(window.nuShiftKey){

                window.nuMouseStartX = e.clientX;                             //-- set starting point for resizing
                window.nuMouseStartY = e.clientY;                             //-- set starting point for resizing
                window.nuResize      = true;

                $('#' + t.id).draggable( 'disable' );

                $('.nuSelected').each(function(n) {                       //-- set starting height and width (only if resizing)
                        $(this).data("height", $(this).css('height'));
                        $(this).data("width", $(this).css('width'));
                    });
          

            }else{
                if(!nuMoveUpAndDown() && nuMovePagebreak() ){
                    $( '#'+ t.id ).draggable({ disabled: true});
                } else
                     $( '#'+ t.id ).draggable({ disabled: false });
                    
            }
        }
        nuReopenObjectProperties();                           //-- refresh Object Properties Dialog
        nuReopenSelectObjects();
        window.nuHasMoved = false;

    }


	function nuMouseMove(e){
		
		if(!e){e=window.event;}
		nuResizeSelected(e);

    }


	function keyDown(e){
	
		if(!e){e=window.event;}
		
        if (e.keyCode == 37){		//-- left arrow key; move selected objects 1 pixel left
            nuResizeSelected(e, -1, 0)
        }
        if (e.keyCode == 39){		//-- right arrow key; move selected objects 1 pixel right
            nuResizeSelected(e, 1, 0)
        }
        if (e.keyCode == 38){		//-- up arrow key; move selected objects 1 pixel up
            nuResizeSelected(e, 0, -1)
        }
        if (e.keyCode == 40){	    //-- down arrow key; move selected objects 1 pixel down
            nuResizeSelected(e, 0, 1)
        }

    }

	
    
    
    
    
	function nuResizeSelected(e, pX, pY){
		
        if(!window.nuResize){return;}
		if(!e){e=window.event;}

		if(arguments.length > 1){  //-- keystrokes
			var sizeXBy               = pX;
			var sizeYBy               = pY;

        for(var i = 0 ; i < $('.nuSelected').length ; i++){
        
            var id  = $('.nuSelected')[i].id;
            
        }
             
            $('.nuSelected').each(function(n) {                       //-- resize selected
                    $(this).css("height", parseInt($(this).css('height')) + parseInt(sizeYBy));
                    $(this).css("width", parseInt($(this).css('width'))   + parseInt(sizeXBy));
                    nuSetObjectValue(this.id,'height', parseInt($(this).css('height')));
                    nuSetObjectValue(this.id,'width', parseInt($(this).css('width')));
                });

        }else{
			var sizeXBy               = e.clientX - window.nuMouseStartX;
			var sizeYBy               = e.clientY - window.nuMouseStartY;

            $('.nuSelected').each(function(n) {                       //-- resize selected
                    $(this).css("height", parseInt($(this).data('height')) + parseInt(sizeYBy));
                    $(this).css("width", parseInt($(this).data('width'))   + parseInt(sizeXBy));
                    nuSetObjectValue(this.id,'height', parseInt($(this).css('height')));
                    nuSetObjectValue(this.id,'width', parseInt($(this).css('width')));
                });
		}

        nuReadjustSections();                    //-- make Sections Bigger if an Object overlaps it
        nuMoveAllObjects();



	}
	
    
    
	function nuKeyMoveSelected(e){     //-- move Selected with arrow keys
		
		if(!e){e=window.event;}

        var x = 0;
        var y = 0;
        
        if (e.keyCode == 37){		//-- left arrow key; move selected objects 1 pixel left
            
            x = -1;
        }
        if (e.keyCode == 39){		//-- right arrow key; move selected objects 1 pixel right
            x = 1;
        }

        if(nuMoveUpAndDown()){

            if (e.keyCode == 38){		//-- up arrow key; move selected objects 1 pixel up
                y = -1;
            }
            if (e.keyCode == 40){	    //-- down arrow key; move selected objects 1 pixel down
                y = 1;
            }

        }
        
        if(x != 0 || y != 0){       //-- if arrow key used
        
            $('.nuSelected').each(function(n) {                       //-- move selected
                    $(this).css("top", parseInt($(this).css('top')) + parseInt(y));
                    $(this).css("left", parseInt($(this).css('left'))   + parseInt(x));
                    var topVal = nuGetObjectValue(this.id, 'top') + parseInt(y);
                    var leftVal = nuGetObjectValue(this.id, 'left') + parseInt(x);
                    nuSetObjectValue(this.id, 'top', parseInt(topVal));
                    nuSetObjectValue(this.id, 'left', parseInt(leftVal));
                });
                
        }
        
    }
	

    function nuReportKeyPressed(e, isPressed){

        if(!e){e = window.event;}

        if(e.keyCode == 16){                    //-- shift key
            window.nuShiftKey     = isPressed;
        }
        if(e.keyCode == 17){                    //-- control key
            window.nuControlKey   = isPressed;
            $('.nuSelected').css( 'cursor',  'move');
        }
        
        if(isPressed){
        
            if(window.nuShiftKey){          //-- resize
                window.nuResize = true;
                if (e.keyCode == 37){		//-- left arrow key; move selected objects 1 pixel left
                    nuResizeSelected(e, -1, 0)
                }
                if (e.keyCode == 39){		//-- right arrow key; move selected objects 1 pixel right
                    nuResizeSelected(e, 1, 0)
                }
                if (e.keyCode == 38){		//-- up arrow key; move selected objects 1 pixel up
                    nuResizeSelected(e, 0, -1)
                }
                if (e.keyCode == 40){	    //-- down arrow key; move selected objects 1 pixel down
                    nuResizeSelected(e, 0, 1)
                }
                
            } else if (window.nuControlKey) {
                if (e.keyCode == 86){       // ctrl + v; clone copied objects
                    
                    if($('#nuDragTitle').length == 0){
                        nuCloneObjects(true);
                    }
                    
                }
                if (e.keyCode == 67) {      //ctrl + c; copy selected object
                    nuCopyObjects();
                }
                if (e.keyCode == 90) {      //ctrl + z; undo last action
                    nuUndoReport();
                }

            }else{                          //-- move

                nuKeyMoveSelected(e);
            }
        }
        else {
            if( e.keyCode == 37 || e.keyCode == 38 || e.keyCode == 39 || e.keyCode == 40 ) {
                nuReadjustSections();                    //-- make Sections Bigger if an Object overlaps it
                nuMoveAllObjects();
                nuReopenSelectObjects();
                nuSaveReport();
            }
        }

        if (e.keyCode == 46) {              //delete selected object.
            if($('#nuProperties').length == 0){
                nuDeleteObjects();
            }
        }
    }

    function nuDeleteObjects(){

        for(var  g = 0 ; g < REPORT.groups.length ; g ++){
            for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
                for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
                    if (REPORT.groups[g].sections[s].objects[o].selected == 1){
                        $("#" + REPORT.groups[g].sections[s].objects[o].id).remove();
                        REPORT.groups[g].sections[s].objects.splice(o--, 1);
                    }
                }
            }
        }
        nuSaveReport();
        nuReopenSelectObjects();

    }

    function nuSaveReport(){

        REPORTS.push(new nuCopyReport(REPORT));
        
    }

    function nuUndoReport(){
            
        if (REPORTS.length>1) {
             REPORTS.pop();
             REPORT = new nuCopyReport(REPORTS[REPORTS.length - 1]);
            nuSortSections();
            nuRefreshReport();
            nuReadjustSections();
            nuMoveAllObjects();
            nuReopenSelectObjects(true);
            nuReopenGroupProperties(true);
            nuReopenObjectProperties(true);
            
        }

    }

    function nuMouseUp(obj, e) {
        nuStopResize(obj);
        nuHighlightObject(obj,e);
    }
    
    function nuRefreshReport() {

        $("div[id^='object']").remove();
        for(var  g = 0 ; g < REPORT.groups.length ; g ++){    //-- unselect all Objects
            for(var  s = 0 ; s < REPORT.groups[g].sections.length ; s ++){
                for(var  o = 0 ; o < REPORT.groups[g].sections[s].objects.length ; o ++){
                    st  = parseInt(REPORT.groups[g].sections[s].top);
                    m   = parseInt(REPORT.groups[g].sections[s].margins);
                    var d = document.createElement('div');
                    ob = REPORT.groups[g].sections[s].objects[o];
                    d.setAttribute('id', ob.id);
                    d.setAttribute('ondblclick',  'nuShowObjectProperties(this)');
                    d.setAttribute('onmousedown', 'nuHighlightObject(this, event)');
                    d.setAttribute('onmouseup',   'nuMouseUp(this, event)');
                    d.setAttribute('onmousemove', 'nuMouseMove(event)');

                    $('#nuSectionIndex20').after(d);
                    $('#' + d.id).css( 'cursor',  'cell');
                    $('#' + d.id).css( 'overflow',  'hidden');
                    $('#' + d.id).addClass( 'nuReportObject');
                    $('#' + d.id).css( 'position', 'absolute');

                    $('#' + d.id).css( 'z-index', ob.zIndex);
                    $('#' + d.id).css( 'height', ob.height);
                    $('#' + d.id).css( 'left',  ob.left + 30);
                    $('#' + d.id).css( 'top',   ob.top + st + m);
                    $('#' + d.id).css( 'width', ob.width);
                    if (ob.objectType == 'pagebreak')
                        $('#' + d.id).css( 'border-style',  'dotted');
                    else
                        $('#' + d.id).css( 'border-style',  'solid');
                    $('#' + d.id).css( 'border-width',  ob.borderWidth);
                    $('#' + d.id).css( 'font-family',  ob.fontFamily);
                    $('#' + d.id).css( 'font-size',  ob.fontSize + 'px');
                    $('#' + d.id).css( 'color',  ob.fontColor);
                    $('#' + d.id).css( 'background-color',  ob.backgroundColor);
                    $('#' + d.id).css( 'text-align',  ob.textAlign);
                    $('#' + d.id).html( ob.fieldName);

                    switch(ob.fontWeight) {
                        case "I":
                            $('#' + ob.id).css('font-style', 'italic');
                            break;
                        case "B":
                            $('#' + ob.id).css('font-weight', 'bold');
                            break;
                        default:
                            $('#' + ob.id).css('font-style', 'normal');
                            break;
                    }
                    
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
                    
                    if(ob.selected == 1){
                        $('#' + d.id).addClass("nuSelected");
                    }
                }
            }
        }
    }

    function nuCopyReport(s){

        this.top          = s.top;
        this.left         = s.left;
        this.bottom       = s.bottom;
        this.right        = s.right;
        this.width        = s.width;
        this.height       = s.height;
        this.paper        = s.paper;
        this.orientation  = s.orientation;
        this.groups       = [];
        this.currentGroup = s.currentGroup;


        for (si = 0 ; si < 11 ; si++ ){

            this.groups.push(new nuCopyGroup(s.groups[si]));

        }
    }

    function nuCopyGroup(sg){

        this.sortField     = sg.sortField;
        this.sortBy        = sg.sortBy;
        this.sections = [];
        this.sections.push(new nuCopySection(sg.sections[0]));
        if (sg.sections[1])
        this.sections.push(new nuCopySection(sg.sections[1]));

    }

    function nuCopySection(ss) {

        this.id          = ss.id;
        this.top         = ss.top;
        this.height      = ss.height;
        this.label       = ss.label;
        this.page_break  = ss.page_break;
        this.margins     = ss.margins;
        this.color       = ss.color;

        this.objects = [];
        for (i = 0; i < ss.objects.length; i++ ) {
            this.objects.push(new nuCopyObject(ss.objects[i]));
        }

        
    }
    
    function nuCopyObject(s) {

        this.id                = s.id;

        this.group           = s.group;                         //-- Group it belongs to.
        this.section         = s.section;                         //-- Header or Footer.
        this.objectType      = s.objectType;
        this.left            = s.left;
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
        this.image           = s.image;
        this.zIndex          = s.zIndex;
        this.minRows         = s.minRows;
        this.maxRows         = s.maxRows;
        this.selected        = s.selected;
        this.toselect        = s.toselect;
        this.name            = s.name;
    }

  </script>
</head>
<body style='margin:30px' onkeydown="nuReportKeyPressed(event, true)" onkeyup="nuReportKeyPressed(event, false)">
 
</body>
</html>
