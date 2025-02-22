-- for project selection Drop down :
SELECT CONVERT(id, CHAR) AS id_string, name FROM projects;
-- for gamaneh selection menu : 
SELECT name, name FROM gamaneh
WHERE project_id = "@#Projects" ORDER BY name;









