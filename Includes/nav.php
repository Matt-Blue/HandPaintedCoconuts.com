<!-- full navbar -->
<nav class="navbar navbar-default navbar-fixed-top" style="padding-bottom: -1;">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

      <!--Main Text on Left-->
      <a class="navbar-brand" href="\index.php" id="xlarge">  Hand Painted Coconuts  </a>

    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">

        <!--Left Side of Nav Bar-->
        <li><a href="index.php" id="large"> HOME <span class="sr-only"></span></a></li>
        <li><a href="about.php" id="large"> ABOUT US <span class="sr-only"></span></a></li>
        <li><a href="browse.php" id="large"> BROWSE COCONUTS <span class="sr-only"></span></a></li>
        <li><a href="addons.php" id="large"> ADDONS <span class="sr-only"></span></a></li>
        <?php if(!isset($_SESSION['SBUser'])):?>
          <li><a href="/Admin/signup.php?add=1" id="large"> CREATE AN ACCOUNT <span class="sr-only"></span></a></li>
          <li><a href="/Admin/login.php" id="large"> LOGIN <span class="sr-only"></span></a></li>
        <?php endif ?>
        <?php if(isset($_SESSION['SBUser'])):?>
          <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"id="large"> MANAGE ACCOUNT <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="/Admin/logout.php" id="medium"> LOGOUT <span class="sr-only"></span></a></li>
            <li><a href="/Admin/change_password.php" id="medium"> CHANGE PASSWORD <span class="sr-only"></span></a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#" id="medium">CLOSE</a></li>
          </ul>
        </li>
        <?php endif ?>

        <!--Right Side of Nav Bar-->
        </ul>
            <ul class="nav navbar-nav navbar-right">
            <li><a href="cart.php" title="cart" id="large">CART</a></li>
        </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav> 