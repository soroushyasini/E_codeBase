SELECT 
    JSON_ARRAYAGG(
        JSON_OBJECT(
            'tarikh',DATE(n2_date(tarikh)),  -- Convert tarikh to Shamsi using n2_date()
            'shomareh_pelak', shomareh_pelak,
            'kharidar', kharidar,
            'vazn_trily',vazn_aval;
            'vazn_khales', vazn_khales,
            'ranande', ranande,
            'time_diff', TIME_FORMAT(time_diff, '%H:%i:%s')
        )
    ) AS json_result
FROM 
    basqul_data
GROUP BY 
    tarikh;