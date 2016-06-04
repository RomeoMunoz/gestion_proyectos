<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "objetivoinfo.php" ?>
<?php include_once "proyectoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "resultadogridcls.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$objetivo_edit = NULL; // Initialize page object first

class cobjetivo_edit extends cobjetivo {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}";

	// Table name
	var $TableName = 'objetivo';

	// Page object name
	var $PageObjName = 'objetivo_edit';

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
    var $AuditTrailOnEdit = TRUE;
    var $AuditTrailOnDelete = FALSE;
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

		// Table object (objetivo)
		if (!isset($GLOBALS["objetivo"]) || get_class($GLOBALS["objetivo"]) == "cobjetivo") {
			$GLOBALS["objetivo"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["objetivo"];
		}

		// Table object (proyecto)
		if (!isset($GLOBALS['proyecto'])) $GLOBALS['proyecto'] = new cproyecto();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'objetivo', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("objetivolist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {

			// Process auto fill for detail table 'resultado'
			if (@$_POST["grid"] == "fresultadogrid") {
				if (!isset($GLOBALS["resultado_grid"])) $GLOBALS["resultado_grid"] = new cresultado_grid;
				$GLOBALS["resultado_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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
		global $EW_EXPORT, $objetivo;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($objetivo);
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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		$this->FormClassName = "ewForm ewEditForm";
		if (ew_IsMobile())
			$this->FormClassName = ew_Concat("form-horizontal", $this->FormClassName, " ");

		// Load key from QueryString
		if (@$_GET["idObjetivo"] <> "") {
			$this->idObjetivo->setQueryStringValue($_GET["idObjetivo"]);
		}

		// Set up master detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values

			// Set up detail parameters
			$this->SetUpDetailParms();
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->idObjetivo->CurrentValue == "")
			$this->Page_Terminate("objetivolist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("objetivolist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			Case "U": // Update
				if ($this->getCurrentDetailTable() <> "") // Master/detail edit
					$sReturnUrl = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
				else
					$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "objetivolist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed

					// Set up detail parameters
					$this->SetUpDetailParms();
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nombre->FldIsDetailKey) {
			$this->nombre->setFormValue($objForm->GetValue("x_nombre"));
		}
		if (!$this->comentarios->FldIsDetailKey) {
			$this->comentarios->setFormValue($objForm->GetValue("x_comentarios"));
		}
		if (!$this->duracion->FldIsDetailKey) {
			$this->duracion->setFormValue($objForm->GetValue("x_duracion"));
		}
		if (!$this->formatoDuracion->FldIsDetailKey) {
			$this->formatoDuracion->setFormValue($objForm->GetValue("x_formatoDuracion"));
		}
		if (!$this->fechaInicio->FldIsDetailKey) {
			$this->fechaInicio->setFormValue($objForm->GetValue("x_fechaInicio"));
			$this->fechaInicio->CurrentValue = ew_UnFormatDateTime($this->fechaInicio->CurrentValue, 7);
		}
		if (!$this->fechFin->FldIsDetailKey) {
			$this->fechFin->setFormValue($objForm->GetValue("x_fechFin"));
			$this->fechFin->CurrentValue = ew_UnFormatDateTime($this->fechFin->CurrentValue, 7);
		}
		if (!$this->proyecto->FldIsDetailKey) {
			$this->proyecto->setFormValue($objForm->GetValue("x_proyecto"));
		}
		if (!$this->tipo->FldIsDetailKey) {
			$this->tipo->setFormValue($objForm->GetValue("x_tipo"));
		}
		if (!$this->estado->FldIsDetailKey) {
			$this->estado->setFormValue($objForm->GetValue("x_estado"));
		}
		if (!$this->idObjetivo->FldIsDetailKey)
			$this->idObjetivo->setFormValue($objForm->GetValue("x_idObjetivo"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->idObjetivo->CurrentValue = $this->idObjetivo->FormValue;
		$this->nombre->CurrentValue = $this->nombre->FormValue;
		$this->comentarios->CurrentValue = $this->comentarios->FormValue;
		$this->duracion->CurrentValue = $this->duracion->FormValue;
		$this->formatoDuracion->CurrentValue = $this->formatoDuracion->FormValue;
		$this->fechaInicio->CurrentValue = $this->fechaInicio->FormValue;
		$this->fechaInicio->CurrentValue = ew_UnFormatDateTime($this->fechaInicio->CurrentValue, 7);
		$this->fechFin->CurrentValue = $this->fechFin->FormValue;
		$this->fechFin->CurrentValue = ew_UnFormatDateTime($this->fechFin->CurrentValue, 7);
		$this->proyecto->CurrentValue = $this->proyecto->FormValue;
		$this->tipo->CurrentValue = $this->tipo->FormValue;
		$this->estado->CurrentValue = $this->estado->FormValue;
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->idObjetivo->DbValue = $row['idObjetivo'];
		$this->nombre->DbValue = $row['nombre'];
		$this->comentarios->DbValue = $row['comentarios'];
		$this->duracion->DbValue = $row['duracion'];
		$this->formatoDuracion->DbValue = $row['formatoDuracion'];
		$this->fechaInicio->DbValue = $row['fechaInicio'];
		$this->fechFin->DbValue = $row['fechFin'];
		$this->proyecto->DbValue = $row['proyecto'];
		$this->tipo->DbValue = $row['tipo'];
		$this->estado->DbValue = $row['estado'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nombre
			$this->nombre->EditAttrs["class"] = "form-control";
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->CurrentValue);
			$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

			// comentarios
			$this->comentarios->EditAttrs["class"] = "form-control";
			$this->comentarios->EditCustomAttributes = "";
			$this->comentarios->EditValue = ew_HtmlEncode($this->comentarios->CurrentValue);
			$this->comentarios->PlaceHolder = ew_RemoveHtml($this->comentarios->FldCaption());

			// duracion
			$this->duracion->EditAttrs["class"] = "form-control";
			$this->duracion->EditCustomAttributes = "";
			$this->duracion->EditValue = ew_HtmlEncode($this->duracion->CurrentValue);
			$this->duracion->PlaceHolder = ew_RemoveHtml($this->duracion->FldCaption());

			// formatoDuracion
			$this->formatoDuracion->EditAttrs["class"] = "form-control";
			$this->formatoDuracion->EditCustomAttributes = "";
			$this->formatoDuracion->EditValue = $this->formatoDuracion->Options(TRUE);

			// fechaInicio
			$this->fechaInicio->EditAttrs["class"] = "form-control";
			$this->fechaInicio->EditCustomAttributes = "";
			$this->fechaInicio->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fechaInicio->CurrentValue, 7));
			$this->fechaInicio->PlaceHolder = ew_RemoveHtml($this->fechaInicio->FldCaption());

			// fechFin
			$this->fechFin->EditAttrs["class"] = "form-control";
			$this->fechFin->EditCustomAttributes = "";
			$this->fechFin->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fechFin->CurrentValue, 7));
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
			if (trim(strval($this->proyecto->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idProyecto`" . ew_SearchString("=", $this->proyecto->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `idProyecto`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `proyecto`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->proyecto, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->proyecto->EditValue = $arwrk;
			}

			// tipo
			$this->tipo->EditAttrs["class"] = "form-control";
			$this->tipo->EditCustomAttributes = "";
			if (trim(strval($this->tipo->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idObjetivosTipo`" . ew_SearchString("=", $this->tipo->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `idObjetivosTipo`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `objetivos_tipo`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->tipo, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->tipo->EditValue = $arwrk;

			// estado
			$this->estado->EditAttrs["class"] = "form-control";
			$this->estado->EditCustomAttributes = "";
			$this->estado->EditValue = $this->estado->Options(TRUE);

			// Edit refer script
			// nombre

			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";

			// comentarios
			$this->comentarios->LinkCustomAttributes = "";
			$this->comentarios->HrefValue = "";

			// duracion
			$this->duracion->LinkCustomAttributes = "";
			$this->duracion->HrefValue = "";

			// formatoDuracion
			$this->formatoDuracion->LinkCustomAttributes = "";
			$this->formatoDuracion->HrefValue = "";

			// fechaInicio
			$this->fechaInicio->LinkCustomAttributes = "";
			$this->fechaInicio->HrefValue = "";

			// fechFin
			$this->fechFin->LinkCustomAttributes = "";
			$this->fechFin->HrefValue = "";

			// proyecto
			$this->proyecto->LinkCustomAttributes = "";
			$this->proyecto->HrefValue = "";

			// tipo
			$this->tipo->LinkCustomAttributes = "";
			$this->tipo->HrefValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->nombre->FldIsDetailKey && !is_null($this->nombre->FormValue) && $this->nombre->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nombre->FldCaption(), $this->nombre->ReqErrMsg));
		}
		if (!$this->duracion->FldIsDetailKey && !is_null($this->duracion->FormValue) && $this->duracion->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->duracion->FldCaption(), $this->duracion->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->duracion->FormValue)) {
			ew_AddMessage($gsFormError, $this->duracion->FldErrMsg());
		}
		if (!$this->fechaInicio->FldIsDetailKey && !is_null($this->fechaInicio->FormValue) && $this->fechaInicio->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fechaInicio->FldCaption(), $this->fechaInicio->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->fechaInicio->FormValue)) {
			ew_AddMessage($gsFormError, $this->fechaInicio->FldErrMsg());
		}
		if (!$this->fechFin->FldIsDetailKey && !is_null($this->fechFin->FormValue) && $this->fechFin->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fechFin->FldCaption(), $this->fechFin->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->fechFin->FormValue)) {
			ew_AddMessage($gsFormError, $this->fechFin->FldErrMsg());
		}
		if (!$this->proyecto->FldIsDetailKey && !is_null($this->proyecto->FormValue) && $this->proyecto->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->proyecto->FldCaption(), $this->proyecto->ReqErrMsg));
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("resultado", $DetailTblVar) && $GLOBALS["resultado"]->DetailEdit) {
			if (!isset($GLOBALS["resultado_grid"])) $GLOBALS["resultado_grid"] = new cresultado_grid(); // get detail page object
			$GLOBALS["resultado_grid"]->ValidateGridForm();
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// nombre
			$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, "", $this->nombre->ReadOnly);

			// comentarios
			$this->comentarios->SetDbValueDef($rsnew, $this->comentarios->CurrentValue, NULL, $this->comentarios->ReadOnly);

			// duracion
			$this->duracion->SetDbValueDef($rsnew, $this->duracion->CurrentValue, NULL, $this->duracion->ReadOnly);

			// formatoDuracion
			$this->formatoDuracion->SetDbValueDef($rsnew, $this->formatoDuracion->CurrentValue, NULL, $this->formatoDuracion->ReadOnly);

			// fechaInicio
			$this->fechaInicio->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechaInicio->CurrentValue, 7), ew_CurrentDate(), $this->fechaInicio->ReadOnly);

			// fechFin
			$this->fechFin->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechFin->CurrentValue, 7), ew_CurrentDate(), $this->fechFin->ReadOnly);

			// proyecto
			$this->proyecto->SetDbValueDef($rsnew, $this->proyecto->CurrentValue, 0, $this->proyecto->ReadOnly);

			// tipo
			$this->tipo->SetDbValueDef($rsnew, $this->tipo->CurrentValue, NULL, $this->tipo->ReadOnly);

			// estado
			$this->estado->SetDbValueDef($rsnew, $this->estado->CurrentValue, NULL, $this->estado->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}

				// Update detail records
				$DetailTblVar = explode(",", $this->getCurrentDetailTable());
				if ($EditRow) {
					if (in_array("resultado", $DetailTblVar) && $GLOBALS["resultado"]->DetailEdit) {
						if (!isset($GLOBALS["resultado_grid"])) $GLOBALS["resultado_grid"] = new cresultado_grid(); // Get detail page object
						$EditRow = $GLOBALS["resultado_grid"]->GridUpdate();
					}
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
					}
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		if ($EditRow) {
			$this->WriteAuditTrailOnEdit($rsold, $rsnew);
		}
		$rs->Close();
		return $EditRow;
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
			if ($sMasterTblVar == "proyecto") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_idProyecto"] <> "") {
					$GLOBALS["proyecto"]->idProyecto->setQueryStringValue($_GET["fk_idProyecto"]);
					$this->proyecto->setQueryStringValue($GLOBALS["proyecto"]->idProyecto->QueryStringValue);
					$this->proyecto->setSessionValue($this->proyecto->QueryStringValue);
					if (!is_numeric($GLOBALS["proyecto"]->idProyecto->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar == "proyecto") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_idProyecto"] <> "") {
					$GLOBALS["proyecto"]->idProyecto->setFormValue($_POST["fk_idProyecto"]);
					$this->proyecto->setFormValue($GLOBALS["proyecto"]->idProyecto->FormValue);
					$this->proyecto->setSessionValue($this->proyecto->FormValue);
					if (!is_numeric($GLOBALS["proyecto"]->idProyecto->FormValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);
			$this->setSessionWhere($this->GetDetailFilter());

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "proyecto") {
				if ($this->proyecto->CurrentValue == "") $this->proyecto->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("resultado", $DetailTblVar)) {
				if (!isset($GLOBALS["resultado_grid"]))
					$GLOBALS["resultado_grid"] = new cresultado_grid;
				if ($GLOBALS["resultado_grid"]->DetailEdit) {
					$GLOBALS["resultado_grid"]->CurrentMode = "edit";
					$GLOBALS["resultado_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["resultado_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["resultado_grid"]->setStartRecordNumber(1);
					$GLOBALS["resultado_grid"]->objetivo->FldIsDetailKey = TRUE;
					$GLOBALS["resultado_grid"]->objetivo->CurrentValue = $this->idObjetivo->CurrentValue;
					$GLOBALS["resultado_grid"]->objetivo->setSessionValue($GLOBALS["resultado_grid"]->objetivo->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("objetivolist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'objetivo';
		$usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		global $Language;
		if (!$this->AuditTrailOnEdit) return;
		$table = 'objetivo';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['idObjetivo'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserID();
		foreach (array_keys($rsnew) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_DATE) { // DateTime field
					$modified = (ew_FormatDateTime($rsold[$fldname], 0) <> ew_FormatDateTime($rsnew[$fldname], 0));
				} else {
					$modified = !ew_CompareValue($rsold[$fldname], $rsnew[$fldname]);
				}
				if ($modified) {
					if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") { // Password Field
						$oldvalue = $Language->Phrase("PasswordMask");
						$newvalue = $Language->Phrase("PasswordMask");
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) { // Memo field
						if (EW_AUDIT_TRAIL_TO_DATABASE) {
							$oldvalue = $rsold[$fldname];
							$newvalue = $rsnew[$fldname];
						} else {
							$oldvalue = "[MEMO]";
							$newvalue = "[MEMO]";
						}
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) { // XML field
						$oldvalue = "[XML]";
						$newvalue = "[XML]";
					} else {
						$oldvalue = $rsold[$fldname];
						$newvalue = $rsnew[$fldname];
					}
					ew_WriteAuditTrail("log", $dt, $id, $usr, "U", $table, $fldname, $key, $oldvalue, $newvalue);
				}
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($objetivo_edit)) $objetivo_edit = new cobjetivo_edit();

// Page init
$objetivo_edit->Page_Init();

// Page main
$objetivo_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$objetivo_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fobjetivoedit = new ew_Form("fobjetivoedit", "edit");

// Validate form
fobjetivoedit.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_nombre");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $objetivo->nombre->FldCaption(), $objetivo->nombre->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_duracion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $objetivo->duracion->FldCaption(), $objetivo->duracion->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_duracion");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($objetivo->duracion->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fechaInicio");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $objetivo->fechaInicio->FldCaption(), $objetivo->fechaInicio->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fechaInicio");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($objetivo->fechaInicio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fechFin");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $objetivo->fechFin->FldCaption(), $objetivo->fechFin->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fechFin");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($objetivo->fechFin->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_proyecto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $objetivo->proyecto->FldCaption(), $objetivo->proyecto->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fobjetivoedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fobjetivoedit.ValidateRequired = true;
<?php } else { ?>
fobjetivoedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fobjetivoedit.Lists["x_formatoDuracion"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fobjetivoedit.Lists["x_formatoDuracion"].Options = <?php echo json_encode($objetivo->formatoDuracion->Options()) ?>;
fobjetivoedit.Lists["x_proyecto"] = {"LinkField":"x_idProyecto","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fobjetivoedit.Lists["x_tipo"] = {"LinkField":"x_idObjetivosTipo","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fobjetivoedit.Lists["x_estado"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fobjetivoedit.Lists["x_estado"].Options = <?php echo json_encode($objetivo->estado->Options()) ?>;

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
<?php $objetivo_edit->ShowPageHeader(); ?>
<?php
$objetivo_edit->ShowMessage();
?>
<form name="fobjetivoedit" id="fobjetivoedit" class="<?php echo $objetivo_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($objetivo_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $objetivo_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="objetivo">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($objetivo->getCurrentMasterTable() == "proyecto") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="proyecto">
<input type="hidden" name="fk_idProyecto" value="<?php echo $objetivo->proyecto->getSessionValue() ?>">
<?php } ?>
<div class="ewDesktop">
<div>
<table id="tbl_objetivoedit" class="table table-bordered table-striped ewDesktopTable">
<?php if ($objetivo->nombre->Visible) { // nombre ?>
	<tr id="r_nombre">
		<td><span id="elh_objetivo_nombre"><?php echo $objetivo->nombre->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $objetivo->nombre->CellAttributes() ?>>
<span id="el_objetivo_nombre">
<input type="text" data-table="objetivo" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($objetivo->nombre->getPlaceHolder()) ?>" value="<?php echo $objetivo->nombre->EditValue ?>"<?php echo $objetivo->nombre->EditAttributes() ?>>
</span>
<?php echo $objetivo->nombre->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($objetivo->comentarios->Visible) { // comentarios ?>
	<tr id="r_comentarios">
		<td><span id="elh_objetivo_comentarios"><?php echo $objetivo->comentarios->FldCaption() ?></span></td>
		<td<?php echo $objetivo->comentarios->CellAttributes() ?>>
<span id="el_objetivo_comentarios">
<input type="text" data-table="objetivo" data-field="x_comentarios" name="x_comentarios" id="x_comentarios" size="30" maxlength="128" placeholder="<?php echo ew_HtmlEncode($objetivo->comentarios->getPlaceHolder()) ?>" value="<?php echo $objetivo->comentarios->EditValue ?>"<?php echo $objetivo->comentarios->EditAttributes() ?>>
</span>
<?php echo $objetivo->comentarios->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($objetivo->duracion->Visible) { // duracion ?>
	<tr id="r_duracion">
		<td><span id="elh_objetivo_duracion"><?php echo $objetivo->duracion->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $objetivo->duracion->CellAttributes() ?>>
<span id="el_objetivo_duracion">
<input type="text" data-table="objetivo" data-field="x_duracion" name="x_duracion" id="x_duracion" size="30" placeholder="<?php echo ew_HtmlEncode($objetivo->duracion->getPlaceHolder()) ?>" value="<?php echo $objetivo->duracion->EditValue ?>"<?php echo $objetivo->duracion->EditAttributes() ?>>
</span>
<?php echo $objetivo->duracion->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($objetivo->formatoDuracion->Visible) { // formatoDuracion ?>
	<tr id="r_formatoDuracion">
		<td><span id="elh_objetivo_formatoDuracion"><?php echo $objetivo->formatoDuracion->FldCaption() ?></span></td>
		<td<?php echo $objetivo->formatoDuracion->CellAttributes() ?>>
<span id="el_objetivo_formatoDuracion">
<select data-table="objetivo" data-field="x_formatoDuracion" data-value-separator="<?php echo ew_HtmlEncode(is_array($objetivo->formatoDuracion->DisplayValueSeparator) ? json_encode($objetivo->formatoDuracion->DisplayValueSeparator) : $objetivo->formatoDuracion->DisplayValueSeparator) ?>" id="x_formatoDuracion" name="x_formatoDuracion"<?php echo $objetivo->formatoDuracion->EditAttributes() ?>>
<?php
if (is_array($objetivo->formatoDuracion->EditValue)) {
	$arwrk = $objetivo->formatoDuracion->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($objetivo->formatoDuracion->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $objetivo->formatoDuracion->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($objetivo->formatoDuracion->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($objetivo->formatoDuracion->CurrentValue) ?>" selected><?php echo $objetivo->formatoDuracion->CurrentValue ?></option>
<?php
    }
}
?>
</select>
</span>
<?php echo $objetivo->formatoDuracion->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($objetivo->fechaInicio->Visible) { // fechaInicio ?>
	<tr id="r_fechaInicio">
		<td><span id="elh_objetivo_fechaInicio"><?php echo $objetivo->fechaInicio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $objetivo->fechaInicio->CellAttributes() ?>>
<span id="el_objetivo_fechaInicio">
<input type="text" data-table="objetivo" data-field="x_fechaInicio" data-format="7" name="x_fechaInicio" id="x_fechaInicio" placeholder="<?php echo ew_HtmlEncode($objetivo->fechaInicio->getPlaceHolder()) ?>" value="<?php echo $objetivo->fechaInicio->EditValue ?>"<?php echo $objetivo->fechaInicio->EditAttributes() ?>>
<?php if (!$objetivo->fechaInicio->ReadOnly && !$objetivo->fechaInicio->Disabled && !isset($objetivo->fechaInicio->EditAttrs["readonly"]) && !isset($objetivo->fechaInicio->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fobjetivoedit", "x_fechaInicio", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $objetivo->fechaInicio->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($objetivo->fechFin->Visible) { // fechFin ?>
	<tr id="r_fechFin">
		<td><span id="elh_objetivo_fechFin"><?php echo $objetivo->fechFin->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $objetivo->fechFin->CellAttributes() ?>>
<span id="el_objetivo_fechFin">
<input type="text" data-table="objetivo" data-field="x_fechFin" data-format="7" name="x_fechFin" id="x_fechFin" placeholder="<?php echo ew_HtmlEncode($objetivo->fechFin->getPlaceHolder()) ?>" value="<?php echo $objetivo->fechFin->EditValue ?>"<?php echo $objetivo->fechFin->EditAttributes() ?>>
<?php if (!$objetivo->fechFin->ReadOnly && !$objetivo->fechFin->Disabled && !isset($objetivo->fechFin->EditAttrs["readonly"]) && !isset($objetivo->fechFin->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fobjetivoedit", "x_fechFin", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $objetivo->fechFin->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($objetivo->proyecto->Visible) { // proyecto ?>
	<tr id="r_proyecto">
		<td><span id="elh_objetivo_proyecto"><?php echo $objetivo->proyecto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $objetivo->proyecto->CellAttributes() ?>>
<?php if ($objetivo->proyecto->getSessionValue() <> "") { ?>
<span id="el_objetivo_proyecto">
<span<?php echo $objetivo->proyecto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $objetivo->proyecto->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_proyecto" name="x_proyecto" value="<?php echo ew_HtmlEncode($objetivo->proyecto->CurrentValue) ?>">
<?php } else { ?>
<span id="el_objetivo_proyecto">
<select data-table="objetivo" data-field="x_proyecto" data-value-separator="<?php echo ew_HtmlEncode(is_array($objetivo->proyecto->DisplayValueSeparator) ? json_encode($objetivo->proyecto->DisplayValueSeparator) : $objetivo->proyecto->DisplayValueSeparator) ?>" id="x_proyecto" name="x_proyecto"<?php echo $objetivo->proyecto->EditAttributes() ?>>
<?php
if (is_array($objetivo->proyecto->EditValue)) {
	$arwrk = $objetivo->proyecto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($objetivo->proyecto->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $objetivo->proyecto->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($objetivo->proyecto->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($objetivo->proyecto->CurrentValue) ?>" selected><?php echo $objetivo->proyecto->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `idProyecto`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `proyecto`";
$sWhereWrk = "";
$objetivo->proyecto->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$objetivo->proyecto->LookupFilters += array("f0" => "`idProyecto` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$objetivo->Lookup_Selecting($objetivo->proyecto, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $objetivo->proyecto->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_proyecto" id="s_x_proyecto" value="<?php echo $objetivo->proyecto->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php echo $objetivo->proyecto->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($objetivo->tipo->Visible) { // tipo ?>
	<tr id="r_tipo">
		<td><span id="elh_objetivo_tipo"><?php echo $objetivo->tipo->FldCaption() ?></span></td>
		<td<?php echo $objetivo->tipo->CellAttributes() ?>>
<span id="el_objetivo_tipo">
<select data-table="objetivo" data-field="x_tipo" data-value-separator="<?php echo ew_HtmlEncode(is_array($objetivo->tipo->DisplayValueSeparator) ? json_encode($objetivo->tipo->DisplayValueSeparator) : $objetivo->tipo->DisplayValueSeparator) ?>" id="x_tipo" name="x_tipo"<?php echo $objetivo->tipo->EditAttributes() ?>>
<?php
if (is_array($objetivo->tipo->EditValue)) {
	$arwrk = $objetivo->tipo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($objetivo->tipo->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $objetivo->tipo->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($objetivo->tipo->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($objetivo->tipo->CurrentValue) ?>" selected><?php echo $objetivo->tipo->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `idObjetivosTipo`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `objetivos_tipo`";
$sWhereWrk = "";
$objetivo->tipo->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$objetivo->tipo->LookupFilters += array("f0" => "`idObjetivosTipo` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$objetivo->Lookup_Selecting($objetivo->tipo, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $objetivo->tipo->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_tipo" id="s_x_tipo" value="<?php echo $objetivo->tipo->LookupFilterQuery() ?>">
</span>
<?php echo $objetivo->tipo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($objetivo->estado->Visible) { // estado ?>
	<tr id="r_estado">
		<td><span id="elh_objetivo_estado"><?php echo $objetivo->estado->FldCaption() ?></span></td>
		<td<?php echo $objetivo->estado->CellAttributes() ?>>
<span id="el_objetivo_estado">
<select data-table="objetivo" data-field="x_estado" data-value-separator="<?php echo ew_HtmlEncode(is_array($objetivo->estado->DisplayValueSeparator) ? json_encode($objetivo->estado->DisplayValueSeparator) : $objetivo->estado->DisplayValueSeparator) ?>" id="x_estado" name="x_estado"<?php echo $objetivo->estado->EditAttributes() ?>>
<?php
if (is_array($objetivo->estado->EditValue)) {
	$arwrk = $objetivo->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($objetivo->estado->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $objetivo->estado->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($objetivo->estado->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($objetivo->estado->CurrentValue) ?>" selected><?php echo $objetivo->estado->CurrentValue ?></option>
<?php
    }
}
?>
</select>
</span>
<?php echo $objetivo->estado->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
<input type="hidden" data-table="objetivo" data-field="x_idObjetivo" name="x_idObjetivo" id="x_idObjetivo" value="<?php echo ew_HtmlEncode($objetivo->idObjetivo->CurrentValue) ?>">
<?php
	if (in_array("resultado", explode(",", $objetivo->getCurrentDetailTable())) && $resultado->DetailEdit) {
?>
<?php if ($objetivo->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("resultado", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "resultadogrid.php" ?>
<?php } ?>
<div class="ewDesktopButton">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $objetivo_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</div>
</form>
<script type="text/javascript">
fobjetivoedit.Init();
</script>
<?php
$objetivo_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$objetivo_edit->Page_Terminate();
?>
