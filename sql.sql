SET SESSION group_concat_max_len = 1000000;

SELECT STRAIGHT_JOIN
		prod.`id` AS productId, prod.`articul` AS productArticul, prod.`name` AS productName, prod.`factoryArticul` AS factoryArticul,

		# Load product attributes
		CONCAT('[',(
			SELECT GROUP_CONCAT(CONCAT('{"propName":"', attr.name, '", "propValue":"',prop.value,'", "attrTrans":"', IFNULL(attr.translationId,0),'", "propTrans":"', IFNULL(prop.translationId,0),'"}'))
			FROM productProperties AS prop
			INNER JOIN attributes AS attr ON (attr.`id` = prop.`attributeId`)
			WHERE prop.productId = prod.`id` && prop.`value` !=''
		), ']') AS productAttributes,

		# Load product images
		(
			SELECT prop.value FROM productMarketingProperties AS prop
			WHERE prop.productId = prod.`id` && prop.`attributeId` = 27 && prop.`value` !=''
		) AS productImage,
		prodType.`id` AS categoryId, prodType.`name` AS categoryName,
		prodPrice.`value` AS productPrice, prodPrice.`discountValue` AS discountPrice, prodPrice.`discount` AS discountPercent, prodCurrency.name AS productCurrency

  		FROM `attributes` AS prodType

		# Load product by types (see prodType.`id` IN (*))
		INNER JOIN `productCategories` AS prodCateg ON (prodCateg.`attributeId` = prodType.`id`)

		# Load products
		INNER JOIN `products` AS prod ON (prod.`id` = prodCateg.`productId`)

		# Load product by price group (1)
		INNER JOIN `productPrices` AS prodPrice ON (prodPrice.`productId` = prodCateg.`productId` && prodPrice.`attributeId` = 1)

		# Load currency (see ...&& prodCurrency.`id` = 1)
		INNER JOIN `currencies` AS prodCurrency ON (prodCurrency.`id` = 5)

		# Filter by status: moderated (26)
		INNER JOIN `productMarketingProperties` AS prodMarkProp ON (prodMarkProp.`productId` = prodCateg.`productId` && prodMarkProp.`attributeId` = 26 && prodMarkProp.`value` = 1)

		INNER JOIN `productProperties` AS prodProp ON (prodProp.`productId` = prod.`id`)

		WHERE prodType.`id` IN (28, 290,309, 600) && prodCurrency.`id` = 1
		GROUP BY prod.id HAVING productImage IS NOT NULL