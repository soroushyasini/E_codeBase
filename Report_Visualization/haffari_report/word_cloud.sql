SELECT
    JSON_ARRAYAGG(
        JSON_OBJECT(
            'تاریخ',form_date,
            'شیفت',shift,
            'گزارش',text_sharh_haffari
        )
    ) AS daily_report
FROM (
    SELECT
        form_date,
        shift,
        text_sharh_haffari
    FROM
        prc_db_gozaresh_ruzane_copy2
    ORDER BY
         form_date
    )
    AS subquery;