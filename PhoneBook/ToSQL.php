<?php
// Retrieve the logged-in user's information from the system variable
$logged_in_user = @@USER_LOGGED;

// Generate a unique application UID
$app_uid = uniqid();

// Retrieve values from session variables or form inputs
$name = @@name;
$number = @@number;
$dakheli = @@dakheli;
$semat = @@semat;
$contact_person = @@contact_person;
$email = @@email;
$vahed_zirabt = @@vahed_zirabt;
$shakhsiyat = @@shakhsiyat;

// Prepare the SQL INSERT query
$insert_query = "INSERT INTO prc_db_phonebook (app_uid, name, number, dakheli, semat, contact_person, email, vahed_zirabt, shakhsiyat, toziat, inserted_by) 
                 VALUES ('$app_uid', '$name', '$number', '$dakheli', '$semat', '$contact_person', '$email', '$vahed_zirabt', '$shakhsiyat','$toziat', '$logged_in_user')";

// Execute the query
executeQuery($insert_query);

// Clear the session variables
unset(@@name);
unset(@@number);
unset(@@dakheli);
unset(@@semat);
unset(@@contact_person);
unset(@@email);
unset(@@vahed_zirabt);
unset(@@shakhsiyat);
unset(@@tozihat);