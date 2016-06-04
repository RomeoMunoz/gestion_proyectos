<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(5, "mmi_empresa", $Language->MenuPhrase("5", "MenuText"), "empresalist.php", -1, "", AllowListMenu('{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}empresa'), FALSE);
$RootMenu->AddMenuItem(3, "mmi_cliente", $Language->MenuPhrase("3", "MenuText"), "clientelist.php", -1, "", AllowListMenu('{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}cliente'), FALSE);
$RootMenu->AddMenuItem(16, "mmci_Proyectos", $Language->MenuPhrase("16", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(9, "mmi_proyecto", $Language->MenuPhrase("9", "MenuText"), "proyectolist.php", 16, "", AllowListMenu('{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}proyecto'), FALSE);
$RootMenu->AddMenuItem(7, "mmi_objetivo", $Language->MenuPhrase("7", "MenuText"), "objetivolist.php?cmd=resetall", 16, "", AllowListMenu('{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}objetivo'), FALSE);
$RootMenu->AddMenuItem(10, "mmi_resultado", $Language->MenuPhrase("10", "MenuText"), "resultadolist.php?cmd=resetall", 16, "", AllowListMenu('{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}resultado'), FALSE);
$RootMenu->AddMenuItem(1, "mmi_actividad", $Language->MenuPhrase("1", "MenuText"), "actividadlist.php?cmd=resetall", 16, "", AllowListMenu('{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}actividad'), FALSE);
$RootMenu->AddMenuItem(20, "mmci_Mantenimientos", $Language->MenuPhrase("20", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(2, "mmi_actividad_avance_porcentaje", $Language->MenuPhrase("2", "MenuText"), "actividad_avance_porcentajelist.php", 20, "", AllowListMenu('{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}actividad_avance_porcentaje'), FALSE);
$RootMenu->AddMenuItem(4, "mmi_cliente_tipo", $Language->MenuPhrase("4", "MenuText"), "cliente_tipolist.php", 20, "", AllowListMenu('{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}cliente_tipo'), FALSE);
$RootMenu->AddMenuItem(6, "mmi_ingreso_tipo", $Language->MenuPhrase("6", "MenuText"), "ingreso_tipolist.php", 20, "", AllowListMenu('{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}ingreso_tipo'), FALSE);
$RootMenu->AddMenuItem(8, "mmi_objetivos_tipo", $Language->MenuPhrase("8", "MenuText"), "objetivos_tipolist.php", 20, "", AllowListMenu('{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}objetivos_tipo'), FALSE);
$RootMenu->AddMenuItem(12, "mmi_usuario_tipo", $Language->MenuPhrase("12", "MenuText"), "usuario_tipolist.php", 20, "", AllowListMenu('{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}usuario_tipo'), FALSE);
$RootMenu->AddMenuItem(11, "mmi_usuario", $Language->MenuPhrase("11", "MenuText"), "usuariolist.php", -1, "", AllowListMenu('{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}usuario'), FALSE);
$RootMenu->AddMenuItem(18, "mmci_Permisos", $Language->MenuPhrase("18", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(14, "mmi_userlevels", $Language->MenuPhrase("14", "MenuText"), "userlevelslist.php", 18, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE);
$RootMenu->AddMenuItem(13, "mmi_userlevelpermissions", $Language->MenuPhrase("13", "MenuText"), "userlevelpermissionslist.php", 18, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE);
$RootMenu->AddMenuItem(-2, "mmi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
