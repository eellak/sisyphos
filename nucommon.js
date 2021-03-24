window.loading = false;
window.nuLastMoverClick = new Date().getTime();
window.nuTabsReordered = false;

function nuSubformArray(sf, all){

    var a = Array();
    var i = 0;
    var p = '';

    if(arguments.length == 1){
        all = true;
    }
    
    if($('#'+sf+'0000_nuRow').length == 0){return a;}
    
    while($('#' + sf + ("000" + String(i)).slice(-4) + '_nuRow').length == 1){
    
        p = sf+("000" + String(i)).slice(-4);
        
        if($('#'+p+'_nuDelete').attr('checked') == undefined || all){
                a.push(p);
        }
        i++;
    }
    
    return a;

}


function nuRowPrefix(pthis){

    return $('#'+pthis.id).attr('data-prefix');
    
}


function nuRowNumber(pthis){

    return $('#'+pthis.id).attr('data-row');
    
}

function nuSearchPressed(e){

    if(!e){e=window.event;}

    if(e.keyCode == 13){                    //-- enter key
        $('#nuSearchButton').click();
    }
    
}


function nuKeyPressed(e, isPressed){

    if(!e){e=window.event;}

    if(e.keyCode == 16){                    //-- shift key
        window.nuShiftKey     = isPressed;
        $('.nuSelected').css( 'cursor',  'e-resize');
    }
    if(e.keyCode == 17 || e.keyCode == 18){ //-- control key or alt/option key
        window.nuLastCtrlPressedTS = Math.floor(Date.now()/1000);
        window.nuControlKey   = isPressed;
        $('.nuSelected').css( 'cursor',  'move');
    }

    if(isPressed){
        $("#nuObjectList > option:selected").each(function() {

            var o       = nuDraggableObjectProperties(this.value);
            var t       = Number(o.holder_top);          //-- top
            var l       = Number(o.holder_left);         //-- left
            
            if (e.keyCode == 37){l--;}                   //-- left
            if (e.keyCode == 39){l++;}                   //-- right
            if (e.keyCode == 38){t--;}                   //-- up
            if (e.keyCode == 40){t++;}                   //-- down

            nuMoveObject(this.value, t, l);              //-- move object
            
        });
                        
        nuRecalculateCoordinates();
    }
    
}

String.prototype.capitalize = function() {
    return this.replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });
};

String.prototype.replaceAll = function(str1, str2, ignore){

   return this.replace(new RegExp(str1.replace(/([\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g, function(c){return "\\" + c;}), "g"+(ignore?"i":"")), str2);

};


function nuGetParentSession(){ 

    if (window.opener != null){
        return window.opener.window.nuSession;
    }else{ 
        return document.defaultView.parent.nuSession;
    }  

}


function nuAddButtons(b){
    addButtons(b);
}

function addButtons(buttons){

    for(var i = 0 ; i < buttons.length ; i++){

        var e = document.createElement('span');                            //-- create space
        e.setAttribute('id', 'nuSpace'+i);
        $('#nuActionButtonHolder').append(e);
        $('#' + e.id).html('&nbsp;');

        var e = document.createElement('input');                           //-- create button
        e.setAttribute('id', 'nuButton'+i);
        e.setAttribute('type', 'button');
        e.setAttribute('value',   ' '+nuTranslate(buttons[i].title)+' ');  //-- add title
        e.setAttribute('onclick', buttons[i].js);                          //-- add javascript
        $('#nuActionButtonHolder').append(e);
        $('#' + e.id).addClass('nuButton');
        $('#' + e.id).addClass('nuActionButton');
        
    }

}

function createCookiesPopUp(){
	var script = document.createElement('script');
	script.onload = function () {
			$('body').ihavecookies({
				title: '&#x1F36A; Αποδοχή Cookies και Πολιτικής Απορρήτου',
				message: 'Για την αποτελεσματικότερη λειτουργία του ο Σίσυφος χρησιμοποιεί cookies και άλλα δεδομένα προσωπικού χαρακτήρα. Για να τον χρησιμοποιήσετε είναι απαραίτητο να συμφωνήσετε με την χρήση των cookies και την πολιτική απορρήτου.',
				delay: 500,
				expires: 180,
				link: 'privacyPolicy.php',
				onAccept: function(){
                    $("#log").prop('disabled',false);
				},
				uncheckBoxes: false
			});
			
		$("<style>")
			.prop("type", "text/css")
			.html("\
				#gdpr-cookie-message {\
					position: fixed;\
					z-index: 10000;\
					top: 20px;\
					left: 10px;\
					right: 20px;\
					background-color: #f4f4f4;\
					padding: 7px;\
					max-width: 550px;\
					border-radius: 5px;\
					box-shadow: 0 6px 6px rgba(0,0,0,0.25);\
					margin-left: 20px;\
					font-family: sans-serif;\
				}\
				#gdpr-cookie-message h4 {\
			        color: #666666;\
			        font-family: sans-serif;\
			        font-size: 12px;\
			        font-weight: bold;\
			        margin-bottom: 5px;\
			    }\
			    #gdpr-cookie-message p {\
					color: #666666;\
					font-family: sans-serif;\
			        font-size: 11px;\
			    }\
			    #gdpr-cookie-message p:last-child {\
			        margin-bottom: 0;\
			        text-align: right;\
			    }\
			    #gdpr-cookie-message a {\
			        color: #4682b4;\
			        text-decoration: none;\
			        font-size: 11px;\
			        padding-bottom: 2px;\
			        border-bottom: 1px dotted rgba(255,255,255,0.75);\
			        transition: all 0.3s ease-in;\
			    }\
			    #gdpr-cookie-message a:hover {\
			        color: #4682b4;\
			        transition: all 0.3s ease-in;\
 			   }\
			    #gdpr-cookie-message button {\
			        border: none;\
					background: #559dd9;\
					color: white;\
					font-family: sans-serif;\
			        font-size: 12px;\
			        padding: 7px;\
			        border-radius: 3px;\
			        margin-left: 15px;\
			        cursor: pointer;\
					transition: all 0.3s ease-in;\
			    }\
			    #gdpr-cookie-message button:hover {\
			        background: #4682b4;\
			        color: white;\
 			       transition: all 0.3s ease-in;}")
			.appendTo("head");
	};
	script.src = 'ihavecookies.min.js';
	document.head.appendChild(script);
}


function toggleModalMode(){   //-- login screen

    if($("#modal_div").length == 0){		
        var e = document.createElement('div');
        e.setAttribute('id', 'modal_div');
        $('body').append(e);
        $('#' + e.id).css({
            'width'            : '100%',
            'height'           : '100%',
            'top'              : '0px',
            'left'             : '0px',
            'position'         : 'absolute',
            'filter'           : 'Alpha(Opacity=20)',
            'opacity'          : '0.2',
            'background-color' : '#FFFFFF'
        });

        var e = document.createElement('div');
        e.setAttribute('id', 'userpass1');
        $('body').append(e);
        $('#' + e.id).css({
            'width'            : '420px',
            'height'           : '300px',
            'top'              : '15%',
            'left'             : '50%',
            'margin-left'      : '-210px',
            'position'         : 'absolute',
            'background-color' : '#faf4ed',
            'border-radius'    : '5px',
			'border-style'    : 'solid',
			'border-color'    : '#CCC2B8',
			'border-width'    : '1px'
        })
        .addClass( 'nuShadeHolder');

        var screenSection = e.id;

        var e = document.createElement('span');
        e.setAttribute('id', 'sptitle');
        $('#'+ screenSection).append(e);
        $('#' + e.id).css({
            'top'              : '35px',
            'left'             : '5px',
            'width'            : '400px',
            'text-align'       : 'center',
            'font-size'        : '25px',
            'font-family'      : 'sans-serif',
            'position'         : 'absolute',
            'background-color' : '#faf4ed'
        })
        .html('<img src=\'sisyphos_logo.png\'/>');


        var e = document.createElement('span');
        e.setAttribute('id', 'dbtitle');
        $('#'+ screenSection).append(e);
        $('#' + e.id).css({
            'top'              : '92px',
            'left'             : '5px',
            'width'            : '400px',
            'text-align'       : 'center',
            'font-size'        : '15px',
            'font-family'      : 'sans-serif',
            'position'         : 'absolute',
			'color'            : '#666666',
            'background-color' : '#faf4ed'

        })
        .html(nuGetTitle());

        var e = document.createElement('span');
        e.setAttribute('id', 'sptitlea');
        $('#'+ screenSection).append(e);
        $('#' + e.id).css({
            'top'              : '135px',
            'left'             : '30px',
            'position'         : 'absolute',
            'background-color' : '#faf4ed',
            'font-family'      : 'sans-serif',
            'color'            : '#666666', 
            'font-size'        : '15px'
        })
        .html(nuTranslate('Username :'));
        
        var e = document.createElement('span');
        e.setAttribute('id', 'sptitleb');
        $('#'+ screenSection).append(e);
        $('#' + e.id).css({
            'top'              : '180px',
            'left'             : '30px',
            'position'         : 'absolute',
            'background-color' : '#faf4ed',
            'font-family'      : 'sans-serif',
            'color'            : '#666666',
            'font-size'        : '15px'
        })
        .html(nuTranslate('Password :'));

        var e = document.createElement('input');
        e.setAttribute('id', 'u');
        e.setAttribute('autocapitalize', 'off');
        e.setAttribute('autocorrect', 'off');
        $('#'+ screenSection).append(e);
        $('#' + e.id).css({
            'width'            : '150px',
            'top'              : '135px',
            'left'             : '150px',
            'position'         : 'absolute',
            'background-color' : 'white',
            'font-size'        : '22px',
            'color'            : '#666666',
            'line-height'      : '22px'
        });
        
        var e = document.createElement('input');
        e.setAttribute('id', 'p');
        e.setAttribute('type', 'password');
        e.setAttribute('autocapitalize', 'off');
        e.setAttribute('autocorrect', 'off');
        $('#'+ screenSection).append(e);
        
        $('#' + e.id).css({
            'width'            : '150px',
            'top'              : '180px',
            'left'             : '150px',
            'position'         : 'absolute',
            'background-color' : 'white',
            'font-size'        : '22px',
            'color'            : '#666666',
            'line-height'      : '22px'
        })
            .keypress(function(event) {
                
                if ( event.which == 13 ) {
                    nuLogin();
                }
            
            });
                
        var e = document.createElement('input');
        e.setAttribute('id', 'log');
        e.setAttribute('type', 'button');
        e.setAttribute('onclick', 'nuLogin()');

        $('#'+ screenSection).append(e);
        $('#' + e.id).css({
            'width'            : '250px',
            'height'           :  '35px',
            'top'              : '230px',
            'left'             : '85px',
            'position'         : 'absolute',
            'background-color' : '#d99b00',
            'border-radius'    : '0px',
            'font-size'        : '22px',
            'color'            : 'white',   
            'line-height'      : '22px',
            'border-radius'    : '3px'
        })
        .val('Είσοδος');
		
        createCookiesPopUp();
    }else{
        $("#modal_div").remove();
        $("#userpass").remove();
        $("#userpass1").remove();
    }
    $('#u').focus();
}




function nuSID(){

    try{
        return document.defaultView.parent.window.nuSession.nuSessionID;
    }catch(e){
        return '';
    }
}


function nuIsDesktop(){

    var q = window.location.href.substr(window.location.pathname.length+window.location.origin.length);
    if(q == ''){
        return true;
    }else{
        return false;
    }
}

function nuGetIframeID(){

    return window.location.href.substr(window.location.pathname.length+window.location.origin.length+4);

}

function nuOpenObjectForm(pThis){

    if(!nuIsGA()){return;}                               //-- not globeadmin
    if($('#nuObjectList').length == 1){return;}          //-- in drag drop mode

    if(window.nuDenied != ''){                           //-- stop access to system tables
        if(nuIsSystem()){
            return;
        }
    }

    var id                  = $('#'+pThis.id).attr('data-id');
    if(window.nuShiftKey){
        nuMoveObject(pThis.id.substr(6), 10, 0);    
    }else{
        window.nuControlKey = true;
        window.nuLastCtrlPressedTS = Math.floor(Date.now() /1000);
        nuOpenForm('nuobject', id, 'nuobject', id, 'nuBuilder Objects');
        window.nuControlKey = false;
    }

}

function nuOpenFormForm(pThis){

    if(!nuIsGA()){return;}
        
    if(window.nuDenied != ''){                           //-- stop access to system tables
        if(nuIsSystem() ||  nuFORM.form_id == 'nuhome'){ //-- nuIsSystem() allows for 'nuhome' but it shouldn't be here
            return;
        }
    }

    var id              = $('#'+pThis.id).attr('data-id');

    window.nuLastCtrlPressedTS = Math.floor(Date.now() /1000);
    if(window.nuControlKey){                              //-- held down manually
        nuOpenForm('nuobject', '', 'nuobject', '', 'nuBuilder Objects', id);
    }else{
        window.nuControlKey = true;                       //-- faked keypress
        window.nuLastCtrlPressedTS = Math.floor(Date.now() /1000);
        nuOpenForm('nuform', id, 'nuform', id, 'nuBuilder Form');
    }

    window.nuControlKey = false;

}

function nuOpenForm(parentFormID, parentRecordID, formID, recordID, formTitle, filter){

    var w              = new nuWindow();
    w.form_id          = formID;
    w.parent_form_id   = parentFormID;
    w.parent_record_id = parentRecordID;
    w.title            = formTitle;
    w.form_data        = nuGetData();
    
    if(arguments.length == 6){
        w.filter       = filter;
    }
    if(recordID == '' || recordID == 'null'){
        w.call_type    = 'getbrowseform';
        w.tip          = 'Browse';
        w.type         = 'Browse';
    }else{
        w.call_type    = 'geteditform';
        w.record_id    = recordID;
        w.tip          = 'Edit';
        w.type         = 'Edit';
    }
    var newTabKeyPressedRecently = false;
    if(typeof window.nuLastCtrlPressedTS != 'undefined'){
        if(Math.floor(Date.now() /1000) < (window.nuLastCtrlPressedTS+2))
            newTabKeyPressedRecently = true;
    }
    if(newTabKeyPressedRecently){ //-- open in a new window
        nuOpenNewWindowManager(w);
    } else {
        nuBuildForm(w);
    }
    
}

function nuOpenFormInFrame(formID,recordID,functionName){

    var parent           = '';
    var w                = new nuWindow();
    w.call_type          = 'geteditform';
    w.form_id            = formID;
    w.breadcrumb         = '0';
    w.record_id          = recordID;
    w.tip                = 'Edit';
    w.type               = 'Edit';
    if(arguments.length == 3){
        w.function_name    = functionName;
    }

    nuSession.nuWindows.push(w);
    nuIframeWindow(w);
    
}


function nuOpenBrowseInFrame(formID, functionName){

    var parent           = '';
    var w                = new nuWindow();
    w.call_type          = 'getlookupform';
    w.form_id            = formID;
    w.breadcrumb         = '0';
    w.tip                = 'Browse';
    w.type               = 'Browse';
    if(arguments.length == 2){
        w.function_name    = functionName;
    }

    nuSession.nuWindows.push(w);
    nuIframeWindow(w);
    
}

    function nuOpenNewWindowManager(w) {

        if ( nuOpenNewWindowCheck() ) {
            if (window.top === window.self) {
                nuOpenNewWindow(w);
            } else {
                nuOpenNewWindowParent(w);
            }
        } else {
            nuSession.nuWindows.push(w);
            window.open('index.php?i='+w.id);
        }
    }

function nuOpenNewWindowCheck() {
    
    if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
        return true;
    }
    
    return false;
}

function nuIframeWindowSizer() {

        if (window.top !== window.self) {
                if ( window.parent.document.getElementById('nuDrag') ) {
                        var iWidthSource = parseInt(document.getElementById('nuHolder').style.width);
                        var iWidthBuffer = 20;                                                               //--offset of 20 to account for padding
                        var iWidthDest   = iWidthSource + iWidthBuffer;
                        window.parent.document.getElementById('nuDrag').style.width = iWidthDest+'px';
                        window.parent.document.getElementById('nuDragBar').style.width = iWidthDest+'px';
                        var iHeightSource = parseInt(document.getElementById('nuHolder').style.height);
                        var iHeightBuffer = 20;
                        var iHeightDest   = iHeightSource + iHeightBuffer;
                        window.parent.document.getElementById('nuDrag').style.height = iHeightDest+'px';
						window.document.body.style.background = "#e5dcd3 no-repeat center center fixed";
                }
        }
}

function nuIframeWindow(w) {

    var url = 'index.php?i='+w.id;
    nuCustomIframeWindow(url, w, '23px', '23px');

}

function nuCustomIframeWindow(url, w, startWidth, startHeight, startTop, startLeft) {
    

    
    if(arguments.length > 1){
        if(typeof w == 'object'){
            var iframe_id      = w.id;
            var function_name  = w.function_name;
        }else{
            var iframe_id      = w;
            var function_name  = '';
        }
    } else {
        var iframe_id = 'custom';
    }

    if(arguments.length > 2){
                var sW  = startWidth;
        } else {
                var sW = '960px';
        }

     if(arguments.length > 3){
                var sH  = startHeight;
        } else {
                var sH = '660px';
        }

    if(arguments.length > 4){
                var sT  = startTop;
        } else {
                var sT = '20px';
        }
    
    if(arguments.length > 5){
                var sL  = startLeft;
        } else {
                var sL = '50px';
        }


        var e = document.createElement('div');
        e.setAttribute('id', 'nuModal');
        $('body').append(e);
        $('#' + e.id).css({
            'width'            : '100%',
            'height'           : '100%',
            'top'              : '0px',
            'left'             : '0px',
            'position'         : 'fixed',                   //--29/01/2014 - Fixed position covers entire screen, whilst absolute covers what you see at the moment - Ken
            'background-color' : '#000000',
            'filter'           : 'Alpha(Opacity=20)',
            'opacity'          : '.2'
        });

        var e = document.createElement('div');              //-- create draggable div
        e.setAttribute('id', 'nuDrag');
        $('body').append(e);
        $('#' + e.id).css({
            'width'            : sW,
            'height'           : sH,
            'top'              : sT,
            'left'             : sL,
            'position'         : 'fixed',					//--24/03/2021 - Fixed position Sisyphos -was absolute
            'background-color' : '#E1E8EA',
            'border-width'     : '2px',
            'border-color'     : '#99918a',
            'border-style'     : 'solid',
			'border-radius' : '5px',
            'filter'           : 'Alpha(Opacity=100)',
            'opacity'          : '1'
        })
        .draggable({ handle: 'nuDragBar' });

        var screenSection = 'nuDrag';
        var e = document.createElement('div');              //-- create draggable div
        e.setAttribute('id', 'nuDragBar');
        $('#'+ screenSection).append(e);
        $('#' + e.id).css({
            'width'            : '20px',
            'height'           : '22px',
            'top'              : '0px',
            'left'             : '0px',
            'position'         : 'absolute',
            'background-color' : '#99918a',
            'z-index'          : '1'
        });

        var e = document.createElement('div');              //-- create draggable div
        e.setAttribute('id', 'nuDragBarClose_');
        $('#nuDragBar').append(e);
        $('#' + e.id).css({
            'width'            : '20px',
            'height'           : '20px',
            'top'              : '0px',
            'left'             : '0px',
            'position'         : 'absolute',
            'z-index'          : '2'
        })
        .addClass('nuClose')
        .html('&#10006;')
        .mousedown(function() {
            $('#nuModal').remove();
            $('#nuDrag').remove();
            if(function_name != ''){
                window[function_name]();                      //-- run a function added as parameter 3 in nuOpenFormInFrame()
            }
        });

        var e = document.createElement('iframe');              //-- create iframe
        e.setAttribute('name', iframe_id);
        e.setAttribute('id', iframe_id);
        e.setAttribute('src', url);
        $('#'+ screenSection).append(e);
        $('#' + iframe_id).css({
            'border-radius' : '5px',
            'width'         : '100%',
            'height'        : '100%',
            'top'           : '0px',
            'left'          : '0px',
            'position'      : 'absolute'
        });

    }

function nuOpenNewWindow(w) {
    w.breadcrumb         = '0';
    nuSession.nuWindows.push(w);
    nuIframeWindow(w);
}

function nuOpenNewWindowParent(w) {
    window.parent.nuOpenNewWindow(w);
}

function nuOpenLookup(pThis, pFilter, pSearch){

    nuCloseModal();
    var parent           = '';
    var w                = new nuWindow();
    w.parent_form_id     = nuFORM.parent_form_id;
    w.call_type          = 'getlookupform';
    w.form_id            = $('#'+pThis.id).attr('data-form');
    w.form_data          = nuGetData();
    w.filter             = '';
    if(arguments.length == 2){
        w.filter             = pFilter;
    }
    if(arguments.length == 3){
        w.search             = pSearch;
    }
    w.breadcrumb         = '0'
    w.prefix             = $('#'+pThis.id).attr('data-prefix');  //-- lookup prefix
    if(pThis.id.substr(0,4) == 'btn_'){
        w.lookup         = pThis.id.substr(w.prefix.length+4);     //-- lookup id
    } else {
        w.lookup         = pThis.id;
    }

    nuSession.nuWindows.push(w);
    nuIframeWindow(w);
}

//******************************** Main Menu ********************************************

function addMainMenuButton(){
	var d1 = document.createElement('div');
	d1.setAttribute('id', 'mainMenuBtn');
	d1.setAttribute('state', 'hide');
	d1.setAttribute("onclick",  "activateMainMenuBtn()");
	d1.setAttribute("onmouseover",  "overMainMenuBtn()"); 
	d1.setAttribute("onmouseout",  "outMainMenuBtn()"); 
	d1.setAttribute('title', 'Συντομεύσεις');
	d1.className = "mainMenuButton";
				
	var s1 = document.createElement('span');
	s1.className = "glyphicon glyphicon-menu-hamburger mainMenuButtonText";
	//s1.innerHTML='&#9776;';
	
	d1.appendChild(s1);
				
	var s2 = document.createElement('span');
	s2.innerHTML='&nbsp;&nbsp;';
					
	var d2 = document.createElement('div');
	d2.setAttribute('id', 'mainMenuDiv');
	d2.setAttribute('title', 'Συντομεύσεις');
	d2.className = "mainMenu";
				
	var t = document.createElement('table');
	t.setAttribute('border', '0');
	t.setAttribute('width', '100%');
	t.setAttribute('height', '100%');
	d2.appendChild(t);
				
	$('#nuBreadCrumbHolder').append(d1);
	$('#nuBreadCrumbHolder').append(s2);
	$('#nuBreadCrumbHolder').append(d2);			
}
function overMainMenuBtn(){
	if($('#mainMenuBtn').attr('state')!="act"){
		$('#mainMenuBtn').css("background-color", "#fde8ce");
	} else {
		$('#mainMenuBtn').css("background-color", "#fbd19d");
	}
}
function outMainMenuBtn(){
	if($('#mainMenuBtn').attr('state')!="act"){
		$('#mainMenuBtn').css("background-color", "transparent");
	} else {
		$('#mainMenuBtn').css("background-color", "#fbd19d");
	}
}
function activateMainMenuBtn(){
	if($('#mainMenuBtn').attr('state')=="act"){
		$('#mainMenuBtn').attr('state','hide');
		$('#mainMenuBtn').css("background-color", "#fde8ce");
		hideMenu('mainMenuDiv');
	} else {
		$('#mainMenuBtn').attr('state','act');
		$('#mainMenuBtn').css("background-color", "#fbd19d");
		showMenu('mainMenuDiv','mainMenuBtn');
	}
}
function polluteMainMenuBtn(js){
	if(js){
		var d = JSON.parse(js);
		for(i=0; i<d.menu_items.length; i++) {
			addMenuItem("mainMenuDiv",d.menu_items[i]);	
		}
	}
}
function showMenu(menuId,parentId){
	menuSelector = "#"+menuId;
	if (!($(menuSelector).is(':visible'))){
		          
		var new_top = $('#'+parentId).height()+7;
		var new_left = 10;
					
		$(menuSelector).css({ top: new_top+'px' });
		$(menuSelector).css({ left: new_left+'px' });
					
		$(menuSelector).show(200);
	}
}			
function hideMenu(menuId){
	$('#'+menuId).hide(200);	
}			
function highlightMainMenuImage(img){
	if(img.src){img.src = img.src.substring(0,img.src.length - 4)+"b.png";}
	img.style.cursor='pointer'	
}
function downlightMainMenuImage(img){
	if(img.src){img.src = img.src.substring(0,img.src.length - 5)+".png";}
	img.style.cursor='pointer'				
}			
function addMenuItem(menuId,data){
	if(data.image || data.label){
		var d = document.getElementById(menuId);
		var t = d.getElementsByTagName('table')[0];
				
		var cimg = d.getElementsByTagName('img').length;
		var crow = t.rows.length;
				
		if(cimg==crow){			// Πρέπει να εισαχθεί μια νέα γραμμη
			var row1 	= t.insertRow(-1);
			var cell11 	= row1.insertCell(0);
    		var cell12 	= row1.insertCell(1);
					
			var row2 	= t.insertRow(-1);
			var cell21 	= row2.insertCell(0);
    		var cell22 	= row2.insertCell(1);
					
			crow+=2;
										// Πρέπει να μεγαλώσει το container div
			d.style.height = (crow * 27)+'px';
				
			imgCell = cell11;
			textCell = cell21;
		} else {
			imgCell = t.rows[crow-2].cells[1];
			textCell = t.rows[crow-1].cells[1];
		}
				
		var img = document.createElement('img');
				
		img.onload = function () {                               
			var w = this.width;
			var h = this.height;
			if(w && h){
				w = Math.floor(w / 2);
				h = Math.floor(h / 2);
				this.style.width = w+'px';
				this.style.height = h+'px';
			}
			this.onload=function (){};
		};
				
		if(data.image){img.src = "images/"+data.image;}
		if(data.title){img.title = data.title;}
		if(data.onclick_code){img.setAttribute("onclick",  data.onclick_code);}
		if(data.onmouseover_code){img.setAttribute("onmouseover",  data.onmouseover_code);} 
		if(data.onmouseout_code){img.setAttribute("onmouseout",  data.onmouseout_code);}
				
		imgCell.appendChild(img);
		imgCell.className = "mainMenuItemImage";
				
    	if(data.label){textCell.innerHTML = data.label;}
		textCell.className = "mainMenuItemText";
	}
}
function openFormFromMenu(formID, recordID, formTitle){
	var b = window.nuSession.breadCrumb;
	while(b.length > 1) {
    	b.pop();
	}
	nuOpenForm('','', formID, recordID,formTitle);
}

function addIcons(){
	var d = document.createElement('div');
	d.setAttribute('id', 'iconHolder');
	d.className = "iconHolder";

	if(nuFORM.new_notifications!=='-1'){
		var d0 = document.createElement('div');
		d0.className = "iconBadgeHolder";
		d0.setAttribute("onclick",  "alert('Λειτουργία υπό κατασκευή...');");
	
		var i0 = document.createElement('span');
		i0.className = "glyphicon glyphicon-bell iconItem";
		i0.title = "Ειδοποιήσεις";
		d0.appendChild(i0);
	
		if(nuFORM.new_notifications!=='0'){
			var badge0 = document.createElement('span');
			badge0.className = "iconBadge";
			badge0.innerHTML = nuFORM.new_notifications;
			d0.appendChild(badge0);
		}
	
		d.appendChild(d0);
	}

	if(nuFORM.new_messages!=='-1'){
		var d4 = document.createElement('div');
		d4.className = "iconBadgeHolder";
		d4.setAttribute("onclick",  "alert('Λειτουργία υπό κατασκευή...');");	
	
		var i4 = document.createElement('span');
		i4.className = "glyphicon glyphicon-comment iconItem";
		i4.title = "Μηνύματα";
		i4.setAttribute("onclick", "alert('Λειτουργία υπό κατασκευή...');");
		d4.appendChild(i4);
	
		if(nuFORM.new_messages!=='0'){
			var badge4 = document.createElement('span');
			badge4.className = "iconBadge";
			badge4.innerHTML = nuFORM.new_messages;
			d4.appendChild(badge4);
		}
	
		d.appendChild(d4);
	}
	
	var i1 = document.createElement('span');
	i1.className = "glyphicon glyphicon-user iconItem";
	i1.title = "Λογαριασμός Πρόσβασης";
	i1.setAttribute("onclick",  "openFormFromMenu('54a65ecc3f14737', nuFORM.nu_user_id, 'Στοιχεία Λογαριασμού');");
	d.appendChild(i1);
	
	var i3 = document.createElement('span');
	i3.title = "Ανανέωση";
	i3.className = "glyphicon glyphicon-refresh iconItem";
	i3.setAttribute("onclick",  "nuReloadForm();");
	d.appendChild(i3);

	var i2 = document.createElement('span');
	i2.title = "Αποσύνδεση";
	i2.className = "glyphicon glyphicon-log-out iconItem";
	i2.setAttribute("onclick",  "location.href =nuGetHome();");
	d.appendChild(i2);
	
	$('#nuBreadCrumbHolder').append(d);			
}

function addPlayButtons(){
	var d = document.createElement('div');
	d.setAttribute('id', 'playButtonHolder');
	d.className = "playButtonHolder";
	
	var i1 = document.createElement('span');
	i1.className = "glyphicon glyphicon-step-backward playButton";
	i1.title = "Πρώτη Εγγραφή";
	i1.setAttribute("onclick",  "nuAjax('first_record','open_record')");
	d.appendChild(i1);

	var i2 = document.createElement('span');
	i2.className = "glyphicon glyphicon-triangle-left playButton";
	i2.title = "Προηγούμενη Εγγραφή";
	i2.style.fontSize = "0.9em";
	i2.setAttribute("onclick",  "nuAjax('prev_record','open_prev_record')");
	d.appendChild(i2);

	var i3 = document.createElement('span');
	i3.className = "glyphicon glyphicon-triangle-right playButton";
	i3.title = "Επόμενη Εγγραφή";
	i3.style.fontSize = "0.9em";
	i3.setAttribute("onclick",  "nuAjax('next_record','open_next_record')");
	d.appendChild(i3);

	var i4 = document.createElement('span');
	i4.className = "glyphicon glyphicon-step-forward playButton";
	i4.title = "Τελευταία Εγγραφή";
	i4.setAttribute("onclick",  "nuAjax('last_record','open_record')");
	d.appendChild(i4);
	
	$('#nuTabTitleHolder').append(d);
}
//*******************************************************************************************************

function nuAddBreadCrumbs(){
	
	var b = window.nuSession.breadCrumb;
	var a = "&nbsp;&#9658;&nbsp;";	
	
	//*************************************** Add Main Menu **********
	var frm = window.frameElement;
	if(!nuIsGA() && !frm && b[0].title=="Αρχική" && nuFORM.menu){	
		addMainMenuButton();
		polluteMainMenuBtn(nuFORM.menu);
	}
	//************************************************************
	
    for(var i = 0 ; i < b.length ; i++){
        var e = document.createElement('span');                  //-- create a breadcrumb
        e.setAttribute('id', 'nuBreadCrumb_'+i);
        $('#nuBreadCrumbHolder').append(e);
        $('#'+e.id).html(b[i].title)
        .addClass('nuBreadCrumbSectionEnd');
        if(i+1==b.length){
            e.setAttribute('title',   'Είστε εδώ...');
            e.setAttribute('data-id',   b[i].form_id);
            e.setAttribute('ondblclick',   'nuOpenFormForm(this)');
            
        }else{
            e.setAttribute('onclick', 'nuGoToForm('+i+')');
            e.setAttribute('title',   b[i].tip);
            $('#' + e.id).addClass('nuBreadCrumbSection');
            var e = document.createElement('span');              //-- create an arrow
            e.setAttribute('id', 'nuArrow_'+i);
            $('#nuBreadCrumbHolder').append(e);
            $('#'+e.id).addClass('nuBreadCrumbPointer')
            .html(a);
        }
    
    }
	if (typeof nuFORM.sum === 'undefined' && !nuIsGA()) {					// is not a browse form
		if(!frm){															// is not in iframe
			addIcons();
		}
		if(nuFORM.play_buttons==='1'){
			addPlayButtons();
		}
	}
}

function nuAddJavascript(o){
        
    window.nuLoadBrowse       = null;
    window.nuLoadEdit         = null;
    window.nuOnSave           = null;
    window.nuDraggableObjects = Array();
    window.nuDraggableObjects = o.draggable_objects;

    $('#nuHolder').append('<script type="text/javascript">'+o.form_javascript+'</script>');
        
}


function nuGoToForm(i, ask){

    if(nuFORM.edited == '1' && nuFORM.parent_form_id  != 'nurunreport' && nuFORM.parent_form_id  != 'nurunphp'){
        
        if(arguments.length != 2){
            //if(!confirm(nuTranslate("Leave This Form Without Saving?"))){
            //    return;
            //}
        }
        
    }

    nuCloseModal();
    if(arguments.length == 0){
            var i = window.nuSession.breadCrumb.length - 1;
    }

    var b         = window.nuSession.getBreadCrumb(i);
    
    nuBuildForm(b);
    
}


function nuReloadForm(){

    nuFORM.call_type    = 'geteditform';
    
    if(nuFORM.edited == '1' && nuFORM.parent_form_id  != 'nurunreport' && nuFORM.parent_form_id  != 'nurunphp'){
        
        //if(!confirm(nuTranslate("Leave This Form Without Saving?"))){
        //    return;
        //}
        
    }

    var b         = window.nuSession.reloadBreadCrumb((window.nuSession.breadCrumb.length - 1));
    
    nuBuildForm(b);
    
}



function nuErrorMessage(e, remove){

    var m         = '';
    
    if(e.length > 0){
        if(typeof(remove) == 'undefined') {
            window.nuSession.breadCrumb.pop();               //-- remove errored breadcrumb
        }
        
        if(e[0] == 'You are not currently logged in'){
                location.reload();
        }
        
        for(var i = 0 ; i < e.length ; i++){
            m     = m + e[i] + "\r";
        }
        
        alert(m);
        return true;
        
    }

    return false;

}



//==============ajax calls=========================================

function nuNewForm(sync,operation){                                                         //-- save data from form and rebuild form

    if(typeof(sync) === "undefined") {
        sync = true;
    }
    
    if(typeof(operation) === "undefined") {
        operation = 0;
    }

    nuFORM.call_type    = 'newform';
    nuFORM.cloned       = '0';

    if(typeof nuOnSave == 'function') {
        if(!nuOnSave()){
            return;
        }
    }

    nuFORM.form_data    = nuGetData();
    var isLookup = true;
    if(typeof(window.nuSession.breadCrumb[0].lookup) == 'undefined') {
        isLookup = false;
    }
        
    nuSavingProgressMessage();
    
    var request = $.ajax({
        url      : "nuapi.php",
        type     : "POST",
        data     : {nuWindow : nuFORM},
        dataType : "json",
        async    :  sync
        }).done(function(data){
            if(nuErrorMessage(data.ERRORS, false)){
                nuAbortSave();
                return;
            }

            var obj          = $.parseJSON(data.DATA);
            
            if(isLookup) {
                var w = window.nuSession.breadCrumb[0];
                if(w.lookup.substr(w.prefix.length,4) == 'btn_'){
                    document.defaultView.parent.$('#'+w.prefix+w.lookup.substr(w.prefix.length+4)).val(obj.record_id);
                    document.defaultView.parent.$('#'+w.prefix+w.lookup.substr(w.prefix.length+4)).change();
                } else {
                    document.defaultView.parent.$('#'+w.lookup).val(obj.record_id);
                    document.defaultView.parent.$('#'+w.lookup).change();
                }
            
                nuRemoveModal();
                
            } else {
            
                nuFORM.edited           = '0';
                
                if(operation == 0) {
                
                    nuFORM.record_id    = obj.record_id;
                    window.nuFormats    = $.parseJSON(obj.formats);
                    window.formatter    = new nuFormatter();

                    nuBuildEditForm(obj);
                } else if(operation == 1){
                
                    nuFORM.call_type    = 'geteditform';
                    nuFORM.record_id    = '-1';
                    nuSession.breadCrumb.pop();
                    nuBuildForm(nuFORM);
                    
                }   
            }
            
            nuSavingMessage();
            
            if(window.opener!=null){
                window.opener.window.nuGoToForm()
            }
    });
}




function nuSaveForm(sync,operation){

    nuHideSaveButtons(true);
    
    if(typeof nuOnSave   == 'function') {                                  //-- check if this custom function has been created
        if(!nuOnSave()){                                                   //-- run it if it has
            nuHideSaveButtons(false);
            return;
        }
    }

    if(typeof(sync)      === "undefined") {sync           = true;}
    if(typeof(operation) === "undefined") {operation      = 0;}

    var w                = new nuCopyJSObject(nuFORM);
    w.call_type          = 'check_edit';

    var request          = $.ajax({
    
        url      : "nuapi.php",
        type     : "POST",
        data     : {nuWindow : w},
        dataType : "json",
        }).done(function(data){

            var obj      = $.parseJSON(data.DATA);

            if(obj.user != ''){

                if (confirm('Changed by ' + obj.user + ' do you wish to over write their changes?')) {
                    nuCompleteSavingForm(sync,operation);
                }else{
                    nuHideSaveButtons(false);
                }

            }else{
                nuCompleteSavingForm(sync,operation);
            }

    });

}




function nuCompleteSavingForm(sync,operation){                             //-- save data from form and rebuild form

    nuFORM.call_type     = 'saveform';
    nuFORM.cloned        = '0';
    nuFORM.form_data     = nuGetData();
    var isLookup         = true;
    
    if(typeof(window.nuSession.breadCrumb[0].lookup) == 'undefined') {
        isLookup         = false;
    }
        
    nuSavingProgressMessage();
    
    var request         = $.ajax({
        url      : "nuapi.php",
        type     : "POST",
        data     : {nuWindow : nuFORM},
        dataType : "json",
        async    :  sync
        }).done(function(data){
            if(nuErrorMessage(data.ERRORS, false)){
                nuAbortSave();
                return;
            }

            var obj     = $.parseJSON(data.DATA);
            
            if(isLookup) {
                var w = window.nuSession.breadCrumb[0];
                if(w.lookup.substr(w.prefix.length,4) == 'btn_'){
                    document.defaultView.parent.$('#'+w.prefix+w.lookup.substr(w.prefix.length+4)).val(obj.record_id);
                    document.defaultView.parent.$('#'+w.prefix+w.lookup.substr(w.prefix.length+4)).change();
                } else {
                    document.defaultView.parent.$('#'+w.lookup).val(obj.record_id);
                    document.defaultView.parent.$('#'+w.lookup).change();
                }
            
                nuRemoveModal();
            
            } else {

                nuFORM.edited    = '0';
                
                if(operation == 0) {
                    nuFORM.record_id = obj.record_id;
                    
                    window.nuFormats = $.parseJSON(obj.formats);
                    window.formatter = new nuFormatter();

                    nuBuildEditForm(obj);
                } else if(operation == 1){
                    nuFORM.call_type    = 'geteditform';
                    nuFORM.record_id    = '-1';
                    nuSession.breadCrumb.pop();
                    nuBuildForm(nuFORM);
                } else if(operation == 2){
                    nuGoToForm(window.nuSession.breadCrumb.length-2, false);
                }   
            }
            
            nuSavingMessage();
            
            if(window.opener!=null){
                window.opener.window.nuGoToForm()
            }

            nuFORM.cloned = 0;
            
    })
    .fail(function(xhr, err) {
        alert(nuTranslate(nuFormatAjaxErrorMessage(xhr,err)));
        nuAbortSave();
        return;
    });
}


function nuPrintPDF(pCode, id, pFilename){  //-- save data from form and rebuild form

   var P               = new nuCopyJSObject(nuFORM);
    
    if(arguments.length < 3){               //-- don't create file
        var pFilename   = '';
    }

    P.call_type         = 'printpdf';
    P.parent_record_id  = pCode;
    P.form_data         = nuGetData();
    P.filename          = pFilename;
    
    if(arguments.length > 1){
        P.iframe        = 1;
    }else{
        P.iframe        = 0;
    }

    var request = $.ajax({
        url      : "nuapi.php",
        type     : "POST",
        data     : {nuWindow : P},
                async    : false,
                success  : function(data) {
                
                    var obj          = $.parseJSON(data.DATA);
                    
                    if(data.ERRORS != ''){return;}
                    
                    if(pFilename == ''){
                    
                        var pdfUrl   = 'nurunpdf.php?i='+obj.id;
                    
                        if(obj.iframe == 0){
                            window.open(pdfUrl);
                        }else{
                            $('#'+id).attr('src',pdfUrl);
                        }
                    }else{                                            //-- attach as email
                        console.log(pFilename);
                    }
                },
        dataType : "json"
        }).done(function(data){

            if(nuErrorMessage(data.ERRORS, false)){return;}
                        
    });

}



function nuOpenReportForm(pCode){

    var p = nuFORM.report_ids[pCode];
    
    if(p==undefined){
        alert('Access not allowed to this Report');
        return;
    }
    
    nuOpenForm('nurunreport', p.record, p.form, p.record, 'Run Report');
    
}


function nuOpenPHPForm(pCode){

    var p = nuFORM.php_ids[pCode];
    
    if(p==undefined){
        alert('Access not allowed to this Procedure');
        return;
    }
    
    nuOpenForm('nurunphp', p.record, p.form, p.record, 'Run Procedure');
    
}


function nuRunPHP(pCode, id, sync){
	
	sync = typeof sync !== 'undefined' ? sync : true;
    
	var P               = new nuCopyJSObject(nuFORM);
    P.call_type         = 'runphp';
    P.parent_record_id  = pCode;
    P.form_data         = nuGetData();

    if(arguments.length > 1){
        P.iframe        = 1;
    }else{
        P.iframe        = 0;
    }
    
	if(sync) {showTopLoader();}
	
    var request = $.ajax({
        url      : "nuapi.php",
        type     : "POST",
        data     : {nuWindow : P},
        async    : sync,
        success  : function(data) {

                var obj          = $.parseJSON(data.DATA);

                if(data.ERRORS != ''){return;}

                var phpUrl       = 'nurunphp.php?i='+obj.id;
                
                if(obj.iframe == 0){
                    window.open(phpUrl);
                }else{
                    setTimeout( function() { $('#'+id).attr('src',phpUrl); },0);
                    
                }
                
            },
            
        dataType : "json"
        
        }).done(function(data){
			if(sync) {hideTopLoader();}
            if(nuErrorMessage(data.ERRORS, false)){return;}
                        
    });

}

function nuRunPrintBrowse(){

    var F               = new nuCopyJSObject(nuFORM);
    F.call_type         = 'runprintbrowse';
    var request = $.ajax({
        url      : "nuapi.php",
        type     : "POST",
        data     : {nuWindow : F},
                async    : false,
                success  : function(data) {
                    var obj          = $.parseJSON(data.DATA);
                    window.open('nurunprintbrowse.php?i='+obj.id);
                },
                dataType : "json"
        }).done(function(data){

            if(nuErrorMessage(data.ERRORS)){return;}
                        
    });

}


function nuCopyJSObject(from){
    for (var key in from){
        this[key] = typeof from[key] == "object" ? new nuCopyJSObject(from[key]) : from[key];
    }
}



function nuDeleteForm(){  //-- delete data from form

    if(!confirm("Are you sure you wish to delete this record?")){return;}
    
    nuFORM.call_type    = 'deleteform';
    nuFORM.form_data    = nuGetData('delete all');

    var request = $.ajax({
        url      : "nuapi.php",
        type     : "POST",
        data     : {nuWindow : nuFORM},
        dataType : "json"
        }).done(function(data){

                        if(nuErrorMessage(data.ERRORS)){return;}

            var obj          = $.parseJSON(data.DATA);
            window.nuFormats = $.parseJSON(obj.formats);
            window.formatter = new nuFormatter();
            if(window.nuSession.breadCrumb.length == 1){
                window.opener.window.nuGoToForm()
                window.close()
            }else{
                nuGoToForm(window.nuSession.breadCrumb.length-2, false)
            }

    });
    
}



function nuCloneForm(pThis, formID, recordID){

    if(typeof nuOnClone == 'function') {
        if(!nuOnClone()){
            return;
        }
    }

    var w          = new nuWindow();
    w.form_id      = formID;
//  w.title        = $('title').html();
    w.title        = window.nuSession.breadCrumb[window.nuSession.breadCrumb.length-1].title;
    w.call_type    = 'cloneform';
    w.cloned       = '1';
    w.record_id    = recordID;
    w.tip          = 'Edit';

    window.nuSession.breadCrumb.pop();                                   //-- remove breadcrumb before another is added
    nuBuildForm(w);
    
}


function nuValidateAccess(type, code){

    w             = window.nuFORM;
    w.call_type   = 'validateaccess'
    w.validate    = type;
    w.code        = code;
    var mess      = Array();
    
    var request   = $.ajax({
        url      : "nuapi.php",
        type     : "POST",
        data     : {nuWindow : w},
        dataType : "json",
        async    : false
        }).done(function(data){
            
        mess = data.ERRORS;
            
    });
    return mess;
}



function nuValidatePDF(code){

    return nuValidateAccess('printpdf', code);
    
}


function nuValidatePHP(code){

    return nuValidateAccess('runphp', code);
    
    
}


function nuBuildForm(w){

    window.nuFORM = w;
    
    if(w.call_type == 'geteditform' || w.call_type == 'cloneform'){      //-- get information and then build edit form
        if(window.loading == false) {
            window.loading = true;
			showTopLoader();
            var request = $.ajax({
                url      : "nuapi.php",
                type     : "POST",
                data     : {nuWindow : w},
                dataType : "json",
                async    : true
                }).done(function(data){
                    window.loading = false;
                    if(nuErrorMessage(data.ERRORS, false)){hideTopLoader();nuRemoveModal();return;}

                    var obj          = $.parseJSON(data.DATA);
                    window.nuFormats = $.parseJSON(obj.formats);
                    window.formatter = new nuFormatter();
                    
                    var b = window.nuSession.breadCrumb[window.nuSession.breadCrumb.length-1];                            

                    if(b!= window.nuFORM) nuSession.setBreadCrumb(window.nuFORM);
                    
                    nuBuildEditForm(obj);
					hideTopLoader();
            });
        }
    }

    if(w.call_type == 'getbrowseform' || w.call_type == 'getlookupform' ){  //-- get information and then build browse or lookup form
            
        w.search_columns = nuBuildSearchColumnString();                     //-- list of searchable Columns
        
        if(window.loading == false) {
            window.loading = true;
			showTopLoader();
            var request = $.ajax({
                url      : "nuapi.php",
                type     : "POST",
                data     : {nuWindow : w},
                dataType : "json"
                }).done(function(data){
                    window.loading = false;
                    if(nuErrorMessage(data.ERRORS, false)){hideTopLoader();return;}

                    var obj          = $.parseJSON(data.DATA);
                    window.nuFormats = $.parseJSON(obj.formats);
                    window.formatter = new nuFormatter();
                    
                    var b = window.nuSession.breadCrumb[window.nuSession.breadCrumb.length-1];                            
                    if(b!= window.nuFORM) nuSession.setBreadCrumb(window.nuFORM);
                    
                    nuBuildBrowseForm(obj);
					hideTopLoader();
            });
        }
    }

}

function nuBuildSearchColumnString(){
    
    var cols = Array();
    
    for(i = 0 ; i < 1000 ; i++){
        
        if($('#nusearch_' + i).length == 0){                              //-- no such column number
            break;
        }else{
            if($('#nusearch_' + i).is(':checked')){
                cols.push(i);
            }            
            
        }
        
    }
    
    var searchCol = cols.join();
    
    return searchCol;
    
}

function nuLookupID(pThis){  //-- get lookup from id

    var w            = new nuWindow();
    w.call_type      = 'lookupid';
    w.title          = '';
    w.tip            = '';
    w.record_id      = pThis.value;
    w.parent_form_id = $('#'+pThis.id).attr('data-parent');
    w.lookup_id      = $('#'+pThis.id).attr('data-id');
    w.prefix         = $('#'+pThis.id).attr('data-prefix');
    w.object_id      = $('#'+pThis.id).attr('data-nuobject');
    w.form_id        = $('#'+pThis.id).attr('data-form');
    
//-- added by sc 2014-02-08

    nuGetData('create hash variables');                                 //-- set currrent Form's values as hash variables (nuFORM properties)
    
    var alreadyDefined   = Array();

    for (var key in w){
        alreadyDefined.push(key);
    }
    
    for (var key in nuFORM){
        if(alreadyDefined.indexOf(key) == -1){
            w[key] = nuFORM[key];                                   //-- add values from parent values (so they can be used as hash variables)
        }
    }
        
//-- end added by sc            
            
    var o            = $('#'+pThis.id).attr('data-nuobject');
    var oprefix      = $('#'+pThis.id).attr('data-prefix');
    var request      = $.ajax({
        url      : "nuapi.php",
        type     : "POST",
        data     : {nuWindow : w},
        dataType : "json"
        }).done(function(data){
            var obj          = $.parseJSON(data.DATA);

            $('#'+obj.prefix+              obj.lookup_id).val(obj.id)
            $('#'+obj.prefix+'code'       +obj.lookup_id).val(obj.code)
            $('#'+obj.prefix+'description'+obj.lookup_id).val(obj.description)

            $.each( obj.lookup_other_fields, function(i, n){
                    //-- begin added by br 12/02/2014: checks the type of the object and if it is a textbox remove all new line characters
                    if( $('#'+obj.prefix + i).attr('data-nuobject-type') == 'text' ) {
                        n = n.replace(/\n/g,' ');
                    }
                    //-- end added by br
                    $('#'+ obj.prefix + i).val(n);
            });
            
            eval(obj.javascript);
        });
}



function nuLookupCode(pThis){  //-- get lookup from code

    var w            = new nuWindow();
    w.call_type      = 'lookupcode';
    w.title          = '';
    w.tip            = '';
    w.record_id      = pThis.value;
    w.parent_form_id = $('#'+pThis.id).attr('data-parent');
    w.lookup_id      = $('#'+pThis.id).attr('data-id');
    w.prefix         = $('#'+pThis.id).attr('data-prefix');
    w.object_id      = $('#'+pThis.id).attr('data-nuobject');
    w.form_id        = $('#'+pThis.id).attr('data-form');

//-- added by sc 2014-02-08

    nuGetData('create hash variables');                                 //-- set currrent Form's values as hash variables (nuFORM properties)

    var alreadyDefined   = Array();

    for (var key in w){
        alreadyDefined.push(key);
    }
    
    for (var key in nuFORM){
        if(alreadyDefined.indexOf(key) == -1){
            w[key] = nuFORM[key];                                   //-- add values from parent values (so they can be used as hash variables)
        }
    }
        
//-- end added by sc            
            
    var prev = pThis._prevValue;    
    
    var request  = $.ajax({
        url      : "nuapi.php",
        type     : "POST",
        data     : {nuWindow : w},
        dataType : "json"
        }).done(function(data){
            var obj          = $.parseJSON(data.DATA);
            
            if(obj.id == 'many records' && obj.code == 'many records' && obj.description == 'many records'){
                nuOpenLookup(document.getElementById(obj.prefix+obj.lookup_id), pThis.value);   //-- open a lookup
            }else if(obj.id == '' && pThis.value != ''){
                var srch = pThis.value;
                pThis.value = '';
                nuOpenLookup(document.getElementById(obj.prefix+obj.lookup_id), '', srch);            //-- open a lookup because there was no matching record    2014-07-14 SC
            }else{
                $('#'+obj.prefix+              obj.lookup_id).val(obj.id)
                $('#'+obj.prefix+'code'       +obj.lookup_id).val(obj.code)
                $('#'+obj.prefix+'description'+obj.lookup_id).val(obj.description)
                                $.each( obj.lookup_other_fields, function(i, n){
                                
                                    if(n === null){//-- added by sc 25/02/2014
                                        n = '';
                                    }
                                    //-- begin added by br 12/02/2014: checks the type of the object and if it is a textbox remove all new line characters
                                    if( $('#'+obj.prefix + i).attr('data-nuobject-type') == 'text' ) {
                                        n = n.replace(/\n/g,' ');
                                    }
                                    //--end added by br
                                    $('#'+ obj.prefix + i).val(n)
                                });
                if( prev !== pThis.value) {     //--Ben: added to prevent javascript running multiple times over the same value.
                    eval(obj.javascript);
                    if(document.activeElement.tagName == 'BODY'){
                        $('#'+obj.prefix+'code'+obj.lookup_id).focus();
                    }
                }
                

            }
    });
    pThis._prevValue = pThis.value;

    $('.ui-menu-item').hide();
    $(pThis).blur();

}


function nuAutocomplete(e) {

        $(e).autocomplete({
            minLength: 2,
            autoFocus: false,
            delay: 300,

            source: function( request, response ) {

                //-- added by sc 2014-02-10

                var w        = new nuWindow();
                w.call_type  = 'autocomplete';
                w.title      = '';
                w.tip        = '';
                w.lookup_id  = $(e).attr('data-id');
                w.prefix     = $(e).attr('data-prefix');
                w.object_id  = $(e).attr('data-nuobject');
                w.form_id    = $(e).attr('data-form');

                nuGetData('create hash variables');                                 //-- set currrent Form's values as hash variables (nuFORM properties)

                var alreadyDefined   = Array();

                for (var key in w){
                    alreadyDefined.push(key);
                }

                for (var key in nuFORM){
                    if(alreadyDefined.indexOf(key) == -1){
                        w[key] = nuFORM[key];                                   //-- add values from parent values (so they can be used as hash variables)
                    }
                }
                        
                //-- end added by sc            
                            
                w.record_id = request.term;
                 $.ajax({
                    url      : "nuapi.php",
                    type     : "POST",
                    data     : {nuWindow : w},
                    dataType : "json"
                    }).done(function(data){
                        if (data.SUCCESS) {
                            data = $.parseJSON(data['DATA'])
                            response( data['results'] );
                        } else {
                            console.log('Error returned from autocomplete');
                        }
                    })

            },
            select: function( event, ui ) {
            
                event.preventDefault();
                if( ui.item !== null ) {
                    $(this).val(ui.item.value);

                }
                nuLookupCode(this);
                
            },
            change: function( event, ui ) {     

            //--Ben: Using onchange for autocomplete caused issues with setting of values from autocomplete selections (both select and change were being executed)
                event.preventDefault();
                event.stopPropagation();
                //nuLookupCode(this);
            }
        }).data("autocomplete")._renderItem = function(ul,item){
            return $("<li>")
            .append($("<a>").html(item.label))
            .appendTo(ul);
        };

     
}

function nuLogin(u, p, k){

    var w              = new nuWindow();
    w.form_id          = 'nuindex';
    w.call_type        = 'login';
    w.record_id        = k == '' ? '-1' : k;
    w.title            = nuTranslate('Home');
    w.tip              = nuTranslate('Desktop');
    window.nuFORM      = w;
    
    if(arguments.length == 0){
    
        w.username     = $('#u').val();
        w.password     = $('#p').val();
        $("#modal_div").remove();
        $("#userpass").remove();
        $("#userpass1").remove();
        
    }else{
    
        w.username     = u;
        w.password     = p;
        
    }

    var request = $.ajax({
        url: "nuapi.php",
        type: "POST",
        data: {nuWindow : w},
        dataType: "json"
        }).done(function(data){
            if(data.DATA['session_id'] == 'Login Failed'){
                alert(nuTranslate('Your username or password was incorrect'));
                toggleModalMode();
                $('#p').val('');
                $('#u').focus();
            }else{
                window.nuFORM.form_id = data.DATA['index_id'];
                nuSession.setBreadCrumb(window.nuFORM);
                
                if(arguments.length == 0){
                    toggleModalMode();
                }
                
                window.nuSession.setSessionID(data.DATA['session_id']);
                w.call_type           = 'geteditform';
                w.record_id           = '-1';
                                w.session_id          = data.DATA['session_id'];
                                if(!window.nuTimeout){
                                    var request = $.ajax({
                                        url      : "nuapi.php",
                                        type     : "POST",
                                        data     : {nuWindow : w},
                                        dataType : "json"
                                        }).done(function(data){

                                            if(nuErrorMessage(data.ERRORS)){return;}

                                            var obj          = $.parseJSON(data.DATA);
                                            window.nuFormats = $.parseJSON(obj.formats);
                                            window.formatter = new nuFormatter();
                                            nuBuildEditForm(obj);
                                    });
                                }

            }
    });
    
    nuFORM.password = 'xxxx';

}



function nuMoveObject(id, top, left){

    var p  =  $('#'+id).closest("[id^=nu_tab_area]").attr('id');
    var t  = document.createElement('table');                             //-- create Object holder 
    var e  = document.createElement('div');                               //-- create Object holder 
    var objectID = $('#'+id).attr('data-nuobject');
    t.setAttribute('id',   'nu_table_'  + id);
    e.setAttribute('id',   'nu_holder_' + id);
    e.setAttribute('data-nuobject',       objectID);
    $('#' + p).append(e);
    $('#' + e.id).append(t)
   .css({
        'top'          : top  + 'px',
        'left'         : left + 'px',
        'position'     : 'absolute',
        'border-style' : 'none'
    })
   .addClass('nuSelectedTab');
   
    $('#' + e.id).mousedown(function() {
    
        var cover = window.nuOTP[nuDraggableObjectProperties(e.id.substr(10), 'sob_all_type')].cover
        if(!$('#' + cover + e.id.substr(10)).hasClass('nuSelected')){

            if(!window.nuControlKey){
                nuAddSelectableObjects();
            }
            
            $('#nuObjectList option[value=' + e.id.substr(10) + ']').attr('selected', true);
            $('#nuObjectList').change();
            
        }

    });

    $('#' + t.id).append($('#tr_'+id));
	
	if (window.top === window.self) {
		$('#title_' + id).css( 'width', nuSetTitleWidth(id) + 'px');
    }
}


function nuSetTitleWidth(i){
    
    var h = "<div id='nuTestWidth' style='position:absolute;visible:hidden;height:auto;width:auto'>" + $('#title_' + i).html() + "</div>";
    $('body').append(h);
    var w = parseInt($('#nuTestWidth').css('width'));
    $('#nuTestWidth').remove();
    return w + 2;
    
}   



function nuIsGA(){

    if(window.nuDenied != ''){
    
        if(nuIsSystem()){
            return false;                                    //-- stop access to system tables
        }else{
            return window.nu_user_name == 'globeadmin';
        }
        
    }else{
        return window.nu_user_name == 'globeadmin';
    }
    

}
    

function nuIsSystem(){

    return nuFORM.form_id.substring(0,2) == 'nu' && nuFORM.form_id != 'nuhome';
    
}   
    
    
function nuIsMoveable(){
    
    return window.nuMoveable;

}
    
    
function nuSavingProgressMessage(){
    var e = document.createElement('div');
    e.setAttribute('id', 'nuProgressSaved');
    $('#nuHolder').append(e);
    $('#' + e.id).html('<img src=\'ajax-loader.gif\'/>')
    .addClass( 'nuSaveMessageProgress')
    .show();
//  $('input[id^="nuButton"]').hide();
}    
  
function nuSavingMessage(){
    $("#nuProgressSaved").hide();
    var e = document.createElement('div');
    e.setAttribute('id', 'nuNowSaved');
    $('#nuHolder').append(e);
    $('#' + e.id).html('Record Saved')
    .addClass( 'nuSaveMessage');
    $("#nuNowSaved").fadeToggle(3000);
} 

function nuAbortSave() {
    $("#nuProgressSaved").hide();
    nuHideSaveButtons(false);
}   

function nuObjectToString(variable,i) {

    if (variable != null) {

            var string = '';
    
              if(typeof variable == 'object'){ 
                     string += 'Object ( <ul style="list-style:none;">';
                     var key;
                 for(key in variable) {
                          if (variable.hasOwnProperty(key)) {
                                string += '<li>['+key+'] => ';
                            string += nuObjectToString(variable[key],i+1);
                            string += '</li>';
                          }
                    }
                    string += '</ul> )';
            } else {
                    string = variable.toString();
            }
          return string;

    } else {
        return 'empty';
    }
}






function nuPrint_r(o){


    var s = String();
    $.each(o, function(key, element){
    
        if (typeof element === "object" || typeof element === "array"){
            s = s + nuPrint_r(element);
        }else{
            s = s + '\n <br>key   : ' + key + '\n<br>value : ' + element;
        }
        
    });
    
    return s + '\n <br>';
    
}


function nuInsideSubform(p) {

    if (p.split('_')[1] == 'nuRow') {
        return true;    
    } else {
        return false;   
    }
}


function nuEmailPDF(pCode, pEmailTo, pAction, pSubject, pMessage, pCallType, pFileName) {
    if (typeof pAction == 'undefined' || pAction == ''){nuEmail(pCode, '', pEmailTo, pSubject, pMessage, pFileName);}  //-- If pAction is defined, PHP Code
    else {nuEmail('', pAction, pEmailTo, pSubject, pMessage, pFileName);}
}
   


function nuEmail(pPDF, pPHP, pEmailTo, pSubject, pMessage, pFileName) {

    if (typeof pPHP      == 'undefined'){var pPHP      = '';}
    if (typeof pEmailTo  == 'undefined'){var pEmailTo  = '';}
    if (typeof pSubject  == 'undefined'){var pSubject  = '';}
    if (typeof pMessage  == 'undefined'){var pMessage  = '';}
    if (typeof pFileName == 'undefined'){var pFileName = 'Report.pdf';} //-- Default name for Reports

    nuSetHash('nu_pdf_code', pPDF);                                     //-- set up some hash variables
    nuSetHash('nu_php_code', pPHP);
    nuSetHash('nu_email_to', pEmailTo);
    nuSetHash('nu_email_subject', pSubject);
    nuSetHash('nu_email_message', pMessage);
    nuSetHash('nu_email_file_name', pFileName);
    nuSetHash('nu_previous_record_id', nuFORM.record_id);

    nuGetData('create hash variables');                                 //-- set currrent Form's values as hash variables so they can be referenced as if they were on the email Form.
    
    nuOpenFormInFrame('nuemail','-1');

}

function nuFormatAjaxErrorMessage(jqXHR, exception) {

    if (jqXHR.status === 0) {
        return ('Not connected.\nPlease verify your network connection.');
    } else if (jqXHR.status == 404) {
        return ('The requested page not found. [404]');
    } else if (jqXHR.status == 500) {
        return ('Internal Server Error [500].');
    } else if (exception === 'parsererror') {
        return ('Requested JSON parse failed.');
    } else if (exception === 'timeout') {
        return ('Time out error.');
    } else if (exception === 'abort') {
        return ('Ajax request aborted.');
    } else {
        return ('Uncaught Error.\n' + jqXHR.responseText);
    }
}

/*
    Uses: 
        nuAjax('PROCEDURE1');
        nuAjax('PROCEDURE1', 'callbackFuncName');
        nuAjax('PROCEDURE1', {async:true, data: {id: '1234'}, done: function(){}});
        nuAjax('PROCEDURE1', 'callbackFuncName', {async:true, data: {id: '1234'}});
*/
function nuAjax(pCode, pFunctionName, pOptions){

    if(typeof pFunctionName === 'object' && typeof pOptions === 'undefined'){
        pOptions = pFunctionName;
        pFunctionName = null;
    }
    var options = typeof pOptions !== 'undefined' ? pOptions : {};
    var defaultData = {};
    if(options.hasOwnProperty('data')){
        defaultData = options.data;
        defaultData.nuWindow = {session_id: nuFORM.session_id};
    } else {
        var P       = new nuCopyJSObject(nuFORM);
        P.form_data = nuGetData();
        P.phpCode   = pCode;
        defaultData = {nuWindow : P};
    }
    var defaultAsync = options.hasOwnProperty('async') ? options.async : false;
    var defaultDone = function(data){
        if(nuErrorMessage(data.ERRORS, false)){
            return;
        }
        if(window.hasOwnProperty(pFunctionName)){
            if(typeof window[pFunctionName] === 'function'){
                window[pFunctionName](data.DATA);
            }
        }
    }
    if(options.hasOwnProperty('done')){
        if(typeof options.done === 'function'){
            defaultDone = options.done;
        }
    }
    var defaultFail = function(){};
    if(options.hasOwnProperty('fail')){
        if(typeof options.fail === 'function'){
            defaultFail = options.fail;
        }
    }
    var defaultAlways = function(){};
    if(options.hasOwnProperty('always')){
        if(typeof options.always === 'function'){
            defaultAlways = options.always;
        }
    }

    var request = $.ajax({
    url      : "nucallsecure.php?c="+pCode,
    type     : "POST",
    data     : defaultData,
    dataType : "json",
    async    : defaultAsync
    }).done(defaultDone).fail(defaultFail).always(defaultAlways);

}

function nuSetHash(name, value){
    nuFORM[name] = value;
}

function nuGetHash(name){
    return nuFORM[name];
}


function nuRemoveModal(){

    document.defaultView.parent.$('#nuModal').remove(); 
    document.defaultView.parent.$('#nuDrag').remove();

}

function nuTimeStamp(value){

    if(arguments.length == 1){
        localStorage.setItem(nuFORM.session_id, value);
    }else{
        return localStorage[nuFORM.session_id];
    }

}


function nuObjectColors(o){

    return 'nu_' + o;
    
}


function nuFieldTitle(f, l){                   //-- formats f ('cus_street_name' becomes 'Street Name' if other strings in l start with cus)

    var t = Array();

    for(var i = 0 ; i < l.length ; i ++){
    
        if(f != l[i] && f.split('_').length > 1 && f.split('_')[0] == l[i].split('_')[0]){
        
            for(var s = 1 ; s < f.split('_').length ; s ++){
                if(f.split('_')[s] != 'id') {

                    t.push(f.split('_')[s].charAt(0).toUpperCase() + f.split('_')[s].slice(1));
                }

            }
            
            return t.join(' ');
            
        }
        
    }

    for(var s = 0 ; s < f.split('_').length ; s ++){
    
        if(f.split('_')[s] != 'id') {
            t.push(f.split('_')[s].charAt(0).toUpperCase() + f.split('_')[s].slice(1));
        }

    }
    
    return t.join(' ');
        
}

function nuFile(c){

    return 'nufileget.php?' + c + '&t=' + new Date().getTime();

}


function nuObjectDraggableDialog(w) {

    nuCloseModal();
    $(".nuActionButton").remove();
    
    var e = document.createElement('div');              //-- create draggable div
    e.setAttribute('id', 'nuDrag');
    $('body').append(e);
    $('#' + e.id).css({
        'width'            : w,
        'height'           : 450,
        'top'              : 50,
        'left'             : 20,
        'position'         : 'absolute',
        'background-color' : '#E1E8EA',
        'z-index'          : 5000,
        'border-width'     : '0px',
        'border-color'     : '#01A6F5',
        'border-style'     : 'solid',
        'filter'           : 'Alpha(Opacity=100)',
        'opacity'          : '1',
        'box-shadow'       : '5px 5px 5px #888888'
    })

    var e = document.createElement('div');              //-- create draggable div
    e.setAttribute('id', 'nuDragBar');
    $('#nuDrag').append(e);
    $('#' + e.id).css({
        'width'                   : w+'px',
        'height'                  : '25px',
        'top'                     : '0px',
        'left'                    : '0px',
        'position'                : 'absolute',
        'background-color'        : '#B6B6B6',
        'z-index'                 : 5000
    })
    .mousedown(function() {
        $('#nuDrag').draggable();
    })
    .mouseup(function() {
        $('#nuDrag').draggable("destroy");
    });

    var e = document.createElement('div');              //-- create draggable div
    e.setAttribute('id', 'nuDragBarClose_');
    $('#nuDragBar').append(e);
    $('#' + e.id).css({
        'width'            : '20px',
        'height'           : '20px',
        'top'              : '1px',
        'left'             : '0px',
        'position'         : 'absolute',
        'background-color' : '#E1E8EA',
        'z-index'          : 5000
    })
    .addClass('nuClose')
    .html('&#10006;')
    .mousedown(function() {
        nuCloseModal();
        $('#nuMoveable').attr('src', 'numove_black.png');
        window.nuMoveable = false;
        $(".nuSelected").removeClass('nuSelected');
        $("#nuObjectList option").remove();
        $("[id^='nu_see_through_']").remove(); 
    });
    
}


function nuObjectMover(){
    
    var e          = document.createElement('select');       //-- create a new listbox object
    e.multiple     = 'multiple';
    e.setAttribute('id', 'nuObjectList');

    $('#nuDrag').append(e);
    $('#nuObjectList').css({
        'width'            : '280px',
        'height'           : '200px',
        'top'              : '35px',
        'left'             : '10px',
        'position'         : 'absolute',
        'font-family'      : 'Lucida Console',
        'z-index'          : 5000
    })
    .change(function() {
            nuHighlightSelected();
    });

    $("[id^='nuButton']").attr( "disabled", "disabled" );                                                       //-- remove see through divs

    $('#nuObjectList').change();
    
    nuAddSelectableObjects();

    nuMoverAdjustVerButton(240, 10);
    nuMoverAdjustHorButton(275, 10);
    nuMoverMoveUpButton(380, 10);
    nuMoverMoveDownButton(415, 10);
    nuMoverUnselectButton(240, 170);
    nuMoverAlignLeftButton(275, 170);
    nuMoverAlignRightButton(310, 170);
    nuMoverAlignTopButton(345, 170);
    nuMoverAlignBottomButton(380, 170);
    nuMoverSaveButton(415, 170);
    
}


function nuMoveUpInObjectList(){

    $('#nuObjectList option:selected').each(function(){
        $(this).insertBefore($(this).prev());
    });
    
    nuReorderTab();

}

function nuMoveDownInObjectList(){

    $('#nuObjectList option:selected').each(function(){
        $(this).insertAfter($(this).next());
    });
    
    nuReorderTab();

}

function nuReorderTab(){

    var o = 10;

    $("#nuObjectList option").each(function() {

        nuDraggableObjectProperties(this.value, 'object_number', o);
        o = o + 10;
        
    });
    
    window.nuDraggableObjects.sort(function(A, B){return ((A.tab_number*1000)+(A.object_number*1)) - ((B.tab_number*1000)+(B.object_number*1));});
    window.nuTabsReordered = true;
    
}



function nuMoverAdjustHorButton(t, l){

    var e = document.createElement('input');                           //-- create button
    e.setAttribute('id', 'nuMoverAdjustHor');
    e.setAttribute('type', 'button');
    e.setAttribute('value',   'Space Horizontally');
    e.setAttribute('title',   'Adjust All Highlighted Objects Horizontally');
    e.setAttribute('onclick', 'nuMoverAdjustHorClick()');
    $('#nuActionButtonHolder').append(e);
    $('#' + e.id).addClass('nuButton');
    $('#nuDrag').append(e);
    $('#' + e.id).css({
        'width'            : 120,
        'height'           : 30,
        'top'              : t,
        'left'             : l,
        'z-index'          : 5000,
        'position'         : 'absolute',
    })

}


function nuMoverAdjustHorClick(){

    var ltor    = Array();
    var rtol    = Array();
    var tw      = 0;
    var l, r, w, o, e, g, s;

    $("#nuObjectList > option:selected").each(function() {
    
        o       = nuDraggableObjectProperties(this.value);
        e       = new nuEmptyObject();
        e.i     = this.value;
        e.l     = Number(o.holder_left) + Number(o.title_width)                                              //-- left
        e.w     = parseInt($('#' + window.nuOTP[o.sob_all_type].cover + this.value).css('width'));           //-- width
        e.r     = e.l + e.w;                                                                                 //-- right
        tw      = tw + e.w;                                                                                  //-- total width
        
        ltor.push(e);
        rtol.push(e);

    });


    ltor.sort(function(A, B){return A.r - B.r;});
    rtol.sort(function(A, B){return B.l - A.l;});

    
    l           = ltor[0].l;                                                                                 //-- left position of most left object    _---- ----- -----
    r           = rtol[0].r;                                                                                 //-- right position of most right object  ----- ----- ----_
    g           = ((r-l) - tw) / (rtol.length -1);                                                           //-- new calculated gap between objects   -----_-----_-----
    s           = ltor[0].l;                                                                                 //-- starting left                        _---- ----- -----


    for(var i = 0 ; i < ltor.length - 1 ; i++) {                                                             //-- reposition all Objects ordered by left most (except the last one)

        o       = nuDraggableObjectProperties(ltor[i].i);
        if(i > 0){nuMoveObject(ltor[i].i, o.holder_top, s - o.title_width);}                                 //-- move object
        s       = s + g + ltor[i].w;
    
    }
                    
    nuRecalculateCoordinates();

}


function nuMoverAdjustVerButton(t, l){

    var e = document.createElement('input');                           //-- create button
    e.setAttribute('id', 'nuMoverAdjustVer');
    e.setAttribute('type', 'button');
    e.setAttribute('value',   'Space Vertically');
    e.setAttribute('title',   'Adjust All Highlighted Objects Vertically');
    e.setAttribute('onclick', 'nuMoverAdjustVerClick()');
    $('#nuActionButtonHolder').append(e);
    $('#' + e.id).addClass('nuButton');
    $('#nuDrag').append(e);
    $('#' + e.id).css({
        'width'            : 120,
        'height'           : 30,
        'top'              : t,
        'left'             : l,
        'z-index'          : 5000,
        'position'         : 'absolute',
    })

}


function nuMoverAdjustVerClick(){

    var t = 10000000;
    var b = 0;
    var h = 0;
    var n = 0;
    var a = Array();
    var o;

    $("#nuObjectList > option:selected").each(function() {
    
        o       = nuDraggableObjectProperties(this.value);
        t       = Math.min(t, Number(o.holder_top) );                                                      //-- calculate highest top
        b       = Math.max(b, Number(o.holder_top) + Number(o.object_height));                            //-- calculate lowest bottom
        h       = h + Number(o.object_height);                                                                //-- total height of objects
        n       = n + 1;                                                                                      //-- number of objects
        
        var top = new nuEmptyObject();
        top.id  = o.sob_all_name;
        top.t   = Number(o.holder_top);
        
        a.push(top);

    });

    var s = a.sort(function(A, B){return A.t - B.t;});
    var newGap = (b-t-h) / (n-1);
    var newTop  = t;
    
    for(var i = 0 ; i < s.length - 1 ; i++) {                      //-- reposition all Objects ordered by highest (except the last one)

        o       = nuDraggableObjectProperties(s[i].id);
        nuMoveObject(s[i].id, newTop, s[i].holder_left);                                      //-- move object
        newTop  = newTop + newGap + Number(o.object_height);
    
    }
                    
    nuRecalculateCoordinates();

}


function nuMoverMoveLeftButton(t, l){

    var e = document.createElement('input');                           //-- create button
    e.setAttribute('id', 'nuMoverMoveLeft');
    e.setAttribute('type', 'button');
    e.setAttribute('value',   'Move Left');
    e.setAttribute('title',   'Move All Highlighted Objects Left');
    e.setAttribute('onclick', 'nuMoverMoveClick(0, -1)');
    $('#nuActionButtonHolder').append(e);
    $('#' + e.id).addClass('nuButton');
    $('#nuDrag').append(e);
    $('#' + e.id).css({
        'width'            : 120,
        'height'           : 30,
        'top'              : t,
        'left'             : l,
        'position'         : 'absolute',
    })

}


function nuMoverMoveRightButton(t, l){

    var e = document.createElement('input');                           //-- create button
    e.setAttribute('id', 'nuMoverMoveRight');
    e.setAttribute('type', 'button');
    e.setAttribute('value',   'Move Right');
    e.setAttribute('title',   'Move All Highlighted Objects Right');
    e.setAttribute('onclick', 'nuMoverMoveClick(0, 1)');
    $('#nuActionButtonHolder').append(e);
    $('#' + e.id).addClass('nuButton');
    $('#nuDrag').append(e);
    $('#' + e.id).css({
        'width'            : 120,
        'height'           : 30,
        'top'              : t,
        'left'             : l,
        'z-index'          : 5000,
        'position'         : 'absolute',
    })

}



function nuMoverMoveDownButton(t, l){

    var e = document.createElement('input');                           //-- create button
    e.setAttribute('id', 'nuMoverMoveDown');
    e.setAttribute('type', 'button');
    e.setAttribute('value',   'Move Down List');
    e.setAttribute('title',   'Rearrange Tab Order');
    e.setAttribute('onclick', 'nuMoveDownInObjectList()');
    $('#nuActionButtonHolder').append(e);
    $('#' + e.id).addClass('nuButton');
    $('#nuDrag').append(e);
    $('#' + e.id).css({
        'width'            : 120,
        'height'           : 30,
        'top'              : t,
        'left'             : l,
        'position'         : 'absolute',
    })

}


function nuMoverMoveUpButton(t, l){

    var e = document.createElement('input');                           //-- create button
    e.setAttribute('id', 'nuMoverMoveUp');
    e.setAttribute('type', 'button');
    e.setAttribute('value',   'Move Up List');
    e.setAttribute('title',   'Rearrange Tab Order');
    e.setAttribute('onclick', 'nuMoveUpInObjectList()');
    $('#nuActionButtonHolder').append(e);
    $('#' + e.id).addClass('nuButton');
    $('#nuDrag').append(e);
    $('#' + e.id).css({
        'width'            : 120,
        'height'           : 30,
        'top'              : t,
        'left'             : l,
        'position'         : 'absolute',
    })

}


function nuMoverUnselectButton(t, l){

    var e = document.createElement('input');                           //-- create button
    e.setAttribute('id', 'nuMoverUnselect');
    e.setAttribute('type', 'button');
    e.setAttribute('value',   'UnSelect All');
    e.setAttribute('title',   'Unselect All Objects');
    e.setAttribute('onclick', 'nuMoverUnselectClick()');
    $('#nuActionButtonHolder').append(e);
    $('#' + e.id).addClass('nuButton');
    $('#nuDrag').append(e);
    $('#' + e.id).css({
        'width'            : 120,
        'height'           : 30,
        'top'              : t,
        'left'             : l,
        'z-index'          : 5000,
        'position'         : 'absolute',
    })

}

function nuMoverUnselectClick(){

    nuAddSelectableObjects();
    $("#nuObjectList").change();
    

}



function nuMoverAlignLeftButton(t, l){

    var e = document.createElement('input');                                                   //-- create button
    e.setAttribute('id', 'nuMoverAlignLeft');
    e.setAttribute('type', 'button');
    e.setAttribute('value',   'Align To Left');
    e.setAttribute('title',   'Align All Highlighted Objects To Left');
    e.setAttribute('onclick', 'nuMoverAlignLeftClick()');
    $('#nuActionButtonHolder').append(e);
    $('#' + e.id).addClass('nuButton');
    $('#nuDrag').append(e);
    $('#' + e.id).css({
        'width'            : 120,
        'height'           : 30,
        'top'              : t,
        'left'             : l,
        'z-index'          : 5000,
        'position'         : 'absolute',
    })

}


function nuMoverAlignLeftClick(){

    var r = 10000000;
    var o = 0;

    $("#nuObjectList > option:selected").each(function() {
    
        o = nuDraggableObjectProperties(this.value);
        r = Math.min(r, Number(o.holder_left) + Number(o.title_width));                                                          //-- calculate left

    });

    $("#nuObjectList > option:selected").each(function() {
    
        var l = r - nuDraggableObjectProperties(this.value, 'title_width');
        nuMoveObject(this.value, nuDraggableObjectProperties(this.value, 'holder_top'), l);                                      //-- move object
        
    });
                    
    nuRecalculateCoordinates();

}


function nuMoverAlignRightButton(t, l){

    var e = document.createElement('input');                           //-- create button
    e.setAttribute('id', 'nuMoverAlignRight');
    e.setAttribute('type', 'button');
    e.setAttribute('value',   'Align To Right');
    e.setAttribute('title',   'Align All Highlighted Objects To Right');
    e.setAttribute('onclick', 'nuMoverAlignRightClick()');
    $('#nuActionButtonHolder').append(e);
    $('#' + e.id).addClass('nuButton');
    $('#nuDrag').append(e);
    $('#' + e.id).css({
        'width'            : 120,
        'height'           : 30,
        'top'              : t,
        'left'             : l,
        'z-index'          : 5000,
        'position'         : 'absolute',
    })

}


function nuMoverAlignRightClick(){

    var r = 0;
    var o = 0;

    $("#nuObjectList > option:selected").each(function() {
    
        o = nuDraggableObjectProperties(this.value);
        r = Math.max(r, Number(o.holder_left) + Number(o.holder_width));                                                         //-- calculate right

    });

    $("#nuObjectList > option:selected").each(function() {
    
        var l = r - nuDraggableObjectProperties(this.value, 'holder_width');
        nuMoveObject(this.value, nuDraggableObjectProperties(this.value, 'holder_top'), l);                                      //-- move object
        
    });
                    
    nuRecalculateCoordinates();
                    
}



function nuMoverAlignBottomButton(t, l){

    var e = document.createElement('input');                           //-- create button
    e.setAttribute('id', 'nuMoverAlignBottom');
    e.setAttribute('type', 'button');
    e.setAttribute('value',   'Align To Bottom');
    e.setAttribute('title',   'Align All Highlighted Objects To Bottom');
    e.setAttribute('onclick', 'nuMoverAlignBottomClick()');
    $('#nuActionButtonHolder').append(e);
    $('#' + e.id).addClass('nuButton');
    $('#nuDrag').append(e);
    $('#' + e.id).css({
        'width'            : 120,
        'height'           : 30,
        'top'              : t,
        'left'             : l,
        'z-index'          : 5000,
        'position'         : 'absolute',
    })

}


function nuMoverAlignBottomClick(){

    var h = 0;
    var o = 0;

    $("#nuObjectList > option:selected").each(function() {
    
        o = nuDraggableObjectProperties(this.value);
        h = Math.max(h, Number(o.holder_top) + Number(o.holder_height));                                                         //-- calculate bottom
    });

    $("#nuObjectList > option:selected").each(function() {
        var t = h - nuDraggableObjectProperties(this.value, 'holder_height');
        nuMoveObject(this.value, t, nuDraggableObjectProperties(this.value, 'holder_left'));                                     //-- move object
        
    });
                    
    nuRecalculateCoordinates();
                    
}


function nuMoverAlignTopButton(t, l){

    var e = document.createElement('input');                           //-- create button
    e.setAttribute('id', 'nuMoverAlignTop');
    e.setAttribute('type', 'button');
    e.setAttribute('value',   'Align To Top');
    e.setAttribute('title',   'Align All Highlighted Objects To Top');
    e.setAttribute('onclick', 'nuMoverAlignTopClick()');
    $('#nuActionButtonHolder').append(e);
    $('#' + e.id).addClass('nuButton');
    $('#nuDrag').append(e);
    $('#' + e.id).css({
        'width'            : 120,
        'height'           : 30,
        'top'              : t,
        'left'             : l,
        'z-index'          : 5000,
        'position'         : 'absolute',
    })

}


function nuMoverAlignTopClick(){

    var h  = 1000000;

    $("#nuObjectList > option:selected").each(function() {
    
        h = Math.min(h,nuDraggableObjectProperties(this.value, 'holder_top'));                               //-- calculate top
        
    });

    $("#nuObjectList > option:selected").each(function() {
    
        nuMoveObject(this.value, h, nuDraggableObjectProperties(this.value, 'holder_left'));                 //-- move object

    });

    nuRecalculateCoordinates();

}


function nuMoverSaveButton(t, l){

    var e = document.createElement('input');                           //-- create button
    e.setAttribute('id', 'nuMoveSave');
    e.setAttribute('type', 'button');
    e.setAttribute('value',   'Save');
    e.setAttribute('title',   'Save all Moved Objects');
    e.setAttribute('onclick', 'nuMoveSaveClick()');
    $('#nuActionButtonHolder').append(e);
    $('#' + e.id).addClass('nuButton');
    $('#nuDrag').append(e);
    $('#' + e.id).css({
        'width'            : 120,
        'height'           : 30,
        'top'              : t,
        'left'             : l,
        'position'         : 'absolute',
        'color'            : 'red',
    })

}

function nuMoveSaveClick(){

    var w                    = new nuCopyJSObject(nuFORM);
    w.call_type              = 'savemovedobjects';
    w.moved_objects          = Array();


    window.nuDraggableObjects.forEach(function(a) {                      //-- get all Objects that have been moved

        if(a.has_been_moved == 1 || window.nuTabsReordered){
            var o            = new nuEmptyObject();
            o.id             = a.zzzsys_object_id;
            o.top            = a.holder_top;
            o.left           = a.holder_left;
            o.order          = a.object_number; 
            w.moved_objects.push(o);
        }
    
    });

    var request = $.ajax({
        url      : "nuapi.php",
        type     : "POST",
        data     : {nuWindow : w},
        dataType : "json",
        async    : false
        }).done(function(data){

            if(nuErrorMessage(data.ERRORS, false)){nuRemoveModal();return;}

            var obj          = $.parseJSON(data.DATA);
            window.nuFormats = $.parseJSON(obj.formats);
            window.formatter = new nuFormatter();
            var b            = window.nuSession.breadCrumb[window.nuSession.breadCrumb.length-1];                            

            if(b!= window.nuFORM) nuSession.setBreadCrumb(window.nuFORM);
            
            nuBuildEditForm(obj);
    });
    
}


function nuHighlightSelected(){

    nuDraggableObjectProperties(-1, 'is_selected', 0);         //-- has not been selected
    
    $(".nuSelected").removeClass('nuSelected');
    
    $("#nuObjectList > option:selected").each(function() {

        nuDraggableObjectProperties(this.value, 'holder_height', parseInt($('#nu_holder_' + this.value).css('height')));
        nuDraggableObjectProperties(this.value, 'holder_width', parseInt($('#nu_holder_' + this.value).css('width')));
        nuDraggableObjectProperties(this.value, 'is_selected', 1);
        $('#td_right_' + this.value).addClass('nuSelected');
        if(nuDraggableObjectProperties(this.value, 'sob_all_type') == 'subform'){
            $('#scroll_' + this.value).addClass('nuSelected');                                  //-- for Subform
        }
        if(nuDraggableObjectProperties(this.value, 'sob_all_type') == 'browse'){
            $('#nu_holder_' + this.value).addClass('nuSelected');                               //-- for Browse
        }
        $('#nu_see_through_' + this.value).css('cursor', 'move');
            
    });
    
    $('#nuMoverUnselect').focus();

}


function nuCloseModal(){

    $('#nuModal').remove();
    $('#nuDrag').remove();
    
}

function nuAddSelectableObjects(){

    if($('#nuObjectList').length == 0){return;}                                                  //-- object dialog is not open

    var i         = String();
    var t         = String();
    var o         = String();
    var p         = String();
    var tab       = nuDisplayedTabNo();

    $(".nuSelected").removeClass('nuSelected');
    $("#nuObjectList option").remove();
    $("[id^='nu_see_through_']").remove();                                                       //-- remove see through divs
    
    window.nuDraggableObjects.forEach(function(a) {
    
        i         = a.sob_all_name;                                                              //-- id
        o         = a.sob_all_type;                                                              //-- type
        t         = o.toUpperCase() + ': ' + i;

        var vis   = $("#" + a.sob_all_name).css('visiblity') != 'hidden';                        //-- visible
        var intab = tab == nuDraggableObjectProperties(a.sob_all_name, 'tab_number');            //-- in this tab

        if(vis && intab){
            $("#nuObjectList").append("<option value='"+ a.sob_all_name +"'>"+t+"</option>") ;
            nuPlaceInHolder(a.sob_all_name);

            var n  = a.sob_all_name;
            var h  = window.nuOTP[a.sob_all_type].cover  + a.sob_all_name;
            var i  = 'nu_see_through_'                   + a.sob_all_name;
            var dW = parseInt($('#' +  window.nuOTP[a.sob_all_type].cover + n).css( 'width'))
            
            if($('#code' + n).length > 0){
                dW = dW + parseInt($('#code' + n).css( 'width')) + 2;
            }
            if($('#description' + n).length > 0){
                dW = dW + parseInt($('#description' + n).css( 'width')) + 2;
            }
            if($('#btn_' + n).length > 0){
                dW = dW + parseInt($('#btn_' + n).css( 'width')) + 2;
            }
            
            var e  = document.createElement('div');       //-- cover object with a see through div
            e.setAttribute('id',  i);
            $('#' + h).css('overflow', 'hidden');
            $('#' + h).append(e);
            $('#' + i).css( 'background-color', '');
            $('#' + i).css( 'position', 'absolute');
            $('#' + i).css( 'z-index', 1000);
            $('#' + i).css( 'top',  '0px');
            $('#' + i).css( 'left', (parseInt(nuDraggableObjectProperties(a.sob_all_name, 'title_width')) + 4) + 'px' );
            $('#' + i).css( 'width', $('#' + h).css('width'));
            $('#' + i).css( 'height', $('#' + h).css('height'));

            $('#nu_holder_' + a.sob_all_name).draggable({

                handle: '#' + i,

                drag: function( event, ui ){
                    if(!window.nuMoveable){
                        event.preventDefault();
                        return;
                    }
                    nuMoveSelected(this.id);
                },

                stop: function() { 
                
                    nuRecalculateCoordinates();
                    
                }
            });

        }
        
    });

}


function nuRecalculateCoordinates(){
    
    $("#nuObjectList > option:selected").each(function() {

        var pos              = $('#nu_holder_' + this.value).position();
        nuDraggableObjectProperties(this.value, 'holder_top',     pos.top);
        nuDraggableObjectProperties(this.value, 'holder_left',    pos.left);
        nuDraggableObjectProperties(this.value, 'has_been_moved', 1);
            
    });

}
    
function nuMoveSelected(id){

    var p  = $('#' + id).position();
    var t  = p.top -  Number(nuDraggableObjectProperties(id.substr(10), 'holder_top'));
    var l  = p.left - Number(nuDraggableObjectProperties(id.substr(10), 'holder_left'));
    var nt = 0;
    var nl = 0;
    
    $("#nuObjectList > option:selected").each(function() {
    
        var nt  = Number(nuDraggableObjectProperties(this.value, 'holder_top'))  + t;
        var nl  = Number(nuDraggableObjectProperties(this.value, 'holder_left')) + l;
        nuDraggableObjectProperties(this.value, 'holder_top',  nt);
        nuDraggableObjectProperties(this.value, 'holder_left', nl);
        nuDraggableObjectProperties(this.value, 'is_selected', 1);
        $('#nu_holder_' + this.value).css('top',  nt);
        $('#nu_holder_' + this.value).css('left',  nl);
        
    });
    

}
    

function nuDisplayedTabNo(){

    var t;
    var v;
    
    $("[id^='nu_tab_area']").each(function(x) {

        t = $("[id^='nu_tab_area']")[x].id;

        if($('#' + t).css('visibility') == 'visible'){
            v = t.substr(11);
        }
        
    });
    
    return v;
    
}

function nuDraggableObjectProperties(i, p, v){

    var i = i;
    var p = p;
    var v = v;
    
// (i) if i = -1 then update all elements in the array
// (p) if p === undefined return all properties in an array

    window.nuDraggableObjects.forEach(function(a) {

        if(a[1] == i && p === undefined){             //-- get all values for 1 object
        
            v = new nuEmptyObject();
            
            for (var el in a) {
                v[el] = a[el];
            }
            
            return;
            
        }

        if(a[1] == i || i == -1){            //-- if i = -1 then update all elements in the array
        
            if(v === undefined){             //-- get value
                v    = a[p];
                return;
            }else{                           //-- set value
                a[p] = v;
                
                if(i > -1){                  //-- update just this element
                    return;
                }
            }
            
        }
            
    });

    return v;
    
}

function nuSetAllDraggableObjectProperties(){

    var tabno                  = -1;
    var tabIndex               = '-1';
    var object_number          = 10;
    
    nuSetObjectTypeProperties();
    
    window.nuDraggableObjects.forEach(function(a) {


        if(tabIndex != a[3]){ 
            tabIndex           = a[3];
            object_number      = 0;              //-- Object order within Tab
            ++tabno;
        }
        
        object_number         = object_number + 10;
    
        a.tab_number          = tabno;
        a.object_number        = object_number;
        a.zzzsys_object_id    = a[0];
        a.sob_all_name        = a[1];
        a.sob_all_type        = a[2];
        a.sob_all_tab_number  = a[3];
        a.sob_all_top         = a[4];
        a.sob_all_left        = a[5];
        a.has_been_moved      = 0;
        a.is_selected         = 0;
        a.prefix              = '';
        if(a[2] == 'lookup') {a.prefix = 'code';}
        if(a[2] == 'subform'){a.prefix = 'scroll_';}
        
        a.title_width         = nuSetTitleWidth(a[1]);
//      a.title_width         = parseInt($('#title_' + a[1]).css('width'));
        a.holder_top          = 0;
        a.holder_left         = 0;
        a.holder_height       = 0;
        a.holder_width        = 0;
        a.object_top          = 0;
        a.object_left         = 0;
        if($('#' + a.prefix + a[1]).length == 1){
            var pos               = $('#' + a.prefix + a[1]).position();
            a.object_top          = pos.top;
            a.object_left         = pos.left;
        }

        if(a[2] == 'subform' || a[2] == 'browse'){a.title_width = 0;}       //-- title is above not to the left of object

        if(a[2] == 'lookup'){
        
            a.object_height   = parseInt($('#code' + a[1]).css('height'));
            a.object_width    = parseInt($('#code' + a[1]).css('width')) + parseInt($('#btn_' + a[1]).css('width')) + parseInt($('#description' + a[1]).css('width')) + 4;
            
        }else{
        
            a.object_height   = parseInt($('#' + a[1]).css('height'));
            a.object_width    = parseInt($('#' + a[1]).css('width'));
            
        }
        
        if(a[4] == 0 && a[5] == 0){                                         //-- floating within a tab's table

            var p = $('#' + a.prefix + a[1]).position();        
            a.holder_top      = p.top - (a[2] == 'lookup' ? 3 : 2);
            a.holder_left     = p.left - a.title_width - 4;
            
        }else{                                                              //-- specifically positioned
        
            a.holder_top      = a[4];
            a.holder_left     = a[5];
            
        }
        
    });

}

function nuPlaceInHolder(i){

    var o = nuDraggableObjectProperties(i);

    nuMoveObject(i, o.holder_top, o.holder_left);
//  nuDraggableObjectProperties(i, 'has_been_moved', 1);
    
}


function nuEmptyObject(){

    this.nu = '';

}


function nuSetObjectTypeProperties(){

//-- Get Object Type Properties

    window.nuOTP                        = Array();
    var a                               = Array();
    
    a.push('browse');
    a.push('button');
    a.push('checkbox');
    a.push('display');
    a.push('dropdown');
    a.push('html');
    a.push('iframe');
    a.push('listbox');
    a.push('lookup');
    a.push('subform');
    a.push('text');
    a.push('textarea');
    a.push('words');

    for(var i = 0 ; i < a.length ; i++){
        
        window.nuOTP[a[i]]              = new nuEmptyObject();
        window.nuOTP[a[i]].title        = 'title_';            //-- holds the object title
        window.nuOTP[a[i]].prefix       = '';                  //-- used when aligning to other objects
        window.nuOTP[a[i]].cover        = 'td_right_';         //-- cover with see through div

    }
    
    window.nuOTP['lookup'].prefix       = 'code';
    window.nuOTP['subform'].title       = 'browse_title_';
    window.nuOTP['subform'].prefix      = 'scroll_';
    window.nuOTP['subform'].cover       = 'scroll_';
    window.nuOTP['browse'].title        = 'browse_title';
    window.nuOTP['browse'].cover        = 'nu_holder_';
    
}

// SISYPHOS

function foo(param){
    ;
}

function pass_session_variable(param){
    nuSetHash('varval', param);
}
function get_session_variable(varname){
    nuSetHash('varname', varname);
    nuAjax('getSessionVariable','pass_session_variable');
    return nuGetHash('varval');
}

function set_session_variable(varname,varval){
    nuSetHash('varname', varname);
    nuSetHash('varval', varval);
    nuAjax('setSessionVariable','foo');
}

function sisMessage(output_msg, title_msg)
{
    if (!title_msg)
        title_msg = 'Πληροφορία';

    if (!output_msg)
        output_msg = 'Δεν υπάρχει μήνυμα για να εμφανιστεί!';
        
    output_msg = output_msg.replace(/(?:\r\n|\r|\n)/g, '<br />');

    $('<div></div>').html(output_msg).dialog({
        title: title_msg,
        resizable: false,
        modal: true,
		width: 400,
        buttons: {
            'Εντάξει': function() 
            {
                $( this ).dialog( "close" );
            }
        }
    });
}

function sisApprove(output_msg, title_msg, onSuccessFunction, functionArg)
{
    if (!title_msg)
        title_msg = 'Ερώτηση';

    if (!output_msg)
        output_msg = 'Δεν υπάρχει μήνυμα για να εμφανιστεί!';
        
    output_msg = output_msg.replace(/(?:\r\n|\r|\n)/g, '<br />');
    
    $('<div></div>').html(output_msg).dialog({
        title: title_msg,
        resizable: false,
        modal: true,
        zIndex: 10000,
        autoOpen: true,
        width: 400,
        
        buttons: {
          'Ναι': function () {
                $(this).dialog('close');
                if(onSuccessFunction){
                    if(functionArg){
                        window[onSuccessFunction](functionArg);
                    } else {
                        window[onSuccessFunction]();
                    }
                }
          },
          'Όχι': function () {
                $(this).dialog('close');
          }
        },
        close: function (event, ui) {
            $(this).remove();
        }
    });
}

function showLoader(){
    var e = document.createElement('div');
    e.setAttribute('id', 'sisProcessingImage');
    $('#nuHolder').append(e);
    $('#' + e.id).html('<img src=\'ajax-loader.gif\'/>')
    .addClass( 'nuSaveMessageProgress')
    .show();
}    

function hideLoader() {
    $("#sisProcessingImage").hide();
}

function loadImg(img_name) { 
    i = new Image(); 
    i.src = 'images/'+img_name+'.png';
    
    ib = new Image(); 
    ib.src = 'images/'+img_name+'b.png';    
}

function showTopLoader(){
    
	var e = document.createElement('div');
    e.setAttribute('id', 'sisTopLoaderAreaHolder');
    $('body').append(e);
	$('#' + e.id).addClass( 'loaderAreaHolder');
	
	var e1 = document.createElement('div');
	e1.setAttribute('id', 'sisTopLoader1');
	$('#' + e.id).append(e1);
	$('#' + e1.id).addClass( 'loader1');
	
	var e2 = document.createElement('div');
	e2.setAttribute('id', 'sisTopLoader2');
	$('#' + e.id).append(e2);
	$('#' + e2.id).addClass( 'loader2');
	$('#' + e2.id).html('Σ');
	
	$('#sisTopLoader1').show();
	$('#sisTopLoader2').show();
}    

function hideTopLoader() {
	$('#sisTopLoader1').remove();
	$('#sisTopLoader2').remove();
	$("#sisTopLoaderAreaHolder").remove();
}

function sumoTransform(select_name,search,select_all) { 
    if(select_name){
		if(search){
			$('#'+select_name).SumoSelect({
				placeholder: 'Επιλέξτε...',
				csvDispCount: 2,
				captionFormat: '{0} Επελέγησαν',
				captionFormatAllSelected:'{0} Όλα Επελέγησαν!',
				okCancelInMulti: true, 
				floatWidth: 300,
				nativeOnDevice: ['xx'],
				locale: ['OK', 'Ακύρωση', 'Επιλογή Όλων'],
				selectAll: select_all,
				search: true, 
				searchText: 'Αναζητήστε εδώ...',
				noMatch: 'Δε βρέθηκαν "{0}"',
				triggerChangeCombined: false
				});
		} else {
			$('#'+select_name).SumoSelect({
				placeholder: 'Επιλέξτε...',
				csvDispCount: 2,
				captionFormat: '{0} Επελέγησαν',
				captionFormatAllSelected:'{0} Όλα Επελέγησαν!',
				okCancelInMulti: true, 
				floatWidth: 300,
				nativeOnDevice: ['xx'],
				locale: ['OK', 'Ακύρωση', 'Επιλογή Όλων'],
				selectAll: select_all,
				triggerChangeCombined: false
				});	
		}

		var p = document.getElementById(select_name);
		var l = (p.offsetWidth+18 < 130 ? 130 : p.offsetWidth+18);
		p.parentNode.style.width = l+'px'; 
	}
}


function sumoGetSelectedIds(s) {

	var ids ='';
	
	for (var i = 0; i < s.options.length; i++) {
		if (s.options[i].selected == true) {
			
			if(ids==''){
				ids = s.options[i].value;	
			} else {
				ids=ids+','+s.options[i].value;	
			}
		}
	}
	
	return(ids);
}

function sumoSetHashSelectedIds(s) {
	
	var ids ='';
	
	for (var i = 0; i < s.options.length; i++) {
		if (s.options[i].selected == true) {
			
			if(ids==''){
				ids = s.options[i].value;	
			} else {
				ids=ids+','+s.options[i].value;	
			}
		}
	}
	
	nuSetHash(s.id+'_sumo', ids);
}

function increaseProperty(propName,className,increment) {
   
    var classElements = document.getElementsByClassName(className);
  
    for(i=0; i<classElements.length; i++) {
  
        var ellement = classElements[i];
        var prop = ellement.style[propName];
    
        if(prop.substring(prop.length-2,prop.length)=='px'){
            var propNumeric = Number(prop.substring(0,prop.length-2));
            if(propNumeric){
                var newPropNumeric = propNumeric + increment;
                ellement.style[propName] = newPropNumeric + 'px'; 
            }
        }
    }
}

function adjustHeight(iFrameObj,minHeight=350){
	
	var curTop = Math.round($('#'+iFrameObj.id).offset().top);
	var curHeight = $('#'+iFrameObj.id).height();
	var curBottom = curTop + curHeight;
	
	var maxBottom = 0;
	
	$('iframe').each(function() {
		if($(this).attr('id') != $('#'+iFrameObj.id).attr('id')){
			var t = $(this).offset() ? Math.round($(this).offset().top) : 0;
			var h = $(this).height() ? $(this).height() : 0;
			if((t+h) > maxBottom){maxBottom = (t+h);}
		}
	});
	
    iFrameObj.style.visibility = 'hidden';
    iFrameObj.style.height = "50px"; // reset to minimal height ...
    
    var doc = iFrameObj.contentDocument ? iFrameObj.contentDocument: iFrameObj.contentWindow.document;
    var body = doc.body, html = doc.documentElement;
    var newHeight = Math.max( body.scrollHeight, body.offsetHeight, 
        html.clientHeight, html.scrollHeight, html.offsetHeight,minHeight)+10;
	
	var newBottom = curTop + newHeight;
	
	if(newBottom > maxBottom && curBottom > maxBottom){
		offset = newBottom - curBottom;
	} else if (maxBottom > newBottom && maxBottom > curBottom){
		offset = 0;
	} else {
		offset = curBottom < newBottom ? newBottom - maxBottom : maxBottom - curBottom;
	}
	
	/*console.log("curTop="+curTop);
	console.log("curHeight="+curHeight);
	console.log("newHeight="+newHeight);
	console.log("maxBottom="+maxBottom);
	console.log("newBottom="+newBottom);
	console.log("offset="+offset);
	console.log("----------------------------");*/
	
	if(offset!=0){
		increaseProperty('height','nuHolder',offset);
		increaseProperty('height','nuShadeHolder',offset);
		increaseProperty('height','nuTabAreaHolder',offset);
		increaseProperty('height','nuSelectedTab',offset); //nuTabArea
		increaseProperty('top','nuStatusHolder',offset);
		nuFORM.form_height = Number(nuFORM.form_height) + offset;
	}
    
    iFrameObj.style.height = newHeight + 'px';
    iFrameObj.style.visibility = 'visible';
}

function open_record(param){
    if(param=='-1' || param==''){
        alert('Προσοχή: Η εγγραφή δε βρέθηκε!');    
    } else {
        window.nuSession.breadCrumb.pop();
        nuOpenForm('','', window.nuFORM.form_id, param,'');    
    } 
}

function open_next_record(param){
    if(param=='-1' || param==''){
        alert('Αυτή είναι η τελευταία εγγραφή!');    
    } else {
        window.nuSession.breadCrumb.pop();
        nuOpenForm('','', window.nuFORM.form_id, param,'');    
    } 
}

function open_prev_record(param){
    if(param=='-1' || param==''){
        alert('Αυτή είναι η πρώτη εγγραφή!');    
    } else {
        window.nuSession.breadCrumb.pop();
        nuOpenForm('','', window.nuFORM.form_id, param,'');    
    } 
}

