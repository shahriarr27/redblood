<?php include('header.php'); ?>
<?php 
   ob_start();
   session_start();
   if($_SESSION['name'] != 'loginsession'){
      header('location: index.php');
   }
   
?>

   <?php 
   
   if(isset($_POST['search_blood'])){
     try{
       if(empty($_POST['blood_group'])||empty($_POST['search_address'])){
         throw new Exception('Please give the required informations!');
       }
       $post_time = time();
        $query = $db->prepare("INSERT INTO timeline(member_id,sb_group,s_address,`timestamp`) VALUES(?,?,?,?)");
        $result = $query->execute(array($_SESSION['id'],$_POST['blood_group'],$_POST['search_address'],$post_time));

        if($result){
          header('location: timeline.php');
        }
     }
     catch(Exception $e){
        $error_message = $e->getMessage();
      }
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
          <li class="nav-item active">
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
        <?php
         $query = $db->prepare("SELECT * FROM memberships WHERE member_id=? ");
          $query->execute(array($_SESSION['id']));
          while($result  = $query->fetch(PDO::FETCH_BOTH)){
            $dp = $result['dp'];
          ?>
          <img src="uploads/<?php echo $result['dp']; ?>" class="img-fluid" alt=""><a href="logout.php">Log Out</a>
          <?php 
          } ?>
        </div>
      </div>
      <div id="search-blood">
        <h4>Create a post for searching blood donors</h4>
        <div class="search-form">
        <?php 
          if(isset($error_message)){echo '<p class="error text-danger text-center" style="font-size: 13px; margin-bottom:10px">'.$error_message.'</p>';};
        ?>
          <form action="" method="POST">
            <label for="">You are searching for</label>
            <select name="blood_group" id="bg" class="form-control">
              <option value="default" selected>Select Group</option>
              <option value="a+">A(+ve)</option>
              <option value="o+">O(+ve)</option>
              <option value="ab+">AB(+ve)</option>
              <option value="o-">O(-ve)</option>
              <option value="a-">A(-ve)</option>
            </select>
            <label for="">You are from</label>
            <input type="text" name="search_address" class="form-control">
            <br>
            <input type="submit" name="search_blood" value="Create Post" class="btn btn-md btn-danger">
          </form>
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