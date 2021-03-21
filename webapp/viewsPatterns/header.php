<header>
    <nav>
        <a id="news" href="/news" class="<? echo ($opt === 'news' ? 'selected' : '') ?>"><? _e('Activity') ?></a>
        <a id="shoplist" href="/shopList" class="<? echo ($opt === 'shoplist' ? 'selected' : '') ?>"><? _e('Wish List') ?></a>
        <? if ($session) { ?>
        <a href="#" id="exit" style="background-image:url('/webapp/statics/imgs/users/<? echo $session['id'] ?>')">
            <span><? _e('Logout') ?></span>
        </a>
        <? } else { ?>
        <a href="/" id="exit"><? _e('Login') ?></a>
        <? } ?>
        <a href="/options" id="options" class="<? echo ($opt === 'options' ? 'selected' : '') ?>"><? _e('Options') ?></a>
    </nav>
</header>