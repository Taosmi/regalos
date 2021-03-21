<!DOCTYPE html>
<html>
<head>
    <title><? _e('TAOSMI Regalos') ?> - <? _e('Recent community activity') ?></title>
    <? $this->pattern('/resources') ?>
    <script type="text/javascript" src="/webapp/statics/js/news.js"></script>
</head>
<body>
    <!-- Header and menu -->
    <? $this->pattern('/header', array('opt' => 'news')) ?>

    <main>
        <!-- Main title -->
        <h1><? _e('Recent community activity') ?></h1>

        <!-- Gifts -->
        <section class="listcard">
            <div>
                <input type="text" id="username" placeholder="<? _e('All users') ?>" value="" />
            </div>
            <p id="info"><? _e('Loading, please wait...') ?></p>
            <ul class="threeCols">
                <li id="giftTemplate1" class="news">
                    <img class="user" src="/webapp/statics/imgs/users/{userId}"/>
                    <a href="{!link}" class="seenHere" target="_blank">
                        <? _e('Seen here') ?> <img src="/webapp/statics/imgs/link.png">
                    </a>
                    <p class="user">{username}</p>
                    <p class="date">{createdOn}</p>
                    <img class="gift" src="{!image}"/>
                    <p class="title">{title}</p>
                    <p class="desc">{description}</p>
                </li>
            </ul>
            <ul class="threeCols">
                <li id="giftTemplate2" class="news">
                    <img class="user" src="/webapp/statics/imgs/users/{userId}"/>
                    <a href="{!link}" class="seenHere" target="_blank">
                        <? _e('Seen here') ?> <img src="/webapp/statics/imgs/link.png">
                    </a>
                    <p class="user">{username}</p>
                    <p class="date">{createdOn}</p>
                    <img class="gift" src="{!image}"/>
                    <p class="title">{title}</p>
                    <p class="desc">{description}</p>
                </li>
            </ul>
            <ul class="threeCols">
                <li id="giftTemplate3" class="news">
                    <img class="user" src="/webapp/statics/imgs/users/{userId}"/>
                    <a href="{!link}" class="seenHere target="_blank">
                        <? _e('Seen here') ?> <img src="/webapp/statics/imgs/link.png">
                    </a>
                    <p class="user">{username}</p>
                    <p class="date">{createdOn}</p>
                    <img class="gift" src="{!image}"/>
                    <p class="title">{title}</p>
                    <p class="desc">{description}</p>
                </li>
            </ul>
        </section>

    </main>

</body>
</html>