
function nuBuildBrowseForm(o){
	nuFORM.menu = o.menu;
	nuFORM.nu_user_id = o.nu_user_id;
    //$('body').html('');
    $('#nuHolder').remove();
    
    window.nuSession.removeSubforms();

    var formObjects      = o.objects;
    var formRecords      = Array();
    formRecords[0]       = o.records;
    window.nu_user_name  = o.nu_user_name;
    window.nuAllowSearch = 1;
    nuFORM.sum           = o.sum;
    window.nuStopPaging  = false;
    nuCloseModal();

    nuBuildHolder('nuHolder', 'nuHolder', 'body');
    nuBuildHolder('nuActionButtonHolder', 'nuActionButtonHolder', 'nuHolder');
    nuBuildHolder('nuBreadCrumbHolder', 'nuBreadCrumbHolder', 'nuHolder');
    nuBuildHolder('nuShadeHolder', 'nuShadeHolder', 'nuHolder');
    nuBuildHolder('nuBrowseTabAreaHolder', 'nuBrowseTabAreaHolder',  'nuShadeHolder');
    nuBuildHolder('nuTabTitleHolder', 'nuTabTitleHolder nuUnselectedTab nuGradient', 'nuShadeHolder');
        
    var width_height    = nuBuildBrowsePage(o);
    var w               = width_height.split('_')[0];
    var h               = width_height.split('_')[1];
        
    $('.nuActionButtonHolder').css('width',  width_height.split('_')[0] + 'px');
    $('.nuHolder').css({
        'width'  : w + 'px',
        'height' : h + 'px'
    });
    nuHighlightSearch(o);
    if(nuFORM.edit_browse == 'true'){                            //-- adjust dimemsions of nuHolder inside iFrame
        $('.nuHolder').css({
            'position' : 'absolute',
            'margin'   : '0px'
        });
    }else{
        nuAddBreadCrumbs();
    }
    
    nuAddJavascript(o);
    
    if(nuFORM.edit_browse != 'true'){                            //-- set focus if not inside an Edit Form
        $('#nuSearchField').focus();
    }
        
    if(window.nuLoadBrowseGlobal){                               //-- allows the user the ability to run javascript on every Browse Screen
        nuLoadBrowseGlobal();
    }
    if(window.nuLoadBrowse){                                     //-- allows the user the ability to run javascript on this Browse Screen
        nuLoadBrowse();
    }
    
    nuIframeWindowSizer();
}

function nuBrowseColumnTotal(c){

    if(nuFORM.sum.length == 0){
        console.log("nuGetBrowseSum(); must be run in 'Before Browse'");
        return 0;
    }
    if(c > nuFORM.sum.length){
        console.log("Invalid column number");
        return 0;
    }
    
    return nuFORM.sum[c];
}


function nuHighlightSearch(o){

    var filterStrings     = [];
    var columns           = o.searchColumns.split(',');
    columns.unshift(0);                                                               //-- initial build form won't have column 0 reference in it
    nuFORM.search_columns = o.searchColumns;
    $.each(o.filterStrings, function(i, el){                                          //-- remove duplicates
        if($.inArray(el, filterStrings) === -1) filterStrings.push(el);
    });
    
    var uniqueColumns = [];                                                            //-- 10:05 24/01/2014 BugFix - Prevents columns from being handled twice leading to broken HTML code
    for(var COL = 0 ; COL < columns.length ; COL++){
        if(uniqueColumns.indexOf(columns[COL]) == -1 && columns[COL] != "") {
            uniqueColumns.push(columns[COL]);
        }
    }
    
    filterStrings.sort(function(a,b) {return (a.length > b.length) ? 1 : 0; });   //-- search small strings first
    
    for(var COL = 0 ; COL < uniqueColumns.length ; COL++){
        $("[id$='col_" + uniqueColumns[COL] + "']").each(function(index) {
            var h = $(this).html();
            h = String(h).replaceAll('&nbsp;','', true);
            for(var STR = 0 ; STR < filterStrings.length ; STR++){
                h = String(h).replaceAll(filterStrings[STR],'`````'+filterStrings[STR]+'````', true);
            }
            h = String(h).replaceAll('`````', '<span class="nuBrowseSearch">', true);
            h = String(h).replaceAll('````', '</span>', true);

            $(this).html(h);
        });
    }
    for(var COL = 0 ; COL < nuBrowseColumns() ; COL++){
            $("[id$='col_" + COL + "']").each(function(index) {
                var h = $(this).html();
                h = String(h).replaceAll('&nbsp;','', true);
                $(this).html('&nbsp;&nbsp;'+h+'&nbsp;&nbsp;');
            });

    }
}

function nuIsSearchable(columns, thisColumn){
    
    for(var i = 0 ; i < columns.length ; i++){
        
        if(Number(columns[i]) == Number(thisColumn)){
            return true;
        }
        
    }
    return false;
    
}

function nuBuildBrowsePage(o){

    $('#nuActionButtonHolder').css('text-align','left');

    var e = document.createElement('input');              //-- create search field
    e.setAttribute('id', 'nuSearchField');
    e.setAttribute('value', o.search);
    $('#nuActionButtonHolder').append(e);
    $('#' + e.id).css('width', '200px')
    .addClass('nuSearch');
    e.setAttribute('onkeypress', 'nuSearchPressed(event)');
    
    var e = document.createElement('span');               //-- create space
    e.setAttribute('id', 'nuSpace');
    $('#nuActionButtonHolder').append(e);
    $('#' + e.id).html('&nbsp;');
    
    var e = document.createElement('input');              //-- create search button
    e.setAttribute('id', 'nuSearchButton');
    e.setAttribute('type', 'button');
    e.setAttribute('value', nuTranslate('Search'));
    e.setAttribute('onclick', 'nuApplySearch()');
    $('#nuActionButtonHolder').append(e);
    $('#' + e.id).addClass('nuButton');
    $('#' + e.id).addClass('nuActionButton');

    addButtons(o.buttons);  
    addBrowseTitles(o);
    return addBrowseRows(o);

}

function addBrowseTitles(o){

    var l                 = 0;
        var columns           = o.searchColumns.split(',');


    for(var i = 1 ; i < o.objects.length ; i++){
    
        var w   = o.objects[i].width;
        var t   = o.objects[i].title;
        var cID = i-1;
        
        var e = document.createElement('div');               //-- column title
        e.setAttribute('id', 'title_'+cID);
        $('#nuTabTitleHolder').append(e);
        $('#' + e.id).css({ 
            'top'          : '0px',
            'left'         :  l+'px',
            'position'     : 'absolute',
            'width'        : w+'px',
            'height'       : '60px',
            'text-align'   : 'center',
            'border-style' : 'solid',
            'border-color' : 'grey'
         });
                
        if(i == 1){
            $('#' + e.id).addClass('nuTabTitleHolderL nuUnselectedTab nuGradient');
        }else if(i+1 == o.objects.length){
            $('#' + e.id).addClass('nuTabTitleHolderR nuUnselectedTab nuGradient');
        }else{
            $('#' + e.id).addClass('nuTabTitleHolderC nuUnselectedTab nuGradient');
        }

        l = Number(l) + Number(w);
        
        if(nuFORM.sort == i + 1){
            var e = document.createElement('span');                  //-- Search Toggle
            e.setAttribute('id', 'arr_'+i);
            $('#title_'+cID).append(e);
            if(nuFORM.descending == 'desc'){
                $('#' + e.id).html('&#x25B2;');
            }else{
                $('#' + e.id).html('&#x25BC;');
            }
            $('#' + e.id).css({
                'color'  : '#96ABB8',
                'height' : '60px'
            });
        }
        
        if(t != ''){                                                 //-- has a title
        
            var e = document.createElement('input');                 //-- Search Toggle
            e.setAttribute('id', 'nusearch_'+cID);
            e.setAttribute('type', 'checkbox');
                    
            $('#title_'+cID).append(e);

            if(nuIsSearchable(columns, cID)){
                $('#' + e.id).attr('checked','checked');
            }
                    
            $('#' + e.id).addClass('nuSearchColumn')
            .css( 'margin' , '3px 0px 0px 0px')
            .dblclick(
                    function (){
                        nuCheckAllBoxes(this);
                    }
            );
            
            var e = document.createElement('br');
            $('#title_'+cID).append(e);
            var e = document.createElement('span');                  //-- Search Toggle
            e.setAttribute('id', 'nusort_'+i);
            e.setAttribute('onclick', 'nuSortBrowse('+(i+1)+')');
            $('#title_'+cID).append(e);
            $('#' + e.id).html(' '+t+' ')
            .hover(
                    function (){
                            $('#' + this.id).css('color', 'red');
                    }, 
                    function (){
                            $('#' + this.id).css('color', '');
                    }
            );
            
        }
        
        
        
    }
    $('#nuHolder').css('width', (20+Number(l))+'px');

    if (window.top != window.self) {
         $('#nuHolder').css('margin', 10+'px');
    }

    $('#nuShadeHolder').css('width', l+'px');
    $('#nuTabTitleHolder').css({
        'width'    : l+'px',
        'height'   : '60px',
        'overflow' : 'hidden'
    });

}

function nuCheckAllBoxes(pthis){
    
    var cols = '';
    
    for(i = 1 ; i < 1000 ; i++){

        if($('#nusearch_' + i).length == 0){
            break;
        }else{
            if($('#' + pthis.id).is(':checked')){
                $('#nusearch_' + i).prop("checked", true);            
            }else{
                $('#nusearch_' + i).prop("checked", false);
            }
        }
        
    }
    
    return cols;
    
}

function nuSortBrowse(sort){

    if(sort != nuFORM.sort){
        nuFORM.descending     = 'asc';
        nuFORM.sort           = sort;
    }else{
        if(nuFORM.descending  == 'asc'){
            nuFORM.descending = 'desc';
        }else{
            nuFORM.descending = 'asc';
        }
    }

    nuFORM.page_number = '1';
    window.nuSession.breadCrumb.pop();  //-- remove breadcrumb before another is added
    nuBuildForm(nuFORM);


}

function nuApplySearch(){

    if(window.nuAllowSearch == 0){return;}
    
    window.nuAllowSearch = 0;
    nuFORM.search      = $('#nuSearchField').val();
    nuFORM.page_number = '1';
    window.nuSession.breadCrumb.pop();  //-- remove breadcrumb before another is added
    nuBuildForm(nuFORM);

}

function nuGotoPage(p, max){

    if(window.nuStopPaging){return;}
    
    if(isNaN(p)){
        nuFORM.page_number = 1;
    }else if(p > max){
        nuFORM.page_number = max;
    } if(p < 1){
        nuFORM.page_number = 1;
    }else{
        nuFORM.page_number = p;
    }

    window.nuStopPaging = true;
    window.nuSession.breadCrumb.pop();  //-- remove breadcrumb before another is added
    
    nuBuildForm(nuFORM);

}

function addBrowseRows(o){

    var top = 0;
    var p;
    var totalRows = o.display.rows;
    var rowHeight = o.display.row_height;

        for(var r = 0 ; r < o.records.length ; r++){

        var l  = 0;
        var rw = ("000" + String(r)).slice (-4);


        var e = document.createElement('div');                 //-- row
        e.setAttribute('id', 'row_'+rw);
        $('#nuBrowseTabAreaHolder').append(e);
        $('#' + e.id).css({
            'top'      : top+'px',
            'left'     : l+'px',
            'position' : 'absolute',
            'height'   : rowHeight+'px',
            'width'    : '0px'
        })
        .addClass('nuSelectedTab');
        p = e.id;

        for(var i   = 1 ; i < o.objects.length ; i++){
        
            var w   = o.objects[i].width;
            var v   = o.records[r][i];
            var a   = o.objects[i].align;
            var e   = document.createElement('div');              //-- row column
            var cID  = i - 1;                                      //-- column number
            e.setAttribute('id', 'row_'+rw+'_col_'+cID);
            $('#'+p).append(e);
            $('#' + e.id).css({
                'top'        : '0px',
                'left'       : l+'px',
                'position'   : 'absolute',
                'height'     : rowHeight+'px',
                'width'      : w+'px',
                'text-align' : nuFormatAlign(a),
                'overflow'   : 'hidden'
            })
            .html(v)
            .addClass('nuSelectedTab nuBrowseCell')
            .hover(
                function (){
                    nuBrowseHighlight(this,'in');
                }, 
                function (){
                    nuBrowseHighlight(this,'out');
                }
            );
            if(o.call_type == 'getlookupform'){  //-- lookup
                $('#' + e.id).click(
                    function (){
                        nuLookupClick(this);
                    }
                );
            }else{                               //-- browse
                $('#' + e.id).click(
                    function (){
                        if(o.edit_browse == 'true'){
                            window.nuControlKey = true;                       //-- faked keypress
                            window.nuLastCtrlPressedTS = Math.floor(Date.now()/1000);
                            nuBrowseClick(this, o.open_form_id);
                            window.nuControlKey = false;
                        }else{
                            nuBrowseClick(this, o.open_form_id);
                        }
                    }
                );
            }
            l = Number(l) + Number(w);
            
        }
        top = top+Number(rowHeight);
        $('#'+p).css({
            'width'    : l+'px',
            'overflow' : 'hidden'
        })
        .attr( 'data-primary-key',    o.records[r][0])
        .attr( 'data-open-form',      o.openform[r])
        .attr( 'data-parent-form',    o.parentform)
        .attr( 'data-parent-record',  o.parentrecord[r]);
                
    }
    
    var firstRow = true;
    for(var r = r ; r < totalRows ; r++){                                             //--repeat for blank lines

        var l = 0;
        var rw = ("000" + String(r)).slice (-4);

        var e = document.createElement('div');                 //-- row
        e.setAttribute('id', 'row_'+rw);
        $('#nuBrowseTabAreaHolder').append(e);
        $('#' + e.id).css({
            'top'      : top+'px',
            'left'     : l+'px',
            'position' : 'absolute',
            'height'   : rowHeight+'px',
            'width'    : '0px'
        });
        if(firstRow){
            $('#' + e.id).css( 'border-style', 'solid none none none');
        }else{
            $('#' + e.id).css( 'border-style', 'none');
        }
        $('#' + e.id).addClass('nuSelectedTab nuBrowseCell');
        p = e.id;

        for(var i = 1 ; i < o.objects.length ; i++){
        
            var w = o.objects[i].width;
            var a = o.objects[i].align;
            var e = document.createElement('div');              //-- row column
            e.setAttribute('id', 'row_'+rw+'_col_'+cID);
            $('#'+p).append(e);
            $('#' + e.id).css({
                'top'          : '0px',
                'left'         : l+'px',
                'position'     : 'absolute',
                'height'       : rowHeight+'px',
                'width'        : w+'px',
                'text-align'   : nuFormatAlign(a),
                'border-style' : 'none' 
            })
            .addClass('nuSelectedTab');
            l = Number(l) + Number(w);
            
        }
        top = top+Number(rowHeight);
        $('#nuHolder').css('width', l+'px');
        $('#'+p).css({
            'overflow' : 'hidden',
            'width'    : l+'px'
        });
        firstRow = false;
    }
    
    var e = document.createElement('div');              //-- status
    e.setAttribute('id', 'nuStatusHolder');
    $('#nuShadeHolder').css('height', (82+top)+'px');
    $('#nuBrowseTabAreaHolder').append(e);
    $('#nuStatusHolder').css({
        'width'     : l+'px',
        'top'       : top+'px',
        'left'      : '0px',
        'position'  : 'absolute',
        'font-size' : '12px',
        'padding'   : '4px 0px 0px 0px'
    })
    .addClass('nuStatusHolder nuUnselectedTab nuGradient');
    if (window.top === window.self) {
        if(nuGetID() == ''){
            $('#nuStatusHolder').html('&nbsp;<a style="text-decoration:none;padding:3px 0px 0px 0px" href="'+nuGetHome()+'">'+nuTranslate('Logout')+'</a>');
            window.onbeforeunload = nuHomeWarning;
        }else{
            window.onbeforeunload = null;
        }
    }
    $('#nuHolder').css('height', (20+Number(top))+'px');

    var pages = Math.ceil(o.rowCount /o.display.rows);
    nuPagingStatus('nuStatusHolder',2,l,nuFORM.page_number, pages);
    $('#paging_nuStatusHolder').addClass('nuStatusHolder nuUnselectedTab nuGradient');
    $('#paging_nuStatusHolder').css('border-style','none');
    return (10+Number(l)) + '_' + (200+(Number(rowHeight) * Number(o.display.rows)));  //-- width and height
        
}


function nuBrowseHighlight(pthis,direction){                                           //-- shaneg changed nuSearch to nuBrowseRowSelected
    
    if(direction == 'in'){
        $('div[id^="'+pthis.id.substr(0,8)+'"]').addClass('nuBrowseRowSelected');
    }else{
        $('div[id^="'+pthis.id.substr(0,8)+'"]').removeClass('nuBrowseRowSelected');
    }
    

}

function nuLookupClick(pthis){                                                         //-- calls the change() event on the parent Form's lookup object

    var i         = nuGetID();
    var pSession  = nuGetParentSession();
    var w         = document.defaultView.parent.nuSession.getWindowInfo(i,pSession);
    var pk        = $('#'+pthis.id.substr(0,8)).attr('data-primary-key');
    var code      = $('#'+pthis.id).attr('data-prefix');
    
    if(w.lookup.substr(w.prefix.length,4) == 'btn_'){
        document.defaultView.parent.$('#'+w.prefix+w.lookup.substr(w.prefix.length+4)).val(pk);
        document.defaultView.parent.$('#'+w.prefix+w.lookup.substr(w.prefix.length+4)).change();
        document.defaultView.parent.$('#'+w.prefix+'code'+w.lookup.substr(w.prefix.length+4)).focus();
    } else {
        document.defaultView.parent.$('#'+w.lookup).val(pk);
        document.defaultView.parent.$('#'+w.lookup).change();
        document.defaultView.parent.$('#code'+w.lookup).focus();
    }
    
    document.defaultView.parent.$('#nuModal').remove(); 
    document.defaultView.parent.$('#nuDrag').remove();  

}


function nuBrowseClick(pthis,form){

    var pk        = $('#'+pthis.id.substr(0,8)).attr('data-primary-key');
    var form      = $('#'+pthis.id.substr(0,8)).attr('data-open-form');
    var parent    = $('#'+pthis.id.substr(0,8)).attr('data-parent-form');
    var parentpk  = $('#'+pthis.id.substr(0,8)).attr('data-parent-record');

    var t         = $('title').html();
    
    nuOpenForm(parent, parentpk, form, pk, nuFORM.title);

}


function nuPagingStatus(parent,top,left,page,total){

        left  = (left / 2) - 80;
    var p = 0;
    
    var e = document.createElement('div');              //-- create a new paging object
    e.setAttribute('id', 'paging_'+parent);
    $('#' + parent).append(e);
    $('#' + e.id).css({
        'width'      : '190px',
        'height'     : '22px',
        'text-align' : 'right',
        'top'        : '0px',
        'left'       : left+'px'
    })
    .addClass('nuUnselectedTab nuGradient');

    var lt = document.createElement('div');              //-- create a less than object
    lt.setAttribute('id', 'paging_previous_'+parent);
    $('#' + e.id).append(lt);
    $('#' + lt.id).css({
        'top'         : '0px',
        'padding'     : '3px 0px 0px 0px',
        'left'        : '15px',
        'position'    : 'absolute',
        'font-weight' : 'bold',
        'font-size'   : '12px'
     })
    .html('&#9668;');
    if(page != 1){
        p = page - 1;
        $('#' + lt.id).css('cursor', 'pointer');
        lt.setAttribute('onclick', 'nuGotoPage(' + p + ', ' + total + ')');
    }   
    $('#' + lt.id).addClass('nuUnselectedTab nuGradient');

    var ltt = document.createElement('div');              //-- create a less than text object
    ltt.setAttribute('id', 'paging_previous_text_'+parent);
    $('#' + e.id).append(ltt);
    $('#' + ltt.id).css({
        'top'       : '0px',
        'padding'   : '3px 0px 0px 0px',
        'left'      : '30px',
        'position'  : 'absolute',
        'font-size' : '12px'
    })
    .html(nuTranslate('Page'))
    .addClass('nuUnselectedTab nuGradient');

    var ip = document.createElement('input');              //-- create a current page object
    ip.setAttribute('id', 'paging_current_'+parent);
    $('#' + e.id).append(ip);
    $('#' + ip.id).css({
        'height'           : '12px',
        'top'              : '3px',
        'left'             : '70px',
        'position'         : 'absolute',
        'width'            : '30px',
        'font-size'        : '12px',
        'text-align'       : 'center',
        'background-color' : 'white'
    })
    .val(page);
    ip.setAttribute('onchange', 'nuGotoPage(this.value, ' + total + ')');

    var gtt = document.createElement('div');              //-- create a greater than text object
    gtt.setAttribute('id', 'paging_next_text'+parent);
    $('#' + e.id).append(gtt);
    $('#' + gtt.id).css({
        'top'       : '0px',
        'padding'   : '3px 0px 0px 0px',
        'left'      : '110px',
        'position'  : 'absolute',
        'font-size' : '12px'
    })
    .html('/&nbsp;'+ Math.max(total,1))
    .addClass('nuUnselectedTab nuGradient');
        
    var swidth = 10 + (String(total).length * 10);
    var gt = document.createElement('span');              //-- create a greater than object
    gt.setAttribute('id', 'paging_next_'+parent);
    $('#' + gtt.id).append(gt);
    $('#' + gt.id).css({ 
        'top'         : '0px',
        'padding'     : '3px 0px 0px 0px',
        'left'        : swidth + 'px',
        'position'    : 'absolute',
        'font-weight' : 'bold',
        'font-size'   : '12px'
    })
    .html('&#9658;');

    if(page != total){
        p = Number(page) + 1;
        $('#' + gt.id).css('cursor', 'pointer');
        gt.setAttribute('onclick', 'nuGotoPage(' + p + ', ' + total + ')');
    }
    $('#' + gt.id).addClass('nuUnselectedTab nuGradient');

    
}


function nuGridValue(r, c, v){

    if($('#row_'+nuXPad(r)+'_col_'+c).length == 0){
        return '';
    }
    if(arguments.length == 3){
        $('#row_'+nuXPad(r)+'_col_'+c).html('&nbsp;&nbsp;'+v+'&nbsp;&nbsp;');
    }
    
    var nv = String($('#row_'+nuXPad(r)+'_col_'+c).html().substr(12));        //-- remove &nbsp;&nbsp;
    nv = nv.substr(0, nv.length-12);                                          //-- remove &nbsp;&nbsp;
    nv = String(nv).replaceAll('<span class="nuBrowseSearch">', "", true);    //-- remove any filtered string tags
    nv = String(nv).replaceAll("</span>", "", true);                          //-- remove any filtered string tags

    return nv;
    
}

function nuGridCss(r, c, s, v){

    if($('#row_'+nuXPad(r)+'_col_'+c).length == 0){
        return '';
    }
    if(arguments.length == 4){
        $('#row_'+nuXPad(r)+'_col_'+c).css(s, v);
    }
    
    return $('#row_'+nuXPad(r)+'_col_'+c).css(s);
    
}

function nuGridClass(r, c, v, rem){

    if($('#row_'+nuXPad(r)+'_col_'+c).length == 0){
        return '';
    }
    
    if(rem) {
        $('#row_'+nuXPad(r)+'_col_'+c).removeClass(v);
    } else {
        $('#row_'+nuXPad(r)+'_col_'+c).addClass(v);
    }
       
}

function nuXPad(value) {
    
    return String(Math.pow(10,4 - String(value).length)).substr(1)+String(value);

}

function nuBrowseRows(){
    return $("[id$='col_0']").length;
}

function nuBrowseColumns(){
    return $("[id^='row_0000']").length;
}
