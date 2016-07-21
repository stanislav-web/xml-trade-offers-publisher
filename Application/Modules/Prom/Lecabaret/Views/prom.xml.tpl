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
                <?php if(1 === count($product['properties'])): ?>
                    <offer id="<?=$product['productArticul'] ?>" selling_type="u" available="<?=($product['available'] > 0) ? 'true' : 'false';?>">
                        <!-- Product name -->
                        <name><?=$product['productName'] ?></name>
                        <!-- /Product name -->

                        <!-- Product category -->
                        <categoryId><?=$product['categoryId'];?></categoryId>
                        <!-- /Product category -->

                        <!-- Product Prices -->
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
                        <!-- /Product Prices -->

                        <!-- Product Pictures -->
                        <?php foreach ($product['photos'] as $photo) : ?>
                        <picture><?=$photo;?></picture>
                        <?php endforeach;?>
                        <!-- / Product Pictures -->

                        <!-- Product articul -->
                        <barcode><?=$product['productArticul'] ?></barcode>
                        <!-- / Product articul -->

                        <!-- Product brand (country is temporary) -->
                        <?php if(false === empty($product['country'])):?>
                            <vendor><?=$product['country'];?></vendor>
                        <?php endif;?>
                        <!-- / Product brand (country is temporary) -->

                        <!-- Product country -->
                        <?php if(false === empty($product['country'])):?>
                        <country><?=$product['country'];?></country>
                        <?php endif;?>
                        <!-- / Product country -->

                        <?php if(false === empty($product['properties'])):?>
                        <!-- Product required properties -->
                        <?php foreach($product['properties'] as $property):?>
                        <?php if(true === isset($this->data['units'][$property['attributeId']])): ?>
                            <param name="<?=($property['name'] === 'Weight') ? 'Вес' : $property['name'];?>" unit="<?=$this->data['units'][$property['attributeId']];?>"><?=$property['value'];?></param>
                        <?php else: ?>
                            <param name="<?=$property['name'];?>"><?$property['value'];?></param>
                        <?php endif;?>
                        <?php endforeach; ?>
                        <!-- /Product required properties -->
                        <?php endif;?>

                        <!-- Product keywords -->
                        <?php if(false === empty($product['keywords'])):?>
                        <keywords><?=$product['keywords'];?></keywords>
                        <?php endif;?>
                        <!-- / Product keywords -->


                        <available><?=$product['available'];?></available>
                    </offer>
                <?php else: //@TODO : Need Bassic(s) properties for loop through them as multiple product ?>
                    <offer id="<?=$product['productArticul'] ?>" selling_type="u" available="<?=($product['available'] > 0) ? 'true' : 'false';?>">
                        <!-- Product name -->
                        <name><?=$product['productName'] ?></name>
                        <!-- /Product name -->

                        <!-- Product category -->
                        <categoryId><?=$product['categoryId'];?></categoryId>
                        <!-- /Product category -->

                        <!-- Product Prices -->
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
                        <!-- /Product Prices -->

                        <!-- Product Pictures -->
                        <?php foreach ($product['photos'] as $photo) : ?>
                        <picture><?=$photo;?></picture>
                        <?php endforeach;?>
                        <!-- / Product Pictures -->

                        <!-- Product articul -->
                        <barcode><?=$product['productArticul'] ?></barcode>
                        <!-- / Product articul -->

                        <!-- Product brand (country is temporary) -->
                        <?php if(false === empty($product['country'])):?>
                        <vendor><?=$product['country'];?></vendor>
                        <?php endif;?>
                        <!-- / Product brand (country is temporary) -->

                        <!-- Product country -->
                        <?php if(false === empty($product['country'])):?>
                        <country><?=$product['country'];?></country>
                        <?php endif;?>
                        <!-- / Product country -->

                        <?php if(false === empty($product['properties'])):?>
                        <!-- Product required properties -->
                        <?php foreach($product['properties'] as $property):?>
                        <?php if(true === isset($this->data['units'][$property['attributeId']])): ?>
                        <param name="<?=$property['name'];?>" unit="<?=$this->data['units'][$property['attributeId']];?>"><?=$property['value'];?></param>
                        <?php else: ?>
                        <param name="<?=$property['name'];?>"><?$property['value'];?></param>
                        <?php endif;?>
                        <?php endforeach; ?>
                        <!-- /Product required properties -->
                        <?php endif;?>

                        <!-- Product keywords -->
                        <?php if(false === empty($product['keywords'])):?>
                        <keywords><?=$product['keywords'];?></keywords>
                        <?php endif;?>
                        <!-- / Product keywords -->


                        <available><?=$product['available'];?></available>
                    </offer>
                <?php endif;?>
            <?php endforeach;?>
        </offers>
    </shop>
</yml_catalog>