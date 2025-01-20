<?php
// Retrieve the logged-in user's information from the system variable
$USER_LOGGED = @@USER_LOGGED;

// Generate a unique application UID


// Retrieve values from session variables or form inputs
$name = @@name;
$number = @@number;
$dakheli = @@dakheli;
$semat = @@semat;
$contact_person = @@contact_person;
$email = @@email;
$vahed_zirabt = @@vahed_zirabt;
$shakhsiyat = @@shakhsiyat;
$tozihat = @@tozihat;
$cellphone = @@cellphone;
// Prepare the SQL INSERT query
$insert_query = "INSERT INTO prc_db_phonebook_emidco (name, number, dakheli, semat, contact_person, email, vahed_zirabt, shakhsiyat, tozihat, USER_LOGGED, cellphone) VALUES ('$name', '$number', '$dakheli', '$semat', '$contact_person', '$email', '$vahed_zirabt', '$shakhsiyat','$tozihat', '$USER_LOGGED','$cellphone')";

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
unset(@@cellphone);