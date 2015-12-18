<?php use Artkonekt\Kampaign\Common\DataResolver; ?>

<div class="alert alert-danger">
    <b>DEBUG INFO</b>: <?= $text ?>
    <hr/>
    <input type="button" id="kampaign-debug-clear-impressions" value="Clear all previous impression data" class="btn btn-default">
    <div id="kampaign-debug-clear-impressions-success" class="alert alert-success" style="display: none;">Previous impression data cleared</div>
</div>

<script>
    (function() {
        function delete_cookie(name) {
            document.cookie = name + '=;path=/;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        };

        $(document).ready(function() {
            $('body').on('click', '#kampaign-debug-clear-impressions', function() {
                delete_cookie("<?= DataResolver::COOKIE_NAME ?>");
                $('#kampaign-debug-clear-impressions').hide();
                $('#kampaign-debug-clear-impressions-success').show();
            });
        });
    }());
</script>