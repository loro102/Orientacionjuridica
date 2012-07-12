// funzione per assegnare l'oggetto XMLHttpRequest 
// compatibile con i browsers più recenti e diffusi
function assegnaXMLHttpRequest() {
  // lista delle variabili locali
  var XHR = null, browserUtente = navigator.userAgent.toUpperCase();
  // browser standard con supporto nativo non importa il tipo di browser
  if( typeof(XMLHttpRequest) === "function" || typeof(XMLHttpRequest) === "object" ){
    XHR = new XMLHttpRequest();
  }
  // browser Internet Explorer è necessario filtrare la versione 4
  else if( window.ActiveXObject && browserUtente.indexOf("MSIE 4") < 0 ) { 
    // la versione 6 di IE ha un nome differente per il tipo di oggetto ActiveX
    if(browserUtente.indexOf("MSIE 5") < 0){
      XHR = new ActiveXObject("Msxml2.XMLHTTP");
  }
    // le versioni 5 e 5.5 invece sfruttano lo stesso nome
    else{
      XHR = new ActiveXObject("Microsoft.XMLHTTP");
    }
  }
  return XHR;
}

// funzione per passare i dati riguardanti la risoluzione al php tramite l'impostazione 
//di variabili di sessione nello script php registra_risoluzione.php
function registra_risoluzione() {
  res_width = screen.width;
  res_height = screen.height;
  // DEBUG document.write( res_width + "," + res_height );
  var ajax = assegnaXMLHttpRequest();
  // DEBUG document.write( ajax );
  if(ajax) {
    // inizializzo la request
    ajax.open( "get" , "registra_risoluzione.php?x=" + res_width + "&y=" + res_height , true );
  // invio la richiesta
  ajax.send(null);
  }
  // ricarico la pagina che ha eseguito la richiesta cosi da rendere 
  // subito disponibili le variabili di sessione impostate
  location.reload();
}
