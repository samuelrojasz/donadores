<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(3, "mi_hospitais", $Language->MenuPhrase("3", "MenuText"), "hospitaislist.php", -1, "", AllowListMenu('{A9B917F6-72DB-4C37-BB0D-F508A0EFFBF8}hospitais'), FALSE);
$RootMenu->AddMenuItem(8, "mi_membership_users", $Language->MenuPhrase("8", "MenuText"), "membership_userslist.php", -1, "", AllowListMenu('{A9B917F6-72DB-4C37-BB0D-F508A0EFFBF8}membership_users'), FALSE);
$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
