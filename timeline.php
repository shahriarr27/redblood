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
          <li class="nav-item active">
            <a href="timeline.php" class="nav-link"><img src="images/icons/007-timeline-3.png" alt=""><br><span>Timeline</span></a>
          </li>
          <li class="nav-item">
            <a href="search.php" class="nav-link"><img src="images/icons/008-blood.png" alt=""><br><span>Search</span></a>
          </li>
          <li class="nav-item"><a href="donate.php" class="nav-link">
              <img src="images/icons/011-blood-donation.png" alt=""><br><span>Donate</span></a>
          </li>
          <li class="nav-item">
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
      <div id="timeline">
        <div class="time-head">
          <h4>Recent Activites by members</h4>
        </div>
        <div class="posts">
          <?php 
            $query = $db->prepare("SELECT * FROM timeline order by `timestamp` DESC");
            $query->execute();
            $result  = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach($result as $row){ 
              $time = $row['timestamp'];
              $date = new DateTime("@$time");
              $final_time = $date->format('d/m/Y H:i a');

              $query = $db->prepare("SELECT * FROM memberships WHERE member_id=? ");
              $query->execute(array($row['member_id']));
              $search_result  = $query->fetchAll(PDO::FETCH_ASSOC);
              foreach($search_result as $search_row){  
          ?>
          <?php if($row['sb_group'] != '' && $row['s_address'] != ''){ ?>
              <div class="post finder-post">
                <div class="post-description">
                  <img src="uploads/<?php echo $search_row['dp']; ?>" alt="DP">
                  <p><b><?php echo $search_row['fname'].' '.$search_row['lname'] ?></b> is searching <b class="text-danger"><?php echo $row['sb_group'] ?></b> Blood. At <?php echo $row['s_address'] ?></p>
                  <p class="post-time"><?php echo $final_time ?></p>
                </div>
                <div class="help">
                  <a href="#" class="btn help-btn"><span class="number">6</span> Interested</a>
                  <a href="#" class="btn help-btn">Contact</a>
                </div>
              </div>
          <?php }; ?>
          <?php if($row['db_group'] != '' && $row['d_address'] != ''){ ?>
            <div class="post donor-post">
              <div class="post-description">
                <img src="uploads/<?php echo $search_row['dp']; ?>" alt="DP">
                <p><b><?php echo $search_row['fname'].' '.$search_row['lname'] ?></b> donated <b class="text-danger"><?php echo $row['db_group'] ?></b> Blood. At <?php echo $row['d_address'] ?></p>
                <p class="post-time"><?php echo $final_time ?></p>
              </div>
              <div class="congrats">
                <a href="#" class="btn help-btn"><span class="number">10</span> Congratulation</a>
                <a href="#" class="btn help-btn">Contact</a>
              </div>
            </div>
          <?php }; ?> 
        <?php }; ?> 
      <?php }; ?> 
          
        </div>
      </div>
    </div>
<?php include('footer.php'); ?>