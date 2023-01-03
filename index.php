<?php include('header.php'); ?>

  <?php 
    if(isset($_POST['memb_form'])){
      try{
        if(empty($_POST['fname'])|| empty($_POST['lname'])|| empty($_POST['age'])|| empty($_POST['weight'])|| empty($_POST['email'])|| empty($_POST['password'])|| empty($_POST['bgroup'])|| empty($_POST['reg_type'])|| empty($_POST['address'])|| empty($_FILES['file-1'])){
          throw new Exception('Please give the required informations!');
       }
       $fname = $_POST['fname'];
       $lname = $_POST['lname'];
       $age = $_POST['age'];
       $weight = $_POST['weight'];
       $email = $_POST['email'];
       $password = md5($_POST['password']);
       $bgroup = $_POST['bgroup'];
       $reg_type = $_POST['reg_type'];
       $address = $_POST['address'];
         
       $filename = $_FILES['file-1']['name'];
       $tmpname =  $_FILES['file-1']['tmp_name'];

       $file_basename = substr($filename,0,strpos($filename, '.'));
       $ext_name = substr($filename, strpos($filename, '.'));
       $rand = rand(1,9999);
       $savename = $file_basename.md5($file_basename).$rand.$ext_name;

       if($ext_name != '.png' && $ext_name != '.jpg' && $ext_name != '.jpeg' && $ext_name != '.gif'){
          throw new Exception('Please upload an image file');
       }



       $query = $db->prepare("INSERT INTO memberships(fname,lname,age,`weight`,email,`password`,bgroup,regtype,`address`,dp) VALUES (?,?,?,?,?,?,?,?,?,?)");
         $result = $query->execute(array($fname,$lname,$age,$weight,$email,$password,$bgroup,$reg_type,$address,$savename));

      
        if($result){
          move_uploaded_file($tmpname,'uploads/'.$savename);
          header('location: timeline.php');
        }
      }
      catch(Exception $e){
        if($e->getCode()== 23000){
          $error_message = 'You are already a member, please LogIn';
        }
        else {
          $error_message = $e->getMessage();
        }
      }
    }
  
    
    if(isset($_POST['login_form'])){
      try{
         if(empty($_POST['loginemail'])){
            throw new Exception('Please enter your email');
         }
         if(empty($_POST['loginpassword'])){
            throw new Exception('Please Enter a password');
         }
         $username = $_POST['loginemail'];
         $logpassword = md5($_POST['loginpassword']);
         
         $query = $db->prepare("SELECT * FROM memberships WHERE email =? AND `password` =?");
         $query->execute(array($username,$logpassword));
          
         $result = $query->fetch(PDO::FETCH_BOTH);
            if(($result['email'] == $_POST['loginemail'])&&($result['password'] == $logpassword)){
               session_start();
               $_SESSION['name'] = 'loginsession';
               $_SESSION['id'] = $result['member_id'];
               
               header('location: timeline.php');
            }
            else{
               throw new Exception('Incorrect username or password');
            }
      }
      catch(Exception $e){
         $log_error_message = $e->getMessage();
      }
   }
  
  ?>

    <div id="sideNavbar">
      <div class="navbar">
        <ul class="navbar-nav">
          <li class="nav-item active">
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
        <h2>Welcome to RedBlood</h2>
        <h4>Take or Donate Blood</h4>
      </div>
      <div class="register">
        <div class="row">
          <div class="col-7">
            <div class="sign-up">
              <form action="#" method="POST" class="register-form" enctype="multipart/form-data">
                <div class="form-heading">
                  <h4>Become a Member</h4>
                  <?php 
                    if(isset($error_message)){echo '<p class="error text-danger text-center" style="font-size: 13px; margin-bottom:10px">'.$error_message.'</p>';};
                    if(isset($success_message)){echo '<p class="success text-success text-center" style="font-size: 13px; margin-bottom:10px">'.$success_message.'</p>';}
                  ?>
                </div>

                <div class="row">
                  <div class="col-6">
                    <label for="">First Name</label>
                    <input type="text" name="fname" class="form-control">
                  </div>
                  <div class="col-6">
                    <label for="">Last Name</label>
                    <input type="text" name="lname" class="form-control">
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-6">
                    <label for="">Age</label>
                    <input type="text" name="age" class="form-control">
                  </div>
                  <div class="col-6">
                    <label for="">Weight</label>
                    <input type="text" name="weight" class="form-control">
                  </div>
                </div>

                <div class="row">
                  <div class="col-6">
                    <label for="">Email</label>
                    <input type="email" name="email" class="form-control" required>
                  </div>
                  <div class="col-6">
                    <label for="">Password</label>
                    <input type="password" name="password" class="form-control" required>
                  </div>
                </div>

                <div class="row">
                  <div class="col-6">
                    <label for="bg">Select Blood Group</label><br>
                    <select name="bgroup" id="bg" class="form-control">
                      <option value="default" selected>Select Group</option>
                      <option value="a+">A(+ve)</option>
                      <option value="o+">O(+ve)</option>
                      <option value="ab+">AB(+ve)</option>
                      <option value="o-">O(-ve)</option>
                      <option value="a-">A(-ve)</option>
                    </select>
                  </div>
                  <div class="col-6">
                    <label for="">You are Registering as</label><br>
                    <select name="reg_type" id="uc" class="form-control">
                      <option value="donor" selected>Blood Donor</option>
                      <option value="reciever">Blood Reciever</option>
                    </select>
                  </div>
                </div>

                

                <label for="">Address</label>
                <input type="text"  name="address" class="form-control">

                <label for="">Profile Picture</label><br>
                <div class="image-form">
                      <input type="file" name="file-1" id="file-1" class="inputfile inputfile-1 form-control" data-multiple-caption="{count} files selected" multiple />
                      <label for="file-1" class="form-control mb-3  upload"><i class="fas fa-upload mr-2"></i><span>Choose a file&hellip;</span></label>


                      <!-- <input type="file" name="file-1[]" id="file-1" class="inputfile inputfile-1" data-multiple-caption="{count} files selected" multiple />
                      <label for="file-1"><span>Choose a file&hellip;</span></label> -->
                </div>

                <input type="submit" name="memb_form" value="Submit" class="btn btn-md btn-danger">
              </form>
            </div>
          </div>
          <div class="col-5">
            <div class="login">
              <div class="form-heading">
                <h4>Already a member?</h4>
                <?php if(isset($log_error_message)){echo '<p class="error text-danger text-center" style="font-size: 13px; margin-bottom:10px">'.$log_error_message.'</p>';}; 

                ?>
              </div>
              <div class="login-form-container">
                <form action="" method="POST" class="login-form">
                  <label for="">Email</label>
                  <input type="email" name="loginemail" class="form-control">
                  <label for="">Password</label>
                  <input type="password" name="loginpassword" class="form-control">
                  <br>
                  <input type="submit" value="Log in" name="login_form" class="form-control btn btn-danger">
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
 
<?php include('footer.php'); ?>