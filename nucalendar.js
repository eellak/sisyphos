
window.nuCalColor = '#F0F0F0';
window.nuCalTop   =  - 30;

function nuAppendChild(p,t,i){

	var o                                     = document.createElement(t);
	o.setAttribute('id',i);
	p.appendChild(o);
	document.getElementById(i).style.zIndex   = 3000;
	return document.getElementById(i);
	
}

function nuPopupCalendar(pThis){

	if(pThis===null){return;}

	$('#nuCalendar').remove();

	window.nuCalendarCaller  = pThis.id;
	var p                    = document.getElementById('nuTabAreaHolder');
	
	var pos                  = $('#' + pThis.id).position();
	var sf                   = $('#' + pThis.id).attr('data-prefix');
	var top;
	var left;
	
	if(sf == ''){                                                                           //-- on main form
	
		if($('#nu_holder_'  + pThis.id).length == 0){                                       //-- still in table
		
			var right         = $('#td_right_'  + pThis.id).position();
			top               = right.top + 22;
			left              = right.left + 2;
			
		}else{                                                                              //--  has been dragged
		
			var right         = $('#nu_holder_'  + pThis.id).position();
			var title         = $('#td_left_'  + pThis.id).width();
			top               = right.top + 22;
			left              = right.left + parseInt(title) + 4;
		}
	}else{                                                                                  //-- in a subform
		var holder           = $('#nu_holder_' + sf.substr(0, sf.length-4)).position();
		var right            = $('#td_right_'  + pThis.id).position();
		var bottoma          = $('#objects_'   + sf.substr(0, sf.length-4)).position();
		var bottomb          = $('#scroll_'   + sf.substr(0, sf.length-4)).position();
		var bottomc          = $('#' + sf + '_nuRow').position();
		
		top                  = holder.top  + bottoma.top + bottomb.top + bottomc.top + 22;
		left                 = holder.left + right.left;
	}
	
	window.nuOnCalendar      = 0;          //-- cursor not in calendar
	
	var c                    = nuAppendChild(p,'div','nuCalendar');

	c.onmouseover            = function(){window.nuOnCalendar = 1;};
	c.onmouseout             = function(){window.nuOnCalendar = 0;};

	c.style.backgroundColor  = window.nuCalColor;
	c.style.position         = 'absolute';
	c.style.top              = top + 'px';
	c.style.left             = left + 'px';
	c.style.width            = '210px';
	c.style.height           = '213px';
	c.style.color            = '#000000';
	c.style.borderStyle      = 'solid'; 
	c.style.borderWidth      = '1px'; 
	c.style.borderColor      = 'grey'; 
	c.style.borderTopLeftRadius = '5px'; 
	c.style.borderTopRightRadius = '5px'; 
	c.style.borderBottomLeftRadius = '5px'; 
	c.style.borderBottomRightRadius = '5px'; 
	c.style.boxShadow               = '5px 5px 5px #888888';
	
	var c = nuAppendChild(document.getElementById('nuCalendar'),'div','nuCalCloser');

	c.setAttribute("onclick", "document.getElementById('nuCalendar').remove();");
	c.style.position         = 'absolute';
	c.style.top              = (window.nuCalTop + 32) + 'px';
	c.style.left             = '2px';
	c.style.width            = '20px';
	c.style.height           = '20px';
	c.style.backgroundColor  = 'lightgrey';
	c.style.textAlign        = 'center';
	c.style.fontSize         = '14px';
	c.style.fontStyle        = 'bold';
	c.style.color            = '#000000';
	c.style.cursor           = 'pointer';
	c.style.borderTopLeftRadius = '5px'; 
	c.style.borderTopRightRadius = '5px'; 
	c.style.borderBottomLeftRadius = '5px'; 
	c.style.borderBottomRightRadius = '5px'; 
	c.innerHTML              = '&#x2716;';

	var c = nuAppendChild(document.getElementById('nuCalendar'),'div','nuCalYear');
	
	c.style.position         = 'absolute';
	c.style.top              = (window.nuCalTop + 55) + 'px';
	c.style.left             = '60px';
	c.style.width            = '90px';
	c.style.height           = '25px';
	c.style.backgroundColor  = window.nuCalColor;
	c.style.textAlign        = 'center';
	c.style.fontSize         = '14px';
	c.style.color            = '#000000';

	var c = nuAppendChild(document.getElementById('nuCalendar'),'div','nuCalYearLess');

	c.setAttribute("onclick", "window.nuCalYear--;nuPopulateCalendar('')");

	c.onmouseover            = function(){this.style.backgroundColor = ''};
	c.onmouseout             = function(){this.style.backgroundColor = window.nuCalColor;};
	
	c.style.position         = 'absolute';
	c.style.top              = (window.nuCalTop + 55) + 'px';
	c.style.left             = '40px';
	c.style.width            = '30px';
	c.style.height           = '25px';
	c.style.cursor           = 'pointer';
	c.className              = 'nuBrowseRowSelected';
	c.style.backgroundColor  = window.nuCalColor;
	c.style.textAlign        = 'center';
	c.style.color            = '#000000';
	c.style.fontSize         = '14px';
	c.innerHTML              = '&#9668;';

	var c = nuAppendChild(document.getElementById('nuCalendar'),'div','nuCalYearMore');

	c.setAttribute("onclick", "window.nuCalYear++;nuPopulateCalendar('')");
	
	c.onmouseover            = function(){this.style.backgroundColor = ''};
	c.onmouseout             = function(){this.style.backgroundColor = window.nuCalColor;};
	
	c.style.position         = 'absolute';
	c.style.top              = (window.nuCalTop + 55) + 'px';
	c.style.left             = '140px';
	c.style.width            = '30px';
	c.style.height           = '25px';
	c.style.cursor           = 'pointer';
	c.className              = 'nuBrowseRowSelected';
	c.style.backgroundColor  = window.nuCalColor;
	c.style.textAlign        = 'center';
	c.style.color            = '#000000';
	c.style.fontSize         = '14px';
	c.innerHTML              = '&#9658;';

	var c = nuAppendChild(document.getElementById('nuCalendar'),'div','nuCalMonth');
	
	c.style.position         = 'absolute';
	c.style.top              = (window.nuCalTop + 75) + 'px';
	c.style.left             = '60px';
	c.style.width            = '90px';
	c.style.height           = '25px';
	c.style.backgroundColor  = window.nuCalColor;
	c.style.fontSize         = '12px';
	c.style.textAlign        = 'center';
	c.style.color            = '#000000';

	var c = nuAppendChild(document.getElementById('nuCalendar'),'div','nuCalMonthLess');

	c.setAttribute("onclick", "window.nuCalMonth--;nuPopulateCalendar(this.id)");

	c.onmouseover            = function(){this.style.backgroundColor = ''};
	c.onmouseout             = function(){this.style.backgroundColor = window.nuCalColor;};
	
	c.style.position         = 'absolute';
	c.style.top              = (window.nuCalTop + 75) + 'px';
	c.style.left             = '40px';
	c.style.width            = '30px';
	c.style.height           = '25px';
	c.style.cursor           = 'pointer';
	c.className              = 'nuBrowseRowSelected';
	c.style.backgroundColor  = window.nuCalColor;
	c.style.textAlign        = 'center';
	c.style.color            = '#000000';
	c.style.fontSize         = '14px';
	c.innerHTML              = '&#9668;';

	var c = nuAppendChild(document.getElementById('nuCalendar'),'div','nuCalMonthMore');

	c.setAttribute("onclick", "window.nuCalMonth++;nuPopulateCalendar(this.id)");

	c.onmouseover            = function(){this.style.backgroundColor = ''};
	c.onmouseout             = function(){this.style.backgroundColor = window.nuCalColor;};
	
	c.style.position         = 'absolute';
	c.style.top              = (window.nuCalTop + 75) + 'px';
	c.style.left             = '140px';
	c.style.width            = '30px';
	c.style.height           = '25px';
	c.style.cursor           = 'pointer';
	c.className              = 'nuBrowseRowSelected';
	c.style.backgroundColor  = window.nuCalColor;
	c.style.textAlign        = 'center';
	c.style.color            = '#000000';
	c.style.fontSize         = '14px';
	c.innerHTML              = '&#9658;';
	
	var t                    = 90; 
	var l                    = 0;

	for(var i = 0 ; i < 42 ; i++){
	
		if(t == 90){nuTitleBox(i, l);}
		nuDayBox(i, l, t)
		if(l == 180){
			l                = 0;
			t                = t + 20;
		}else{
			l                = l + 30;
		}
		
	}
	
	var fd                   = formatter.formatField($('#' + window.nuCalendarCaller).attr('data-nuformat') ,$('#' + window.nuCalendarCaller).val(),true);  //-- format value for sql string	

	var d                    = new Date();

	if(fd != ''){
	
		d.setDate(Number(fd.split('-')[2]));
		d.setMonth(Number(fd.split('-')[1]) - 1);
		d.setFullYear(fd.split('-')[0]);
	}

	nuPopulateCalendar('', d.getFullYear(), d.getMonth(), d.getDate());
	
}

function nuTitleBox(n, l){

	var t                    = nuTranslate('ΚΔΤΤΠΠΣ');
	var c                    = nuAppendChild(document.getElementById('nuCalendar'),'div','nuCalTitle'+n);
	
	
	c.style.position         = 'absolute';
	c.style.top              = (window.nuCalTop + 97) + 'px';
	c.style.left             = l + 'px';
	c.style.width            = '28px';
	c.style.height           = '18px';
	c.style.backgroundColor  = '#EDEDED';
	c.style.color            = '#000000';
	c.style.borderColor      = '#D3D3D3';
	c.style.borderStyle      = 'solid';
	c.style.borderWidth      = '1px';
	c.style.fontSize         = '14px';
	c.style.textAlign        = 'center';
	c.innerHTML              = t.substr(n, 1);

}

function nuDayBox(n, l, t){

	var c                    = nuAppendChild(document.getElementById('nuCalendar'),'div','nuCalDay'+n);
	var today                =  new Date();
	
	c.onmouseover            = function(){this.style.backgroundColor = ''};
	c.onmouseout             = function(){this.style.backgroundColor = window.nuCalColor;};

	c.setAttribute("onclick", "window.nuCalDay=this.innerHTML;nuCalChoice()");
	
	c.style.position         = 'absolute';
	c.className              = 'nuBrowseRowSelected';
	c.style.backgroundColor  = window.nuCalColor;
	c.style.top              = (window.nuCalTop + t + 30) + 'px';
	c.style.left             = l + 'px';
	c.style.width            = '30px';
	c.style.height           = '20px';
	c.style.fontSize         = '14px';
	c.style.textAlign        = 'center';
	c.style.cursor           = 'pointer';

}

function nuPopulateCalendar(id, y, m, d){

	if(arguments.length != 1){
		window.nuCalDay                                   = d;
		window.nuCalMonth                                 = m;
		window.nuCalYear                                  = y;
	}
	window.nuCalMonth = nuMonthScope(window.nuCalMonth);
	
	if(id == 'nuCalMonthLess' && window.nuCalMonth == 11){
		window.nuCalYear = window.nuCalYear - 1;
	}
	
	if(id == 'nuCalMonthMore' && window.nuCalMonth == 0){
		window.nuCalYear = window.nuCalYear + 1;
	}
	
	document.getElementById('nuCalYear').innerHTML        = window.nuCalYear;
	document.getElementById('nuCalMonth').innerHTML       = nuTranslate(nuFullMonth(window.nuCalMonth));
	var s                                                 = new Date(window.nuCalYear, window.nuCalMonth, 1);
	var today                                             = new Date();
	var day                                               = 0;
	var nextmonth                                         = 0;
	
	for(var i = 0 ; i < 42 ; i++){
	
		document.getElementById('nuCalDay' + i).innerHTML = '';
		
	}
	
	for(var i = s.getDay() ; i < 42 ; i++){
		
		day++;
		s.setDate(day);
		c = document.getElementById('nuCalDay' + i);
		
		if(s.getDate() != day){
			return;
		}
		
		if(today.getDate() == day && today.getMonth() == window.nuCalMonth && today.getFullYear() == window.nuCalYear){
			c.style.color            = 'red';
		}else{
			c.style.color            = '#000000';
		}
		c.innerHTML = day;
		
	}

}


function nuPreviousMonth(y, m, d){

	m = nuMonthScope(m-1);
	
	var d   = new Date(y, m, 1);
	
	var p   = Array();
	var day = 1;
	debugger;
	while (d.getDate() == day) {
	
		p.push(day);
		d.setDate(day);
		day++;

	}	
	return p;

}


function nuFullMonth(n){
	
	var m  = Array();
	
	m[0]   = 'Ιανουάριος';
	m[1]   = 'Φεβρουάριος';
	m[2]   = 'Μάρτιος';
	m[3]   = 'Απρίλιος';
	m[4]   = 'Μάιος';
	m[5]   = 'Ιούνιος';
	m[6]   = 'Ιούλιος';
	m[7]   = 'Αύγουστος';
	m[8]   = 'Σεπτέμβριος';
	m[9]   = 'Οκτώβριος';
	m[10]  = 'Νοέμβριος';
	m[11]  = 'Δεκέμβριος';

	return String(m[n]);
	
}


function nuCalChoice(){

	window.nuCalMonth = nuMonthScope(window.nuCalMonth);
	var df            = document.getElementById(window.nuCalendarCaller);
	var d             = new Date(window.nuCalYear, window.nuCalMonth, window.nuCalDay);
	df.value          = nuFormatDateByString(d, nuFormats[df.getAttribute('data-nuformat')].format)

	$('#nuCalendar').remove();
	$('#' + df.id).change();
	$('#' + df.id).focus();
	
	
	
}


function nuMonthScope(m){

	if(m<0){m=11;}
	if(m>11){m=0;}
	
	return m;

}

