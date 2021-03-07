  //Crea un objeto Ajax.
    function crearAjax() {
      var xmlhttp = false;
      try { 
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
      } catch (e) {
        try {
          xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch(E) {
          xmlhttp = false;
        }
      }
      if (!xmlhttp && XMLHttpRequest!='undefined') {
        xmlhttp = new XMLHttpRequest();
      }
      return xmlhttp;
    }

  //Realiza validaciones y crea el usuario.
    function crearUsuario() {
      if (document.getElementById('usu').value == "") {
	  	alert("Rellene el campo USUARIO correctamente");
		return false;
      }
      if (document.getElementById('pass').value == "") {
	  	alert("Rellene el campo CONTRASEÑA correctamente");
        return false;
      }
      if (document.getElementById('pass').value != document.getElementById('passR').value) {
	  	alert("Los campos CONTRASEÑA no son iguales");
        return false;
      }
      var email = document.getElementById('email').value.split("@");
	  if (email[1] == undefined) {
	  	alert("Rellene el campo EMAIL correctamente");
		return false;
      }
	  var dominio = email[1].split(".");
      if ((document.getElementById('email').value == "") || (email[0] == undefined) || (dominio[0] == undefined)
	  || (dominio[1] == undefined)) {
	  	alert("Rellene el campo EMAIL correctamente");
		return false;
      }
      var ajax = crearAjax();
      ajax.onreadystatechange = function() {
	  	switch (ajax.readyState) {
          case 1: document.getElementById('cuerpo').innerHTML = " Cargando..."; break;
		  case 4: document.getElementById('cuerpo').innerHTML = ajax.responseText; break;
		}
      }
	  var usu    = document.getElementById('usu').value;
	  var pass   = document.getElementById('pass').value;
      var email  = document.getElementById('email').value;
	  var fecNac = document.getElementById('dia').value+"/"+document.getElementById('mes').value+"/"+document.getElementById('any').value;
	  var notif  = (document.getElementById('notificar').checked)? "Y" : "N";
      ajax.open("$POST", "crearUsuario.php?usu="+usu+"&pass="+pass+"&fecNac="+fecNac+"&email="+email+"&notificar="+notif, true);
      ajax.send(null);
    }

  //Vuelve a la pagina de inicio.
    function volverPrincipal() {
		document.location = "index.php";
	}

  //Vuelve a la pagina de registro
    function volverRegistro() {
		document.location = "registrarse.php";
	}