<?
$akhz = @@akhz;
$alarm = @@alarm;
$app_uid = uniqid();
$dastgahejrai = @@dastgahejrai;
$gregorian_deadline_asnad = @@deadline_asnad; // This should be in Gregorian format as stated
$gregorian_deadline_pasokh = @@deadline_pasokh; // This should be in Gregorian format as stated
$gregorian_insert_date = insert_date;
$mablagh = @@mablagh;
$mozayede_shomare = @@mozayede_shomare;
$nahve_sherkat = @@nahve_sherkat;
$name = @@name;
$tahvil_bar = @@tahvil_bar;
$type = @@type;
$zemanat_nameh = @@zemanat_nameh;
$zemanat_nameh_2 = @@zemanat_nameh_2;
$vahed_marbute = @@vahed_marbute;
$tamin_konnande = @@tamin_konnande;
$category = @@category;
$subcategory = @@subcategory;
$products = @@products;
$haffari_area = @@haffari_area;

// Modify the SQL query to ensure only the date part is stored
$insert_query = "INSERT INTO prc_db_mozayedat_monaghesat (app_uid, akhz, alarm, dastgahejrai, deadline_asnad,
   deadline_pasokh, mablagh, mozayede_shomare, nahve_sherkat,
   name, tahvil_bar, type, zemanat_nameh, zemanat_nameh_2,
   vahed_marbute, tamin_konnande, insert_date, category, subcategory, products, haffari_area) 
VALUES ('$app_uid', '$akhz', '$alarm', '$dastgahejrai', DATE(n2_date('$gregorian_deadline_asnad')), DATE(n2_date('$gregorian_deadline_pasokh')), '$mablagh',
 '$mozayede_shomare', '$nahve_sherkat', '$name', '$tahvil_bar', '$type', '$zemanat_nameh', '$zemanat_nameh_2',
  '$vahed_marbute', '$tamin_konnande', DATE(n2_date('$gregorian_insert_date')),  '$category', '$subcategory', '$products', '$haffari_area'
  )";
executeQuery($insert_query); 

// Clear the session variables
unset(@@akhz);
unset(@@alarm);
unset(@@app_uid);
unset(@@dastgahejrai);
unset(@@deadline_asnad);
unset(@@deadline_pasokh);
unset(@@mablagh);
unset(@@mozayede_shomare);
unset(@@nahve_sherkat);
unset(@@name);
unset(@@tahvil_bar);
unset(@@type);
unset(@@zemanat_nameh);
unset(@@zemanat_nameh_2);
unset(@@vahed_marbute);
unset(@@tamin_konnande);
unset(@@insert_date);
unset(@@subcategory);
unset(@@products);
unset(@@haffari_area);