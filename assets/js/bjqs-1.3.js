(function (e) {
    "use strict";
    e.fn.bjqs = function (t) {
        var n = {
            width: 300,
            height: 250,
            animtype: "fade",
            animduration: 400,
            animspeed: 4e3,
            automatic: true,
            showcontrols: true,
            centercontrols: true,
            nexttext: "",
            prevtext: "",
            showmarkers: true,
            centermarkers: true,
            keyboardnav: true,
            hoverpause: true,
            usecaptions: true,
            randomstart: false,
            responsive: false
        };
        var r = e.extend({}, n, t);
        var i = this,
            s = i.find(".slides"),
            o = s.children("a"),
            u = null,
            a = null,
            f = null,
            l = null,
            c = null,
            h = null,
            p = null,
            d = null;
        var v = {
            slidecount: o.length,
            animating: false,
            paused: false,
            currentslide: 1,
            nextslide: 0,
            currentindex: 0,
            nextindex: 0,
            interval: null
        };
        var m = {
            width: null,
            height: null,
            ratio: null
        };
        var g = {
            fwd: "forward",
            prev: "previous"
        };
        var y = function () {
            o.addClass("bjqs-slide");
            if (r.responsive) {
                b()
            } else {
                E()
            } if (v.slidecount > 1) {
                if (r.randomstart) {
                    L()
                }
                if (r.showcontrols) {
                    x()
                }
                if (r.showmarkers) {
                    T()
                }
                if (r.keyboardnav) {
                    N()
                }
                if (r.hoverpause && r.automatic) {
                    C()
                }
                if (r.animtype === "slide") {
                    S()
                }
            } else {
                r.automatic = false
            } if (r.usecaptions) {
                k()
            }
            if (r.animtype === "slide" && !r.randomstart) {
                v.currentindex = 1;
                v.currentslide = 2
            }
            s.show();
            o.eq(v.currentindex).show();
            if (r.automatic) {
                v.interval = setInterval(function () {
                    O(g.fwd, false)
                }, r.animspeed)
            }
        };
        var b = function () {
            m.width = i.outerWidth();
            m.ratio = m.width / r.width, m.height = r.height * m.ratio;
            o.css({
                height: r.height,
                width: "100%"
            });
            o.children("img").css({
                "max-height": "62%",
                "max-width": "100%"
            });
            s.css({
                height: r.height,
                width: "100%"
            });
            i.css({
                height: r.height,
                "max-width": r.width,
                position: "relative"
            });
            if (m.width < r.width) {
                o.css({
                    height: m.height
                });
                o.children("img").css({
                    height: m.height
                });
                s.css({
                    height: m.height
                });
                i.css({
                    height: m.height
                })
            }
            e(window).resize(function () {
                m.width = i.outerWidth();
                m.ratio = m.width / r.width, m.height = r.height * m.ratio;
                o.css({
                    height: m.height
                });
                o.children("img").css({
                    height: m.height
                });
                s.css({
                    height: m.height
                });
                i.css({
                    height: m.height
                })
            })
        };
        var w = function () {
            var e = {};
            return function (t, n, r) {
                if (!r) {
                    r = "Don't call this twice without a uniqueId"
                }
                if (e[r]) {
                    clearTimeout(e[r])
                }
                e[r] = setTimeout(t, n)
            }
        }();
        var E = function () {
            o.css({
                height: r.height,
                width: r.width
            });
            s.css({
                height: r.height,
                width: r.width
            });
            i.css({
                height: r.height,
                "max-width": r.width,
                position: "relative"
            })
        };
        var S = function () {
            p = o.eq(0).clone();
            d = o.eq(v.slidecount - 1).clone();
            p.attr({
                "data-clone": "last",
                "data-slide": 0
            }).appendTo(s).show();
            d.attr({
                "data-clone": "first",
                "data-slide": 0
            }).prependTo(s).show();
            o = s.children("a");
            v.slidecount = o.length;
            h = e('<div class="bjqs-wrapper"></div>');
            if (r.responsive && m.width < r.width) {
                h.css({
                    width: m.width,
                    height: m.height,
                    overflow: "hidden",
                    position: "relative"
                });
                s.css({
                    width: m.width * (v.slidecount + 2),
                    left: -m.width * v.currentslide
                })
            } else {
                h.css({
                    width: r.width,
                    height: r.height,
                    overflow: "hidden",
                    position: "relative"
                });
                s.css({
                    width: r.width * (v.slidecount + 2),
                    left: -r.width * v.currentslide
                })
            }
            o.css({
                "float": "left",
                position: "relative",
                display: "list-item"
            });
            h.prependTo(i);
            s.appendTo(h)
        };
        var x = function () {
            u = e('<ul class="bjqs-controls"></ul>');
            a = e('<li class="bjqs-next"><a href="#" data-direction="' + g.fwd + '"></a></li>');
            f = e('<li class="bjqs-prev"><a href="#" data-direction="' + g.prev + '"></a></li>');
            u.on("click", "a", function (t) {
                t.preventDefault();
                var n = e(this).attr("data-direction");
                if (!v.animating) {
                    if (n === g.fwd) {
                        O(g.fwd, false)
                    }
                    if (n === g.prev) {
                        O(g.prev, false)
                    }
                }
            });
            f.appendTo(u);
            a.appendTo(u);
            u.appendTo(i);
            if (r.centercontrols) {
                u.addClass("v-centered");
                var t = (i.height() - a.children("a").outerHeight()) / 2,
                    n = t / r.height * 100,
                    s = n + "%";
                a.find("a").css("top", s);
                f.find("a").css("top", s)
            }
        };
        var T = function () {
            l = e('<ol class="bjqs-markers"></ol>');
            e.each(o, function (t, n) {
                var i = t + 1,
                    s = t + 1;
                if (r.animtype === "slide") {
                    s = t + 2
                }
                var o = e('<li><a href="#">' + i + "</a></li>");
                if (i === v.currentslide) {
                    o.addClass("active-marker")
                }
                o.on("click", "a", function (e) {
                    e.preventDefault();
                    if (!v.animating && v.currentslide !== s) {
                        O(false, s)
                    }
                });
                o.appendTo(l)
            });
            l.appendTo(i);
            c = l.find("li");
            if (r.centermarkers) {
                l.addClass("h-centered");
                var t = (r.width - l.width()) / 2;
                l.css("left", t)
            }
        };
        var N = function () {
            e(document).keyup(function (e) {
                if (!v.paused) {
                    clearInterval(v.interval);
                    v.paused = true
                }
                if (!v.animating) {
                    if (e.keyCode === 39) {
                        e.preventDefault();
                        O(g.fwd, false)
                    } else if (e.keyCode === 37) {
                        e.preventDefault();
                        O(g.prev, false)
                    }
                }
                if (v.paused && r.automatic) {
                    v.interval = setInterval(function () {
                        O(g.fwd)
                    }, r.animspeed);
                    v.paused = false
                }
            })
        };
        var C = function () {
            i.hover(function () {
                if (!v.paused) {
                    clearInterval(v.interval);
                    v.paused = true
                }
            }, function () {
                if (v.paused) {
                    v.interval = setInterval(function () {
                        O(g.fwd, false)
                    }, r.animspeed);
                    v.paused = false
                }
            })
        };
        var k = function () {
            e.each(o, function (t, n) {
                var r = e(n).children("img:first-child").attr("title");
                if (!r) {
                    r = e(n).children("a").find("img:first-child").attr("title")
                }
                if (r) {
                    r = e('<p class="bjqs-caption">' + r + "</p>");
                    r.appendTo(e(n))
                }
            })
        };
        var L = function () {
            var e = Math.floor(Math.random() * v.slidecount) + 1;
            v.currentslide = e;
            v.currentindex = e - 1
        };
        var A = function (e) {
            if (e === g.fwd) {
                if (o.eq(v.currentindex).next().length) {
                    v.nextindex = v.currentindex + 1;
                    v.nextslide = v.currentslide + 1
                } else {
                    v.nextindex = 0;
                    v.nextslide = 1
                }
            } else {
                if (o.eq(v.currentindex).prev().length) {
                    v.nextindex = v.currentindex - 1;
                    v.nextslide = v.currentslide - 1
                } else {
                    v.nextindex = v.slidecount - 1;
                    v.nextslide = v.slidecount
                }
            }
        };
        var O = function (e, t) {
            if (!v.animating) {
                v.animating = true;
                if (t) {
                    v.nextslide = t;
                    v.nextindex = t - 1
                } else {
                    A(e)
                } if (r.animtype === "fade") {
                    if (r.showmarkers) {
                        c.removeClass("active-marker");
                        c.eq(v.nextindex).addClass("active-marker")
                    }
                    o.eq(v.currentindex).fadeOut(r.animduration);
                    o.eq(v.nextindex).fadeIn(r.animduration, function () {
                        v.animating = false;
                        v.currentslide = v.nextslide;
                        v.currentindex = v.nextindex
                    })
                }
                if (r.animtype === "slide") {
                    if (r.showmarkers) {
                        var n = v.nextindex - 1;
                        if (n === v.slidecount - 2) {
                            n = 0
                        } else if (n === -1) {
                            n = v.slidecount - 3
                        }
                        c.removeClass("active-marker");
                        c.eq(n).addClass("active-marker")
                    }
                    if (r.responsive && m.width < r.width) {
                        v.slidewidth = m.width
                    } else {
                        v.slidewidth = r.width
                    }
                    s.animate({
                        left: -v.nextindex * v.slidewidth
                    }, r.animduration, function () {
                        v.currentslide = v.nextslide;
                        v.currentindex = v.nextindex;
                        if (o.eq(v.currentindex).attr("data-clone") === "last") {
                            s.css({
                                left: -v.slidewidth
                            });
                            v.currentslide = 2;
                            v.currentindex = 1
                        } else if (o.eq(v.currentindex).attr("data-clone") === "first") {
                            s.css({
                                left: -v.slidewidth * (v.slidecount - 2)
                            });
                            v.currentslide = v.slidecount - 1;
                            v.currentindex = v.slidecount - 2
                        }
                        v.animating = false
                    })
                }
            }
        };
        y()
    }
})(jQuery)