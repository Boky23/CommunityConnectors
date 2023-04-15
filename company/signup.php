<!---
    This page handles company account signups.
--->

<?php
session_start();
// Include config file
require_once "../config.php";

//This function checks if given string is just numbers/digits
function isDigits(string $s, int $minDigits = 9, int $maxDigits = 14): bool {
    return preg_match('/^[0-9]{'.$minDigits.','.$maxDigits.'}\z/', $s);
}

//This function checks for a valid phone number using ReGex
function isValidTelephoneNumber(string $telephone, int $minDigits = 9, int $maxDigits = 14): bool {
    if (preg_match('/^[+][0-9]/', $telephone)) { //is the first character + followed by a digit
        $count = 1;
        $telephone = str_replace(['+'], '', $telephone, $count); //remove +
    }
    
    //remove white space, dots, hyphens and brackets
    $telephone = str_replace([' ', '.', '-', '(', ')'], '', $telephone); 

    //are we left with digits only?
    return isDigits($telephone, $minDigits, $maxDigits); 
}

//This function normalizes phone number and removes spaces, dots, minuses and parantheses
function normalizeTelephoneNumber(string $telephone): string {
    //remove white space, dots, hyphens and brackets
    $telephone = str_replace([' ', '.', '-', '(', ')'], '', $telephone);
    return $telephone;
}
 
// Define variables and initialize with empty values
$company_name = $about_company = $phone_number = $email = $username = $password = $confirm_password = "";
$company_name_err = $about_company_err = $phone_number_err = $username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate company name
    if(empty(trim($_POST["company_name"]))){
        $email_err = "Company name field is mandatory.";
    } elseif(strlen(trim($_POST["company_name"])) < 2){
        $email_err = "Company name should be longer than one character.";
    } else{
        // Prepare a select statement to get id of a company based on company name, so we can check if the same company name already exists in the database
        $sql = "SELECT id FROM user_has_company WHERE company_name = ?";
        
        if($stmt = mysqli_prepare($c, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_company_name);
            
            // Set parameters
            $param_company_name = trim($_POST["company_name"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $company_name_err = 'That company name is already taken.';
                } else{
                    $company_name = trim($_POST["company_name"]);
                }
            } else{
                echo "It seems like we are having issues. Please try again later!";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate about
    if(empty(trim($_POST["about_company"]))){
        $about_company_err = "Company description field is mandatory.";
    } elseif(strlen(trim($_POST["about_company"])) < 15){
        $about_company_err = "Company description should be at least 15 characters long.";
    }else{
        $about_company = trim($_POST["about_company"]);
    }

    // Validate phone number (simple)
    if(empty(trim($_POST["phone_number"]))){
        $phone_number_err = "Company description field is mandatory.";
    } elseif(!isValidTelephoneNumber(trim($_POST["phone_number"]))){
        $phone_number_err = "Company description should be at least 15 characters long.";
    }else{
        $phone_number = trim($_POST["phone_number"]);
    }

    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Email field is mandatory.";
    } elseif(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
        $email_err = "Please enter valid email.";
    } else{
        // Prepare a select statement to get id of user based on it's email, so we can check if account already exists for given email
        $sql = "SELECT id FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($c, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = 'It seems like you already have an account. Proceed to <a href="../login.php"> login page</a>.';
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "It seems like we are having issues. Please try again later!";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Username field is mandatory.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement to get id of user based on username, so we can check if account with same username already exists
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($c, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken. Please choose another one.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "It seems like we are having issues. Please try again later!";
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
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err && empty($email_err)) && empty($company_name_err) && empty($about_company_err) && empty($phone_number_err)){
        
        // Prepare an insert statement to populate users table
        $sql = "INSERT INTO users (email, username, password, company) VALUES (?, ?, ?, ?)";
        $user_id = null;
        if($stmt = mysqli_prepare($c, $sql)){
            // Bind variables to the prepared statement as parameters (sssi means that we prepare three strings and one integer)
            mysqli_stmt_bind_param($stmt, "sssi", $param_email, $param_username, $param_password, $param_company);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_email = $email;
            $param_company = 1;
                   
            // Attempt to execute the prepared statement
            if(!mysqli_stmt_execute($stmt)){ 
                echo "It seems like we are having issues. Please try again later!";
            }

            // Close statement
            mysqli_stmt_close($stmt);

            $sql_select = "SELECT id FROM users WHERE email = ?";
            //$result = mysqli_query($c, $sql_select);
            if($stmt = mysqli_prepare($c, $sql_select)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_email);
                var_dump($param_email);
                $param_email = $email;

                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Store result
                    $result = mysqli_stmt_get_result($stmt);
                    $value = mysqli_fetch_object($result);

                    $user_id = $value->id;
                } else{
                    echo "It seems like we are having issues. Please try again later!";
                }

                // Close statement
                mysqli_stmt_close($stmt);
            }
        }

        $sql = "INSERT INTO user_has_company (user_id, phone_number, about, company_name) VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($c, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "isss", $user_id, $param_phone_number, $param_about_company, $param_company_name);
            
            // Set parameters
            $param_user_id = $user_id;
            $param_phone_number = normalizeTelephoneNumber($phone_number);
            $param_about_company = $about_company;
            $param_company_name = $company_name;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: ../login.php");
            } else{
                echo "It seems like we are having issues. Please try again later!";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($c);
}
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    
    <!-- FontAwesome Icons CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">

    <!-- Custom JS -->
    <script src="../js/main.js"></script>


    <title>Community Connectors - Company Signup</title>
  </head>
  <body>

  <nav class="navbar navbar-dark navbar-expand-sm bg-dark fixed-top">
        <div class="container">
            <a href="../" class="navbar-brand">
                <img src="../img/brand.png" alt="" width="30" height="24" class="d-inline-block align-text-top"></img> &nbsp;
                Community Connectors
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>


            <div id="navbarCollapse" class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="../" class="nav-link active">
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../projects.php" class="nav-link active">
                        Open Projects
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../faq.php" class="nav-link active">
                        FAQ
                    </a>
                </li>
                <?php
                if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
                ?>
                    <li class="nav-item">
                        <a href="../company/signup.php" class="nav-link active">
                            Sign Up (Company)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../students/signup.php" class="nav-link active">
                            Sign Up (Students)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../login.php" class="nav-link active">
                            Log In
                        </a>
                    </li>
                <?php 
                }else{
                 
                    if((isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === true)){    
                        if($_SESSION["company"] === 1)   {             
                ?>   
                            <li class="nav-item">
                                <a href="../project/create.php" class="nav-link active">
                                    Create Project
                                </a>
                            </li>
                        <?php 
                        }else{
                            ?>
                            <li class="nav-item">
                                <a href="../students/projects.php" class="nav-link active">
                                    My Projects
                                </a>
                            </li>
                            <?php
                        } 
                        ?>
                                <li class="nav-item">
                                    <a href="../logout.php" class="nav-link active">
                                        Logout
                                        </a>
                                </li>
                     <?php
                    }
                }
                  
                ?>
            </ul>
            </div>
        </div>
    </nav>

    <!-- JavaScript/jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
    <div class="box-wrapper mx-auto mt-5">
        <h2>Sign Up</h2>
        <p>Please fill form below to create company account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div> 
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Company Name</label>
                <input type="text" name="company_name" class="form-control <?php echo (!empty($company_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $company_name; ?>">
                <span class="invalid-feedback"><?php echo $company_name_err; ?></span>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone_number" class="form-control <?php echo (!empty($phone_number_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phone_number; ?>">
                <span class="invalid-feedback"><?php echo $phone_number_err; ?></span>
            </div>
            <div class="form-group">
                <label>About your company</label>
                <textarea name="about_company" class="form-control <?php echo (!empty($about_company_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $about_company; ?>" rows="3"></textarea>
                <span class="invalid-feedback"><?php echo $about_company_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>  

   </body>

  <footer class="bg-light text-center text-white">
  <div class="container p-4 pb-0">
    <section class="mb-4">
      <!-- Facebook -->
      <a
        class="btn text-white btn-floating m-1"
        style="background-color: #3b5998;"
        href="#!"
        role="button"
        ><i class="fab fa-facebook-f"></i
      ></a>

      <!-- Twitter -->
      <a
        class="btn text-white btn-floating m-1"
        style="background-color: #55acee;"
        href="#!"
        role="button"
        ><i class="fab fa-twitter"></i
      ></a>

      <!-- Instagram -->
      <a
        class="btn text-white btn-floating m-1"
        style="background-color: #ac2bac;"
        href="#!"
        role="button"
        ><i class="fab fa-instagram"></i
      ></a>

      <!-- LinkedIn -->
      <a
        class="btn text-white btn-floating m-1"
        style="background-color: #0082ca;"
        href="#!"
        role="button"
        ><i class="fab fa-linkedin-in"></i
      ></a>
    </section>
  </div>

  <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
    © 2023 Copyright:
    <a class="text-white" href="/">Community Creators</a>
  </div>

</footer>
</html>