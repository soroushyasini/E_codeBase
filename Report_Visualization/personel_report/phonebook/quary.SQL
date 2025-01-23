SELECT 
    DATE(n2_date(created_at)) AS date, 
    COUNT(id) AS record_count 
FROM 
    prc_db_phonebook_emidco 
GROUP BY 
    DATE(n2_date(created_at))
ORDER BY 
    date ASC;



/// JSON

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