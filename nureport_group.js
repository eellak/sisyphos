    
function nuGroupDialog(reopen){

    nuCreateDialog(600, 600, "Group Properties");
    
    nuDialogButtons();
    nuDialogInput('', 'nu_group_report', 20, 160, 1);
    nuDialogInput('', 'nu_group_page', 45, 160, 2);
    nuDialogInput('', 'nu_group_1', 70, 160, 3);
    nuDialogInput('', 'nu_group_2', 95, 160, 4);
    nuDialogInput('', 'nu_group_3', 120, 160, 5);
    nuDialogInput('', 'nu_group_4', 145, 160, 6);
    nuDialogInput('', 'nu_group_5', 170, 160, 7);
    nuDialogInput('', 'nu_group_6', 195, 160, 8);
    nuDialogInput('', 'nu_group_7', 220, 160, 9);
    nuDialogInput('', 'nu_group_8', 245, 160, 10);
    nuDialogInput('', 'nu_group_detail', 270, 160, 0);

    $("[id^='nu_group_']").focus(function() {
        nuDisplayGroupProperties(this.id);
    });
    
    $("[id^='nu_group_']").blur(function() {
        for(var i = 1; i < 9; i++ ) {
            if( $('#nu_group_'+i).val() == '' ) {
                $('#nu_sort_'+i).val('');
            }
        }        
    });

    var ar = Array();
    ar.push('a|Ascending');
    ar.push('d|Descending');

    var pb = Array();
    pb.push('0|No');
    pb.push('1|Yes');

    nuDialogSelect('', 'nu_sort_1', 70, 370, ar);
    nuDialogSelect('', 'nu_sort_2', 95, 370, ar);
    nuDialogSelect('', 'nu_sort_3', 120, 370, ar);
    nuDialogSelect('', 'nu_sort_4', 145, 370, ar);
    nuDialogSelect('', 'nu_sort_5', 170, 370, ar);
    nuDialogSelect('', 'nu_sort_6', 195, 370, ar);
    nuDialogSelect('', 'nu_sort_7', 220, 370, ar);
    nuDialogSelect('', 'nu_sort_8', 245, 370, ar);
    
    nuDialogInput('Section Name', 'header_name', 360, 240);
    nuDialogInput('Height', 'header_height', 382, 240);
    nuDialogInput('Background Color', 'header_color', 404, 240);
    nuDialogSelect('Page Break', 'header_page_break', 426, 240, pb);
    nuDialogInput('Section Name', 'footer_name', 465, 240);
    nuDialogInput('Height', 'footer_height', 487, 240);
    nuDialogInput('Background Color', 'footer_color', 509, 240);
    nuDialogSelect('Page Break', 'footer_page_break', 531, 240, pb);

    $("#nuProperties *").change(function() {               //-- all childen on this dialog
        nuSetSectionProperties();
    });

    $('#nu_group_report').val('Report');
    $('#nu_group_page').val('Page');
    $('#nu_group_detail').val('Detail');
    
    $('#nu_group_report').attr('readonly', true);
    $('#nu_group_page').attr('readonly', true);
    $('#nu_group_detail').attr('readonly', true);
    $('#header_name').attr('readonly', true);
    $('#footer_name').attr('readonly', true);

    $('#nu_group_report').css('background-color', '#FFFFFF');
    $('#nu_group_page').css('background-color', '#FFFFFF');
    $('#nu_group_detail').css('background-color', '#FFFFFF');
    $('#header_name').css('background-color', '#FFFFFF');
    $('#footer_name').css('background-color', '#FFFFFF');
    
    //loop through all groups and focus ones with data in the groupField
    for( var i = 1; i <= 10; i++ ) {
        if( $('#'+GRP[i].groupField).val() != '' ) {
            $('#' + GRP[i].groupField).focus();
        }
    }
    
    var g = $('#nu_group_detail').data('group');
    $('#' + GRP[g].groupField).focus();
    
}    

function nuSetCurrentGroup(id){

    REPORT.currentGroup = id.substr(9);

}




    function nuHideSection(c){

        $('#' + c).css({
            'visibility'       : 'hidden',
            'border-width'     : 0,
            'height'           : 0,
            'background-color' : '#FFFFFF'
        });
    
    }

    function nuShowSection(c){
    
        $('#' + c).css({
            'visibility'   : 'visible',
            'border-width' : 1
        });
    
    }

    function nuSetSectionProperties(){

        var g = REPORT.currentGroup;
      
        if($('#' + GRP[g].groupField).val() == ''){
            
            if(confirm("Are you sure you want to remove this section \nand its objects?")){
                
                for(var i = 0 ; i < REPORT.groups[g].sections[0].objects.length ; i++){
                    $('#' + REPORT.groups[g].sections[0].objects[i].id).remove();
                }
                for(var i = 0 ; i < REPORT.groups[g].sections[1].objects.length ; i++){
                    $('#' + REPORT.groups[g].sections[1].objects[i].id).remove();
                }
                REPORT.groups[g].sections[0].objects.length = 0;
                REPORT.groups[g].sections[1].objects.length = 0;
                REPORT.groups[g].sortField                  = '';
                REPORT.groups[g].sortBy                     = '';
                REPORT.groups[g].sections[0].height         = 0;
                REPORT.groups[g].sections[0].color          = '#FFFFFF';
                REPORT.groups[g].sections[0].label          = ' Header';
                REPORT.groups[g].sections[1].height         = 0;
                REPORT.groups[g].sections[1].color          = '#FFFFFF';
                REPORT.groups[g].sections[1].label          = ' Footer';
                
                nuBuildGroupFromREPORT(g);
                nuMoveAllObjects();
                nuReadjustSections();
                nuSaveReport();
            
            }else{
                $('#' + GRP[g].groupField).val(window.nuGroupWas);
            }
            
            return;
        
        }

        if(g == 0){                                                                   //-- DETAIL section
            REPORT.groups[g].sections[0].height     = $('#header_height').val();
            REPORT.groups[g].sections[0].color      = $('#header_color').val();
            REPORT.groups[g].sections[0].page_break = $('#header_page_break').val();
        }else{
            REPORT.groups[g].sections[0].page_break = $('#header_page_break').val();
            REPORT.groups[g].sections[1].page_break = $('#footer_page_break').val();
        }

        if(g == 1 || g == 2){                                                         //-- REPORT or PAGE section
            REPORT.groups[g].sections[0].height     = $('#header_height').val();
            REPORT.groups[g].sections[0].color      = $('#header_color').val();
            REPORT.groups[g].sections[1].height     = $('#footer_height').val();
            REPORT.groups[g].sections[1].color      = $('#footer_color').val();
        }

        if(g > 2){                                                                    //-- sortable section
            REPORT.groups[g].sortField              = $('#' + GRP[g].groupField).val();
            REPORT.groups[g].sortBy                 = $('#' + GRP[g].groupSort).val();
            REPORT.groups[g].sections[0].height     = $('#header_height').val();
            REPORT.groups[g].sections[0].color      = $('#header_color').val();
            REPORT.groups[g].sections[0].label      = $('#' + GRP[g].groupField).val() + ' Header';
            REPORT.groups[g].sections[1].height     = $('#footer_height').val();
            REPORT.groups[g].sections[1].color      = $('#footer_color').val();
            REPORT.groups[g].sections[1].label      = $('#' + GRP[g].groupField).val() + ' Footer';
        }
        
        for(var idx = 3; idx < REPORT.groups.length; idx++ ) {
            if( $('#' + GRP[idx].groupField).val() != '' ) {
                REPORT.groups[idx].sortBy = $('#' + GRP[idx].groupSort).val();
            }
        }
        
        nuBuildGroupFromREPORT(g);
        nuMoveAllObjects();
        nuReadjustSections();
        nuSaveReport();
    }


    
function nuDialogButtons(){

    e = document.createElement('input');
    e.setAttribute('type', 'button');
    e.setAttribute('id', 'nuUp');
    e.setAttribute('onclick', "nuMoveGroupUp()");
    $('#nuProperties').append(e);
    $('#' + e.id).val('Move Group Up');
    $('#' + e.id).css({
        'height'   : 30,
        'width'    : 140,
        'position' : 'absolute',
        'left'     : 8,
        'top'      : 120
    });
    
    e = document.createElement('input');
    e.setAttribute('type', 'button');
    e.setAttribute('id', 'nuDown');
    e.setAttribute('onclick', "nuMoveGroupDown()");
    $('#nuProperties').append(e);
    $('#' + e.id).val('Move Group Down');
    $('#' + e.id).css({
        'height'   : 30,
        'width'    : 140,
        'position' : 'absolute',
        'left'     : 8,
        'top'      : 160
    });
    
}



    function nuDisplayGroupProperties(id){

        $('*').removeClass('nuDialogFocus');

        $('#' + id).addClass('nuDialogFocus');
        
        var group            = $('#' + id).data('group');
        REPORT.currentGroup  = group;
        window.nuGroupWas    = $('#' + id).val();
        var g                = REPORT.groups[group];

        $('#' + GRP[group].groupField).val(g.sortField);              //-- Grouping Field
        $('#' + GRP[group].groupSort).val(g.sortBy);                  //-- Group By

        $('#header_name').val(g.sections[0].label);                   //-- header
        $('#header_height').val(g.sections[0].height);
        $('#header_color').val(g.sections[0].color);
        if(g.sections.length > 1){
            $('#title_footer_name').css('visibility', 'visible');
            $('#title_footer_height').css('visibility', 'visible');
            $('#title_footer_color').css('visibility', 'visible');
            $('#footer_name').css('visibility', 'visible');           //-- footer
            $('#footer_height').css('visibility', 'visible');
            $('#footer_color').css('visibility', 'visible');
            $('#footer_name').val(g.sections[1].label);               
            $('#footer_height').val(g.sections[1].height);
            $('#footer_color').val(g.sections[1].color);
        }else{
            $('#title_footer_name').css('visibility', 'hidden');
            $('#title_footer_height').css('visibility', 'hidden');
            $('#title_footer_color').css('visibility', 'hidden');
            $('#footer_name').css('visibility', 'hidden');     
            $('#footer_height').css('visibility', 'hidden');
            $('#footer_color').css('visibility', 'hidden');
        }
        
        if(group > 2 && group < 10){      //-- only sorting group headers and footers can have page breaks
        
            $('#header_page_break').val(g.sections[0].page_break);
            $('#footer_page_break').val(g.sections[1].page_break);
            $('#title_header_page_break').css('visibility', 'visible');
            $('#header_page_break').css('visibility', 'visible');
            $('#title_footer_page_break').css('visibility', 'visible');
            $('#footer_page_break').css('visibility', 'visible');
            
        }else{
        
            $('#header_page_break').val(0);
            $('#footer_page_break').val(0);
            $('#title_header_page_break').css('visibility', 'hidden');
            $('#header_page_break').css('visibility', 'hidden');
            $('#title_footer_page_break').css('visibility', 'hidden');
            $('#footer_page_break').css('visibility', 'hidden');
            
        }
        
        for(var i = 3 ; i < 11 ; i ++){
            $('#' + GRP[i].groupField).val(REPORT.groups[i].sortField);
        }
        
    }




    function nuMoveGroupUp(){
    
        if(REPORT.currentGroup < 4){                     //-- Detail, Page, Report or Top Group
            alert('Cannot Move This Group Up');
            return;
        }

        if($('#' + GRP[REPORT.currentGroup].groupField).val() == ''){
            alert('Cannot Move This Group Up');
            return;
        }
        

        var grp = [];
        grp     = REPORT.groups.splice(REPORT.currentGroup, 1);
        REPORT.groups.splice(REPORT.currentGroup - 1, 0, grp[0]);

        nuDisplayGroupProperties(GRP[REPORT.currentGroup].groupField);
        nuDisplayGroupProperties(GRP[REPORT.currentGroup - 1].groupField);    
        
        for( var idx = 1; idx < 9; idx++ ) {
            var groupBy = $('#nu_group_'+idx).val();
            if( groupBy == '' ) {
                $('#nu_sort_'+idx).val('');
            }
        }
        
        nuSortSections();
        nuMoveAllObjects();
        nuReadjustSections();
        nuSaveReport();

    }


    
    
    
    function nuMoveGroupDown(){
    
        var currentIs  = REPORT.currentGroup;

        if(REPORT.currentGroup > 9 || REPORT.currentGroup < 3){                     //-- Bottom Group
            alert('Cannot Move This Group Down');
            return;
        }


        var grp = [];
        grp     = REPORT.groups.splice(REPORT.currentGroup, 1);
        REPORT.groups.splice(REPORT.currentGroup + 1 , 0, grp[0]);


        nuDisplayGroupProperties(GRP[REPORT.currentGroup].groupField);
        nuDisplayGroupProperties(GRP[REPORT.currentGroup + 1].groupField);

        for( var idx = 1; idx < 9; idx++ ) {
            var groupBy = $('#nu_group_'+idx).val();
            if( groupBy == '' ) {
                $('#nu_sort_'+idx).val('');
            }
        }
        
        nuSortSections();
        nuMoveAllObjects();
        nuReadjustSections();
        nuSaveReport();

    }


function nuReopenGroupProperties(){

    // Reopen Select Objects dialog

    var title = String($('#nuDragTitle').html()).substr(6);

    if(title == 'Group Properties'){
        nuGroupDialog(true);
    }

}