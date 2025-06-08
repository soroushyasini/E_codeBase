WITH RECURSIVE Numbers AS (
    SELECT 1 AS n
    UNION ALL
    SELECT n + 1
    FROM Numbers
    WHERE n < 200
),
SplitNames AS (
    SELECT 
        form_date, 
        shift, 
        drill_amount, 
        Gamane_name,
        Projects,
        TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(person_data, ',', n.n), ',', -1)) AS person
    FROM (
        SELECT form_date, shift, drill_amount, Gamane_name, Projects, sarparast AS person_data FROM prc_db_gozaresh_ruzane_copy2
        UNION ALL
        SELECT form_date, shift, drill_amount, Gamane_name, Projects, negahban FROM prc_db_gozaresh_ruzane_copy2
        UNION ALL
        SELECT form_date, shift, drill_amount, Gamane_name, Projects, zaminshenas FROM prc_db_gozaresh_ruzane_copy2
        UNION ALL
        SELECT form_date, shift, drill_amount, Gamane_name, Projects, driver FROM prc_db_gozaresh_ruzane_copy2
        UNION ALL
        SELECT form_date, shift, drill_amount, Gamane_name, Projects, sar_haffar FROM prc_db_gozaresh_ruzane_copy2
        UNION ALL
        SELECT form_date, shift, drill_amount, Gamane_name, Projects, haffar FROM prc_db_gozaresh_ruzane_copy2
        UNION ALL
        SELECT form_date, shift, drill_amount, Gamane_name, Projects, komak_haffar FROM prc_db_gozaresh_ruzane_copy2
        UNION ALL
        SELECT form_date, shift, drill_amount, Gamane_name, Projects, kargar FROM prc_db_gozaresh_ruzane_copy2
    ) t
    JOIN Numbers n ON n.n <= (LENGTH(person_data) - LENGTH(REPLACE(person_data, ',', '')) + 1)
    WHERE person_data IS NOT NULL AND TRIM(person_data) != ''
),
AggregatedData AS (
    SELECT 
        form_date, 
        Gamane_name, 
        Projects,
        person,
        ROUND(SUM(CASE WHEN shift = 'DAY' THEN drill_amount ELSE 0 END), 1) AS day_drill_amount,
        ROUND(SUM(CASE WHEN shift = 'NIGHT' THEN drill_amount ELSE 0 END), 1) AS night_drill_amount
    FROM SplitNames
    WHERE person IS NOT NULL AND person != ''
    GROUP BY form_date, Gamane_name, Projects, person
),
JsonData AS (
    SELECT 
        form_date, 
        Gamane_name,
        Projects,
        JSON_ARRAYAGG(
            JSON_OBJECT(
                'person', person,
                'drill_night', night_drill_amount,
                'drill_day', day_drill_amount
            )
        ) AS persons
    FROM AggregatedData
    WHERE form_date LIKE "1403-10%"
    GROUP BY form_date, Gamane_name, Projects
)
SELECT JSON_ARRAYAGG(
    JSON_OBJECT(
        'form_date', form_date,
        'Gamane_name', Gamane_name,
        'Projects', Projects,
        'persons', persons
    )
) AS json_result
FROM JsonData;