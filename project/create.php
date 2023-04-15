<!---
    This page handles project creation.
-->

<?php
session_start();
  
// Include config file
require_once "../config.php";
 
// Define variables and initialize with empty values
$name = $description = $workload = $date = $location = "";
$name_err = $description_err = $workload_err = $date_err = $location_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate name
    if(empty(trim($_POST["name"]))){
        $name_err = "Project name field is mandatory.";
    } elseif(strlen(trim($_POST["name"])) < 3){
        $name_err = "Project name needs to have at least 3 characters.";
    }   elseif(strlen(trim($_POST["name"])) > 50){
        $name_err = "Project name can't be longer than 50 characters.";
    } else{
        $name = trim($_POST["name"]);
    }

    // Validate description
    if(empty(trim($_POST["description"]))){
        $description_err = "Description field is mandatory.";
    } elseif(strlen(trim($_POST["description"])) < 20){
        $description_err = "Project description needs to have at least 20 characters.";
    }  elseif(strlen(trim($_POST["description"])) > 256){
        $description_err = "Project description can't be longer than 256 characters.";
    }  else{
        $description = trim($_POST["description"]);
    }
    
    // Validate workload
    if(empty(trim($_POST["workload"]))){
        $workload_err = "Please enter a workload.";     
    } elseif(!preg_match('/\d[hdmy]/', trim($_POST["workload"]))){
        $workload_err = "Please use correct format. Example: 5h";
    } else{
        $workload = trim($_POST["workload"]);
    }
    
    // Validate date
    if(empty(trim($_POST["date"]))){
        $date_err = "Please enter project date.";     
    } else{
        $date = trim($_POST["date"]);
    }

    // Validate location
    if(empty(trim($_POST["location"]))){
        $location_err = "Please enter project location.";     
    } else{
        $location = trim($_POST["location"]);
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($description_err) && empty($workload_err) && empty($date_err) && empty($location_err)){
        
        // Prepare an insert statement (projects table)
        $sql = "INSERT INTO projects (name, description, workload, date, location) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($c, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_name, $param_description, $param_workload, $param_date, $param_location);
            
            // Set parameters
            $param_name = $name;
            $param_description = $description; // Creates a password hash
            $param_workload = $workload;
            $param_date = $date;
            $param_location = $location;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                $project_id = mysqli_stmt_insert_id($stmt);
                // Close statement
                mysqli_stmt_close($stmt);
    
                // Prepare an insert statement (user_has_project)
                $sql = "INSERT INTO user_has_project (user_id, project_id, role) VALUES (?, ?, ?)";
                
                if($stmt = mysqli_prepare($c, $sql)){
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "iis", $param_user_id, $param_project_id, $param_role);
                    
                    // Set parameters
                    $param_user_id = $_SESSION["id"];
                    $param_project_id = $project_id;
                    $param_role = "owner";
                    
                    // Attempt to execute the prepared statement
                    if(mysqli_stmt_execute($stmt)){
                        // Redirect to login page
                        header("location: ../projects.php");
                    } else{
                        echo "It seems like we are having issues. Please try again later!";
                    }
    
                    // Close statement
                    mysqli_stmt_close($stmt);
                }
            } else{
                echo "It seems like we are having issues. Please try again later!";
            }       
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


    <title>Community Connectors - Create Project</title>
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
    <?php  
        if(((isset($_SESSION["company"]) && $_SESSION["company"] === 1)) && (isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === true)){                     
    ?> 
        <h2>Sign Up</h2>
        <p>Please fill form below to create a project.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Project Name</label>
                <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                <span class="invalid-feedback"><?php echo $name_err; ?></span>
            </div> 
            <div class="form-group">
                <label>Project Description</label>
                <textarea name="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $description; ?>" rows="3"></textarea>
                <span class="invalid-feedback"><?php echo $description_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Workload (d=days, h=hours, m=months, y=years)</label>
                <input type="text" name="workload" class="form-control <?php echo (!empty($workload_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $workload; ?>">
                <span class="invalid-feedback"><?php echo $workload_err; ?></span>
            </div>
            <div class="form-group">
                <label>Location</label>
                <input type="text" name="location" class="form-control <?php echo (!empty($location_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $location; ?>">
                <span class="invalid-feedback"><?php echo $location_err; ?></span>
            </div>
            <div class="form-group">
                <label>Date</label>
                <input type="date" id="date" name="date" value="<?php echo $date; ?>" min="<?php echo date("Y-m-d"); ?>" class="form-control <?php echo (!empty($date_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $date_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
        </form>
    <?php  
        }else{                  
    ?> 
        <h2>Error</h2>
        <p>How did you land here? Access denied.</p>
    <?php } ?>
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