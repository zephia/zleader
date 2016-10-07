(function () {
    function parseQueryString() {
        var qstr = window.location.search;
        var query = {};
        var a = qstr.substr(1).split('&');
        for (var i = 0; i < a.length; i++) {
            var b = a[i].split('=');
            if (decodeURIComponent(b[0]) != '') {
                query[decodeURIComponent(b[0])] = decodeURIComponent(b[1]);
            }
        }
        return query;
    }

    function createCookie(name, value, hours) {
        var expires;
        if (hours) {
            var date = new Date();
            date.setTime(date.getTime() + (hours * 60 * 60 * 1000));
            expires = "; expires=" + date.toGMTString();
        }
        else {
            expires = "";
        }
        document.cookie = name + "=" + value + expires + "; path=/";
    }

    function readCookies() {
        var a = document.cookie.split(';');
        var cookies = {};
        for (var i = 0; i < a.length; i++) {
            var b = a[i].split('=');
            if (decodeURIComponent(b[0]) != '') {
                cookies[decodeURIComponent(b[0]).trim()] = decodeURIComponent(b[1]).trim();
            }
        }
        return cookies;
    }

    var qs_params = parseQueryString();
    for (i in qs_params) {
        if (qs_params.hasOwnProperty(i)) {
            var param = i.split('_');
            if (param.length > 1) {
                switch (param[0]) {
                    case 'utm':
                    case 'zlfield':
                        createCookie(i, qs_params[i], 1);
                        break;
                }
            }
        }
    }
    var cookies = readCookies();
    var forms = document.getElementsByTagName('form'), i, j;
    for (i in forms) {
        if (forms.hasOwnProperty(i)) {
            var cn = forms[i].className;
            if (cn && cn.match(new RegExp("(^|\\s)zlform(\\s|$)"))) {
                for (j in cookies) {
                    if (cookies.hasOwnProperty(j)) {
                        var cookie_param = j.split('_');
                        if (cookie_param.length > 1) {
                            switch (cookie_param[0]) {
                                case 'utm':
                                case 'zlfield':
                                    var input = document.createElement("input");
                                    input.setAttribute("type", "hidden");
                                    input.setAttribute("name", j);
                                    input.setAttribute("value", cookies[j]);
                                    forms[i].appendChild(input);
                                    break;
                            }
                        }
                    }
                }
            }
        }
    }
})();