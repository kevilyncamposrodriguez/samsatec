create view SP_Sales_details as
select
    case
        when `documents`.`type_doc` = 1 then 'Factura Electronica'
        when `documents`.`type_doc` = 2 then 'Nota de Debito'
        when `documents`.`type_doc` = 3 then 'Nota de Credito'
        when `documents`.`type_doc` = 4 then 'Tiquete Electronico'
        when `documents`.`type_doc` = 11 then 'Proforma'
        else 'Otro'
    end AS `Tipo de documento`,
    `documents`.`e_a` AS `Actividad_Economica`,
    `economic_activities`.`name_ea` AS `Nombre_Actividad`,
    `mh_categories`.`name` AS `Categoria_MH`,
    month(`documents`.`created_at`) AS `Mes`,
    date_format(`documents`.`created_at`, '%d/%m/%Y') AS `fecha_de_venta`,
    concat('\'', substr(`documents`.`key`, 22, 20)) AS `Consecutivo`,
    `clients`.`id_card` AS `Cedula`,
    `clients`.`name_client` AS `Cliente`,
    `products`.`description` AS `Producto`,
    `document_details`.`sku` AS `Unidad`,
    `document_details`.`qty_dispatch` AS `Cantidad`,
    `document_details`.`price_unid` AS `Precio`,
    case
        when `documents`.`type_doc` = 1 then `document_details`.`subtotal` * 1
        when `documents`.`type_doc` = 2 then `document_details`.`subtotal` * 1
        when `documents`.`type_doc` = 3 then `document_details`.`subtotal` * -1
        when `documents`.`type_doc` = 4 then `document_details`.`subtotal` * 1
        else `documents`.`total_net_sale` * 1
    end AS `Monto`,
    if(
        `documents`.`type_doc` = 3
        and json_extract(`document_details`.`taxes`, '$.rate') = '0',
        `document_details`.`subtotal` * -1,
        if(
            json_extract(`document_details`.`taxes`, '$.rate') = '0',
            `document_details`.`subtotal`,
            0
        )
    ) AS `Excento`,
    if(
        `documents`.`type_doc` = 3
        and json_extract(`document_details`.`taxes`, '$.rate') <> '0',
        `document_details`.`subtotal` * -1,
        if(
            json_extract(`document_details`.`taxes`, '$.rate') <> '0',
            `document_details`.`subtotal`,
            0
        )
    ) AS `Gravado`,
    case
        when json_extract(
            json_extract(`document_details`.`taxes`, '$.exoneration'),
            '$.AmountExoneration'
        ) is not null then if(
            `documents`.`type_doc` = 3
            and json_extract(`document_details`.`taxes`, '$.rate') <> '0',
            `document_details`.`subtotal` * -1,
            if(
                json_extract(`document_details`.`taxes`, '$.rate') <> '0',
                `document_details`.`subtotal`,
                0
            )
        )
        else 0
    end AS `Monto Exonerado`,
    `document_details`.`discounts` AS `Descuento`,
    case
        when `documents`.`type_doc` = 1 then `document_details`.`subtotal` * 1 - `document_details`.`discounts`
        when `documents`.`type_doc` = 2 then `document_details`.`subtotal` * 1 - `document_details`.`discounts`
        when `documents`.`type_doc` = 3 then `document_details`.`subtotal` * -1 - `document_details`.`discounts`
        when `documents`.`type_doc` = 4 then `document_details`.`subtotal` * 1 - `document_details`.`discounts`
        else `documents`.`total_net_sale` * 1 - `document_details`.`discounts`
    end AS `Subtotal`,
    case
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '0' then '0%'
        when `document_details`.`taxes` = '[]' then '0%'
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '1' then '1%'
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '2' then '2%'
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '4' then '4%'
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '8' then '8%'
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '13' then '13%'
        else 'Otro'
    end AS `Tarifa`,
    if(
        `documents`.`type_doc` = 3
        and truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '',
        `document_details`.`subtotal`,
        if(
            truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '',
            `document_details`.`subtotal`,
            if(
                `document_details`.`taxes` = '[]',
                `document_details`.`subtotal`,
                0
            )
        )
    ) AS `Impuesto 0%`,
    if(
        `documents`.`type_doc` = 3
        and truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '1',
        `document_details`.`subtotal` * -0.01,
        if(
            truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '1',
            `document_details`.`subtotal` * 0.01,
            0
        )
    ) AS `Impuesto 1%`,
    if(
        `documents`.`type_doc` = 3
        and truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '2',
        `document_details`.`subtotal` * -0.02,
        if(
            truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '2',
            `document_details`.`subtotal` * 0.02,
            0
        )
    ) AS `Impuesto 2%`,
    if(
        `documents`.`type_doc` = 3
        and truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '4',
        `document_details`.`subtotal` * -0.04,
        if(
            truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '4',
            `document_details`.`subtotal` * 0.04,
            0
        )
    ) AS `Impuesto 4%`,
    if(
        `documents`.`type_doc` = 3
        and truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '8',
        `document_details`.`subtotal` * -0.08,
        if(
            truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '8',
            `document_details`.`subtotal` * 0.08,
            0
        )
    ) AS `Impuesto 8%`,
    if(
        `documents`.`type_doc` = 3
        and truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '13',
        `document_details`.`subtotal` * -0.13,
        if(
            truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '13',
            `document_details`.`subtotal` * 0.13,
            0
        )
    ) AS `Impuesto 13%`,
    case
        when truncate(
            json_extract(
                json_extract(`document_details`.`taxes`, '$.exoneration'),
                '$.PercentageExoneration'
            ),
            0
        ) = '0' then '0%'
        when truncate(
            json_extract(
                json_extract(`document_details`.`taxes`, '$.exoneration'),
                '$.PercentageExoneration'
            ),
            0
        ) = '1' then '1%'
        when truncate(
            json_extract(
                json_extract(`document_details`.`taxes`, '$.exoneration'),
                '$.PercentageExoneration'
            ),
            0
        ) = '2' then '2%'
        when truncate(
            json_extract(
                json_extract(`document_details`.`taxes`, '$.exoneration'),
                '$.PercentageExoneration'
            ),
            0
        ) = '4' then '4%'
        when truncate(
            json_extract(
                json_extract(`document_details`.`taxes`, '$.exoneration'),
                '$.PercentageExoneration'
            ),
            0
        ) = '8' then '8%'
        when truncate(
            json_extract(
                json_extract(`document_details`.`taxes`, '$.exoneration'),
                '$.PercentageExoneration'
            ),
            0
        ) = '13' then '13%'
        else '0'
    end AS `Tarifa Exonerada`,
    case
        when json_extract(
            json_extract(`document_details`.`taxes`, '$.exoneration'),
            '$.AmountExoneration'
        ) is not null then if(
            `documents`.`type_doc` = 3
            and json_extract(`document_details`.`taxes`, '$.rate') <> '0',
            json_extract(
                json_extract(`document_details`.`taxes`, '$.exoneration'),
                '$.AmountExoneration'
            ) * -1,
            json_extract(
                json_extract(`document_details`.`taxes`, '$.exoneration'),
                '$.AmountExoneration'
            )
        )
        else 0
    end AS `Impuesto exonerado`,
    case
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '0' then if(
            `documents`.`type_doc` = 3
            and truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '0',
            `document_details`.`subtotal` * 0,
            if(
                truncate(
                    json_extract(
                        `document_details`.`taxes`,
                        '$.rate'
                    ),
                    0
                ) = '0',
                `document_details`.`subtotal` * 0,
                0
            )
        )
        when `document_details`.`taxes` = '[]' then `document_details`.`subtotal` * 0
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '1' then if(
            `documents`.`type_doc` = 3
            and truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '1',
            `document_details`.`subtotal` * -0.01,
            if(
                truncate(
                    json_extract(
                        `document_details`.`taxes`,
                        '$.rate'
                    ),
                    0
                ) = '1',
                `document_details`.`subtotal` * 0.01,
                0
            )
        )
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '2' then if(
            `documents`.`type_doc` = 3
            and truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '2',
            `document_details`.`subtotal` * -0.02,
            if(
                truncate(
                    json_extract(
                        `document_details`.`taxes`,
                        '$.rate'
                    ),
                    0
                ) = '2',
                `document_details`.`subtotal` * 0.02,
                0
            )
        )
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '4' then if(
            `documents`.`type_doc` = 3
            and truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '4',
            `document_details`.`subtotal` * -0.04,
            if(
                truncate(
                    json_extract(
                        `document_details`.`taxes`,
                        '$.rate'
                    ),
                    0
                ) = '4',
                `document_details`.`subtotal` * 0.04,
                0
            )
        )
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '8' then if(
            `documents`.`type_doc` = 3
            and truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '8',
            `document_details`.`subtotal` * -0.08,
            if(
                truncate(
                    json_extract(
                        `document_details`.`taxes`,
                        '$.rate'
                    ),
                    0
                ) = '8',
                `document_details`.`subtotal` * 0.08,
                0
            )
        )
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '13' then if(
            `documents`.`type_doc` = 3
            and truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '13',
            `document_details`.`subtotal` * -0.13,
            if(
                truncate(
                    json_extract(
                        `document_details`.`taxes`,
                        '$.rate'
                    ),
                    0
                ) = '13',
                `document_details`.`subtotal` * 0.13,
                0
            )
        )
        else 0
    end AS `total_impuesto`,
    case
        when `documents`.`type_doc` = 1 then `document_details`.`total_amount_line` * 1
        when `documents`.`type_doc` = 2 then `document_details`.`total_amount_line` * 1
        when `documents`.`type_doc` = 3 then `document_details`.`total_amount_line` * -1
        when `documents`.`type_doc` = 4 then `document_details`.`total_amount_line` * 1
        else `document_details`.`total_amount` * 1
    end AS `total_venta`,
    `documents`.`currency` AS `Moneda`,
    `documents`.`exchange_rate` AS `Tipo de cambio`,
    `counts`.`description` AS `Cuenta Contable`,
    `branch_offices`.`name_branch_office` AS `Sucursal`,
    `terminals`.`number` AS `Terminal`,
    `sellers`.`name` AS `Vendedor`
from
    (
        (
            (
                (
                    (
                        (
                            (
                                (
                                    (
                                        (
                                            (
                                                `document_details`
                                                join `documents` on (
                                                    `documents`.`id` = `document_details`.`id_document`
                                                )
                                            )
                                            join `clients` on (
                                                `documents`.`id_client` = `clients`.`id`
                                            )
                                        )
                                        join `sale_conditions` on (
                                            `documents`.`sale_condition` = `sale_conditions`.`code`
                                        )
                                    )
                                    join `products` on (
                                        `document_details`.`id_product` = `products`.`id`
                                    )
                                )
                                join `economic_activities` on (
                                    `documents`.`e_a` = `economic_activities`.`number`
                                )
                            )
                            join `mh_categories` on (
                                `documents`.`id_mh_categories` = `mh_categories`.`id`
                            )
                        )
                        left join `counts` on (
                            `document_details`.`id_count` = `counts`.`id`
                        )
                    )
                    join `branch_offices` on (
                        `documents`.`id_branch_office` = `branch_offices`.`id`
                    )
                )
                join `terminals` on (
                    `documents`.`id_terminal` = `terminals`.`id`
                )
            )
            left join `sellers` on (
                `documents`.`id_seller` = `sellers`.`id`
            )
        )
        left join `document_references` on (
            `document_references`.`id_document` = `documents`.`id`
        )
    )
where
    (
        `documents`.`answer_mh` = 'aceptado'
        or `documents`.`answer_mh` = 'ninguna'
    )
    and `documents`.`type_doc` <> '00'
    and `documents`.`type_doc` <> '99'
    and `documents`.`type_doc` <> '11';

create view c_x_c_details as
select
    `documents`.`id_company` AS `Compania`,
    `clients`.`name_client` AS `cliente`,
    `clients`.`id` AS `idCliente`,
    count(
        if(
            `documents`.`balance` <> 0
            and timestampdiff(
                DAY,
                `documents`.`created_at`,
                current_timestamp()
            ) - `clients`.`time` < 1,
            `documents`.`balance`,
            NULL
        )
    ) AS `cantAlDia`,
    sum(
        if(
            `documents`.`balance` <> 0
            and timestampdiff(
                DAY,
                `documents`.`created_at`,
                current_timestamp()
            ) - `clients`.`time` < 1,
            `documents`.`balance`,
            0
        )
    ) AS `montoAlDia`,
    count(
        if(
            `documents`.`balance` <> 0
            and timestampdiff(
                DAY,
                `documents`.`created_at`,
                current_timestamp()
            ) - `clients`.`time` >= 1,
            `documents`.`balance`,
            NULL
        )
    ) AS `cantAtrasadas`,
    sum(
        if(
            `documents`.`balance` <> 0
            and timestampdiff(
                DAY,
                `documents`.`created_at`,
                current_timestamp()
            ) - `clients`.`time` >= 1,
            `documents`.`balance`,
            0
        )
    ) AS `montoAtrasadas`,
    count(
        if(
            `documents`.`balance` <> 0,
            `documents`.`balance`,
            NULL
        )
    ) AS `cantTotal`,
    sum(
        if(
            `documents`.`balance` <> 0
            and timestampdiff(
                DAY,
                `documents`.`created_at`,
                current_timestamp()
            ) - `clients`.`time` < 1,
            `documents`.`balance`,
            0
        ) + if(
            `documents`.`balance` <> 0
            and timestampdiff(
                DAY,
                `documents`.`created_at`,
                current_timestamp()
            ) - `clients`.`time` >= 1,
            `documents`.`balance`,
            0
        )
    ) AS `montoTotal`
from
    (
        `documents`
        join `clients` on (`clients`.`id` = `documents`.`id_client`)
    )
where
    (
        `documents`.`answer_mh` = 'aceptado'
        or `documents`.`answer_mh` = 'ninguna'
    )
    and `documents`.`type_doc` <> '00'
    and `documents`.`type_doc` <> '03'
    and `documents`.`balance` <> ''
    and `documents`.`type_doc` <> '99'
    and `documents`.`balance` > 0
group by
    `clients`.`name_client`,
    `documents`.`id_company`,
    `clients`.`id`;

create view c_x_c_s as
select
    `documents`.`id` AS `id`,
    `documents`.`id_company` AS `company`,
    `clients`.`id` AS `idCliente`,
    `clients`.`name_client` AS `cliente`,
    `documents`.`consecutive` AS `consecutivo`,
    `documents`.`created_at` AS `fecha_de_venta`,
    `documents`.`created_at` + interval `clients`.`time` day AS `fecha_de_vencimiento`,
    to_days(curdate()) - to_days(
        `documents`.`created_at` + interval `clients`.`time` day
    ) AS `dias_vencidos`,
    `clients`.`time` AS `dias_de_credito`,
    round(`documents`.`balance`, 2) AS `saldo_pendiente`,
    round(`documents`.`total_document`, 2) AS `monto_total`,
    case
        when `documents`.`type_doc` = 1 then `documents`.`total_net_sale` * 1
        when `documents`.`type_doc` = 2 then `documents`.`total_net_sale` * 1
        when `documents`.`type_doc` = 3 then `documents`.`total_net_sale` * -1
        when `documents`.`type_doc` = 4 then `documents`.`total_net_sale` * 1
        else `documents`.`total_net_sale` * 1
    end AS `monto`,
    case
        when `documents`.`type_doc` = 1 then `documents`.`total_tax` * 1
        when `documents`.`type_doc` = 2 then `documents`.`total_tax` * 1
        when `documents`.`type_doc` = 3 then `documents`.`total_tax` * -1
        when `documents`.`type_doc` = 4 then `documents`.`total_tax` * 1
        else `documents`.`total_tax` * 1
    end AS `impuesto`,
    case
        when timestampdiff(
            DAY,
            `documents`.`created_at`,
            current_timestamp()
        ) - `clients`.`time` < 1 then 'Al Dia'
        when timestampdiff(
            DAY,
            `documents`.`created_at`,
            current_timestamp()
        ) - `clients`.`time` > 0
        and timestampdiff(
            DAY,
            `documents`.`created_at`,
            current_timestamp()
        ) - `clients`.`time` < 16 then '0 a 15 dias de atraso'
        when timestampdiff(
            DAY,
            `documents`.`created_at`,
            current_timestamp()
        ) - `clients`.`time` > 15
        and timestampdiff(
            DAY,
            `documents`.`created_at`,
            current_timestamp()
        ) - `clients`.`time` < 31 then '15 a 30 dias de atraso'
        when timestampdiff(
            DAY,
            `documents`.`created_at`,
            current_timestamp()
        ) - `clients`.`time` > 30
        and timestampdiff(
            DAY,
            `documents`.`created_at`,
            current_timestamp()
        ) - `clients`.`time` < 61 then '30 a 60 dias de atraso'
        when timestampdiff(
            DAY,
            `documents`.`created_at`,
            current_timestamp()
        ) - `clients`.`time` > 60
        and timestampdiff(
            DAY,
            `documents`.`created_at`,
            current_timestamp()
        ) - `clients`.`time` < 91 then '60 a 90 dias de atraso'
        else 'Mas de 90 dias de atraso'
    end AS `dias_de_atraso`,
    case
        when `documents`.`type_doc` = 1 then 'Factura Electronica'
        when `documents`.`type_doc` = 2 then 'Nota Debito'
        when `documents`.`type_doc` = 3 then 'Nota Credito'
        when `documents`.`type_doc` = 4 then 'Tiquete Electronico'
        when `documents`.`type_doc` = 11 then 'Factura Control Interno'
        else 'Factura Control Interno'
    end AS `tipo_documento`
from
    (
        (
            `documents`
            join `clients` on (
                `clients`.`id` = `documents`.`id_client`
            )
        )
        join `sale_conditions` on (
            `sale_conditions`.`id` = `documents`.`sale_condition`
        )
    )
where
    (
        `documents`.`answer_mh` = 'aceptado'
        or `documents`.`answer_mh` = 'ninguna'
    )
    and `documents`.`type_doc` <> '00'
    and `documents`.`type_doc` <> '03'
    and `documents`.`balance` <> ''
    and `documents`.`type_doc` <> '99'
    and `documents`.`balance` > 1;

create view c_x_p_details as
select
    `expenses`.`id_company` AS `company`,
    `providers`.`name_provider` AS `provider`,
    `providers`.`id` AS `idProvider`,
    count(
        if(
            `expenses`.`pending_amount` > 1
            and timestampdiff(
                DAY,
                `expenses`.`created_at`,
                current_timestamp()
            ) - `providers`.`time` < 1,
            `expenses`.`pending_amount`,
            NULL
        )
    ) AS `cantAlDia`,
    sum(
        if(
            `expenses`.`pending_amount` > 1
            and timestampdiff(
                DAY,
                `expenses`.`created_at`,
                current_timestamp()
            ) - `providers`.`time` < 1,
            `expenses`.`pending_amount`,
            0
        )
    ) AS `montoAlDia`,
    count(
        if(
            `expenses`.`pending_amount` > 1
            and timestampdiff(
                DAY,
                `expenses`.`created_at`,
                current_timestamp()
            ) - `providers`.`time` >= 1,
            `expenses`.`pending_amount`,
            NULL
        )
    ) AS `cantAtrasadas`,
    sum(
        if(
            `expenses`.`pending_amount` > 1
            and timestampdiff(
                DAY,
                `expenses`.`created_at`,
                current_timestamp()
            ) - `providers`.`time` >= 1,
            `expenses`.`pending_amount`,
            0
        )
    ) AS `montoAtrasadas`,
    count(
        if(
            `expenses`.`pending_amount` > 1,
            `expenses`.`pending_amount`,
            NULL
        )
    ) AS `cantTotal`,
    sum(
        if(
            `expenses`.`pending_amount` > 1
            and timestampdiff(
                DAY,
                `expenses`.`created_at`,
                current_timestamp()
            ) - `providers`.`time` < 1,
            `expenses`.`pending_amount`,
            0
        ) + if(
            `expenses`.`pending_amount` > 1
            and timestampdiff(
                DAY,
                `expenses`.`created_at`,
                current_timestamp()
            ) - `providers`.`time` >= 1,
            `expenses`.`pending_amount`,
            0
        )
    ) AS `montoTotal`
from
    (
        `expenses`
        left join `providers` on (`providers`.`id` = `expenses`.`id_provider`)
    )
where
    (
        `expenses`.`condition` = 'aceptado'
        or `expenses`.`condition` = 'Ninguna'
    )
    and `expenses`.`pending_amount` > 1
group by
    `providers`.`name_provider`,
    `expenses`.`id_company`,
    `providers`.`id`;

create view c_x_p_s as
select
    `expenses`.`id` AS `id`,
    `expenses`.`id_company` AS `company`,
    `providers`.`id` AS `idProviders`,
    `providers`.`name_provider` AS `provider`,
    `expenses`.`key` AS `key`,
    substr(`expenses`.`key`, 22, 20) AS `consecutivo`,
    `expenses`.`date_issue` AS `fecha_de_venta`,
    `expenses`.`date_issue` + interval `providers`.`time` day AS `fecha_de_vencimiento`,
    to_days(curdate()) - to_days(
        `expenses`.`date_issue` + interval `providers`.`time` day
    ) AS `dias_vencidos`,
    `providers`.`time` AS `dias_de_credito`,
    round(`expenses`.`pending_amount`, 2) AS `saldo_pendiente`,
    round(`expenses`.`total_document`, 2) AS `monto_total`,
    case
        when substr(`expenses`.`key`, 31, 1) = 1 then `expenses`.`total_document` * 1
        when substr(`expenses`.`key`, 31, 1) = 2 then `expenses`.`total_document` * 1
        when substr(`expenses`.`key`, 31, 1) = 3 then `expenses`.`total_document` * -1
        when substr(`expenses`.`key`, 31, 1) = 4 then `expenses`.`total_document` * 1
        else substr(`expenses`.`key`, 31, 1) * 1
    end AS `monto`,
    case
        when substr(`expenses`.`key`, 31, 1) = 1 then `expenses`.`total_tax` * 1
        when substr(`expenses`.`key`, 31, 1) = 2 then `expenses`.`total_tax` * 1
        when substr(`expenses`.`key`, 31, 1) = 3 then `expenses`.`total_tax` * -1
        when substr(`expenses`.`key`, 31, 1) = 4 then `expenses`.`total_tax` * 1
        else substr(`expenses`.`key`, 3, 1) * 1
    end AS `impuesto`,
    case
        when timestampdiff(
            DAY,
            `expenses`.`created_at`,
            current_timestamp()
        ) - `providers`.`time` < 1 then 'Al Dia'
        when timestampdiff(
            DAY,
            `expenses`.`created_at`,
            current_timestamp()
        ) - `providers`.`time` > 0
        and timestampdiff(
            DAY,
            `expenses`.`created_at`,
            current_timestamp()
        ) - `providers`.`time` < 16 then '0 a 15 dias de atraso'
        when timestampdiff(
            DAY,
            `expenses`.`created_at`,
            current_timestamp()
        ) - `providers`.`time` > 15
        and timestampdiff(
            DAY,
            `expenses`.`created_at`,
            current_timestamp()
        ) - `providers`.`time` < 31 then '15 a 30 dias de atraso'
        when timestampdiff(
            DAY,
            `expenses`.`created_at`,
            current_timestamp()
        ) - `providers`.`time` > 30
        and timestampdiff(
            DAY,
            `expenses`.`created_at`,
            current_timestamp()
        ) - `providers`.`time` < 61 then '30 a 60 dias de atraso'
        when timestampdiff(
            DAY,
            `expenses`.`created_at`,
            current_timestamp()
        ) - `providers`.`time` > 60
        and timestampdiff(
            DAY,
            `expenses`.`created_at`,
            current_timestamp()
        ) - `providers`.`time` < 91 then '60 a 90 dias de atraso'
        else 'Mas de 90 dias de atraso'
    end AS `dias_de_atraso`,
    case
        when substr(`expenses`.`key`, 31, 1) = 1 then 'Factura Electronica'
        when substr(`expenses`.`key`, 31, 1) = 2 then 'Nota Debito'
        when substr(`expenses`.`key`, 31, 1) = 3 then 'Nota Credito'
        when substr(`expenses`.`key`, 31, 1) = 4 then 'Ticket Electronico'
        when substr(`expenses`.`key`, 31, 1) = 8 then 'Factura Electronica de Compra'
        else 'Otro'
    end AS `tipo_documento`
from
    (
        `expenses`
        left join `providers` on (`providers`.`id` = `expenses`.`id_provider`)
    )
where
    (
        `expenses`.`condition` = 'aceptado'
        or `expenses`.`condition` = 'ninguna'
    )
    and `expenses`.`pending_amount` > 1
    and substr(`expenses`.`consecutive`, 1, 2) <> 'OC';

create view sp_cxc as
select
    `clients`.`name_client` AS `Cliente`,
    concat('\'', `documents`.`consecutive`) AS `Consecutivo`,
    date_format(`documents`.`created_at`, '%d/%m/%Y') AS `fecha_de_venta`,
    date_format(
        `documents`.`created_at` + interval `clients`.`time` day,
        '%d/%m/%Y'
    ) AS `Fecha_de_vencimiento`,
    to_days(curdate()) - to_days(
        `documents`.`created_at` + interval `clients`.`time` day
    ) AS `Dias Vencidos`,
    `clients`.`time` AS `Dias_de_credito`,
    `documents`.`balance` AS `Saldo_Pendiente`,
    `documents`.`total_document` AS `Monto_total`,
    case
        when `documents`.`type_doc` = 1 then `documents`.`total_net_sale` * 1
        when `documents`.`type_doc` = 2 then `documents`.`total_net_sale` * 1
        when `documents`.`type_doc` = 3 then `documents`.`total_net_sale` * -1
        when `documents`.`type_doc` = 4 then `documents`.`total_net_sale` * 1
        else `documents`.`total_net_sale` * 1
    end AS `Monto`,
    case
        when `documents`.`type_doc` = 1 then `documents`.`total_tax` * 1
        when `documents`.`type_doc` = 2 then `documents`.`total_tax` * 1
        when `documents`.`type_doc` = 3 then `documents`.`total_tax` * -1
        when `documents`.`type_doc` = 4 then `documents`.`total_tax` * 1
        else `documents`.`total_tax` * 1
    end AS `Impuesto`,
    case
        when timestampdiff(
            DAY,
            `documents`.`created_at`,
            current_timestamp()
        ) - `clients`.`time` < 1 then ' Al Dia'
        when timestampdiff(
            DAY,
            `documents`.`created_at`,
            current_timestamp()
        ) - `clients`.`time` > 0
        and timestampdiff(
            DAY,
            `documents`.`created_at`,
            current_timestamp()
        ) - `clients`.`time` < 16 then '0 a 15 dias de atraso'
        when timestampdiff(
            DAY,
            `documents`.`created_at`,
            current_timestamp()
        ) - `clients`.`time` > 15
        and timestampdiff(
            DAY,
            `documents`.`created_at`,
            current_timestamp()
        ) - `clients`.`time` < 31 then '15 a 30 dias de atraso'
        when timestampdiff(
            DAY,
            `documents`.`created_at`,
            current_timestamp()
        ) - `clients`.`time` > 30
        and timestampdiff(
            DAY,
            `documents`.`created_at`,
            current_timestamp()
        ) - `clients`.`time` < 61 then '30 a 60 dias de atraso'
        when timestampdiff(
            DAY,
            `documents`.`created_at`,
            current_timestamp()
        ) - `clients`.`time` > 60
        and timestampdiff(
            DAY,
            `documents`.`created_at`,
            current_timestamp()
        ) - `clients`.`time` < 91 then '60 a 90 dias de atraso'
        else 'Más de 90 dias de atraso'
    end AS `Dias_de_Atraso`,
    case
        when `documents`.`type_doc` = 1 then 'Factura Electronica'
        when `documents`.`type_doc` = 2 then 'Nota Debito'
        when `documents`.`type_doc` = 3 then 'Nota Credito'
        when `documents`.`type_doc` = 4 then 'Factura Control Interno'
        when `documents`.`type_doc` = 11 then 'Factura Interna'
        else 'OTRO'
    end AS `Tipo_Documento`
from
    (
        (
            `documents`
            join `clients` on (
                `clients`.`id` = `documents`.`id_client`
            )
        )
        join `sale_conditions` on (
            `sale_conditions`.`id` = `documents`.`sale_condition`
        )
    )
where
    (
        `documents`.`answer_mh` = 'aceptado'
        or `documents`.`answer_mh` = 'ninguna'
    )
    and `documents`.`type_doc` <> '00'
    and `documents`.`type_doc` <> '99'
    and `documents`.`balance` <> 0;

create view view_cuMonth_sales as
select
    sum(
        if(
            `documents`.`type_doc` <> '03'
            and `documents`.`type_doc` <> '11'
            and `documents`.`answer_mh` = 'aceptado'
            or `documents`.`answer_mh` = 'ninguna',
            `documents`.`total_document`,
            0
        ) + if(
            `documents`.`type_doc` = '03'
            and `documents`.`type_doc` <> '11'
            and `documents`.`answer_mh` = 'aceptado'
            or `documents`.`answer_mh` = 'ninguna',
            `documents`.`total_document` * -1,
            0
        )
    ) AS `Total Ventas Registradas`,
    sum(
        if(
            `documents`.`type_doc` <> '03'
            and `documents`.`type_doc` = '11',
            `documents`.`total_document`,
            0
        ) + if(
            `documents`.`type_doc` = '03'
            and `documents`.`type_doc` = '11',
            `documents`.`total_document` * -1,
            0
        )
    ) AS `Total Ventas No Registradas`
from
    `documents`
where
    month(`documents`.`created_at`) = month(curdate())
    and year(`documents`.`created_at`) = year(curdate())
    and `documents`.`type_doc` <> '00'
    and `documents`.`type_doc` <> '99';

create view view_curmonth_exps as
select `expenses`.`id_company`            AS `COMPANIA`,
       year(`expenses`.`created_at`)      AS `ANO`,
       monthname(`expenses`.`created_at`) AS `MES`,
       sum(case
               when `expense_details`.`id_product` is not null then case
                                                                                                   when `expenses`.`type_doc` = 3
                                                                                                       then `expense_details`.`subtotal` * -1
                                                                                                   else `expense_details`.`subtotal` end
               else 0 end)                                           AS `COMPRAS`,
       sum(case
               when `expenses`.`type_doc` <> 11 and
                    `expense_details`.`id_product` is not null then case
                                                                                                   when `expenses`.`type_doc` = 3
                                                                                                       then `expense_details`.`subtotal` * -1
                                                                                                   else `expense_details`.`subtotal` end
               else 0 end)                                           AS `COMPRAS_TRIBUTADAS`,
       sum(case
               when `expenses`.`type_doc` = 11 and
                    `expense_details`.`id_product` is not null
                   then `expense_details`.`subtotal`
               else 0 end)                                           AS `COMPRAS_NO_TRIBUTADAS`,
       sum(case
               when `expense_details`.`id_product` is null then case
                                                                                               when `expenses`.`type_doc` = 3
                                                                                                   then `expense_details`.`subtotal` * -1
                                                                                               else `expense_details`.`subtotal` end
               else 0 end)                                           AS `GASTOS`,
       sum(case
               when `expenses`.`type_doc` <> 11 and
                    `expense_details`.`id_product` is null then case
                                                                                               when `expenses`.`type_doc` = 3
                                                                                                   then `expense_details`.`subtotal` * -1
                                                                                               else `expense_details`.`subtotal` end
               else 0 end)                                           AS `GASTOS_TRIBUTADOS`,
       sum(case
               when `expenses`.`type_doc` = 11 and
                    `expense_details`.`id_product` is null
                   then `expense_details`.`subtotal`
               else 0 end)                                           AS `GASTOS_NO_TRIBUTADOS`
from (`expenses` join `expense_details`
      on (`expenses`.`id` = `expense_details`.`id_expense`))
where month(`expenses`.`created_at`) = month(curdate())
  and year(`expenses`.`created_at`) = year(curdate())
  and (`expenses`.`state` = 'aceptado' or
       `expenses`.`state` = 'Ninguno')
  and (`expenses`.`condition` = 'aceptado' or
       `expenses`.`condition` = 'guardado' or
       `expenses`.`condition` = 'Ninguna')
  and `expenses`.`type_doc` <> '00'
group by `expenses`.`id_company`;



create view view_curYear_monthly_expenses as
select
    monthname(`expenses`.`created_at`) AS `MES`,
    `expenses`.`id_branch_office` AS `idSucursal`,
    `branch_offices`.`name_branch_office` AS `nombreSucursal`,
    sum(
        if(
            `expenses`.`sale_condition` <> '03'
            and `expenses`.`condition` = 'Aceptado'
            or `expenses`.`condition` = 'Guardado'
            and `count_types`.`name` = 'COSTO',
            `expense_details`.`subtotal`,
            0
        ) + if(
            `expenses`.`sale_condition` = '03'
            and `expenses`.`condition` = 'Aceptado'
            or `expenses`.`condition` = 'Guardado'
            and `count_types`.`name` = 'COSTO',
            `expense_details`.`subtotal` * -1,
            0
        )
    ) AS `Total Costos Registrados`,
    sum(
        if(
            `expenses`.`sale_condition` <> '03'
            and `expenses`.`condition` = 'Ninguna'
            and `count_types`.`name` = 'COSTO',
            `expense_details`.`subtotal`,
            0
        ) + if(
            `expenses`.`sale_condition` = '03'
            and `expenses`.`condition` = 'Ninguna'
            and `count_types`.`name` = 'COSTO',
            `expense_details`.`subtotal` * -1,
            0
        )
    ) AS `Total Costos no reportados`,
    sum(
        if(
            `expenses`.`sale_condition` <> '03'
            and `expenses`.`condition` = 'Aceptado'
            or `expenses`.`condition` = 'Guardado'
            and `count_types`.`name` = 'GASTO',
            `expense_details`.`subtotal`,
            0
        ) + if(
            `expenses`.`sale_condition` = '03'
            and `expenses`.`condition` = 'Aceptado'
            or `expenses`.`condition` = 'Guardado'
            and `count_types`.`name` = 'GASTO',
            `expense_details`.`subtotal` * -1,
            0
        )
    ) AS `Total gastos Registrados`,
    sum(
        if(
            `expenses`.`sale_condition` <> '03'
            and `expenses`.`condition` = 'Ninguna'
            and `count_types`.`name` = 'GASTO',
            `expense_details`.`subtotal`,
            0
        ) + if(
            `expenses`.`sale_condition` = '03'
            and `expenses`.`condition` = 'Ninguna'
            and `count_types`.`name` = 'GASTO',
            `expense_details`.`subtotal` * -1,
            0
        )
    ) AS `Total gastos no reportados`
from
    (
        (
            (
                (
                    (
                        `expenses`
                        join `expense_details` on (
                            `expense_details`.`id_expense` = `expenses`.`id`
                        )
                    )
                    join `counts` on (
                        `counts`.`id` = `expense_details`.`id_count`
                    )
                )
                join `count_categories` on (
                    `count_categories`.`id` = `counts`.`id_count_primary`
                )
            )
            join `count_types` on (
                `count_types`.`id` = `count_categories`.`id_count_type`
            )
        )
        join `branch_offices` on (
            `expenses`.`id_branch_office` = `branch_offices`.`id`
        )
    )
where
    year(`expenses`.`created_at`) = year(curdate())
    and `expenses`.`condition` <> 'rechazado'
    and substr(`expenses`.`consecutive`, 1, 2) <> 'OC'
group by
    monthname(`expenses`.`created_at`);

create view view_curYear_monthly_sales as
select
    month(`documents`.`created_at`) AS `MES`,
    sum(
        if(
            `documents`.`type_doc` <> '03'
            and `documents`.`type_doc` <> '11'
            and `documents`.`answer_mh` = 'aceptado'
            or `documents`.`answer_mh` = 'ninguna',
            `documents`.`total_document`,
            0
        ) + if(
            `documents`.`type_doc` = '03'
            and `documents`.`type_doc` <> '11'
            and `documents`.`answer_mh` = 'aceptado'
            or `documents`.`answer_mh` = 'ninguna',
            `documents`.`total_document` * -1,
            0
        )
    ) AS `Total Ventas Registradas`,
    sum(
        if(
            `documents`.`type_doc` <> '03'
            and `documents`.`type_doc` = '11',
            `documents`.`total_document`,
            0
        ) + if(
            `documents`.`type_doc` = '03'
            and `documents`.`type_doc` = '11',
            `documents`.`total_document` * -1,
            0
        )
    ) AS `Total Ventas No Registradas`
from
    `documents`
where
    year(`documents`.`created_at`) = year(curdate())
    and (
        `documents`.`answer_mh` = 'aceptado'
        or `documents`.`answer_mh` = 'ninguna'
    )
    and `documents`.`type_doc` <> '00'
    and `documents`.`type_doc` <> '99'
group by
    monthname(`documents`.`created_at`);

create view view_curmonth_sales as
select `documents`.`id_company`                       AS `COMPANIA`,
       year(`documents`.`created_at`)                 AS `ANO`,
       monthname(`documents`.`created_at`)            AS `MES`,
       sum(case
               when `documents`.`type_doc` = 3
                   then `documents`.`total_document` * -1
               else `documents`.`total_document` end) AS `VENTAS`,
       sum(case
               when `documents`.`type_doc` <> 11 then case
                                                                                     when `documents`.`type_doc` = 3
                                                                                         then `documents`.`total_document` * -1
                                                                                     else `documents`.`total_document` end
               else 0 end)                                                       AS `TRIBUTADAS`,
       sum(case
               when `documents`.`type_doc` = 11
                   then `documents`.`total_document`
               else 0 end)                                                       AS `INTERNAS`
from `documents`
where month(`documents`.`created_at`) = month(curdate())
  and year(`documents`.`created_at`) = year(curdate())
  and (`documents`.`answer_mh` = 'aceptado' or
       `documents`.`answer_mh` = 'Ninguna')
  and `documents`.`type_doc` <> '00'
  and `documents`.`type_doc` <> '99'
group by `documents`.`id_company`;




create view view_expenses_details as
select `expenses`.`id_branch_office`                                AS `idSucursal`,
       `branch_offices`.`name_branch_office`                        AS `nombreSucursal`,
       `expenses`.`id_company`                                      AS `Compania`,
       case
           when substr(`expenses`.`key`, 30, 2) = 1 then 'Factura Electronica'
           when substr(`expenses`.`key`, 30, 2) = 2 then 'Nota Debito'
           when substr(`expenses`.`key`, 30, 2) = 3 then 'Nota Credito'
           when substr(`expenses`.`key`, 30, 2) = 4 then 'Tiquete Electronico'
           when substr(`expenses`.`key`, 30, 2) = 8 then 'Compra Electronica'
           else 'OTRO' end                                                                     AS `Tipo_Documento`,
       `expense_details`.`type`                                     AS `type`,
       `expenses`.`e_a`                                             AS `Actividad_Economica`,
       `economic_activities`.`name_ea`                              AS `Nombre_Actividad_Economica`,
       `mh_categories`.`name`                                       AS `Categoria_MH`,
       month(`expenses`.`created_at`)                               AS `Mes`,
       year(`expenses`.`created_at`)                                AS `ano`,
       `expenses`.`date_issue`                                      AS `Fecha`,
       `expenses`.`consecutive_real`                                AS `Consecutivo`,
       `providers`.`id_card`                                        AS `Cedula`,
       `providers`.`name_provider`                                  AS `Proveedor`,
       `expense_details`.`id_product`                               AS `Id_producto`,
       `expense_details`.`detail`                                   AS `Producto`,
       `expense_details`.`sku`                                      AS `Unidad`,
       `expense_details`.`qty`                                      AS `Cantidad`,
       `expense_details`.`price`                                    AS `Costo`,
       `expense_details`.`subtotal`                                 AS `Monto`,
       if(substr(`expenses`.`key`, 30, 2) = 3 and
          truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '0',
          `expense_details`.`subtotal` * -1,
          if(truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '0',
             `expense_details`.`subtotal`, if(
                             substr(`expenses`.`key`, 30, 2) = 3 and
                             `expense_details`.`taxes` = '[]',
                             `expense_details`.`subtotal` * -1,
                             if(`expense_details`.`taxes` = '[]',
                                `expense_details`.`subtotal`, 0)))) AS `Excento`,
       if(substr(`expenses`.`key`, 30, 2) = 3 and
          truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) <> '0',
          `expense_details`.`subtotal` * -1,
          if(truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) <> '0',
             `expense_details`.`subtotal`, 0))                      AS `Gravado`,
       `expense_details`.`discounts`                                AS `discounts`,
       case
           when substr(`expenses`.`key`, 30, 2) = 1
               then `expense_details`.`subtotal` * 1
           when substr(`expenses`.`key`, 30, 2) = 2
               then `expense_details`.`subtotal` * 1
           when substr(`expenses`.`key`, 30, 2) = 3
               then `expense_details`.`subtotal` * -1
           when `expenses`.`sale_condition` = 4
               then `expense_details`.`subtotal` * 1
           else `expense_details`.`subtotal` * 1 end                AS `subtotal`,
       case
           when truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '0'
               then '0%'
           when `expense_details`.`taxes` = '[]' then '0%'
           when truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '1'
               then '1%'
           when truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '2'
               then '2%'
           when truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '4'
               then '4%'
           when truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '8'
               then '8%'
           when truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '13'
               then '13%'
           else 'Otro' end                                                                     AS `Tarifa`,
       if(substr(`expenses`.`key`, 30, 2) = 3 and
          truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '0',
          `expense_details`.`subtotal` * -1,
          if(truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '0',
             `expense_details`.`subtotal`, 0))                      AS `Impuesto_0`,
       if(substr(`expenses`.`key`, 30, 2) = 3 and
          truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '0',
          `expense_details`.`subtotal` * -1,
          if(truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '0',
             `expense_details`.`subtotal`, 0))                      AS `Monto_0`,
       if(substr(`expenses`.`key`, 30, 2) = 3 and
          truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '1',
          `expense_details`.`subtotal` * -0.01,
          if(truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '1',
             `expense_details`.`subtotal` * 0.01, 0))               AS `Impuesto_1`,
       if(substr(`expenses`.`key`, 30, 2) = 3 and
          truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '1',
          `expense_details`.`subtotal` * -1,
          if(truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '1',
             `expense_details`.`subtotal`, 0))                      AS `Monto_1`,
       if(substr(`expenses`.`key`, 30, 2) = 3 and
          truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '2',
          `expense_details`.`subtotal` * -0.02,
          if(truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '2',
             `expense_details`.`subtotal` * 0.02, 0))               AS `Impuesto_2`,
       if(substr(`expenses`.`key`, 30, 2) = 3 and
          truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '2',
          `expense_details`.`subtotal` * -1,
          if(truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '2',
             `expense_details`.`subtotal`, 0))                      AS `Monto_2`,
       if(substr(`expenses`.`key`, 30, 2) = 3 and
          cast(json_extract(`expense_details`.`taxes`, '$.rate') as char charset utf8mb4) =
          '4', `expense_details`.`subtotal` * -0.04, if(
                      cast(json_extract(`expense_details`.`taxes`,
                                        '$.rate') as char charset utf8mb4) = '4',
                      `expense_details`.`subtotal` * 0.04, 0))      AS `Impuesto_4`,
       if(substr(`expenses`.`key`, 30, 2) = 3 and
          cast(json_extract(`expense_details`.`taxes`, '$.rate') as char charset utf8mb4) =
          '4', `expense_details`.`subtotal` * -1, if(
                      cast(json_extract(`expense_details`.`taxes`,
                                        '$.rate') as char charset utf8mb4) = '4',
                      `expense_details`.`subtotal`, 0))             AS `Monto_4`,
       if(substr(`expenses`.`key`, 30, 2) = 3 and
          truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '8',
          `expense_details`.`subtotal` * -0.08,
          if(truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '8',
             `expense_details`.`subtotal` * 0.08, 0))               AS `Impuesto_8`,
       if(substr(`expenses`.`key`, 30, 2) = 3 and
          truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '8',
          `expense_details`.`subtotal` * -1,
          if(truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '8',
             `expense_details`.`subtotal`, 0))                      AS `Monto_8`,
       if(substr(`expenses`.`key`, 30, 2) = 3 and
          truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '13',
          `expense_details`.`subtotal` * -0.13,
          if(truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '13',
             `expense_details`.`subtotal` * 0.13, 0))               AS `Impuesto_13`,
       if(substr(`expenses`.`key`, 30, 2) = 3 and
          truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '13',
          `expense_details`.`subtotal` * -1,
          if(truncate(json_extract(`expense_details`.`taxes`, '$.rate'), 0) = '13',
             `expense_details`.`subtotal`, 0))                      AS `Monto_13`,
       `expense_details`.`total_amount`                             AS `total_amount`,
       truncate(json_extract(`expense_details`.`taxes`, '$.mount'), 0) -
       if(truncate(json_extract(`expense_details`.`taxes`, '$.exoneration'), 0) <> '',
          truncate(json_extract(json_extract(`expense_details`.`taxes`, '$.exoneration'),
                                '$.AmountExoneration'), 0), 0)                                 AS `total_impuesto`,
       `expenses`.`currency`                                        AS `currency`,
       `expenses`.`exchange_rate`                                   AS `exchange_rate`,
       `counts`.`name`                                              AS `name`,
       `branch_offices`.`name_branch_office`                        AS `name_branch_office`
from ((((((`expense_details` join `expenses`
           on (`expense_details`.`id_expense` =
               `expenses`.`id`)) join `economic_activities`
          on (`expenses`.`e_a` =
              `economic_activities`.`number`)) join `mh_categories`
         on (`expenses`.`id_mh_categories` =
             `mh_categories`.`id`)) join `providers`
        on (`expenses`.`id_provider` =
            `providers`.`id`)) left join `counts`
       on (`expense_details`.`id_count` =
           `counts`.`id`)) join `branch_offices`
      on (`expenses`.`id_branch_office` = `branch_offices`.`id`))
where (`expenses`.`condition` = 'aceptado' or
       `expenses`.`condition` = 'ninguna')
  and `expenses`.`state` <> 'rechazado'
  and substr(`expenses`.`consecutive`, 1, 2) <> 'OC'
order by `expenses`.`created_at` desc;

create view view_expIva_details as
select
    `ved`.`Compania` AS `Compania`,
    `ved`.`Mes` AS `Mes`,
    `ved`.`Fecha` AS `Fecha`,
    `ved`.`Categoria_MH` AS `Categoria_MH`,
    `ved`.`Consecutivo` AS `Consecutivo_real`,
    `ved`.`Proveedor` AS `Proveedor`,
    `ved`.`Monto` AS `Monto`,
    `ved`.`discounts` AS `discounts`,
    `ved`.`Impuesto_0` AS `Impuesto_0`,
    `ved`.`Impuesto_1` AS `Impuesto_1`,
    `ved`.`Impuesto_2` AS `Impuesto_2`,
    `ved`.`Impuesto_4` AS `Impuesto_4`,
    `ved`.`Impuesto_8` AS `Impuesto_8`,
    `ved`.`Impuesto_13` AS `Impuesto_13`,
    `ved`.`total_amount` AS `total_amount`,
    `ved`.`currency` AS `currency`,
    `ved`.`Actividad_Economica` AS `Actividad_Economica`,
    `ved`.`idSucursal` AS `idSucursal`,
    `ved`.`nombreSucursal` AS `nombreSucursal`
from
    `view_expenses_details` `ved`;

create view view_expenses_resumes as
select
    `expenses`.`id_branch_office` AS `idSucursal`,
    `branch_offices`.`name_branch_office` AS `nombreSucursal`,
    `expenses`.`id_company` AS `Compania`,
    case
        when substr(`expenses`.`key`, 30, 2) = 1 then 'Factura Electronica'
        when substr(`expenses`.`key`, 30, 2) = 2 then 'Nota Debito'
        when substr(`expenses`.`key`, 30, 2) = 3 then 'Nota Credito'
        when substr(`expenses`.`key`, 30, 2) = 4 then 'Tiquete Electronico'
        when substr(`expenses`.`key`, 30, 2) = 8 then 'Compra Electronica'
        else 'Otro'
    end AS `Tipo_Documento`,
    `expenses`.`e_a` AS `Actividad_Economica`,
    `economic_activities`.`name_ea` AS `Nombre_Actividad_Economica`,
    `mh_categories`.`name` AS `Categoria_MH`,
    month(`expenses`.`date_issue`) AS `Mes`,
    `expenses`.`date_issue` AS `Fecha`,
    substr(`expenses`.`key`, 22, 20) AS `Consecutivo`,
    `providers`.`id_card` AS `Cedula`,
    `providers`.`id` AS `Id_proveedor`,
    `providers`.`name_provider` AS `Proveedor`,
    if(
        substr(`expenses`.`key`, 30, 2) = 3
        and truncate(
            json_extract(`expense_details`.`taxes`, '$.rate'),
            0
        ) = '0',
        `expenses`.`total_exempt` * -1,
        if(
            truncate(
                json_extract(`expense_details`.`taxes`, '$.rate'),
                0
            ) = '0',
            `expenses`.`total_exempt`,
            if(
                substr(`expenses`.`key`, 30, 2) = 3
                and `expense_details`.`taxes` = '[]',
                `expenses`.`total_exempt` * -1,
                if(
                    `expense_details`.`taxes` = '[]',
                    `expenses`.`total_exempt`,
                    0
                )
            )
        )
    ) AS `Excento`,
    if(
        substr(`expenses`.`key`, 30, 2) = 3
        and truncate(
            json_extract(`expense_details`.`taxes`, '$.rate'),
            0
        ) <> '0',
        `expenses`.`total_taxed` * -1,
        if(
            truncate(
                json_extract(`expense_details`.`taxes`, '$.rate'),
                0
            ) <> '0',
            `expenses`.`total_taxed`,
            0
        )
    ) AS `Gravado`,
    `expenses`.`total_discount` AS `discounts`,
    case
        when substr(`expenses`.`key`, 30, 2) = 1 then `expenses`.`total_net_sale` * 1
        when substr(`expenses`.`key`, 30, 2) = 2 then `expenses`.`total_net_sale` * 1
        when substr(`expenses`.`key`, 30, 2) = 3 then `expenses`.`total_net_sale` * -1
        when substr(`expenses`.`key`, 30, 2) = 4 then `expenses`.`total_net_sale` * 1
        else `expenses`.`total_net_sale` * 1
    end AS `subtotal`,
    sum(
        if(
            substr(`expenses`.`key`, 30, 2) = 3
            and truncate(
                json_extract(`expense_details`.`taxes`, '$.rate'),
                0
            ) = '',
            `expense_details`.`subtotal`,
            if(
                truncate(
                    json_extract(`expense_details`.`taxes`, '$.rate'),
                    0
                ) = '',
                `expense_details`.`subtotal`,
                0
            )
        )
    ) AS `Impuesto_0`,
    sum(
        if(
            substr(`expenses`.`key`, 30, 2) = 3
            and truncate(
                json_extract(`expense_details`.`taxes`, '$.rate'),
                0
            ) = '1',
            `expense_details`.`subtotal` * -0.01,
            if(
                truncate(
                    json_extract(`expense_details`.`taxes`, '$.rate'),
                    0
                ) = '1',
                `expense_details`.`subtotal` * 0.01,
                0
            )
        )
    ) AS `Impuesto_1`,
    sum(
        if(
            substr(`expenses`.`key`, 30, 2) = 3
            and truncate(
                json_extract(`expense_details`.`taxes`, '$.rate'),
                0
            ) = '2',
            `expense_details`.`subtotal` * -0.02,
            if(
                truncate(
                    json_extract(`expense_details`.`taxes`, '$.rate'),
                    0
                ) = '2',
                `expense_details`.`subtotal` * 0.02,
                0
            )
        )
    ) AS `Impuesto_2`,
    sum(
        if(
            substr(`expenses`.`key`, 30, 2) = 3
            and truncate(
                json_extract(`expense_details`.`taxes`, '$.rate'),
                0
            ) = '4',
            `expense_details`.`subtotal` * -0.04,
            if(
                truncate(
                    json_extract(`expense_details`.`taxes`, '$.rate'),
                    0
                ) = '4',
                `expense_details`.`subtotal` * 0.04,
                0
            )
        )
    ) AS `Impuesto_4`,
    sum(
        if(
            substr(`expenses`.`key`, 30, 2) = 3
            and truncate(
                json_extract(`expense_details`.`taxes`, '$.rate'),
                0
            ) = '8',
            `expense_details`.`subtotal` * -0.08,
            if(
                truncate(
                    json_extract(`expense_details`.`taxes`, '$.rate'),
                    0
                ) = '8',
                `expense_details`.`subtotal` * 0.08,
                0
            )
        )
    ) AS `Impuesto_8`,
    sum(
        if(
            substr(`expenses`.`key`, 30, 2) = 3
            and truncate(
                json_extract(`expense_details`.`taxes`, '$.rate'),
                0
            ) = '13',
            `expense_details`.`subtotal` * -0.13,
            if(
                truncate(
                    json_extract(`expense_details`.`taxes`, '$.rate'),
                    0
                ) = '13',
                `expense_details`.`subtotal` * 0.13,
                0
            )
        )
    ) AS `Impuesto_13`,
    `expenses`.`total_tax` AS `total_tax`,
    `expenses`.`total_document` AS `total_document`,
    `expenses`.`currency` AS `currency`,
    `expenses`.`exchange_rate` AS `exchange_rate`,
    `counts`.`name` AS `name`,
    `branch_offices`.`name_branch_office` AS `name_branch_office`
from
    (
        (
            (
                (
                    (
                        (
                            `expense_details`
                            join `expenses` on (
                                `expense_details`.`id_expense` = `expenses`.`id`
                            )
                        )
                        join `economic_activities` on (
                            `expenses`.`e_a` = `economic_activities`.`number`
                        )
                    )
                    join `mh_categories` on (
                        `expenses`.`id_mh_categories` = `mh_categories`.`id`
                    )
                )
                join `providers` on (
                    `expenses`.`id_provider` = `providers`.`id`
                )
            )
            left join `counts` on (
                `expense_details`.`id_count` = `counts`.`id`
            )
        )
        join `branch_offices` on (
            `expenses`.`id_branch_office` = `branch_offices`.`id`
        )
    )
where
    substr(`expenses`.`consecutive`, 1, 2) <> 'OC'
group by
    `expenses`.`consecutive`,
    `expenses`.`id_company`;

create view view_inventory_reports as
select
    `categories`.`name` AS `Categoria`,
    `families`.`name` AS `Familia`,
    `class_products`.`name` AS `Clase`,
    `products`.`internal_code` AS `Codigo_Interno`,
    `products`.`description` AS `Descripcion`,
    `skuses`.`description` AS `Unidad`,
    `products`.`stock` AS `Cantidad`,
    `products`.`alert_min` AS `Minimo`,
    `products`.`stock_base` AS `Maximo`
from
    (
        (
            (
                (
                    `products`
                    join `categories` on (
                        `products`.`id_category` = `categories`.`id`
                    )
                )
                join `families` on (
                    `products`.`id_family` = `families`.`id`
                )
            )
            join `class_products` on (
                `products`.`id_class` = `class_products`.`id`
            )
        )
        join `skuses` on (`products`.`id_sku` = `skuses`.`id`)
    )
where
    `products`.`type` = 1;

create view view_iva_details as
select
    `documents`.`id_company` AS `Compania`,
    `documents`.`id_branch_office` AS `idSucursal`,
    `branch_offices`.`name_branch_office` AS `nombreSucursal`,
    case
        when `documents`.`type_doc` = 1 then 'Factura Electronica'
        when `documents`.`type_doc` = 2 then 'Nota de Debito'
        when `documents`.`type_doc` = 3 then 'Nota de Credito'
        when `documents`.`type_doc` = 4 then 'Tiquete Electronico'
        when `documents`.`type_doc` = 11 then 'Factura Interna'
        else 'Otro'
    end AS `Tipo_de_documento`,
    `documents`.`e_a` AS `Actividad_Economica`,
    `economic_activities`.`name_ea` AS `Nombre_Actividad`,
    `mh_categories`.`name` AS `Categoria_MH`,
    month(`documents`.`created_at`) AS `Mes`,
    year(`documents`.`created_at`) AS `ano`,
    date_format(`documents`.`created_at`, '%d/%m/%Y') AS `fecha_de_venta`,
    substr(`documents`.`key`, 22, 20) AS `Consecutivo`,
    `clients`.`id_card` AS `Cedula`,
    `clients`.`name_client` AS `Cliente`,
    `products`.`description` AS `Producto`,
    `document_details`.`sku` AS `Unidad`,
    `document_details`.`qty` AS `Cantidad`,
    `document_details`.`price_unid` AS `Precio`,
    case
        when `documents`.`type_doc` = 1 then `document_details`.`subtotal` * 1
        when `documents`.`type_doc` = 2 then `document_details`.`subtotal` * 1
        when `documents`.`type_doc` = 3 then `document_details`.`subtotal` * -1
        when `documents`.`type_doc` = 4 then `document_details`.`subtotal` * 1
        else `document_details`.`subtotal` * 1
    end AS `Monto`,
    if(
        `documents`.`type_doc` = 3
        and json_extract(`document_details`.`taxes`, '$.rate') = '0',
        `document_details`.`subtotal` * -1,
        if(
            json_extract(`document_details`.`taxes`, '$.rate') = '0',
            `document_details`.`subtotal`,
            0
        )
    ) AS `Excento`,
    if(
        `documents`.`type_doc` = 3
        and json_extract(`document_details`.`taxes`, '$.rate') <> '0',
        `document_details`.`subtotal` * -1,
        if(
            json_extract(`document_details`.`taxes`, '$.rate') <> '0',
            `document_details`.`subtotal`,
            0
        )
    ) AS `Gravado`,
    case
        when json_extract(
            json_extract(`document_details`.`taxes`, '$.exoneration'),
            '$.AmountExoneration'
        ) is not null then if(
            `documents`.`type_doc` = 3
            and json_extract(`document_details`.`taxes`, '$.rate') <> '0',
            `document_details`.`subtotal` * -1,
            if(
                json_extract(`document_details`.`taxes`, '$.rate') <> '0',
                `document_details`.`subtotal`,
                0
            )
        )
        else 0
    end AS `Monto_Exonerado`,
    `document_details`.`discounts` AS `Descuento`,
    case
        when `documents`.`type_doc` = 1 then `document_details`.`subtotal` * 1 - `document_details`.`discounts`
        when `documents`.`type_doc` = 2 then `document_details`.`subtotal` * 1 - `document_details`.`discounts`
        when `documents`.`type_doc` = 3 then `document_details`.`subtotal` * -1 - `document_details`.`discounts`
        when `documents`.`type_doc` = 4 then `document_details`.`subtotal` * 1 - `document_details`.`discounts`
        else `document_details`.`subtotal` * 1 - `document_details`.`discounts`
    end AS `Subtotal`,
    case
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '0' then '0'
        when `document_details`.`taxes` = '[]' then '0'
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '1' then '1'
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '2' then '2'
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '4' then '4'
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '8' then '8'
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '13' then '13'
        else 'Otro'
    end AS `Tarifa`,
    if(
        `documents`.`type_doc` = 3
        and truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '',
        `document_details`.`subtotal` * 0,
        if(
            truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '',
            `document_details`.`subtotal` * 0,
            if(
                `document_details`.`taxes` = '[]',
                `document_details`.`subtotal` * 0,
                0
            )
        )
    ) AS `Impuesto_0`,
    if(
        `documents`.`type_doc` = 3
        and truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '',
        `document_details`.`subtotal`,
        if(
            truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '',
            `document_details`.`subtotal`,
            if(
                `document_details`.`taxes` = '[]',
                `document_details`.`subtotal`,
                0
            )
        )
    ) AS `Monto_0`,
    if(
        `documents`.`type_doc` = 3
        and truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '1',
        `document_details`.`subtotal` * -0.01,
        if(
            truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '1',
            `document_details`.`subtotal` * 0.01,
            0
        )
    ) AS `Impuesto_1`,
    if(
        `documents`.`type_doc` = 3
        and truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '1',
        `document_details`.`subtotal` * -1,
        if(
            truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '1',
            `document_details`.`subtotal`,
            0
        )
    ) AS `Monto_1`,
    if(
        `documents`.`type_doc` = 3
        and truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '2',
        `document_details`.`subtotal` * -0.02,
        if(
            truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '2',
            `document_details`.`subtotal` * 0.02,
            0
        )
    ) AS `Impuesto_2`,
    if(
        `documents`.`type_doc` = 3
        and truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '2',
        `document_details`.`subtotal` * -1,
        if(
            truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '2',
            `document_details`.`subtotal`,
            0
        )
    ) AS `Monto_2`,
    if(
        `documents`.`type_doc` = 3
        and truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '4',
        `document_details`.`subtotal` * -0.04,
        if(
            truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '4',
            `document_details`.`subtotal` * 0.04,
            0
        )
    ) AS `Impuesto_4`,
    if(
        `documents`.`type_doc` = 3
        and truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '4',
        `document_details`.`subtotal` * -1,
        if(
            truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '4',
            `document_details`.`subtotal`,
            0
        )
    ) AS `Monto_4`,
    if(
        `documents`.`type_doc` = 3
        and truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '8',
        `document_details`.`subtotal` * -0.08,
        if(
            truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '8',
            `document_details`.`subtotal` * 0.08,
            0
        )
    ) AS `Impuesto_8`,
    if(
        `documents`.`type_doc` = 3
        and truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '8',
        `document_details`.`subtotal` * -1,
        if(
            truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '8',
            `document_details`.`subtotal`,
            0
        )
    ) AS `Monto_8`,
    if(
        `documents`.`type_doc` = 3
        and truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '13',
        `document_details`.`subtotal` * -0.13,
        if(
            truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '13',
            `document_details`.`subtotal` * 0.13,
            0
        )
    ) AS `Impuesto_13`,
    if(
        `documents`.`type_doc` = 3
        and truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '13',
        `document_details`.`subtotal` * -1,
        if(
            truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '13',
            `document_details`.`subtotal`,
            0
        )
    ) AS `Monto_13`,
    case
        when truncate(
            json_extract(
                json_extract(`document_details`.`taxes`, '$.exoneration'),
                '$.PercentageExoneration'
            ),
            0
        ) = '0' then '0%'
        when truncate(
            json_extract(
                json_extract(`document_details`.`taxes`, '$.exoneration'),
                '$.PercentageExoneration'
            ),
            0
        ) = '1' then '1%'
        when truncate(
            json_extract(
                json_extract(`document_details`.`taxes`, '$.exoneration'),
                '$.PercentageExoneration'
            ),
            0
        ) = '2' then '2%'
        when truncate(
            json_extract(
                json_extract(`document_details`.`taxes`, '$.exoneration'),
                '$.PercentageExoneration'
            ),
            0
        ) = '4' then '4%'
        when truncate(
            json_extract(
                json_extract(`document_details`.`taxes`, '$.exoneration'),
                '$.PercentageExoneration'
            ),
            0
        ) = '8' then '8%'
        when truncate(
            json_extract(
                json_extract(`document_details`.`taxes`, '$.exoneration'),
                '$.PercentageExoneration'
            ),
            0
        ) = '13' then '13%'
        else '0'
    end AS `Tarifa_Exonerada`,
    case
        when json_extract(
            json_extract(`document_details`.`taxes`, '$.exoneration'),
            '$.AmountExoneration'
        ) is not null then if(
            `documents`.`type_doc` = 3
            and json_extract(`document_details`.`taxes`, '$.rate') <> '0',
            json_extract(
                json_extract(`document_details`.`taxes`, '$.exoneration'),
                '$.AmountExoneration'
            ) * -1,
            json_extract(
                json_extract(`document_details`.`taxes`, '$.exoneration'),
                '$.AmountExoneration'
            )
        )
        else 0
    end AS `Impuesto_exonerado`,
    case
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '0' then if(
            `documents`.`type_doc` = 3
            and truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '0',
            `document_details`.`subtotal` * 0,
            if(
                truncate(
                    json_extract(
                        `document_details`.`taxes`,
                        '$.rate'
                    ),
                    0
                ) = '0',
                `document_details`.`subtotal` * 0,
                0
            )
        )
        when `document_details`.`taxes` = '[]' then `document_details`.`subtotal` * 0
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '1' then if(
            `documents`.`type_doc` = 3
            and truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '1',
            `document_details`.`subtotal` * -0.01,
            if(
                truncate(
                    json_extract(
                        `document_details`.`taxes`,
                        '$.rate'
                    ),
                    0
                ) = '1',
                `document_details`.`subtotal` * 0.01,
                0
            )
        )
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '2' then if(
            `documents`.`type_doc` = 3
            and truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '2',
            `document_details`.`subtotal` * -0.02,
            if(
                truncate(
                    json_extract(
                        `document_details`.`taxes`,
                        '$.rate'
                    ),
                    0
                ) = '2',
                `document_details`.`subtotal` * 0.02,
                0
            )
        )
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '4' then if(
            `documents`.`type_doc` = 3
            and truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '4',
            `document_details`.`subtotal` * -0.04,
            if(
                truncate(
                    json_extract(
                        `document_details`.`taxes`,
                        '$.rate'
                    ),
                    0
                ) = '4',
                `document_details`.`subtotal` * 0.04,
                0
            )
        )
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '8' then if(
            `documents`.`type_doc` = 3
            and truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '8',
            `document_details`.`subtotal` * -0.08,
            if(
                truncate(
                    json_extract(
                        `document_details`.`taxes`,
                        '$.rate'
                    ),
                    0
                ) = '8',
                `document_details`.`subtotal` * 0.08,
                0
            )
        )
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '13' then if(
            `documents`.`type_doc` = 3
            and truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '13',
            `document_details`.`subtotal` * -0.13,
            if(
                truncate(
                    json_extract(
                        `document_details`.`taxes`,
                        '$.rate'
                    ),
                    0
                ) = '13',
                `document_details`.`subtotal` * 0.13,
                0
            )
        )
        else 0
    end AS `total_impuesto`,
    case
        when `documents`.`type_doc` = 1 then `document_details`.`total_amount_line` * 1
        when `documents`.`type_doc` = 2 then `document_details`.`total_amount_line` * 1
        when `documents`.`type_doc` = 3 then `document_details`.`total_amount_line` * -1
        when `documents`.`type_doc` = 4 then `document_details`.`total_amount_line` * 1
        else `document_details`.`total_amount` * 1
    end AS `total_venta`,
    `documents`.`currency` AS `Moneda`,
    `documents`.`exchange_rate` AS `Tipo_de_cambio`,
    `counts`.`description` AS `Cuenta_Contable`,
    `branch_offices`.`name_branch_office` AS `Sucursal`,
    `terminals`.`number` AS `Terminal`,
    `sellers`.`name` AS `Vendedor`,
    `document_details`.`cost_unid` AS `costo_unitario`,
    `document_details`.`cost_unid` * `document_details`.`qty_dispatch` AS `costo_total`,
    `document_details`.`subtotal` - `document_details`.`cost_unid` AS `utilidad`
from
    (
        (
            (
                (
                    (
                        (
                            (
                                (
                                    (
                                        (
                                            (
                                                `document_details`
                                                join `documents` on (
                                                    `documents`.`id` = `document_details`.`id_document`
                                                )
                                            )
                                            join `clients` on (
                                                `documents`.`id_client` = `clients`.`id`
                                            )
                                        )
                                        join `sale_conditions` on (
                                            `documents`.`sale_condition` = `sale_conditions`.`code`
                                        )
                                    )
                                    join `products` on (
                                        `document_details`.`id_product` = `products`.`id`
                                    )
                                )
                                join `economic_activities` on (
                                    `documents`.`e_a` = `economic_activities`.`number`
                                )
                            )
                            join `mh_categories` on (
                                `documents`.`id_mh_categories` = `mh_categories`.`id`
                            )
                        )
                        left join `counts` on (
                            `document_details`.`id_count` = `counts`.`id`
                        )
                    )
                    join `branch_offices` on (
                        `documents`.`id_branch_office` = `branch_offices`.`id`
                    )
                )
                join `terminals` on (
                    `documents`.`id_terminal` = `terminals`.`id`
                )
            )
            left join `sellers` on (
                `documents`.`id_seller` = `sellers`.`id`
            )
        )
        left join `document_references` on (
            `document_references`.`id_document` = `documents`.`id`
        )
    )
where
    (
        `documents`.`answer_mh` = 'aceptado'
        or `documents`.`answer_mh` = 'ninguna'
    )
    and `documents`.`type_doc` <> '00'
    and `documents`.`type_doc` <> '99'
    and `documents`.`type_doc` <> '11';

create view view_sales as
select
    `documents`.`id_company` AS `Compania`,
    `documents`.`id_branch_office` AS `idSucursal`,
    `branch_offices`.`name_branch_office` AS `nombreSucursal`,
    case
        when `documents`.`type_doc` = 1 then 'Factura Electronica'
        when `documents`.`type_doc` = 2 then 'Nota de Debito'
        when `documents`.`type_doc` = 3 then 'Nota de Credito'
        when `documents`.`type_doc` = 4 then 'Tiquete Electronico'
        when `documents`.`type_doc` = 11 then 'Proforma'
        else 'Otro'
    end AS `Tipo_de_documento`,
    `documents`.`e_a` AS `Actividad_Economica`,
    `economic_activities`.`name_ea` AS `Nombre_Actividad`,
    `mh_categories`.`name` AS `Categoria_MH`,
    month(`documents`.`created_at`) AS `Mes`,
    `documents`.`created_at` AS `fecha_de_venta`,
    substr(`documents`.`key`, 22, 20) AS `Consecutivo`,
    `clients`.`id_card` AS `Cedula`,
    `clients`.`name_client` AS `Cliente`,
    `documents`.`total_net_sale` AS `total_net_sale`,
    if(
        `documents`.`type_doc` = 3
        and truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '0',
        `documents`.`total_exempt` * -1,
        if(
            truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '0',
            `documents`.`total_exempt`,
            if(
                `documents`.`type_doc` = 3
                and `document_details`.`taxes` = '[]',
                `documents`.`total_exempt` * -1,
                if(
                    `document_details`.`taxes` = '[]',
                    `documents`.`total_exempt`,
                    0
                )
            )
        )
    ) AS `Excento`,
    if(
        `documents`.`type_doc` = 3
        and truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) <> '0',
        `documents`.`total_taxed` * -1,
        if(
            truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) <> '0',
            `documents`.`total_taxed`,
            0
        )
    ) AS `Gravado`,
    `documents`.`total_discount` AS `Descuento`,
    sum(
        if(
            `documents`.`type_doc` = 3
            and truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '',
            `document_details`.`subtotal` * 0,
            if(
                truncate(
                    json_extract(`document_details`.`taxes`, '$.rate'),
                    0
                ) = '',
                `document_details`.`subtotal` * 0,
                if(
                    `document_details`.`taxes` = '[]',
                    `document_details`.`subtotal` * 0,
                    0
                )
            )
        )
    ) AS `Impuesto_0`,
    sum(
        if(
            `documents`.`type_doc` = 3
            and truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '1',
            `document_details`.`subtotal` * -0.01,
            if(
                truncate(
                    json_extract(`document_details`.`taxes`, '$.rate'),
                    0
                ) = '1',
                `document_details`.`subtotal` * 0.01,
                0
            )
        )
    ) AS `Impuesto_1`,
    sum(
        if(
            `documents`.`type_doc` = 3
            and truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '2',
            `document_details`.`subtotal` * -0.02,
            if(
                truncate(
                    json_extract(`document_details`.`taxes`, '$.rate'),
                    0
                ) = '2',
                `document_details`.`subtotal` * 0.02,
                0
            )
        )
    ) AS `Impuesto_2`,
    sum(
        if(
            `documents`.`type_doc` = 3
            and truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '4',
            `document_details`.`subtotal` * -0.04,
            if(
                truncate(
                    json_extract(`document_details`.`taxes`, '$.rate'),
                    0
                ) = '4',
                `document_details`.`subtotal` * 0.04,
                0
            )
        )
    ) AS `Impuesto_4`,
    sum(
        if(
            `documents`.`type_doc` = 3
            and truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '8',
            `document_details`.`subtotal` * -0.08,
            if(
                truncate(
                    json_extract(`document_details`.`taxes`, '$.rate'),
                    0
                ) = '8',
                `document_details`.`subtotal` * 0.08,
                0
            )
        )
    ) AS `Impuesto_8`,
    sum(
        if(
            `documents`.`type_doc` = 3
            and truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '13',
            `document_details`.`subtotal` * -0.13,
            if(
                truncate(
                    json_extract(`document_details`.`taxes`, '$.rate'),
                    0
                ) = '13',
                `document_details`.`subtotal` * 0.13,
                0
            )
        )
    ) AS `Impuesto_13`,
    `documents`.`total_tax` AS `Total_Impuestos`,
    `documents`.`total_document` AS `Total_de_venta`,
    `documents`.`currency` AS `currency`,
    `documents`.`exchange_rate` AS `exchange_rate`,
    `counts`.`description` AS `Cuenta_Contable`,
    `branch_offices`.`name_branch_office` AS `name_branch_office`,
    `terminals`.`number` AS `number`,
    `sellers`.`name` AS `name_seller`,
    `document_references`.`number` AS `Referencia`
from
    (
        (
            (
                (
                    (
                        (
                            (
                                (
                                    (
                                        `document_details`
                                        join `documents` on (
                                            `documents`.`id` = `document_details`.`id_document`
                                        )
                                    )
                                    join `economic_activities` on (
                                        `economic_activities`.`number` = `documents`.`e_a`
                                    )
                                )
                                join `mh_categories` on (
                                    `mh_categories`.`id` = `documents`.`id_mh_categories`
                                )
                            )
                            join `clients` on (
                                `documents`.`id_client` = `clients`.`id`
                            )
                        )
                        left join `counts` on (
                            `document_details`.`id_count` = `counts`.`id`
                        )
                    )
                    join `branch_offices` on (
                        `documents`.`id_branch_office` = `branch_offices`.`id`
                    )
                )
                join `terminals` on (
                    `branch_offices`.`id` = `terminals`.`id_branch_office`
                )
            )
            left join `sellers` on (
                `documents`.`id_seller` = `sellers`.`id`
            )
        )
        left join `document_references` on (
            `documents`.`id` = `document_references`.`id_document`
        )
    )
where
    (
        `documents`.`answer_mh` = 'aceptado'
        or `documents`.`answer_mh` = 'ninguna'
    )
    and `documents`.`type_doc` <> '00'
    and `documents`.`type_doc` <> '99'
group by
    `documents`.`consecutive`,
    `documents`.`id_company`;

create view view_sales_details as
select
    `documents`.`id_company` AS `Compania`,
    `documents`.`id_branch_office` AS `idSucursal`,
    `branch_offices`.`name_branch_office` AS `nombreSucursal`,
    case
        when `documents`.`type_doc` = 1 then 'Factura Electronica'
        when `documents`.`type_doc` = 2 then 'Nota de Debito'
        when `documents`.`type_doc` = 3 then 'Nota de Credito'
        when `documents`.`type_doc` = 4 then 'Tiquete Electronico'
        when `documents`.`type_doc` = 11 then 'Factura Interna'
        else 'Otro'
    end AS `Tipo_de_documento`,
    `documents`.`e_a` AS `Actividad_Economica`,
    `economic_activities`.`name_ea` AS `Nombre_Actividad`,
    `mh_categories`.`name` AS `Categoria_MH`,
    month(`documents`.`created_at`) AS `Mes`,
    `documents`.`created_at` AS `fecha_de_venta`,
    substr(`documents`.`key`, 22, 20) AS `Consecutivo`,
    `clients`.`id_card` AS `Cedula`,
    `clients`.`name_client` AS `Cliente`,
    `clients`.`id` AS `id_cliente`,
    `products`.`description` AS `Producto`,
    `document_details`.`sku` AS `Unidad`,
    `document_details`.`qty` AS `Cantidad`,
    `document_details`.`price_unid` AS `Precio`,
    case
        when `documents`.`type_doc` = 1 then `document_details`.`subtotal` * 1
        when `documents`.`type_doc` = 2 then `document_details`.`subtotal` * 1
        when `documents`.`type_doc` = 3 then `document_details`.`subtotal` * -1
        when `documents`.`type_doc` = 4 then `document_details`.`subtotal` * 1
        else `document_details`.`subtotal` * 1
    end AS `Monto`,
    if(
        `documents`.`type_doc` = 3
        and json_extract(`document_details`.`taxes`, '$.rate') = '0',
        `document_details`.`subtotal` * -1,
        if(
            json_extract(`document_details`.`taxes`, '$.rate') = '0',
            `document_details`.`subtotal`,
            0
        )
    ) AS `Excento`,
    if(
        `documents`.`type_doc` = 3
        and json_extract(`document_details`.`taxes`, '$.rate') <> '0',
        `document_details`.`subtotal` * -1,
        if(
            json_extract(`document_details`.`taxes`, '$.rate') <> '0',
            `document_details`.`subtotal`,
            0
        )
    ) AS `Gravado`,
    case
        when json_extract(
            json_extract(`document_details`.`taxes`, '$.exoneration'),
            '$.AmountExoneration'
        ) is not null then if(
            `documents`.`type_doc` = 3
            and json_extract(`document_details`.`taxes`, '$.rate') <> '0',
            `document_details`.`subtotal` * -1,
            if(
                json_extract(`document_details`.`taxes`, '$.rate') <> '0',
                `document_details`.`subtotal`,
                0
            )
        )
        else 0
    end AS `Monto_Exonerado`,
    `document_details`.`discounts` AS `Descuento`,
    case
        when `documents`.`type_doc` = 1 then `document_details`.`subtotal` * 1 - `document_details`.`discounts`
        when `documents`.`type_doc` = 2 then `document_details`.`subtotal` * 1 - `document_details`.`discounts`
        when `documents`.`type_doc` = 3 then `document_details`.`subtotal` * -1 - `document_details`.`discounts`
        when `documents`.`type_doc` = 4 then `document_details`.`subtotal` * 1 - `document_details`.`discounts`
        else `document_details`.`subtotal` * 1 - `document_details`.`discounts`
    end AS `Subtotal`,
    case
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '0' then '0%'
        when `document_details`.`taxes` = '[]' then '0%'
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '1' then '1%'
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '2' then '2%'
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '4' then '4%'
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '8' then '8%'
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '13' then '13%'
        else 'Otro'
    end AS `Tarifa`,
    if(
        `documents`.`type_doc` = 3
        and truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '',
        `document_details`.`subtotal` * 0,
        if(
            truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '',
            `document_details`.`subtotal` * 0,
            if(
                `document_details`.`taxes` = '[]',
                `document_details`.`subtotal` * 0,
                0
            )
        )
    ) AS `Impuesto_0`,
    if(
        `documents`.`type_doc` = 3
        and truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '1',
        `document_details`.`subtotal` * -0.01,
        if(
            truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '1',
            `document_details`.`subtotal` * 0.01,
            0
        )
    ) AS `Impuesto_1`,
    if(
        `documents`.`type_doc` = 3
        and truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '2',
        `document_details`.`subtotal` * -0.02,
        if(
            truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '2',
            `document_details`.`subtotal` * 0.02,
            0
        )
    ) AS `Impuesto_2`,
    if(
        `documents`.`type_doc` = 3
        and truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '4',
        `document_details`.`subtotal` * -0.04,
        if(
            truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '4',
            `document_details`.`subtotal` * 0.04,
            0
        )
    ) AS `Impuesto_4`,
    if(
        `documents`.`type_doc` = 3
        and truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '8',
        `document_details`.`subtotal` * -0.08,
        if(
            truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '8',
            `document_details`.`subtotal` * 0.08,
            0
        )
    ) AS `Impuesto_8`,
    if(
        `documents`.`type_doc` = 3
        and truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '13',
        `document_details`.`subtotal` * -0.13,
        if(
            truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '13',
            `document_details`.`subtotal` * 0.13,
            0
        )
    ) AS `Impuesto_13`,
    case
        when truncate(
            json_extract(
                json_extract(`document_details`.`taxes`, '$.exoneration'),
                '$.PercentageExoneration'
            ),
            0
        ) = '0' then '0%'
        when truncate(
            json_extract(
                json_extract(`document_details`.`taxes`, '$.exoneration'),
                '$.PercentageExoneration'
            ),
            0
        ) = '1' then '1%'
        when truncate(
            json_extract(
                json_extract(`document_details`.`taxes`, '$.exoneration'),
                '$.PercentageExoneration'
            ),
            0
        ) = '2' then '2%'
        when truncate(
            json_extract(
                json_extract(`document_details`.`taxes`, '$.exoneration'),
                '$.PercentageExoneration'
            ),
            0
        ) = '4' then '4%'
        when truncate(
            json_extract(
                json_extract(`document_details`.`taxes`, '$.exoneration'),
                '$.PercentageExoneration'
            ),
            0
        ) = '8' then '8%'
        when truncate(
            json_extract(
                json_extract(`document_details`.`taxes`, '$.exoneration'),
                '$.PercentageExoneration'
            ),
            0
        ) = '13' then '13%'
        else '0'
    end AS `Tarifa_Exonerada`,
    case
        when json_extract(
            json_extract(`document_details`.`taxes`, '$.exoneration'),
            '$.AmountExoneration'
        ) is not null then if(
            `documents`.`type_doc` = 3
            and json_extract(`document_details`.`taxes`, '$.rate') <> '0',
            json_extract(
                json_extract(`document_details`.`taxes`, '$.exoneration'),
                '$.AmountExoneration'
            ) * -1,
            json_extract(
                json_extract(`document_details`.`taxes`, '$.exoneration'),
                '$.AmountExoneration'
            )
        )
        else 0
    end AS `Impuesto_exonerado`,
    case
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '0' then if(
            `documents`.`type_doc` = 3
            and truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '0',
            `document_details`.`subtotal` * 0,
            if(
                truncate(
                    json_extract(
                        `document_details`.`taxes`,
                        '$.rate'
                    ),
                    0
                ) = '0',
                `document_details`.`subtotal` * 0,
                0
            )
        )
        when `document_details`.`taxes` = '[]' then `document_details`.`subtotal` * 0
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '1' then if(
            `documents`.`type_doc` = 3
            and truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '1',
            `document_details`.`subtotal` * -0.01,
            if(
                truncate(
                    json_extract(
                        `document_details`.`taxes`,
                        '$.rate'
                    ),
                    0
                ) = '1',
                `document_details`.`subtotal` * 0.01,
                0
            )
        )
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '2' then if(
            `documents`.`type_doc` = 3
            and truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '2',
            `document_details`.`subtotal` * -0.02,
            if(
                truncate(
                    json_extract(
                        `document_details`.`taxes`,
                        '$.rate'
                    ),
                    0
                ) = '2',
                `document_details`.`subtotal` * 0.02,
                0
            )
        )
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '4' then if(
            `documents`.`type_doc` = 3
            and truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '4',
            `document_details`.`subtotal` * -0.04,
            if(
                truncate(
                    json_extract(
                        `document_details`.`taxes`,
                        '$.rate'
                    ),
                    0
                ) = '4',
                `document_details`.`subtotal` * 0.04,
                0
            )
        )
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '8' then if(
            `documents`.`type_doc` = 3
            and truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '8',
            `document_details`.`subtotal` * -0.08,
            if(
                truncate(
                    json_extract(
                        `document_details`.`taxes`,
                        '$.rate'
                    ),
                    0
                ) = '8',
                `document_details`.`subtotal` * 0.08,
                0
            )
        )
        when truncate(
            json_extract(`document_details`.`taxes`, '$.rate'),
            0
        ) = '13' then if(
            `documents`.`type_doc` = 3
            and truncate(
                json_extract(`document_details`.`taxes`, '$.rate'),
                0
            ) = '13',
            `document_details`.`subtotal` * -0.13,
            if(
                truncate(
                    json_extract(
                        `document_details`.`taxes`,
                        '$.rate'
                    ),
                    0
                ) = '13',
                `document_details`.`subtotal` * 0.13,
                0
            )
        )
        else 0
    end AS `total_impuesto`,
    case
        when `documents`.`type_doc` = 1 then `document_details`.`total_amount_line` * 1
        when `documents`.`type_doc` = 2 then `document_details`.`total_amount_line` * 1
        when `documents`.`type_doc` = 3 then `document_details`.`total_amount_line` * -1
        when `documents`.`type_doc` = 4 then `document_details`.`total_amount_line` * 1
        else `document_details`.`total_amount` * 1
    end AS `total_venta`,
    `documents`.`currency` AS `Moneda`,
    `documents`.`exchange_rate` AS `Tipo_de_cambio`,
    `counts`.`description` AS `Cuenta_Contable`,
    `branch_offices`.`name_branch_office` AS `Sucursal`,
    `terminals`.`number` AS `Terminal`,
    `sellers`.`name` AS `Vendedor`,
    `document_details`.`cost_unid` AS `costo_unitario`,
    `document_details`.`cost_unid` * `document_details`.`qty_dispatch` AS `costo_total`,
    `document_details`.`subtotal` - `document_details`.`cost_unid` AS `utilidad`
from
    (
        (
            (
                (
                    (
                        (
                            (
                                (
                                    (
                                        (
                                            (
                                                `document_details`
                                                join `documents` on (
                                                    `documents`.`id` = `document_details`.`id_document`
                                                )
                                            )
                                            join `clients` on (
                                                `documents`.`id_client` = `clients`.`id`
                                            )
                                        )
                                        join `sale_conditions` on (
                                            `documents`.`sale_condition` = `sale_conditions`.`code`
                                        )
                                    )
                                    join `products` on (
                                        `document_details`.`id_product` = `products`.`id`
                                    )
                                )
                                join `economic_activities` on (
                                    `documents`.`e_a` = `economic_activities`.`number`
                                )
                            )
                            join `mh_categories` on (
                                `documents`.`id_mh_categories` = `mh_categories`.`id`
                            )
                        )
                        left join `counts` on (
                            `document_details`.`id_count` = `counts`.`id`
                        )
                    )
                    join `branch_offices` on (
                        `documents`.`id_branch_office` = `branch_offices`.`id`
                    )
                )
                join `terminals` on (
                    `documents`.`id_terminal` = `terminals`.`id`
                )
            )
            left join `sellers` on (
                `documents`.`id_seller` = `sellers`.`id`
            )
        )
        left join `document_references` on (
            `document_references`.`id_document` = `documents`.`id`
        )
    )
where
    (
        `documents`.`answer_mh` = 'aceptado'
        or `documents`.`answer_mh` = 'ninguna'
    )
    and `documents`.`type_doc` <> '00'
    and `documents`.`type_doc` <> '99';

create view view_summary_ivas as
select
    `vid`.`Compania` AS `Compania`,
    `vid`.`Mes` AS `Mes`,
    `vid`.`ano` AS `Ano`,
    `vid`.`Actividad_Economica` AS `Actividad_Economica`,
    `vid`.`Nombre_Actividad` AS `Nombre_Actividad`,
    `vid`.`Categoria_MH` AS `Categoria_MH`,
    `vid`.`Tarifa` AS `Tarifa`,
    `vid`.`idSucursal` AS `idSucursal`,
    `vid`.`nombreSucursal` AS `nombreSucursal`,
    sum(`vid`.`Monto`) AS `monto`,
    sum(`vid`.`Impuesto_0`) AS `Impuesto_0`,
    sum(`vid`.`Impuesto_1`) AS `Impuesto_1`,
    sum(`vid`.`Impuesto_2`) AS `Impuesto_2`,
    sum(`vid`.`Impuesto_4`) AS `Impuesto_4`,
    sum(`vid`.`Impuesto_8`) AS `Impuesto_8`,
    sum(`vid`.`Impuesto_13`) AS `Impuesto_13`
from
    `view_iva_details` `vid`
group by
    `vid`.`Nombre_Actividad`,
    `vid`.`Tarifa`,
    `vid`.`Categoria_MH`,
    `vid`.`Mes`;

    create view view_total_exps as
select `expenses`.`id_company`       AS `COMPANIA`,
       year(`expenses`.`created_at`) AS `ANO`,
       sum(case
               when `expense_details`.`id_product` is not null then case
                                                                                                   when `expenses`.`type_doc` = 3
                                                                                                       then `expense_details`.`subtotal` * -1
                                                                                                   else `expense_details`.`subtotal` end
               else 0 end)                                      AS `COMPRAS`,
       sum(case
               when `expenses`.`type_doc` <> 11 and
                    `expense_details`.`id_product` is not null then case
                                                                                                   when `expenses`.`type_doc` = 3
                                                                                                       then `expense_details`.`subtotal` * -1
                                                                                                   else `expense_details`.`subtotal` end
               else 0 end)                                      AS `COMPRAS_TRIBUTADAS`,
       sum(case
               when `expenses`.`type_doc` = 11 and
                    `expense_details`.`id_product` is not null
                   then `expense_details`.`subtotal`
               else 0 end)                                      AS `COMPRAS_NO_TRIBUTADAS`,
       sum(case
               when `expense_details`.`id_product` is null then case
                                                                                               when `expenses`.`type_doc` = 3
                                                                                                   then `expense_details`.`subtotal` * -1
                                                                                               else `expense_details`.`subtotal` end
               else 0 end)                                      AS `GASTOS`,
       sum(case
               when `expenses`.`type_doc` <> 11 and
                    `expense_details`.`id_product` is null then case
                                                                                               when `expenses`.`type_doc` = 3
                                                                                                   then `expense_details`.`subtotal` * -1
                                                                                               else `expense_details`.`subtotal` end
               else 0 end)                                      AS `GASTOS_TRIBUTADOS`,
       sum(case
               when `expenses`.`type_doc` = 11 and
                    `expense_details`.`id_product` is null
                   then `expense_details`.`subtotal`
               else 0 end)                                      AS `GASTOS_NO_TRIBUTADOS`
from (`expenses` join `expense_details`
      on (`expenses`.`id` = `expense_details`.`id_expense`))
where (`expenses`.`state` = 'aceptado' or
       `expenses`.`state` = 'Ninguno')
  and (`expenses`.`condition` = 'aceptado' or
       `expenses`.`condition` = 'guardado' or
       `expenses`.`condition` = 'Ninguna')
  and `expenses`.`type_doc` <> '00'
group by `expenses`.`id_company`, year(`expenses`.`created_at`);


create view view_total_sales as
select `documents`.`id_company`                       AS `COMPANIA`,
       year(`documents`.`created_at`)                 AS `ANO`,
       sum(case
               when `documents`.`type_doc` = 3
                   then `documents`.`total_document` * -1
               else `documents`.`total_document` end) AS `VENTAS`,
       sum(case
               when `documents`.`type_doc` <> 11 then case
                                                                                     when `documents`.`type_doc` = 3
                                                                                         then `documents`.`total_document` * -1
                                                                                     else `documents`.`total_document` end
               else 0 end)                                                       AS `TRIBUTADAS`,
       sum(case
               when `documents`.`type_doc` = 11
                   then `documents`.`total_document`
               else 0 end)                                                       AS `INTERNAS`
from `documents`
where (`documents`.`answer_mh` = 'aceptado' or
       `documents`.`answer_mh` = 'Ninguna')
  and `documents`.`type_doc` <> '00'
  and `documents`.`type_doc` <> '99'
group by `documents`.`id_company`, year(`documents`.`created_at`);

