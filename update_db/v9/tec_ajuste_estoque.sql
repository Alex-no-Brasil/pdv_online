CREATE TABLE IF NOT EXISTS `tec_ajuste_estoque` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `cod_produto` VARCHAR(45) NULL,
  `quantity` INT NOT NULL,
  `cod_loja` VARCHAR(45) NULL,
  `createdBy` INT NOT NULL,
  `createdAt` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `sync_time` INT NOT NULL DEFAULT 0,
  `loja_quantity` INT NOT NULL DEFAULT 0,
  `loja_ajuste` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8;