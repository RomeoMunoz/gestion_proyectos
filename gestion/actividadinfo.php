<?php

// Global variable for table object
$actividad = NULL;

//
// Table class for actividad
//
class cactividad extends cTable {
	var $idActividad;
	var $avance;
	var $nombre;
	var $duracion;
	var $tipoDuracion;
	var $fechaInicio;
	var $fechaFin;
	var $predecesora;
	var $recurso;
	var $estado;
	var $estatus;
	var $Resultado;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'actividad';
		$this->TableName = 'actividad';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`actividad`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = PHPExcel_Worksheet_PageSetup::ORIENTATION_DEFAULT; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// idActividad
		$this->idActividad = new cField('actividad', 'actividad', 'x_idActividad', 'idActividad', '`idActividad`', '`idActividad`', 3, -1, FALSE, '`idActividad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->idActividad->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idActividad'] = &$this->idActividad;

		// avance
		$this->avance = new cField('actividad', 'actividad', 'x_avance', 'avance', '`avance`', '`avance`', 3, -1, FALSE, '`avance`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->avance->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['avance'] = &$this->avance;

		// nombre
		$this->nombre = new cField('actividad', 'actividad', 'x_nombre', 'nombre', '`nombre`', '`nombre`', 200, -1, FALSE, '`nombre`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['nombre'] = &$this->nombre;

		// duracion
		$this->duracion = new cField('actividad', 'actividad', 'x_duracion', 'duracion', '`duracion`', '`duracion`', 3, -1, FALSE, '`duracion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->duracion->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['duracion'] = &$this->duracion;

		// tipoDuracion
		$this->tipoDuracion = new cField('actividad', 'actividad', 'x_tipoDuracion', 'tipoDuracion', '`tipoDuracion`', '`tipoDuracion`', 202, -1, FALSE, '`tipoDuracion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->tipoDuracion->OptionCount = 2;
		$this->fields['tipoDuracion'] = &$this->tipoDuracion;

		// fechaInicio
		$this->fechaInicio = new cField('actividad', 'actividad', 'x_fechaInicio', 'fechaInicio', '`fechaInicio`', 'DATE_FORMAT(`fechaInicio`, \'%d/%m/%Y\')', 135, 7, FALSE, '`fechaInicio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fechaInicio->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fechaInicio'] = &$this->fechaInicio;

		// fechaFin
		$this->fechaFin = new cField('actividad', 'actividad', 'x_fechaFin', 'fechaFin', '`fechaFin`', 'DATE_FORMAT(`fechaFin`, \'%d/%m/%Y\')', 135, 7, FALSE, '`fechaFin`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fechaFin->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fechaFin'] = &$this->fechaFin;

		// predecesora
		$this->predecesora = new cField('actividad', 'actividad', 'x_predecesora', 'predecesora', '`predecesora`', '`predecesora`', 3, -1, FALSE, '`predecesora`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->predecesora->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['predecesora'] = &$this->predecesora;

		// recurso
		$this->recurso = new cField('actividad', 'actividad', 'x_recurso', 'recurso', '`recurso`', '`recurso`', 3, -1, FALSE, '`recurso`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->recurso->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['recurso'] = &$this->recurso;

		// estado
		$this->estado = new cField('actividad', 'actividad', 'x_estado', 'estado', '`estado`', '`estado`', 200, -1, FALSE, '`estado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['estado'] = &$this->estado;

		// estatus
		$this->estatus = new cField('actividad', 'actividad', 'x_estatus', 'estatus', '`estatus`', '`estatus`', 202, -1, FALSE, '`estatus`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->estatus->OptionCount = 4;
		$this->fields['estatus'] = &$this->estatus;

		// Resultado
		$this->Resultado = new cField('actividad', 'actividad', 'x_Resultado', 'Resultado', '`Resultado`', '`Resultado`', 3, -1, FALSE, '`Resultado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->Resultado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Resultado'] = &$this->Resultado;
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

	// Current master table name
	function getCurrentMasterTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE];
	}

	function setCurrentMasterTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE] = $v;
	}

	// Session master WHERE clause
	function GetMasterFilter() {

		// Master filter
		$sMasterFilter = "";
		if ($this->getCurrentMasterTable() == "resultado") {
			if ($this->Resultado->getSessionValue() <> "")
				$sMasterFilter .= "`idResultado`=" . ew_QuotedValue($this->Resultado->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "resultado") {
			if ($this->Resultado->getSessionValue() <> "")
				$sDetailFilter .= "`Resultado`=" . ew_QuotedValue($this->Resultado->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_resultado() {
		return "`idResultado`=@idResultado@";
	}

	// Detail filter
	function SqlDetailFilter_resultado() {
		return "`Resultado`=@Resultado@";
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`actividad`";
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
			if (array_key_exists('idActividad', $rs))
				ew_AddFilter($where, ew_QuotedName('idActividad', $this->DBID) . '=' . ew_QuotedValue($rs['idActividad'], $this->idActividad->FldDataType, $this->DBID));
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
		return "`idActividad` = @idActividad@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->idActividad->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@idActividad@", ew_AdjustSql($this->idActividad->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "actividadlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "actividadlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("actividadview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("actividadview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "actividadadd.php?" . $this->UrlParm($parm);
		else
			$url = "actividadadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("actividadedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("actividadadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("actividaddelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		if ($this->getCurrentMasterTable() == "resultado" && strpos($url, EW_TABLE_SHOW_MASTER . "=") === FALSE) {
			$url .= (strpos($url, "?") !== FALSE ? "&" : "?") . EW_TABLE_SHOW_MASTER . "=" . $this->getCurrentMasterTable();
			$url .= "&fk_idResultado=" . urlencode($this->Resultado->CurrentValue);
		}
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "idActividad:" . ew_VarToJson($this->idActividad->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->idActividad->CurrentValue)) {
			$sUrl .= "idActividad=" . urlencode($this->idActividad->CurrentValue);
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
			if ($isPost && isset($_POST["idActividad"]))
				$arKeys[] = ew_StripSlashes($_POST["idActividad"]);
			elseif (isset($_GET["idActividad"]))
				$arKeys[] = ew_StripSlashes($_GET["idActividad"]);
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
			$this->idActividad->CurrentValue = $key;
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
		$this->idActividad->setDbValue($rs->fields('idActividad'));
		$this->avance->setDbValue($rs->fields('avance'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->duracion->setDbValue($rs->fields('duracion'));
		$this->tipoDuracion->setDbValue($rs->fields('tipoDuracion'));
		$this->fechaInicio->setDbValue($rs->fields('fechaInicio'));
		$this->fechaFin->setDbValue($rs->fields('fechaFin'));
		$this->predecesora->setDbValue($rs->fields('predecesora'));
		$this->recurso->setDbValue($rs->fields('recurso'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->estatus->setDbValue($rs->fields('estatus'));
		$this->Resultado->setDbValue($rs->fields('Resultado'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// idActividad
		// avance
		// nombre
		// duracion
		// tipoDuracion
		// fechaInicio
		// fechaFin
		// predecesora
		// recurso
		// estado
		// estatus
		// Resultado
		// idActividad

		$this->idActividad->ViewValue = $this->idActividad->CurrentValue;
		$this->idActividad->ViewCustomAttributes = "";

		// avance
		if (strval($this->avance->CurrentValue) <> "") {
			$sFilterWrk = "`idAvance`" . ew_SearchString("=", $this->avance->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `idAvance`, `cantidad` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `actividad_avance_porcentaje`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->avance, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->avance->ViewValue = $this->avance->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->avance->ViewValue = $this->avance->CurrentValue;
			}
		} else {
			$this->avance->ViewValue = NULL;
		}
		$this->avance->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// duracion
		$this->duracion->ViewValue = $this->duracion->CurrentValue;
		$this->duracion->ViewCustomAttributes = "";

		// tipoDuracion
		if (strval($this->tipoDuracion->CurrentValue) <> "") {
			$this->tipoDuracion->ViewValue = $this->tipoDuracion->OptionCaption($this->tipoDuracion->CurrentValue);
		} else {
			$this->tipoDuracion->ViewValue = NULL;
		}
		$this->tipoDuracion->ViewCustomAttributes = "";

		// fechaInicio
		$this->fechaInicio->ViewValue = $this->fechaInicio->CurrentValue;
		$this->fechaInicio->ViewValue = ew_FormatDateTime($this->fechaInicio->ViewValue, 7);
		$this->fechaInicio->ViewCustomAttributes = "";

		// fechaFin
		$this->fechaFin->ViewValue = $this->fechaFin->CurrentValue;
		$this->fechaFin->ViewValue = ew_FormatDateTime($this->fechaFin->ViewValue, 7);
		$this->fechaFin->ViewCustomAttributes = "";

		// predecesora
		$this->predecesora->ViewValue = $this->predecesora->CurrentValue;
		$this->predecesora->ViewCustomAttributes = "";

		// recurso
		if (strval($this->recurso->CurrentValue) <> "") {
			$sFilterWrk = "`idUsuario`" . ew_SearchString("=", $this->recurso->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `idUsuario`, `nombres` AS `DispFld`, `apellidos` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `usuario`";
		$sWhereWrk = "";
		$lookuptblfilter = "`tipoUsuario` = 4";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->recurso, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->recurso->ViewValue = $this->recurso->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->recurso->ViewValue = $this->recurso->CurrentValue;
			}
		} else {
			$this->recurso->ViewValue = NULL;
		}
		$this->recurso->ViewCustomAttributes = "";

		// estado
		$this->estado->ViewValue = $this->estado->CurrentValue;
		$this->estado->ViewCustomAttributes = "";

		// estatus
		if (strval($this->estatus->CurrentValue) <> "") {
			$this->estatus->ViewValue = $this->estatus->OptionCaption($this->estatus->CurrentValue);
		} else {
			$this->estatus->ViewValue = NULL;
		}
		$this->estatus->ViewCustomAttributes = "";

		// Resultado
		if (strval($this->Resultado->CurrentValue) <> "") {
			$sFilterWrk = "`idResultado`" . ew_SearchString("=", $this->Resultado->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `idResultado`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `resultado`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Resultado, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Resultado->ViewValue = $this->Resultado->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Resultado->ViewValue = $this->Resultado->CurrentValue;
			}
		} else {
			$this->Resultado->ViewValue = NULL;
		}
		$this->Resultado->ViewCustomAttributes = "";

		// idActividad
		$this->idActividad->LinkCustomAttributes = "";
		$this->idActividad->HrefValue = "";
		$this->idActividad->TooltipValue = "";

		// avance
		$this->avance->LinkCustomAttributes = "";
		$this->avance->HrefValue = "";
		$this->avance->TooltipValue = "";

		// nombre
		$this->nombre->LinkCustomAttributes = "";
		$this->nombre->HrefValue = "";
		$this->nombre->TooltipValue = "";

		// duracion
		$this->duracion->LinkCustomAttributes = "";
		$this->duracion->HrefValue = "";
		$this->duracion->TooltipValue = "";

		// tipoDuracion
		$this->tipoDuracion->LinkCustomAttributes = "";
		$this->tipoDuracion->HrefValue = "";
		$this->tipoDuracion->TooltipValue = "";

		// fechaInicio
		$this->fechaInicio->LinkCustomAttributes = "";
		$this->fechaInicio->HrefValue = "";
		$this->fechaInicio->TooltipValue = "";

		// fechaFin
		$this->fechaFin->LinkCustomAttributes = "";
		$this->fechaFin->HrefValue = "";
		$this->fechaFin->TooltipValue = "";

		// predecesora
		$this->predecesora->LinkCustomAttributes = "";
		$this->predecesora->HrefValue = "";
		$this->predecesora->TooltipValue = "";

		// recurso
		$this->recurso->LinkCustomAttributes = "";
		$this->recurso->HrefValue = "";
		$this->recurso->TooltipValue = "";

		// estado
		$this->estado->LinkCustomAttributes = "";
		$this->estado->HrefValue = "";
		$this->estado->TooltipValue = "";

		// estatus
		$this->estatus->LinkCustomAttributes = "";
		$this->estatus->HrefValue = "";
		$this->estatus->TooltipValue = "";

		// Resultado
		$this->Resultado->LinkCustomAttributes = "";
		$this->Resultado->HrefValue = "";
		$this->Resultado->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// idActividad
		$this->idActividad->EditAttrs["class"] = "form-control";
		$this->idActividad->EditCustomAttributes = "";
		$this->idActividad->EditValue = $this->idActividad->CurrentValue;
		$this->idActividad->ViewCustomAttributes = "";

		// avance
		$this->avance->EditAttrs["class"] = "form-control";
		$this->avance->EditCustomAttributes = "";

		// nombre
		$this->nombre->EditAttrs["class"] = "form-control";
		$this->nombre->EditCustomAttributes = "";
		$this->nombre->EditValue = $this->nombre->CurrentValue;
		$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

		// duracion
		$this->duracion->EditAttrs["class"] = "form-control";
		$this->duracion->EditCustomAttributes = "";
		$this->duracion->EditValue = $this->duracion->CurrentValue;
		$this->duracion->PlaceHolder = ew_RemoveHtml($this->duracion->FldCaption());

		// tipoDuracion
		$this->tipoDuracion->EditAttrs["class"] = "form-control";
		$this->tipoDuracion->EditCustomAttributes = "";
		$this->tipoDuracion->EditValue = $this->tipoDuracion->Options(TRUE);

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

		// predecesora
		$this->predecesora->EditAttrs["class"] = "form-control";
		$this->predecesora->EditCustomAttributes = "";
		$this->predecesora->EditValue = $this->predecesora->CurrentValue;
		$this->predecesora->PlaceHolder = ew_RemoveHtml($this->predecesora->FldCaption());

		// recurso
		$this->recurso->EditAttrs["class"] = "form-control";
		$this->recurso->EditCustomAttributes = "";

		// estado
		$this->estado->EditAttrs["class"] = "form-control";
		$this->estado->EditCustomAttributes = "";
		$this->estado->EditValue = $this->estado->CurrentValue;
		$this->estado->PlaceHolder = ew_RemoveHtml($this->estado->FldCaption());

		// estatus
		$this->estatus->EditAttrs["class"] = "form-control";
		$this->estatus->EditCustomAttributes = "";
		$this->estatus->EditValue = $this->estatus->Options(TRUE);

		// Resultado
		$this->Resultado->EditAttrs["class"] = "form-control";
		$this->Resultado->EditCustomAttributes = "";
		if ($this->Resultado->getSessionValue() <> "") {
			$this->Resultado->CurrentValue = $this->Resultado->getSessionValue();
		if (strval($this->Resultado->CurrentValue) <> "") {
			$sFilterWrk = "`idResultado`" . ew_SearchString("=", $this->Resultado->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `idResultado`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `resultado`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Resultado, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Resultado->ViewValue = $this->Resultado->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Resultado->ViewValue = $this->Resultado->CurrentValue;
			}
		} else {
			$this->Resultado->ViewValue = NULL;
		}
		$this->Resultado->ViewCustomAttributes = "";
		} else {
		}

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
					if ($this->idActividad->Exportable) $Doc->ExportCaption($this->idActividad);
					if ($this->avance->Exportable) $Doc->ExportCaption($this->avance);
					if ($this->nombre->Exportable) $Doc->ExportCaption($this->nombre);
					if ($this->duracion->Exportable) $Doc->ExportCaption($this->duracion);
					if ($this->tipoDuracion->Exportable) $Doc->ExportCaption($this->tipoDuracion);
					if ($this->fechaInicio->Exportable) $Doc->ExportCaption($this->fechaInicio);
					if ($this->fechaFin->Exportable) $Doc->ExportCaption($this->fechaFin);
					if ($this->predecesora->Exportable) $Doc->ExportCaption($this->predecesora);
					if ($this->recurso->Exportable) $Doc->ExportCaption($this->recurso);
					if ($this->estado->Exportable) $Doc->ExportCaption($this->estado);
					if ($this->estatus->Exportable) $Doc->ExportCaption($this->estatus);
					if ($this->Resultado->Exportable) $Doc->ExportCaption($this->Resultado);
				} else {
					if ($this->idActividad->Exportable) $Doc->ExportCaption($this->idActividad);
					if ($this->avance->Exportable) $Doc->ExportCaption($this->avance);
					if ($this->nombre->Exportable) $Doc->ExportCaption($this->nombre);
					if ($this->duracion->Exportable) $Doc->ExportCaption($this->duracion);
					if ($this->tipoDuracion->Exportable) $Doc->ExportCaption($this->tipoDuracion);
					if ($this->fechaInicio->Exportable) $Doc->ExportCaption($this->fechaInicio);
					if ($this->fechaFin->Exportable) $Doc->ExportCaption($this->fechaFin);
					if ($this->predecesora->Exportable) $Doc->ExportCaption($this->predecesora);
					if ($this->recurso->Exportable) $Doc->ExportCaption($this->recurso);
					if ($this->estado->Exportable) $Doc->ExportCaption($this->estado);
					if ($this->estatus->Exportable) $Doc->ExportCaption($this->estatus);
					if ($this->Resultado->Exportable) $Doc->ExportCaption($this->Resultado);
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
						if ($this->idActividad->Exportable) $Doc->ExportField($this->idActividad);
						if ($this->avance->Exportable) $Doc->ExportField($this->avance);
						if ($this->nombre->Exportable) $Doc->ExportField($this->nombre);
						if ($this->duracion->Exportable) $Doc->ExportField($this->duracion);
						if ($this->tipoDuracion->Exportable) $Doc->ExportField($this->tipoDuracion);
						if ($this->fechaInicio->Exportable) $Doc->ExportField($this->fechaInicio);
						if ($this->fechaFin->Exportable) $Doc->ExportField($this->fechaFin);
						if ($this->predecesora->Exportable) $Doc->ExportField($this->predecesora);
						if ($this->recurso->Exportable) $Doc->ExportField($this->recurso);
						if ($this->estado->Exportable) $Doc->ExportField($this->estado);
						if ($this->estatus->Exportable) $Doc->ExportField($this->estatus);
						if ($this->Resultado->Exportable) $Doc->ExportField($this->Resultado);
					} else {
						if ($this->idActividad->Exportable) $Doc->ExportField($this->idActividad);
						if ($this->avance->Exportable) $Doc->ExportField($this->avance);
						if ($this->nombre->Exportable) $Doc->ExportField($this->nombre);
						if ($this->duracion->Exportable) $Doc->ExportField($this->duracion);
						if ($this->tipoDuracion->Exportable) $Doc->ExportField($this->tipoDuracion);
						if ($this->fechaInicio->Exportable) $Doc->ExportField($this->fechaInicio);
						if ($this->fechaFin->Exportable) $Doc->ExportField($this->fechaFin);
						if ($this->predecesora->Exportable) $Doc->ExportField($this->predecesora);
						if ($this->recurso->Exportable) $Doc->ExportField($this->recurso);
						if ($this->estado->Exportable) $Doc->ExportField($this->estado);
						if ($this->estatus->Exportable) $Doc->ExportField($this->estatus);
						if ($this->Resultado->Exportable) $Doc->ExportField($this->Resultado);
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
