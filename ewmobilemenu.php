<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(3, "mmi_hospitais", $Language->MenuPhrase("3", "MenuText"), "hospitaislist.php", -1, "", AllowListMenu('{A9B917F6-72DB-4C37-BB0D-F508A0EFFBF8}hospitais'), FALSE);
$RootMenu->AddMenuItem(8, "mmi_membership_users", $Language->MenuPhrase("8", "MenuText"), "membership_userslist.php", -1, "", AllowListMenu('{A9B917F6-72DB-4C37-BB0D-F508A0EFFBF8}membership_users'), FALSE);
$RootMenu->AddMenuItem(9, "mmi_userlevelpermissions", $Language->MenuPhrase("9", "MenuText"), "userlevelpermissionslist.php", -1, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE);
$RootMenu->AddMenuItem(10, "mmi_userlevels", $Language->MenuPhrase("10", "MenuText"), "userlevelslist.php", -1, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE);
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
