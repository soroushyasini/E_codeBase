ALTER TABLE basqul ADD COLUMN time_diff TIME;

UPDATE basqul 
SET time_diff = TIMEDIFF(tarekh_tozin_dovom, tarekh_tozin_aval);


SELECT 
    tarekh_tozin_aval, 
    tarekh_tozin_dovom, 
    time_diff 
FROM basqul;