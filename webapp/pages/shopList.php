<!DOCTYPE html>
<html>
<head>
    <title><? _e('TAOSMI Regalos') ?> - <? _e('Wish List') ?></title>
    <? $this->pattern('/resources') ?>
    <script type="text/javascript" src="/webapp/statics/js/component.js"></script>
    <script type="text/javascript" src="/webapp/statics/js/shoplist.js"></script>
</head>
<body>
    <!-- Header and menu -->
    <? $this->pattern('/header', array('opt' => 'shoplist')) ?>

    <main>
        <!-- Main title -->
        <h1><? _e('Wish List') ?></h1>

        <!-- Gifts -->
        <section class="listcard">
            <div>
                <a href="/gift" class="button primary"><? _e('New gift') ?></a>
            </div>
            <p id="info"><? _e('Loading, please wait...') ?></p>
            <ul>
                <li id="giftTemplate" class="gifts">
                    <figure>
                        <img class="gift" src="{!image}"/>
                    </figure>
                    <img data-sup="{?status:gotit}" src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fimg1.pnghut.com%2F18%2F14%2F9%2FK7AQfzz4Ri%2Fcheckbox-icon-design-flat-check-mark-cheque.jpg&amp;f=1&amp;nofb=1" style="position: relative;width: 3.6%;top: -20px;left: 160px;float: right;">
                    <a href="{!link}" class="seenHere" target="_blank">
                        <? _e('Seen here') ?> <img src="/webapp/statics/imgs/link.png">
                    </a>
                    <p class="createdOn"><? _e('Created on') ?> {createdOn}</p>
                    <p class="title">{title}</p>
                    <p class="desc">{description}</p>
                    <a href="/gift/{giftId}" class="button edit"><? _e('Edit') ?></a>
                    <a href="" class="button edit">{status}<? _e('Got it!') ?></a>
                    <p class="privacy">
                        <img src="/webapp/statics/imgs/{privacy}.png"/>
                        {privacy}
                    </p>
                </li>
            </ul>
        </section>

    </main>

</body>
</html>