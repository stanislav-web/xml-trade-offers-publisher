<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="<?php echo date('Y-m-d H:i', time()) ?>">
    <shop>
        <name><?=$this->data['shop']['name'];?></name>
        <company><?=$this->data['shop']['name'];?> <?=$this->data['shop']['url'];?></company>
        <url><?=$this->data['shop']['url'];?></url>

        <currencies>
            <currency id="<?=$this->data['shop']['currency'] ?>" rate="1"/>
        </currencies>

        <categories>
            <?php foreach($this->data['categories'] as $category) : ?>
                <?php if($category['parentId'] === $category['categoryId']): ?>
                    <category id="<?=$category['categoryId'];; ?>"><?=$category['categoryName'];?></category>
                <?php else: ?>
                    <category id="<?=$category['categoryId'] ?>" parentID="<?=$category['parentId']; ?>"><?=$category['categoryName']; ?></category>
                <?php endif;?>
            <?php endforeach; ?>
        </categories>

        <delivery-options>
            <option cost="0" days="1-3" />
        </delivery-options>
        <offers>
            <?php foreach($this->data['products'] as $product) : ?>
                <?php if($product['available'] <= 0): ?>
                    <?php continue; ?>
                <?php endif;?>
                <?php if(false === isset($product['sizes']) && true === isset($product['photos']) && isset($product['price'])): ?>
                    <offer id="<?=$product['productArticul'] ?>" selling_type="u" available="<?=($product['available'] > 0) ? 'true' : 'false';?>">
                        <name><?=$product['productName']; ?></name>
                        <categoryId><?=$product['categoryId'];?></categoryId>
                        <?php if($product['discountValue'] == $product['price']):?>
                        <price><?=$product['price'] ?></price>
                        <?php else: ?>
                        <price><?=$product['discountValue'] ?></price>
                        <oldprice><?=$product['price']; ?></oldprice>
                        <?php endif; ?>
                        <?php if( 0 < $product['discountPercent']):?>
                        <discount><?=$product['discountPercent'];?></discount>
                        <?php endif; ?>
                        <currencyId><?=$this->data['shop']['currency'] ?></currencyId>
                        <?php foreach ($product['photos'] as $photo) : ?>
                        <picture><?=$photo;?></picture>
                        <?php endforeach;?>
                        <barcode><?=$product['productArticul'] ?></barcode>
                        <?php if(false === empty($product['brand'])):?>
                            <vendor><?=$product['brand'];?></vendor>
                        <?php endif;?>
                        <?php if(false === empty($product['country'])):?>
                        <country><?=$product['country'];?></country>
                        <?php endif;?>
                        <?php if(false === empty($product['properties'])):?>
                            <?php foreach($product['properties'] as $property):?>
                                <?php if(true === isset($this->data['units']['name'][$property['name']])): ?>
                                    <param name="<?=$this->data['units']['name'][$property['name']];?>" unit="<?=$this->data['units']['unit'][$property['unit']];?>"><?=$property['value'];?></param>
                                <?php else: ?>
                                    <param name="<?=$property['name'];?>"><?$property['value'];?></param>
                                <?php endif;?>
                            <?php endforeach; ?>
                        <?php endif;?>
                        <?php if(false === empty($product['keywords'])):?>
                        <keywords><?=$product['keywords'];?></keywords>
                        <?php endif;?>
                        <available><?=$product['available'];?></available>
                    </offer>
                <?php elseif(true === isset($product['photos']) && isset($product['price'])): ?>
                    <?php foreach($product['sizes'] as $size): ?>
                        <offer id="<?=$size['size'].$product['productArticul'] ?>" selling_type="u" available="<?=($product['available'] > 0) ? 'true' : 'false';?>">
                            <name><?=$product['productName'] ?></name>
                            <categoryId><?=$product['categoryId'];?></categoryId>
                            <?php if($product['discountValue'] == $product['price']):?>
                            <price><?=$product['price'] ?></price>
                            <?php else: ?>
                            <price><?=$product['discountValue'] ?></price>
                            <oldprice><?=$product['price']; ?></oldprice>
                            <?php endif; ?>
                            <?php if( 0 < $product['discountPercent']):?>
                            <discount><?=$product['discountPercent'];?></discount>
                            <?php endif; ?>
                            <currencyId><?=$this->data['shop']['currency'] ?></currencyId>
                            <?php foreach ($product['photos'] as $photo) : ?>
                            <picture><?=$photo;?></picture>
                            <?php endforeach;?>
                            <barcode><?=$product['productArticul'] ?></barcode>
                            <?php if(false === empty($product['brand'])):?>
                            <vendor><?=$product['brand'];?></vendor>
                            <?php endif;?>
                            <?php if(false === empty($product['country'])):?>
                            <country><?=$product['country'];?></country>
                            <?php endif;?>
                            <?php if(false === empty($product['properties'])):?>
                                <?php foreach($product['properties'] as $property):?>
                                    <?php if(true === isset($this->data['units']['name'][$property['name']])): ?>
                                        <param name="<?=$this->data['units']['name'][$property['name']];?>" unit="<?=$this->data['units']['unit'][$property['unit']];?>"><?=$property['value'];?></param>
                                    <?php else: ?>
                                        <param name="<?=$property['name'];?>"><?$property['value'];?></param>
                                    <?php endif;?>
                                <?php endforeach; ?>
                            <?php endif;?>
                            <?php if(false === empty($size['properties'])):?>
                                <?php foreach($size['properties'] as $sizeProperty):?>
                                    <?php if(isset($this->data['units']['unit'][$sizeProperty['unit']])): ?>
                                        <param unit="<?=$this->data['units']['unit'][$sizeProperty['unit']];?>" name="<?=$sizeProperty['name'];?>"><?=$sizeProperty['value'];?></param>
                                    <?php else:?>
                                        <param unit="<?=$sizeProperty['unit'];?>" name="<?=$sizeProperty['name'];?>"><?=$sizeProperty['value'];?></param>
                                    <?php endif;?>
                                <?php endforeach; ?>
                            <?php endif;?>
                            <?php if(false === empty($product['keywords'])):?>
                            <keywords><?=$product['keywords'];?></keywords>
                            <?php endif;?>
                            <available><?=$product['available'];?></available>
                        </offer>
                    <?php endforeach; ?>
                <?php endif;?>
            <?php endforeach;?>
        </offers>
    </shop>
</yml_catalog>