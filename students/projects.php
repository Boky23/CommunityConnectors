<!---
    This page shows all projects that student user is currently subscribed to.
-->

<?php
        session_start(); 
        // Include config file
        require_once "../config.php";
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


    <title>Community Connectors - My Projects</title>
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
                                <a href="./projects.php" class="nav-link active">
                                    My Projects
                                </a>
                            </li>
                            <?php
                        } 
                    }
                }
                ?>
                    <li class="nav-item">
                        <a href="../logout.php" class="nav-link active">
                            Logout
                        </a>
                    </li>
            </ul>
            </div>
        </div>
    </nav>
    <div class="project-box-wrapper mt-5 mx-auto">
        <h1 id="header-padding" class="text-center">Your Projects</h1>
        <div class="table table-responsive">
            <table id="dtBasicExample" class="table table-striped table-bordered table-sm mx-auto text-center" cellspacing="0" width="100%">
            <thead>
                <tr>
                <th class="th-sm">Organisation
                </th>
                <th class="th-sm">Project Description
                </th>
                <th class="th-sm">Workload
                </th>
                <th class="th-sm">More Information
                </th>
                <th class="th-sm"> 
                </tr>
            </thead>
            <tbody>
                <?php

                function create_short_description($string, $limit) 
                {
                    if(strlen($string) > $limit) 
                    {
                        return substr($string, 0, $limit) . "..."; 
                    }
                    else 
                    {
                        return $string;
                    }
                }
                if ((isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === true)) {
                    $sql = "SELECT user_id, project_id FROM user_has_project WHERE user_id = ? and role = ?";
                
                    //$result = $mysqli->query($sql);

                    if($stmt = mysqli_prepare($c, $sql)){
                        mysqli_stmt_bind_param($stmt, "is", $param_id, $param_role);
                        $param_id = $_SESSION['id'];
                        $param_role = "member";
                        if(mysqli_stmt_execute($stmt)){
                            ($stmt_result = $stmt->get_result()) or trigger_error($stmt->error, E_USER_ERROR);
                            while($row = $stmt_result->fetch_assoc()) {
                                $user_id = $row["user_id"];
                                $project_id = $row["project_id"];

                                mysqli_stmt_close($stmt);

                                $sql = "SELECT * FROM projects WHERE id = ?";

                                if($stmt = mysqli_prepare($c, $sql)){
                                    // Bind variables to the prepared statement as parameters
                                    mysqli_stmt_bind_param($stmt, "i", $param_project_id);
                                    $param_project_id = $project_id;
                                    
                                    // Attempt to execute the prepared statement
                                    if(mysqli_stmt_execute($stmt)){
                                        $result = $stmt->get_result(); // get the mysqli result
                                        $project = $result->fetch_object();
                                

                                        echo "<tr>";
                                        echo "<td>" . $project->name . "</td>";
                                        echo "<td>" . create_short_description($project->description, 10) . "</td>";
                                        echo "<td>" . $project->workload . "</td>";
                                        echo '<td> <a role="button" class="btn btn-outline-secondary" href="../project/details.php?id=' . $project_id . '">More Information</a> </td>';
                                        echo '<td> <a role="button" class="btn btn-outline-primary" href="../project/signout.php?id=' . $project_id . '">Sign Out</a> </td>';
                                        echo "</tr>";

                                        mysqli_free_result($result);
                                        mysqli_stmt_close($stmt);
                                    }
                            }
                            }
                    
                        } else{
                            echo "It seems like we are having issues. Please try again later!";
                        }
                    }else{
                        echo "Cant' prepare.";
                    }
                } else {
                    // Fallback behaviour
                    header("location: ../index.php");
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                <th>Organisation
                </th>
                <th>Project Description
                </th>
                <th>Workload
                </th>
                <th>More Information
                </th>
                </tr>
            </tfoot>
            </table>
        </div>
    </div>

    <!-- JavaScript/jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
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