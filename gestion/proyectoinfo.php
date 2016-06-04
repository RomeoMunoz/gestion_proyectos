<?php

// Global variable for table object
$proyecto = NULL;

//
// Table class for proyecto
//
class cproyecto extends cTable {
	var $idProyecto;
	var $nombre;
	var $descripcion;
	var $fechaInicio;
	var $fechaFin;
	var $fechaCreacion;
	var $usuarioCreacion;
	var $usuarioLider;
	var $usuarioEncargado;
	var $cliente;
	var $prioridad;
	var $fechaUltimoAcceso;
	var $fechaModificacion;
	var $usuarioModificacion;
	var $estatus;
	var $estado;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'proyecto';
		$this->TableName = 'proyecto';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`proyecto`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = PHPExcel_Worksheet_PageSetup::ORIENTATION_DEFAULT; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4; // Page size (PHPExcel only)
		$this->DetailAdd = TRUE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// idProyecto
		$this->idProyecto = new cField('proyecto', 'proyecto', 'x_idProyecto', 'idProyecto', '`idProyecto`', '`idProyecto`', 3, -1, FALSE, '`idProyecto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->idProyecto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idProyecto'] = &$this->idProyecto;

		// nombre
		$this->nombre = new cField('proyecto', 'proyecto', 'x_nombre', 'nombre', '`nombre`', '`nombre`', 200, -1, FALSE, '`nombre`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['nombre'] = &$this->nombre;

		// descripcion
		$this->descripcion = new cField('proyecto', 'proyecto', 'x_descripcion', 'descripcion', '`descripcion`', '`descripcion`', 200, -1, FALSE, '`descripcion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['descripcion'] = &$this->descripcion;

		// fechaInicio
		$this->fechaInicio = new cField('proyecto', 'proyecto', 'x_fechaInicio', 'fechaInicio', '`fechaInicio`', 'DATE_FORMAT(`fechaInicio`, \'%d/%m/%Y\')', 135, 7, FALSE, '`fechaInicio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fechaInicio->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fechaInicio'] = &$this->fechaInicio;

		// fechaFin
		$this->fechaFin = new cField('proyecto', 'proyecto', 'x_fechaFin', 'fechaFin', '`fechaFin`', 'DATE_FORMAT(`fechaFin`, \'%d/%m/%Y\')', 135, 7, FALSE, '`fechaFin`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fechaFin->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fechaFin'] = &$this->fechaFin;

		// fechaCreacion
		$this->fechaCreacion = new cField('proyecto', 'proyecto', 'x_fechaCreacion', 'fechaCreacion', '`fechaCreacion`', 'DATE_FORMAT(`fechaCreacion`, \'%d/%m/%Y\')', 135, 7, FALSE, '`fechaCreacion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fechaCreacion->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fechaCreacion'] = &$this->fechaCreacion;

		// usuarioCreacion
		$this->usuarioCreacion = new cField('proyecto', 'proyecto', 'x_usuarioCreacion', 'usuarioCreacion', '`usuarioCreacion`', '`usuarioCreacion`', 3, -1, FALSE, '`usuarioCreacion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->usuarioCreacion->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['usuarioCreacion'] = &$this->usuarioCreacion;

		// usuarioLider
		$this->usuarioLider = new cField('proyecto', 'proyecto', 'x_usuarioLider', 'usuarioLider', '`usuarioLider`', '`usuarioLider`', 3, -1, FALSE, '`usuarioLider`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->usuarioLider->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['usuarioLider'] = &$this->usuarioLider;

		// usuarioEncargado
		$this->usuarioEncargado = new cField('proyecto', 'proyecto', 'x_usuarioEncargado', 'usuarioEncargado', '`usuarioEncargado`', '`usuarioEncargado`', 3, -1, FALSE, '`usuarioEncargado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->usuarioEncargado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['usuarioEncargado'] = &$this->usuarioEncargado;

		// cliente
		$this->cliente = new cField('proyecto', 'proyecto', 'x_cliente', 'cliente', '`cliente`', '`cliente`', 3, -1, FALSE, '`cliente`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->cliente->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cliente'] = &$this->cliente;

		// prioridad
		$this->prioridad = new cField('proyecto', 'proyecto', 'x_prioridad', 'prioridad', '`prioridad`', '`prioridad`', 202, -1, FALSE, '`prioridad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->prioridad->OptionCount = 3;
		$this->fields['prioridad'] = &$this->prioridad;

		// fechaUltimoAcceso
		$this->fechaUltimoAcceso = new cField('proyecto', 'proyecto', 'x_fechaUltimoAcceso', 'fechaUltimoAcceso', '`fechaUltimoAcceso`', 'DATE_FORMAT(`fechaUltimoAcceso`, \'%d/%m/%Y\')', 135, 7, FALSE, '`fechaUltimoAcceso`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fechaUltimoAcceso->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fechaUltimoAcceso'] = &$this->fechaUltimoAcceso;

		// fechaModificacion
		$this->fechaModificacion = new cField('proyecto', 'proyecto', 'x_fechaModificacion', 'fechaModificacion', '`fechaModificacion`', 'DATE_FORMAT(`fechaModificacion`, \'%d/%m/%Y\')', 135, 7, FALSE, '`fechaModificacion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fechaModificacion->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fechaModificacion'] = &$this->fechaModificacion;

		// usuarioModificacion
		$this->usuarioModificacion = new cField('proyecto', 'proyecto', 'x_usuarioModificacion', 'usuarioModificacion', '`usuarioModificacion`', '`usuarioModificacion`', 3, -1, FALSE, '`usuarioModificacion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->usuarioModificacion->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['usuarioModificacion'] = &$this->usuarioModificacion;

		// estatus
		$this->estatus = new cField('proyecto', 'proyecto', 'x_estatus', 'estatus', '`estatus`', '`estatus`', 202, -1, FALSE, '`estatus`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->estatus->OptionCount = 4;
		$this->fields['estatus'] = &$this->estatus;

		// estado
		$this->estado = new cField('proyecto', 'proyecto', 'x_estado', 'estado', '`estado`', '`estado`', 202, -1, FALSE, '`estado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->estado->OptionCount = 2;
		$this->fields['estado'] = &$this->estado;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Current detail table name
	function getCurrentDetailTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE];
	}

	function setCurrentDetailTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE] = $v;
	}

	// Get detail url
	function GetDetailUrl() {

		// Detail url
		$sDetailUrl = "";
		if ($this->getCurrentDetailTable() == "objetivo") {
			$sDetailUrl = $GLOBALS["objetivo"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_idProyecto=" . urlencode($this->idProyecto->CurrentValue);
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "proyectolist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`proyecto`";
	}

	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
    	return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
    	$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "`estado` = 'Activo'";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
    	return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
    	$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
    	return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
    	$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
    	return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
    	$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
    	return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
    	$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('idProyecto', $rs))
				ew_AddFilter($where, ew_QuotedName('idProyecto', $this->DBID) . '=' . ew_QuotedValue($rs['idProyecto'], $this->idProyecto->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`idProyecto` = @idProyecto@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->idProyecto->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@idProyecto@", ew_AdjustSql($this->idProyecto->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "proyectolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "proyectolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("proyectoview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("proyectoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "proyectoadd.php?" . $this->UrlParm($parm);
		else
			$url = "proyectoadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("proyectoedit.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("proyectoedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("proyectoadd.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("proyectoadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("proyectodelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "idProyecto:" . ew_VarToJson($this->idProyecto->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->idProyecto->CurrentValue)) {
			$sUrl .= "idProyecto=" . urlencode($this->idProyecto->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["idProyecto"]))
				$arKeys[] = ew_StripSlashes($_POST["idProyecto"]);
			elseif (isset($_GET["idProyecto"]))
				$arKeys[] = ew_StripSlashes($_GET["idProyecto"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->idProyecto->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->idProyecto->setDbValue($rs->fields('idProyecto'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->descripcion->setDbValue($rs->fields('descripcion'));
		$this->fechaInicio->setDbValue($rs->fields('fechaInicio'));
		$this->fechaFin->setDbValue($rs->fields('fechaFin'));
		$this->fechaCreacion->setDbValue($rs->fields('fechaCreacion'));
		$this->usuarioCreacion->setDbValue($rs->fields('usuarioCreacion'));
		$this->usuarioLider->setDbValue($rs->fields('usuarioLider'));
		$this->usuarioEncargado->setDbValue($rs->fields('usuarioEncargado'));
		$this->cliente->setDbValue($rs->fields('cliente'));
		$this->prioridad->setDbValue($rs->fields('prioridad'));
		$this->fechaUltimoAcceso->setDbValue($rs->fields('fechaUltimoAcceso'));
		$this->fechaModificacion->setDbValue($rs->fields('fechaModificacion'));
		$this->usuarioModificacion->setDbValue($rs->fields('usuarioModificacion'));
		$this->estatus->setDbValue($rs->fields('estatus'));
		$this->estado->setDbValue($rs->fields('estado'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// idProyecto
		// nombre
		// descripcion
		// fechaInicio
		// fechaFin
		// fechaCreacion
		// usuarioCreacion
		// usuarioLider
		// usuarioEncargado
		// cliente
		// prioridad
		// fechaUltimoAcceso
		// fechaModificacion
		// usuarioModificacion
		// estatus
		// estado
		// idProyecto

		$this->idProyecto->ViewValue = $this->idProyecto->CurrentValue;
		$this->idProyecto->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// descripcion
		$this->descripcion->ViewValue = $this->descripcion->CurrentValue;
		$this->descripcion->ViewCustomAttributes = "";

		// fechaInicio
		$this->fechaInicio->ViewValue = $this->fechaInicio->CurrentValue;
		$this->fechaInicio->ViewValue = ew_FormatDateTime($this->fechaInicio->ViewValue, 7);
		$this->fechaInicio->ViewCustomAttributes = "";

		// fechaFin
		$this->fechaFin->ViewValue = $this->fechaFin->CurrentValue;
		$this->fechaFin->ViewValue = ew_FormatDateTime($this->fechaFin->ViewValue, 7);
		$this->fechaFin->ViewCustomAttributes = "";

		// fechaCreacion
		$this->fechaCreacion->ViewValue = $this->fechaCreacion->CurrentValue;
		$this->fechaCreacion->ViewValue = ew_FormatDateTime($this->fechaCreacion->ViewValue, 7);
		$this->fechaCreacion->ViewCustomAttributes = "";

		// usuarioCreacion
		if (strval($this->usuarioCreacion->CurrentValue) <> "") {
			$sFilterWrk = "`idUsuario`" . ew_SearchString("=", $this->usuarioCreacion->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `idUsuario`, `nombres` AS `DispFld`, `apellidos` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `usuario`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->usuarioCreacion, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->usuarioCreacion->ViewValue = $this->usuarioCreacion->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->usuarioCreacion->ViewValue = $this->usuarioCreacion->CurrentValue;
			}
		} else {
			$this->usuarioCreacion->ViewValue = NULL;
		}
		$this->usuarioCreacion->ViewCustomAttributes = "";

		// usuarioLider
		if (strval($this->usuarioLider->CurrentValue) <> "") {
			$sFilterWrk = "`idUsuario`" . ew_SearchString("=", $this->usuarioLider->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `idUsuario`, `nombres` AS `DispFld`, `apellidos` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `usuario`";
		$sWhereWrk = "";
		$lookuptblfilter = "`tipoUsuario` = 3";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->usuarioLider, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->usuarioLider->ViewValue = $this->usuarioLider->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->usuarioLider->ViewValue = $this->usuarioLider->CurrentValue;
			}
		} else {
			$this->usuarioLider->ViewValue = NULL;
		}
		$this->usuarioLider->ViewCustomAttributes = "";

		// usuarioEncargado
		if (strval($this->usuarioEncargado->CurrentValue) <> "") {
			$sFilterWrk = "`idUsuario`" . ew_SearchString("=", $this->usuarioEncargado->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `idUsuario`, `nombres` AS `DispFld`, `apellidos` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `usuario`";
		$sWhereWrk = "";
		$lookuptblfilter = "`tipoUsuario` = 2";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->usuarioEncargado, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->usuarioEncargado->ViewValue = $this->usuarioEncargado->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->usuarioEncargado->ViewValue = $this->usuarioEncargado->CurrentValue;
			}
		} else {
			$this->usuarioEncargado->ViewValue = NULL;
		}
		$this->usuarioEncargado->ViewCustomAttributes = "";

		// cliente
		if (strval($this->cliente->CurrentValue) <> "") {
			$sFilterWrk = "`idCliente`" . ew_SearchString("=", $this->cliente->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `idCliente`, `cliente` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cliente`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->cliente, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->cliente->ViewValue = $this->cliente->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->cliente->ViewValue = $this->cliente->CurrentValue;
			}
		} else {
			$this->cliente->ViewValue = NULL;
		}
		$this->cliente->ViewCustomAttributes = "";

		// prioridad
		if (strval($this->prioridad->CurrentValue) <> "") {
			$this->prioridad->ViewValue = $this->prioridad->OptionCaption($this->prioridad->CurrentValue);
		} else {
			$this->prioridad->ViewValue = NULL;
		}
		$this->prioridad->ViewCustomAttributes = "";

		// fechaUltimoAcceso
		$this->fechaUltimoAcceso->ViewValue = $this->fechaUltimoAcceso->CurrentValue;
		$this->fechaUltimoAcceso->ViewValue = ew_FormatDateTime($this->fechaUltimoAcceso->ViewValue, 7);
		$this->fechaUltimoAcceso->ViewCustomAttributes = "";

		// fechaModificacion
		$this->fechaModificacion->ViewValue = $this->fechaModificacion->CurrentValue;
		$this->fechaModificacion->ViewValue = ew_FormatDateTime($this->fechaModificacion->ViewValue, 7);
		$this->fechaModificacion->ViewCustomAttributes = "";

		// usuarioModificacion
		if (strval($this->usuarioModificacion->CurrentValue) <> "") {
			$sFilterWrk = "`idUsuario`" . ew_SearchString("=", $this->usuarioModificacion->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `idUsuario`, `nombres` AS `DispFld`, `apellidos` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `usuario`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->usuarioModificacion, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->usuarioModificacion->ViewValue = $this->usuarioModificacion->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->usuarioModificacion->ViewValue = $this->usuarioModificacion->CurrentValue;
			}
		} else {
			$this->usuarioModificacion->ViewValue = NULL;
		}
		$this->usuarioModificacion->ViewCustomAttributes = "";

		// estatus
		if (strval($this->estatus->CurrentValue) <> "") {
			$this->estatus->ViewValue = $this->estatus->OptionCaption($this->estatus->CurrentValue);
		} else {
			$this->estatus->ViewValue = NULL;
		}
		$this->estatus->ViewCustomAttributes = "";

		// estado
		if (strval($this->estado->CurrentValue) <> "") {
			$this->estado->ViewValue = $this->estado->OptionCaption($this->estado->CurrentValue);
		} else {
			$this->estado->ViewValue = NULL;
		}
		$this->estado->ViewCustomAttributes = "";

		// idProyecto
		$this->idProyecto->LinkCustomAttributes = "";
		$this->idProyecto->HrefValue = "";
		$this->idProyecto->TooltipValue = "";

		// nombre
		$this->nombre->LinkCustomAttributes = "";
		$this->nombre->HrefValue = "";
		$this->nombre->TooltipValue = "";

		// descripcion
		$this->descripcion->LinkCustomAttributes = "";
		$this->descripcion->HrefValue = "";
		$this->descripcion->TooltipValue = "";

		// fechaInicio
		$this->fechaInicio->LinkCustomAttributes = "";
		$this->fechaInicio->HrefValue = "";
		$this->fechaInicio->TooltipValue = "";

		// fechaFin
		$this->fechaFin->LinkCustomAttributes = "";
		$this->fechaFin->HrefValue = "";
		$this->fechaFin->TooltipValue = "";

		// fechaCreacion
		$this->fechaCreacion->LinkCustomAttributes = "";
		$this->fechaCreacion->HrefValue = "";
		$this->fechaCreacion->TooltipValue = "";

		// usuarioCreacion
		$this->usuarioCreacion->LinkCustomAttributes = "";
		$this->usuarioCreacion->HrefValue = "";
		$this->usuarioCreacion->TooltipValue = "";

		// usuarioLider
		$this->usuarioLider->LinkCustomAttributes = "";
		$this->usuarioLider->HrefValue = "";
		$this->usuarioLider->TooltipValue = "";

		// usuarioEncargado
		$this->usuarioEncargado->LinkCustomAttributes = "";
		$this->usuarioEncargado->HrefValue = "";
		$this->usuarioEncargado->TooltipValue = "";

		// cliente
		$this->cliente->LinkCustomAttributes = "";
		$this->cliente->HrefValue = "";
		$this->cliente->TooltipValue = "";

		// prioridad
		$this->prioridad->LinkCustomAttributes = "";
		$this->prioridad->HrefValue = "";
		$this->prioridad->TooltipValue = "";

		// fechaUltimoAcceso
		$this->fechaUltimoAcceso->LinkCustomAttributes = "";
		$this->fechaUltimoAcceso->HrefValue = "";
		$this->fechaUltimoAcceso->TooltipValue = "";

		// fechaModificacion
		$this->fechaModificacion->LinkCustomAttributes = "";
		$this->fechaModificacion->HrefValue = "";
		$this->fechaModificacion->TooltipValue = "";

		// usuarioModificacion
		$this->usuarioModificacion->LinkCustomAttributes = "";
		$this->usuarioModificacion->HrefValue = "";
		$this->usuarioModificacion->TooltipValue = "";

		// estatus
		$this->estatus->LinkCustomAttributes = "";
		$this->estatus->HrefValue = "";
		$this->estatus->TooltipValue = "";

		// estado
		$this->estado->LinkCustomAttributes = "";
		$this->estado->HrefValue = "";
		$this->estado->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// idProyecto
		$this->idProyecto->EditAttrs["class"] = "form-control";
		$this->idProyecto->EditCustomAttributes = "";
		$this->idProyecto->EditValue = $this->idProyecto->CurrentValue;
		$this->idProyecto->ViewCustomAttributes = "";

		// nombre
		$this->nombre->EditAttrs["class"] = "form-control";
		$this->nombre->EditCustomAttributes = "";
		$this->nombre->EditValue = $this->nombre->CurrentValue;
		$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

		// descripcion
		$this->descripcion->EditAttrs["class"] = "form-control";
		$this->descripcion->EditCustomAttributes = "";
		$this->descripcion->EditValue = $this->descripcion->CurrentValue;
		$this->descripcion->PlaceHolder = ew_RemoveHtml($this->descripcion->FldCaption());

		// fechaInicio
		$this->fechaInicio->EditAttrs["class"] = "form-control";
		$this->fechaInicio->EditCustomAttributes = "";
		$this->fechaInicio->EditValue = ew_FormatDateTime($this->fechaInicio->CurrentValue, 7);
		$this->fechaInicio->PlaceHolder = ew_RemoveHtml($this->fechaInicio->FldCaption());

		// fechaFin
		$this->fechaFin->EditAttrs["class"] = "form-control";
		$this->fechaFin->EditCustomAttributes = "";
		$this->fechaFin->EditValue = ew_FormatDateTime($this->fechaFin->CurrentValue, 7);
		$this->fechaFin->PlaceHolder = ew_RemoveHtml($this->fechaFin->FldCaption());

		// fechaCreacion
		$this->fechaCreacion->EditAttrs["class"] = "form-control";
		$this->fechaCreacion->EditCustomAttributes = "";
		$this->fechaCreacion->EditValue = ew_FormatDateTime($this->fechaCreacion->CurrentValue, 7);
		$this->fechaCreacion->PlaceHolder = ew_RemoveHtml($this->fechaCreacion->FldCaption());

		// usuarioCreacion
		$this->usuarioCreacion->EditAttrs["class"] = "form-control";
		$this->usuarioCreacion->EditCustomAttributes = "";

		// usuarioLider
		$this->usuarioLider->EditAttrs["class"] = "form-control";
		$this->usuarioLider->EditCustomAttributes = "";

		// usuarioEncargado
		$this->usuarioEncargado->EditAttrs["class"] = "form-control";
		$this->usuarioEncargado->EditCustomAttributes = "";

		// cliente
		$this->cliente->EditAttrs["class"] = "form-control";
		$this->cliente->EditCustomAttributes = "";

		// prioridad
		$this->prioridad->EditAttrs["class"] = "form-control";
		$this->prioridad->EditCustomAttributes = "";
		$this->prioridad->EditValue = $this->prioridad->Options(TRUE);

		// fechaUltimoAcceso
		$this->fechaUltimoAcceso->EditAttrs["class"] = "form-control";
		$this->fechaUltimoAcceso->EditCustomAttributes = "";
		$this->fechaUltimoAcceso->EditValue = ew_FormatDateTime($this->fechaUltimoAcceso->CurrentValue, 7);
		$this->fechaUltimoAcceso->PlaceHolder = ew_RemoveHtml($this->fechaUltimoAcceso->FldCaption());

		// fechaModificacion
		$this->fechaModificacion->EditAttrs["class"] = "form-control";
		$this->fechaModificacion->EditCustomAttributes = "";
		$this->fechaModificacion->EditValue = ew_FormatDateTime($this->fechaModificacion->CurrentValue, 7);
		$this->fechaModificacion->PlaceHolder = ew_RemoveHtml($this->fechaModificacion->FldCaption());

		// usuarioModificacion
		$this->usuarioModificacion->EditAttrs["class"] = "form-control";
		$this->usuarioModificacion->EditCustomAttributes = "";

		// estatus
		$this->estatus->EditAttrs["class"] = "form-control";
		$this->estatus->EditCustomAttributes = "";
		if (strval($this->estatus->CurrentValue) <> "") {
			$this->estatus->EditValue = $this->estatus->OptionCaption($this->estatus->CurrentValue);
		} else {
			$this->estatus->EditValue = NULL;
		}
		$this->estatus->ViewCustomAttributes = "";

		// estado
		$this->estado->EditAttrs["class"] = "form-control";
		$this->estado->EditCustomAttributes = "";
		$this->estado->EditValue = $this->estado->Options(TRUE);

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->idProyecto->Exportable) $Doc->ExportCaption($this->idProyecto);
					if ($this->nombre->Exportable) $Doc->ExportCaption($this->nombre);
					if ($this->descripcion->Exportable) $Doc->ExportCaption($this->descripcion);
					if ($this->fechaInicio->Exportable) $Doc->ExportCaption($this->fechaInicio);
					if ($this->fechaFin->Exportable) $Doc->ExportCaption($this->fechaFin);
					if ($this->fechaCreacion->Exportable) $Doc->ExportCaption($this->fechaCreacion);
					if ($this->usuarioCreacion->Exportable) $Doc->ExportCaption($this->usuarioCreacion);
					if ($this->usuarioLider->Exportable) $Doc->ExportCaption($this->usuarioLider);
					if ($this->usuarioEncargado->Exportable) $Doc->ExportCaption($this->usuarioEncargado);
					if ($this->cliente->Exportable) $Doc->ExportCaption($this->cliente);
					if ($this->prioridad->Exportable) $Doc->ExportCaption($this->prioridad);
					if ($this->fechaUltimoAcceso->Exportable) $Doc->ExportCaption($this->fechaUltimoAcceso);
					if ($this->fechaModificacion->Exportable) $Doc->ExportCaption($this->fechaModificacion);
					if ($this->usuarioModificacion->Exportable) $Doc->ExportCaption($this->usuarioModificacion);
					if ($this->estatus->Exportable) $Doc->ExportCaption($this->estatus);
					if ($this->estado->Exportable) $Doc->ExportCaption($this->estado);
				} else {
					if ($this->idProyecto->Exportable) $Doc->ExportCaption($this->idProyecto);
					if ($this->nombre->Exportable) $Doc->ExportCaption($this->nombre);
					if ($this->descripcion->Exportable) $Doc->ExportCaption($this->descripcion);
					if ($this->fechaInicio->Exportable) $Doc->ExportCaption($this->fechaInicio);
					if ($this->fechaFin->Exportable) $Doc->ExportCaption($this->fechaFin);
					if ($this->fechaCreacion->Exportable) $Doc->ExportCaption($this->fechaCreacion);
					if ($this->usuarioCreacion->Exportable) $Doc->ExportCaption($this->usuarioCreacion);
					if ($this->usuarioLider->Exportable) $Doc->ExportCaption($this->usuarioLider);
					if ($this->usuarioEncargado->Exportable) $Doc->ExportCaption($this->usuarioEncargado);
					if ($this->cliente->Exportable) $Doc->ExportCaption($this->cliente);
					if ($this->prioridad->Exportable) $Doc->ExportCaption($this->prioridad);
					if ($this->fechaUltimoAcceso->Exportable) $Doc->ExportCaption($this->fechaUltimoAcceso);
					if ($this->fechaModificacion->Exportable) $Doc->ExportCaption($this->fechaModificacion);
					if ($this->usuarioModificacion->Exportable) $Doc->ExportCaption($this->usuarioModificacion);
					if ($this->estatus->Exportable) $Doc->ExportCaption($this->estatus);
					if ($this->estado->Exportable) $Doc->ExportCaption($this->estado);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->idProyecto->Exportable) $Doc->ExportField($this->idProyecto);
						if ($this->nombre->Exportable) $Doc->ExportField($this->nombre);
						if ($this->descripcion->Exportable) $Doc->ExportField($this->descripcion);
						if ($this->fechaInicio->Exportable) $Doc->ExportField($this->fechaInicio);
						if ($this->fechaFin->Exportable) $Doc->ExportField($this->fechaFin);
						if ($this->fechaCreacion->Exportable) $Doc->ExportField($this->fechaCreacion);
						if ($this->usuarioCreacion->Exportable) $Doc->ExportField($this->usuarioCreacion);
						if ($this->usuarioLider->Exportable) $Doc->ExportField($this->usuarioLider);
						if ($this->usuarioEncargado->Exportable) $Doc->ExportField($this->usuarioEncargado);
						if ($this->cliente->Exportable) $Doc->ExportField($this->cliente);
						if ($this->prioridad->Exportable) $Doc->ExportField($this->prioridad);
						if ($this->fechaUltimoAcceso->Exportable) $Doc->ExportField($this->fechaUltimoAcceso);
						if ($this->fechaModificacion->Exportable) $Doc->ExportField($this->fechaModificacion);
						if ($this->usuarioModificacion->Exportable) $Doc->ExportField($this->usuarioModificacion);
						if ($this->estatus->Exportable) $Doc->ExportField($this->estatus);
						if ($this->estado->Exportable) $Doc->ExportField($this->estado);
					} else {
						if ($this->idProyecto->Exportable) $Doc->ExportField($this->idProyecto);
						if ($this->nombre->Exportable) $Doc->ExportField($this->nombre);
						if ($this->descripcion->Exportable) $Doc->ExportField($this->descripcion);
						if ($this->fechaInicio->Exportable) $Doc->ExportField($this->fechaInicio);
						if ($this->fechaFin->Exportable) $Doc->ExportField($this->fechaFin);
						if ($this->fechaCreacion->Exportable) $Doc->ExportField($this->fechaCreacion);
						if ($this->usuarioCreacion->Exportable) $Doc->ExportField($this->usuarioCreacion);
						if ($this->usuarioLider->Exportable) $Doc->ExportField($this->usuarioLider);
						if ($this->usuarioEncargado->Exportable) $Doc->ExportField($this->usuarioEncargado);
						if ($this->cliente->Exportable) $Doc->ExportField($this->cliente);
						if ($this->prioridad->Exportable) $Doc->ExportField($this->prioridad);
						if ($this->fechaUltimoAcceso->Exportable) $Doc->ExportField($this->fechaUltimoAcceso);
						if ($this->fechaModificacion->Exportable) $Doc->ExportField($this->fechaModificacion);
						if ($this->usuarioModificacion->Exportable) $Doc->ExportField($this->usuarioModificacion);
						if ($this->estatus->Exportable) $Doc->ExportField($this->estatus);
						if ($this->estado->Exportable) $Doc->ExportField($this->estado);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
