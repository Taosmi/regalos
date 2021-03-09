<!DOCTYPE html>
<html>
<head>
    <title><? _e('TAOSMI Regalos') ?></title>
    <? $this->pattern('/resources') ?>
    <script type="text/javascript" src="/webapp/statics/js/login.js"></script>
</head>
<body>
    <main>
        <!-- Gift image -->
        <figure id="logo">
            <img src="/webapp/statics/imgs/regalo02.png" alt="<? _e('TAOSMI Regalos') ?>"/>
        </figure>

        <!-- Main title -->
        <h1><? _e('TAOSMI Regalos') ?></h1>

        <!-- Login form -->
        <section class="card login">
            <form id="loginForm" method="post">
                <fieldset class="language">
                    <img src="/webapp/statics/imgs/lang.png" />
                    <select id="language">
                        <option value="es_ES" selected="selected">Castellano</option>
                        <option value="en_US">English</option>
                        <option value="ca_ES">Mallorquí</option>
                    </select>
                </fieldset>
                <fieldset>
                    <label for="email"><? _e('Email') ?></label>
                    <input type="text" id="email" class="fullsize" maxlength="100"/>
                    <label for="pwd"><? _e('Password') ?></label>
                    <input type="password" id="pwd" class="fullsize"/>
                </fieldset>
                <fieldset>
                    <button type="submit" class="primary fullsize"><? _e('Login') ?></button>
                    <a href="/recoverPassword" class="secondary"><? _e('Forgot my password') ?></a>
                </fieldset>
            </form>
            <p id="newAccount">
                <? _e('Join TAOSMI Regalos') ?><br/>
                <a href="/register"><? _e('Sign up for free') ?></a>
            </p>
        </section>
    </main>

    <!-- Footer sponsor -->
    <footer id="sponsor">
        <a href="http://taosmi.es" title="TAOSMI technology TM">
            <img src="http://taosmi.es/webapps/taosmi.es/statics/imgs/brainTaosmiLogo.gif" alt="Creada por TAOSMI technology TM"/>
        </a>
    </footer>
</body>
</html>