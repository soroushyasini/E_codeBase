WITH RECURSIVE Numbers AS (
    -- Generate numbers up to 200 to handle multiple names
    SELECT 1 AS n
    UNION ALL
    SELECT n + 1
    FROM Numbers
    WHERE n < 200
),
SplitNames AS (
    -- Split sarparast names
    SELECT 
        t.form_date, 
        t.shift, 
        t.drill_amount, 
        TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(t.sarparast, ',', n.n), ',', -1)) AS person
    FROM prc_db_gozaresh_ruzane_copy2 t
    JOIN Numbers n
    ON n.n <= (LENGTH(t.sarparast) - LENGTH(REPLACE(t.sarparast, ',', '')) + 1)
    WHERE t.sarparast IS NOT NULL AND TRIM(t.sarparast) != ''
    UNION ALL
    -- Split negahban names
    SELECT 
        t.form_date, 
        t.shift, 
        t.drill_amount, 
        TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(t.negahban, ',', n.n), ',', -1)) AS person
    FROM prc_db_gozaresh_ruzane_copy2 t
    JOIN Numbers n
    ON n.n <= (LENGTH(t.negahban) - LENGTH(REPLACE(t.negahban, ',', '')) + 1)
    WHERE t.negahban IS NOT NULL AND TRIM(t.negahban) != ''
    UNION ALL
    -- Split zaminshenas names
    SELECT 
        t.form_date, 
        t.shift, 
        t.drill_amount, 
        TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(t.zaminshenas, ',', n.n), ',', -1)) AS person
    FROM prc_db_gozaresh_ruzane_copy2 t
    JOIN Numbers n
    ON n.n <= (LENGTH(t.zaminshenas) - LENGTH(REPLACE(t.zaminshenas, ',', '')) + 1)
    WHERE t.zaminshenas IS NOT NULL AND TRIM(t.zaminshenas) != ''
    UNION ALL
    -- Split driver names
    SELECT 
        t.form_date, 
        t.shift, 
        t.drill_amount, 
        TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(t.driver, ',', n.n), ',', -1)) AS person
    FROM prc_db_gozaresh_ruzane_copy2 t
    JOIN Numbers n
    ON n.n <= (LENGTH(t.driver) - LENGTH(REPLACE(t.driver, ',', '')) + 1)
    WHERE t.driver IS NOT NULL AND TRIM(t.driver) != ''
    UNION ALL
    -- Split sar_haffar names
    SELECT 
        t.form_date, 
        t.shift, 
        t.drill_amount, 
        TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(t.sar_haffar, ',', n.n), ',', -1)) AS person
    FROM prc_db_gozaresh_ruzane_copy2 t
    JOIN Numbers n
    ON n.n <= (LENGTH(t.sar_haffar) - LENGTH(REPLACE(t.sar_haffar, ',', '')) + 1)
    WHERE t.sar_haffar IS NOT NULL AND TRIM(t.sar_haffar) != ''
    UNION ALL
    -- Split haffar names
    SELECT 
        t.form_date, 
        t.shift, 
        t.drill_amount, 
        TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(t.haffar, ',', n.n), ',', -1)) AS person
    FROM prc_db_gozaresh_ruzane_copy2 t
    JOIN Numbers n
    ON n.n <= (LENGTH(t.haffar) - LENGTH(REPLACE(t.haffar, ',', '')) + 1)
    WHERE t.haffar IS NOT NULL AND TRIM(t.haffar) != ''
    UNION ALL
    -- Split komak_haffar names
    SELECT 
        t.form_date, 
        t.shift, 
        t.drill_amount, 
        TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(t.komak_haffar, ',', n.n), ',', -1)) AS person
    FROM prc_db_gozaresh_ruzane_copy2 t
    JOIN Numbers n
    ON n.n <= (LENGTH(t.komak_haffar) - LENGTH(REPLACE(t.komak_haffar, ',', '')) + 1)
    WHERE t.komak_haffar IS NOT NULL AND TRIM(t.komak_haffar) != ''
    UNION ALL
    -- Split kargar names
    SELECT 
        t.form_date, 
        t.shift, 
        t.drill_amount, 
        TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(t.kargar, ',', n.n), ',', -1)) AS person
    FROM prc_db_gozaresh_ruzane_copy2 t
    JOIN Numbers n
    ON n.n <= (LENGTH(t.kargar) - LENGTH(REPLACE(t.kargar, ',', '')) + 1)
    WHERE t.kargar IS NOT NULL AND TRIM(t.kargar) != ''
),
AggregatedData AS (
    -- Aggregate drill_amount by form_date, person, and shift
    SELECT 
        form_date,
        person,
        SUM(CASE WHEN shift = 'DAY' THEN drill_amount ELSE 0 END) AS day_drill_amount,
        SUM(CASE WHEN shift = 'NIGHT' THEN drill_amount ELSE 0 END) AS night_drill_amount
    FROM SplitNames
    WHERE person IS NOT NULL AND person != ''
    GROUP BY form_date, person
),
JsonData AS (
    -- Create JSON objects for each person
    SELECT 
        form_date,
        JSON_ARRAYAGG(
            JSON_OBJECT(
                'person', person,
                'night', night_drill_amount,
                'day', day_drill_amount
            )
        ) AS persons
    FROM AggregatedData
    WHERE form_date LIKE '1403-12-%'
    GROUP BY form_date
)
-- Wrap in final JSON array
SELECT 
    JSON_ARRAYAGG(
        JSON_OBJECT(
            'form_date', form_date,
            'persons', persons
        )
    ) AS json_result
FROM JsonData;