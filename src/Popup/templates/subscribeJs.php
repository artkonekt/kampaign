<?php

use Artkonekt\Kampaign\Common\DataResolver;

?>

<script>
$(document).ready(function () {
    $('body').on('submit', '#nlc-subscriber-form', function () {
        $.ajax({
            url: "<?= $this->subscriptionUrl ?>",
            method: "POST",
            data: {
            <?= DataResolver::CAMPAIGN_ID_KEY ?>: <?= $campaign->getTrackingId() ?>,
            <?= DataResolver::SUBSCRIBER_EMAIL_KEY ?>: $("input[name=<?= DataResolver::SUBSCRIBER_EMAIL_KEY ?>]").val()
        },
        success: function(data) {
            $('#subscription-success').html(data);
            $('#subscription-success').show();
            $('#nlc-subscriber-form').hide();
            $('#subscription-error').hide();
        },
        error: function(data, textStatus, errorThrown) {
            $('#subscription-error').html(errorThrown);
            $('#subscription-error').show();
            $('#subscription-success').hide();
        }
    });

        return false;
    });
});
</script>