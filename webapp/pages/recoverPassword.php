<!DOCTYPE html>
<html>
<head>
    <title><? _e('TAOSMI Regalos') ?> - <? _e('Recover password') ?></title>
    <? $this->pattern('/resources') ?>
    <script type="text/javascript" src="/webapp/statics/js/recoverPassword.js"></script>
</head>
<body>
    <main>
        <!-- Gift image -->
        <figure id="logo">
            <img src="/webapp/statics/imgs/regalo01.png" alt="<? _e('TAOSMI Regalos') ?>"/>
        </figure>

        <!-- Main title -->
        <h1><? _e('Recover your TAOSMI Regalos password') ?></h1>

        <!-- Login form -->
        <section class="card recover">
            <p><? _e('Enter your email address used to sign up in TAOSMI Regalos. You will receive an email with your password.') ?></p>
            <form id="recoverForm" method="get">
                <fieldset>
                    <label for="email"><? _e('Email') ?></label>
                    <input type="text" id="email" class="fullsize" maxlength="100"/>
                </fieldset>
                <fieldset>
                    <button type="submit" class="primary fullsize"><? _e('Recover') ?></button>
                    <a href="/index" class="secondary"><? _e('I don\'t know what I am doing') ?></a>
                </fieldset>
            </form>
            <p><? _e('If you are not able to remember the email address used to sign up in TAOSMI Regalos, for reasons that doesn\'t mind right now, you only have one last option: send an email to "regalos@taosmi.es".') ?></p>
            <p><? _e('Your request will be kindly attended, violating all known standards and rights for data protection on this planet and on the rest of the solar system (excluding planets beyond the asteroid belt, Ceuta and Melilla) and you agree with this.') ?></p>
        </section>
    </main>
</body>
</html>