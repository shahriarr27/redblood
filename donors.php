<?php include('header.php'); ?>
<?php 
   ob_start();
   session_start();
   if($_SESSION['name'] != 'loginsession'){
      header('location: index.php');
   }
   
?>
    <div id="sideNavbar">
      <div class="navbar">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a href="index.php" class="nav-link"><img src="images/icons/001-home.png" alt=""><br><span>Home</span></a>
          </li>
          <li class="nav-item">
            <a href="timeline.php" class="nav-link"><img src="images/icons/007-timeline-3.png" alt=""><br><span>Timeline</span></a>
          </li>
          <li class="nav-item">
            <a href="search.php" class="nav-link"><img src="images/icons/008-blood.png" alt=""><br><span>Search</span></a>
          </li>
          <li class="nav-item"><a href="donate.php" class="nav-link">
              <img src="images/icons/011-blood-donation.png" alt=""><br><span>Donate</span></a>
          </li>
          <li class="nav-item active">
            <a href="donors.php" class="nav-link"><img src="images/icons/010-donors.png" alt=""><br><span>Donors</span></a>
          </li>
          <li class="nav-item">
            <a href="store.php" class="nav-link"><img src="images/icons/002-pipe.png" alt=""><br><span>Store</span></a>
          </li>
          <li class="nav-item">
            <a href="logout.php" class="nav-link"><img src="images/icons/012-logout.png" alt=""><br><span>Log
                out</span></a>
          </li>
        </ul>
      </div>
    </div>
    <div id="mainContainer">
    <div class="header">
        <div class="text">
          <h2>Welcome to RedBlood</h2>
          <h4>Take or Donate Blood</h4>
        </div>
        <div class="profile">
        <?php $query = $db->prepare("SELECT * FROM memberships WHERE member_id=? ");
          $query->execute(array($_SESSION['id']));
          while($result  = $query->fetch(PDO::FETCH_BOTH)){
            $dp = $result['dp'];
          ?>
          <img src="uploads/<?php echo $result['dp']; ?>" class="img-fluid" alt=""><a href="logout.php">Log Out</a>
          <?php 
          } ?>
        </div>
      </div>
      <div class="donors-profile">
        <div class="donor-header">
          <h4>Our blood donors</h4>
        </div>
        <div class="donors">
          <div class="row">
          <?php 
            $query = $db->prepare("SELECT * FROM memberships ORDER BY member_id DESC ");
            $query->execute();
            $search_result  = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach($search_result as $search_row){  
              if($search_row['regtype']=='donor'){
          ?>
              <div class="col-md-3">
                <div class="profile">
                  <div class="donor-img">
                    <img src="uploads/<?php echo $search_row['dp']; ?>" alt="donor" class="img-fluid">
                  </div>
                  <div class="name-prof">
                    <h4><?php echo $search_row['fname'].' '.$search_row['lname'] ?></h4>
                  </div>
                  <div class="infos">  
                    <p class="group"><b>Group:</b><span class="text-danger text-uppercase"><?php echo $search_row['bgroup'] ?></span></p>
                    <p><b>From:</b><?php echo $search_row['address'] ?></p>
                    <p><b>Last Donation:</b>
                      <?php

                        $query = $db->prepare("SELECT member_id , MAX(`timestamp`) AS max FROM timeline WHERE member_id=?");
                        $query->execute(array($search_row['member_id']));
                        $result  = $query->fetchAll(PDO::FETCH_ASSOC);
                        
                          foreach($result as $row){ 
                            if($row['member_id']){
                              $time = $row['max'];
                              $date = new DateTime("@$time");
                              echo $final_time = $date->format('d/m/Y');
                            }
                            else{
                              echo 'Not Donated Yet';
                            }
                          }
                      ?>
                    </p>
                  </div>
                </div>
              </div>
              <?php }; ?>
            <?php }; ?> 
            
          </div>
        </div>
      </div>
    </div>
  </div>





  <script src="js/jquery-3.4.1.min.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/isotope.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/custom-file-input.js"></script>
  <script src="js/jquery.countup.js"></script>
  <script src="js/jquery.waypoints.min.js"></script>
  <script src="js/custom.js"></script>
</body>

</html>