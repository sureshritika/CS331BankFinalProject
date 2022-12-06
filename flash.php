<div class="container" id="flash">
    <?php $messages = getMessages(); ?>
    <?php if ($messages) : ?>
        <?php foreach ($messages as $msg) : ?>
            <div class="row justify-content-center">
                <div class="alert alert-<?php se($msg, 'color', 'info'); ?>" role="alert"><?php se($msg, "text", ""); ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<script>
    function moveMeUp(ele) {
        let target = document.getElementsByTagName("nav")[0];
        if (target) {
            target.after(ele);
        }
    }

    moveMeUp(document.getElementById("flash"));
</script>