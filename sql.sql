LOCK TABLES `productsInStock` WRITE;
ALTER TABLE `productsInStock` ADD INDEX `idx_warehouseId` (`warehouseId`);
UNLOCK TABLES;