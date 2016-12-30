<?php
echo '
<!-- Navbar (sit on top) -->
<div class="w3-top" style="z-index:4;">
  <ul class="w3-navbar w3-white w3-padding-8 w3-card-2">
    <li class=" w3-wide">
      <a href="index.php" class="w3-margin-left w3-text-indigo">Job Recommender</a>
    </li>
    <!-- Right-sided navbar links. Hide them on small screens -->
    <li class="w3-right w3-hide-small" style="letter-spacing: 2px;">
      <a href="about.php" class="w3-left">About</a>';
if(isset($_SESSION['user']))
  echo '
      <a href="rec.php" class="tablinks w3-left" id="">Recommendations</a>
      <a href="bookmarks.php" class="tablinks w3-left" id="">Bookmarks</a>
      <a href="profile.php" class="tablinks w3-left" id="defaultOpen">Profile</a>
      ';
echo '
      <a href="contact.php" class="w3-left">Contact</a>
      ';
if(isset($_SESSION['user']))
  echo '
      <a href="model/logout.php" class="w3-left w3-margin-right w3-black">Log Out</a>
      ';
else
  echo '
      <a href="register.php" class="w3-left w3-text-red">Register Today!</a>
      <a href="login.php" class="w3-left w3-margin-right w3-black">Log In</a>
      ';
echo '
    </li>
  </ul>
</div>
';