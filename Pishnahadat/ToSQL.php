<?

$subject = @@subject;
$main_feedback = @@main_feedback;

// Generate a unique identifier for the feedback entry
$feedback_id = uniqid();

// Save the feedback to a new table or anonymous feedback storage
$insert_query = "INSERT INTO prc_db_anonymous_feedback (id, subject, feedback) VALUES ('$feedback_id', '$subject', '$main_feedback')";
executeQuery($insert_query);

// Clear the feedback text from the session
unset(@@subject);
unset(@@main_feedback);
