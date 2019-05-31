<?php
define('NEXT_IMAGE_STATE_PATH', $_SERVER['DOCUMENT_ROOT'] . ".next_image_state");
define('UPLOADS_URL_PATH', '/uploads/images/');
define('UPLOADS_FILE_PATH', $_SERVER['DOCUMENT_ROOT'] . UPLOADS_URL_PATH);
?>

<script type="text/javascript">
    var UPLOADS_URL_PATH = <?php echo '"' . UPLOADS_URL_PATH . '"'?>;
</script>
