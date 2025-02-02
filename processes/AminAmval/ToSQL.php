<?
// before get started, we should know column names,
// here is SQL code to do so :
    //SELECT COLUMN_NAME
    //FROM INFORMATION_SCHEMA.COLUMNS
    //WHERE TABLE_SCHEMA = 'your_database_name' AND TABLE_NAME = 'your_table_name';


$grouh = @@grouh;
$makan = @@makan;
$moshakhase = @@moshakhase;
$type = @@type;
$onvan_amval = @@onvan_amval;
$pelak_amval = @@pelak_amval;
$sanad = @@sanad;
$tahvil_girande = @@tahvil_girande;
$tarikh = @@tarikh;
$tedad = @@tedad;
$vasziyat_estefade = @@vasziyat_estefade;
$sherkat = @@sherkat;

$insert_query = "INSERT INTO prc_db_amin_amval (grouh, onvan_amval, tedad, type, moshakhase, makan, sanad, tarikh, pelak_amval, vasziyat_estefade, tahvil_girande ) 
                 values ('$grouh', '$onvan_amval', '$tedad', '$type', '$moshakhase', '$makan', '$sanad', DATE(n2_date('$tarikh')), '$pelak_amval', '$vasziyat_estefade', '$tahvil_girande')";
executeQuery($insert_query);
// Clear the session variables
//unset(@@APP_UID);
unset(@@grouh);
unset(@@makan);
unset(@@moshakhase);
unset(@@onvan_amval);
unset(@@pelak_amval);
unset(@@sanad);
unset(@@tahvil_girande);
unset(@@tarikh);
unset(@@tedad);
unset(@@vasziyat_estefade);
