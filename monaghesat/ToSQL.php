<?
$akhz = @@akhz;
$alarm = @@alarm;
$app_uid = uniqid();
$dastgahejrai = @@dastgahejrai;
$deadline_asnad = @@deadline_asnad;
$deadline_pasokh = @@deadline_pasokh;
$mablagh = @@mablagh;
$mozayede_shomare = @@mozayede_shomare;
$nahve_sherkat = @@nahve_sherkat;
$name = @@name;
$tahvil_bar = @@tahvil_bar;
$type = @@type;
$zemanat_nameh = @@zemanat_nameh;
$zemanat_nameh_2 = @@zemanat_nameh_2;

// Insert the feedback into the database using APP_UID as the identifier
$insert_query = "INSERT INTO prc_db_mozayedat_monaghesat (app_uid, akhz, alarm, dastgahejrai, deadline_asnad, deadline_pasokh, mablagh, mozayede_shomare, nahve_sherkat, name, tahvil_bar, type, zemanat_nameh, zemanat_nameh_2) values ('$app_uid', '$akhz', '$alarm', '$dastgahejrai', '$deadline_asnad', '$deadline_pasokh', '$mablagh', '$mozayede_shomare', '$nahve_sherkat', '$name', '$tahvil_bar', '$type', '$zemanat_nameh', '$zemanat_nameh_2')";
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
