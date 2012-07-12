<?php
function CreateXmlHttpReq(handler) {
  var xmlhttp = null;
  xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = handler;
  return xmlhttp;
};
?>