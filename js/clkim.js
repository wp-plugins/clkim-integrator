var jQueryScriptOutputted = false;
function initJQuery() {
    if (typeof(jQuery) == 'undefined') {
        if (! jQueryScriptOutputted) {
            jQueryScriptOutputted = true;
            document.write("<scr" + "ipt type=\"text/javascript\" src=\"//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js\"></scr" + "ipt>");
        }
        setTimeout("initJQuery()", 50);
    } else {
        jQuery(function($) {

            if ( typeof(clkim) == 'undefined' || typeof(clkim.api) == 'undefined' || typeof(clkim.selector) == 'undefined' ) {
                console.log('Clk.im is not configured correctly');
				return;
            }

            $.getScript('http://www.clk.im/urlshortener.min.js',function(){

                // Shorten
                $(clkim.selector).shorten({
                    url : 'http://clk.im',
                    key : clkim.api,
                    links_type: clkim.links_type,
                    links_domains: clkim.links_domains
                });

            });
        });
    }
}
initJQuery();