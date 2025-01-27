SELECT CONCAT(
    '(',
    GROUP_CONCAT(
        CONCAT('''', COLUMN_NAME, '''')
        ORDER BY ORDINAL_POSITION
        SEPARATOR ','
    ),
    ')'
) AS column_names
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'your_table_name';