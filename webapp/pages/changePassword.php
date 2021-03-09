<!DOCTYPE html>
<html>
<head>
    <title><? _e('TAOSMI Regalos') ?> - <? _e('Change password') ?></title>
    <? $this->pattern('/resources') ?>
    <script type="text/javascript" src="/webapp/statics/js/changePassword.js"></script>
</head>
<body>
    <!-- Header and menu -->
    <? $this->pattern('/header', array('opt' => 'options')) ?>

    <main>
        <!-- Main title -->
        <h1><? _e('Change your TAOSMI Regalos password') ?></h1>

        <!-- Login form -->
        <section class="card recover">
            <p><? _e('Enter a new password. Please try to use a password with a enough sense of randomness that a little boy could not ever guess.') ?></p>
            <form id="resetForm" method="get">
                <fieldset>
                    <label for="password"><? _e('Password') ?></label>
                    <input type="text" id="password" class="fullsize" maxlength="100"/>
                    <label for="repassword"><? _e('Repeat the password') ?></label>
                    <input type="text" id="repassword" class="fullsize" maxlength="100"/>
                </fieldset>
                <fieldset>
                    <button type="submit" class="primary fullsize"><? _e('Change') ?></button>
                    <a href="/options" class="secondary"><? _e('Go back to Options') ?></a>
                </fieldset>
            </form>
        </section>
    </main>
</body>
</html>