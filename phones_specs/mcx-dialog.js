/**
 * Mcx Dialog Mobile v0.1.0
 * Copyright (C) 2018 mcx
 * https://github.com/code-mcx/mcx-dialog-mobile
 */
(function (global, factory) {
	typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
	typeof define === 'function' && define.amd ? define(factory) :
	(global.mcxDialog = factory());
}(this, (function () { 'use strict'; 

function addClass(e, c) {
	var newclass = e.className.split(" ");
	if (e.className === "") newclass = [];
	newclass.push(c);
	e.className = newclass.join(" ");
};

function extend(source, target) {
	for (var key in target) {
		source[key] = target[key];
	}
	return source;
}

function getAnimationEndName(dom) {
	var cssAnimation = ["animation", "webkitAnimation"];
	var animationEnd = {
		"animation": "animationend",
		"webkitAnimation": "webkitAnimationEnd"
	};
	for (var i = 0; i < cssAnimation.length; i++) {
		if (dom.style[cssAnimation[i]] != undefined) {
			return animationEnd[cssAnimation[i]];
		}
	}
	return undefined;
}
function getFontSize() {
	var clientWidth = document.documentElement.clientWidth;
	if (clientWidth < 640) return 16 * (clientWidth / 375) + "px";else return 16;
}

var layer = {
	initOpen: function initOpen(dom, options) {

		dom.style.fontSize = getFontSize();

		var body = document.querySelector("body");
		var bg = document.createElement("div");
		addClass(bg, "dialog-mobile-bg");
		if (options.showBottom == true) {
			addClass(bg, "animation-bg-fadeIn");
		}

		if (options.bottom) {
			bg.addEventListener("click", function () {
				handleClose();
			});
		}

		body.appendChild(bg);
		body.appendChild(dom);

		var animationEndName = getAnimationEndName(dom);
		function handleClose() {
			if (animationEndName) {
				layer.close([bg]);
				addClass(dom, options.closeAnimation);
				dom.addEventListener(animationEndName, function () {
					layer.close([dom]);
				});
			} else {
				layer.close([bg, dom]);
			}
		}

		//set button click event
		options.btns.forEach(function (btn, i) {
			if (i != 0 && i <= options.btns.length - 1) {
				if (!options.bottom) {
					btn.addEventListener("click", function () {
						handleClose();
						options.sureBtnClick();
					});
				} else {
					btn.addEventListener("click", function () {
						handleClose();
						options.btnClick(this.getAttribute("i"));
					});
				}
			} else {
				btn.addEventListener("click", handleClose);
			}
		});

		if (!options.bottom) {
			//set position
			dom.style.top = (document.documentElement.clientHeight - dom.offsetHeight) / 2 + "px";
			dom.style.left = (document.documentElement.clientWidth - dom.offsetWidth) / 2 + "px";
		}
	},
	close: function close(doms) {
		var body = document.querySelector("body");
		for (var i = 0; i < doms.length; i++) {
			body.removeChild(doms[i]);
		}
	}
};

var mcxDialog = {
	alert: function alert(content) {
		var btn = document.createElement("div");
		btn.innerText = "OK";
		addClass(btn, "dialog-button");

		var opts = {};
		opts.btns = [btn];

		this.open(content, opts);
	},
	confirm: function confirm(content, options) {
		var opts = {
			sureBtnText: "OK",
			sureBtnClick: function sureBtnClick() {}
		};
		opts = extend(opts, options);

		var cancelBtn = document.createElement("div");
		cancelBtn.innerText = "Cancel";
		addClass(cancelBtn, "dialog-cancel-button");

		var sureBtn = document.createElement("div");
		sureBtn.innerText = opts.sureBtnText;
		addClass(sureBtn, "dialog-sure-button");

		opts.btns = [cancelBtn, sureBtn];
		this.open(content, opts);
	},
	open: function open(content, options) {
		var dialog = document.createElement("div");
		var dialogContent = document.createElement("div");

		addClass(dialog, "dialog-mobile");
		addClass(dialog, "animation-zoom-in");
		addClass(dialogContent, "dialog-content");

		dialogContent.innerText = content;

		dialog.appendChild(dialogContent);

		options.btns.forEach(function (btn, i) {
			dialog.appendChild(btn);
		});
		options.closeAnimation = "animation-zoom-out";

		layer.initOpen(dialog, options);
	}
};

// providing better operations in Vue
mcxDialog.install = function (Vue, options) {
	Vue.prototype.$mcxDialog = mcxDialog;
};

return mcxDialog;

})));