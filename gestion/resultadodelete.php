<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "resultadoinfo.php" ?>
<?php include_once "objetivoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$resultado_delete = NULL; // Initialize page object first

class cresultado_delete extends cresultado {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}";

	// Table name
	var $TableName = 'resultado';

	// Page object name
	var $PageObjName = 'resultado_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}
    var $AuditTrailOnAdd = FALSE;
    var $AuditTrailOnEdit = FALSE;
    var $AuditTrailOnDelete = TRUE;
    var $AuditTrailOnView = FALSE;
    var $AuditTrailOnViewData = FALSE;
    var $AuditTrailOnSearch = FALSE;

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = TRUE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (resultado)
		if (!isset($GLOBALS["resultado"]) || get_class($GLOBALS["resultado"]) == "cresultado") {
			$GLOBALS["resultado"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["resultado"];
		}

		// Table object (objetivo)
		if (!isset($GLOBALS['objetivo'])) $GLOBALS['objetivo'] = new cobjetivo();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'resultado', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (usuario)
		if (!isset($UserTable)) {
			$UserTable = new cusuario();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("resultadolist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->idResultado->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $resultado;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($resultado);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("resultadolist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in resultado class, resultadoinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("resultadolist.php"); // Return to list
			}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->idResultado->setDbValue($rs->fields('idResultado'));
		$this->objetivo->setDbValue($rs->fields('objetivo'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->tiempoEstimado->setDbValue($rs->fields('tiempoEstimado'));
		$this->tiempoTipo->setDbValue($rs->fields('tiempoTipo'));
		$this->fechaInicio->setDbValue($rs->fields('fechaInicio'));
		$this->fechaFin->setDbValue($rs->fields('fechaFin'));
		$this->estatus->setDbValue($rs->fields('estatus'));
		$this->estado->setDbValue($rs->fields('estado'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->idResultado->DbValue = $row['idResultado'];
		$this->objetivo->DbValue = $row['objetivo'];
		$this->nombre->DbValue = $row['nombre'];
		$this->tiempoEstimado->DbValue = $row['tiempoEstimado'];
		$this->tiempoTipo->DbValue = $row['tiempoTipo'];
		$this->fechaInicio->DbValue = $row['fechaInicio'];
		$this->fechaFin->DbValue = $row['fechaFin'];
		$this->estatus->DbValue = $row['estatus'];
		$this->estado->DbValue = $row['estado'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// idResultado
		// objetivo
		// nombre
		// tiempoEstimado
		// tiempoTipo
		// fechaInicio
		// fechaFin
		// estatus
		// estado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// idResultado
		$this->idResultado->ViewValue = $this->idResultado->CurrentValue;
		$this->idResultado->ViewCustomAttributes = "";

		// objetivo
		if (strval($this->objetivo->CurrentValue) <> "") {
			$sFilterWrk = "`idObjetivo`" . ew_SearchString("=", $this->objetivo->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `idObjetivo`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `objetivo`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->objetivo, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->objetivo->ViewValue = $this->objetivo->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->objetivo->ViewValue = $this->objetivo->CurrentValue;
			}
		} else {
			$this->objetivo->ViewValue = NULL;
		}
		$this->objetivo->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// tiempoEstimado
		$this->tiempoEstimado->ViewValue = $this->tiempoEstimado->CurrentValue;
		$this->tiempoEstimado->ViewCustomAttributes = "";

		// tiempoTipo
		if (strval($this->tiempoTipo->CurrentValue) <> "") {
			$this->tiempoTipo->ViewValue = $this->tiempoTipo->OptionCaption($this->tiempoTipo->CurrentValue);
		} else {
			$this->tiempoTipo->ViewValue = NULL;
		}
		$this->tiempoTipo->ViewCustomAttributes = "";

		// fechaInicio
		$this->fechaInicio->ViewValue = $this->fechaInicio->CurrentValue;
		$this->fechaInicio->ViewValue = ew_FormatDateTime($this->fechaInicio->ViewValue, 7);
		$this->fechaInicio->ViewCustomAttributes = "";

		// fechaFin
		$this->fechaFin->ViewValue = $this->fechaFin->CurrentValue;
		$this->fechaFin->ViewValue = ew_FormatDateTime($this->fechaFin->ViewValue, 7);
		$this->fechaFin->ViewCustomAttributes = "";

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

			// idResultado
			$this->idResultado->LinkCustomAttributes = "";
			$this->idResultado->HrefValue = "";
			$this->idResultado->TooltipValue = "";

			// objetivo
			$this->objetivo->LinkCustomAttributes = "";
			$this->objetivo->HrefValue = "";
			$this->objetivo->TooltipValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// tiempoEstimado
			$this->tiempoEstimado->LinkCustomAttributes = "";
			$this->tiempoEstimado->HrefValue = "";
			$this->tiempoEstimado->TooltipValue = "";

			// tiempoTipo
			$this->tiempoTipo->LinkCustomAttributes = "";
			$this->tiempoTipo->HrefValue = "";
			$this->tiempoTipo->TooltipValue = "";

			// fechaInicio
			$this->fechaInicio->LinkCustomAttributes = "";
			$this->fechaInicio->HrefValue = "";
			$this->fechaInicio->TooltipValue = "";

			// fechaFin
			$this->fechaFin->LinkCustomAttributes = "";
			$this->fechaFin->HrefValue = "";
			$this->fechaFin->TooltipValue = "";

			// estatus
			$this->estatus->LinkCustomAttributes = "";
			$this->estatus->HrefValue = "";
			$this->estatus->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();
		if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteBegin")); // Batch delete begin

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['idResultado'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
			if ($DeleteRows) {
				foreach ($rsold as $row)
					$this->WriteAuditTrailOnDelete($row);
			}
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteSuccess")); // Batch delete success
		} else {
			$conn->RollbackTrans(); // Rollback changes
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteRollback")); // Batch delete rollback
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "objetivo") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_idObjetivo"] <> "") {
					$GLOBALS["objetivo"]->idObjetivo->setQueryStringValue($_GET["fk_idObjetivo"]);
					$this->objetivo->setQueryStringValue($GLOBALS["objetivo"]->idObjetivo->QueryStringValue);
					$this->objetivo->setSessionValue($this->objetivo->QueryStringValue);
					if (!is_numeric($GLOBALS["objetivo"]->idObjetivo->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		} elseif (isset($_POST[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_POST[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "objetivo") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_idObjetivo"] <> "") {
					$GLOBALS["objetivo"]->idObjetivo->setFormValue($_POST["fk_idObjetivo"]);
					$this->objetivo->setFormValue($GLOBALS["objetivo"]->idObjetivo->FormValue);
					$this->objetivo->setSessionValue($this->objetivo->FormValue);
					if (!is_numeric($GLOBALS["objetivo"]->idObjetivo->FormValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "objetivo") {
				if ($this->objetivo->CurrentValue == "") $this->objetivo->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("resultadolist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'resultado';
		$usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnDelete) return;
		$table = 'resultado';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['idResultado'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$curUser = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$oldvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$oldvalue = $rs[$fldname];
					else
						$oldvalue = "[MEMO]"; // Memo field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$oldvalue = "[XML]"; // XML field
				} else {
					$oldvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $curUser, "D", $table, $fldname, $key, $oldvalue, "");
			}
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($resultado_delete)) $resultado_delete = new cresultado_delete();

// Page init
$resultado_delete->Page_Init();

// Page main
$resultado_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$resultado_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fresultadodelete = new ew_Form("fresultadodelete", "delete");

// Form_CustomValidate event
fresultadodelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fresultadodelete.ValidateRequired = true;
<?php } else { ?>
fresultadodelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fresultadodelete.Lists["x_objetivo"] = {"LinkField":"x_idObjetivo","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fresultadodelete.Lists["x_tiempoTipo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fresultadodelete.Lists["x_tiempoTipo"].Options = <?php echo json_encode($resultado->tiempoTipo->Options()) ?>;
fresultadodelete.Lists["x_estatus"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fresultadodelete.Lists["x_estatus"].Options = <?php echo json_encode($resultado->estatus->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $resultado_delete->ShowPageHeader(); ?>
<?php
$resultado_delete->ShowMessage();
?>
<form name="fresultadodelete" id="fresultadodelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($resultado_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $resultado_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="resultado">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($resultado_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $resultado->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($resultado->idResultado->Visible) { // idResultado ?>
		<th><span id="elh_resultado_idResultado" class="resultado_idResultado"><?php echo $resultado->idResultado->FldCaption() ?></span></th>
<?php } ?>
<?php if ($resultado->objetivo->Visible) { // objetivo ?>
		<th><span id="elh_resultado_objetivo" class="resultado_objetivo"><?php echo $resultado->objetivo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($resultado->nombre->Visible) { // nombre ?>
		<th><span id="elh_resultado_nombre" class="resultado_nombre"><?php echo $resultado->nombre->FldCaption() ?></span></th>
<?php } ?>
<?php if ($resultado->tiempoEstimado->Visible) { // tiempoEstimado ?>
		<th><span id="elh_resultado_tiempoEstimado" class="resultado_tiempoEstimado"><?php echo $resultado->tiempoEstimado->FldCaption() ?></span></th>
<?php } ?>
<?php if ($resultado->tiempoTipo->Visible) { // tiempoTipo ?>
		<th><span id="elh_resultado_tiempoTipo" class="resultado_tiempoTipo"><?php echo $resultado->tiempoTipo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($resultado->fechaInicio->Visible) { // fechaInicio ?>
		<th><span id="elh_resultado_fechaInicio" class="resultado_fechaInicio"><?php echo $resultado->fechaInicio->FldCaption() ?></span></th>
<?php } ?>
<?php if ($resultado->fechaFin->Visible) { // fechaFin ?>
		<th><span id="elh_resultado_fechaFin" class="resultado_fechaFin"><?php echo $resultado->fechaFin->FldCaption() ?></span></th>
<?php } ?>
<?php if ($resultado->estatus->Visible) { // estatus ?>
		<th><span id="elh_resultado_estatus" class="resultado_estatus"><?php echo $resultado->estatus->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$resultado_delete->RecCnt = 0;
$i = 0;
while (!$resultado_delete->Recordset->EOF) {
	$resultado_delete->RecCnt++;
	$resultado_delete->RowCnt++;

	// Set row properties
	$resultado->ResetAttrs();
	$resultado->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$resultado_delete->LoadRowValues($resultado_delete->Recordset);

	// Render row
	$resultado_delete->RenderRow();
?>
	<tr<?php echo $resultado->RowAttributes() ?>>
<?php if ($resultado->idResultado->Visible) { // idResultado ?>
		<td<?php echo $resultado->idResultado->CellAttributes() ?>>
<span id="el<?php echo $resultado_delete->RowCnt ?>_resultado_idResultado" class="resultado_idResultado">
<span<?php echo $resultado->idResultado->ViewAttributes() ?>>
<?php echo $resultado->idResultado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($resultado->objetivo->Visible) { // objetivo ?>
		<td<?php echo $resultado->objetivo->CellAttributes() ?>>
<span id="el<?php echo $resultado_delete->RowCnt ?>_resultado_objetivo" class="resultado_objetivo">
<span<?php echo $resultado->objetivo->ViewAttributes() ?>>
<?php echo $resultado->objetivo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($resultado->nombre->Visible) { // nombre ?>
		<td<?php echo $resultado->nombre->CellAttributes() ?>>
<span id="el<?php echo $resultado_delete->RowCnt ?>_resultado_nombre" class="resultado_nombre">
<span<?php echo $resultado->nombre->ViewAttributes() ?>>
<?php echo $resultado->nombre->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($resultado->tiempoEstimado->Visible) { // tiempoEstimado ?>
		<td<?php echo $resultado->tiempoEstimado->CellAttributes() ?>>
<span id="el<?php echo $resultado_delete->RowCnt ?>_resultado_tiempoEstimado" class="resultado_tiempoEstimado">
<span<?php echo $resultado->tiempoEstimado->ViewAttributes() ?>>
<?php echo $resultado->tiempoEstimado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($resultado->tiempoTipo->Visible) { // tiempoTipo ?>
		<td<?php echo $resultado->tiempoTipo->CellAttributes() ?>>
<span id="el<?php echo $resultado_delete->RowCnt ?>_resultado_tiempoTipo" class="resultado_tiempoTipo">
<span<?php echo $resultado->tiempoTipo->ViewAttributes() ?>>
<?php echo $resultado->tiempoTipo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($resultado->fechaInicio->Visible) { // fechaInicio ?>
		<td<?php echo $resultado->fechaInicio->CellAttributes() ?>>
<span id="el<?php echo $resultado_delete->RowCnt ?>_resultado_fechaInicio" class="resultado_fechaInicio">
<span<?php echo $resultado->fechaInicio->ViewAttributes() ?>>
<?php echo $resultado->fechaInicio->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($resultado->fechaFin->Visible) { // fechaFin ?>
		<td<?php echo $resultado->fechaFin->CellAttributes() ?>>
<span id="el<?php echo $resultado_delete->RowCnt ?>_resultado_fechaFin" class="resultado_fechaFin">
<span<?php echo $resultado->fechaFin->ViewAttributes() ?>>
<?php echo $resultado->fechaFin->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($resultado->estatus->Visible) { // estatus ?>
		<td<?php echo $resultado->estatus->CellAttributes() ?>>
<span id="el<?php echo $resultado_delete->RowCnt ?>_resultado_estatus" class="resultado_estatus">
<span<?php echo $resultado->estatus->ViewAttributes() ?>>
<?php echo $resultado->estatus->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$resultado_delete->Recordset->MoveNext();
}
$resultado_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $resultado_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fresultadodelete.Init();
</script>
<?php
$resultado_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$resultado_delete->Page_Terminate();
?>
