<?php
echo '
<!-- Sidenav/menu -->
<nav class="w3-sidenav w3-light-grey w3-collapse w3-top" style="z-index:3;width:260px;padding-top: 45px" id="mySidenav">
  <div class="w3-container w3-padding-8">
    <hr>
    <h5>Search Filters</h5>
    <hr>
    <form action="form.asp" target="_blank">
      <p><label><i class="fa fa-calendar-check-o"></i> XXXXXX</label></p>
      <input class="w3-input w3-border" type="text" placeholder="DD MM YYYY" name="CheckIn" required> 
      <p><button class="w3-btn-block w3-green w3-padding w3-left-align" type="submit"><i class="fa fa-search w3-margin-right"></i> Search </button></p>
    </form>
  </div>
  
  <a href="javascript:void(0)" class="w3-padding" onclick="document.getElementById(\'subscribe\').style.display=\'block\'"><i class="fa fa-rss"></i> Subscribe</a>
  <a href="#contact" class="w3-padding-16"><i class="fa fa-envelope"></i> Contact</a>
</nav>
';