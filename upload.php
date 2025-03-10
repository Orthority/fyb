<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $nickname = $_POST['nickname'];
    $state = $_POST['state'];
    $relationship = $_POST['relationship'];
    $hobbies = $_POST['hobbies'];
    $course = $_POST['course'];
    $best_clinical = $_POST['best_clinical'];
    $challenging = $_POST['challenging'];
    $lecturer = $_POST['lecturer'];
    $specialization = $_POST['specialization'];
    $if_not_nursing = $_POST['if_not_nursing'];
    $quote = $_POST['quote'];
    $advice = $_POST['advice'];

    // Handle file upload
    $upload_dir = 'uploads/';
    $image = $_FILES['image'];
    $image_name = $image['name'];
    $image_tmp_name = $image['tmp_name'];
    $image_size = $image['size'];
    $image_error = $image['error'];
    
    // Check file size (max 10MB)
    if ($image_size > 10485760) {
        echo "File is too large. Please upload a file smaller than 10MB.";
        exit;
    }
    
    // Move uploaded file to the upload directory
    $upload_path = $upload_dir . basename($image_name);
    if (move_uploaded_file($image_tmp_name, $upload_path)) {
        // Send email
        $to = 'agbekeomotoso@gmail.com';
        $subject = 'Conquistadors Class of Nursing - Personal Information Submission';
        
        $message = "
            Name: $name\n
            Nickname: $nickname\n
            State of Origin: $state\n
            Relationship Status: $relationship\n
            Hobbies: $hobbies\n
            Favorite Course: $course\n
            Best Clinical Posting: $best_clinical\n
            Most Challenging Level: $challenging\n
            Favourite Lecturer: $lecturer\n
            Specialization of Interest: $specialization\n
            If not nursing, what: $if_not_nursing\n
            Favourite Quote: $quote\n
            Advice to Juniors: $advice\n
        ";

        $headers = "From: no-reply@oau.edu.ng";

        // Attach image
        $attachment = chunk_split(base64_encode(file_get_contents($upload_path)));
        $boundary = md5(time());

        $headers .= "\nMIME-Version: 1.0";
        $headers .= "\nContent-Type: multipart/mixed; boundary=\"$boundary\"";

        $body = "--$boundary\n";
        $body .= "Content-Type: text/plain; charset=ISO-8859-1\n";
        $body .= "Content-Transfer-Encoding: 7bit\n\n";
        $body .= $message;

        $body .= "--$boundary\n";
        $body .= "Content-Type: image/jpeg; name=\"$image_name\"\n";
        $body .= "Content-Transfer-Encoding: base64\n";
        $body .= "Content-Disposition: attachment; filename=\"$image_name\"\n\n";
        $body .= $attachment;
        $body .= "--$boundary--";

        if (mail($to, $subject, $body, $headers)) {
            echo "Your form has been submitted successfully.";
        } else {
            echo "There was an error submitting your form. Please try again.";
        }
    } else {
        echo "Error uploading the image.";
    }
}
?>
