<div>
    <img src="/images/just_do_it.jpg">
</div>

<!-- TODO name ids consistently -->
<!-- TODO Translatable strings -->
<!-- TODO Custom error messages with placeholders/callbacks (or some other solution?) -->

<p class="alert alert-info">Subscribe to our newsletter via our campaign <?= $campaign->getTrackingId() ?></p>

<div>
    <div id="subscription-success" class="alert alert-success" style="display: none"></div>
    <div id="subscription-error" class="alert alert-danger" style="display: none">Something went wrong. (TODO: What?)</div>
    <form id="nlc-subscriber-form">
        <label for="email">Email: </label>
        <input type="input" name="<?= $emailKey ?>" id="<?= $emailKey ?>">
        <input type="submit" value="Subscribe me!">
    </form>
</div>

<script>
    $(document).ready(function () {
        $('body').on('submit', '#nlc-subscriber-form', function () {
            $.ajax({
                url: "subscribe.php",
                method: "POST",
                data: {
                    <?= $campaignIdKey ?>: <?= $campaign->getTrackingId() ?>,
                    <?= $emailKey ?>: $("#<?= $emailKey ?>").val()
                },
                success: function (data) {
                    $('#subscription-success').html(data);
                    $('#subscription-success').show();
                    $('#nlc-subscriber-form').hide();
                },
                error: function (data) {
                    $('#subscription-error').show();
                }
            });

            return false;
        });
    });
</script>
