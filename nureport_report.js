
function nuPaper(p){

    if(p == 'A4P')    {return '297,210';}
    if(p == 'A5P')    {return '210,148';}
    if(p == 'LetterP'){return '279.4,215.9';}
    if(p == 'LegalP') {return '355.6,215.9';}
    if(p == 'A4L')    {return '210,297';}
    if(p == 'A5L')    {return '148,210';}
    if(p == 'LetterL'){return '215.9,279.4';}
    if(p == 'LegalL') {return '215.9,355.6';}
    return '0,0';
}




function nuReportDialog(){

    var ore = Array();
    ore.push('P|Portrait');
    ore.push('L|Landscape');
    
    var pap = Array();
    pap.push('A4|A4');
    pap.push('A5|A5');
    pap.push('Letter|Letter');
    pap.push('Legal|Legal');

    nuCreateDialog(400, 180, "Report Properties");
    nuDialogInput('Width', 'nuPageWidth', 20, 140);
    nuDialogInput('Height', 'nuPageHeight', 45, 140);
    nuDialogSelect('Paper Type', 'nuPageType', 70, 140, pap);
    nuDialogSelect('Orientation', 'nuPageOrientation', 95, 140, ore);

    $("select").change(
        function (){
            paper = nuPaper($('#nuPageType').val()+$('#nuPageOrientation').val());
            nuPaper($('#nuPageWidth').val(paper.split(',')[1]));
            nuPaper($('#nuPageHeight').val(paper.split(',')[0]));
            nuChangeWidth(paper.split(',')[1]);
            
        }
    );
    
    $('#nuPageWidth').val(REPORT.width);
    $('#nuPageHeight').val(REPORT.height);
    $('#nuPageType').val(REPORT.paper);
    $('#nuPageOrientation').val(REPORT.orientation);
    
}    


    function nuReportPropertiesOK(){

        REPORT.width       = $('#nuPageWidth').val();
        this.right         = parseInt(REPORT.width) + 30;
        REPORT.height      = $('#nuPageHeight').val();
        REPORT.paper       = $('#nuPageType').val();
        REPORT.orientation = $('#nuPageOrientation').val();
        nuChangeWidth(REPORT.width);
        nuSaveReport();
        return true;

    }
  
