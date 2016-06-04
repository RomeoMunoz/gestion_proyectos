<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(5, "mi_empresa", $Language->MenuPhrase("5", "MenuText"), "empresalist.php", -1, "", AllowListMenu('{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}empresa'), FALSE);
$RootMenu->AddMenuItem(3, "mi_cliente", $Language->MenuPhrase("3", "MenuText"), "clientelist.php", -1, "", AllowListMenu('{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}cliente'), FALSE);
$RootMenu->AddMenuItem(16, "mci_Proyectos", $Language->MenuPhrase("16", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(9, "mi_proyecto", $Language->MenuPhrase("9", "MenuText"), "proyectolist.php", 16, "", AllowListMenu('{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}proyecto'), FALSE);
$RootMenu->AddMenuItem(7, "mi_objetivo", $Language->MenuPhrase("7", "MenuText"), "objetivolist.php?cmd=resetall", 16, "", AllowListMenu('{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}objetivo'), FALSE);
$RootMenu->AddMenuItem(10, "mi_resultado", $Language->MenuPhrase("10", "MenuText"), "resultadolist.php?cmd=resetall", 16, "", AllowListMenu('{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}resultado'), FALSE);
$RootMenu->AddMenuItem(1, "mi_actividad", $Language->MenuPhrase("1", "MenuText"), "actividadlist.php?cmd=resetall", 16, "", AllowListMenu('{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}actividad'), FALSE);
$RootMenu->AddMenuItem(20, "mci_Mantenimientos", $Language->MenuPhrase("20", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(2, "mi_actividad_avance_porcentaje", $Language->MenuPhrase("2", "MenuText"), "actividad_avance_porcentajelist.php", 20, "", AllowListMenu('{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}actividad_avance_porcentaje'), FALSE);
$RootMenu->AddMenuItem(4, "mi_cliente_tipo", $Language->MenuPhrase("4", "MenuText"), "cliente_tipolist.php", 20, "", AllowListMenu('{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}cliente_tipo'), FALSE);
$RootMenu->AddMenuItem(6, "mi_ingreso_tipo", $Language->MenuPhrase("6", "MenuText"), "ingreso_tipolist.php", 20, "", AllowListMenu('{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}ingreso_tipo'), FALSE);
$RootMenu->AddMenuItem(8, "mi_objetivos_tipo", $Language->MenuPhrase("8", "MenuText"), "objetivos_tipolist.php", 20, "", AllowListMenu('{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}objetivos_tipo'), FALSE);
$RootMenu->AddMenuItem(12, "mi_usuario_tipo", $Language->MenuPhrase("12", "MenuText"), "usuario_tipolist.php", 20, "", AllowListMenu('{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}usuario_tipo'), FALSE);
$RootMenu->AddMenuItem(11, "mi_usuario", $Language->MenuPhrase("11", "MenuText"), "usuariolist.php", -1, "", AllowListMenu('{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}usuario'), FALSE);
$RootMenu->AddMenuItem(18, "mci_Permisos", $Language->MenuPhrase("18", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(14, "mi_userlevels", $Language->MenuPhrase("14", "MenuText"), "userlevelslist.php", 18, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE);
$RootMenu->AddMenuItem(13, "mi_userlevelpermissions", $Language->MenuPhrase("13", "MenuText"), "userlevelpermissionslist.php", 18, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE);
$RootMenu->AddMenuItem(-2, "mi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
