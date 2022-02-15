<?php 

include_once "helperfunctions.php";
if (!isset($_COOKIE["user"])) {
    redirect("login.php");
}

  function createHeader() {
    return '
    <header id="navbar">
        <nav class="navbar-container">
        <a href="dashboard.php" class="home-link">
            <div class="navbar-logo"></div>
            Jirra2
        </a>
        <div id="navbar-menu" class="detached">
            <ul class="navbar-links">
            <li><a href="editproject.php">Create Project</a></li>
            <li><a class="navbar-link" href="export.php">Export</a></li>
            <li><a class="navbar-link" href="logout.php">Log Out</a></li>
            </ul>
        </div>
        </nav>
    </header>
    ';
  }
