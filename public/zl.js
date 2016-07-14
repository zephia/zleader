(function() {
    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }
    var utm_fields = ['utm_source', 'utm_medium', 'utm_term', 'utm_content', 'utm_campaign'];
    var forms = document.getElementsByTagName('form'),i;
    for (i in forms) {
        var cn = forms[i].className;
        if(cn && cn.match(new RegExp("(^|\\s)zlform(\\s|$)"))) {
            console.log(forms[i]);
            console.log(getParameterByName('utm_sources'));

            for (j in utm_fields) {
                var utm_value = getParameterByName(utm_fields[j]);
                if(utm_value != null) {
                    var input = document.createElement("input");
                    input.setAttribute("type", "hidden");
                    input.setAttribute("name", utm_fields[j]);
                    input.setAttribute("value", utm_value);
                    forms[i].appendChild(input);
                }
            }
        }
    }

    /*console.log();
    var f = document.getElementById("<ID_OF_THE_DIV>");
    var content = document.createTextNode("<YOUR_CONTENT>");
    theDiv.appendChild(content);*/
})();