function ltrim(s){return s.replace(/^\s*/,"");s=null;} 
function rtrim(s){return s.replace(/\s*$/,"");s=null;} 
function trim(s){return rtrim(ltrim(s));s=null;}
function email (emailStr){emailStr=trim(emailStr);var emailPat=/^(.+)@(.+)$/;var specialChars="\\(\\)<>@,;:\\\\\\\"\\.\\[\\]";var validChars="\[^\\s"+specialChars+"\]";var firstChars=validChars;var quotedUser="(\"[^\"]*\")";var ipDomainPat=/^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/;var atom="("+firstChars+validChars+"*"+")";var word="("+atom+"|"+quotedUser+")";var userPat=new RegExp("^"+word+"(\\."+word+")*$");var domainPat=new RegExp("^"+atom+"(\\."+atom+")*$");var matchArray=emailStr.match(emailPat);if (matchArray==null){alert("Correo electronico no valido");return false;}var user=matchArray[1];var domain=matchArray[2];if (user.match(userPat)==null){alert("Correo electronico no valido");return false;}var IPArray=domain.match(ipDomainPat);if (IPArray!=null){for (var i=1;i<=4;i++){if (IPArray[i]>255){alert("Correo Electronico sobrepasa el largo permitido");return false;}}return true;}var domainArray=domain.match(domainPat);if (domainArray==null){alert("Correo electronico no valido.");return false;}var atomPat=new RegExp(atom,"g");var domArr=domain.match(atomPat);var len=domArr.length;if (domArr[domArr.length-1].length<2 || domArr[domArr.length-1].length>4){alert("Correo electronico no valido");return false;}if (domArr[domArr.length-1].length >= 2 && len < 2){var errStr="Correo electronico invalido";return false;}return true;emailPat=null;specialChars=null;validChars=null;firstChars=null;quotedUser=null;ipDomainPat=null;atom=null;word=null;userPat=null;domainPat=null;matchArray=null;IPArray=null;domainArray=null;atomPat=null;domArr=null;len=null;user=null;domain=null;}
function numerico(campo,carac_extra,nulo){campo=trim(campo);if (nulo==1 && campo==""){alert("No puede dejar este campo vacio \nprocure ingresar los datos requeridos");return false;}var ubicacion;var caracteres="1234567890."+carac_extra;var contador=0;for (var i=0;i<campo.length; i++){ubicacion=campo.substring(i,i+1);if (caracteres.indexOf(ubicacion) != -1){contador++; }else{alert(ubicacion+" Dato no valido para este campo\nIngrese los datos de forma correcta");return false;}}campo=null;carac_extra=null;nulo=null;ubicacion=null;caracteres=null;contador=null;}
function alfanumerico(campo,carac_extra,nulo){campo=trim(campo);if (nulo==1 && campo==""){alert("No puede dejar este campo vacio \nprocure ingresar los datos requeridos");return false;}var ubicacion;var caracteres="ABCDEFGHIJKLMNÑOPQRSTUVWXYZabcdefghijklmnñopqrstuvwxyz .áéíóúÁÉÍÓÚ0123456789,-/#:,*_(){}[]:+&%"+carac_extra;var contador=0;for (var i=0;i<campo.length; i++){ubicacion=campo.substring(i,i+1);if (caracteres.indexOf(ubicacion) != -1){contador++; }else{alert(ubicacion+" Dato no valido para este campo\nIngrese los datos de forma correcta");return false;}}campo=null;carac_extra=null;nulo=null;ubicacion=null;caracteres=null;contador=null;}
function sele(campo){eval("document.formulario1."+campo+".select();");campo=null;}
function foco(campo){eval("document.formulario1."+campo+".focus();");campo=null;}
function limpia_combo(combo){combo=eval('document.formulario1.'+combo);var i=combo.length;for (i;i>0;--i){combo.options[i]=null;}combo.options[0]=new Option("Seleccione","");}
function cuenta(campo,carac,maximo){if (campo.value.length>maximo) campo.value=campo.value.substring(0,maximo); else carac.value=maximo-campo.value.length;}
function validarut(rut2){var rut=trim(rut2);if (rut.length<9)return(false);i1=rut.indexOf("-");dv=rut.substr(i1+1);dv=dv.toUpperCase();nu=rut.substr(0,i1);cnt=0;suma=0;for (i=nu.length-1; i>=0; --i){dig=nu.substr(i,1);fc=cnt+2;suma+= parseInt(dig)*fc;cnt=(cnt+1)%6;}dvok=11-(suma%11);if (dvok==11) dvokstr="0";if (dvok==10) dvokstr="K";if ((dvok!=11) && (dvok!=10)) dvokstr=""+dvok;if (dvokstr==dv)return(true); else return(false);}
var dias_del_mes=Array[31,29,31,30,31,30,31,31,30,31,30,31];function dias_febrero(ano){return (ano % 4==0 && (!(ano % 100==0) || (ano % 400==0)) ? 29 : 28);}
function valida_fecha(dd,mm,yyyy,largo){if (largo==10){if (mm !="" && !(mm > 0 && mm < 13)){alert ("Fecha no valida, los meses seben ser entre 1 a 12.");return false;}if (dd !="" && !(dd > 0 && dd < 32)){alert ("Fecha no valida, los dias deben ser entre 1 a 31.");return false;}if ((dd!="" && mm!="") && dd > dias_del_mes[mm-1]){alert ("Fecha no valida, mes no corresponde.");return false;}if (yyyy !="" && !(yyyy > 1889 && yyyy < 2011)){alert ("Año no valido.");return false;}if ((mm=="2" || mm=="02" && dd!="" && yyyy!="") && dd > dias_febrero(yyyy)){alert ("Fecha no valida para este año.");return false;}}else{alert("Fecha no valida.");return false;}return true;}function consultar(url,ancho,alto){var gsScreenW=screen.width;var gsScreenH=screen.height;var posionF=(gsScreenW-ancho)/2;var posionC=(gsScreenH-alto)/2;newwin=window.open(url,'','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width='+ancho+',height='+alto+',top='+posionC+',left='+posionF+'');newwin.focus();}
function desconecta(){var form=document.formulario1;form.accion.value='desconecta';form.submit();}
function principal(url,trs){location.href=url+'?trs='+trs;url=null;}
function MM_swapImgRestore(){var i,x,a=document.MM_sr;for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;}
function MM_preloadImages(){var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}}
function MM_findObj(n,d){var p,i,x;if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);if(!x && d.getElementById) x=d.getElementById(n); return x;}
function MM_swapImage(){var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}}
function str_replace($cambia_esto,$por_esto,$cadena){return $cadena.split($cambia_esto).join($por_esto);}
function Textarea_Sin_Enter($char, $id){$textarea=document.getElementById($id);if($char==13){$texto_escapado=escape($textarea.value);if(navigator.appName=="Opera" || navigator.appName=="Microsoft Internet Explorer") $texto_sin_enter=str_replace("%0D%0A", "", $texto_escapado); else $texto_sin_enter=str_replace("%0A", "", $texto_escapado);$textarea.value=unescape($texto_sin_enter);}}

function FmtDate(day,month,year){ 
if (day<10){
sday="0"+day;
}else{
sday=day;
}
if (month<10){
smonth="0"+month;
}else{
smonth=month;
}
return sday+"/"+smonth+"/"+year;
}

function WriteToday(obj,obj2){
test=new Date();
month=test.getMonth();
month=(month * 1)+1;
day=test.getDate();
year=test.getFullYear();
var rng=eval('document.formulario1.'+obj);
rng.value=FmtDate(day,month,year);
var rng=eval('document.formulario1.'+obj2);
rng.value=FmtDate(day,month,year);
}

function WriteYesterday(obj,obj2){
today=new Date();
n=today.getTime();
n=n-86400000;
yesterday=new Date(n);
month=yesterday.getMonth();
month=(month*1)+1;
day=yesterday.getDate();
year=yesterday.getFullYear();
var rng=eval('document.formulario1.'+obj);
rng.value=FmtDate(day,month,year);
var rng=eval('document.formulario1.'+obj2);
rng.value=FmtDate(day,month,year);
}

function WriteYear(obj,obj2){
test=new Date();
month=test.getMonth();
month=(month * 1)+1;
day=test.getDate();
year=test.getFullYear();
var rng=eval('document.formulario1.'+obj);
rng.value=FmtDate(1,1,year);
var rng=eval('document.formulario1.'+obj2);
rng.value=FmtDate(day,month,year);
}

function WriteWeek(a,obj,obj2){
var today=new Date();
var day=today.getDate();
var month=today.getMonth()+1;
var year=today.getFullYear();
if (year < 2000)    // Y2K Fix, Isaac Powell
year=year+1900; // http://onyx.idbsu.edu/~ipowell
var offset=today.getDay();
var week;

if(offset != 0) 
{
day=day - offset;
if ( day < 1) 
{
if ( month==1) day=31+day;
if (month==2) day=31+day;
if (month==3) 
{
if (( year==00) || ( year==04)) 
{
day=29+day;
}
else 
{
day=28+day;
}
} //END if (month==3)

if (month==4) day=31+day;
if (month==5) day=30+day;
if (month==6) day=31+day;
if (month==7) day=30+day;
if (month==8) day=31+day;
if (month==9) day=31+day;
if (month==10) day=30+day;
if (month==11) day=31+day;
if (month==12) day=30+day;
if (month==1)
{
month=12;
year=year - 1;
}
else 
{
month=month - 1;
}
} //END if (day < 1)
} //END if (offset != 0)

week=month+"-"+day+"-"+year; // i.e. 10-31-99

switch(a)
{
case 1:
test=new Date();
day1=test.getDate();
month1=test.getMonth();
year1=test.getFullYear();
month1=(month1 * 1)+1;
var rng=eval('document.formulario1.'+obj2);
rng.value=FmtDate(day1,month1,year1);
var rng=eval('document.formulario1.'+obj);
rng.value=FmtDate(day,month,year);
break;

case 2:
str=FmtDate(day,month,year);
var day2=/([0-9]{1,2})\/[0-9]{1,2}\/[0-9]{4}/;
var month2=/[0-9]{1,2}\/([0-9]{1,2})\/[0-9]{4}/;
var year2=/([0-9]{1,2})\/[0-9]{1,2}\/([0-9]{4})/;

lastSunday=new Date(year,month,day);
n=lastSunday.getTime();     //miliseconds since 01/01/1970

if(day < 7)
{  
n=n - (86400000 * 8);        //substract six days miliseconds to get sunaday befoe last sunday
}       
else
{
n=n - (86400000 * 7)        //substract six days miliseconds to get sunaday befoe last sunday
}

l_lastSunday=new Date(n);

day1=l_lastSunday.getDate();
month1=l_lastSunday.getMonth();
year1=l_lastSunday.getFullYear();

var rng=eval('document.formulario1.'+obj2);
rng.value=FmtDate(day1,month,year);
var rng=eval('document.formulario1.'+obj);
rng.value=FmtDate(day,month,year);

} // END switch(a)


} //END function WriteWeek




function WriteMonth(s,id_1,id_2){
today=new Date();
day=today.getDate();
month=today.getMonth();
month=(month * 1)+1;
year=today.getFullYear(); 
lastYear=year - 1;
switch (s){
case 1:
var rng=eval('document.formulario1.'+id_1);
rng.value=FmtDate(1,month,year);
var rng=eval('document.formulario1.'+id_2);
rng.value=FmtDate(day,month,year);
break;

case 2:
if(month==1){
lastMonth=12;
year=year-1;
}else{
lastMonth=month-1;
lastYear=year;
}
if(lastMonth==12) {lastDay=31; }
if(lastMonth==11) {lastDay=30; }
if(lastMonth==10) {lastDay=31; }
if(lastMonth==9) {lastDay=30; }
if(lastMonth==8) {lastDay=31; }
if(lastMonth==7) {lastDay=31; }
if(lastMonth==6) {lastDay=30; }
if(lastMonth==5) {lastDay=31; }
if(lastMonth==4) {lastDay=30; }
if(lastMonth==3) {lastDay=31; }
if(lastMonth==2) {lastDay=28; }
if(lastMonth==1) {lastDay=31; }
var rng=eval('document.formulario1.'+id_1);
rng.value=FmtDate(1,lastMonth,lastYear);
var rng=eval('document.formulario1.'+id_2);
rng.value=FmtDate(lastDay,lastMonth,year);
break;
case 3:
if(month >= 3){
lastTwoMonth=month - 2;
lastYear=year;
}else{
if (month==1){
lastTwoMonth=11;
}else{
lastTwoMonth=12;
}
}
var rng=eval('document.formulario1.'+id_1);
rng.value=FmtDate(1, lastTwoMonth, lastYear);
var rng=eval('document.formulario1.'+id_2);
rng.value=FmtDate(day,month,year);
break;
} 
} 

function SetEspecificTextRange(a,b,sa,sb,f){
}

function ClearOnClickTextRange(a,f){
}

function ValidateDateFormat(date){
var cont1=0;
var cont2=1;
var cont3=0;
var array=new Array();
array[0]=0;
array[1]=0;
var i=0; 
for(i;i<date.length;i++){
var numb=0;
if( date.charAt(i)=="/"){ 
cont1++;
if(cont1==1){ 
array[0]=i;
}else{
array[1]=i;
}
}else{
var j=0;
for(j;j<10;j++){
if(date.charAt(i)==j){ 
numb=1;  
}
}
if(numb!=1){
cont2=0;
break;
}else{
cont2=1;
}
} 
} 
if((cont1==2) && (cont2==1)){
if((parseFloat(date.substring(0,array[0]))>0) && (parseFloat(date.substring(0,array[0]))<=31)){ 
if(((parseFloat(date.substring(array[0]+1,array[1])))>0) && ((parseFloat(date.substring(array[0]+1,array[1])))<=12)){
if((date.substring(array[1]+1,date.length).length==4) && ((parseFloat(date.substring(array[1]+1,date.length)))>=1997)){
cont3=1;
}
}
} 
}else{
cont3=0;
}
return cont3;   
} 

function ValidateDateOrders(start,end){
var ans=0;
var i=0;
starray=new Array(0,0);
endarray=new Array(0,0);
rnow=new Date();
for(i;i<start.length;i++){
if(start.charAt(i)=="/"){
if(i<3){
starray[0]=i;
}else{
starray[1]=i;
}
}
}
i=0;
for(i;i<end.length;i++){
if(end.charAt(i)=="/"){
if(i<3){
endarray[0]=i;
}else{
endarray[1]=i
}
}
}
var syear=start.substring(starray[1]+1, start.length);
var smonth=start.substring(starray[0]+1, starray[1]);
var sday=start.substr(0,starray[0]);
var eyear=end.substring(endarray[1]+1, end.length);
var emonth=end.substring(endarray[0]+1, endarray[1]);
var eday=end.substr(0,endarray[0]);
var sdate=new Date(syear, smonth, sday);
var edate=new Date(eyear, emonth, eday);
if(sdate.getTime()<=edate.getTime()){
ans=1;
}

if(ans==1){   
var day=rnow.getDate();
var month=rnow.getMonth();
month=(month * 1)+1;                               
var year=rnow.getFullYear();
var rnow1=new Date(year, month, day);
if(edate.getTime()>rnow1.getTime()){
ans=2;
}
}

return ans;
}
function validateCellField(fieldValue){
var ans=1;
if (fieldValue!=""){
var cellNumber=fieldValue;
var exp=new RegExp("[0-9]{7}");
if(!cellNumber.match(exp)){
ans=0;
}
}
return ans;
} 