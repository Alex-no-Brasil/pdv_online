CREATE  TABLE `tec_products_estoque_lojas` (
  `code` VARCHAR(45) NOT NULL,
  `ean` VARCHAR(45) NOT NULL,
  `cod_loja` VARCHAR(45) NOT NULL,
  `quantity` INT NOT NULL ,
  PRIMARY KEY (`code`, `cod_loja`)
);
