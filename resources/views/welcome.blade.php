<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <script src="/js/rxp-js.js"></script>
    </head>
    <body>
        <button id="payButtonId">Submit Payment</button>
        <script src="rxp-hpp.js"></script>
        <script>
            $(document).ready(function() {
              $.getJSON("/realex", function(jsonFromRequestEndpoint) {
                RealexHpp.setHppUrl("https://pay.sandbox.realexpayments.com/pay");
                RealexHpp.lightbox.init("payButtonId", "/realexReturn", jsonFromRequestEndpoint);
              });
            });
        </script>
    </body>
</html>
