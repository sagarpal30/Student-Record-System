<?php
include("config.php");

function input_field($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

$pErr="";
if(isset($_POST['register']))
// if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    if(input_field($_POST['ps']) !=  input_field($_POST['cps']))
    {
        $pErr="password should match";
       echo "<script>alert('Password should be match')</script>";
    }
    $userName=input_field($_POST['un']);
    $email=input_field($_POST['ei']);
    $regNo=input_field($_POST["rn"]);
    $userExistQuery= "SELECT * FROM ssignup WHERE username='$userName' OR emailid='$email' OR regno='$regNo'";

    $result=mysqli_query($conn,$userExistQuery);



    if($result)
    {
        if(mysqli_num_rows($result)>0)
        {
            $resultFetch=mysqli_fetch_assoc($result);

            if($resultFetch['emailid']==input_field($_POST['ei']))
            {
                echo "<script>alert('$resultFetch[emailid] - emailid already taken')</script>";
            }
            elseif($resultFetch['regno']==input_field($_POST['rn']))
            {
                echo "<script>alert('$resultFetch[regno] - Registration No. already taken')</script>";
            }
            else
            {
                echo "<script>alert('$resultFetch[username] - username already taken')</script>";
            }
        }
       
        else if(empty($pErr))
        {
              
            $query="INSERT INTO ssignup(simage,fullname,username,emailid,phoneno,gender,regno,stream,password) VALUES(?,?,?,?,?,?,?,?,?)";
            
            $stmt = mysqli_prepare($conn,$query);

            if($stmt)
            {
                mysqli_stmt_bind_param($stmt, "sssssssss",$folder,$fullName,$userName,$email,$phonNo,$gender,$regNo,$stream,$password);

                $filename = $_FILES["uploadfile"]["name"];
                $tempname = $_FILES["uploadfile"]["tmp_name"];
    
                $folder = "simages/".$filename;
        
        
                move_uploaded_file($tempname, $folder);
                $fullName=input_field($_POST["fn"]);
                $userName=input_field($_POST["un"]);
                $email=input_field($_POST["ei"]);
                $phonNo=input_field($_POST["p"]);
                $gender=input_field($_POST["gn"]);
                $regNo=input_field($_POST["rn"]);
                $stream=input_field($_POST["stream"]);
                $password=password_hash($_POST["ps"],PASSWORD_DEFAULT) ;
                $cPassword=input_field($_POST["cps"]);
                if(mysqli_stmt_execute($stmt))
                {    
                    echo "<script>alert('registtation succesfull');
                        
                    </script>";
                }
                else
                {
                    echo "<script>alert('data not registerd');
                    </script>"; 
                }
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($conn);
    }
    // else
    // {
    //     echo "<script>alert('cannot run query or server down');
           
    //         </script>";
    // }
}

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student SignUp</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="titel">Student Registration</div>
        <form action="#" method="POST" enctype="multipart/form-data">
            <div class="user-details">


            <div class="input-box">
                <span class="details">Add Student Image</span>
                <input type="file" placeholder="" name="uploadfile" required>
            </div>

            <div class="input-box">
                <span class="details">Full Name</span>
                <input type="text" placeholder="Enter your name" name="fn" required>
            </div>
            <div class="input-box">
                <span class="details">Username</span>
                <input type="text" placeholder="Enter Username" name="un" required>
            </div>
            <div class="input-box">
                <span class="details">Email</span>
                <input type="email" placeholder="Email Id" name="ei" required>
            </div>
            <div class="input-box">
                <span class="details">Phone No.</span>
                <input type="text" placeholder="Phone Number" name="p" required>
            </div>
<!-- 
            <div class="gender-details">
                <span class="gender-titel">Gender</span>
                 <div class="category">
                    <select name="gn">
                        <option value="">Select</option>
                        
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="otherse">Others</option>
                    </select>
                 </div>
            </div> -->

            <div class="input-box">
                <span class="details">Gender</span>
                <select name="gn">
                    <option value="">Select</option>
                    
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="otherse">Others</option>
                </select>
            </div>

            

            <div class="input-box">
                <span class="details">Registration No.</span>
                <input type="text" placeholder="Registration Number" name="rn" required>
            </div>
            
            
            <div class="input-box">
                <span class="details">Stream</span>
                <select name="stream">
                    <option value="">Select</option>
                    
                    <option value="BCA">BCA</option>
                    <option value="BBA">BBA</option>
                   
                </select>
            </div>
            
            <div class="input-box">
                <span class="details">Password</span>
                <input type="password" placeholder="Password" name="ps" required>
            </div>
            <div class="input-box">
                <span class="details">Confirm Password</span>
                <input type="password" placeholder="Confirm Password" name="cps" required>
            </div>
            </div>
            <div class="button">
                <input type="submit" name="register" value="Sign-up">
            </div>
            <div class="sign">
                <span>already registered? Please Login</span>
                <a href="slogin.php"><input type="button" value="Login"></a>
            </div>
        </form>
    </div>
</body>
</html>