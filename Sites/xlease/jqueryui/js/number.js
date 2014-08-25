// JavaScript Document By jakapan
function dokeyup( obj ,e)
{
	    var key;
   
    if(window.event){
        key = window.event.keyCode; // IE

    }else{
        key = e.which; // Firefox       

  }

if( key != 37 & key != 39 & key != 190 & key != 110)
{
var value = obj.value;
var svals = value.split( "." ); //แยกทศนิยมออก
var sval = svals[0]; //ตัวเลขจำนวนเต็ม

var n = 0;
var result = "";
var c = "";
for ( a = sval.length - 1; a >= 0 ; a-- )
{
c = sval.charAt(a);
if ( c != ',' )
{
n++;
if ( n == 4 )
{
result = "," + result;
n = 1;
};
result = c + result;
};
};

if ( svals[1] )
{
result = result + '.' + svals[1];
};

obj.value = result;
};
};

//ให้ text รับค่าเป็นตัวเลขอย่างเดียว onKeyPress="checknumber(event)"
function checknumber(e) //number and dot
{
	    if(window.event){
        key = window.event.keyCode; // IE

    }else{
        key = e.which; // Firefox       

  }
   

if ( key != 46 & ( key < 48 || key > 57 ) & key != 8 )
{
	    if(window.event){
      event.returnValue = false; // IE

    }else{
       e.preventDefault(); // Firefox       

  }

};
};
// onKeyPress="checknumber2(event)"
function checknumber2(e) // number no dot
{
	    if(window.event){
        key = window.event.keyCode; // IE

    }else{
        key = e.which; // Firefox       

  }


if ( (key < 48 || key > 57 ) & key != 8 )
{
	    if(window.event){
      event.returnValue = false; // IE

    }else{
       e.preventDefault(); // Firefox       

  }

};
};

function checknumber3(e) // number and -
{
	    if(window.event){
        key = window.event.keyCode; // IE

    }else{
        key = e.which; // Firefox       

  }


if ( (key < 48 || key > 57 ) & key != 8  & key != 189 )
{
	    if(window.event){
      event.returnValue = false; // IE

    }else{
       e.preventDefault(); // Firefox       

  }

};
};
function addCommas(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}