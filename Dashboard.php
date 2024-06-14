<?php
                 session_start();
                 require 'dp.php';
                 global $conn;?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard - HowiyyaShop</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/Admin.css" rel="stylesheet">

</head>

<body>

  <!-- ======= Header ======= -->
  <?php
  include 'adminHeader.php';
  ?>

  <!-- ======= Sidebar ======= -->
  <?php
  include 'adminSideBar.php';
  ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-8">
          <div class="row">

            <!-- Sales Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <?php
                  require 'dp.php';
                  global $conn;
// Fetch number of orders placed today
                  $stmt = $conn->prepare("SELECT COUNT(*) as orders_today FROM `order` WHERE DATE(order_date) = CURDATE()");
                  $stmt->execute();
                  $result = $stmt->get_result();
                  $row = $result->fetch_assoc();
                  $ordersToday = $row['orders_today'];
                  $stmt->close();

                  // Fetch number of orders placed on the previous day
                  $stmt = $conn->prepare("SELECT COUNT(*) as orders_yesterday FROM `order` WHERE DATE(order_date) = CURDATE() - INTERVAL 1 DAY");
                  $stmt->execute();
                  $result = $stmt->get_result();
                  $row = $result->fetch_assoc();
                  $ordersYesterday = $row['orders_yesterday'];
                  $stmt->close();

                  // Calculate the percentage increase
                  if ($ordersYesterday > 0) {
                  $increasePercentage = (($ordersToday - $ordersYesterday) / $ordersYesterday) * 100;
                  } else {
                  $increasePercentage = $ordersToday > 0 ? 100 : 0;
                  }

                  $conn->close();
                  ?>

                  <h5 class="card-title">Sales <span>| Today</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-cart"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?php echo $ordersToday; ?></h6>
                      <span class="text-success small pt-1 fw-bold" style="color: <?php echo $increasePercentage >= 0 ? 'green' : 'red'; ?>">
                          <?php echo $increasePercentage; ?>% <?php echo $increasePercentage >= 0 ? 'increase' : 'decrease'; ?></span>
<!--                        <span class="text-muted small pt-2 ps-1">increase</span>increase-->
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->

            <!-- Revenue Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card revenue-card">

                  <?php
                  require 'dp.php';
                  global $conn;

                  $userName = $_SESSION['UserID']; // Ensure user_name is stored in session

                  // Fetch total revenue for the current month
                  $stmt = $conn->prepare("
                SELECT SUM(o.total_amount) as total_revenue
                FROM `order` o
                WHERE MONTH(o.order_date) = MONTH(CURDATE()) AND YEAR(o.order_date) = YEAR(CURDATE())
               ");
                  $stmt->execute();
                  $result = $stmt->get_result();
                  $totalRevenue = $result->fetch_assoc()['total_revenue'];
                  $stmt->close();

                  // Fetch total revenue for the previous month
                  $stmt = $conn->prepare("
                SELECT SUM(o.total_amount) as previous_revenue
                FROM `order` o
                WHERE MONTH(o.order_date) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(o.order_date) = YEAR(CURDATE())
                 ");
                  $stmt->execute();
                  $result = $stmt->get_result();
                  $previousRevenue = $result->fetch_assoc()['previous_revenue'];
                  $stmt->close();

                  $percentageChange = 0;
                  $changeClass = '';
                  $changeIcon = '';
                  if ($previousRevenue > 0) {
                      $percentageChange = (($totalRevenue - $previousRevenue) / $previousRevenue) * 100;
                      $percentageChange = number_format($percentageChange, 2);
                      $changeClass = $percentageChange >= 0 ? 'text-success' : 'text-danger';
                      $changeIcon = $percentageChange >= 0 ? 'bi bi-arrow-up' : 'bi bi-arrow-down';
                  }

                  $conn->close();
                  ?>
                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Revenue <span>| This Month</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="ps-3">
                      <h6 style="font-size: medium;"><?php echo number_format($totalRevenue, 2); ?></h6>
                      <span class="<?php echo $changeClass; ?> small pt-1 fw-bold"><?php echo $percentageChange; ?>%</span>
                        <span class="text-muted small pt-2 ps-1"><?php echo $percentageChange >= 0 ? 'increase' : 'decrease'; ?></span>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Revenue Card -->

            <!-- Customers Card -->
            <div class="col-xxl-4 col-xl-12">

              <div class="card info-card customers-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Customers <span>| This Year</span></h5>
                    <?php
                    require 'dp.php';
                    global $conn;
                    // Fetch the number of users who have made orders this year and have userlevel 'u'
                    $stmt = $conn->prepare("
                    SELECT COUNT(DISTINCT o.user_name) as user_count 
                    FROM `order` o 
                    JOIN `user` u ON o.user_name = u.user_name 
                    WHERE YEAR(o.order_date) = YEAR(CURDATE()) AND u.user_level = 'u'
                ");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    $userCount = $row['user_count'];
                    $stmt->close();

                    // Fetch the number of users who made orders last year and have userlevel 'u'
                    $stmt = $conn->prepare("
                    SELECT COUNT(DISTINCT o.user_name) as user_count 
                    FROM `order` o 
                    JOIN `user` u ON o.user_name = u.user_name 
                    WHERE YEAR(o.order_date) = YEAR(CURDATE()) - 1 AND u.user_level = 'u'
                ");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    $userCountLastYear = $row['user_count'];
                    $stmt->close();

                    // Calculate the percentage increase or decrease
                    if ($userCountLastYear > 0) {
                        $percentageChange = (($userCount - $userCountLastYear) / $userCountLastYear) * 100;
                    } else {
                        $percentageChange = $userCount > 0 ? 100 : 0;
                    }

                    $conn->close();
                    ?>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-people"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?php echo $userCount; ?></h6>
                      <span class="<?php echo $percentageChange >= 0 ? 'text-success' : 'text-danger'; ?> small pt-1 fw-bold">   <?php echo abs($percentageChange); ?>%</span> <span class="text-muted small pt-2 ps-1"> <?php echo $percentageChange >= 0 ? 'increase' : 'decrease'; ?></span>

                    </div>
                  </div>

                </div>
              </div>

            </div><!-- End Customers Card -->

            <!-- Reports -->
            <div class="col-12">
              <div class="card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Reports <span>/Today</span></h5>
                    <?php

                    require 'dp.php';
                    global $conn;

                    // Fetch sales data for the current day
                    $stmt = $conn->prepare("
                    SELECT HOUR(order_date) as hour, COUNT(order_id) as sales_count, SUM(total_amount) as total_revenue
                    FROM `order`
                    WHERE DATE(order_date) = CURDATE()
                    GROUP BY HOUR(order_date)
                    ORDER BY HOUR(order_date)
                ");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $salesData = [];
                    $revenueData = [];
                    while ($row = $result->fetch_assoc()) {
                        $salesData[] = $row['sales_count'];
                        $revenueData[] = $row['total_revenue'];
                    }
                    $stmt->close();

                    // Fetch customers data for the current day
                    $stmt = $conn->prepare("
                    SELECT HOUR(order_date) as hour, COUNT(DISTINCT user_name) as customer_count
                    FROM `order`
                    WHERE DATE(order_date) = CURDATE()
                    GROUP BY HOUR(order_date)
                    ORDER BY HOUR(order_date)
                ");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $customerData = [];
                    while ($row = $result->fetch_assoc()) {
                        $customerData[] = $row['customer_count'];
                    }
                    $stmt->close();

                    $conn->close();
                    ?>

                  <!-- Line Chart -->
                    <div id="reportsChart"></div>
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            var salesData = <?php echo json_encode($salesData); ?>;
                            var revenueData = <?php echo json_encode($revenueData); ?>;
                            var customerData = <?php echo json_encode($customerData); ?>;
                            new ApexCharts(document.querySelector("#reportsChart"), {
                                series: [{
                                    name: 'Sales',
                                    data: salesData,
                                }, {
                                    name: 'Revenue',
                                    data: revenueData
                                }, {
                                    name: 'Customers',
                                    data: customerData
                                }],
                                chart: {
                                    height: 350,
                                    type: 'area',
                                    toolbar: {
                                        show: false
                                    },
                                },
                                markers: {
                                    size: 4
                                },
                                colors: ['#4154f1', '#2eca6a', '#ff771d'],
                                fill: {
                                    type: "gradient",
                                    gradient: {
                                        shadeIntensity: 1,
                                        opacityFrom: 0.3,
                                        opacityTo: 0.4,
                                        stops: [0, 90, 100]
                                    }
                                },
                                dataLabels: {
                                    enabled: false
                                },
                                stroke: {
                                    curve: 'smooth',
                                    width: 2
                                },
                                xaxis: {
                                    type: 'datetime',
                                    categories: ["2024-06-11T00:00:00.000Z", "2024-06-11T01:00:00.000Z", "2024-06-11T02:00:00.000Z", "2024-06-11T03:00:00.000Z", "2024-06-11T04:00:00.000Z", "2024-06-11T05:00:00.000Z", "2024-06-11T06:00:00.000Z"]
                                },
                                tooltip: {
                                    x: {
                                        format: 'dd/MM/yy HH:mm'
                                    },
                                }
                            }).render();
                        });
                    </script>
                    <!-- End Line Chart -->
                </div>

              </div>
            </div><!-- End Reports -->

            <!-- Recent Sales -->
            <div class="col-12">
              <div class="card recent-sales overflow-auto">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Recent Sales <span>| Today</span></h5>
                    <?php

                    require 'dp.php';
                    global $conn;

                    // Function to get recent sales
                    function getRecentSales($conn) {
                        $stmt = $conn->prepare("
                    SELECT o.order_id, u.name as customer_name, p.product_name, o.total_amount, od.status
                    FROM `order` o
                    JOIN user u ON o.user_name = u.user_name
                    JOIN order_deatails od ON o.order_id = od.order_id
                    JOIN product p ON od.product_id = p.product_id
                    WHERE DATE(o.order_date) = CURDATE()
                    ORDER BY o.order_date DESC
                    LIMIT 5
                ");
                        if (!$stmt) {
                            die("Prepare statement failed: " . $conn->error);
                        }

                        $stmt->execute();
                        $result = $stmt->get_result();
                        if (!$result) {
                            die("Execute statement failed: " . $stmt->error);
                        }

                        $recentSales = $result->fetch_all(MYSQLI_ASSOC);
                        $stmt->close();
                        return $recentSales;
                    }

                    $recentSales = getRecentSales($conn);
                    $conn->close();

                    if (empty($recentSales)) {
                        echo "<p>No recent sales found.</p>";
                    } else {
                        echo "<p>Recent sales found: " . count($recentSales) . "</p>";
                    }
                    ?>
                  <table class="table table-borderless datatable">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Product</th>
                        <th scope="col">Price</th>
                        <th scope="col">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (!empty($recentSales)) {
                        foreach ($recentSales as $sale) {
                            echo '<tr>
                                <th scope="row"><a href="#">#' . htmlspecialchars($sale['order_id']) . '</a></th>
                                <td>' . htmlspecialchars($sale['customer_name']) . '</td>
                                <td><a href="#" class="text-primary">' . htmlspecialchars($sale['product_name']) . '</a></td>
                                <td>$' . htmlspecialchars($sale['total_amount']) . '</td>
                                <td><span class="badge ' . getStatusClass($sale['status']) . '">' . htmlspecialchars($sale['status']) . '</span></td>
                            </tr>';
                        }
                    } else {
                        echo '<tr><td colspan="5">No recent sales found.</td></tr>';
                    }
                    ?>
                    </tbody>
                  </table>

                </div>

              </div>
            </div><!-- End Recent Sales -->
              <?php
              function getStatusClass($status) {
                  switch ($status) {
                      case 'Approved':
                          return 'bg-success';
                      case 'Rejected':
                          return 'bg-danger';
                      default:
                          return 'bg-warning';
                  }
              }
              ?>

            <!-- Top Selling -->
            <div class="col-12">
              <div class="card top-selling overflow-auto">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body pb-0">
                  <h5 class="card-title">Top Selling <span>| Month</span></h5>
                    <?php
                    require 'dp.php';
                    global $conn;

                    function getTopSellingProducts($conn) {
                        $stmt = $conn->prepare("
                        SELECT p.product_id, p.product_name, p.price, i.image_url, SUM(od.quantity) as sold, SUM(od.quantity * p.price) as revenue
                        FROM order_deatails od
                        JOIN `order` o ON od.order_id = o.order_id
                        JOIN product p ON od.product_id = p.product_id
                        LEFT JOIN images_url i ON p.product_id = i.product_id
                        WHERE MONTH(o.order_date) = MONTH(CURDATE()) AND YEAR(o.order_date) = YEAR(CURDATE())
                        GROUP BY p.product_id
                        ORDER BY sold DESC
                        LIMIT 5
                    ");
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $topSellingProducts = $result->fetch_all(MYSQLI_ASSOC);
                        $stmt->close();
                        return $topSellingProducts;
                    }

                    $topSellingProducts = getTopSellingProducts($conn);
                    $conn->close();
                    ?>

                  <table class="table table-borderless">
                    <thead>
                      <tr>
                        <th scope="col">Preview</th>
                        <th scope="col">Product</th>
                        <th scope="col">Price</th>
                        <th scope="col">Sold</th>
                        <th scope="col">Revenue</th>
                      </tr>
                    </thead>
                      <tbody>
                      <?php if (!empty($topSellingProducts)): ?>
                          <?php foreach ($topSellingProducts as $product): ?>
                              <tr>
                                  <th scope="row"><a href="#"><img src="<?= htmlspecialchars($product['image_url']) ?>" alt=""></a></th>
                                  <td><a href="#" class="text-primary fw-bold"><?= htmlspecialchars($product['product_name']) ?></a></td>
                                  <td>$<?= htmlspecialchars(number_format($product['price'], 2)) ?></td>
                                  <td class="fw-bold"><?= htmlspecialchars($product['sold']) ?></td>
                                  <td>$<?= htmlspecialchars(number_format($product['revenue'], 2)) ?></td>
                              </tr>
                          <?php endforeach; ?>
                      <?php else: ?>
                          <tr>
                              <td colspan="5">No top-selling products found for this month.</td>
                          </tr>
                      <?php endif; ?>
                      </tbody>
                  </table>

                </div>

              </div>
            </div><!-- End Top Selling -->

          </div>

            <!-- To-Do List -->
            <div class="col-12">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col col-xl-10">

                        <div class="card" style="border-radius: 15px;">
                            <div class="card-body p-5">

                                <h6 class="mb-3">To Do List</h6>

                                <form id="todo-form" class="d-flex justify-content-center align-items-center mb-4">
                                    <div data-mdb-input-init class="form-outline flex-fill">
                                        <input type="text" id="new-task" class="form-control form-control-lg" />
                                        <label class="form-label" for="new-task">What do you need to do today?</label>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg ms-2">Add</button>
                                </form>

                                <ul id="todo-list" class="list-group mb-0">
                                    <li class="list-group-item d-flex justify-content-between align-items-center border-start-0 border-top-0 border-end-0 border-bottom rounded-0 mb-2">
                                        <div class="d-flex align-items-center">
                                            <input class="form-check-input me-2" type="checkbox" value="" aria-label="..." />
                                            <span> Check the orders </span>
                                        </div>
                                        <a href="#!" class="remove-item" data-mdb-tooltip-init title="Remove item">
                                            <i class="fas fa-times text-primary"></i>
                                        </a>
                                    </li>
                                </ul>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <style>
                .remove-item {
                    display: none;
                }
                .list-group-item.completed .remove-item {
                    display: block;
                }
            </style>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const form = document.getElementById('todo-form');
                    const taskInput = document.getElementById('new-task');
                    const todoList = document.getElementById('todo-list');

                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        addTask(taskInput.value);
                        taskInput.value = '';
                    });

                    function addTask(taskText) {
                        const li = document.createElement('li');
                        li.className = 'list-group-item d-flex justify-content-between align-items-center border-start-0 border-top-0 border-end-0 border-bottom rounded-0 mb-2';

                        const taskDiv = document.createElement('div');
                        taskDiv.className = 'd-flex align-items-center';

                        const checkbox = document.createElement('input');
                        checkbox.className = 'form-check-input me-2';
                        checkbox.type = 'checkbox';
                        checkbox.addEventListener('change', function() {
                            if (checkbox.checked) {
                                taskSpan.style.textDecoration = 'line-through';
                                li.classList.add('completed');
                            } else {
                                taskSpan.style.textDecoration = 'none';
                                li.classList.remove('completed');
                            }
                        });

                        const taskSpan = document.createElement('span');
                        taskSpan.textContent = taskText;

                        taskDiv.appendChild(checkbox);
                        taskDiv.appendChild(taskSpan);

                        const removeLink = document.createElement('a');
                        removeLink.href = '#!';
                        removeLink.className = 'remove-item';
                        removeLink.setAttribute('data-mdb-tooltip-init', '');
                        removeLink.setAttribute('title', 'Remove item');
                        removeLink.innerHTML = '<i class="fas fa-times text-primary"></i>';
                        removeLink.addEventListener('click', function() {
                            todoList.removeChild(li);
                        });

                        li.appendChild(taskDiv);
                        li.appendChild(removeLink);

                        todoList.appendChild(li);
                    }
                });
            </script>





        </div><!-- End Left side columns -->

        <!-- Right side columns -->
        <div class="col-lg-4">

            <?php

            require 'dp.php';
            global $conn;

            function getRecentActivity($conn) {
                $stmt = $conn->prepare("
                SELECT o.order_id, o.order_date, o.total_amount, u.user_name, p.product_name
                FROM `order` o
                JOIN user u ON o.user_name = u.user_name
                JOIN order_deatails od ON o.order_id = od.order_id
                JOIN product p ON od.product_id = p.product_id
                ORDER BY o.order_date DESC
                LIMIT 5
            ");
                $stmt->execute();
                $result = $stmt->get_result();
                $recentActivity = $result->fetch_all(MYSQLI_ASSOC);
                $stmt->close();
                return $recentActivity;
            }

            function timeAgo($time) {
                $time_difference = time() - strtotime($time);
                if ($time_difference < 1 ) { return 'just now'; }
                $condition = array( 12 * 30 * 24 * 60 * 60 =>  'year',
                    30 * 24 * 60 * 60       =>  'month',
                    24 * 60 * 60            =>  'day',
                    60 * 60                 =>  'hour',
                    60                      =>  'minute',
                    1                       =>  'second'
                );
                foreach($condition as $secs => $str) {
                    $d = $time_difference / $secs;
                    if ($d >= 1) {
                        $t = round($d);
                        return $t . ' ' . $str . ($t > 1 ? 's' : '') . ' ago';
                    }
                }
            }

            $recentActivity = getRecentActivity($conn);
            $conn->close();
            ?>

            <!-- Recent Activity -->
            <div class="card">
                <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <li class="dropdown-header text-start">
                            <h6>Filter</h6>
                        </li>
                        <li><a class="dropdown-item" href="#">Today</a></li>
                        <li><a class="dropdown-item" href="#">This Month</a></li>
                        <li><a class="dropdown-item" href="#">This Year</a></li>
                    </ul>
                </div>

                <div class="card-body">
                    <h5 class="card-title">Recent Activity <span>| Today</span></h5>

                    <div class="activity">
                        <?php if (!empty($recentActivity)): ?>
                            <?php foreach ($recentActivity as $activity): ?>
                                <div class="activity-item d-flex">
                                    <div class="activite-label"><?= timeAgo($activity['order_date']) ?></div>
                                    <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                    <div class="activity-content">
                                        Order #<?= htmlspecialchars($activity['order_id']) ?> by <a href="#" class="fw-bold text-dark"><?= htmlspecialchars($activity['user_name']) ?></a> for <?= htmlspecialchars($activity['product_name']) ?>
                                    </div>
                                </div><!-- End activity item-->
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No recent activity found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div><!-- End Recent Activity -->

            <!-- Website Traffic -->
          <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body pb-0">
              <h5 class="card-title">Website Traffic <span>| Today</span></h5>

              <div id="trafficChart" style="min-height: 400px;" class="echart"></div>

              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  echarts.init(document.querySelector("#trafficChart")).setOption({
                    tooltip: {
                      trigger: 'item'
                    },
                    legend: {
                      top: '5%',
                      left: 'center'
                    },
                    series: [{
                      name: 'Access From',
                      type: 'pie',
                      radius: ['40%', '70%'],
                      avoidLabelOverlap: false,
                      label: {
                        show: false,
                        position: 'center'
                      },
                      emphasis: {
                        label: {
                          show: true,
                          fontSize: '18',
                          fontWeight: 'bold'
                        }
                      },
                      labelLine: {
                        show: false
                      },
                      data: [{
                          value: 1048,
                          name: 'Search Engine'
                        },
                        {
                          value: 735,
                          name: 'Direct'
                        },
                        {
                          value: 580,
                          name: 'Email'
                        },
                        {
                          value: 484,
                          name: 'Union Ads'
                        },
                        {
                          value: 300,
                          name: 'Video Ads'
                        }
                      ]
                    }]
                  });
                });
              </script>

            </div>
          </div><!-- End Website Traffic -->

          <!-- News & Updates Traffic -->
            <?php
            require 'dp.php';
            global $conn;

            function getRecentOrders($conn) {
                $stmt = $conn->prepare("
                SELECT o.order_id, o.order_date, o.total_amount, u.user_name, p.product_name, i.image_url
                FROM `order` o
                JOIN user u ON o.user_name = u.user_name
                JOIN order_deatails od ON o.order_id = od.order_id
                JOIN product p ON od.product_id = p.product_id
                LEFT JOIN images_url i ON p.product_id = i.product_id
                ORDER BY o.order_date DESC
                LIMIT 5
            ");
                $stmt->execute();
                $result = $stmt->get_result();
                $recentOrders = $result->fetch_all(MYSQLI_ASSOC);
                $stmt->close();
                return $recentOrders;
            }

            $recentOrders = getRecentOrders($conn);
            $conn->close();
            ?>

            <!-- News & Updates Traffic -->
            <div class="card">
                <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <li class="dropdown-header text-start">
                            <h6>Filter</h6>
                        </li>
                        <li><a class="dropdown-item" href="#">Today</a></li>
                        <li><a class="dropdown-item" href="#">This Month</a></li>
                        <li><a class="dropdown-item" href="#">This Year</a></li>
                    </ul>
                </div>

                <div class="card-body pb-0">
                    <h5 class="card-title">News &amp; Updates <span></span></h5>
                    <div class="news">
                        <?php if (!empty($recentOrders)): ?>
                            <?php foreach ($recentOrders as $order): ?>
                                <div class="post-item clearfix">
                                    <img src="<?= htmlspecialchars($order['image_url']) ?>" alt="">
                                    <h4><a href="#">Order #<?= htmlspecialchars($order['order_id']) ?></a></h4>
                                    <p>
                                        <strong>User:</strong> <?= htmlspecialchars($order['user_name']) ?><br>
                                        <strong>Product:</strong> <?= htmlspecialchars($order['product_name']) ?><br>
                                        <strong>Total:</strong> <?= number_format($order['total_amount'], 2) ?> NIS<br>
                                        <strong>Date:</strong> <?= htmlspecialchars($order['order_date']) ?>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No recent orders found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div><!-- End News & Updates -->


        </div><!-- End Right side columns -->

      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php
  include 'adminFooter.php';
  ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/Admin.js"></script>

</body>

</html>