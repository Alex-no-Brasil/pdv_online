ALTER TABLE `tec_sales` ADD COLUMN `unique_key` VARCHAR(45) NOT NULL; 
ALTER TABLE `tec_sales` ADD UNIQUE INDEX `unique_key_UNIQUE` (`unique_key` ASC);
