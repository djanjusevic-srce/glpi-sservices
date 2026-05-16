INSERT INTO `glpi_plugin_sservices_visibilities`
(`id`, `name`, `comment`)
SELECT *
FROM (

    SELECT 1, 'Javni', ''
    UNION ALL
    SELECT 2, 'Javni (samo CARNet mreža)', ''
    UNION ALL
    SELECT 3, 'Interni', ''
    UNION ALL
    SELECT 4, 'Administratorski', ''

) AS seed
WHERE NOT EXISTS (
    SELECT 1
    FROM `glpi_plugin_sservices_visibilities`
    LIMIT 1
);
