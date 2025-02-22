CREATE TABLE years (
    year_id INT AUTO_INCREMENT PRIMARY KEY,
    year_value INT NOT NULL UNIQUE
);


INSERT INTO years (year_value) VALUES (1402), (1403);


CREATE TABLE months (
    month_id INT AUTO_INCREMENT PRIMARY KEY,
    month_name VARCHAR(20) NOT NULL UNIQUE
);


INSERT INTO months (month_name) VALUES 
('فروردین'),
('اردیبهشت'),
('خرداد'),
('تیر'),
('مرداد'),
('شهریور'),
('مهر'),
('آبان'),
('آذر'),
('دی'),
('بهمن'),
('اسفند');




------
CREATE TABLE year_month (
    year_id INT,
    month_id INT,
    PRIMARY KEY (year_id, month_id),
    FOREIGN KEY (year_id) REFERENCES years(year_id),
    FOREIGN KEY (month_id) REFERENCES months(month_id)
);


-- Assuming year_id 1 is 1402 and year_id 2 is 1403
INSERT INTO year_month (year_id, month_id)
SELECT y.year_id, m.month_id
FROM years y
CROSS JOIN months m;




SELECT y.year_value, m.month_name
FROM year_month ym
JOIN years y ON ym.year_id = y.year_id
JOIN months m ON ym.month_id = m.month_id
WHERE y.year_value = 1402;