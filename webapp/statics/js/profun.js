//ProFun JS.
//Copyright TAOSMI.

//****************
//ProFun JS Core.
//****************

"use strict";

var profun;

profun = (function () {

    // Private variables.
    var version = "1.0",
        browserInfo = {
            cookiesEnabled: navigator.cookieEnabled,
            javaEnabled: navigator.javaEnabled(),
            name: navigator.appName,
            platform: navigator.platform,
            userAgent: navigator.userAgent,
            version: navigator.appVersion
        },
        types = {
            "[object Function]": "function",
            "[object RegExp]": "regexp",
            "[object Array]": "array",
            "[object Date]": "date",
            "[object Error]": "error"
        },
        nodeTypes = {
            "1": "element",
            "2": "attribute",
            "3": "textNode",
            "4": "cdata",
            "5": "entityReference",
            "6": "entity",
            "7": "element",
            "8": "comment",
            "9": "document",
            "10": "documentType",
            "11": "documentFragment",
            "12": "notation"
        };

    // Methods.
    return {

        // Get browser information. Parameters allowed are: cookiesEnabled,
        // javaEnabled, name, platform, userAgent and version.
        browser: function (value) {
            return browserInfo[value];
        },

        // Compare two elements and return true if they are identical.
        compare: function (a, b) {
            if (profun.type(a) !== profun.type(b)) {
                return false;
            }
            return a.isEqual
                ? a.isEqual(b)
                : a === b;
        },

        // Get a copy of an element.
        copy: function (item) {
            var copyObj = item.copy
                ? item.copy()
                : item;
            return copyObj;
        },

        // Check if an element is defined.
        isDefined: function (item) {
            return ((item !== undefined) && (item !== null));
        },

        // Get the DOM node type of a node element.
        nodeType: function (node) {
            if (node.nodeType) {
                return nodeTypes[node.nodeType];
            }
            if (node.nodeName) {
                return node.nodeName;
            }
            return undefined;
        },

        // Get a random number in range of 0-100 or in range [min, max] if defined.
        random: function (min, max) {
            min = min || 0;
            max = max || 100;
            return Math.floor(Math.random() * (max - min + 1) + min);
        },

        // Get a Date object with the actual time plus an offset in days (optional).
        time: function (days) {
            var date = new Date();
            days = days || 0;
            date.setTime(date.getTime() + (days * 24 * 3600 * 1000));
            return date;
        },

        // Get the system TimeStamp.
        timeStamp: function () {
            return new Date().getTime();
        },

        // Transform an iterable object into an array.
        toArray: function (item) {
            var prop, array = [];
            for (prop in item) {
                if (item.hasOwnProperty(prop)) {
                    array.push(prop);
                }
            }
            return array;
        },

        // Get an object type.
        type: function (item) {
            var type = typeof item,
                objString = Object.prototype.toString;
            if (type !== "object") {
                return type;
            }
            if (types[objString.call(item)]) {
                return types[objString.call(item)];
            }
            return type;
        },

        // Get the ProfunJS version.
        version: function () {
            return version;
        }

    };
}());


/*
//Compara dos Arrays. Retorna verdadero si son iguales.
profun.isEqualArr = function (a, b, doRecursive) {
    var i, ln;
    if (a.length !== b.length) {
        return false;
    }
    for (i = 0, ln = a.length; i < ln; i += 1) {
        if (a[i] !== b[i]) {
            return false;
        }
    }
    return true;
};

//Compara dos objetos. Retorna verdadero si son iguales.
profun.isEqualObj = function (a, b) {
    var key;
    for (key in a) {
        if (a.hasOwnProperty(key) && (a[key] !== b[key])) {
            return false;
        }
    }
    for (key in b) {
        if (b.hasOwnProperty(key) && (b[key] !== a[key])) {
            return false;
        }
    }
    return true;
};


//Obtiene una copia del elemento especificado.
profun.copy = function (item) {
    var copyObj;
    switch (profun.type(item)) {
    case "array":
        copyObj = [];
        var i, l;
        for (i = 0, l = item.length; i < l; i += 1) {
            copyObj.push(item[i]);
        }
        break;
    case "object":
        copyObj = {};
        var prop;
        for (prop in item) {
            if (item.hasOwnProperty(prop)) {
                copyObj[prop] = item[prop];
            }
        }
        break;
    default:
        copyObj = item;
    }
    return copyObj;
};

*/
//Copia las propiedades del elemento origen al elemento destino.
profun.extend = function(destiny, source) {
	for (var prop in source) {
		if (destiny.prototype) destiny.prototype[prop] = source[prop];
		else destiny[prop] = source[prop];
	}
	return destiny;
};


//****************
//ProFun JS AJAX.
//****************

//Ajax object.

profun.Ajax = function (uri, fHandler, params) {

    //Private variables.

    params = params || [];
    var method = "GET",
        mimeType = "text/html",
        timeOut = 0,
        timeOutFunc = null,
        timeOutRef = null,
        xmlHttp = (function () {
            try { return new XMLHttpRequest(); } catch (e) {}
            try { return new ActiveXObject("Msxml2.XMLHTTP.6.0"); } catch (e) {}
            try { return new ActiveXObject("Msxml2.XMLHTTP.3.0"); } catch (e) {}
            try { return new ActiveXObject("Microsoft.XMLHTTP"); } catch (e) {}
            return null;
        }());

    //Private methods.

    function onReadyHandler() {
        if (timeOut && xmlHttp.readyState === 4) {
            clearTimeout(timeOutRef);
        }
        return fHandler.apply(xmlHttp, params);
    }

    function timeOutHandler(that) {
        return setTimeout(function () {
            that.abort();
            timeOutFunc();
        }, timeOut);
    }

    //Returns the Ajax object with the public funcionality.

    return {

        //Cancela la peticion.
        abort: function () {
            if (timeOut) { 
                clearTimeout(timeOutRef);
            }
            xmlHttp.onreadystatechange = null;
            xmlHttp.abort();
        },

        //Establece el metodo de la peticion.
        setMethod: function (newMethod) {
            method = newMethod;
        },
 
        //Estable el mimeType de la peticion.
        setMimeType: function (newMimeType) {
            mimeType = newMimeType;
        },
 
        //Envia la peticion con la informacion especificada.
        send: function (data) {
            data = data || "";
            xmlHttp.onreadystatechange = onReadyHandler;
            if (xmlHttp.overrideMimeType) {
                xmlHttp.overrideMimeType(mimeType);
            }
            xmlHttp.open(method, uri + data, true);
            xmlHttp.send(null);
            if (timeOut) {
                timeOutRef = timeOutHandler(this);
            }
        },

        //Establece un timeOut para la peticion.
        setTimeOut: function (newTimeOut, newTimeOutFunc) {
            timeOut = newTimeOut;
            timeOutFunc = newTimeOutFunc;
        }
    };
};

/*
//Envia la peticion y actualiza el contenido de un elemento.
	$extend($elementMethods, {
		$update: function(url, queryString, fHandlerUpdate) {
			var ajax = new $Ajax(url, function(obj) {
				if (this.readyState != 4) return false;
				obj.$setContent(this.responseText);
				if (fHandlerUpdate) fHandlerUpdate();
			}, [this]);
			ajax.send(queryString);
		}
	});
*/


//*****************
//ProFun JS Array.
//*****************

	//splice: implementar.

//Metodos.
	profun.extend(Array, {

	//Añade al array los elementos especificados si no existen previamente.
		$add: function() {
			for (var i=0,l=arguments.length; i<l; i++) {
				if (this.$indexOf(arguments[i]) == -1) this.push(arguments[i]);
			}
			return this;
		},

	//Vacia el array.
		$clear: function() {
			this.length = 0;
			return this;
		},

	//Obtiene un nuevo Array sin los elementos null o undefined.
		$compact: function() {
			for (var i=0,result=[],l=this.length; i<l; i++) {
				if (this[i] != null) result.push(this[i]);
			}
			return result;
		},

	//Borra del array los elementos especificados.
		$del: function() {
			for (var i=0,l=arguments.length; i<l; i++) {
				var j = this.$indexOf(arguments[i]);
				if (j != -1) this.splice(j,1);
			}
			return this;
		},

	//Borra del array los elementos cuya propiedad es igual al valor especificado.
		$delByProp: function(prop, value) {
			for (var i=0; i<this.length; i++) {
				if (this[i][prop] == value) {
					this.splice(i,1);
					i--;
				}
			}
			return this;
		},

	//Retorna verdadero si al ejecutar la funcion especificada en todos los elementos del array retorna verdadero para almenos uno de ellos.
		$detect: function(fHandler) {
			for (var i=0,l=this.length; i<l; i++) {
				if (fHandler(this[i])) return true;
			}
			return false;
		},

	//Obtiene verdadero si la funcion especificada retorna verdadero para todos los elementos del array.
		$detectAll: function(fHandler) {
			for (var i=0,l=this.length; i<l; i++) {
				if (!fHandler(this[i])) return false;
			}
			return true;
		},

	//Ejecuta la funcion especificada a cada elemento del array, usando como par�metros (elemento, indice).
		$forEach: function(fHandler) {
			for (var i=0,l=this.length; i<l; i++) {
				fHandler.apply([this[i],i], this);
			}
			return this;
		},

	//Obtiene el primer elemento del array o undefined si esta vacio.
		$first: function() {
			return this[0];
		},

	//Obtiene el indice de un elemento o -1 si no existe en el array.
		$indexOf: function(value) {
			for (var i=0,l=this.length; i<l; i++) {
				if (this[i] === value) return i;
			}
			return -1;
		},

	//Obtiene el indice de un elemento cuya propiedad coincida con el valor especificado.
		$indexOfByProp: function(prop, value) {
			for (var i=0,l=this.length; i<l; i++) {
				if (this[i][prop] == value) return i;
			}
			return -1;
		},

	//Obtiene el ultimo elemento del array o undefined si esta vacio.
		$last: function() {
			return this[this.length - 1];
		},

	//Obtiene el elemento mayor. Opcionalmente se puede especificar la funcion evaluadora.
		$max: function(fHandler) {
			if (this.length == 0) return undefined;
			if (this.length == 1) return this[0];
			fHandler = fHandler || function(item) { return item };
			for (var i=1,max=this[0],l=this.length; i<l; i++) {
				if (fHandler(this[i]) > fHandler(max)) max = this[i];
			}
			return max;
		},

	//Obtiene el elemento menor. Opcionalmente se puede especificar la funcion evaluadora.
		$min: function(fHandler) {
			if (this.length == 0) return false;
			if (this.length == 1) return this[0];
			fHandler = fHandler || function(item) { return item };
			for (var i=1,min=this[0],l=this.length; i<l; i++) {
				if (fHandler(this[i]) < fHandler(min)) min = this[i];
			}
			return min;
		},

	//Obtiene y borra del array el elemento en la ultima posicion o en la posicion especificada.
		$pop: function(index) {
			if (profun.isDefined(index)) {
				if (!index.$inRange(0,this.length-1)) return undefined;
				if (index == 0) return this.shift();
				return this.splice(index,1);
			}
			return this.pop();
		},

	//Añade un elemento al array en la ultima posicion o en la posicion especificada.
		$push: function(element, index) {
			if (profun.isDefined(index)) {
				if (!index.$inRange(0,this.length-1)) return undefined;
				this.splice(index,0,element);
				return this.length;
			}
			return this.push(element); 
		},

	//Obtiene un elemento aleatorio del array.
		$random: function() {
			return this[Math.floor(Math.random()*(this.length))];
		},

//Atencion si es un array de nodos, devolverlo aumentado.

	//Si el array contiene un unico elemento, retorna el elemento. Sino, retorna el array.
		$reduce: function() {
			return (this.length > 1)? this : this[0];
		},

	//Obtiene un array con los elementos que retornan verdadero para la funcion especificada.
		$select: function(fHandler) {
			for (var i=0,result=[],l=this.length; i<l; i++) {
				if (fHandler(this[i])) result.push(this[i]);
			}
			return result;
		},

	//Obtiene una representacion del array en forma de string.
		$toString: function(separator) {
			return this.join(separator);
		}

	});


//*******************
//ProFun JS Cookies.
//*******************

	profun.cookie = {

	//Borra la cookie.
		erase: function(name) {
			profun.cookie.write(name, "", -1);
		},

	//Lee el valor de la cookie.
		read: function(name) {
			var cookie = document.cookie.match(name+'=([^;]*)');
			return (cookie)? cookie[1] : "";
		},

	//Guarda en la cookie el valor y propiedades especificados.
		write: function(name, value, days, secure, domain, path) {
			secure = secure || false;
			domain = domain || document.domain;
			path = path || "/";
			var cookie = name +"="+ value +";";
			if (days) cookie += " expires="+ $time(days).toGMTString() +";";
			cookie += " path="+ path +";";			
			cookie += " domain="+ domain +";";
			if (secure) cookie += " secure";
			document.cookie = cookie;
		}

	};


//***************
//ProFun JS DOM.
//***************

//Implementacion del elemento Node para navegadores que no lo soportan.
	if (!Node) {
		var Node = {
			ELEMENT_NODE: 1,
			ATTRIBUTE_NODE: 2,
			TEXT_NODE: 3,
			CDATA_SECTION_NODE: 4,
			ENTITY_REFERENCE_NODE: 5,
			ENTITY_NODE: 6,
			PROCESSING_INSTRUCTION_NODE: 7,
			COMMENT_NODE: 8,
			DOCUMENT_NODE: 9,
			DOCUMENT_TYPE_NODE: 10,
			DOCUMENT_FRAGMENT_NODE: 11,
			NOTATION_NODE: 12,
			DOCUMENT_POSITION_DISCONNECTED: 1,
			DOCUMENT_POSITION_PRECEDING: 2,
			DOCUMENT_POSITION_FOLLOWING: 4,
			DOCUMENT_POSITION_CONTAINS: 8,
			DOCUMENT_POSITION_CONTAINED_BY: 16,
			DOCUMENT_POSITION_IMPLEMENTATION_SPECIFIC: 32
		};
	}

//Extiende el elemento Node con metodos mejorados.
	profun.extend(Node, {

	//CopyNode como importNode.

	//Asigna el texto especificado como TextNode descendiente y elimina todos los nodos ya existentes.
		$setContent: function(txt) {
			this.$delChilds();
			var txtNode = document.createTextNode(txt);
			this.appendChild(txtNode);
			return this;
		},

	//Obtiene el contenido de los TextNode descendientes del nodo.
		$getContent: function() {
			var txt = "";
			for (var i=0,l=this.childNodes.length; i<l; i++) {
				if (this.childNodes[i].nodeType == 3) {
					txt += this.childNodes[i].nodeValue;
				}
			}
			return txt;
		},

	//Añade el contenido al nodo como TextNode descendiente.
		$addContent: function(txt) {
			var txtNode = document.createTextNode(txt);
			this.appendChild(txtNode);
			return this;
		},

	//Obtiene los nodos descendientes y borra los TextNode existentes entre ElementNodes.
		$getChilds: function() {
			var result = [];
			for (var i=0,l=this.childNodes.length; i<l; i++) {
				if (this.childNodes[i].nodeType == 1) {
					result.push(this.childNodes[i]);
				}
			}
			return result;
		},

	//Clona el nodo actual.
		$clone: function() {
			return this.cloneNode(true);
		},

	//Elimina el nodo.
		$del: function() {
			this.parentNode.removeChild(this);
			return this;
		},

	//Elimina todos los nodos descendientes.
		$delChilds: function() {
			this.innerHTML = "";
			return this;
		},

	//Obtiene el primer ElementNode descendiente.
		$firstChild: function() {
			var node = this.firstChild;
			while (node && node.nodeType != 1)
				node = node.nextSibling;
			return node;
		},

	//Obtiene verdadero si el nodo tiene descendientes.
		$hasChilds: function() {
			for (var i=0,l=this.childNodes.length; i<l; i++) {
				if (this.childNodes[i].nodeType == 1) return true;
			}
			return false;
		},

	//Inserta un nodo en el DOM.
		$insert: function(node, where) {
			switch(where) {
				case "before":
					node.parentNode.insertBefore(this, node);
					break;
				case "child":
					node.appendChild(this);
					break;
				case "firstChild":
					node.insertBefore(this, node.firstChild);
					break;
				case "after":
				default:
					var nextNode = node.$sibling("next");
					if (nextNode) {
						nextNode.parentNode.insertBefore(this, nextNode);
					} else {
						node.parentNode.appendChild(this);
					}
					break;
			}
			return this;
		},

	//Obtiene verdadero si alguno de sus ancestros tiene el id indicado.
		$hasAncestor: function(id) {
			var node = this.parentNode;
			while (node && node != document.body) {
				if (node.id == id) return true;
				node = node.parentNode;
			}
			return false;
		},

	//Retorna verdadero si es descendiente del nodo.
		$isChild: function(id) {
			return (document.getElementById(id) == this.parentNode);
		},

	//Obtiene el ultimo descendiente del nodo.
		$lastChild: function() {
			var node = this.lastChild;
			while (node && node.nodeType != 1)
				node = node.previousSibling;
			return node;
		},

	//Obtiene el nodo padre.
		$parent:  function() {
			return this.parentNode;
		},

	//Reemplaza el nodo actual por el especificado.
		$replace: function(node) {
			this.parentNode.replaceChild(node, this);
			return this;
		},

	//Obtiene hermanos del nodo, (next, prev, allNext, allPrev, all).
		$sibling: function(who) {
			switch(who) {
				case "all":
					var result = [];
					var node = this.firstChild;
					while (node) {
						if ((node.nodeType == 1) && (node != this)) result.push(node);
						node = node.nextSibling;
					}
					return result;
					break;
				case "allNext":
					var result = [];
					var node = this.nextSibling;
					while (node) {
						if (node.nodeType == 1) result.push(node);
						node = node.nextSibling;
					}
					return result;
				break;
				case "allPrev":
					var result = [];
					var node = this.previousSibling;
					while (node) {
						if (node.nodeType == 1) result.push(node);
						node = node.previousSibling;
					}
					return result;
				break;
				case "next":
					var node = this.nextSibling;
					while (node &&  node.nodeType != 1)
						node = node.nextSibling;
					return ((node)? node : undefined);
				break;
				case "prev":
					var node = this.previousSibling;
					while (node &&  node.nodeType != 1)
						node = node.previousSibling;
					return ((node)? node : node);
				break;
			}
		}

	});


//********************************
//ProFun JS DOM CSS Manipulation.
//********************************

	profun.extend(Node, {

	//Añade la clase especificada al nodo.
		$addClass: function(newClass) {
			this.className += (this.className)? " "+newClass : newClass;
			return this;
		},

	//Elimina solo la clase especificada.
		$delClass: function(oldClass) {
			this.className = this.className.replace(new RegExp("\\s*\\b"+ oldClass +"\\b","g"), "");
			return this;
		},

	//Obtiene el estilo de la propiedad especificada.
		$getStyle: function(prop) {
			if (prop == "opacity") return this.$getOpacity();
			if (this.currentStyle) {
				return this.currentStyle[prop.$camelize()];
			}
			return document.defaultView.getComputedStyle(this,null).getPropertyValue(prop);
		},

	//Obtiene verdadero si el nodo tiene la clase especificada.
		$hasClass: function(className) {
			return (this.className.search(new RegExp("(^|( )+)"+ className +"(( )+|$)")) >= 0);
		},

	//Establece el valor al atributo CSS del nodo.
		$setStyle: function(prop, value) {
			if (prop == "opacity") return this.$setOpacity(value);
			this.style[prop.$camelize()] = value;
			return this;
		},

	//Asigna la clase especificada, sobreescribiendo las existentes.
		$setClass: function(newClass) {
			this.className = newClass;
			return this;
		},

	//Establece la opacidad del nodo especificado al valor determinado (0-100).
		$setOpacity: function(value) {
			this.style.opacity = (value/100);
			this.style.filter = "alpha(opacity="+ value +")";
			return this;
		},

	//Obtiene la opacidad del nodo.
		$getOpacity: function() {
			if (this.currentStyle) {
				if (this.currentStyle["filter"]) {
					var filter = this.currentStyle["filter"].match(/opacity=(\d+)/);
					if (filter) return filter[1];
				}
				return (this.currentStyle["opacity"] * 100);
			}
			return (document.defaultView.getComputedStyle(this,null).getPropertyValue("opacity") * 100);
		},

	//Obtiene la posicion del nodo respecto a la esquina superior izquierda del documento.
		$offset: function() {
			var x = 0, y = 0, node = this;
			while (node != null) {
				x += node.offsetLeft;
				y += node.offsetTop;
				node = node.offsetParent;
			}
			return {x:x, y:y};
		},

	//Obtiene la cantidad de desplazamiento del elemento respecto a su contenedor.
		$scrollOffset: function() {
			var x = 0, y = 0, node = this;
			while (node != null) {
				x += node.scrollLeft;
				y += node.scrollTop;
				node = node.offsetParent;
			}
			return {x:x, y:y};
		},

	//Oculta un nodo.
		$hide: function() {
			this._display = this.$getStyle("display");
			this.style.display = "none";
			return this;
		},

	//Muestra el elemento.
		$show: function() {
			this.style.display = this.style._display || "block";
			return this;
		}

	});

/*
 * 
 * TO FX module

	//Obtiene verdadero si un elemento es visible, en cualquier otro caso obtiene falso.
		$isVisible: function() {
			return (this.style.display != "none");
		},

	//Muestra/Oculta el elemento.
		$toggle: function() {
			return (this.$isVisible())? this.$hide() : this.$show();
		},

	//Desplaza la p�gina hasta el elemento.
		$scrollTo: function() {
			var offset = this.$offset();
			window.scrollTo(offset.x, offset.y);
			return this;
		},


//Revisar
	//Cambia a la posicion absoluta manteniendo la posicion del elemento.
		$absolutize: function() {
			if (this.$getStyle("position") == "absolute") return this;
			var offset = this.$offset();
			this.style.position = "absolute";
			this.style.left = offset.x +"px";
			this.style.top = offset.y +"px";
			return this;
		},


	//Cambia la posici�n del elemento a modo relativo.
		$relativize: function() {
			if (this.style.position == "relative") return this;
			var offsetOld = this.$offset();
			this.style.position = "relative";
			var offsetNew = this.$offset();
			this.style.left = (offsetOld.x - (offsetNew.x - (this.$getStyle('left').$toInt() || 0)))+"px";
			this.style.top = (offsetOld.y - (offsetNew.y - (this.$getStyle('top').$toInt() || 0)))+"px";
			return this;
		},

	//A�ade/Elimina la clase.
		$toggleClass: function(className) {
			return (this.$hasClass(className))? this.$delClass(className) : this.$addClass(className);
		}

	});
*/


//******************
//ProFun JS Screen.
//******************

	profun.screen = {

	//Obtiene la componente X e Y de la pagina en pixels.
		pageSize: function() {
			var x = document.body.scrollWidth;
			if (document.documentElement.scrollWidth > x) x = document.documentElement.scrollWidth;
			var y = document.body.scrollHeight;
			if (document.documentElement.scrollHeight > y) y = document.documentElement.scrollHeight;
			var viewSize = profun.viewportSize();
			if (viewSize.y > y ) y = viewSize.y;
			return {x:x, y:y};
		},

	//Obtiene las componente X e Y de la resolucion de la pantalla en pixels.
		screenRes: function() {
			return {x: screen.width, y: screen.height};
		},

	//Obtiene las componente X e Y de la pantalla disponible en pixels.
		screenSize: function() {
			return {x: screen.availWidth, y: screen.availHeight};
		},

	//Obtiene la cantidad de scroll realizado en las componente X e Y de la pagina.
		viewportScroll: function() {
			return {
				x: window.pageXOffset || document.documentElement.scrollLeft || document.body.scrollLeft || 0,
				y: window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0
			};
		},
	
	//Obtiene la componente X e Y de la zona visible de la pagina (sin scroll ni barras de herramientas).
		viewportSize: function() {
			return {
				x: window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth || 0,
				y: window.innerHeight || document.documentElement.clientHeight  || document.body.clientHeight || 0
			};
		}

	};


//***********************
//ProFun JS DOM Selector.
//***********************

//Obtiene el/los elementos con ids especificados y ademas los extiende con los metodos profun.
	function $(element) {
		if (arguments.length > 1) {
			for (var i=0,r=[],l=arguments.length; i<l; i++) {
				r[i] = ($type(arguments[i]=="string"))? document.getElementById(arguments[i]) : arguments[i];
				if (r[i] && !r[i].$profunObj) r[i] = $extend(r[i], $elementMethods);
			}
			return r;
		}
		var extObj = ($type(element)=="string")? document.getElementById(element) : element;
		if (extObj && !extObj.$profunObj) $extend(extObj, $elementMethods);
		return extObj;
	}

//Obtiene el primer elemento cuya propiedad name es igual a la especificada.
	function $N(name) {
		return $A(document.getElementsByName(name)).$reduce();
	}

//Obtiene un array con los elementos cuya propiedad name es igual a la especificada.
	function $$(name) {
		return $A(document.getElementsByName(name));
	}


//*******************
//ProFun JS Numbers.
//*******************

//format, mejorar.

//Metodos.
	profun.extend(Number, {

	//Funciones nativas de Math.
		abs: function() {
			return Math.abs(this);
		},
		acos: function() {
			return Math.acos(this);
		},
		asin: function() {
			return Math.asin(this);
		},
		atan: function() {
			return Math.atan(this);
		},
		ceil: function() {
			return Math.ceil(this);
		},
		cos: function() {
			return Math.cos(this);
		},
		exp: function() {
			return Math.exp(this);
		},
		floor: function() {
			return Math.floor(this);
		},
		log: function() {
			return Math.log(this);
		},
		pow: function(x) {
			return Math.pow(this,x);
		},
		round: function() {
			return Math.round(this);
		},
		sin: function() {
			return Math.sin(this);
		},
		sqrt: function() {
			return Math.sqrt(this);
		},
		tan: function() {
			return Math.tan(this);
		},

	//Retorna verdadero si el numero esta delimitado en el rango.
		$inRange: function(min, max) {
			if (!isNaN(max)) return (this >= min);
			if (!isNaN(min)) return (this <= max);
			return ((this >= min) && (this <= max));
		},

	//Obtiene un numero delimitado por el rango [min,max] especificado.
		$limit: function(min, max) {
			if (this < min) return min;
			if (this > max) return max;
			return this;
		},

	//Obtiene el numero de veces que se repite el incremento, delimitado por los valores min y max.
		$snap: function(increment, min, max) {
			var result = (this / increment);
			result = (result>0)? Math.floor(result) : Math.ceil(result);
			return result.$limit(min, max);
		},

	//Formatea un numero segun la mascara. #:se pinta si hay un numero, 0:se pinta 0 si hay espacio.
		$format: function(mask, decimalSeparator) {
			var num = String(this);
			decimalSeparator = decimalSeparator || ",";
			var maskDecimalPos = mask.indexOf(decimalSeparator);
			if (maskDecimalPos < 0) maskDecimalPos = mask.length;
			var numDecimalPos = num.indexOf(".");
			if (numDecimalPos < 0) numDecimalPos = num.length;
			//Igualamos longitud parte entera.
			if (numDecimalPos > maskDecimalPos) {
				num = num.substring((numDecimalPos - maskDecimalPos));
			} else {
				for (var i=maskDecimalPos - numDecimalPos; i>0; i--) num = " "+ num;
			}
			//Igualamos longitud parte decimal.
			if (num.length > mask.length) {
				num = num.substring(0,mask.length);
			} else {
				while (num.length < mask.length) num += " ";
			}
			//Aplicamos la mascara de derecha a izquierda.
			var result = "";
			for (var i=mask.length-1; i>=0; i--) {
				if (mask[i] == "#") {
					result = num[i] + result;
				} else if (mask[i] == "0") {
					result = (num[i]==" ")? "0"+ result : num[i] + result;
				} else if (mask[i] == decimalSeparator) {
					result = decimalSeparator + result;
				} else {
					result = (num[i]==" " && mask[i-1]=="#")? result : mask[i] + result;
					num = num.substring(1);
				}
			}
			return result.$trim();
		},

	//Obtiene la cadena de caracteres que corresponde con el numero.
		$toString: function() {
			return String(this);
		}

	});


//*******************
//ProFun JS Strings.
//*******************

	profun.extend(String, {

	//Obtiene la cadena con la primera letra en mayusculas y el resto en minusculas.
		$capitalize: function() {
			return (this.charAt(0).toUpperCase() + this.substr(1));
		},

	//Elimina cualquier separacion entre palabras y cambia la primera letra de cada palabra en mayusculas excepto la primera.
		$camelize: function(strMatch, pattern) {
			var str = this.replace(/[^a-zA-Z0-9](\w)/g, function(strMatch, pattern) {
				return pattern.toUpperCase();
			});
			return str;
		},

	//Obtiene verdadero si la cadena acaba con el fragmento especificado.
		$endsWith: function(str) {
			return (this.search(new RegExp(str+"$")) >= 0);
		},

	//Borra el fragmento especificado de la cadena.
		$erase: function(str) {
			return this.replace(new RegExp("["+str+"]","g"),"");
		},

	//Obtiene la cadena con los caracteres especiales HTML escapados.
		$escapeHTML: function() {
			return this.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
		},

	//Añade por la izquierda el caracter ch (por defecto " ") hasta completar la longitud indicada.
		$leftPad: function(ln, ch) {
			var ch = ch || " ";
			var str = String(this);
			while (str.length < ln) {
				str = ch + str;
			}
			return str;
		},

	//Obtiene verdadero si la cadena comienza por la subcadena especificada.
		$startsWith: function(str) {
			return (this.indexOf(str) == 0);
		},

	//Obtiene la representacion numerica decimal de la cadena.
		$toFloat: function() {
			return parseFloat(this);
		},

	//Obtiene la representacion numerica de la cadena. La base es opcional (base 10 por defecto).
		$toInt: function(base) {
			return parseInt(this,(base || 10));
		},

	//Obtiene la cadena sin espacios ni por delante ni por detras.
		$trim: function() {
			return this.replace(/^\s+|\s+$/g, '');
		},

	//Obtiene la cadena sin dobles espacios.
		$trimDoubleWhitespace: function() {
			return this.replace(/\s\s/g, ' ');
		},

	//Obtiene la cadena sin tags SCRIPTS.
		$trimScripts: function() {
			return this.replace(/<script[.\s]+<\/script>/gi, '');
		},

	//Obtiene la cadena sin tags HTML.
		$trimTags: function() {
			return this.replace(/<\/?[^>]+>/g, '');
		},

	//Obtiene la cadena truncada. La longitud es opcional (longitud por defecto 25).
		$truncate: function(ln) {
			ln = ln || 25;
			if (this.length <= ln) return this;
			return (this.substr(0, ln) +"...");
		},

	//Obtiene la cadena con los caracteres especiales HTML desescapados.
		$unescapeHTML: function() {
			return this.replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&amp;/g,'&');
		}

	});


//***********************
//ProFun JS Validations.
//***********************

//Comprueba que el elemento especificado sea Ok (definido y no est� vac�o / cero).
	function $chk(element) {
		if (!element) return false;
		if (($type(element) == "array") && (element.length == 0)) return false;
		for (var prop in element) break;
		if (!prop) return false;
		return true;
	}

//Comprueba que existan entre min y max checkbox seleccionados.
	function $chkCheckboxList(name, min, max) {
		var objs = $$(name).$select(function(item) {
			return item.checked;
		});
		if (!objs.length.$inRange(min,max)) return false;
		return true;
	}

//Comprueba que la fecha especificada sea correcta.
	function $chkDate(txt) {
		if (!$chk(txt)) return null;
		var date = txt.match(/^(\d{1,2})[-\/](\d{1,2})[-\/](\d\d\d\d)$/);
		if (!date) return false;
		var year  = date[3].$toInt();
		var month = date[2].$toInt();
		var day   = date[1].$toInt();
		if ((month < 1) || (month > 12)) return false;
		var calDate = new Date(year, month, 0);
		if (day > calDate.getDate()) return false;
		return true;
	}

//Comprueba que la direcci�n de correo electr�nico sea correcta.
	function $chkEmail(txt) {
		if (!$chk(txt)) return null;
		if (txt.match(/^\w(\.?\w)*@(\w)+(\.(\w)+)+$/)) return true;
		return false;
	}

//Comprueba que el telefono movil sea correcta (solo valido en Espa�a).
	function $chkMobile(num) {
		if (!$chk(num)) return null;
		if (num.match(/^6\d{8}$/)) return true;
		return false;
	}

//Comprueba que el importe sea correcto.
	function $chkMoney(num) {
		if (!$chk(num)) return null;
		if (num.match(/^\d+([\,\.]\d{1,4})?$/)) return true;
		return false;
	}

//Comprueba que el mes y a�o especificado sea correcta.
	function $chkMonth(txt) {
		if (!$chk(txt)) return null;
		var date = txt.match(/^(\d{1,2})[-\/](\d\d\d\d)$/);
		if (!date) return false;
		var year  = date[2].$toInt();
		var month = date[1].$toInt();
		if ((month < 1) || (month > 12)) return false;
		return true;
	}

//Comprueba que el n�mero sea correcto.
	function $chkNumber(num) {
		if (!$chk(num)) return null;
		return (!isNaN(num));
	}

//Comprueba que al menos un radioButton est� seleccionado.
	function $chkRadios(name) {
		var objs = document.getElementsByName(name);
		for (var i=0,l=objs.length; i<l; i++) {
			if (objs[i].checked) return true;
		}
		return undefined;
	}

//Valida el select a partir del id especificado (la opci�n seleccionada debe tener value Ok).
	function $chkSelect(id) {
		if ($(id).selectedIndex == 0) return undefined;
		return true;
	}

//Valida la cadena especificada (solo permite caracteres, numeros, espacios, guiones, puntos y comas).
	function $chkText(txt) {
		if (!$chk(txt)) return null;
		if (txt.match(/[^\.\,\\\/a-zA-Z0-9��\s_-]/)) return false;
		return true;
	}

//Compara dos fechas.
	function $cmpDate(a, b) {
		a = a.match(/^(\d{1,2})[-\/](\d{1,2})[-\/](\d\d\d\d)$/);
		b = b.match(/^(\d{1,2})[-\/](\d{1,2})[-\/](\d\d\d\d)$/);
		var aDate = new Date(a[3],(a[2]-1),a[1]);
		var bDate = new Date(b[3],(b[2]-1),b[1]);
		if (aDate > bDate) return 1;
		if (aDate < bDate) return -1;
		return 0;
	}

//Compara dos importes.
	function $cmpMoney(a, b) {
		a = a.match(/^(\d+)([\,\.](\d{1,4}))?$/);
		b = b.match(/^(\d+)([\,\.](\d{1,4}))?$/);
		if (a[1].$toInt() > b[1].$toInt()) return 1;
		if (a[1].$toInt() < b[1].$toInt()) return -1;
		if (!a[3]) a[3] = '00';
		if (!b[3]) b[3] = '00';
		if (a[3].$toInt() > b[3].$toInt()) return 1;
		if (a[3].$toInt() < b[3].$toInt()) return -1;
		return 0;
	}


//*********************
//ProFun JS XMLParser.
//*********************

//Obtiene el arbol xml a partir del texto especificado.
	function $xmlParser(txt) {
		if (ActiveXObject("Microsoft.XMLDOM")) {
			var xmlObj = new ActiveXObject("Microsoft.XMLDOM");
			xmlObj.async = false;
			xmlObj.loadXML(txt);
			return xmlObj;
		}
		if (DOMParser) {
			var xmlParser = new DOMParser();
			var xmlObj = xmlParser.parserFromString(txt,"text/xml");
			return xmlObj;
		}
		return false;
	}