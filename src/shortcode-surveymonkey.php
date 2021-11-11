<?php
add_shortcode('survey', function () {
    ob_start();
    ?>
    <div class="container mx-auto py-10">
        <style>
            .smcx-embed {
                max-width: 100% !important;
                height: 100vh !important;
            }

            .smcx-embed > .smcx-iframe-container {
                max-width: 100% !important;
                height: 100vh !important;
            }
        </style>
        <script>
            (function (t, e, s, n) {
                var o, a, c;
                t.SMCX = t.SMCX || [], e.getElementById(n) || (o = e.getElementsByTagName(s), a = o[o.length - 1], c = e.createElement(s), c.type = "text/javascript", c.async = !0, c.id = n,
                    c.src = "https://widget.surveymonkey.com/collect/website/js/tRaiETqnLgj758hTBazgdzg2p6Dy7FWCIOJdvfpciaabxi8y1mPtZ2ZHXaNL4WU_2B.js",
                    a.parentNode.insertBefore(c, a))
            })(window, document, "script", "smcx-sdk");
        </script>
    </div>
    <?php
    return ob_get_clean();
});