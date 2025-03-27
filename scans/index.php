<?php
session_start();

include '../config/config.php';
include '../config/db.php';
$conn = OpenCon();
if (!isset($_SESSION['username'])) {
  header('Location: ' .$base_url . '/index.php');
  die();
}
  ?>
<!doctype html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>Reconn</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/dashboard/">

    

    <!-- Bootstrap core CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    
    <!-- Custom styles for this template -->
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/6c966798cf.js" crossorigin="anonymous"></script>
  </head>
  <body>
    
  <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="../home.php?page=1">Basic Web Reconnaissance</a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="navbar-nav">
    <div class="nav-item text-nowrap">
    <a class="nav-link p-2" href="../functions/logout.php">Log Out <i class="fa-solid fa-power-off p-1"></i></a>
    </div>
  </div>
</header>

<div class="container-fluid">
  <div class="row">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse" style="min-height: calc(100vh - 50px);">
      <div class="position-sticky pt-3">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../index.php">
              <span data-feather="scans"></span>
              Scans
            </a>
          </li>
        </ul>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
        <?php
              $id = $_GET['id'];
              $project_id = $_SESSION['project_id'];
              $sql = "SELECT id, project_owner_id, urls, project_name, nikto, whatweb, wafw00f, testssl, feroxbuster FROM projects WHERE id = ?";
              $stmt = $conn->prepare($sql);
              $stmt->bind_param("i", $id);
              $stmt->execute();
              $result = $stmt->get_result();
              $value = $result->fetch_assoc();
              ?> 
        <p class="h1"><?= htmlspecialchars($value['project_name']); ?></p>
        <hr>
        <p class="h5 text-muted"><em><?= htmlspecialchars($value['urls']); ?></em></p>
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
        <button class="btn btn-sm btn-dark m-1" disabled><i class="fa-solid fa-user"></i> <?= htmlspecialchars($_SESSION['username']); ?></button>
        <a href="../home.php?page=1"><button type="button" class="btn btn-sm btn-outline-success m-1"><i class="fa-solid fa-house"></i> Home</button></a>        
        </div>
      </div>

      <!-- Main Section -->
      <div class="table-responsive">
        <table class="table table-striped table-borderless">
          <thead>
            <tr>
              <th scope="col">Tools</th>
              <th scope="col">Details</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>  


          <?php
            if ($value['nikto'] === 1) {
              $parse_filename_nikto = $result_path . 'nikto_' . $id . '_' . $_SESSION['project_id'] . '.html';
              $nikto_result = @fopen($parse_filename_nikto, "r") or die("Unable to open file!");
              $nikto_file = @fread($nikto_result,filesize($parse_filename_nikto));
              fclose($nikto_result);
              echo '
              <!-- Nikto -->
            <tr>
            <td class="text-center"><p class="fs-6 fw-semibold">Nikto</p></td>
            <!-- Button trigger modal -->
            <td>
            <div class="d-grid gap-2">
            <button type="button" class="btn btn-outline-primary btn-sm align-items-end m-1" data-bs-toggle="modal" data-bs-target="#nikto_modal">Show Detail</button>
          </div>            
            </td>
            <!-- Nikto Modal -->
            <div class="modal fade bd-example-modal-xl" id="nikto_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                  <div class="modal-header" style="background-color: #353839; color: white;">
                    <h5 class="modal-title" id="exampleModalLabel"><p class="fs-4 fw-semibold">Nikto Result</p></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" style="background-color: #353839; color: white;">
                  ' . $nikto_file . '
                  </div>
                  <div class="modal-footer" style="background-color: #353839; color: white;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- Nikto Modal End -->
            
            <td>
            <div class="d-grid gap-2">
            <a href="' . $base_url . '/scan-results/' . 'nikto_' . $id . '_' . $_SESSION['project_id'] . '.html" class="btn btn-outline-secondary btn-sm align-items-end m-1" download><i class="fa-solid fa-file-export"></i> Export</a>
          </div>            
            </td>

            </tr>
            <!-- Nikto End--> 
              ';
            } else {
              echo '';
            }
          ?>                          
            <?php
            if ($value['whatweb'] === 1) {
              $parse_filename_whatweb = $result_path . 'whatweb_' . $id . '_' . $_SESSION['project_id'] .'.html';
              $whatweb_result = @fopen($parse_filename_whatweb, "r") or die("Unable to open file!");
              $whatweb_file = @fread($whatweb_result,filesize($parse_filename_whatweb));
              fclose($whatweb_result);
              echo '
              <!-- Whatweb -->
            <tr>
            <td class="text-center" style="vertical-align: middle;"><p class="fs-6 fw-semibold">Whatweb</p></td>
            <!-- Button trigger modal -->
            <td>
            <div class="d-grid gap-2">
            <button type="button" class="btn btn-outline-primary btn-sm align-items-end m-1" data-bs-toggle="modal" data-bs-target="#whatweb_modal">Show Detail</button>
          </div>            
            </td>
            <!-- Whatweb Modal -->
            <div class="modal fade bd-example-modal-xl" id="whatweb_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                  <div class="modal-header" style="background-color: #353839; color: white;">
                    <h5 class="modal-title" id="exampleModalLabel"><p class="fs-4 fw-semibold p-1 m-1">Whatweb Result</p></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" style="background-color: #353839; color: white;">
                  ' . $whatweb_file . '
                  </div>
                  <div class="modal-footer" style="background-color: #353839; color: white;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- Whatweb Modal End -->
            
            <td>
            <div class="d-grid gap-2">
            <a href="' . $base_url . '/scan-results/' . 'whatweb_' . $id . '_' . $_SESSION['project_id'] . '.html" class="btn btn-outline-secondary btn-sm align-items-end m-1" download><i class="fa-solid fa-file-export"></i> Export</a>
          </div>            
            </td>

            </tr>
            <!-- Whatweb End--> 
              ';
            } else {
              echo '';
            }
          ?>  
            
            <?php
            if ($value['wafw00f'] === 1) {
              $parse_filename_wafw00f = $result_path . 'wafw00f_' . $id . '_' . $_SESSION['project_id'] . '.html';
              $wafw00f_result = @fopen($parse_filename_wafw00f, "r") or die("Unable to open file!");
              $wafw00f_file = @fread($wafw00f_result,filesize($parse_filename_wafw00f));
              fclose($wafw00f_result);
              echo '
              <!-- Wafw00f -->
            <tr>
            <td class="text-center"><p class="fs-6 fw-semibold">Wafw00f</p></td>
            <!-- Button trigger modal -->
            <td>
            <div class="d-grid gap-2">
            <button type="button" class="btn btn-outline-primary btn-sm align-items-end m-1" data-bs-toggle="modal" data-bs-target="#wafw00f_modal">Show Detail</button>
          </div>            
            </td>
            <!-- Wafw00f Modal -->
            <div class="modal fade bd-example-modal-xl" id="wafw00f_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                  <div class="modal-header" style="background-color: #353839; color: white;">
                    <h5 class="modal-title" id="exampleModalLabel"><p class="fs-4 fw-semibold p-1 m-1">Wafw00f Result</p></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" style="background-color: #353839; color: white;">
                      ' . $wafw00f_file . '
                  </div>
                  <div class="modal-footer" style="background-color: #353839; color: white;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- Wafw00f Modal End -->
            
            <td>
            <div class="d-grid gap-2">
            <a href="' . $base_url . '/scan-results/' . 'wafw00f_' . $id . '_' . $_SESSION['project_id'] . '.html" class="btn btn-outline-secondary btn-sm align-items-end m-1" download><i class="fa-solid fa-file-export"></i> Export</a>
          </div>            
            </td>

            </tr>
            <!-- Wafw00f End--> 
              ';
            } else {
              echo '';
            }
          ?>
            
            <?php
            if ($value['testssl'] === 1) {
              $parse_filename_testssl = $result_path . 'testssl_' . $id . '_' . $_SESSION['project_id'] .'.html';
              $testssl_result = @fopen($parse_filename_testssl, "r") or die("Unable to open file!");
              $testssl_file = @fread($testssl_result,filesize($parse_filename_testssl));
              fclose($testssl_result);
              echo '
              <!-- testssl -->
            <tr>
            <td class="text-center"><p class="fs-6 fw-semibold">Testssl</p></td>
            <!-- Button trigger modal -->
            <td>
            <div class="d-grid gap-2">
            <button type="button" class="btn btn-outline-primary btn-sm align-items-end m-1" data-bs-toggle="modal" data-bs-target="#testssl_modal">Show Detail</button>
          </div>            
            </td>
            <!-- testssl Modal -->
            <div class="modal fade bd-example-modal-xl" id="testssl_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                  <div class="modal-header" style="background-color: #353839; color: white;">
                    <h5 class="modal-title" id="exampleModalLabel"><p class="fs-4 fw-semibold p-1 m-1">Testssl Result</p></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" style="background-color: #353839; color: white;">
                  ' . $testssl_file . '
                  </div>
                  <div class="modal-footer" style="background-color: #353839; color: white;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- testssl Modal End -->
            
            <td>
            <div class="d-grid gap-2">
            <a href="' . $base_url . '/scan-results/' . 'testssl_' . $id . '_' . $_SESSION['project_id'] . '.html" class="btn btn-outline-secondary btn-sm align-items-end m-1" download><i class="fa-solid fa-file-export"></i> Export</a>
          </div>            
            </td>

            </tr>
            <!-- testssl End--> 
              ';
            } else {
              echo '';
            }
          ?>
          <?php
            if ($value['feroxbuster'] === 1) {
              $parse_filename_feroxbuster = $result_path . 'feroxbuster_' . $id . '_' . $_SESSION['project_id'] .'.html';
              $feroxbuster_result = @fopen($parse_filename_feroxbuster, "r") or die("Unable to open file!");
              $feroxbuster_file = @fread($feroxbuster_result,filesize($parse_filename_feroxbuster));
              fclose($feroxbuster_result);
              echo '
              <!-- feroxbuster -->
            <tr>
            <td class="text-center"><p class="fs-6 fw-semibold">Feroxbuster</p></td>
            <!-- Button trigger modal -->
            <td>
            <div class="d-grid gap-2">
            <button type="button" class="btn btn-outline-primary btn-sm align-items-end m-1" data-bs-toggle="modal" data-bs-target="#feroxbuster_modal">Show Detail</button>
          </div>            
            </td>
            <!-- feroxbuster Modal -->
            <div class="modal fade bd-example-modal-xl" id="feroxbuster_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                  <div class="modal-header" style="background-color: #353839; color: white;">
                    <h5 class="modal-title" id="exampleModalLabel"><p class="fs-4 fw-semibold p-1 m-1">Feroxbuster Result</p></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" style="background-color: #353839; color: white;">
                  ' . $feroxbuster_file . '
                  </div>
                  <div class="modal-footer" style="background-color: #353839; color: white;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- feroxbuster Modal End -->
            
            <td>
            <div class="d-grid gap-2">
            <a href="' . $base_url . '/scan-results/' . 'feroxbuster_' . $id . '_' . $_SESSION['project_id'] . '.html" class="btn btn-outline-secondary btn-sm align-items-end m-1" download><i class="fa-solid fa-file-export"></i> Export</a>
          </div>            
            </td>

            </tr>
            <!-- feroxbuster End--> 
              ';
            } else {
              echo '';
            }
            $stmt->get_result();
          ?>
          </tbody>
        </table>
        
        <p class="fs-6 text-muted"><em>If the scan result is empty or incomplete, the scan is not finished or an error occured.</em></p>
      </div>
      <!-- Main Section End -->
    </main>
  </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

      <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="dashboard.js"></script>
  </body>
</html>
