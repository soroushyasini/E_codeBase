SELECT 
    JSON_ARRAYAGG(
        JSON_OBJECT(
            'دستگاه اجرایی', Company_Name, 
            'مناقصات', Monaghesat,
            'مزایدات',Mozayedat
        )
    ) AS chart_data
FROM (

SELECT 
    DASTGAHEJRAI AS 'Company_Name',
    SUM(CASE WHEN TYPE = 'مناقصه' THEN 1 ELSE 0 END) AS 'Monaghesat',
    SUM(CASE WHEN TYPE = 'مزایده' THEN 1 ELSE 0 END) AS 'Mozayedat'
FROM 
    prc_db_mozayedat_monaghesat
GROUP BY 
    DASTGAHEJRAI
ORDER BY Mozayedat

) AS subquery;