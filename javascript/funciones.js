
_FORMAT_FECHA_FORM = "%Y-%m-%d";
_FORMAT_FECHA_FORM2 = "%Y%m%d";

//**** funcion que evita que el campo solo contega caracter espacio


 function vacio(q,msg) {

 for ( i = 0; i<q.length; i++ ) {
  if ( q.charAt(i) != " " ) {
          return true;
         }
 }

 alert(msg);
 return false;

}

 function vacio_sm(q) {

 for ( i = 0; i<q.length; i++ ) {
  if ( q.charAt(i) != " " ) {
          return true;
         }
 }

 return false;

}
 
//********************************** FUNCION PARA UN COMENTARIO ********************************


// function que verifica que el campo tenga caracteres para un cuadro de comentario

function comentario(campo,caract_extra,nulo,msg){
  if (nulo == 1 && campo == ""){
    alert("No debe dejar este campo vacio");
    return false;}

  var ubicacion;
  var enter = "\n";
  var caracteres="abcdefghijklmnopqrstuvwxyzñ1234567890AQZXSWEDCVFRTGBNÑHYMJUIKLOP:=.,$@¿?\\//~+-*\"#'&¡!()[]áéíóúÁÉÍÓÚÀÈÌÒÙàèìòù"+String.fromCharCode(13)+enter+caract_extra;

  var contador = 0;    
      for (var i=0; i < campo.length; i++){
            ubicacion = campo.substring(i, i + 1);
            if (caracteres.indexOf(ubicacion) != -1){
              contador ++;}
            else{
              alert(msg);
              return false;}
      }
}

// ********************** VERIFICA EL LARGO DEL CAMPO ***********************************

// Campo es el nombre del campo a verificar y largo el numero maximo de caracteres

function largo(campo,largo){

 if (campo.length > largo){ 
     alert ("El Campo Sobrepasa el largo permitido de "+largo+" caracteres");
     return false;}
  }

// ******************** FUNCION ALFABETICO *****************************************

//  verifica que el campo tenga solo caracteres alfabeticos + caracteres especiales

function alfabeticos(campo,carac_extra,nulo,msg){
   if (nulo == 1 && campo == ""){
     alert("No debe dejar este campo vacio");
     return false;}
  
  var ubicacion;
  var alfabetico ="abcdefghijklmnopqrstuvwñxyzAQZXSWEDCVFRTGBNHYMJÑUIKLOPáéíóúÁÉÍÓÚ";
  var caracteres = alfabetico + carac_extra;
  var contador = 0;    

      for (var i=0; i < campo.length; i++){
            ubicacion = campo.substring(i, i + 1);
            if (caracteres.indexOf(ubicacion) != -1)
                   contador ++;
            else{
                alert (msg);
                return false;}

      }
}


// ******************* FUNCION ALFANUMERICO  **********************************

// function que verifica que el campo tenga solo caracteres alfanumericos pudiendo opcionalmente agregar caracteres
// permite que el campo quede vacio o no vacio = 0 permite quequede vacio vacio = 1 no puede quedar vacio

function alfanumero(campo,carac_extra,nulo,msg){
  if (nulo == 1 && campo == ""){
    alert("No debe dejar este campo vacio");
    return false;}

    var ubicacion;
    var alfanumerico ="abcdefghijklmnopqrstuvwxyzñ1234567890AQZXSWEDCVFRTGBNHYMJUIKLOPáéíóúÁÉÍÓÚ";
    var contador = 0;    
    var caracteres = alfanumerico + carac_extra;

  //  verifica que el campo tenga solo caracteres alfanumeico + caracteres extra
      for (var i=0; i < campo.length; i++){
            ubicacion = campo.substring(i, i + 1);
            if (caracteres.indexOf(ubicacion) != -1)
                   contador ++;
            else{
                alert (msg);
                return false;}
      }
 }


// ****************** FUNCION TELEFONO ****************

// function que verifica que el campo tenga solo caracteres validos para un numero de telefono
// permite que el campo quede vacio dependiendo de la opcion 1 o 0

function telefono(campo,carac_extra,nulo,msg){
   if (nulo == 1 && campo == ""){
    alert("No debe dejar este campo vacio");
    return false;}

    var ubicacion;
    var car_fono = "1234567890";
    var caracteres = car_fono + carac_extra;
    var contador = 0;    

     for (var i=0; i < campo.length; i++){
            ubicacion = campo.substring(i, i + 1);
            if (caracteres.indexOf(ubicacion) != -1){
           contador ++;
      }
}
    if (contador != campo.length) {
          alert(msg);
          return false;}
}

// *************************** FUNCION PATH ****************************

// function que verifica que el campo tenga solo caracteres validos para una ruta de archivo + caracteres opcionales

function path(StrObj,msg){
 var urlArray = StrObj.match(/(http:\/\/)+([a-zA-Z0-9._-]+\.[\/~a-zA-Z0-9._-]+)/gi);

 if (urlArray == null) {
  alert(msg);
  return false; 
    }
 
   return true;
}

//* *********************** FUNCION NUMERICO  ****************************

// El campo solo recive datos numerico y puede estar vacia
 function numerico(campo,carac_extra,nulo,msg){
   if (nulo == 1 && campo == ""){
     alert(msg);
     return false;}
  
  var ubicacion;
  var numeros = "1234567890";
  var caracteres = numeros + carac_extra;
  var contador = 0;    

  //  verifica que el campo tenga solo caracteres numericos

      for (var i=0; i < campo.length; i++){
            ubicacion = campo.substring(i, i + 1);
            if (caracteres.indexOf(ubicacion) != -1)
           contador ++;
      }

    if (contador != campo.length) {
          alert(msg);
          return false;}
}
      
// ************** FUNCION QUE TRANSFORMA La " " a %20 ************************
function change_char(campo){
   var posicion = new String();
   var new_string = new String();
   var space = " ";
   var contador = 0;    

      for (var i=0; i < campo.length; i++){
              new_string = new_string + posicion;
              posicion = campo.substring(i, i + 1);
        if (space.indexOf(posicion) == -1)
            contador ++; 
        else
             posicion = "%20"; 
      }
   new_string = new_string + posicion;
   return new_string;
}

function CuentaChar(caracter,campo){
  var contador = 0;

  for (var i=0; i < campo.length; i++){
  ubicacion = campo.substring(i, i + 1);
  if (caracter.indexOf(ubicacion) != -1)
     contador ++;
  }
  return contador;
}

//******************************** FUNCION QUE VALIDA FECHA ******************************

function isValidDate(dateStr,nulo) {

var datePat = /^(\d{1,2})(\/|-)(\d{1,2})\2(\d{4}|\d{4})$/;

   if (dateStr == "" && nulo == 0) {
         dateStr = "10/12/1990";
   }

var matchArray = dateStr.match(datePat); 

 if (matchArray == null) {
       alert("Ingrese una fecha valida.");
       return false;}

month = matchArray[3]; 
day = matchArray[1];
year = matchArray[4];


/*if (year < 0){
  alert("El año tiene que ser distinto a 0");
   return false;  
 }*/

if (month < 1 || month > 12) { 
 alert("El mes va entre 1 y 12.");
 return false;
}

if (day < 1 || day > 31) {
 alert("El día va entre 1 y 31.");
 return false;
}

if ((month==4 || month==6 || month==9 || month==11) && day==31) {
 alert("El Mes "+month+" no tiene 31 días.")
 return false
}

if (month == 2) { 
 var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
 if (day>29 || (day==29 && !isleap)) {
  alert("Febrero de " + year + " no tiene " + day + " días.");
  return false;
 }
  }

if(year == false){
   alert("El año debe ser distinto a 0");
    return false;}

  return true;
}

 
function valnumerico(obj){ 
 var campo = obj.value; 
 var ubicacion; 
 var numeros = "1234567890"; 
 var caracteres = numeros; 
 var contador = 0;     
 
  //  verifica que el campo tenga solo caracteres numericos 
 
 for (var i=0; i < campo.length; i++){ 
  ubicacion = campo.substring(i, i + 1); 
  if (caracteres.indexOf(ubicacion) != -1) 
    contador ++; 
 } 
 
 if (contador != campo.length) { 
    alert(NUM_VAL); 
    obj.value = ""; 
    obj.focus(); 
 } 
} 
 
function valfecha(obj){ 
  
 if(isValidDateUni(obj.value,0) == false){ 
  alert(DATE_VAL);  
  obj.value = ""; 
  obj.focus(); 
 } 
} 
 
function valtexto(obj){ 
 if(vacio2(obj.value) == false) 
  obj.value = ""; 
} 
 
function valnada(obj){ 
  
} 
 
 function vacio2(q) { 
 for ( i = 0; i<q.length; i++ ) { 
  if ( q.charAt(i) != " " ) { 
          return true; 
         } 
 } 
 return false; 
} 
 
function isValidDateUni(dateStr, nulo) { 
 
 var datePat = /^(\d{1,2})(\/|-)(\d{1,2})\2(\d{4}|\d{4})$/; 
 
   if (dateStr == "" && nulo == 0) { 
         dateStr = "10/12/1990"; 
   }  
  
 var matchArray = dateStr.match(datePat);  
 
  if (matchArray == null) 
     return false; 
 
 month = matchArray[3];  
 day = matchArray[1]; 
 year = matchArray[4]; 
 
 
 /*if (year < 0){ 
   alert("El año tiene que ser distinto a 0"); 
    return false;   
  }*/ 
 
 if (month < 1 || month > 12)  
  return false; 
 
 if (day < 1 || day > 31)  
  return false; 
  
 if ((month==4 || month==6 || month==9 || month==11) && day==31)  
  return false 
 
 if (month == 2) {  
  var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0)); 
  if (day>29 || (day==29 && !isleap)) 
   return false; 
   } 
 
 if(year == false) 
  return false; 
 
   return true; 
} 
 

/********/
function isValidDateGringa(dateStr, nulo) { 
 
  dateStr = "01/" + dateStr.substring(4,6) + "/" + dateStr.substring(0,4);

// var datePat = /^(\d{1,2})(\/|-)(\d{1,2})\2(\d{4}|\d{4})$/; 
 var datePat = /^(\d{1,2})(\/|-)(\d{1,2})\2(\d{4}|\d{4})$/; 
 
   if (dateStr == "" && nulo == 0) { 
         dateStr = "10/12/1990"; 
   }  
  
 var matchArray = dateStr.match(datePat);  
 
  if (matchArray == null) 
     return false; 
 
 month = matchArray[3];  
 day = matchArray[1]; 
 year = matchArray[4]; 
 
 
 /*if (year < 0){ 
   alert("El año tiene que ser distinto a 0"); 
    return false;   
  }*/ 
 
 if (month < 1 || month > 12)  
  return false; 
 
 if (day < 1 || day > 31)  
  return false; 
  
 if ((month==4 || month==6 || month==9 || month==11) && day==31)  
  return false 
 
 if (month == 2) {  
  var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0)); 
  if (day>29 || (day==29 && !isleap)) 
   return false; 
   } 
 
 if(year == false) 
  return false; 
 
   return true; 
} 

// Verifica que el email sea valido y no este vacio 
 
function email(emailStr,msg) { 
//msg = "Ingrese un email valido"; 
var emailPat=/^(.+)@(.+)$/ 
var specialChars="\\(\\)<>@,;:\\\\\\\"\\.\\[\\]" 
var validChars="\[^\\s" + specialChars + "\]" 
var firstChars=validChars 
var quotedUser="(\"[^\"]*\")" 
var ipDomainPat=/^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/ 
var atom="(" + firstChars + validChars + "*" + ")" 
var word="(" + atom + "|" + quotedUser + ")" 
var userPat=new RegExp("^" + word + "(\\." + word + ")*$") 
var domainPat=new RegExp("^" + atom + "(\\." + atom +")*$") 
var matchArray=emailStr.match(emailPat) 
 
if (matchArray==null) { 
   alert(msg) 
   return false; 
} 
 
var user=matchArray[1] 
var domain=matchArray[2] 
 
if (user.match(userPat)==null) { 
    alert(msg) 
    return false 
} 
var IPArray=domain.match(ipDomainPat) 
if (IPArray!=null) {  
   for (var i=1;i<=4;i++) { 
     if (IPArray[i]>255) { 
         alert(msg) 
  return false 
     } 
    } 
    return true 
} 
 
var domainArray=domain.match(domainPat) 
if (domainArray==null) { 
 alert(msg) 
    return false 
} 
 
var atomPat=new RegExp(atom,"g") 
var domArr=domain.match(atomPat) 
var len=domArr.length 
if (domArr[domArr.length-1].length<2 ||  
    domArr[domArr.length-1].length>4) { 
   alert(msg) 
   return false 
} 
 
if (domArr[domArr.length-1].length >= 2 && len < 2) { 
   var errStr=msg; 
   alert(errStr) 
   return false 
} 
return true; 
} 
 
function alltrim(sEmail){ 
 return sEmail; 
} 
 
function Trim(TRIM_VALUE){ 
    if(TRIM_VALUE.length < 1){ 
 return""; 
    } 
    TRIM_VALUE = RTrim(TRIM_VALUE); 
    TRIM_VALUE = LTrim(TRIM_VALUE); 
    if(TRIM_VALUE==""){ 
 return ""; 
    } 
    else{ 
 return TRIM_VALUE; 
    } 
} //End Function 
 
function RTrim(VALUE){ 
    var w_space = String.fromCharCode(32); 
    var v_length = VALUE.length; 
    var strTemp = ""; 
    if(v_length < 0){ 
 return""; 
    } 
    var iTemp = v_length -1; 
 
    while(iTemp > -1){ 
 if(VALUE.charAt(iTemp) == w_space){ 
        } 
        else{ 
     strTemp = VALUE.substring(0,iTemp +1); 
            break; 
 } 
 iTemp = iTemp-1; 
 
    } //End While 
    return strTemp; 
 } //End Function 
 
function LTrim(VALUE){ 
var w_space = String.fromCharCode(32); 
if(v_length < 1){ 
return""; 
} 
var v_length = VALUE.length; 
var strTemp = ""; 
 
var iTemp = 0; 
 
while(iTemp < v_length){ 
if(VALUE.charAt(iTemp) == w_space){ 
} 
else{ 
strTemp = VALUE.substring(iTemp,v_length); 
break; 
} 
iTemp = iTemp + 1; 
} //End While 
return strTemp; 
} //End Function 


// ******************* FUNCION VALIDA RUT ************************************

//Valida rut con un cuadro de texto

function rut(campo,msg){
var suma = 0;
var contador = 0;
var caracteres = "1234567890-kK";
var rut = campo.substring(0,8);
var drut = campo.substring(9,10);
var dvr = '0';
var mul = 2;

if ( campo.length == 0 ){
   alert(msg);
    return false;
}

if ( campo.length == 9 ){
    rut = campo.substring(0,7);
 drut = campo.substring(8,9);
}

//  verifica que el campo tenga solo caracteres numericos - k

      for (var i=0; i < campo.length; i++){
            ubicacion = campo.substring(i, i + 1);
            if (caracteres.indexOf(ubicacion) != -1)
           contador ++;
      }

    if (contador != 10 && contador != 9){
       alert(msg);
       return false;}


   for (i= rut.length -1 ; i >= 0; i--)
    {
      suma = suma + rut.charAt(i) * mul
        if (mul == 7)
          mul = 2
        else    
          mul++
    }

  res = suma % 11
  if (res==1)
    dvr = 'k'
  else if (res==0)
    dvr = '0'
  else
    {
      dvi = 11-res
      dvr = dvi + ""
    }

  if ( dvr != drut.toLowerCase() )
    { alert(msg);
  return false; }
  else
    { return true; }
}



//**********************************************************************************************
//	invierta una fecha dada retornando en formato YYYYMMDD
//  dFecIni = Fecha a invertir
//	nTipFormat = Formato en que biene la fecha
//				 1 = DD/MM/YYYY 
//				 2 = MM/DD/YYYY	
//				 3 = YYYY/MM/DD
//				 4 = YYYY/DD/MM

function invFecha(nTipFormat,dFecIni){
	var dFecIni = dFecIni.replace(/-/g,"/");					// reemplaza el - por /	
	
	// primera division fecha
	var nPosUno  = ponCero(dFecIni.substr(0,dFecIni.indexOf("/")));
	// 2º divicion fecha
	var nPosDos  = ponCero(dFecIni.substr(parseInt(dFecIni.indexOf("/")) + 1,parseInt(dFecIni.lastIndexOf("/")) - parseInt(dFecIni.indexOf("/")) - 1));
	// 3º divicion fecha
	var nPosTres = ponCero(dFecIni.substr(parseInt(dFecIni.lastIndexOf("/")) + 1));

	switch(nTipFormat){
		case 1 :	//	DD/MM/YYYY
			dReturnFecha = nPosTres + "" + nPosDos + "" + nPosUno;
			break;

		case 2 :	//	MM/DD/YYYY
			dReturnFecha = nPosTres + "" + nPosUno + "" +nPosDos;
			break;

		case 3 :	//	YYYY/MM/DD
			dReturnFecha = nPosUno + "" + nPosDos + "" +nPosTres;
			break;
	
		case 4 :	//	YYYY/DD/MM
			dReturnFecha = nPosUno + "" + nPosTres + "" +nPosDos;
			break;
	}
	
	return dReturnFecha;	// retorna la fecha 	
}

// Agrega un cero delante del strPon cuando tenga solo un caracter
function ponCero(strPon){
	if(parseInt(strPon.length) < 2)
		strPon = "0" + strPon;
	return strPon;
}

//*******************************************************************************
// valida que la fecha dFecMenor es menor o igual a  dFecMayor
// los parametros dFecMenor, dFecMayor son fecha con divisores validos "-" o "/"
// el parametro dFormat es el tipo de formato en que viene la fecha 
//				 1 = DD/MM/YYYY 
//				 2 = MM/DD/YYYY	
//				 3 = YYYY/MM/DD
//				 4 = YYYY/DD/MM

function comparaFecha(dFormat,dFecMenor, dFecMayor){
	dFecMenor = invFecha(dFormat,dFecMenor);
	dFecMayor = invFecha(dFormat,dFecMayor);

	if(dFecMenor > dFecMayor)
		return false;
	else
		return true;
}

/*

ejemplo de como comparar 2 fechas
function validaFecha( formulario ){
	var dFechaMenor = formulario.dFecMenor.value;
	var dFechaMayor = formulario.dFecMayor.value;

	if(comparaFecha( formato,dFechaMenor,dFechaMayor) == true)
		alert("OK. La fecha es menor.");
	else
		alert("Error. La fecha NO es menor.");
}
*/

function cambiarFormatoFecha(dFecIni, nTipFormat,nNewFormat){
// el parametro dFormat es el tipo de formato en que viene la fecha 
//				 1 = DD/MM/YYYY 
//				 2 = MM/DD/YYYY	
//				 3 = YYYY/MM/DD
//				 4 = YYYY/DD/MM

	var dFecIni = dFecIni.replace(/-/g,"/");					// reemplaza el - por /	
	
	// primera division fecha
	var nPosUno  = ponCero(dFecIni.substr(0,dFecIni.indexOf("/")));
	// 2º divicion fecha
	var nPosDos  = ponCero(dFecIni.substr(parseInt(dFecIni.indexOf("/")) + 1,parseInt(dFecIni.lastIndexOf("/")) - parseInt(dFecIni.indexOf("/")) - 1));
	// 3º divicion fecha
	var nPosTres = ponCero(dFecIni.substr(parseInt(dFecIni.lastIndexOf("/")) + 1));


////////////////////////////////////////////////////////////////////
// valida que la fecha corresponda al formato informado
	if((nTipFormat == 1 || nTipFormat == 2) && nPosTres.length != 4)
		return false;		// fecha ingresada no corresponde al informado
	
	if((nTipFormat == 3 || nTipFormat == 4) && nPosUno.length != 4)
		return false;		// fecha ingresada no corresponde al informado
////////////////////////////////////////////////////////////////////

	switch(nTipFormat){
		case 1 :	//	DD/MM/YYYY
			dReturnFecha = newFormat(nPosUno,nPosDos,nPosTres, nNewFormat);
			break;

		case 2 :	//	MM/DD/YYYY
			dReturnFecha = newFormat(nPosDos,nPosUno,nPosTres, nNewFormat);
			break;

		case 3 :	//	YYYY/MM/DD
			dReturnFecha = newFormat(nPosTres,nPosDos,nPosUno, nNewFormat);
			break;
	
		case 4 :	//	YYYY/DD/MM
			dReturnFecha = newFormat(nPosDos,nPosTres,nPosUno, nNewFormat);
			break;
	}

	return dReturnFecha;	// retorna la fecha 	
}

function newFormat(dia,mes,anio, nNewFormat){
	switch(nNewFormat){
		case 1 :	//	DD/MM/YYYY
			dReturnFecha = dia + "-" + mes + "-" + anio;
			break;

		case 2 :	//	MM/DD/YYYY
			dReturnFecha = mes + "-" + dia + "-" + anio;
			break;

		case 3 :	//	YYYY/MM/DD
			dReturnFecha = anio + "-" + mes + "-" + dia;
			break;
	
		case 4 :	//	YYYY/DD/MM
			dReturnFecha = anio + "-" + dia + "-" + mes;
			break;
	}
	return dReturnFecha;
}

