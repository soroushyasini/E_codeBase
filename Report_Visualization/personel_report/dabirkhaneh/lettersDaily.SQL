SELECT
    JSON_ARRAYAGG(
        JSON_OBJECT(
            'تاریخ',day,
            'نامه ورودی',import_count,
            'نامه خروجی',export_count
        )
    ) as letter_data
FROM (
  SELECT 
      DATE(insert_date) AS day, -- Extract the date part from the timestamp
      COUNT(CASE WHEN letter_type = 'import' THEN 1 END) AS import_count, -- Count imports
      COUNT(CASE WHEN letter_type = 'export' THEN 1 END) AS export_count  -- Count exports
  FROM 
      auto_letter
  GROUP BY 
      DATE(insert_date) -- Group by the date part
  ORDER BY 
    day )
    AS subquery; -- Optional: Order by date