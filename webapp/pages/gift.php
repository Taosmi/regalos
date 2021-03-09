<!DOCTYPE html>
<html>
<head>
    <title><? _e('TAOSMI Regalos') ?> - <? _e('Gift') ?></title>
    <? $this->pattern('/resources') ?>
    <script type="text/javascript" src="/webapp/statics/js/gift.js"></script>
</head>
<body>
    <!-- Header and menu -->
    <? $this->pattern('/header', array('opt' => 'shoplist')) ?>

    <main>
        <!-- Main title -->
        <h1><? _e('Gift Manipulation') ?></h1>

        <!-- Gifts -->
        <section class="card gift">
            <form id="gift" data-giftid="<? echo current($params) ?>">
                <fieldset>
                    <ul class="twoCols">
                        <li>
                            <label for="title"><? _e('Title') ?></label>
                            <input type="text" id="title" class="fullsize"/>
                        </li>
                        <li>
                            <label for="description"><? _e('Description') ?></label>
                            <textarea id="description" placeholder="<? _e('Optional') ?>" class="fullsize" rows="5"></textarea>
                        </li>
                    </ul>
                    <ul class="twoCols">
                        <li class="file">
                            <label><? _e('Image') ?></label>
                            <input type="file" id="fileInput"/>
                            <p id="loadImg" data-urlimg="" data-img="">
                                <a id="loadFileButton" class="button"><? _e('From file') ?></a>
                                <a id="loadURLButton" class="button" data-msg="<? _e('Please copy the URL of the image:')?>"><? _e('From URL') ?></a>
                            </p>
                        </li>
                    </ul>
                    <ul>
                        <li>
                            <label for="link"><? _e('Link') ?></label>
                            <input type="text" id="link" placeholder="<? _e('Optional') ?>" class="fullsize"/>
                        </li>
                        <li>
                            <label><? _e('Privacy') ?></label>
                            <input type="radio" name="privacy" id="public" value="public" checked="checked"/>
                            <label for="public" class="text"><? _e('Public, everyone can see it') ?></label>
                            <input type="radio" name="privacy" id="private" value="private"/>
                            <label for="private" class="text"><? _e('Private, only your friends can see it') ?></label>
                        </li>
                    </ul>
                </fieldset>
                <fieldset>
                    <button type="submit" class="primary fullsize"><? _e('Save') ?></button>
                    <button type="button" id="delete" class="secondary fullsize"><? _e('Remove') ?></button>
                    <a href="/shopList" class="secondary"><? _e('Back to Wish List') ?></a>
                </fieldset>
            </form>
        </section>

    </main>

</body>
</html>