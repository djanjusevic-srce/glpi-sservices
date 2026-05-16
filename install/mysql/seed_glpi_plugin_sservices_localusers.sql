INSERT INTO `glpi_plugin_sservices_localusers`
(`id`, `name`, `comment`)
SELECT *
FROM (

    SELECT 1, 'root', ''
    UNION ALL SELECT 2, 'apache', ''
    UNION ALL SELECT 3, 'www-data', ''
    UNION ALL SELECT 4, 'postfix', ''
    UNION ALL SELECT 5, 'dovecot', ''
    UNION ALL SELECT 6, 'globus', ''
    UNION ALL SELECT 7, 'nobody', ''
    UNION ALL SELECT 8, 'mysql', ''
    UNION ALL SELECT 9, 'bind', ''
    UNION ALL SELECT 10, 'openldap', ''
    UNION ALL SELECT 11, 'aosi', ''
    UNION ALL SELECT 12, 'zabbix', ''
    UNION ALL SELECT 13, 'postgres', ''
    UNION ALL SELECT 14, 'orion', ''
    UNION ALL SELECT 15, 'horvat', ''
    UNION ALL SELECT 16, 'jboss', ''
    UNION ALL SELECT 17, 'freerad', ''
    UNION ALL SELECT 18, 'openca', ''
    UNION ALL SELECT 19, 'asterisk', ''
    UNION ALL SELECT 20, 'informix', ''
    UNION ALL SELECT 21, 'daemon', ''
    UNION ALL SELECT 22, 'ntp', ''
    UNION ALL SELECT 23, 'tomcat', ''
    UNION ALL SELECT 24, 'named', ''
    UNION ALL SELECT 25, 'activemq', ''
) AS seed
WHERE NOT EXISTS (
    SELECT 1
    FROM `glpi_plugin_sservices_localusers`
    LIMIT 1
);
