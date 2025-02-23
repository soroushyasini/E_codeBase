WITH all_names AS (
    SELECT shift, TRIM(name) AS person
    FROM (
        SELECT shift, 
            SUBSTRING_INDEX(SUBSTRING_INDEX(t.names, ',', n.n), ',', -1) AS name
        FROM (
            SELECT shift, 
                negahban AS names FROM prc_db_gozaresh_ruzane_copy2
            UNION ALL
            SELECT shift, zaminshenas FROM prc_db_gozaresh_ruzane_copy2
            UNION ALL
            SELECT shift, driver FROM prc_db_gozaresh_ruzane_copy2
            UNION ALL
            SELECT shift, sar_haffar FROM prc_db_gozaresh_ruzane_copy2
            UNION ALL
            SELECT shift, haffar FROM prc_db_gozaresh_ruzane_copy2
            UNION ALL
            SELECT shift, kargar FROM prc_db_gozaresh_ruzane_copy2
            UNION ALL
            SELECT shift, komak_haffar FROM prc_db_gozaresh_ruzane_copy2
        ) AS t
        JOIN (SELECT 1 n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 
            UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8) n
        ON CHAR_LENGTH(t.names) - CHAR_LENGTH(REPLACE(t.names, ',', '')) >= n.n - 1
    ) AS split_names
    WHERE name IS NOT NULL AND name <> ''
)
SELECT
    JSON_ARRAYAGG(
        JSON_OBJECT(
            'شخص', person,
            'شیفت شب', night_shifts,
            'شیفت روز', day_shifts
        )
    ) AS chart_data
FROM (
    SELECT person, 
        SUM(shift = 'DAY') AS day_shifts,
        SUM(shift = 'NIGHT') AS night_shifts
    FROM all_names
    GROUP BY person
    ORDER BY person
) AS shift_counts;  -- Added alias here
----------------
UPDATE prc_db_gozaresh_ruzane_copy2
SET haffar = REPLACE(haffar, 'جعفر ذوفی', 'جعفر ذوقی')
WHERE haffar LIKE '%جعفر ذوفی%';
------------------