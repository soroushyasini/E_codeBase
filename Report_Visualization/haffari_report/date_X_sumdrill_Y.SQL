-- code to extract rah chaman data

SELECT 
    JSON_ARRAYAGG(
        JSON_OBJECT(
            'تاریخ', date, 
            'شیفت', shift,
            'عمق شروع', drill_start_flt,
            'عمق پایان', drill_end_flt,
            'میزان حفاری', drill_amount,
          	'گمانه', gamane_name
        )
    ) AS chart_data
FROM (
    SELECT 
        form_date AS date, 
        shift,
        drill_start_flt,
        drill_end_flt,
        drill_amount,
        gamane_name
    FROM 
        prc_db_gozaresh_ruzane 
    WHERE 
        Projects = '1'
    ORDER BY 
        date
) AS subquery;