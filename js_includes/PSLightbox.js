(function() {
	function checkCompatibility() {
		if (typeof window.postMessage == "undefined"
				|| typeof Element == "undefined") {
			return false;
		}
		return true;
	}
	if (!checkCompatibility()) {
		window.PagSeguroLightbox = function() {
			return false;
		};
		return false;
	}
	function onDocumentReady(callback) {
		var eventName = document.addEventListener ? "DOMContentLoaded"
				: "onreadystatechange";
		if (document.readyState === "complete"
				|| (document.readyState !== "loading" && !document.attachEvent)) {
			callback();
			return;
		}
		document[document.addEventListener ? "addEventListener" : "attachEvent"]
				(
						eventName,
						function() {
							if (eventName == "DOMContentLoaded"
									|| document.readyState === "complete") {
								callback();
								document[document.removeEventListener ? "removeEventListener"
										: "detachEvent"](eventName,
										arguments.callee, false);
							}
						}, false);
	}
	var lightbox = document.createElement("iframe"), styleNode = document
			.createElement("style"), styleSheets = [ ".uolPSMediator {position:fixed; left:0px; top:0px; width:100%; height:100%; background-color:transparent; border:0px none transparent; overflow:hidden; display:none; z-index:9999;}" ]
			.join(""), PagSeguro = PagSeguro || {};
	lightbox.setAttribute("src",
			"https://sandbox.pagseguro.uol.com.br/checkout/embedded/i-ck.html");
	lightbox.setAttribute("width", "100%");
	lightbox.setAttribute("height", "100%");
	lightbox.setAttribute("id", "uolPSMediator");
	lightbox.setAttribute("class", "uolPSMediator");
	lightbox.setAttribute("allowtransparency", "true");
	lightbox.setAttribute("frameborder", "0");
	document.getElementsByTagName("head")[0].appendChild(styleNode);
	if (styleNode.styleSheet) {
		styleNode.styleSheet.cssText = styleSheets;
	} else {
		styleNode.appendChild(document.createTextNode(styleSheets));
	}
	PagSeguro.Lightbox = function() {
		this.token;
		this.lastSentToken = "";
		this.transactionCode;
		this.callback;
		this.recoveryCode = "";
		this.lightbox = lightbox;
		this.isMobile = false;
		this.ready = false;
		this.mediator = new PagSeguro.APIMediator({
			lightbox : this.lightbox
		});
		this.listenChannels();
	};
	PagSeguro.Lightbox.prototype = {
		constructor : PagSeguro.Lightbox,
		checkout : function() {
			var _that = this;
			if (!this.ready) {
				setTimeout(function() {
					_that.checkout();
				}, 150);
				return;
			}
			if (!this.isMobile) {
				this.showLightbox();
			}
			this.sendToken();
		},
		showLightbox : function() {
			this.lightbox.style.display = "block";
		},
		hideLightbox : function() {
			this.lightbox.style.display = "none";
		},
		execCallback : function() {
			if (this.transactionCode != "ABORTED" && this.transactionCode != "") {
				if (this.callback["success"]) {
					this.callback["success"](this.transactionCode);
				}
			} else {
				this.callback["abort"](this.recoveryCode);
			}
		},
		syntonize : function() {
			this.publish({
				command : "syntonize",
				value : window.location.protocol + "//" + window.location.host
			}, "lightbox");
		},
		setToken : function(token) {
			this.token = (typeof (token) === "string") ? "code=" + token
					: (token instanceof HTMLFormElement ? this
							.serializeForm(token) : this.serialize(token));
		},
		sendToken : function() {
			var tokenToSent = this.token;
			if (tokenToSent === this.lastSentToken && this.recoveryCode != "") {
				tokenToSent = this.serialize({
					recoveryCode : this.recoveryCode
				});
			} else {
				this.recoveryCode = "";
			}
			this.publish({
				command : "setToken",
				value : tokenToSent
			}, "lightbox");
			this.lastSentToken = this.token;
		},
		catchCommunicationException : function() {
			if (window.location.toString().indexOf("file:///") != -1) {
				this.publish({
					command : "error",
					type : "1"
				}, "lightbox");
			}
		},
		publish : function(message, channel) {
			this.mediator.postMessage(message, channel);
		},
		subscribe : function(channel, callback) {
			this.mediator.acceptMessage(channel, callback);
		},
		serialize : function(obj) {
			var str = "", i;
			for (i in obj) {
				str += i + "=" + obj[i] + "&";
			}
			return str.replace(/\&$/, "");
		},
		serializeForm : function(htmlForm) {
			var obj = {}, elements = htmlForm.elements, i, l;
			for (i = 0, l = elements.length; i < l; i++) {
				if (elements[i].type != "submit" && elements[i].name
						&& !obj[elements[i].name]
						&& typeof elements[i].value != "undefined") {
					obj[elements[i].name] = elements[i].value;
				}
			}
			return this.serialize(obj);
		},
		listenChannels : function() {
			var _that = this;
			this.subscribe("lightbox", function(data) {
				switch (data.command) {
				case "setTransactionCode":
					_that.transactionCode = data.value;
					_that.recoveryCode = "";
					break;
				case "setRecoveryCode":
					_that.recoveryCode = data.value;
					break;
				case "hide":
					_that.hideLightbox();
					_that.execCallback();
					break;
				case "ready":
					_that.ready = data.value;
					_that.isMobile = data.isMobile;
				}
			});
		}
	};
	PagSeguro.APIMediator = function(core) {
		var channels = {
			lightbox : {
				context : core.lightbox.contentWindow,
				url : "https://sandbox.pagseguro.uol.com.br",
				callbacks : []
			}
		};
		this.postMessage = function(message, channel) {
			if (channels[channel]) {
				channels[channel].context.postMessage(JSON.stringify(message),
						channels[channel].url);
			}
		};
		this.acceptMessage = function(channel, callback) {
			var _that = this, callbacks = channels[channel].callbacks;
			callbacks[callbacks.length] = function(event) {
				if (!channels[channel]) {
					return;
				}
				if (event.origin == channels[channel].url) {
					var data = JSON.parse(event.data);
					callback(data);
				}
			};
			window[window.addEventListener ? "addEventListener" : "attachEvent"]
					((window.addEventListener ? "message" : "onmessage"),
							callbacks[callbacks.length - 1], false);
		};
		this.ignoreMessage = function(channel) {
			var _that = this, i = channels[channel].callbacks.length;
			if (channels[channel]) {
				while (i--) {
					window[window.removeEventListener ? "removeEventListener"
							: "detachEvent"](
							(window.removeEventListener ? "message"
									: "onmessage"),
							channels[channel].callbacks[i], false);
				}
			}
		};
	};
	function _logErrors(e, method) {
		var i = new Image();
		i.src = "https://sandbox.pagseguro.uol.com.br/checkout/fe-logger.jhtml?log="
				+ e.toString() + " at(l:" + e.lineNumber + ", c:"
				+ e.columnNumber + ")" + "&jsMethod=" + method
				+ "&jsOrigin=pagseguro.lightbox.js";
	}
	window.PagSeguroLightbox = (function() {
		var ltb;
		onDocumentReady(function() {
			document.getElementsByTagName("body")[0].appendChild(lightbox);
			function _onload() {
				try {
					ltb = new PagSeguro.Lightbox();
					ltb.syntonize();
				} catch (e) {
					_logErrors(e, "_onload");
					throw e;
				}
			}
			if (!window.addEventListener) {
				lightbox.attachEvent("onload", _onload);
			} else {
				lightbox.addEventListener("load", _onload, false);
			}
		});
		function initLightbox(token, callback) {
			if (ltb === undefined) {
				setTimeout(function() {
					initLightbox(token, callback);
				}, 100);
				return;
			}
			ltb.setToken(token);
			ltb.transactionCode = "ABORTED";
			ltb.callback = callback || {
				abort : function() {
				}
			};
			ltb.checkout();
		}
		return function(token, callback) {
			try {
				initLightbox(token, callback);
				return true;
			} catch (e) {
				_logErrors(e, "initLightbox");
				throw e;
			}
		};
	})();
})();