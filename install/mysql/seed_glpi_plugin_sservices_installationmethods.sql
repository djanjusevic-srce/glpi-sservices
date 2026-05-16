INSERT INTO `glpi_plugin_sservices_installationmethods`
(`id`, `name`, `comment`)
SELECT *
FROM (
    SELECT 1, 'Source', ''
    UNION ALL
    SELECT 2, 'Package', ''
    UNION ALL
    SELECT 3, 'Binary', ''
) AS seed
WHERE NOT EXISTS (
    SELECT 1
    FROM `glpi_plugin_sservices_installationmethods`
    LIMIT 1
);
