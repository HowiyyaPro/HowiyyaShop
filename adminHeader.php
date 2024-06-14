<?php
echo '<style>
 /* Ensure the logo has a fixed width */

.custom-logo {
    width: 100%; /* Set the desired width */
    height: 100%; /* Maintain aspect ratio */
}

/* Style the text to align it with the logo */
.custom-logo-text {
    margin-left: 10px; /* Adjust spacing between logo and text */
    font-size: 1.5em; /* Adjust font size as needed */
    color: red; /* Set the desired color */
    white-space: nowrap; /* Prevent text wrapping */
}

/* Align items in the header properly */
.header .logo {
    display: flex;
    align-items: center;
}

/* Ensure the logo and text are properly aligned */
.logo-wrapper {
    display: flex;
    align-items: center;
}

 </style>';
echo '<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center">
        <div class="logo-wrapper d-flex align-items-center">
          <img src="assets/images/HOWIYYA.png" alt="" class="custom-logo">
          <span class="d-none d-lg-block custom-logo-text" style="color:red;">HOWIYYA SHOP</span>
        </div>
      </a>
     
    </div><!-- End Logo -->
     <i class="bi bi-list toggle-sidebar-btn" style="margin-left: auto"></i>

  </header><!-- End Header -->';


