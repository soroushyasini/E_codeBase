SELECT 
    DATE(insert_date) AS day, -- Extract the date part from the timestamp
    COUNT(CASE WHEN type = 'import' THEN 1 END) AS import_count, -- Count imports
    COUNT(CASE WHEN type = 'export' THEN 1 END) AS export_count  -- Count exports
FROM 
    your_table_name
GROUP BY 
    DATE(insert_date) -- Group by the date part
ORDER BY 
    day; -- Optional: Order by date