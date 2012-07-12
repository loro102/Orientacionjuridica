/************************************************************************************************************
(C) www.dhtmlgoodies.com, March 2005

This is a script by Stefan Born(http://home.arcor.de/xbo/title-to-note/) featured at www.dhtmlgoodies.com.
You will find this and a lot of other scripts at our website.	

Terms of use:
You are free to use this script as long as the copyright message is kept intact. However, you may not
redistribute, sell or repost it without our permission.

Thank you!

www.dhtmlgoodies.com
Alf Magne Kalleland

************************************************************************************************************/

var titleToNote = {
 // Define which elements should be affected:
 elements : ['a', 'img'],
 setup : function(){
 if(!document.getElementById || !document.createElement) return;
   // create note
   var div = document.createElement("div");
   div.setAttribute("id", "title2note");
   document.getElementsByTagName("body")[0].appendChild(div);
   document.getElementById("title2note").style.display = "none";
   // attach events
   for(j=0;j<titleToNote.elements.length;j++){
     for(i=0;i<document.getElementsByTagName(titleToNote.elements[j]).length;i++){
       var el = document.getElementsByTagName(titleToNote.elements[j])[i];
       if(el.getAttribute("title") && el.getAttribute("title") != ""){
         el.onmouseover = titleToNote.showNote;
         el.onmouseout = titleToNote.hideNote;
       }
     }
   }
 },
 showNote : function()
 {
   document.getElementById("title2note").innerHTML = this.getAttribute("title");
   this.setAttribute("title", "");
   document.getElementById("title2note").style.display = "block";
 },
 hideNote : function()
 {
   this.setAttribute("title", document.getElementById("title2note").innerHTML);
   document.getElementById("title2note").innerHTML = "";
   document.getElementById("title2note").style.display = "none";
 }
}
 /* End Title To Note */
 
//Onload Handling 
var oldonload=window.onload;if(typeof window.onload!='function'){
window.onload=titleToNote.setup;
}else{window.onload=function(){oldonload();
titleToNote.setup();}}
  /* setup faster by deleting these lines and adding
         <script type="text/javascript">titleToNote.setup();</script>
         before the closing body tag in your HTML */
