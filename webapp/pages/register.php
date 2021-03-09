<!DOCTYPE html>
<html>
<head>
    <title><? _e('TAOSMI Regalos') ?></title>
    <? $this->pattern('/resources'); ?>
    <script type="text/javascript" src="/webapp/statics/js/register.js"></script>
</head>
<body>
    <main>
        <!-- Gift image -->
        <figure id="logo">
            <img src="/webapp/statics/imgs/regalo02.png" alt="<? _e('TAOSMI Regalos') ?>"/>
        </figure>

        <!-- Main title -->
        <h1><? _e('Create your TAOSMI Regalos Account') ?></h1>

        <!-- New account card -->
        <section class="card register">
            <form id="registerForm" action="/v1/user" method="post">
                <fieldset>
                    <label for="name"><? _e('Name') ?></label>
                    <input type="text" id="name" class="fullsize"/>
                    <label for="email"><? _e('Email') ?></label>
                    <input type="text" id="email" class="fullsize"/>
                    <label for="day"><? _e('Birthday') ?></label>
                    <select id="day">
                    <? for ($i = 1; $i <= 31; $i += 1) { ?>
                        <option value="<? echo $i ?>"><? echo $i ?></option>
                    <? } ?>
                    </select>
                    <select id="month">
                        <option value="01"><? _e('January') ?></option>
                        <option value="02"><? _e('February') ?></option>
                        <option value="03"><? _e('March') ?></option>
                        <option value="04"><? _e('April') ?></option>
                        <option value="05"><? _e('May') ?></option>
                        <option value="06"><? _e('June') ?></option>
                        <option value="07"><? _e('July') ?></option>
                        <option value="08"><? _e('August') ?></option>
                        <option value="09"><? _e('September') ?></option>
                        <option value="10"><? _e('October') ?></option>
                        <option value="11"><? _e('November') ?></option>
                        <option value="12"><? _e('December') ?></option>
                    </select>
                    <select id="year">
                    <? for ($any = date('Y'), $i = ($any - 100); $i <= $any; $i += 1) { ?>
                        <option value="<? echo $i ?>"><? echo $i ?></option>
                    <? } ?>
                    </select>
                    <label for="password"><? _e('Password') ?></label>
                    <input type="password" id="password" class="fullsize"/>
                    <label for="rePassword"><? _e('Repeat the password') ?></label>
                    <input type="password" id="rePassword" class="fullsize"/>
                </fieldset>
                <fieldset>
                    <input type="checkbox" id="policy" checked="checked" value="Y"/>
                    <label for="policy" class="text"><? _e('By joining TAOSMI, you agree to our Terms of Service and Privacy Policy') ?></label>
                </fieldset>
                <fieldset>
                    <button type="submit" class="primary fullsize"><? _e('Create account') ?></button>
                    <a href="/" class="secondary"><? _e('Back to Homepage') ?></a>
                </fieldset>
            </form>
        </section>
    </main>
</body>
</html>