

function nuFormatDateByString(dt, s){

	var f = new nuFormatter();
	var d = String('0'+ dt.getDate()).substr(-2);
	var m = String(dt.getMonth()+1);
	var y = String(dt.getFullYear());
	
	
	s = s.replace('yyyy', y);
	s = s.replace('yy', y.substr(2));
	s = s.replace('mmm', f.mmm[m]);
	s = s.replace('mm', f.mm[m]);
	s = s.replace('dd', d);
	
	return s;
	
}




function nuFormatter(){

	this.mmm  = Array();
	this.mm   = Array();
	this.dd   = Array();

	this.mm['1']  = '01';
	this.mm['2']  = '02';
	this.mm['3']  = '03';
	this.mm['4']  = '04';
	this.mm['5']  = '05';
	this.mm['6']  = '06';
	this.mm['7']  = '07';
	this.mm['8']  = '08';
	this.mm['9']  = '09';
	this.mm['10']  = '10';
	this.mm['11']  = '11';
	this.mm['12']  = '12';
	this.mm['01']  = '01';
	this.mm['02']  = '02';
	this.mm['03']  = '03';
	this.mm['04']  = '04';
	this.mm['05']  = '05';
	this.mm['06']  = '06';
	this.mm['07']  = '07';
	this.mm['08']  = '08';
	this.mm['09']  = '09';
	this.mm['jan']  = '01';
	this.mm['feb']  = '02';
	this.mm['mar']  = '03';
	this.mm['apr']  = '04';
	this.mm['may']  = '05';
	this.mm['jun']  = '06';
	this.mm['jul']  = '07';
	this.mm['aug']  = '08';
	this.mm['sep']  = '09';
	this.mm['oct']  = '10';
	this.mm['nov']  = '11';
	this.mm['dec']  = '12';

	this.mmm['1']  = 'Jan';
	this.mmm['2']  = 'Feb';
	this.mmm['3']  = 'Mar';
	this.mmm['4']  = 'Apr';
	this.mmm['5']  = 'May';
	this.mmm['6']  = 'Jun';
	this.mmm['7']  = 'Jul';
	this.mmm['8']  = 'Aug';
	this.mmm['9']  = 'Sep';
	this.mmm['10']  = 'Oct';
	this.mmm['11']  = 'Nov';
	this.mmm['12']  = 'Dec';
	this.mmm['01']  = 'Jan';
	this.mmm['02']  = 'Feb';
	this.mmm['03']  = 'Mar';
	this.mmm['04']  = 'Apr';
	this.mmm['05']  = 'May';
	this.mmm['06']  = 'Jun';
	this.mmm['07']  = 'Jul';
	this.mmm['08']  = 'Aug';
	this.mmm['09']  = 'Sep';
	this.mmm['jan']  = 'Jan';
	this.mmm['feb']  = 'Feb';
	this.mmm['mar']  = 'Mar';
	this.mmm['apr']  = 'Apr';
	this.mmm['may']  = 'May';
	this.mmm['jun']  = 'Jun';
	this.mmm['jul']  = 'Jul';
	this.mmm['aug']  = 'Aug';
	this.mmm['sep']  = 'Sep';
	this.mmm['oct']  = 'Oct';
	this.mmm['nov']  = 'Nov';
	this.mmm['dec']  = 'Dec';

	this.dd['1']  = '01';
	this.dd['2']  = '02';
	this.dd['3']  = '03';
	this.dd['4']  = '04';
	this.dd['5']  = '05';
	this.dd['6']  = '06';
	this.dd['7']  = '07';
	this.dd['8']  = '08';
	this.dd['9']  = '09';
	this.dd['01']  = '01';
	this.dd['02']  = '02';
	this.dd['03']  = '03';
	this.dd['04']  = '04';
	this.dd['05']  = '05';
	this.dd['06']  = '06';
	this.dd['07']  = '07';
	this.dd['08']  = '08';
	this.dd['09']  = '09';
	this.dd['10']  = '10';
	this.dd['11']  = '11';
	this.dd['12']  = '12';
	this.dd['13']  = '13';
	this.dd['14']  = '14';
	this.dd['15']  = '15';
	this.dd['16']  = '16';
	this.dd['17']  = '17';
	this.dd['18']  = '18';
	this.dd['19']  = '19';
	this.dd['20']  = '20';
	this.dd['21']  = '21';
	this.dd['22']  = '22';
	this.dd['23']  = '23';
	this.dd['24']  = '24';
	this.dd['25']  = '25';
	this.dd['26']  = '26';
	this.dd['27']  = '27';
	this.dd['28']  = '28';
	this.dd['29']  = '29';
	this.dd['30']  = '30';
	this.dd['31']  = '31';

	this.formatField = function(f,v,returnSQLFormated){

		if(v == ''){return v;}
		if(f == ''){return v;}
		if(f == "undefined"){return v;}

		if(nuFormats[f].type == 'date'){

			var d           = new Date();
			y               = String(d.getFullYear());
			v               = v.split('/').join('-');
			v               = v.split('.').join('-');
			var aFormat     = nuFormats[f].format.split("-");
			var aValue      = v.split("-");

			if(aValue.length != 3){return '';}

			var aNew        = ['', '', ''];
			var aSQL        = ['', '', ''];
			
			if(aFormat[0] == 'dd'){              //-- Day Of Month is first

				aNew[0]     = this.dd[aValue[0]];
				aSQL[2]     = this.dd[aValue[0]];
				aSQL[1]     = this.mm[String(aValue[1]).toLowerCase()];
				if(aFormat[1] == 'mm'){
					aNew[1] = this.mm[String(aValue[1]).toLowerCase()];
				}else{
					aNew[1] = this.mmm[String(aValue[1]).toLowerCase()];
				}
			}else if(aFormat[0] == 'yyyy'){      //-- Year is first
				aNew[0]	= y;
				aSQL[0] = y;
				aNew[2] = this.dd[aValue[2]];
				aSQL[2]	= this.dd[aValue[2]];
				aSQL[1]	= this.mm[String(aValue[1]).toLowerCase()];
				if(aFormat[1] == 'mm'){
					aNew[1] = this.mm[String(aValue[1]).toLowerCase()];
				}else{
					aNew[1] = this.mmm[String(aValue[1]).toLowerCase()];
				}
			}else{                               //-- Month Of Year is first

				aNew[1]     = this.dd[aValue[1]];
				aSQL[2]     = this.dd[aValue[1]];
				aSQL[1]     = this.mm[String(aValue[0]).toLowerCase()];
				if(aFormat[0] == 'mm'){
					aNew[0] = this.mm[String(aValue[0]).toLowerCase()];
				}else{
					aNew[0] = this.mmm[String(aValue[0]).toLowerCase()];
				}
			}

			if(aFormat[0] != 'yyyy') {
				if(aValue.length == 2){               //-- Add This Year
					aNew[2]     = y;
					aSQL[0]     = y;
					aValue[2]   = y;
				}
				if(String(aValue[2]).length == 4){            //-- Make Year 4 Characters
					aNew[2]     = aValue[2];
					aSQL[0]     = aValue[2];
				}else if(String(aValue[2]).length == 2){
					aNew[2]     = '20' + aValue[2];
					aSQL[0]     = '20' + aValue[2];
				}else if(String(aValue[2]).length == 1){
					aNew[2]     = '200' + aValue[2];
					aSQL[0]     = '200' + aValue[2];
				}
				if(aFormat[2] == 'yy'){               //-- Make Year 2 Characters
					aNew[2]     = aNew[2].substring(2);
				}
			}
			d.setFullYear(Number(aSQL[0]), Number(aSQL[1]) - 1, Number(aSQL[2]));
			
			var actuallyIs = d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate();
			var shouldBe   = Number(aSQL[0]) + '-' + Number(aSQL[1])    + '-' + Number(aSQL[2]);

			if(actuallyIs == shouldBe){ 
				if(arguments.length == 3){
					return aSQL.join('-');            //-- Return valid SQL Date Format
				}else{
					return aNew.join('-');            //-- Return Custom Format
				}
			}else{
				return '';
			}
		}
		if(nuFormats[f].type == 'number'){

			v                       = v.split(nuFormats[f].separator).join('');        //-- remove separators
			v                       = v.split(nuFormats[f].decimal).join('.');         //-- replace decimal
			v                       = Number(v).toFixed(nuFormats[f].format);
			var full                = v.split('.')[0];
			if(v.split('.').length == 2){
				var part            = v.split('.')[1];
			}else{
				var part            = '0';
			}

			if(nuFormats[f].separator != ''){

				while (/(\d+)(\d{3})/.test(full.toString())){
				  full = full.toString().replace(/(\d+)(\d{3})/, '$1'+nuFormats[f].separator+'$2');
				}
				
			}

			var formattedNumber     = full + nuFormats[f].decimal + part;
			
			if(isNaN(v)){ 
				return '';
			}else{
			
				if(arguments.length == 3){
					return v;                      //-- Return valid SQL Number Format
				}else{
				
					if(nuFormats[f].format == '0'){
						return formattedNumber.split(nuFormats[f].decimal)[0];          //-- fixed 2014-03-04 by SC
					}else{
						return formattedNumber;                                         
					}
					
				}
			}
		}
	};
}








function nuWindow(){

	this.id                = nuSession.getWindowID();
	this.session_id        = nuSession.nuSessionID;
	this.call_type         = '';
    this.cloned            = '0';
    this.edited            = '0';
	this.title             = nuTranslate('Home');
	this.tip               = nuTranslate('Desktop');
	this.type              = '';
	this.record_id         = '';
	this.tab_number        = '0';
	this.search            = '';
	this.sort              = '0';
	this.descending        = 'asc';
	this.filter            = '';
	this.page_number       = '1';
	this.lookup_id         = '';
	this.prefix            = '';
	this.breadcrumb        = '1';
	this.object_id         = '1';
	this.username          = '';
	this.password          = '';
	this.edit_browse       = 'false';   //-- Browse Form inside Edit Form
	this.moved_objects     = false;     //-- Objects moved by globeadmin
	this.form_id           = '';
    this.parent_form_id    = '';
    this.parent_record_id  = '';
	this.form_data         = [];
	this.form_height       = 1000;
	this.form_width        = 1000;
	this.php               = '';
    this.search_columns    = '';
    this.iframe            = 0;
    this.function_name	    = '';
}


function nuFormatAlign(a){

	if(a == ''){return 'left';}
	if(a == 'l'){return 'left';}
	if(a == 'c'){return 'center';}
	if(a == 'r'){return 'right';}
	
}




function nuBuilderSession(){

	this.nuBuilderSeparator      = '#nuSep#';
	this.subformName             = Array();
	this.subformRowNumber        = Array();
	this.subformColumns          = Array();
	this.subformRowHTML          = Array();
	this.subformRowHeight        = Array();
	this.subformWidth            = Array();
	this.subformIndex            = Array();
	this.subformParentID         = Array();
	this.subformType	     = Array();
	this.breadCrumb              = Array();
	this.nuWindows               = Array();
	this.nuSessionID             = '';
	this.nuDirectory             = '';
	this.nuWindowID              = 1000;
	this.properties              = new Object;
	
	
	
	
	this.removeSubforms = function(){        //-- before rebuilding a new form

		this.subformName         = Array();
		this.subformRowNumber    = Array();
		this.subformColumns      = Array();
		this.subformRowHTML      = Array();
		this.subformRowHeight    = Array();
		this.subformWidth        = Array();
		this.subformIndex        = Array();
		this.subformParentID     = Array();
		this.subformType	     = Array();

	}

	this.getWindowInfo = function(id,parentSession){        //-- get information for window

		for(var i = 0 ; i < parentSession.nuWindows.length ; i++){
			
			if(parentSession.nuWindows[i].id == id){
				return parentSession.nuWindows[i];
			}

		}
	};


	this.getWindowID = function(){        //-- gets ID and increments by 1

		var i                    = this.nuWindowID;
		this.nuWindowID          = Number(this.nuWindowID) + 1;
		return i;

	}

	this.setBreadCrumb = function(w){        //-- Add Bread Crumb

		this.breadCrumb.push(w);

	}

	this.getBreadCrumb = function(pindex){  //-- Get Bread Crumb

		var bc;
		while (this.breadCrumb.length > pindex){
			bc                   = this.breadCrumb.pop();
		}
	
		return bc;

	}


	this.reloadBreadCrumb = function(pindex){  //-- Reload Bread Crumb

		return this.breadCrumb[pindex];

	}

	this.setSessionID = function(ID){  //-- Set Session ID

		this.nuSessionID         = ID;

	}


	this.setDirectory = function(dir){  //-- Set Session ID

		this.nuDirectory         = dir;

	}

	this.getSubformIndex = function(name){  //-- returns the index for a subform

		for(var i = 0 ; i < this.subformName.length ; i++){
			if(this.subformName[i] == name){
				return i;
			}
		}
		return -1;  //-- not found

	}

	this.addSessionSubform = function(name,rows,html,height,width,columns,i,parent,type){

		for(var I = 0 ; I < this.subformName.length ; I++){
			if(this.subformName[I] == name){return;}  //-- don't add it twice
		}
		this.subformName.push(name);
		this.subformRowNumber.push(rows);
		this.subformRowHTML.push(html);
		this.subformRowHeight.push(height);
		this.subformWidth.push(width);
		this.subformColumns.push(columns);
		this.subformIndex.push(i);
		this.subformParentID.push(parent);
		this.subformType.push(type);

	}

	this.rebuildSubforms = function(o){
		
		$('#nuFormPrimaryKey').val(o.records[0]);    //-- reset Form Primary Key
		
		for(var i = 0 ; i < this.subformIndex.length-1 ; i++){

			var form         = new nuRecordObjects(this.subformType[i], 0, 0);
			form.prefix       = this.subformName[i]+'0000';
			form.sfName       = this.subformName[i];
			form.row          = '0000';
			
			form.nuBuildSubform(o.objects,this.subformIndex[i],this.subformParentID[i]);
			
		}

	}

	this.addSubformRow = function(pthis){ //-- add subform row
	
		nuFORM.last_subform_row = pthis.id.substr(0, pthis.id.length-6);

		if($('#'+pthis.id).attr('data-addable') == 'no'){return;}
		var name      = pthis.getAttribute('data-subform');
		var row       = Number(pthis.getAttribute('data-row'));
		var i         = this.getSubformIndex(name);
		var n         = Number(this.subformRowNumber[i]);
		var last      = name+("000" + String(row)).slice(-4);

		if(n != row){return;}
		$('#'+last+'_nuDelete').removeAttr('checked');
		
		var was                     = name+("000" + String(n  )).slice(-4);
		var now                     = name+("000" + String(n+1)).slice(-4);
		
		if($('#nuCalendar').length == 0){
			var cal                 = '';
		}else{
			var cal                 = window.nuCalendarCaller;
		}
		
		$('#nuCalendar').remove();
		var h                       = $('#'+pthis.id).html();
		this.subformRowNumber[i]    = n+1;
		var rx                      = new RegExp(was, 'g');
		this.subformRowHTML[i]      = h.replace(rx, now);                      //-- replace new row prefix
		e                           = document.createElement('div');           //-- create wrapper surrounding Subform Row

		e.setAttribute('id', now+'_nuRow');
		e.setAttribute('data-subform', name);
		e.setAttribute('onkeypress', 'nuSession.addSubformRow(this)');
		e.setAttribute('onclick', 'nuSession.addSubformRow(this)');
		e.setAttribute('data-row', ("000" + String(n+1)).slice(-4));
		$('#objects_'+name).append(e);
		$('#' + e.id).css( 'top', (this.subformRowNumber[i] * this.subformRowHeight[i])+'px');
		$('#' + e.id).css( 'width', (Number(this.subformWidth[i])-40)+'px');
		$('#' + e.id).css( 'left', '0px');
		$('#' + e.id).css( 'height', this.subformRowHeight[i]+'px');
		$('#' + e.id).css( 'position', 'absolute');
		$('#' + e.id).html(this.subformRowHTML[i]);
		
		if(cal != ''){
			nuPopupCalendar(document.getElementById(cal));
		}
		
		$('#' + e.id + ' .ui-autocomplete-input').each(function(i){
			nuAutocomplete(this);
		})

		$('#'+now+'_nuDelete').attr('checked','checked');
	}
}


function nuAddRow(sf){

   var r = nuSubformArray(sf).pop();        //-- get last row in subform
   $('#' + r + '_nuRow').click();

}   


function nuSetEdited(){

    if(nuFORM.edited == '0'){
        for(var i  = 0 ; i < $('.nuButton').length ; i++){
            
            var id = $('.nuButton')[i].id;
            
            if($('#' + id).attr('onclick')== "nuSaveForm()"){
		$('#' + id).toggleClass('nuButton');
		$('#' + id).toggleClass('nuNotSaved');
            }
            
        }
        
    }

    nuFORM.edited = '1';

}

function nuBuilderFormat(pthis){

    pthis.value   = formatter.formatField(pthis.getAttribute('data-nuformat') ,pthis.value);

}

function nuCurrentRow(){

	return String(nuFORM.last_subform_row);

}
