function openSisyphosPage()
{
  var win=window.open('http://www.sisyphos.gr/index.php?page=creator.html', '_blank');
  win.focus();
}

function nuAceTitle(i){

   var w = i.split('_');
   w.shift();
   var t = w.join(' ');
   return t.capitalize();
   
}

function nuOpenAce(l, f, t){

	nuFORM.aceLanguage = l;
	nuFORM.aceField    = f;
	nuFORM.aceTitle    = nuAceTitle(f);
	var ts             = new Date().getTime();
	
	window.open('nuace.html?' + ts);

}

function nuLoadAce(){

   var ta = Array();
   
   ta.push('SQL|sfo_add_button_display_condition');
   ta.push('SQL|sfo_save_button_display_condition');
   ta.push('SQL|sfo_delete_button_display_condition');
   ta.push('SQL|sfo_clone_button_display_condition');
   ta.push('SQL|sfo_new_button_display_condition');
   ta.push('SQL|sfo_print_button_display_condition');
   ta.push('SQL|sob_all_display_condition');
   ta.push('SQL|sfa_button_display_condition');
   ta.push('SQL|sfo_sql');
   ta.push('SQL|sfo_breadcrumb');
   ta.push('SQL|sob_all_default_value_sql');
   ta.push('SQL|sob_display_sql');
   ta.push('SQL|sob_dropdown_sql');
   ta.push('SQL|sob_listbox_sql');
   ta.push('SQL|sob_lookup_sql');
   ta.push('SQL|sob_subform_sql');
   ta.push('SQL|sre_zzzsys_sql');
   
   ta.push('PHP|sfo_custom_code_run_before_save');
   ta.push('PHP|sfo_custom_code_run_before_browse');
   ta.push('PHP|sfo_custom_code_run_before_open');
   ta.push('PHP|sfo_custom_code_run_after_save');
   ta.push('PHP|sfo_custom_code_run_after_delete');
   ta.push('PHP|sob_lookup_php');
   ta.push('PHP|slp_php');
   
   ta.push('Javascript|sfo_custom_code_run_javascript');
   ta.push('Javascript|sev_javascript');
   ta.push('Javascript|sob_lookup_javascript');
   ta.push('Javascript|sfa_button_javascript');
   
   ta.push('HTML|sob_html_code');
   ta.push('HTML|set_css');


   for(var i = 0 ; i < ta.length ; i++){

      var o     = String(ta[i]).split('|');
	  
        if(o[0] == 'SQL'){
	  
           $("textarea","[id*='"+o[1]+"']").dblclick(function() {
               nuOpenAce('SQL', this.id);
           });
		 
        }
	  
        if(o[0] == 'PHP'){
	  
           $("textarea","[id*='"+o[1]+"']").dblclick(function() {
               nuOpenAce('PHP', this.id);
           });
		 
        }
	  
        if(o[0] == 'Javascript'){
	  
           $("textarea","[id*='"+o[1]+"']").dblclick(function() {
               nuOpenAce('Javascript', this.id);
           });
		 
        }
	  
        if(o[0] == 'HTML'){
	  
           $("textarea","[id*='"+o[1]+"']").dblclick(function() {
               nuOpenAce('HTML', this.id);
           });
		 
        }
    }   
}

function nuBuildEditForm(o){
	nuFORM.moved_objects = '';
	nuFORM.menu = o.menu;
	nuFORM.nu_user_id = o.nu_user_id;
	nuFORM.new_messages = o.new_messages;
	nuFORM.new_notifications = o.new_notifications;
	nuFORM.play_buttons = o.play_buttons;
	
	if(window.nuMoveable) {
		window.nuMoveable = false;
	}

	$('#nuHolder').remove();
	window.nuSession.removeSubforms();
	window.nu_user_name = o.nu_user_name;
	var formObjects     = o.objects;
	var formRecords     = Array();
	formRecords[0]      = o.records;
	nuFORM.last_edit    = o.edited;
	nuCloseModal();

	nuBuildHolder('nuHolder', 'nuHolder', 'body');
	nuBuildHolder('nuActionButtonHolder', 'nuActionButtonHolder', 'nuHolder');
	nuBuildHolder('nuBreadCrumbHolder', 'nuBreadCrumbHolder', 'nuHolder');
	nuBuildHolder('nuShadeHolder', 'nuShadeHolder', 'nuHolder');
	nuBuildHolder('nuTabAreaHolder', 'nuTabAreaHolder', 'nuShadeHolder');
	nuBuildHolder('nuTabTitleHolder', 'nuTabTitleHolder nuUnselectedTab nuGradient', 'nuShadeHolder');
	nuBuildHolder('nuStatusHolder', 'nuStatusHolder nuUnselectedTab nuGradient', 'nuShadeHolder');
	
	window.hashData          = Array();
	window.nuVALUES          = Array();
	window.nuTab             = 5;
	window.hashData          = nuGetHashData(formObjects,formRecords);  //-- to be used in Edit Browse Objects
	
	nuFORM.form_width        = o.form_width;
	nuFORM.form_height       = o.form_height;
	nuFORM.schema            = o.schema;
	//nuFORM.object_properties = o.object_properties;
	nuFORM.php_ids           = o.php_ids;
	nuFORM.report_ids        = o.report_ids;

	nuDisplayEditForm(formObjects,formRecords);
	nuSelectTab(nuFORM.tab_number, o.form_height, o.form_width);

	$('#nuActionButtonHolder').css('text-align','center');
	$('#nuActionButtonHolder').css('width', o.form_width);
	$('#nuTabTitleHolder').css('width', o.form_width);
	$('#nuStatusHolder').css('width', o.form_width);
	$('#nuStatusHolder').css('top', Number(o.form_height)-22);
	$('#nuStatusHolder').css( 'font-size', '12px');
    if(nuGetID() == ''){
        $('#nuStatusHolder').html('&nbsp;<a style="text-decoration:none;padding:3px 0px 0px 0px" href="'+nuGetHome()+'">'+nuTranslate('Logout')+'</a>');
		window.onbeforeunload = nuHomeWarning;
	}else{
		window.onbeforeunload = nuWindowWarning;
    }
    $('#nuStatusHolder').css( 'padding', '4px 0px 0px 0px');
	
	var cd = new Date();
	var cy = cd.getFullYear();
	
	var h     =  '<span title="Λογαριασμός Πρόσβασης" onclick="openFormFromMenu(\'54a65ecc3f14737\', nuFORM.nu_user_id, \'Στοιχεία Λογαριασμού\');">' + o.nu_user_name + "</span><span style='font-weight:bold'> :: </span><span title='Πληροφορίες για το δημιουργό' onclick='openSisyphosPage(); return false;'>" + nuTranslate('') + '&copy; 2014-'+cy+' Παπαδακάκης Νεκ.&nbsp;&nbsp;</span>';

	if(nuIsGA()){
		h    += '<img id="nuMoveable"    src="numove_black.png"    style="width:15px;height:15px;" onclick="nuPoweredBy(this);" title="Drag Objects">&nbsp;&nbsp;';
	}
	
	//if(nuFORM.record_id != '-1'){
		h    += '<img id="nuRefreshLogo" src="nurefresh_black.png" style="width:15px;height:15px;" onclick="nuReloadForm()"  title="Refresh Form" >';
	//}
	
	e         = document.createElement('div');              //-- create a nuBuilder Advert
	e.setAttribute('id', 'poweredBy');
	$($('#nuStatusHolder')).append(e);
	$('#' + e.id).css({
        'width'     : '400px',
        'text-align': 'right',
        'top'       : '3px',
        'left'      : (o.form_width - 405) +'px',
        'position'  : 'absolute'  
    })
    .html(h);
	
	$('#nuTabArea').css('width', o.form_width);
	$('#nuShadeHolder').css('height', o.form_height);
	$('#nuShadeHolder').css('width', o.form_width);
	$('#nuHolder').css('height', Number(o.form_height)+100);
	$('#nuHolder').css('width', o.form_width);

	if (window.top != window.self) {
                 $('#nuHolder').css('margin', 10+'px');
        }

        if (window.nuSession.breadCrumb.length > 1 && nuTranslate(o.breadcrumb))
            window.nuSession.breadCrumb[window.nuSession.breadCrumb.length -1].title = nuTranslate(o.breadcrumb);

		addButtons(o.buttons);	
		nuAddBreadCrumbs();	
        nuAddJavascript(o);
		nuSetAllDraggableObjectProperties();
		$("[tabindex ='5']").focus();
		
        if(window.nuLoadEditGlobal){               //-- allows the user the ability to jun javascript on every Edit Screen
            nuLoadEditGlobal();
        }
        if(window.nuLoadEdit){                     //-- allows the user the ability to jun javascript on this Edit Screen
            nuLoadEdit();
        }
        
	nuIframeWindowSizer();  

}

function nuPoweredBy(t){

    if(!nuIsGA()){return;}
    
	window.nuMoveable = true;
	$('#'+t.id).attr('src', 'numove_red.png');
	//$('#'+t.id).remove();
	nuObjectDraggableDialog(300);
	nuObjectMover();

}

function nuGetHashData(o,r){

	var h = Array();
	for(var i = 1 ; i < o.length ; i++){
		var obj = Object();
		obj.field = o[i].field;
		obj.value = r[0][i];
		h.push(obj);
	}
	return h;
	
}
	



function nuBuildHolder(pid, pclass, pparent){

	var e = document.createElement('div');
	e.setAttribute('id', pid);
	if(pparent == 'body'){
		$(pparent).append(e);
	}else{
		$('#' + pparent).append(e);
	}
	$('#' + pid).addClass(pclass);

}




function nuObjectSize(o,e,i,xAndY){

	if(o[i].display == '0'){
		$('#' + e.id).css({
            'height'    : '0px',
            'width'     : '0px',
            'visibility': 'hidden'
        });
		e.setAttribute('tabindex',          '10000');
	}
	if(o[i].type != 'checkbox'){
		$('#' + e.id).css( 'width', o[i].width+'px')
	}
	

	$('#' + e.id).css( 'width', o[i].width+'px')
	if(!xAndY){return;}
	$('#' + e.id).css( 'height', o[i].height+'px')

}


function nuSetLookupAttributes(e,c,img,d,o,i,prefix, rowPK){

	var id               = prefix+o[i].field;
	e.setAttribute('id',                   prefix+o[i].field);
	c.setAttribute('id',                   prefix+'code'+o[i].field);
	d.setAttribute('id',                   prefix+'description'+o[i].field);
	e.setAttribute('data-id',              o[i].field);
	c.setAttribute('data-id',              o[i].field);
	d.setAttribute('data-id',              o[i].field);
	img.setAttribute('id',                 prefix+'btn_'+o[i].field);
    window.nuVALUES[id]  = '';

	if(o[i].value == '' || o[i].value == null){
		e.setAttribute('value',                '');
		c.setAttribute('value',                '');
		d.setAttribute('value',                '');
	}else{
		var values = $.parseJSON(o[i].value);
		e.setAttribute('value',                values[0]);
		c.setAttribute('value',                values[1]);
		d.setAttribute('value',                values[2]);
		if(rowPK != '-1' && rowPK != '') {
			window.nuVALUES[id]  = values[0];
		}
	}
	
	e.setAttribute('data-saveable',  '1');
	e.setAttribute('data-nuformat',  '');
	e.setAttribute('data-nuobject',        o[i].o_id);
	c.setAttribute('data-nuobject',        o[i].o_id);
	c.setAttribute('data-nuobject-type',   o[i].type);
	e.setAttribute('onchange',            'nuSetEdited();nuLookupID(this)');
	c.setAttribute('onchange',            'nuSetEdited();nuLookupCode(this);');

	img.setAttribute('data-prefix',        prefix);
	img.setAttribute('data-form',          o[i].form);
	e.setAttribute('data-prefix',          prefix);
	e.setAttribute('data-form',            o[i].form);
	e.setAttribute('data-parent',          o[i].f_id);
	c.setAttribute('data-prefix',          prefix);
	c.setAttribute('data-form',            o[i].form);
	c.setAttribute('data-parent',          o[i].f_id);
	d.setAttribute('data-prefix',          prefix);

	if(o[i].read_only == '1'){
		e.setAttribute('readonly',             true);
		c.setAttribute('readonly',             true);
		c.setAttribute('tabindex', '-1');
		img.setAttribute('disabled',           'disabled');
	}else{
		img.setAttribute('onclick',           'nuOpenLookup(this)');
	}
	
	d.setAttribute('readonly',             true);
    d.setAttribute('tabindex', '-1');

    if(prefix == '') {
        c.setAttribute('tabindex', window.nuTab);
        window.nuTab+=5;
        window.nuTab+=5;
    }

        
}


function nuPopulateSelect(o,i,p,e){

	var obj = $.parseJSON(o[i].list);
	if(String(o[i].value) == 'null'){
		var sel = new Array();
	}else{
		var sel = o[i].value.split(nuSession.nuBuilderSeparator);  //-- nuBuilder separator
	}
	if(o[i].type=='dropdown'){
		var option = document.createElement('option');
		option.value = '';
		option.appendChild(document.createTextNode(''));
		e.appendChild(option);
	}

	for(var I = 0 ; I < obj.length ; I++){

		var option = document.createElement('option');
		option.value = obj[I][0];
		option.appendChild(document.createTextNode(obj[I][1]));
		if($.inArray(obj[I][0], sel) != -1){
//			option.selected = true;
			option.setAttribute('selected', 'selected');
		}
		e.appendChild(option);
		
	}

}




function nuSubformColumnTitles(o,i,e,objects){

	var left = 0;

	c = document.createElement('div');              //-- create a new subform column div
	c.setAttribute('id', 'status_title_'+o[i].field);
	$('#' + e.id).append(c);
	width      = parseInt(o[i].width);
	$('#' + c.id).css({ 
        'width'                       : (width-40)+'px',
        'height'                      : (o[i].title_height) +'px',
        'top'                         : '0px',
        'left'                        : left+'px',
        'position'                    : 'absolute',
        'border-color'                : 'grey',
        'border-width'                : '0px'
    })
	.addClass('nuUnselectedTab nuGradient');
	
	if(o[i].row_type == 'g'){
	
		for(var I = 1 ; I < objects.length ; I++){
		
			c = document.createElement('div');              //-- create a new subform column div
			c.setAttribute('id', 'title_'+o[i].field+objects[I].field);
			c.setAttribute('data-id', objects[I].o_id);
			c.setAttribute('ondblclick', 'nuOpenObjectForm(this)');
			$('#' + e.id).append(c);
			width      = parseInt(objects[I].width)+(parseInt(objects[I].width) == 0 ? 0 : 4);
			$('#' + c.id).css({ 
                'width'       : width+'px',
                'height'      : (o[i].title_height) +'px',
                'top'         : '0px',
                'left'        : left+'px',
                'position'    : 'absolute'
            })
            .addClass('nuUnselectedTab nuGradient')
			.html(objects[I].title);
			left       = left + width;

		}
	}


	if(left > o[i].width){
		var delete_left = (left + 1) + 'px';
	}else{
		var delete_left = (o[i].width - 60) + 'px';
	}

	
	c = document.createElement('div');              //-- create a new subform column div
	c.setAttribute('id', 'title_delete'+o[i].field);
	$('#' + e.id).append(c);
	$('#' + c.id).css({ 

		'width'                       : '42px',
		'height'                      : (o[i].title_height) +'px',
		'top'                         : '0px',
		'left'                        : delete_left,
		'position'                    : 'absolute',
		'border-width'                : '0px'
	})
	.addClass('nuUnselectedTab nuGradient')
	
	if(o[i].deletable == 1){
	
		$('#' + c.id).html('Delete');
		
	}

	return left;  //-- total width
}

  
function nuSelectTab(tab, form_height, form_width){

	nuFORM.tab_number = tab;

	$('.nuTabTitle').removeClass("nuSelectedTabTitle");
	$('.nuTabTitle').removeClass("nuSelectedTab");
	$('.nuTabArea').removeClass("nuSelectedTab");
	$('.nuTabTitle').removeClass("nuUnselectedTab");
	$('.nuTabArea').removeClass("nuUnselectedTab");
	$('.nuTabArea').css('height',0);
	$('.nuTabArea').css('visibility','hidden');
	$('#tab'+tab).addClass("nuSelectedTabTitle");
	$('#tab'+tab).addClass("nuSelectedTab");
	$('#nu_tab_area'+tab).addClass("nuSelectedTab");
	$('#nu_tab_area'+tab).css('height', form_height-30);
	$('#nu_tab_area'+tab).css('width', form_width);
	$('#nu_tab_area'+tab).css('visibility','visible');
	$('#tab'+tab).css('border-bottom-color',$('#tab'+tab).css('background-color'));

	if($('#nuObjectList').length == 1){
			nuAddSelectableObjects();
	
	}
	

	
}
  


function nuPagingStatus(parent,top,left,page,total){

	e = document.createElement('div');              //-- create a new paging object
	e.setAttribute('id', 'paging_'+parent);
	$('#' + parent).append(e);
	$('#' + e.id).css({ 
        'width'      : '190px',
        'height'     : '18px',
        'text-align' : 'right',
        'top'        : top+'px',
        'left'       : left+'px',
        'position'   : 'absolute'
    })
	.addClass('nuUnselectedTab');

	lt = document.createElement('div');              //-- create a less than object
	lt.setAttribute('id', 'paging_previous_'+parent);
	$('#' + e.id).append(lt);
	$('#' + lt.id).css({ 
        'top'         : '0px',
         'left'       : '0px',
         'position'   : 'absolute',
         'font-weight': 'bold',
         'font-size'  : '12px'
    })
	.html('&lt;')
	.addClass('nuUnselectedTab');

	ltt = document.createElement('div');              //-- create a less than text object
	ltt.setAttribute('id', 'paging_previous_text_'+parent);
	$('#' + e.id).append(ltt);
	$('#' + ltt.id).css({ 
        'top'      : '0px',
        'left'     : '30px',
        'position' : 'absolute',
        'font-size': '12px'
    })
	.html('Page')
	.addClass('nuUnselectedTab');

	ip = document.createElement('input');              //-- create a current page object
	ip.setAttribute('id', 'paging_current_'+parent);
	$('#' + e.id).append(ip);
	$('#' + ip.id).css({ 
        'height'          : '12px',
        'top'             : '0px',
        'left'            : '70px',
        'position'        : 'absolute',
        'width'           : '30px',
        'font-size'       : '12px',	
        'text-align'      : 'center',
        'background-color': 'white'
    })
    .val(page);
    
	gtt = document.createElement('div');              //-- create a greater than text object
	gtt.setAttribute('id', 'paging_next_text'+parent);
	$('#' + e.id).append(gtt);
	$('#' + gtt.id).css({ 
        'top'      : '0px',
        'left'     : '110px',
        'position' : 'absolute',
        'font-size': '12px'
    })
	.html('/&nbsp;'+total)
	.addClass('nuUnselectedTab');

	gt = document.createElement('div');              //-- create a greater than object
	gt.setAttribute('id', 'paging_next_'+parent);
	$('#' + e.id).append(gt);
	$('#' + gt.id).css({ 
        'top'        : '0px',
        'left'       : '150px',
        'position'   : 'absolute',
        'font-weight': 'bold',
        'font-size'  : '12px'
    })
	.html('&gt;')
	.addClass('nuUnselectedTab');

	
}


function nuGetGridWidth(o,i,objects){

	var width = 0;
	for(var I = 0 ; I < objects.length ; I++){
		width = width + Number(objects[I].width);
	}
	return width;

}

function nuGetGridRowHeight(o,i,objects){

	var h = 0;
	if(o[i].row_type == 'g'){
		for(var I = 0 ; I < objects.length ; I++){
			if(objects[I].height > h){
				h = objects[I].height;
			}
		}
		if(h < 20){h=20;}
		return h;
		
	}else{
		return o[i].row_height;
	}
	

}


function nuiFrame(id,tab,u,t,l,h,w){

    if($('#'+id).length > 0){$('#'+id).remove();}
    e              = document.createElement('iframe');                            //-- create iframe
    e.setAttribute('id',  id);
    e.setAttribute('src', u);
    $('#nu_tab_area'+ tab).append(e);
    $('#' + e.id).css({ 
        'width'    :  w  + 'px',
        'height'   :  h + 'px',
        'top'      :  t + 'px',
        'left'     :  l + 'px',
        'position' :  'absolute'
    });

}


function nuRecordObjects(formType, formTop){

	this.type            = formType;
	this.top             = formTop;
	this.left            = 0;
	this.prefix          = '';
	this.row             = '';
	this.sfName          = '';
	this.startingValue   = '';


	this.nuBuildSubform = function(o,i,p){
		this.nuSubformWrapper(o,i,p);
	}


	this.nuBuildHtml = function(o,i,p){
        var e          = document.createElement('div');       //-- create a new html object

		this.nuObjectWrapper(o,i,p);
		this.nuSetAttributes(e,o,i);

		$('#'+'td_right_'+e.id).append(e);
		nuObjectSize(o,e,i,true);
		$('#' + e.id).html(o[i].html);

	}

	this.nuBuildButton = function(o,i,p){

		var e          = document.createElement('input');       //-- create a new button object
        var filter     = o[i].filter;

		e.setAttribute('type', 'button');
		
		if(o[i].form_id != ''){
            if(filter != ''){
                filter = "', '"+filter;                         //-- add filter if not blank
            }
			e.setAttribute('onclick', "nuOpenForm('"+o[i].form_id+"', '"+o[i].record_id+"', '"+o[i].form_id+"', '"+o[i].record_id+"', '"+o[i].form_title+filter+"')");
		}

		o[i].value     = o[i].title;
		if ( !nuInsideSubform(p) ) {
			o[i].title     = '';
		}
		this.nuObjectWrapper(o,i,p);
		this.nuSetAttributes(e,o,i);

		$('#'+'td_right_'+e.id).append(e);
		nuObjectSize(o,e,i,true);
		$('#'+e.id).addClass('nuButton');

	}

	this.nuBuildListbox = function(o,i,p){

		var e          = document.createElement('select');       //-- create a new listbox object
		e.multiple     = 'multiple';

		this.nuObjectWrapper(o,i,p);
		this.nuSetAttributes(e,o,i);

		$('#'+'td_right_'+e.id).append(e);
		nuObjectSize(o,e,i,true);
		nuPopulateSelect(o,i,p,e);
		$('#' + e.id).css( 'text-align', nuFormatAlign(o[i].align));

    }

	this.nuBuildiFrame = function(o,i,p){

        e              = document.createElement('iframe');        //-- create a new iframe object
        
		this.nuObjectWrapper(o,i,p);
		this.nuSetAttributes(e,o,i);

		$('#'+'td_right_'+e.id).append(e);
		nuObjectSize(o,e,i,true);
        
        if(o[i].src == 'php'){
            nuRunPHP(o[i].src_code, o[i].field);
        }else{
            nuPrintPDF(o[i].src_code, o[i].field);
        }
        
    }

	this.nuBuildDropdown = function(o,i,p){

		var e          = document.createElement('select');       //-- create a new dropdown object
		this.nuObjectWrapper(o,i,p);
		this.nuSetAttributes(e,o,i);

		$('#'+'td_right_'+e.id).append(e);
		nuObjectSize(o,e,i,false);
		nuPopulateSelect(o,i,p,e);
		$('#' + e.id).css( 'text-align', nuFormatAlign(o[i].align));
		
        if ( o[i].read_only == '1' ) {
            $('#' + e.id).prop("disabled", true);
            $('#' + e.id).prop("tabindex", '-1');
        }
	}
	
	this.nuBuildCheckBox = function(o,i,p){

		var e          = document.createElement('input');       //-- create a new checkbox object
		e.setAttribute('type', 'checkbox');

		this.nuObjectWrapper(o,i,p);
		this.nuSetAttributes(e,o,i);

		$('#'+'td_right_'+e.id).append(e);
		$('#'+'td_right_'+e.id).append(e);
		nuObjectSize(o,e,i,false);
		
		if(this.type != 'g'){                                   //-- left align and vertically center
			$('#'+e.id).css('width', 20);
			$('#'+e.id).css('marginTop',3);			
		}
		
		$('#' + e.id).css( 'text-align', nuFormatAlign(o[i].align));
        if ( o[i].read_only == '1' ) {
            $('#' + e.id).prop("disabled", true);
        }
		
	}


	this.nuBuildDisplay = function(o,i,p){

		var e          = document.createElement('input');       //-- create a new display object
		
		this.nuObjectWrapper(o,i,p);
		this.nuSetAttributes(e,o,i);

		$('#'+'td_right_'+e.id).append(e);
		nuObjectSize(o,e,i,false);
		$('#' + e.id).css( 'text-align', nuFormatAlign(o[i].align));
                $('#' + e.id).addClass('nuReadOnly');

	}



	this.nuBuildLookup = function(o,i,p){

		var c_width    = parseInt(o[i].c_width);
		var d_width    = parseInt(o[i].d_width);

		var e          = document.createElement('input');       //-- create a new hidden text object
		var c          = document.createElement('input');       //-- create a new lookup code object
		var img        = document.createElement('div' );       //-- create a new lookup button object	
		var d          = document.createElement('input');       //-- create a new lookup description object

		this.nuObjectWrapper(o,i,p);
		nuSetLookupAttributes(e,c,img,d,o,i,this.prefix, this.rowPK);

		$('#'+'td_right_'+e.id).append(c,img,d,e);
		$('#' + e.id).css({ 
            'visibility' : 'hidden',
            'width'      : '0px',
			'position'   : 'absolute'
        });
        
		$('#' + c.id).css( 'width', c_width+'px');
		
		if ( o[i].read_only == '1' ) {
            $('#' + c.id).addClass('nuReadOnly');
            $('#' + c.id).prop("tabindex", '-1');
        } else {
            $('#' + c.id).addClass('nuLookup');
		}

		if(c_width == '0'){
			$('#' + c.id).css( 'visibility', 'hidden');
		}
		if(d_width == '0'){
			$('#' + d.id).css( 'visibility', 'hidden');
			$('#' + d.id).css(  'position', 'absolute');
		}
        
		$('#' + d.id).css( 'width', d_width+'px')
        .addClass('nuReadOnly');

		$('#' + img.id).css({ 
            'vertical-align': 'text-bottom',
            'width'         : '20px',
            'height'        : '16px'
        })
        .addClass('nuClose')
        .html('&#10138;');

		if(o[i].display != '1') {
			$('#' + img.id).css(  'visibility', 'hidden');
			$('#' + img.id).css(  'position', 'absolute');
		}
	
		if (o[i].autocomplete == '1'){
			nuAutocomplete(c);
		}


        };

	this.nuBuildTextarea = function(o,i,p){

		var e          = document.createElement('textarea');       //-- create a new textarea object
		
		this.nuObjectWrapper(o,i,p);
		this.nuSetAttributes(e,o,i);

		$('#'+'td_right_'+e.id).append(e);
		nuObjectSize(o,e,i,true);
		$('#' + e.id).css( 'text-align', nuFormatAlign(o[i].align))
		.attr( 'wrap', 'on')
		.val(o[i].value);
        
		 if ( o[i].read_only == '1' ) {
                        $('#' + e.id).addClass('nuReadOnly');
                }


	}




	this.nuBuildText = function(o,i,p){

		var e          = document.createElement('input');       //-- create a new text object
		
		this.nuObjectWrapper(o,i,p);
		this.nuSetAttributes(e,o,i);

		$('#'+'td_right_'+e.id).append(e);

		nuObjectSize(o,e,i,false);
		$('#' + e.id).css( 'text-align', nuFormatAlign(o[i].align));

		if(o[i].is_date && o[i].read_only != '1'){
		
			var currentOnClick = e.getAttribute('onclick');
			e.setAttribute('onclick', currentOnClick+';nuPopupCalendar(this);');
			var currentOnBlur = e.getAttribute('onblur');
			e.setAttribute('onblur', currentOnBlur+';nuBlurDateField()');
			
		}
		
		if(o[i].text_type != ''){
			e.setAttribute('type', o[i].text_type);
		}
		
		if ( o[i].read_only == '1' ) {
			$('#' + e.id).addClass('nuReadOnly');
            $('#' + e.id).prop("tabindex", '-1');
		}

		if (o[i].f_id == 'nuform' && o[i].field == 'sfo_table' || o[i].f_id == 'nuobject' && (o[i].field == 'sob_subform_table' || o[i].field == 'sob_subform_foreign_key' )) {
		
			var currentOnChange = e.getAttribute('onchange');
			e.setAttribute('onchange', currentOnChange+';nuSetEdited();nuBuilderFormat(this);nuBuildDefaultSql(this);');
			
		} else {
		
			var currentOnChange = e.getAttribute('onchange');
			e.setAttribute('onchange', currentOnChange+';nuSetEdited();nuBuilderFormat(this)');
			
		}
		
	}


	this.nuBuildBrowse = function(o,i,p){

		var w          = new nuWindow();
		w.call_type    = 'getbrowseform';
		w.form_id      = o[i].form_id;
		w.filter       = o[i].filter;
		w.edit_browse  = 'true';
		w.form_data    = window.hashData;                                             //-- starting values of Edit Form
		nuSession.nuWindows.push(w);

		var e         = document.createElement('div');                               //-- create Browse holder
		var title      = 25;
		e.setAttribute('id',   'nu_holder_'+this.prefix+o[i].field);
		e.setAttribute('data-id'       , o[i].field);
		e.setAttribute('data-nuobject' , o[i].o_id);

		$('#'+ p).append(e);
		
        $('#' + e.id).css({ 
            'top'          : o[i].top + 'px',
            'left'         : o[i].left + 'px',
            'width'        : o[i].width + 'px',
            'height'       : o[i].height + 'px',
            'position'     : 'absolute',
            'border-style' : 'none'
        })
		.addClass('nuSelectedTab');
		

		p              = e.id;

		e              = document.createElement('span');                              //-- create title span
		e.setAttribute('id',  'browse_title' + this.prefix+o[i].field);
		e.setAttribute('data-id', o[i].o_id);
		e.setAttribute('ondblclick', 'nuOpenObjectForm(this)');
		$('#'+ p).append(e);
		$('#' + e.id).css( {
            'width'      :  o[i].width  + 'px',
            'height'     : o[i].height + 'px',
            'top'        :    '4px',
            'left'       :   '0px',
            'text-align' :   'left',
            'position'   : 'absolute'
        })
		.html(o[i].title);


		$('#' + e.id).hover(
			function (){
                            if(nuIsMoveable()){

				$('#' + this.id).css('color', 'red');
                            }
			}, 
			function (){
				$('#' + this.id).css('color', '');
			}
		);
		
		e              = document.createElement('iframe');                            //-- create iframe
		e.setAttribute('id',  this.prefix+o[i].field);
		e.setAttribute('src', 'index.php?i=' + w.id);
		$('#'+ p).append(e);
		$('#' + e.id).css({ 
            'width'    :  o[i].width  + 'px',
            'height'   : o[i].height + 'px',
            'top'      :    title       + 'px',
            'left'     :   0           + 'px',
            'position' : 'absolute'
        });

	}


	
	this.nuBuildWords = function(o,i,p){

		var e          = document.createElement('div');       //-- create a new word object
		var html       = o[i].title;
		o[i].title     = '';
		
		this.nuObjectWrapper(o,i,p);
		this.nuSetAttributes(e,o,i);
        e.removeAttribute('tabindex');

		$('#'+'td_right_'+e.id).append(e);
		nuObjectSize(o,e,i,false)
		$('#' + e.id).css('text-align', nuFormatAlign(o[i].align));
		$('#' + e.id).html(html);

	}



	this.nuSetAttributes = function(e,o,i){

		var id  = this.prefix+o[i].field;
		e.setAttribute('id',                   id);
		e.setAttribute('data-nuformat',        o[i].format);
		e.setAttribute('data-nuobject',        o[i].o_id);
		e.setAttribute('data-nuobject-type',   o[i].type);
		e.setAttribute('data-prefix',          this.prefix);
		e.setAttribute('data-row',             this.row);
        
        if(this.prefix == '')  {
            e.setAttribute('tabindex', window.nuTab);
            window.nuTab+=5;
        }

		if(o[i].events.length > 2){                                //-- empty array  []
			var events = $.parseJSON(o[i].events);
			for(var E = 0 ; E < events.event.length ; E++){
				e.setAttribute(events.event[E],    events.js[E]);
			}
		}
		
		if($.inArray(o[i].type , ['text','display','button','checkbox']) != -1){
			if(o[i].value==null){
				o[i].value = 0;
			}
			e.setAttribute('value', o[i].value);
		}
		
		if(o[i].type == 'checkbox'){
			var currentOnClick = e.getAttribute('onclick');
			e.setAttribute('onclick',  currentOnClick+';nuToggleCB(this)');

			switch (o[i].value){
					case "1":
					e.checked = true;
					break;
				default:
					e.checked = false;
			}
		}
		
		if($.inArray(o[i].type , ['text','textarea','dropdown','checkbox','listbox','lookup']) != -1){
		
			e.setAttribute('data-saveable',  '1');
			var currentOnChange = e.getAttribute('onchange');
			e.setAttribute('onchange',  currentOnChange+';nuSetEdited();nuAddSFRow(this)');
			if(this.rowPK != '-1' && this.rowPK != '') {
				window.nuVALUES[id] = o[i].value;
			}
			
		}else{
			e.setAttribute('data-saveable',  '0');
		}
		
		if(o[i].read_only == '1'){
			e.setAttribute('readonly',          true);
			e.setAttribute('tabindex',          '-1');
		}

	}


	
	this.nuObjectWrapper = function(o,i,p){  //-- create the td the object will sit in

		var parent     = p;
		var field      = o[i].field;
		var title      = o[i].title;
		
		if(this.type != 'g'){  //-- not a grid
			e = document.createElement('tr');              //-- create a new tr
			e.setAttribute('id','tr_'+this.prefix+field);
			$('#' + parent).append(e);
			parent    = e.id;


			e = document.createElement('td');              //-- create a new td
			e.setAttribute('id','td_left_'+this.prefix+field);
			$('#' + parent).append(e);
			$('#' + e.id).css( 'text-align',     'right');
			$('#' + e.id).css( 'vertical-align', 'middle');
			$('#' + e.id).html('<div id="title_'+field+'" data-id="'+o[i].o_id+'" onclick="nuGiveFocus(this)" ondblclick="nuOpenObjectForm(this)">'+title+'&nbsp;</div>');

			e = document.createElement('td');              //-- create a new td
			e.setAttribute('id',  'td_right_'+this.prefix+field);
			$('#' + parent).append(e);
			$('#' + e.id).css( 'text-align', 'left')
			return;
		}

		var e               = document.createElement('div');       //-- create a new field container
		e.setAttribute('id',   'td_right_'+this.prefix+field);
		$('#'+parent).append(e);
		$('#' + e.id).css( 'position', 'absolute');
		$('#' + e.id).css( 'top', '1px');
		$('#' + e.id).css( 'left', this.left+'px');
		$('#' + e.id).css( 'width', (Number(o[i].width) + 5)+'px');
	}


	this.nuSubformWrapper = function(o,i,p){  //-- create the td the object will sit in

		var parent     = p;
		var width      = 0;
		var height     = 0;
		var e          = Object();
		var sfObjects  = $.parseJSON(o[i].objects);
		var sfRecords  = $.parseJSON(o[i].records);
		if(o[i].row_type == 'b'){    //-- grid like subform
				width   = nuGetGridWidth(o,i,sfObjects)+60;
		}
		
		if(o[i].width == '0'){
			o[i].width = width;   //-- resize as needed
		}

		e              = document.createElement('div');                                //-- create Subform holder
		var title      = 25;
		e.setAttribute('id', 'nu_holder_'+this.prefix+o[i].field);
		e.setAttribute('data-id',     o[i].o_id);
		$('#' + parent).append(e);
		$('#' + e.id).css({ 
            'top'          : o[i].top + 'px',
            'left'         : o[i].left + 'px',
            'width'        : o[i].width + 'px',
            'height'       : o[i].height + 'px',
            'position'     : 'absolute',
            'border-style' : 'none'
        })
		.addClass('nuSelectedTab');

		parent    = e.id;

		e              = document.createElement('span');                              //-- create title span
		e.setAttribute('id',  'browse_title_' + this.prefix+o[i].field);
		e.setAttribute('data-id', o[i].o_id);
		e.setAttribute('ondblclick', 'nuOpenObjectForm(this)');
		$('#'+ parent).append(e);
		$('#' + e.id).css({ 
            'width'      : o[i].width  + 'px',
            'height'     : o[i].height + 'px',
            'top'        : '0px',
            'left'       : '0px',
            'text-align' : 'left',
            'position'   : 'absolute'
        })
		.html(o[i].title);



		$('#' + e.id).hover(
			function (){
				if(nuIsMoveable()){
					$('#' + this.id).css('color', 'red');

				}
			}, 
			function (){
				$('#' + this.id).css('color', '');
			}
		);
		
		nuObjectSize(o,e,i,true);
		
		e          = document.createElement('div');              //-- create a new subform scrolling div
		e.setAttribute('id', 'scroll_'+o[i].field);
		$('#' + parent).append(e);
		width      = parseInt(o[i].width);
		height     = parseInt(o[i].height);
		$('#' + e.id).css({ 

            'width'                        : (width  - 18) + 'px',
             'height'                      : (height - 15) + 'px',
             'top'                         : '20px',
             'left'                        : '0px',
             'position'                    : 'absolute',
             'background-color'            : '#E0E0E0',
             'overflow'                    : 'auto'
        });
		parent     = e.id;


		var left   = nuSubformColumnTitles(o,i,e,sfObjects);
		var newL   = width;
		e          = document.createElement('div');              //-- create a new object holding div
		e.setAttribute('id', 'objects_'+o[i].field);
		$('#' + parent).append(e);
		width      = parseInt(o[i].width);
		height     = parseInt(o[i].height);
		
		if(newL < left){
			newL   = left + 62;		
		}
		
		$('#' + e.id).css({
            'width'    : (newL - 19) +'px',
            'height'   : (height-(o[i].title_height)-18) +'px',
            'top'      : (Number(o[i].title_height)+2)+'px',
            'left'     : '0px',
            'position' : 'absolute',
            'overflow' : 'scroll'
        });

		parent     = e.id;
		var row_height  = nuGetGridRowHeight(o,i,sfObjects);
		var row_top     = 0;
		nuDisplayEditForm(sfObjects,sfRecords,parent,i,o);		
	}
	
}

function nuDisplayEditForm(formObjects,formRecords,formParent,sfI,sfO){

    if(arguments.length != 2){                                   //-- is a subform
        var subformType = sfO[sfI].row_type;
        var formWidth   = sfO[sfI].width;
        var formHeight  = sfO[sfI].row_height;
        var formPrefix  = sfO[sfI].field;
    }
	
	var e;
	var subform;
	var rw;
	var width           = 0;
	var left            = 0;
	var row             = -1;
	var top             = 0;
	var nuTab           = '';
	var nuCol           = '';
	var TabNo           = -1;
	var ColNo           = -1;
	var parent          = '';
	var tParent         = '';
	
	for(var R = 0 ; R < formRecords.length; R++){

		var firstLoop   = true;
		left            = 0;
		row             = row + 1;
		rw              = ("000" + String(row)).slice (-4);
		form            = new nuRecordObjects(subformType, top, left);
		
		if(arguments.length == 2){                               //-- not a subform
			form.prefix       = '';
			form.sfName       = '';
			form.row          = '';
			var subformType   = '';
			var formWidth     = '';
			var formHeight    = '';
			var formParent    = '';
			var formPrefix    = '';
		}else{
            form.prefix       = formPrefix+rw;
			form.sfName       = formPrefix;
			form.row          = rw;
		}
		form.top              = top;

		for(var i = 1 ; i < formObjects.length ; i++){

			if(formObjects[i].tab_title != nuTab && arguments.length == 2){                   //-- create a new TAB

				TabNo     = TabNo + 1;
				nuCol     = '';
				ColNo     = -1;
				nuTab     = formObjects[i].tab_title ;
				var e     = document.createElement('div');         //-- create a TAB TITLE
				e.setAttribute('id','tab'+TabNo);
				//e.setAttribute('onclick','nuSelectTab('+TabNo+ ', '+nuFORM.form_height + ', ' + nuFORM.form_width +')');
				e.setAttribute('onclick','nuSelectTab('+TabNo+ ', nuFORM.form_height, nuFORM.form_width)');
				e.setAttribute('ondblclick',"if(!nuIsGA()){return;}window.nuControlKey = true;window.nuLastCtrlPressedTS = Math.floor(Date.now() /1000);nuOpenForm('nuobject', '', 'nuobject', '', 'nuBuilder Objects', '" + nuFORM.form_id + "');window.nuControlKey = false;");
				$('#nuTabTitleHolder').append(e);
				$('#'+e.id).html('&nbsp;&nbsp;'+formObjects[i].tab_title+'&nbsp;&nbsp;');
				$('#'+e.id).addClass("nuTabTitle nuUnselectedTab");
				$('#'+e.id).css( 'display', 'inline-block')

				e         = document.createElement('div');         //-- create wrapper surrounding Form Tab
				e.setAttribute('id','nu_tab_area'+TabNo);
				$('#nuTabAreaHolder').append(e);
				$('#'+e.id).addClass("nuTabArea");
				parent    = e.id;

				e         = document.createElement('br');          //-- create a top margin
				$('#' + parent).append(e);

				
				e         = document.createElement('table');       //-- create a new table
				e.setAttribute('id','table'+TabNo+'Area');
				$('#' + parent).append(e);
				parent    = e.id;
				e         = document.createElement('tr');          //-- create a new tr
				e.setAttribute('id','tr'+TabNo+'Area');
				$('#' + parent).append(e);
				parent    = e.id;
				tParent   = e.id;
			}
			if(subformType == 'f' && firstLoop){  //-- is a edit type subform


				firstLoop = false;
				TabNo     = TabNo + 1;
				nuCol     = '';
				ColNo     = -1;
				nuTab     = formObjects[i].tab_title ;

				e        = document.createElement('div');           //-- create wrapper surrounding Subform Row
				e.setAttribute('id', form.prefix+'_nuRow');
				e.setAttribute('data-subform', formPrefix);
				e.setAttribute('onkeypress', 'nuSession.addSubformRow(this)');
				e.setAttribute('onclick', 'nuSession.addSubformRow(this)');
				e.setAttribute('data-row', rw);
                if(sfO[sfI].addable == '1' && sfO[sfI].read_only != '1'){
                    e.setAttribute('data-addable', 'yes');
                }else{
                    e.setAttribute('data-addable', 'no');
                }
				$('#' + formParent).append(e);
				$('#' + e.id).css({ 
                    'top'          : form.top+'px',
                    'width'        : (formWidth-40)+'px',
                    'left'         : '0px',
                    'height'       : formHeight+'px',
                    'position'     : 'absolute',
                    'border-style' : 'none'
                });
				
				parent = e.id;
				e         = document.createElement('table');       //-- create a new table
				e.setAttribute('id',formPrefix+'table'+TabNo+'Area');
				$('#' + parent).append(e);
				parent    = e.id;
				e         = document.createElement('tr');          //-- create a new tr
				e.setAttribute('id',formPrefix+'tr'+TabNo+'Area');
				$('#' + parent).append(e);
				parent    = e.id;
				tParent   = e.id;

			}
			if(subformType == 'g' && firstLoop){  //-- is a grid type subform


				firstLoop = false;
				TabNo     = TabNo + 1;
				nuCol     = '';
				ColNo     = -1;
				nuTab     = formObjects[i].tab_title ;

				e        = document.createElement('div');           //-- create wrapper surrounding Subform Row
				e.setAttribute('id', form.prefix+'_nuRow');
				e.setAttribute('data-subform', formPrefix);
				e.setAttribute('onkeypress', 'nuSession.addSubformRow(this)');
				e.setAttribute('onclick', 'nuSession.addSubformRow(this)');
				e.setAttribute('data-row', rw);
                                if(sfO[sfI].addable == '1' && sfO[sfI].read_only != '1'){
                                    e.setAttribute('data-addable', 'yes');
                                }else{
                                    e.setAttribute('data-addable', 'no');
                                }
				$('#' + formParent).append(e);
				
				$('#' + e.id).css({
                    'top'          : form.top+'px',
                    'width'        : (formWidth-40)+'px',
                    'left'         : '0px',
                    'height'       : formHeight+'px',
                    'position'     : 'absolute',
                    'border-style' : 'none',
                    'text-align'   : 'center'
                });

				parent = e.id;

			}

            $('#' + form.prefix+'_nuRow').hover(
                function (){
                    $('#' + this.id).css('background-color', '#D3D3D3');
                }, 
                function (){
                    $('#' + this.id).css('background-color', '');
                }
            );
				
				
			if(formObjects[i].column != nuCol && subformType != 'g'){                      //-- create a new COLUMN
				ColNo     = ColNo + 1;
				nuCol     = formObjects[i].column;
				
				e         = document.createElement('td');          //-- create a new td
				e.setAttribute('id',formPrefix+'tab'+TabNo+'col'+ColNo+'Area');
				$('#' + tParent).append(e);
				parent    = e.id;

				e         = document.createElement('table');       //-- create a new table
				e.setAttribute('id',formPrefix+'nu_form_tab_no_'+TabNo+'_col_no_'+ColNo);
				$('#' + parent).append(e);
				parent    = e.id;

			}

			formObjects[i].value = formRecords[R][i];

			if(formRecords[R][0] == '-1'){
				form.startingValue = '';
			}else{
				form.startingValue = formRecords[R][i];
			}
			
			form.rowPK             = formRecords[R][0];

			if(formObjects[i].type == 'iframe'){        form.nuBuildiFrame       (formObjects,i,parent);}
			if(formObjects[i].type == 'html'){          form.nuBuildHtml         (formObjects,i,parent);}
			if(formObjects[i].type == 'button'){        form.nuBuildButton       (formObjects,i,parent);}
			if(formObjects[i].type == 'listbox'){       form.nuBuildListbox      (formObjects,i,parent);}
			if(formObjects[i].type == 'dropdown'){      form.nuBuildDropdown     (formObjects,i,parent);}
			if(formObjects[i].type == 'checkbox'){      form.nuBuildCheckBox     (formObjects,i,parent);}
			if(formObjects[i].type == 'display'){       form.nuBuildDisplay      (formObjects,i,parent);}
			if(formObjects[i].type == 'lookup'){        form.nuBuildLookup       (formObjects,i,parent);}
			if(formObjects[i].type == 'textarea'){      form.nuBuildTextarea     (formObjects,i,parent);}
			if(formObjects[i].type == 'text'){          form.nuBuildText         (formObjects,i,parent);}
			if(formObjects[i].type == 'words'){         form.nuBuildWords        (formObjects,i,parent);}
			if(formObjects[i].type == 'subform'){       form.nuBuildSubform      (formObjects,i,parent);}
			if(formObjects[i].type == 'browse'){        form.nuBuildBrowse       (formObjects,i,parent);}
			form.left = Number(form.left) + Number(formObjects[i].width)+(Number(formObjects[i].width) == 0 ? 0 : 4);


		}

		pk               = document.createElement('input');                  //-- create hidden primary key

		if(subformType == ''){
			pk.setAttribute('id', 'nuFormPrimaryKey');
		}else{
			pk.setAttribute('id', form.prefix+'_nuPrimaryKey');
		}

		if(formRecords[R][0] == '-1'){
			pk.setAttribute('value', '');
		}else{
			pk.setAttribute('value', formRecords[R][0]);
		}
		
		var parentOfTable = parent;

		if($('#' + parent).prop("tagName") == 'TABLE'){
			parentOfTable = $('#' + parent).parent().attr('id');
		}
		$('#' + parentOfTable).append(pk);
		$('#' + pk.id).css( 'height', '0px')
		$('#' + pk.id).css( 'width', '0px')
		$('#' + pk.id).css( 'visibility', 'hidden')
		
		if(arguments.length != 2){
            if(sfO[sfI].deletable == '1' && sfO[sfI].read_only != '1'){
                cb           = document.createElement('input');              //-- create delete checkbox
                cb.setAttribute('id', form.prefix+'_nuDelete');
                cb.setAttribute('type', 'checkbox');

				if(sfO[sfI].events.length > 2){                                //-- empty array  []
					var events = $.parseJSON(sfO[sfI].events);
					for(var E = 0 ; E < events.event.length ; E++){
						cb.setAttribute(events.event[E],    events.js[E]);
					}
				}

                if(formRecords[R][0] == '-1'){
                    cb.setAttribute('checked','true');
                }
                $('#' + parentOfTable).append(cb);
                $('#' + cb.id).css( 'top', '5px');
				if(form.left > (formWidth)){
					$('#' + cb.id).css( 'left', (form.left + 10) + 'px');
				}else{
					$('#' + cb.id).css( 'left', (formWidth - 50) + 'px');
				}
                $('#' + cb.id).css( 'position', 'absolute');
            }
			top              = Number(top) + Number(formHeight);
		}
		form.prefix          = '';
		form.left            = 0;
		form.type            = '';

	}

	if(subformType == ''){
		nuSession.addSessionSubform('nuBuilderEditForm', 0, '', 0, 0, formObjects,0,parent,subformType);
	}else{
		nuSession.addSessionSubform(formPrefix, formRecords.length-1, $('#' + parent).html(), formHeight, formWidth, formObjects,sfI,parent,subformType);
	}
	

}
			


function nuGetData(pAction){

    var edit        = {};
	var forms       = [];
	var form        = {};
	var records     = [];
	var record      = {};
	var fields      = [];
	var field       = {};
	var json        = '';
	
	if(arguments.length != 1){
		var pAction = '';
	}

	for(var i = 0 ; i < nuSession.subformName.length ; i++){                                                        //-- loop through form and subforms
	
		var isMainForm  = nuSession.subformName[i] == 'nuBuilderEditForm';
		
		var name    = nuSession.subformName[i];
		var recs    = nuSession.subformRowNumber[i]+1;
		var cols    = nuSession.subformColumns[i];
		form        = {};   //-- form or subform
		records     = [];

		for(var R = 0 ; R < recs ; R++){                                                                        //-- loop through records

			record  = {};
			fields  = [];
			
			if(isMainForm){
				var row    = '';
				name       = '';
			}else{
				var row    = ("000" + String(R)).slice (-4);
			}

			for(var C = 1 ; C < cols.length ; C++){                                                     //-- loop through fields
			
				var id                    = name + row + cols[C].field;
				field                     = {};
				var v                     = $('#' + id).val();
                var match                 = v;
                var save                  = 0;
				
				if($('#'+id).attr('data-saveable')=='1'){                                               //-- is this saveable data
				
					if(cols[C].type == 'listbox'){                                                      //-- if listbox then create a string
						if(v == null){
							v             = '';
						}else{
							v             = v.join(nuSession.nuBuilderSeparator);
						}
                        match             = v;
                                                
					}else{
						v                 =  formatter.formatField($('#' + id).attr('data-nuformat') ,v,true);  //-- format value for sql string
					}

					if(nuCheckStartValue(id) != match || nuFORM.cloned == '1'){
                        save              = 1;
					} 
                                        
                    field                 = {field : cols[C].field, value : v, save : save};

					if(pAction == 'create hash variables'){
						nuSetHash(cols[C].field, v)
					}
                    
                    fields.push(field);
				}
			}

			if($('#'+name+row+'_nuPrimaryKey').length == 0){
				var PK                    = $('#nuFormPrimaryKey').val();
			}else{
				var PK                    = $('#'+name+row+'_nuPrimaryKey').val();
			}

			if(pAction == 'delete all'){  //-- delete all records
				var DR                    = 'yes';
			}else{
				var DR                    = $('#'+name+row+'_nuDelete').is(':checked') ? 'yes' : 'no';
			}
                        
			record                        = {primary_key : PK, delete_record : DR, fields : fields};
			records.push(record);
		}

		json = nuGetJSONSubform(name);
		form = {subform : name, json : json, form_id : cols[0].f_id, records : records};
//		form = {subform : name, form_id : cols[0].f_id, records : records};
		forms.unshift(form);
			
   }
   return {data : forms};

}			

function nuSaveRowValue(n, r){

	if(r == ''){                                            //-- not from a subform
		return false;
	}else{
		if($('#'+n+r+'_nuPrimaryKey').length == 0){         //-- new row
			return true;
		}else{
			if($('#'+n+r+'_nuPrimaryKey').val() == ''){     //-- cloned row
				return true;
			}else{
				return false;
			}
		}
	}


}

function nuCheckStartValue(id){
    
    for (var key in window.nuVALUES) {
        if(key == id){
            return window.nuVALUES[id];
        }
    }
    
    return '-=noValue=-';                    //-- a string that won't match any value
}


function nuAddSFRow(t){

    var p = $('#'+t.id).attr('data-prefix');

    if($('#' + p + '_nuRow').length > 0){
        $('#' + p + '_nuRow').click();      //-- adds a new row to a subform
    }

}


function nuBuildDefaultSql(e) {

    if (e.id == "sfo_table")
        if ($('#sfo_table').val() && !$('#sfo_sql').val())
            $('#sfo_sql').val("SELECT * FROM " + $('#sfo_table').val());
    
    if (e.id == "sob_subform_table" || e.id == "sob_subform_foreign_key" )
        if ($('#sob_subform_table').val() && $('#sob_subform_foreign_key').val() && !$('#sob_subform_sql').val())
            $('#sob_subform_sql').val("SELECT * FROM " + $('#sob_subform_table').val() + " where " + $('#sob_subform_foreign_key').val() + " = '#RECORD_ID#' ");

}


function createCookie(name,value){
    document.cookie = name+"="+value+";";
}

function readCookie(name){

    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
    
}

function eraseCookie(name) {
    createCookie(name,"",-1);
}

function nuGiveFocus(pThis){                                             //-- highligh Object when clicking on its Title

	if($('#' + pThis.id.substr(6)).css('visibility') == 'hidden'){          //-- lookup
		$('#code' + pThis.id.substr(6)).focus();
	}else{
		$('#' + pThis.id.substr(6)).focus();
	}

}

function nuToggleCB(t){
	
    if($('#'+t.id).prop('checked')==true){
		$('#'+t.id).attr('value', "1");
	}else{
		$('#'+t.id).attr('value', "0");
	}
}

function nuHideSaveButtons(hide){

	$("[id^='nuButton']").each(function(index){
	
		if(hide){
			$(this).css('visibility', 'hidden');
		}else{
			$(this).css('visibility', 'visible');
		}
			
	});
	
}


function nuBlurDateField(){
	
	if(window.nuOnCalendar == 0){
		$('#nuCalendar').remove();
	}

}


function nuDisable(i, v){                 //-- disable Edit Form Object and set value

	$('#'+i).addClass('nuReadOnly');
	$('#'+i).prop('readonly', true);
	$('#'+i).prop('disabled', true);

	$('#code'+i).addClass('nuReadOnly');
	$('#code'+i).prop('readonly', true);
	$('#code'+i).prop('disabled', true);
	$('#btn_'+i).css('visibility', 'hidden');

	var s = $('#'+i).attr('data-id');
	var p = $('#'+i).attr('data-prefix');

	$('#'+p+'code'+s).addClass('nuReadOnly');
	$('#'+p+'code'+s).prop('readonly', true);
	$('#'+p+'code'+s).prop('disabled', true);
	$('#'+p+'btn_'+s).css('visibility', 'hidden');

	if(arguments.length == 2){
		$('#'+i).val(v);
		$('#'+i).change();
	}
   
}


function nuEnable(i, v){                 //-- enable Edit Form Object and set value

	$('#'+i).removeClass('nuReadOnly');
	$('#'+i).prop('readonly', false);
	$('#'+i).prop('disabled', false);

	$('#code'+i).removeClass('nuReadOnly');
	$('#code'+i).prop('readonly', false);
	$('#code'+i).prop('disabled', false);
	$('#btn_'+i).css('visibility', 'visible');

	var s = $('#'+i).attr('data-id');
	var p = $('#'+i).attr('data-prefix');

	$('#'+p+'code'+s).removeClass('nuReadOnly');
	$('#'+p+'code'+s).prop('readonly', false);
	$('#'+p+'code'+s).prop('disabled', false);
	$('#'+p+'btn_'+s).css('visibility', 'visible');

	if(arguments.length == 2){
		$('#'+i).val(v);
		$('#'+i).change();
	}

}



function nuGetJSONSubform(s){

    var q = $("[data-prefix='"+s+"0000']");
    var R = nuSubformArray(s);
    var S = Array();
    var C = Array();
    var n,d,i;

    for(i = 0 ; i < $(q).length ; i++){

        C.push(String($(q)[i].id).substr(s.length + Number(4)));
        
    }

    for(r = 0 ; r < R.length ; r++){
        
        var o = new nuObject(r);
        
        for(c = 0 ; c < C.length ; c++){
        
            o[C[c]] = $('#' + R[r] + C[c]).val();
            
        }
        
        o['nuDelete']     = $('#' + R[r] + '_nuDelete').prop('checked');
        o['nuPrimaryKey'] = $('#' + R[r] + '_nuPrimaryKey').val();
        
        S.push(o);
    }
    
    return JSON.stringify(S);
    
}


function nuObject(i){
    this.nuIndex = i;
}


function nuSubformColumnNames(subformName){
	
	var j	= nuGetJSONSubform(subformName);
	var o	= JSON.parse(j);
	var c	= o[0];
	var a	= [];

	for (var f in c){
	  a.push(f);
	}

	return a.splice(1,a.length-3);

}
