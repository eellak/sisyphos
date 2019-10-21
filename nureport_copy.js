
function nuStringify(){

    if(window.opener){

        if(window.nuSaveObject != 0){                                           //-- does need saving
            nuSetOnBlur(window.nuSaveObject);
        }

        window.opener.document.getElementById('sre_layout').value = JSON.stringify(REPORT);
        alert('Copied to Report Successfully..');
        window.opener.nuSetEdited();
        window.close();
    }else{
        alert('Cannot be saved to Report Form');
    }


}
