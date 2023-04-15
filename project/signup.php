<!---
    This page signs user up for a project.
-->

<?php session_start(); ?>
<?php
// Include config file
require_once "../config.php";

if (isset($_GET['id'])) {
    if((isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === true)){
        $project_name = $message = "";

        $sql = "SELECT id, name FROM projects WHERE id = ?";

        if($stmt = mysqli_prepare($c, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            $param_id = $_GET['id'];

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                $result = mysqli_stmt_get_result($stmt);
                $value = mysqli_fetch_object($result);

                $project_name = $value->name;
                $project_id = $value->id;
                mysqli_stmt_close($stmt);
                
                // Prepare a select statement
                $sql = 'SELECT id FROM user_has_project WHERE project_id = ? AND role = ? AND user_id = ?';
                
                if($stmt = mysqli_prepare($c, $sql)){
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "isi", $param_project_id, $param_role, $param_user_id);
                    
                    // Set parameters
                    $param_project_id = $project_id;
                    $param_role = "member";
                    $param_user_id = $_SESSION["id"];
                    
                    // Attempt to execute the prepared statement
                    if(mysqli_stmt_execute($stmt)){
                        /* store result */
                        mysqli_stmt_store_result($stmt);
                        
                        if(mysqli_stmt_num_rows($stmt) == 1){
                            $message = '<h2>Error</h2> <p>You are already signed up for project "'. $project_name .'".</p>';
                        } else{
                            // Prepare an insert statement
                            $sql = "INSERT INTO user_has_project (user_id, project_id, role) VALUES (?, ?, ?)";

                            if($stmt = mysqli_prepare($c, $sql)){
                                // Bind variables to the prepared statement as parameters
                                mysqli_stmt_bind_param($stmt, "iis", $param_user_id, $param_project_id, $param_role);
                                
                                // Set parameters
                                $param_user_id = $_SESSION["id"];
                                $param_project_id = $project_id; // Creates a password hash
                                $param_role = "member";
                                    
                                // Attempt to execute the prepared statement
                                if(!mysqli_stmt_execute($stmt)){ 
                                    echo "It seems like we are having issues. Please try again later!";
                                }else{
                                    $message = "<h2>Success</h2> <p>You have successfully signed up for project " . $project_name . ".</p>";
                                }
                            }

                        }
                    } else{
                        echo "It seems like we are having issues. Please try again later!";
                    }

                    // Close statement
                    mysqli_stmt_close($stmt);
                }

            } else{
                header("location: ../index.php");
            }
        }else{
            header("location: ../index.php");
        }
    }else{
        $message = "<h2>Error</h2> <p>You need to be logged in to sign up for a project. <a href=\"../students/signup.php\">Click here to register an account</a>.</p>";
    }
} else {
    // Fallback behaviour
    header("location: ../index.php");
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


    <title>Community Connectors - Project Signup</title>
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
        <?php echo $message; ?>
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