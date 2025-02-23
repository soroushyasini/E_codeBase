SET @date_filter = '1403-10-%';  -- Change this to '1403-10-%' or any other pattern as needed

SELECT 
    JSON_ARRAYAGG(
        JSON_OBJECT(
            'شخص',person,
            'شیفت روز',day_shift_count,
            'شیفت شب',night_shift_count
        )
    ) as shift_data
FROM (

WITH filtered_data AS (
    SELECT form_date, shift, sarparast, negahban, zaminshenas, driver, sar_haffar, haffar, kargar, komak_haffar, aux_kargar
    FROM prc_db_gozaresh_ruzane_copy2
    WHERE form_date LIKE @date_filter
),
numbers AS (
    SELECT 1 AS n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5
)
SELECT 
    person,
    COUNT(DISTINCT CASE WHEN shift = 'DAY' THEN form_date END) AS day_shift_count,
    COUNT(DISTINCT CASE WHEN shift = 'NIGHT' THEN form_date END) AS night_shift_count
   -- COUNT(DISTINCT form_date) AS total_shift_count
FROM (
    -- For sarparast
    SELECT form_date, shift, TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(sarparast, ',', n), ',', -1)) AS person
    FROM filtered_data
    JOIN numbers
    ON CHAR_LENGTH(sarparast) - CHAR_LENGTH(REPLACE(sarparast, ',', '')) >= n - 1
    WHERE sarparast <> ''

    UNION ALL

    -- For negahban
    SELECT form_date, shift, TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(negahban, ',', n), ',', -1)) AS person
    FROM filtered_data
    JOIN numbers
    ON CHAR_LENGTH(negahban) - CHAR_LENGTH(REPLACE(negahban, ',', '')) >= n - 1
    WHERE negahban <> ''

    UNION ALL

    -- For zaminshenas
    SELECT form_date, shift, TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(zaminshenas, ',', n), ',', -1)) AS person
    FROM filtered_data
    JOIN numbers
    ON CHAR_LENGTH(zaminshenas) - CHAR_LENGTH(REPLACE(zaminshenas, ',', '')) >= n - 1
    WHERE zaminshenas <> ''

    UNION ALL

    -- For driver
    SELECT form_date, shift, TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(driver, ',', n), ',', -1)) AS person
    FROM filtered_data
    JOIN numbers
    ON CHAR_LENGTH(driver) - CHAR_LENGTH(REPLACE(driver, ',', '')) >= n - 1
    WHERE driver <> ''

    UNION ALL

    -- For sar_haffar
    SELECT form_date, shift, TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(sar_haffar, ',', n), ',', -1)) AS person
    FROM filtered_data
    JOIN numbers
    ON CHAR_LENGTH(sar_haffar) - CHAR_LENGTH(REPLACE(sar_haffar, ',', '')) >= n - 1
    WHERE sar_haffar <> ''

    UNION ALL

    -- For haffar
    SELECT form_date, shift, TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(haffar, ',', n), ',', -1)) AS person
    FROM filtered_data
    JOIN numbers
    ON CHAR_LENGTH(haffar) - CHAR_LENGTH(REPLACE(haffar, ',', '')) >= n - 1
    WHERE haffar <> ''

    UNION ALL

    -- For kargar
    SELECT form_date, shift, TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(kargar, ',', n), ',', -1)) AS person
    FROM filtered_data
    JOIN numbers
    ON CHAR_LENGTH(kargar) - CHAR_LENGTH(REPLACE(kargar, ',', '')) >= n - 1
    WHERE kargar <> ''

    UNION ALL

    -- For komak_haffar
    SELECT form_date, shift, TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(komak_haffar, ',', n), ',', -1)) AS person
    FROM filtered_data
    JOIN numbers
    ON CHAR_LENGTH(komak_haffar) - CHAR_LENGTH(REPLACE(komak_haffar, ',', '')) >= n - 1
    WHERE komak_haffar <> ''

    UNION ALL

    -- For aux_kargar
    SELECT form_date, shift, TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(aux_kargar, ',', n), ',', -1)) AS person
    FROM filtered_data
    JOIN numbers
    ON CHAR_LENGTH(aux_kargar) - CHAR_LENGTH(REPLACE(aux_kargar, ',', '')) >= n - 1
    WHERE aux_kargar <> ''
) AS all_persons
GROUP BY person )
    AS subquery;
-- ORDER BY total_shift_count DESC;