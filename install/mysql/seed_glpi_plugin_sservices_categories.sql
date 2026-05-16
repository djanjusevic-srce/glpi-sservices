INSERT INTO `glpi_plugin_sservices_categories`
(`id`, `name`, `comment`)
SELECT *
FROM (
    SELECT 1, 'AOSI HTTPS', ''
    UNION ALL
    SELECT 2, 'Apache Custom HTTP', ''
    UNION ALL
    SELECT 3, 'Apache Custom HTTPS', ''
    UNION ALL
    SELECT 4, 'Apache HTTP', ''
    UNION ALL
    SELECT 5, 'Apache HTTPS', ''
    UNION ALL
    SELECT 6, 'Apache SOLR', ''
    UNION ALL
    SELECT 7, 'Asterisk PBX', ''
    UNION ALL
    SELECT 8, 'Baza podataka', ''
    UNION ALL
    SELECT 9, 'BDII', ''
    UNION ALL
    SELECT 10, 'Bind DNS', ''
    UNION ALL
    SELECT 11, 'Custom Service', ''
    UNION ALL
    SELECT 12, 'Dovecot IMAP', ''
    UNION ALL
    SELECT 13, 'Dovecot IMAPS', ''
    UNION ALL
    SELECT 14, 'Dovecot POP3', ''
    UNION ALL
    SELECT 15, 'Dovecot POP3S', ''
    UNION ALL
    SELECT 16, 'Elasticsearch', ''
    UNION ALL
    SELECT 17, 'HAProxy', ''
    UNION ALL
    SELECT 18, 'Hazelcast', ''
    UNION ALL
    SELECT 19, 'Hazelcast Management Center', ''
    UNION ALL
    SELECT 20, 'IBM TSM Server', ''
    UNION ALL
    SELECT 21, 'IIS HTTP', ''
    UNION ALL
    SELECT 22, 'IIS HTTPS', ''
    UNION ALL
    SELECT 23, 'Informix', ''
    UNION ALL
    SELECT 24, 'Microsoft RPC', ''
    UNION ALL
    SELECT 25, 'MS Exchange IMAPS', ''
    UNION ALL
    SELECT 26, 'MS Exchange SMTP', ''
    UNION ALL
    SELECT 27, 'MS SMB', ''
    UNION ALL
    SELECT 28, 'MS SQL', ''
    UNION ALL
    SELECT 29, 'MySQL', ''
    UNION ALL
    SELECT 30, 'Net Logon', ''
    UNION ALL
    SELECT 31, 'OpenLDAP LDAP', ''
    UNION ALL
    SELECT 32, 'OpenLDAP LDAPS', ''
    UNION ALL
    SELECT 33, 'OpenSSH SSH', ''
    UNION ALL
    SELECT 34, 'Postfix SMTP', ''
    UNION ALL
    SELECT 35, 'Postfix SMTPS', 'StartTLS enabled SMTP'
    UNION ALL
    SELECT 36, 'Postfix SUBMISSION', ''
    UNION ALL
    SELECT 37, 'PostgreSQL', ''
) AS seed
WHERE NOT EXISTS (
    SELECT 1
    FROM `glpi_plugin_sservices_categories`
    LIMIT 1
);
