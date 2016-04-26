<?php

use Library\Helper;
require_once(APP_PATH."Library/CommonHelper.php");
$helperYML= new Helper();
?><?xml version="1.0" encoding="<?php echo $data['encoding']; ?>"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="<?php echo date('Y-m-d H:i', time()) ?>">
    <shop>
        <name><?php echo $shop['name'] ?></name>
        <company><?php echo $shop['title'] ?></company>
        <url>http://<?php echo $host ?>/</url>
        <currencies>
            <currency id="<?php echo $shop['currency'] ?>" rate="1"/>
        </currencies>
        <categories>
            <?php  // load categories at first ?>
            <?php foreach($categories as $category) : ?>
            <?php if(empty($category['parentId'])): ?>
            <category id="<?php echo $category['id'] ?>"><?php echo $helperYML->YMLTextPrepare($category['title']) ?></category>
            <?php endif;?>
            <?php endforeach; ?>
            <?php  // load childs categories ?>
            <?php foreach($categories as $category) : ?>
            <?php if(!empty($category['parentId'])): ?>
            <category id="<?php echo $category['id'] ?>" parentID="<?php echo $category['parentId']; ?>"><?php echo $helperYML->YMLTextPrepare($category['title']) ?></category>
            <?php endif;?>
            <?php endforeach; ?>
        </categories>
        <delivery-options>
            <option cost="0" days="1-3" />
        </delivery-options>
        <offers>
            <?php foreach($products as $product) : ?>

            <?php $available = ($product['status'] > 0) ? 'true' : 'false'; ?>
            <?php if (!isset($product['categoriesId'])) continue;?>
            <?php if (in_array($product['categoriesId'], $excludeCategories)) continue;?>
            <?php if(!isset($product['prices'][$priceId])) continue;
                else $prices = $product['prices'][$priceId]; ?>
            <?php if (!is_array($product['photos']) || !count($product['photos'])) continue; ?>
            <?php if (count($product['photos']) > 10) $product['photos'] = array_slice($product['photos'], 0, 10); ?>
            <?php $measures = json_decode($product['measures'], true); ?>
            <?php if(!empty($product['sizes'])): ?>
            <?php foreach($product['sizes'] as $size => $count): ?>
            <?php if($count > 0): ?>
            <offer id="<?php echo trim($size.'.'.$product['articul']); ?>" group_id="<?php echo trim($product['articul']);?>" selling_type="u" available="<?php echo $available;?>">
                <name><?php echo $helperYML->YMLTextPrepare($product['title']) ?> <?php echo $helperYML->YMLTextPrepare($product['brand']) ?></name>
                <categoryId><?php echo $product['categoriesId'] ?></categoryId>
                <?php if($prices['discount_price'] == $prices['price']):?>
                <price><?php echo $prices['discount_price'] ?></price>
                <?php else: ?>
                <price><?php echo $prices['discount_price'] ?></price>
                <oldprice><?php echo $prices['price']; ?></oldprice>
                <?php endif; ?>
                <currencyId><?php echo $shop['currency'] ?></currencyId>
                <?php foreach ($product['photos'] as $photo) : ?>
                <picture><?php echo 'http://' . $host . '/f/p/800x800/catalogue/' . $product['id'] . '/' . $photo ?></picture>
                <?php endforeach; ?>
                <vendor><?php echo $helperYML->YMLTextPrepare($product['brand']) ?></vendor>
                <model><?php echo $helperYML->YMLTextPrepare($product['title']) ?></model>
                <barcode><?php echo trim($product['articul']); ?></barcode>
                <?php if(!empty($product['madeIn'])): ?>
                <country><?php echo $product['madeIn']; ?></country>
                <?php endif;?>
                <?php if(!empty($product['weight'])): ?>
                <param name="Вес" unit="гр"><?php echo $product['weight'] ?></param>
                <?php endif;?>
                <?php if(!empty($product['color'])): ?>
                <param name="Цвет"><?php echo $product['color'] ?></param>
                <?php endif;?>
                <?php if(isset($measures['material']) && !empty($measures['material'])): ?>
                <?php $materialAvailable = ''; foreach($measures['material'] as $material): ?>
                <?php foreach($material as $m => $percent): ?>
                <?php if(!empty($percent)):?>
                <?php $materialAvailable .= $m." ".$percent."% / " ?>
                <?php else: ?>
                <?php $materialAvailable .= $m." / " ?>
                <?php endif;?>
                <?php endforeach;?>
                <?php endforeach ?>
                <?php $sizesAvailable = ''; foreach ($product['sizes'] as $s => $v): ?>
                <?php if($v > 0) $sizesAvailable .= $s." / " ?>
                <?php endforeach; ?>
                <?php $sizesAvailable = rtrim($sizesAvailable, ' / '); ?>
                <?php if(!empty($sizesAvailable)):?>
                <param name="Доступные размеры"><?php echo $sizesAvailable; ?></param>
                <?php endif;?>
                <param name="Размер"><?php echo $size; ?></param>
                <param name="Материал" unit="Состав ткани"><?php echo rtrim($materialAvailable,' / '); ?></param>
                <?php endif;?>
                <description><![CDATA[<?php echo $helperYML->YMLTextPrepare($product['description']); ?>]]></description>
                <keywords><?php echo $helperYML->YMLKeywordsPrepare($product); ?></keywords>
            </offer>
            <?php endif;?>
            <?php endforeach;?>
            <?php unset($groupId); ?>
            <?php else:?>
            <offer id="0.<?php echo $product['articul'] ?>" selling_type="u" available="<?php echo $available;?>">
                <name><?php echo $helperYML->YMLTextPrepare($product['title']) ?></name>
                <categoryId><?php echo $product['categoriesId'] ?></categoryId>
                <?php if($prices['discount_price'] == $prices['price']):?>
                <price><?php echo $prices['discount_price'] ?></price>
                <?php else: ?>
                <price><?php echo $prices['discount_price'] ?></price>
                <oldprice><?php echo $prices['price']; ?></oldprice>
                <?php endif; ?>
                <?php if(!empty($prices['discount'])):?>
                <discount><?php echo $prices['discount'];?></discount>
                <?php endif; ?>
                <currencyId><?php echo $shop['currency'] ?></currencyId>
                <?php foreach ($product['photos'] as $photo) : ?>
                <picture><?php echo 'http://' . $host . '/f/p/800x800/catalogue/' . $product['id'] . '/' . $photo ?></picture>
                <?php endforeach; ?>
                <vendor><?php echo $helperYML->YMLTextPrepare($product['brand']) ?></vendor>
                <barcode><?php echo $product['articul']; ?></barcode>
                <?php if(!empty($product['madeIn'])): ?>
                <country><?php echo $product['madeIn']; ?></country>
                <?php endif;?>
                <?php if(!empty($product['weight'])): ?>
                <param name="Вес" unit="гр"><?php echo $product['weight'] ?></param>
                <?php endif;?>
                <?php if(!empty($product['color'])): ?>
                <param name="Цвет"><?php echo $product['color'] ?></param>
                <?php endif;?>
                <?php if(isset($measures['material']) && !empty($measures['material'])): ?>
                <?php $materialAvailable = ''; foreach($measures['material'] as $material): ?>
                <?php foreach($material as $m => $percent): ?>
                <?php if(!empty($percent)):?>
                <?php $materialAvailable .= $m." ".$percent."% / " ?>
                <?php else: ?>
                <?php $materialAvailable .= $m." / " ?>
                <?php endif;?>
                <?php endforeach;?>
                <?php endforeach ?>
                <param name="Материал" unit="Состав ткани"><?php echo rtrim($materialAvailable,' / '); ?></param>
                <?php endif;?>
                <description><![CDATA[<?php echo $helperYML->YMLTextPrepare($product['description']); ?>]]></description>
                <available><?php echo $available;?></available>
            </offer>
            <?php endif;?>
            <?php endforeach;?>
        </offers>
    </shop>
</yml_catalog>