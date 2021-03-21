<!DOCTYPE html>
<html>
<head>
    <title><? _e('TAOSMI Regalos') ?> - <? _e('Options') ?></title>
    <? $this->pattern('/resources') ?>
    <script type="text/javascript" src="/webapp/statics/js/options.js"></script>
</head>
<body>
    <!-- Header and menu -->
    <? $this->pattern('/header', array('opt' => 'options')) ?>

    <main>
        <!-- Main title -->
        <h1><? _e('Options') ?></h1>

        <!-- Gifts -->
        <section class="card user">
            <form id="user" data-id="<? echo $session['id'] ?>">
                <fieldset>
                    <ul class="twoCols">
                        <li>
                            <label for="name"><? _e('Name') ?></label>
                            <input type="text" id="name" class="fullsize"/>
                        </li>
                        <li>
                            <label for="email"><? _e('Email') ?></label>
                            <input type="text" id="email" class="fullsize"/>
                        </li>
                        <li>
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
                        </li>
                    </ul>
                    <ul class="twoCols">
                        <li class="file">
                            <label><? _e('Avatar') ?></label>
                            <input type="file" id="fileInput" />
                            <p id="loadAvatar">
                                <a id="loadAvatarButton" class="button"><? _e('Change avatar') ?></a>
                            </p>
                        </li>
                    </ul>
                </fieldset>
                <fieldset>
                    <input type="checkbox" id="policy" checked="checked" value="Y"/>
                    <label for="policy" class="text"><? _e('You agree to our Terms of Service and Privacy Policy') ?></label>
                </fieldset>
                <fieldset>
                    <button type="submit" class="primary fullsize"><? _e('Save') ?></button>
                    <a href="/changePassword" class="secondary"><? _e('Change password') ?></a>
                </fieldset>
            </form>
        </section>
    </main>

</body>
</html>