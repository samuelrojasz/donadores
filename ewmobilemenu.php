<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(1, "mmi_dadores_de_sangre", $Language->MenuPhrase("1", "MenuText"), "dadores_de_sangrelist.php", -1, "", AllowListMenu('{FC1F4E8D-CD1D-4597-961D-132102C33822}dadores_de_sangre'), FALSE);
$RootMenu->AddMenuItem(2, "mmi_donacoes_sangre", $Language->MenuPhrase("2", "MenuText"), "donacoes_sangrelist.php", -1, "", AllowListMenu('{FC1F4E8D-CD1D-4597-961D-132102C33822}donacoes_sangre'), FALSE);
$RootMenu->AddMenuItem(3, "mmi_hospitais", $Language->MenuPhrase("3", "MenuText"), "hospitaislist.php", -1, "", AllowListMenu('{FC1F4E8D-CD1D-4597-961D-132102C33822}hospitais'), FALSE);
$RootMenu->AddMenuItem(8, "mmi_membership_users", $Language->MenuPhrase("8", "MenuText"), "membership_userslist.php", -1, "", AllowListMenu('{FC1F4E8D-CD1D-4597-961D-132102C33822}membership_users'), FALSE);
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
