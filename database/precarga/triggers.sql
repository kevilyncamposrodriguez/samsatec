delimiter #
Create trigger create_teams 
after insert on teams
for each row
begin
INSERT INTO internal_consecutives (id_company) VALUES (new.id);
insert into counts (id_company,name,description,id_count_primary,id_count,is_deleted) values 
(new.id,'CAJA GENERAL','CAJA GENERAL',34,null,'0'),
(new.id,'BANCO GENERAL','BANCO GENERAL',35,null,'0'),
(new.id,'CUENTAS POR COBRAR A CLIENTES','CUENTAS POR COBRAR A CLIENTES',36,null,'0'),
(new.id,'AVANCES Y ANTICIPOS ENTREGADOS','AVANCES Y ANTICIPOS ENTREGADOS',37,null,'0'),
(new.id,'IMPUESTOS A FAVOR','IMPUESTOS A FAVOR',38,null,'0'),
(new.id,'RETENCIONES A FAVOR','RETENCIONES A FAVOR',38,null,'0'),
(new.id,'CUENTAS POR COBRAR A EMPLEADOS','CUENTAS POR COBRAR A EMPLEADOS',39,null,'0'),
(new.id,'DEVOLUCIONES A PROVEEDORES','DEVOLUCIONES A PROVEEDORES',39,null,'0'),
(new.id,'PRESTAMOS A PERSONAS','PRESTAMOS A PERSONAS',3,null,'0'),
(new.id,'INVENTARIO GENERAL','INVENTARIO GENERAL',4,null,'0'),
(new.id,'FONDO DE INVERSIÓN PRINCIPAL','FONDO DE INVERSIÓN PRINCIPAL',5,null,'0'),
(new.id,'OTROS ACTIVOS CORRIENTES','OTROS ACTIVOS CORRIENTES',6,null,'0'),
(new.id,'PROPIEDAD, PLANTA Y EQUIPO','PROPIEDAD, PLANTA Y EQUIPO',7,null,'0'),
(new.id,'OTROS ACTIVOS NO CORRIENTES','OTROS ACTIVOS NO CORRIENTES',8,null,'0'),
(new.id,'CUENTAS POR PAGAR A PROVEEDORES','CUENTAS POR PAGAR A PROVEEDORES',40,null,'0'),
(new.id,'AVANCES Y ANTICIPOS RECIBIDOS','AVANCES Y ANTICIPOS RECIBIDOS',41,null,'0'),
(new.id,'DEVOLUCIONES A CLIENTES','DEVOLUCIONES A CLIENTES',42,null,'0'),
(new.id,'SALARIOS','SALARIOS',43,null,'0'),
(new.id,'PRESTACIONES SOCIALES','PRESTACIONES SOCIALES',43,null,'0'),
(new.id,'TARJETA PRINCIPAL','TARJETA PRINCIPAL',44,null,'0'),
(new.id,'IV POR PAGAR','IV POR PAGAR',45,null,'0'),
(new.id,'EXCENTO POR PAGAR','EXCENTO POR PAGAR',45,null,'0'),
(new.id,'OTRO TIPO DE IMPUESTO POR PAGAR','OTRO TIPO DE IMPUESTO POR PAGAR',45,null,'0'),
(new.id,'IVA POR PAGAR','IVA POR PAGAR',45,null,'0'),
(new.id,'OTRAS RETENCIONES POR PAGAR','OTRAS RETENCIONES POR PAGAR',46,null,'0'),
(new.id,'OTROS PASIVOS CORRIENTES','OTROS PASIVOS CORRIENTES',13,null,'0'),
(new.id,'PRÉSTAMOS A LARGO PLAZO','PRÉSTAMOS A LARGO PLAZO',14,null,'0'),
(new.id,'OTROS PASIVOS FIJOS','OTROS PASIVOS FIJOS',15,null,'0'),
(new.id,'CAPITAL SOCIAL','CAPITAL SOCIAL',16,null,'0'),
(new.id,'GANANCIAS ACUMULADAS','GANANCIAS ACUMULADAS',17,null,'0'),
(new.id,'AJUSTES INICIALES EN BANCOS','AJUSTES INICIALES EN BANCOS',18,null,'0'),
(new.id,'AJUSTES INICIALES EN INVENTARIOS','AJUSTES INICIALES EN INVENTARIOS',19,null,'0'),
(new.id,'VENTAS','VENTAS',20,null,'0'),
(new.id,'DEVOLUCIONES EN VENTAS','DEVOLUCIONES EN VENTAS',21,null,'0'),
(new.id,'INGRESOS FINANCIEROS','INGRESOS FINANCIEROS',22,null,'0'),
(new.id,'COSTOS DE INVENTARIO','COSTOS DE INVENTARIO',47,null,'0'),
(new.id,'AJUSTES DE INVENTARIO','AJUSTES DE INVENTARIO',48,null,'0'),
(new.id,'DESCUENTOS FINANCIEROS','DESCUENTOS FINANCIEROS',49,null,'0'),
(new.id,'DEVOLUCIONES EN COMPRAS DE INVENTARIO','DEVOLUCIONES EN COMPRAS DE INVENTARIO',50,null,'0'),
(new.id, 'COSTO DE MERCADERÍA VENDIDA', 'COSTO DE MERCADERÍA VENDIDA', 23, null, '0'),
(new.id,'COSTO DE LOS SERVICIOS VENDIDOS','COSTO DE LOS SERVICIOS VENDIDOS',24,null,'0'),
(new.id,'SALARIOS','SALARIOS',51,null,'0'),
(new.id,'COMISIONES, HONORARIOS Y SERVICIOS','COMISIONES, HONORARIOS Y SERVICIOS',26,null,'0'),
(new.id,'ARRENDAMIENTOS','ARRENDAMIENTOS',26,null,'0'),
(new.id,'SERVICIOS PÚBLICOS','SERVICIOS PÚBLICOS',26,null,'0'),
(new.id,'PAPELERÍA','PAPELERÍA',26,null,'0'),
(new.id,'SERVICIOS DE ASEO E INSTALACIONES','COSTO DE LOS SERVICIOS VENDIDOS',26,null,'0'),
(new.id,'PUBLICIDAD','PUBLICIDAD',26,null,'0'),
(new.id,'VIGILANCIA','VIGILANCIA',26,null,'0'),
(new.id,'SEGUROS Y PÓLIZAS','SEGUROS Y PÓLIZAS',26,null,'0'),
(new.id,'COMBUSTIBLES Y LUBRICANTES','COMBUSTIBLES Y LUBRICANTES',26,null,'0'),
(new.id,'OTROS GASTOS GENERALES','OTROS GASTOS GENERALES',26,null,'0'),
(new.id,'SALARIOS','SALARIOS',26,null,'0'),
(new.id,'DETERIORO DE CUENTAS POR COBRAR','DETERIORO DE CUENTAS POR COBRAR',27,null,'0'),
(new.id,'DEPRECIACIÓN DE PROPIEDAD, PLANTA Y EQUIPO','DEPRECIACIÓN DE PROPIEDAD, PLANTA Y EQUIPO',28,null,'0'),
(new.id,'GASTOS FINANCIEROS','GASTOS FINANCIEROS',29,null,'0'),
(new.id,'AJUSTE POR DIFERECIA EN CAMBIO','AJUSTE POR DIFERECIA EN CAMBIO',30,null,'0'),
(new.id,'AJUSTES POR APROXIMACIONES EN CÁLCULOS','AJUSTES POR APROXIMACIONES EN CÁLCULOS',31,null,'0'),
(new.id,'IMPUESTOS DE RENTA','IMPUESTOS DE RENTA',32,null,'0'),
(new.id,'GASTOS POR IMPUESTOS NO ACREDITABLES','GASTOS POR IMPUESTOS NO ACREDITABLES',33,null,'0'),
(new.id,'TRANSFERENCIAS BANCARIAS','TRANSFERENCIAS BANCARIAS',52,null,'0');



insert into class_products (name,symbol,id_company) values 
('Producto Primario','PPRI',new.id),
('Producto en Proceso','PPRO',new.id),
('Producto Terminado','PTER',new.id);

insert into families (name,id_company) values 
('Familia Principal',new.id);

insert into categories (name,id_company) values 
('Categoria Principal',new.id);

insert into zones (code,name,id_company) values 
(001,'Zona Principal',new.id);

insert into taxes (id_company,id_taxes_code,id_rate_code,rate,description,tax_net) values 
(new.id,1,1,0,'IVA 0%',0),
(new.id,1,2,1,'IVA 1%',1),
(new.id,1,3,2,'IVA 2%',2),
(new.id,1,4,4,'IVA 4%',4),
(new.id,1,8,13,'IVA 13%',13);
end#
delimiter ;


delimiter #
Create trigger insert_branch_offices
after insert on branch_offices
for each row
begin
insert into terminals (number,id_company,id_branch_office) values 
(1,new.id_company,new.id);
end#
delimiter ;

delimiter #
Create trigger insert_terminal
after insert on terminals
for each row
begin
insert into consecutives (id_terminal,id_branch_offices) values 
(new.id,new.id_branch_office);
end#
delimiter ;

delimiter #
Create trigger charge_list_with_product
after insert on price_lists
for each row
begin
DECLARE done INT DEFAULT FALSE;
  DECLARE product_id, price_list_id INT;
  DECLARE cur CURSOR FOR SELECT id FROM products;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
  
  OPEN cur;
  
  read_loop: LOOP
    FETCH cur INTO product_id;
    IF done THEN
      LEAVE read_loop;
    END IF;    
      INSERT INTO price_lists_lists (id_price_list,id_product,price) VALUES (new.id,product_id,0);   
  END LOOP;

  CLOSE cur;
end#
delimiter ;

delimiter #
Create trigger charge_product_in_list 
after insert on products
for each row
begin
DECLARE done INT DEFAULT FALSE;
  DECLARE product_id, price_list_id INT;
  DECLARE cur CURSOR FOR SELECT id FROM price_lists;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
  
  OPEN cur;
  
  read_loop: LOOP
    FETCH cur INTO price_list_id;
    IF done THEN
      LEAVE read_loop;
    END IF;    
      INSERT INTO price_lists_lists (id_price_list,id_product,price) VALUES (price_list_id,new.id,0);   
  END LOOP;
  CLOSE cur;
end#
delimiter ;

