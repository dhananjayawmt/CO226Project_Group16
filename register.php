<?php 

include("./partials/menu.php");
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$email = $password = $confirm_password = "";
$email_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a email.";
    // } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    //     $email_err = "Invalid email";
    } else{
        // Prepare a select statement
        $sql = "SELECT EMAIL FROM USER WHERE EMAIL = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email is already taken.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($email_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO USER (EMAIL, PASSWORD) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_email, $param_password);
            
            // Set parameters
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 

    <!-- Body Sections begin  -->
    <div class="body">
        <section class="gradient">
            <section class="container">
                <div class="registration section" id="">
                    <h3>Register</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis hic accusamus vel asperiores. Quae, sed?lorem30</p>
                    <div class="registration-form">
                        <div class="form">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <table>
                                    <tr>
                                        <td class="lbl"><label for="module">Select module</label></td>
                                        <td>
                                            <select class="inp" name="module" id="module">
                                                <option value="none">select module</option>
                                                <option value="STD">student</option>
                                                <option value="UGS">undergraduate</option>
                                                <option value="ADM">admin</option>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                                <div class="basic-form" id="basic-form">
                                    <table>
                                        <tr>
                                            <td class="reg-msg">Fil following form to register the system</td>
                                            <td class="btn"><input type="submit" value="register"></td>

                                        </tr>
                                    </table>
                                    <table>
                                        <tr>
                                            <td class="lbl" ><label for="fname">first name</label></td>
                                            <td><input type="text" name="fname" id="fname" class="inp" placeholder=" first name"></td>
                                        </tr>
                                        <tr>
                                            <td class="lbl" ><label for="lname">last name</label></td>
                                            <td><input type="text" name="lname" id="lname" class="inp" placeholder=" last name"></td>
                                        </tr>
                                        <tr>
                                            <td class="lbl" ><label for="email">email</label></td>
                                            <td><input type="email" name="email" id="email" class="inp simple" placeholder=" example@gmail.com" value="<?php echo $email; ?>"></td>
                                        </tr>
                                        <tr>
                                            <td class="lbl" ><label for="telno">TEL-number</label></td>
                                            <td><input type="text" name="telno" id="telno" class="inp" placeholder=" 0713000000"></td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="std" id="std">
                                    <table>
                                        <tr>
                                        <td class="lbl"><label for="schools">Select school</label></td>
                                            <td>
                                                <select class="inp" name="schools" id="schools">
                                                    <option value="none">school</option>
                                                    <?php
                                                        $dbhost = "localhost:3306";
                                                        $dbuser = "root";
                                                        $dbpass = "";
                                                        $db = "Group16Project";
                                                    
                                                        // Create the sql connection
                                                        $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
                                                    
                                                        if (!$conn)
                                                        {
                                                            header("Location: ./error.php");
                                                            exit();
                                                        }
                                                    
                                                        $sqlquery = "SELECT SCHOOL_ID, SCHOOL_NAME FROM SCHOOL";

                                                        //  get the result from the query
                                                        $query = mysqli_query($conn, $sqlquery);

                                                        if (mysqli_num_rows($query) > 0)
                                                        {
                                                            while($row = mysqli_fetch_array($query))
                                                            {
                                                                echo "<option value=" .$row[0] .">" .$row[1] ."</option>";
                                                            }
                                                        }
                                                        else
                                                        {
                                                            echo "<option>student</option>";
                                                        }

                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="lbl" ><label for="bod">birth day</label></td>
                                            <td><input type="text" name="bod" id="bod" class="inp" placeholder=" 2008-01-01"></td>
                                        </tr>
                                        <tr>
                                            <td class="lbl" ><label for="addr">address</label></td>
                                            <td><input type="text" name="addr" id="addr" class="inp" placeholder=" no 71, peradeniya, kandy"></td>
                                        </tr>
                                        <tr>
                                            <td class="lbl" ><label for="grade">grade</label></td>
                                            <td><input type="text" name="grade" id="grade" class="inp" placeholder=" 9 "></td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="ugs" id="ugs">
                                    <table>
                                        <tr>
                                                <td class="lbl" ><label for="reg-no">reg no</label></td>
                                                <td><input type="text" name="reg-no" id="reg-no" class="inp simple" placeholder=" E/18/xxx"></td>
                                            </tr>
                                            <tr>
                                                <td class="lbl" ><label for="ug-department">department</label></td>
                                                <td><input type="text" name="ug-department" id="ug-department" class="inp" placeholder=" department of compute enginnering"></td>
                                            </tr>
                                    </table>
                                </div>

                                <div class="adm" id="adm">
                                    <table>
                                    <tr>
                                                <td class="lbl" ><label for="enroll-no">enrollment no</label></td>
                                                <td><input type="text" name="enroll-no" id="enroll-no" class="inp simple" placeholder=" eng/xx/xxx"></td>
                                            </tr>
                                            <tr>
                                            <td class="lbl" ><label for="adm-department">department</label></td>
                                                <td><input type="text" name="adm-department" id="adm-department" class="inp" placeholder=" department of compute enginnering"></td>
                                            </tr>
                                    </table>
                                </div>
                                <div class="pass" id="pass">
                                    <table>
                                        <tr>
                                            <td for="password" class="lbl" >password</td>
                                            <td><input type="password" class="inp" id="password" name="password" placeholder=" Enter password here" value="<?php echo $password; ?>"></td>
                                        </tr>
                                        <tr>
                                            <td for="confirm_password" class="lbl" >re-password</td>
                                            <td><input type="password" class="inp" id="confirm_password" name="confirm_password" placeholder=" re-enter password here" value="<?php echo $confirm_password; ?>"></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="err">
                                    <table>
                                        <tr><td><span class="lbl invalid"><?php echo $email_err; ?></span></td></tr>
                                        <tr><td><span class="lbl invalid"><?php echo $password_err; ?></span></td></tr>
                                        <tr><td><span class="lbl invalid"><?php echo $confirm_password_err; ?></span></td></tr>
                                    </table>
                                </div>
                            </form>
                            <table>
                            <tr>
                                <td class="msg">If you haven an account</td>
                            </tr>
                            <tr>
                                <td><a class="log-link" href="./login.php">login here</a></td>
                            </tr>
                            </table>
                            <table>
                                <tr>
                                    <td ><a href="https://www.instagram.com/" target="_blank"><img class="contact-img" src="./images/insta.png" alt=""></a></td>
                                    <td ><a href="https://www.facebook.com/" target="_blank"><img class="contact-img" src="./images/fb.png" alt=""></a></td>
                                    <td ><a href="https://web.whatsapp.com/" target="_blank"><img class="contact-img" src="./images/wp.png" alt=""></a></td>
                                    <td ><a href="https://mail.google.com/" target="_blank"><img class="contact-img" src="./images/email.png" alt=""></a></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </div>
    <!-- Body Sections ends  -->

<?php include("./partials/footer.php"); ?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="./js/register.js"></script>