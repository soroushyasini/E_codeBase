<?


$name_darkhastkonande = @@name_darkhastkonande;
$semat = @@semat;
$rozaneh_type = @@rozaneh_type;
$start_date = @@start_date;
$end_date = @@end_date;
$duration = @@duration;
$tozihat = @@tozihat;

$sarparast_nazar = @@sarparast_nazar;
$sarparast_tozihat = @@sarparast_tozihat;
$edari_nazar = @@edari_nazar;
$edari_tozihat = @@edari_tozihat;





// Insert into the database
$query = "INSERT INTO prc_db_morakhasi (name_darkhastkonande, semat, rozaneh_type, start_date,  end_date, duration, tozihat, sarparast_nazar, sarparast_tozihat, edari_nazar, edari_tozihat

) VALUES (
    '$name_darkhastkonande', '$semat', '$rozaneh_type', DATE(n2_date('$start_date')), DATE(n2_date('$end_date')), '$duration', '$tozihat', '$sarparast_nazar',
    '$sarparast_tozihat', '$edari_nazar', '$edari_tozihat'
);";


echo $query; // Debug: Print the query

// Execute the query
executeQuery($query);

// Clear the session variables
unset(@@name_darkhastkonande);
unset(@@semat);
unset(@@rozaneh_type);
unset(@@start_date);
unset(@@end_date);
unset(@@duration);
unset(@@tozihat);
unset(@@sarparast_nazar);
unset(@@sarparast_tozihat);
unset(@@edari_nazar);
unset(@@edari_tozihat);
