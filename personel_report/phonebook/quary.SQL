SELECT 
    DATE(n2_date(created_at)) AS date, 
    COUNT(id) AS record_count 
FROM 
    prc_db_phonebook_emidco 
GROUP BY 
    DATE(n2_date(created_at))
ORDER BY 
    date ASC;