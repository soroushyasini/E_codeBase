SELECT 
    JSON_ARRAYAGG(
        JSON_OBJECT(
            'date', date, 
            'count', record_count
        )
    ) AS chart_data
FROM (
    SELECT 
        DATE(n2_date(created_at)) AS date, 
        COUNT(id) AS record_count 
    FROM 
        prc_db_phonebook_emidco 
    GROUP BY 
        DATE(n2_date(created_at)) 
    ORDER BY 
        DATE(n2_date(created_at)) ASC 
) AS subquery;



SELECT 
    DATE(n2_date(insert_date)) AS day, -- Extract the date part from the timestamp
    COUNT(CASE WHEN letter_type = 'import' THEN 1 END) AS import_count, -- Count imports
    COUNT(CASE WHEN letter_type = 'export' THEN 1 END) AS export_count  -- Count exports
FROM 
    auto_letter
GROUP BY 
    DATE(n2_date(insert_date)) -- Group by the date part
ORDER BY 
    day; -- Optional: Order by date
