<?php

@include 'config.php';

if(isset($_POST['submit'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = md5($_POST['password']);
   $cpass = md5($_POST['cpassword']);
   $gender = $_POST['gender'];
   $website = $_POST['website'];
   $accept_terms = $_POST['accept_terms'];
   if($_FILES["image"]["error"] == 4){
      echo
      "<script> alert('Image Does Not Exist'); </script>"
      ;
    }
    else{
      $fileName = $_FILES["image"]["name"];
      $fileSize = $_FILES["image"]["size"];
      $tmpName = $_FILES["image"]["tmp_name"];
  
      $validImageExtension = ['jpg', 'gif', 'png'];
      $imageExtension = explode('.', $fileName);
      $imageExtension = strtolower(end($imageExtension));
      if ( !in_array($imageExtension, $validImageExtension) ){
        echo
        "
        <script>
          alert('Invalid Image Extension must be .jpg , .png or .gif');
        </script>
        ";
      }
      else{
        $image = uniqid();
        $image .= '.' . $imageExtension;
  
        move_uploaded_file($tmpName, 'img/' . $image);
      }
    }
   

   $select = " SELECT * FROM user WHERE email = '$email' && password = '$pass' ";

   $result = mysqli_query($conn, $select);

   if(mysqli_num_rows($result) > 0){

      $error[] = 'user already exist!';

   }else{

      if($pass != $cpass){
         $error[] = 'password not matched!';
      }
      else{
         $insert = "INSERT INTO user(name, email, password, gender, website, image,accept_terms ) VALUES('$name','$email','$pass','$gender','$website','$image','$accept_terms')";
         mysqli_query($conn, $insert);
         header('location:login_form.php');
      }
   }

};


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register form</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<div class="form-container">

   <form action="" method="post" enctype="multipart/form-data">
      <h3>register now</h3>
      <?php
      if(isset($error)){
         foreach($error as $error){
            echo '<span class="error-msg">'.$error.'</span>';
         };
      };
      ?>
      <label for="name" style="float: left;">name</label>
      <input id="name" type="text" name="name" required placeholder="enter your name">
      <label for="email" style="float: left;">email</label>
      <input id="email" type="email" name="email" required placeholder="enter your email">
      <label for="password" style="float: left;">password</label>
      <input id="password" type="password" name="password" required placeholder="enter your password">
      <label for="confirmpassword" style="float: left;">confirm password</label>
      <input id="confirmpassword" type="password" name="cpassword" required placeholder="confirm your password">
      <div style="display: flex;">
      <label for="gender" style="margin-right: 50px;" required>gender</label>
      male <input type="radio" name="gender" value='m'>
      female <input type="radio" name="gender" value='f'>
      </div>
      <label for="website" style="float: left;">website</label>
      <input id="website" type="text" name="website" required placeholder="">

      <label for="image" style="float: left;">profile picture</label>
      <input id="image" type="file" name="image" accept=".jpg, .gif, .png ," >

      <div style="display: flex; float:left;">
      <input type="checkbox" name="accept_terms" value="1" id="" required> I accept terms and condition
      </div>
      <input type="submit" name="submit" value="register now" class="form-btn">
      <p>already have an account? <a href="login_form.php">login now</a></p>
   </form>

</div>

</body>
</html>