<?php
if (session_status() == PHP_SESSION_NONE):
    session_start();
?>
    <div id="welcome-message" class="welcome-message">
        <p><?= $_SESSION['welcome_message']; ?></p>
    </div>
<?php
    unset($_SESSION['welcome_message']);
    session_write_close();
endif ?>