<?php
// Initialize the session
session_start();
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
    <link rel="stylesheet" href="./css/style.css">

    <!-- Custom JS -->
    <script src="./js/parallax.js"></script>

    <title>Community Connectors - Home</title>
  </head>
  <body>

  <nav class="navbar navbar-dark navbar-expand-sm bg-dark fixed-top">
        <div class="container">
            <a href="./" class="navbar-brand">
                <img src="./img/brand.png" alt="" width="30" height="24" class="d-inline-block align-text-top"></img> &nbsp;
                Community Connectors
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>


            <div id="navbarCollapse" class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="./" class="nav-link active">
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./projects.php" class="nav-link active">
                        Open Projects
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./faq.php" class="nav-link active">
                        FAQ
                    </a>
                </li>
                <?php
                if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
                ?>
                    <li class="nav-item">
                        <a href="./company/signup.php" class="nav-link active">
                            Sign Up (Company)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="./students/signup.php" class="nav-link active">
                            Sign Up (Students)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="./login.php" class="nav-link active">
                            Log In
                        </a>
                    </li>
                <?php 
                }else{
                 
                    if((isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === true)){    
                        if($_SESSION["company"] === 1)   {             
                ?>   
                            <li class="nav-item">
                                <a href="./project/create.php" class="nav-link active">
                                    Create Project
                                </a>
                            </li>
                        <?php 
                        }else{
                            ?>
                            <li class="nav-item">
                                <a href="./students/projects.php" class="nav-link active">
                                    My Projects
                                </a>
                            </li>
                            <?php
                        } 
                        ?>
                                <li class="nav-item">
                                    <a href="./logout.php" class="nav-link active">
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
    <div class="d-flex justify-content-center flex-nowrap">
        <div class="header-container">
            <div class="header-item mx-auto mt-2">
                        <h2 class="header-text">Some inspirational short sentence written over the picture and under the company name.</h2>
                            <div class="header-buttons text-center pt-5">
                                <a class="btn btn-outline-dark btn-lg" href="./students/signup.php" role="button">Sign Up (Student)</a>
                                <a class="btn btn-outline-dark btn-lg" href="./company/signup.php" role="button">Sign Up (Company)</a>
                            </div>
            </div>    
        </div>
    </div>

    <div class="container-fluid mb-5">
        <div class="row">
            <div class="col-md-9 offset-md-2 text-left">
                <p class="display-2">Our mission</p>
            </div>
            <div class="col-md-6 offset-md-2">
                    <p class="lead">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas et mi et dui pharetra consequat sed quis quam. Etiam quis neque vitae nisl dignissim aliquam id a nisl. Nunc fermentum arcu quis enim commodo, vel aliquet nibh cursus. Phasellus turpis ante, condimentum quis laoreet nec, bibendum id purus. Etiam ullamcorper erat id mi venenatis dapibus. Nunc tempus convallis tortor, sit amet congue lectus pretium a. Cras ullamcorper dui urna, ac iaculis ipsum venenatis sed.
                    </p>

            </div>
            <div class="col-md-2">
                    <div class="text-center">
                        <a class="btn btn-outline-dark btn-lg" href="./projects.php" role="button">Open Projects</a>
                    </div>
            </div>
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