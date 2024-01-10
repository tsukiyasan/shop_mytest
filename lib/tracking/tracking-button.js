!function(e) {
    var t = {};
    function r(o) {
        if (t[o])
            return t[o].exports;
        var i = t[o] = {
            i: o,
            l: !1,
            exports: {}
        };
        return e[o].call(i.exports, i, i.exports, r),
        i.l = !0,
        i.exports
    }
    r.m = e,
    r.c = t,
    r.d = function(e, t, o) {
        r.o(e, t) || Object.defineProperty(e, t, {
            enumerable: !0,
            get: o
        })
    }
    ,
    r.r = function(e) {
        "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {
            value: "Module"
        }),
        Object.defineProperty(e, "__esModule", {
            value: !0
        })
    }
    ,
    r.t = function(e, t) {
        if (1 & t && (e = r(e)),
        8 & t)
            return e;
        if (4 & t && "object" == typeof e && e && e.__esModule)
            return e;
        var o = Object.create(null);
        if (r.r(o),
        Object.defineProperty(o, "default", {
            enumerable: !0,
            value: e
        }),
        2 & t && "string" != typeof e)
            for (var i in e)
                r.d(o, i, function(t) {
                    return e[t]
                }
                .bind(null, i));
        return o
    }
    ,
    r.n = function(e) {
        var t = e && e.__esModule ? function() {
            return e.default
        }
        : function() {
            return e
        }
        ;
        return r.d(t, "a", t),
        t
    }
    ,
    r.o = function(e, t) {
        return Object.prototype.hasOwnProperty.call(e, t)
    }
    ,
    r.p = "/",
    r(r.s = 80)
}({
    80: function(e, t, r) {
        e.exports = r(81)
    },
    81: function(e, t) {
        !function(e, t) {
            var r = t.createElement("style");
            r.innerHTML = ".iframebox-open{overflow:hidden;}.iframebox{opacity:0;transition:visibility 0s,opacity 0.2s linear;position:fixed;top:0;left:0;width:100%;height:100%;z-index:9999999;display:flex;background-color:rgba(0,0,0,.5)}.iframebox iframe{margin:auto;width:900px;height:625px;border:none;max-width:90%;max-height:90%;-moz-box-shadow:0 5px 10px #555;-webkit-box-shadow:0 5px 10px #555;box-shadow:0 5px 10px #555;background:url(https://mytrackcdn.com/img/loading.gif) center center no-repeat #FFF}.iframebox-close{position:fixed;top:20px;right:20px;width:40px;height:40px;cursor:pointer;text-align:center;background:#efeff0;border-radius:50%;color:#6e6d73}.iframebox-close:hover{text-decoration:none;color:#0a0a0a}.iframebox-close:focus{background:#3aa3e3;color:#fff}.iframebox-close:focus:after{color:#6e6d73}.iframebox-close:before{content:'\\00d7';font-size:25px;line-height:40px}.iframebox-embed{width:1000px;height:625px;border:1px solid #e0e0e0;max-width:100%;max-height:100%;background:url(https://mytrackcdn.com/img/loading.gif) center center no-repeat #FFF}",
            t.head.appendChild(r);
            e.TrackButton = {
                track: function(e) {
                    return "string" == typeof e.tracking_no && ((!e.courier || "string" == typeof e.courier) && ((!e.theme || "string" == typeof e.theme) && ((!e.lang || "string" == typeof e.lang) && ((!e.width || "string" == typeof e.width) && ((!e.height || "string" == typeof e.height) && void function(e) {
                        var r = !0
                          , o = !0;
                        if ("string" != typeof e.url)
                            return !1;
                        "boolean" == typeof e.closeBtn && (r = e.closeBtn),
                        "boolean" == typeof e.bodyHideScroll && (o = e.bodyHideScroll),
                        "function" == typeof e.afterClose && e.afterClose;
                        var i = t.querySelector(".iframebox");
                        if (i && (console.log("[Track Button] Script duplicate detected."),
                        i.parentNode.removeChild(i)),
                        !t.body)
                            return console.log("[Track Button] Please run the script after the opening <body> tag."),
                            !1;
                        var n = t.createElement("div");
                        n.className = "iframebox",
                        n.innerHTML = '<iframe src="' + e.url + '"></iframe>' + (r ? '<a title="Close" class="iframebox-close"></a>' : ""),
                        t.body.appendChild(n),
                        o && t.body.classList.add("iframebox-open");
                        var a = function() {
                            var e = t.querySelector(".iframebox");
                            return !!e && (e.style.opacity = 0,
                            setTimeout((function() {
                                e.parentNode.removeChild(e),
                                o && t.body.classList.remove("iframebox-open")
                            }
                            ), 200),
                            !0)
                        };
                        t.querySelector(".iframebox").addEventListener("click", a),
                        e.width && (t.querySelector(".iframebox iframe").style.width = e.width),
                        e.height && (t.querySelector(".iframebox iframe").style.height = e.height),
                        setTimeout((function() {
                            t.querySelector(".iframebox").style.opacity = 1
                        }
                        ), 50)
                    }({
                        url: "https://www.tracking.my/externalcall?style=iframebox&lang=" + (e.lang ? e.lang : "en") + "&tracking_no=" + e.tracking_no + (e.courier ? "&courier=" + e.courier : "") + (e.theme ? "&theme=" + e.theme : ""),
                        width: e.width ? e.width : "450px",
                        height: e.height ? e.height : "625px"
                    }))))))
                },
                embed: function(e) {
                    if ("string" != typeof e.selector)
                        return !1;
                    if ("string" != typeof e.tracking_no)
                        return !1;
                    if (e.courier && "string" != typeof e.courier)
                        return !1;
                    if (e.theme && "string" != typeof e.theme)
                        return !1;
                    if (e.lang && "string" != typeof e.lang)
                        return !1;
                    if (e.width && "string" != typeof e.width)
                        return !1;
                    if (e.height && "string" != typeof e.height)
                        return !1;
                    var r = "https://www.tracking.my/externalcall?style=iframebox&lang=" + (e.lang ? e.lang : "en") + "&tracking_no=" + e.tracking_no + (e.courier ? "&courier=" + e.courier : "") + (e.theme ? "&theme=" + e.theme : "");
                    t.querySelector(e.selector).innerHTML = '<iframe src="' + r + '" class="iframebox-embed" style="' + (e.width ? "width:" + e.width + ";" : "") + (e.height ? "height:" + e.height + ";" : "") + '"></iframe>'
                }
            }
        }(window, document)
    }
});
