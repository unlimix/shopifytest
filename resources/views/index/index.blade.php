<?php
/**
 * @var \App\Data\Product[] $products
 * @var array $tags
 */
?>
<h1>Shopify Basic App</h1>
<a href="{{ url('/create-product') }}">Create Product</a>
<div style="margin-top: 50px;margin-left: 25px;">
    {!! Form::open(['url' => '/']) !!}
    {!! Form::text('tags') !!}
    {!! Form::submit('filter') !!}
    {!! Form::close() !!}
    <div>
        <?php foreach ($tags as $tag) { ?>
        <span style="background-color: #d9d9d9; padding: 2px 10px;"><?= $tag ?></span>
        <?php } ?>
    </div>
</div>
<div style="margin-top: 50px;">
    <?php foreach ($products as $productItem) { ?>
    <div style="float: left; border: 1px solid gray; padding: 25px; margin-left: 25px;">
        <div>
            <img src="<?= $productItem->getImageSrc() ?>">
        </div>
        <div>
            <div><?= $productItem->getTitle() ?></div>
            <ul>
                <?php foreach ($productItem->getTags() as $tagName) { ?>
                <li><?= $tagName ?></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <?php } ?>
</div>
