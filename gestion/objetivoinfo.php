<?php

// Global variable for table object
$objetivo = NULL;

//
// Table class for objetivo
//
class cobjetivo extends cTable {
	var $idObjetivo;
	var $nombre;
	var $comentarios;
	var $duracion;
	var $formatoDuracion;
	var $fechaInicio;
	var $fechFin;
	var $proyecto;
	var $tipo;
	var $estado;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'objetivo';
		$this->TableName = 'objetivo';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`objetivo`";
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

		// idObjetivo
		$this->idObjetivo = new cField('objetivo', 'objetivo', 'x_idObjetivo', 'idObjetivo', '`idObjetivo`', '`idObjetivo`', 3, -1, FALSE, '`idObjetivo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->idObjetivo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idObjetivo'] = &$this->idObjetivo;

		// nombre
		$this->nombre = new cField('objetivo', 'objetivo', 'x_nombre', 'nombre', '`nombre`', '`nombre`', 200, -1, FALSE, '`nombre`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['nombre'] = &$this->nombre;

		// comentarios
		$this->comentarios = new cField('objetivo', 'objetivo', 'x_comentarios', 'comentarios', '`comentarios`', '`comentarios`', 200, -1, FALSE, '`comentarios`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['comentarios'] = &$this->comentarios;

		// duracion
		$this->duracion = new cField('objetivo', 'objetivo', 'x_duracion', 'duracion', '`duracion`', '`duracion`', 3, -1, FALSE, '`duracion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->duracion->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['duracion'] = &$this->duracion;

		// formatoDuracion
		$this->formatoDuracion = new cField('objetivo', 'objetivo', 'x_formatoDuracion', 'formatoDuracion', '`formatoDuracion`', '`formatoDuracion`', 202, -1, FALSE, '`formatoDuracion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->formatoDuracion->OptionCount = 2;
		$this->fields['formatoDuracion'] = &$this->formatoDuracion;

		// fechaInicio
		$this->fechaInicio = new cField('objetivo', 'objetivo', 'x_fechaInicio', 'fechaInicio', '`fechaInicio`', 'DATE_FORMAT(`fechaInicio`, \'%d/%m/%Y\')', 135, 7, FALSE, '`fechaInicio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fechaInicio->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fechaInicio'] = &$this->fechaInicio;

		// fechFin
		$this->fechFin = new cField('objetivo', 'objetivo', 'x_fechFin', 'fechFin', '`fechFin`', 'DATE_FORMAT(`fechFin`, \'%d/%m/%Y\')', 135, 7, FALSE, '`fechFin`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fechFin->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fechFin'] = &$this->fechFin;

		// proyecto
		$this->proyecto = new cField('objetivo', 'objetivo', 'x_proyecto', 'proyecto', '`proyecto`', '`proyecto`', 3, -1, FALSE, '`proyecto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->proyecto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['proyecto'] = &$this->proyecto;

		// tipo
		$this->tipo = new cField('objetivo', 'objetivo', 'x_tipo', 'tipo', '`tipo`', '`tipo`', 3, -1, FALSE, '`tipo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->tipo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['tipo'] = &$this->tipo;

		// estado
		$this->estado = new cField('objetivo', 'objetivo', 'x_estado', 'estado', '`estado`', '`estado`', 202, -1, FALSE, '`estado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
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
		if ($this->getCurrentMasterTable() == "proyecto") {
			if ($this->proyecto->getSessionValue() <> "")
				$sMasterFilter .= "`idProyecto`=" . ew_QuotedValue($this->proyecto->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "proyecto") {
			if ($this->proyecto->getSessionValue() <> "")
				$sDetailFilter .= "`proyecto`=" . ew_QuotedValue($this->proyecto->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_proyecto() {
		return "`idProyecto`=@idProyecto@";
	}

	// Detail filter
	function SqlDetailFilter_proyecto() {
		return "`proyecto`=@proyecto@";
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
		if ($this->getCurrentDetailTable() == "resultado") {
			$sDetailUrl = $GLOBALS["resultado"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_idObjetivo=" . urlencode($this->idObjetivo->CurrentValue);
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "objetivolist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`objetivo`";
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
		$this->TableFilter = "`estado`= 'Activo'";
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
			if (array_key_exists('idObjetivo', $rs))
				ew_AddFilter($where, ew_QuotedName('idObjetivo', $this->DBID) . '=' . ew_QuotedValue($rs['idObjetivo'], $this->idObjetivo->FldDataType, $this->DBID));
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
		return "`idObjetivo` = @idObjetivo@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->idObjetivo->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@idObjetivo@", ew_AdjustSql($this->idObjetivo->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "objetivolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "objetivolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("objetivoview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("objetivoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "objetivoadd.php?" . $this->UrlParm($parm);
		else
			$url = "objetivoadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("objetivoedit.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("objetivoedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
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
			$url = $this->KeyUrl("objetivoadd.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("objetivoadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("objetivodelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		if ($this->getCurrentMasterTable() == "proyecto" && strpos($url, EW_TABLE_SHOW_MASTER . "=") === FALSE) {
			$url .= (strpos($url, "?") !== FALSE ? "&" : "?") . EW_TABLE_SHOW_MASTER . "=" . $this->getCurrentMasterTable();
			$url .= "&fk_idProyecto=" . urlencode($this->proyecto->CurrentValue);
		}
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "idObjetivo:" . ew_VarToJson($this->idObjetivo->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->idObjetivo->CurrentValue)) {
			$sUrl .= "idObjetivo=" . urlencode($this->idObjetivo->CurrentValue);
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
			if ($isPost && isset($_POST["idObjetivo"]))
				$arKeys[] = ew_StripSlashes($_POST["idObjetivo"]);
			elseif (isset($_GET["idObjetivo"]))
				$arKeys[] = ew_StripSlashes($_GET["idObjetivo"]);
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
			$this->idObjetivo->CurrentValue = $key;
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
		$this->idObjetivo->setDbValue($rs->fields('idObjetivo'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->comentarios->setDbValue($rs->fields('comentarios'));
		$this->duracion->setDbValue($rs->fields('duracion'));
		$this->formatoDuracion->setDbValue($rs->fields('formatoDuracion'));
		$this->fechaInicio->setDbValue($rs->fields('fechaInicio'));
		$this->fechFin->setDbValue($rs->fields('fechFin'));
		$this->proyecto->setDbValue($rs->fields('proyecto'));
		$this->tipo->setDbValue($rs->fields('tipo'));
		$this->estado->setDbValue($rs->fields('estado'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// idObjetivo
		// nombre
		// comentarios
		// duracion
		// formatoDuracion
		// fechaInicio
		// fechFin
		// proyecto
		// tipo
		// estado
		// idObjetivo

		$this->idObjetivo->ViewValue = $this->idObjetivo->CurrentValue;
		$this->idObjetivo->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// comentarios
		$this->comentarios->ViewValue = $this->comentarios->CurrentValue;
		$this->comentarios->ViewCustomAttributes = "";

		// duracion
		$this->duracion->ViewValue = $this->duracion->CurrentValue;
		$this->duracion->ViewCustomAttributes = "";

		// formatoDuracion
		if (strval($this->formatoDuracion->CurrentValue) <> "") {
			$this->formatoDuracion->ViewValue = $this->formatoDuracion->OptionCaption($this->formatoDuracion->CurrentValue);
		} else {
			$this->formatoDuracion->ViewValue = NULL;
		}
		$this->formatoDuracion->ViewCustomAttributes = "";

		// fechaInicio
		$this->fechaInicio->ViewValue = $this->fechaInicio->CurrentValue;
		$this->fechaInicio->ViewValue = ew_FormatDateTime($this->fechaInicio->ViewValue, 7);
		$this->fechaInicio->ViewCustomAttributes = "";

		// fechFin
		$this->fechFin->ViewValue = $this->fechFin->CurrentValue;
		$this->fechFin->ViewValue = ew_FormatDateTime($this->fechFin->ViewValue, 7);
		$this->fechFin->ViewCustomAttributes = "";

		// proyecto
		if (strval($this->proyecto->CurrentValue) <> "") {
			$sFilterWrk = "`idProyecto`" . ew_SearchString("=", $this->proyecto->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `idProyecto`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `proyecto`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->proyecto, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->proyecto->ViewValue = $this->proyecto->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->proyecto->ViewValue = $this->proyecto->CurrentValue;
			}
		} else {
			$this->proyecto->ViewValue = NULL;
		}
		$this->proyecto->ViewCustomAttributes = "";

		// tipo
		if (strval($this->tipo->CurrentValue) <> "") {
			$sFilterWrk = "`idObjetivosTipo`" . ew_SearchString("=", $this->tipo->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `idObjetivosTipo`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `objetivos_tipo`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->tipo, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->tipo->ViewValue = $this->tipo->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->tipo->ViewValue = $this->tipo->CurrentValue;
			}
		} else {
			$this->tipo->ViewValue = NULL;
		}
		$this->tipo->ViewCustomAttributes = "";

		// estado
		if (strval($this->estado->CurrentValue) <> "") {
			$this->estado->ViewValue = $this->estado->OptionCaption($this->estado->CurrentValue);
		} else {
			$this->estado->ViewValue = NULL;
		}
		$this->estado->ViewCustomAttributes = "";

		// idObjetivo
		$this->idObjetivo->LinkCustomAttributes = "";
		$this->idObjetivo->HrefValue = "";
		$this->idObjetivo->TooltipValue = "";

		// nombre
		$this->nombre->LinkCustomAttributes = "";
		$this->nombre->HrefValue = "";
		$this->nombre->TooltipValue = "";

		// comentarios
		$this->comentarios->LinkCustomAttributes = "";
		$this->comentarios->HrefValue = "";
		$this->comentarios->TooltipValue = "";

		// duracion
		$this->duracion->LinkCustomAttributes = "";
		$this->duracion->HrefValue = "";
		$this->duracion->TooltipValue = "";

		// formatoDuracion
		$this->formatoDuracion->LinkCustomAttributes = "";
		$this->formatoDuracion->HrefValue = "";
		$this->formatoDuracion->TooltipValue = "";

		// fechaInicio
		$this->fechaInicio->LinkCustomAttributes = "";
		$this->fechaInicio->HrefValue = "";
		$this->fechaInicio->TooltipValue = "";

		// fechFin
		$this->fechFin->LinkCustomAttributes = "";
		$this->fechFin->HrefValue = "";
		$this->fechFin->TooltipValue = "";

		// proyecto
		$this->proyecto->LinkCustomAttributes = "";
		$this->proyecto->HrefValue = "";
		$this->proyecto->TooltipValue = "";

		// tipo
		$this->tipo->LinkCustomAttributes = "";
		$this->tipo->HrefValue = "";
		$this->tipo->TooltipValue = "";

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

		// idObjetivo
		$this->idObjetivo->EditAttrs["class"] = "form-control";
		$this->idObjetivo->EditCustomAttributes = "";
		$this->idObjetivo->EditValue = $this->idObjetivo->CurrentValue;
		$this->idObjetivo->ViewCustomAttributes = "";

		// nombre
		$this->nombre->EditAttrs["class"] = "form-control";
		$this->nombre->EditCustomAttributes = "";
		$this->nombre->EditValue = $this->nombre->CurrentValue;
		$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

		// comentarios
		$this->comentarios->EditAttrs["class"] = "form-control";
		$this->comentarios->EditCustomAttributes = "";
		$this->comentarios->EditValue = $this->comentarios->CurrentValue;
		$this->comentarios->PlaceHolder = ew_RemoveHtml($this->comentarios->FldCaption());

		// duracion
		$this->duracion->EditAttrs["class"] = "form-control";
		$this->duracion->EditCustomAttributes = "";
		$this->duracion->EditValue = $this->duracion->CurrentValue;
		$this->duracion->PlaceHolder = ew_RemoveHtml($this->duracion->FldCaption());

		// formatoDuracion
		$this->formatoDuracion->EditAttrs["class"] = "form-control";
		$this->formatoDuracion->EditCustomAttributes = "";
		$this->formatoDuracion->EditValue = $this->formatoDuracion->Options(TRUE);

		// fechaInicio
		$this->fechaInicio->EditAttrs["class"] = "form-control";
		$this->fechaInicio->EditCustomAttributes = "";
		$this->fechaInicio->EditValue = ew_FormatDateTime($this->fechaInicio->CurrentValue, 7);
		$this->fechaInicio->PlaceHolder = ew_RemoveHtml($this->fechaInicio->FldCaption());

		// fechFin
		$this->fechFin->EditAttrs["class"] = "form-control";
		$this->fechFin->EditCustomAttributes = "";
		$this->fechFin->EditValue = ew_FormatDateTime($this->fechFin->CurrentValue, 7);
		$this->fechFin->PlaceHolder = ew_RemoveHtml($this->fechFin->FldCaption());

		// proyecto
		$this->proyecto->EditAttrs["class"] = "form-control";
		$this->proyecto->EditCustomAttributes = "";
		if ($this->proyecto->getSessionValue() <> "") {
			$this->proyecto->CurrentValue = $this->proyecto->getSessionValue();
		if (strval($this->proyecto->CurrentValue) <> "") {
			$sFilterWrk = "`idProyecto`" . ew_SearchString("=", $this->proyecto->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `idProyecto`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `proyecto`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->proyecto, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->proyecto->ViewValue = $this->proyecto->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->proyecto->ViewValue = $this->proyecto->CurrentValue;
			}
		} else {
			$this->proyecto->ViewValue = NULL;
		}
		$this->proyecto->ViewCustomAttributes = "";
		} else {
		}

		// tipo
		$this->tipo->EditAttrs["class"] = "form-control";
		$this->tipo->EditCustomAttributes = "";

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
					if ($this->idObjetivo->Exportable) $Doc->ExportCaption($this->idObjetivo);
					if ($this->nombre->Exportable) $Doc->ExportCaption($this->nombre);
					if ($this->comentarios->Exportable) $Doc->ExportCaption($this->comentarios);
					if ($this->duracion->Exportable) $Doc->ExportCaption($this->duracion);
					if ($this->formatoDuracion->Exportable) $Doc->ExportCaption($this->formatoDuracion);
					if ($this->fechaInicio->Exportable) $Doc->ExportCaption($this->fechaInicio);
					if ($this->fechFin->Exportable) $Doc->ExportCaption($this->fechFin);
					if ($this->proyecto->Exportable) $Doc->ExportCaption($this->proyecto);
					if ($this->tipo->Exportable) $Doc->ExportCaption($this->tipo);
					if ($this->estado->Exportable) $Doc->ExportCaption($this->estado);
				} else {
					if ($this->idObjetivo->Exportable) $Doc->ExportCaption($this->idObjetivo);
					if ($this->nombre->Exportable) $Doc->ExportCaption($this->nombre);
					if ($this->comentarios->Exportable) $Doc->ExportCaption($this->comentarios);
					if ($this->duracion->Exportable) $Doc->ExportCaption($this->duracion);
					if ($this->formatoDuracion->Exportable) $Doc->ExportCaption($this->formatoDuracion);
					if ($this->fechaInicio->Exportable) $Doc->ExportCaption($this->fechaInicio);
					if ($this->fechFin->Exportable) $Doc->ExportCaption($this->fechFin);
					if ($this->proyecto->Exportable) $Doc->ExportCaption($this->proyecto);
					if ($this->tipo->Exportable) $Doc->ExportCaption($this->tipo);
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
						if ($this->idObjetivo->Exportable) $Doc->ExportField($this->idObjetivo);
						if ($this->nombre->Exportable) $Doc->ExportField($this->nombre);
						if ($this->comentarios->Exportable) $Doc->ExportField($this->comentarios);
						if ($this->duracion->Exportable) $Doc->ExportField($this->duracion);
						if ($this->formatoDuracion->Exportable) $Doc->ExportField($this->formatoDuracion);
						if ($this->fechaInicio->Exportable) $Doc->ExportField($this->fechaInicio);
						if ($this->fechFin->Exportable) $Doc->ExportField($this->fechFin);
						if ($this->proyecto->Exportable) $Doc->ExportField($this->proyecto);
						if ($this->tipo->Exportable) $Doc->ExportField($this->tipo);
						if ($this->estado->Exportable) $Doc->ExportField($this->estado);
					} else {
						if ($this->idObjetivo->Exportable) $Doc->ExportField($this->idObjetivo);
						if ($this->nombre->Exportable) $Doc->ExportField($this->nombre);
						if ($this->comentarios->Exportable) $Doc->ExportField($this->comentarios);
						if ($this->duracion->Exportable) $Doc->ExportField($this->duracion);
						if ($this->formatoDuracion->Exportable) $Doc->ExportField($this->formatoDuracion);
						if ($this->fechaInicio->Exportable) $Doc->ExportField($this->fechaInicio);
						if ($this->fechFin->Exportable) $Doc->ExportField($this->fechFin);
						if ($this->proyecto->Exportable) $Doc->ExportField($this->proyecto);
						if ($this->tipo->Exportable) $Doc->ExportField($this->tipo);
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
