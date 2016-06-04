<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "actividadinfo.php" ?>
<?php include_once "resultadoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$actividad_edit = NULL; // Initialize page object first

class cactividad_edit extends cactividad {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}";

	// Table name
	var $TableName = 'actividad';

	// Page object name
	var $PageObjName = 'actividad_edit';

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

		// Table object (actividad)
		if (!isset($GLOBALS["actividad"]) || get_class($GLOBALS["actividad"]) == "cactividad") {
			$GLOBALS["actividad"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["actividad"];
		}

		// Table object (resultado)
		if (!isset($GLOBALS['resultado'])) $GLOBALS['resultado'] = new cresultado();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'actividad', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("actividadlist.php"));
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
		global $EW_EXPORT, $actividad;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($actividad);
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
		if (@$_GET["idActividad"] <> "") {
			$this->idActividad->setQueryStringValue($_GET["idActividad"]);
		}

		// Set up master detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->idActividad->CurrentValue == "")
			$this->Page_Terminate("actividadlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("actividadlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "actividadlist.php")
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
		if (!$this->avance->FldIsDetailKey) {
			$this->avance->setFormValue($objForm->GetValue("x_avance"));
		}
		if (!$this->nombre->FldIsDetailKey) {
			$this->nombre->setFormValue($objForm->GetValue("x_nombre"));
		}
		if (!$this->duracion->FldIsDetailKey) {
			$this->duracion->setFormValue($objForm->GetValue("x_duracion"));
		}
		if (!$this->tipoDuracion->FldIsDetailKey) {
			$this->tipoDuracion->setFormValue($objForm->GetValue("x_tipoDuracion"));
		}
		if (!$this->fechaInicio->FldIsDetailKey) {
			$this->fechaInicio->setFormValue($objForm->GetValue("x_fechaInicio"));
			$this->fechaInicio->CurrentValue = ew_UnFormatDateTime($this->fechaInicio->CurrentValue, 7);
		}
		if (!$this->fechaFin->FldIsDetailKey) {
			$this->fechaFin->setFormValue($objForm->GetValue("x_fechaFin"));
			$this->fechaFin->CurrentValue = ew_UnFormatDateTime($this->fechaFin->CurrentValue, 7);
		}
		if (!$this->predecesora->FldIsDetailKey) {
			$this->predecesora->setFormValue($objForm->GetValue("x_predecesora"));
		}
		if (!$this->recurso->FldIsDetailKey) {
			$this->recurso->setFormValue($objForm->GetValue("x_recurso"));
		}
		if (!$this->estado->FldIsDetailKey) {
			$this->estado->setFormValue($objForm->GetValue("x_estado"));
		}
		if (!$this->estatus->FldIsDetailKey) {
			$this->estatus->setFormValue($objForm->GetValue("x_estatus"));
		}
		if (!$this->Resultado->FldIsDetailKey) {
			$this->Resultado->setFormValue($objForm->GetValue("x_Resultado"));
		}
		if (!$this->idActividad->FldIsDetailKey)
			$this->idActividad->setFormValue($objForm->GetValue("x_idActividad"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->idActividad->CurrentValue = $this->idActividad->FormValue;
		$this->avance->CurrentValue = $this->avance->FormValue;
		$this->nombre->CurrentValue = $this->nombre->FormValue;
		$this->duracion->CurrentValue = $this->duracion->FormValue;
		$this->tipoDuracion->CurrentValue = $this->tipoDuracion->FormValue;
		$this->fechaInicio->CurrentValue = $this->fechaInicio->FormValue;
		$this->fechaInicio->CurrentValue = ew_UnFormatDateTime($this->fechaInicio->CurrentValue, 7);
		$this->fechaFin->CurrentValue = $this->fechaFin->FormValue;
		$this->fechaFin->CurrentValue = ew_UnFormatDateTime($this->fechaFin->CurrentValue, 7);
		$this->predecesora->CurrentValue = $this->predecesora->FormValue;
		$this->recurso->CurrentValue = $this->recurso->FormValue;
		$this->estado->CurrentValue = $this->estado->FormValue;
		$this->estatus->CurrentValue = $this->estatus->FormValue;
		$this->Resultado->CurrentValue = $this->Resultado->FormValue;
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->idActividad->DbValue = $row['idActividad'];
		$this->avance->DbValue = $row['avance'];
		$this->nombre->DbValue = $row['nombre'];
		$this->duracion->DbValue = $row['duracion'];
		$this->tipoDuracion->DbValue = $row['tipoDuracion'];
		$this->fechaInicio->DbValue = $row['fechaInicio'];
		$this->fechaFin->DbValue = $row['fechaFin'];
		$this->predecesora->DbValue = $row['predecesora'];
		$this->recurso->DbValue = $row['recurso'];
		$this->estado->DbValue = $row['estado'];
		$this->estatus->DbValue = $row['estatus'];
		$this->Resultado->DbValue = $row['Resultado'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// avance
			$this->avance->EditAttrs["class"] = "form-control";
			$this->avance->EditCustomAttributes = "";
			if (trim(strval($this->avance->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idAvance`" . ew_SearchString("=", $this->avance->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `idAvance`, `cantidad` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `actividad_avance_porcentaje`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->avance, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->avance->EditValue = $arwrk;

			// nombre
			$this->nombre->EditAttrs["class"] = "form-control";
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->CurrentValue);
			$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

			// duracion
			$this->duracion->EditAttrs["class"] = "form-control";
			$this->duracion->EditCustomAttributes = "";
			$this->duracion->EditValue = ew_HtmlEncode($this->duracion->CurrentValue);
			$this->duracion->PlaceHolder = ew_RemoveHtml($this->duracion->FldCaption());

			// tipoDuracion
			$this->tipoDuracion->EditAttrs["class"] = "form-control";
			$this->tipoDuracion->EditCustomAttributes = "";
			$this->tipoDuracion->EditValue = $this->tipoDuracion->Options(TRUE);

			// fechaInicio
			$this->fechaInicio->EditAttrs["class"] = "form-control";
			$this->fechaInicio->EditCustomAttributes = "";
			$this->fechaInicio->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fechaInicio->CurrentValue, 7));
			$this->fechaInicio->PlaceHolder = ew_RemoveHtml($this->fechaInicio->FldCaption());

			// fechaFin
			$this->fechaFin->EditAttrs["class"] = "form-control";
			$this->fechaFin->EditCustomAttributes = "";
			$this->fechaFin->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fechaFin->CurrentValue, 7));
			$this->fechaFin->PlaceHolder = ew_RemoveHtml($this->fechaFin->FldCaption());

			// predecesora
			$this->predecesora->EditAttrs["class"] = "form-control";
			$this->predecesora->EditCustomAttributes = "";
			$this->predecesora->EditValue = ew_HtmlEncode($this->predecesora->CurrentValue);
			$this->predecesora->PlaceHolder = ew_RemoveHtml($this->predecesora->FldCaption());

			// recurso
			$this->recurso->EditAttrs["class"] = "form-control";
			$this->recurso->EditCustomAttributes = "";
			if (trim(strval($this->recurso->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idUsuario`" . ew_SearchString("=", $this->recurso->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `idUsuario`, `nombres` AS `DispFld`, `apellidos` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `usuario`";
			$sWhereWrk = "";
			$lookuptblfilter = "`tipoUsuario` = 4";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			if (!$GLOBALS["actividad"]->UserIDAllow("edit")) $sWhereWrk = $GLOBALS["usuario"]->AddUserIDFilter($sWhereWrk);
			$this->Lookup_Selecting($this->recurso, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->recurso->EditValue = $arwrk;

			// estado
			$this->estado->EditAttrs["class"] = "form-control";
			$this->estado->EditCustomAttributes = "";
			$this->estado->EditValue = ew_HtmlEncode($this->estado->CurrentValue);
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
			if (trim(strval($this->Resultado->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idResultado`" . ew_SearchString("=", $this->Resultado->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `idResultado`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `resultado`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Resultado, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Resultado->EditValue = $arwrk;
			}

			// Edit refer script
			// avance

			$this->avance->LinkCustomAttributes = "";
			$this->avance->HrefValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";

			// duracion
			$this->duracion->LinkCustomAttributes = "";
			$this->duracion->HrefValue = "";

			// tipoDuracion
			$this->tipoDuracion->LinkCustomAttributes = "";
			$this->tipoDuracion->HrefValue = "";

			// fechaInicio
			$this->fechaInicio->LinkCustomAttributes = "";
			$this->fechaInicio->HrefValue = "";

			// fechaFin
			$this->fechaFin->LinkCustomAttributes = "";
			$this->fechaFin->HrefValue = "";

			// predecesora
			$this->predecesora->LinkCustomAttributes = "";
			$this->predecesora->HrefValue = "";

			// recurso
			$this->recurso->LinkCustomAttributes = "";
			$this->recurso->HrefValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";

			// estatus
			$this->estatus->LinkCustomAttributes = "";
			$this->estatus->HrefValue = "";

			// Resultado
			$this->Resultado->LinkCustomAttributes = "";
			$this->Resultado->HrefValue = "";
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
		if (!$this->avance->FldIsDetailKey && !is_null($this->avance->FormValue) && $this->avance->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->avance->FldCaption(), $this->avance->ReqErrMsg));
		}
		if (!$this->nombre->FldIsDetailKey && !is_null($this->nombre->FormValue) && $this->nombre->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nombre->FldCaption(), $this->nombre->ReqErrMsg));
		}
		if (!$this->duracion->FldIsDetailKey && !is_null($this->duracion->FormValue) && $this->duracion->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->duracion->FldCaption(), $this->duracion->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->duracion->FormValue)) {
			ew_AddMessage($gsFormError, $this->duracion->FldErrMsg());
		}
		if (!$this->tipoDuracion->FldIsDetailKey && !is_null($this->tipoDuracion->FormValue) && $this->tipoDuracion->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tipoDuracion->FldCaption(), $this->tipoDuracion->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->fechaInicio->FormValue)) {
			ew_AddMessage($gsFormError, $this->fechaInicio->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->fechaFin->FormValue)) {
			ew_AddMessage($gsFormError, $this->fechaFin->FldErrMsg());
		}
		if (!ew_CheckInteger($this->predecesora->FormValue)) {
			ew_AddMessage($gsFormError, $this->predecesora->FldErrMsg());
		}
		if (!$this->estado->FldIsDetailKey && !is_null($this->estado->FormValue) && $this->estado->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->estado->FldCaption(), $this->estado->ReqErrMsg));
		}
		if (!$this->estatus->FldIsDetailKey && !is_null($this->estatus->FormValue) && $this->estatus->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->estatus->FldCaption(), $this->estatus->ReqErrMsg));
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

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// avance
			$this->avance->SetDbValueDef($rsnew, $this->avance->CurrentValue, 0, $this->avance->ReadOnly);

			// nombre
			$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, "", $this->nombre->ReadOnly);

			// duracion
			$this->duracion->SetDbValueDef($rsnew, $this->duracion->CurrentValue, 0, $this->duracion->ReadOnly);

			// tipoDuracion
			$this->tipoDuracion->SetDbValueDef($rsnew, $this->tipoDuracion->CurrentValue, "", $this->tipoDuracion->ReadOnly);

			// fechaInicio
			$this->fechaInicio->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechaInicio->CurrentValue, 7), NULL, $this->fechaInicio->ReadOnly);

			// fechaFin
			$this->fechaFin->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechaFin->CurrentValue, 7), NULL, $this->fechaFin->ReadOnly);

			// predecesora
			$this->predecesora->SetDbValueDef($rsnew, $this->predecesora->CurrentValue, NULL, $this->predecesora->ReadOnly);

			// recurso
			$this->recurso->SetDbValueDef($rsnew, $this->recurso->CurrentValue, NULL, $this->recurso->ReadOnly);

			// estado
			$this->estado->SetDbValueDef($rsnew, $this->estado->CurrentValue, "", $this->estado->ReadOnly);

			// estatus
			$this->estatus->SetDbValueDef($rsnew, $this->estatus->CurrentValue, "", $this->estatus->ReadOnly);

			// Resultado
			$this->Resultado->SetDbValueDef($rsnew, $this->Resultado->CurrentValue, NULL, $this->Resultado->ReadOnly);

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
			if ($sMasterTblVar == "resultado") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_idResultado"] <> "") {
					$GLOBALS["resultado"]->idResultado->setQueryStringValue($_GET["fk_idResultado"]);
					$this->Resultado->setQueryStringValue($GLOBALS["resultado"]->idResultado->QueryStringValue);
					$this->Resultado->setSessionValue($this->Resultado->QueryStringValue);
					if (!is_numeric($GLOBALS["resultado"]->idResultado->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar == "resultado") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_idResultado"] <> "") {
					$GLOBALS["resultado"]->idResultado->setFormValue($_POST["fk_idResultado"]);
					$this->Resultado->setFormValue($GLOBALS["resultado"]->idResultado->FormValue);
					$this->Resultado->setSessionValue($this->Resultado->FormValue);
					if (!is_numeric($GLOBALS["resultado"]->idResultado->FormValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "resultado") {
				if ($this->Resultado->CurrentValue == "") $this->Resultado->setSessionValue("");
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("actividadlist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'actividad';
		$usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		global $Language;
		if (!$this->AuditTrailOnEdit) return;
		$table = 'actividad';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['idActividad'];

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
if (!isset($actividad_edit)) $actividad_edit = new cactividad_edit();

// Page init
$actividad_edit->Page_Init();

// Page main
$actividad_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$actividad_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = factividadedit = new ew_Form("factividadedit", "edit");

// Validate form
factividadedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_avance");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $actividad->avance->FldCaption(), $actividad->avance->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nombre");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $actividad->nombre->FldCaption(), $actividad->nombre->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_duracion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $actividad->duracion->FldCaption(), $actividad->duracion->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_duracion");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($actividad->duracion->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tipoDuracion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $actividad->tipoDuracion->FldCaption(), $actividad->tipoDuracion->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fechaInicio");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($actividad->fechaInicio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fechaFin");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($actividad->fechaFin->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_predecesora");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($actividad->predecesora->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_estado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $actividad->estado->FldCaption(), $actividad->estado->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_estatus");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $actividad->estatus->FldCaption(), $actividad->estatus->ReqErrMsg)) ?>");

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
factividadedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
factividadedit.ValidateRequired = true;
<?php } else { ?>
factividadedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
factividadedit.Lists["x_avance"] = {"LinkField":"x_idAvance","Ajax":true,"AutoFill":false,"DisplayFields":["x_cantidad","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
factividadedit.Lists["x_tipoDuracion"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
factividadedit.Lists["x_tipoDuracion"].Options = <?php echo json_encode($actividad->tipoDuracion->Options()) ?>;
factividadedit.Lists["x_recurso"] = {"LinkField":"x_idUsuario","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_apellidos","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
factividadedit.Lists["x_estatus"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
factividadedit.Lists["x_estatus"].Options = <?php echo json_encode($actividad->estatus->Options()) ?>;
factividadedit.Lists["x_Resultado"] = {"LinkField":"x_idResultado","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

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
<?php $actividad_edit->ShowPageHeader(); ?>
<?php
$actividad_edit->ShowMessage();
?>
<form name="factividadedit" id="factividadedit" class="<?php echo $actividad_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($actividad_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $actividad_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="actividad">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($actividad->getCurrentMasterTable() == "resultado") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="resultado">
<input type="hidden" name="fk_idResultado" value="<?php echo $actividad->Resultado->getSessionValue() ?>">
<?php } ?>
<div class="ewDesktop">
<div>
<table id="tbl_actividadedit" class="table table-bordered table-striped ewDesktopTable">
<?php if ($actividad->avance->Visible) { // avance ?>
	<tr id="r_avance">
		<td><span id="elh_actividad_avance"><?php echo $actividad->avance->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $actividad->avance->CellAttributes() ?>>
<span id="el_actividad_avance">
<select data-table="actividad" data-field="x_avance" data-value-separator="<?php echo ew_HtmlEncode(is_array($actividad->avance->DisplayValueSeparator) ? json_encode($actividad->avance->DisplayValueSeparator) : $actividad->avance->DisplayValueSeparator) ?>" id="x_avance" name="x_avance"<?php echo $actividad->avance->EditAttributes() ?>>
<?php
if (is_array($actividad->avance->EditValue)) {
	$arwrk = $actividad->avance->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($actividad->avance->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $actividad->avance->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($actividad->avance->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($actividad->avance->CurrentValue) ?>" selected><?php echo $actividad->avance->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `idAvance`, `cantidad` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `actividad_avance_porcentaje`";
$sWhereWrk = "";
$actividad->avance->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$actividad->avance->LookupFilters += array("f0" => "`idAvance` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$actividad->Lookup_Selecting($actividad->avance, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $actividad->avance->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_avance" id="s_x_avance" value="<?php echo $actividad->avance->LookupFilterQuery() ?>">
</span>
<?php echo $actividad->avance->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($actividad->nombre->Visible) { // nombre ?>
	<tr id="r_nombre">
		<td><span id="elh_actividad_nombre"><?php echo $actividad->nombre->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $actividad->nombre->CellAttributes() ?>>
<span id="el_actividad_nombre">
<input type="text" data-table="actividad" data-field="x_nombre" name="x_nombre" id="x_nombre" size="25" maxlength="128" placeholder="<?php echo ew_HtmlEncode($actividad->nombre->getPlaceHolder()) ?>" value="<?php echo $actividad->nombre->EditValue ?>"<?php echo $actividad->nombre->EditAttributes() ?>>
</span>
<?php echo $actividad->nombre->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($actividad->duracion->Visible) { // duracion ?>
	<tr id="r_duracion">
		<td><span id="elh_actividad_duracion"><?php echo $actividad->duracion->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $actividad->duracion->CellAttributes() ?>>
<span id="el_actividad_duracion">
<input type="text" data-table="actividad" data-field="x_duracion" name="x_duracion" id="x_duracion" size="3" placeholder="<?php echo ew_HtmlEncode($actividad->duracion->getPlaceHolder()) ?>" value="<?php echo $actividad->duracion->EditValue ?>"<?php echo $actividad->duracion->EditAttributes() ?>>
</span>
<?php echo $actividad->duracion->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($actividad->tipoDuracion->Visible) { // tipoDuracion ?>
	<tr id="r_tipoDuracion">
		<td><span id="elh_actividad_tipoDuracion"><?php echo $actividad->tipoDuracion->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $actividad->tipoDuracion->CellAttributes() ?>>
<span id="el_actividad_tipoDuracion">
<select data-table="actividad" data-field="x_tipoDuracion" data-value-separator="<?php echo ew_HtmlEncode(is_array($actividad->tipoDuracion->DisplayValueSeparator) ? json_encode($actividad->tipoDuracion->DisplayValueSeparator) : $actividad->tipoDuracion->DisplayValueSeparator) ?>" id="x_tipoDuracion" name="x_tipoDuracion"<?php echo $actividad->tipoDuracion->EditAttributes() ?>>
<?php
if (is_array($actividad->tipoDuracion->EditValue)) {
	$arwrk = $actividad->tipoDuracion->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($actividad->tipoDuracion->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $actividad->tipoDuracion->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($actividad->tipoDuracion->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($actividad->tipoDuracion->CurrentValue) ?>" selected><?php echo $actividad->tipoDuracion->CurrentValue ?></option>
<?php
    }
}
?>
</select>
</span>
<?php echo $actividad->tipoDuracion->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($actividad->fechaInicio->Visible) { // fechaInicio ?>
	<tr id="r_fechaInicio">
		<td><span id="elh_actividad_fechaInicio"><?php echo $actividad->fechaInicio->FldCaption() ?></span></td>
		<td<?php echo $actividad->fechaInicio->CellAttributes() ?>>
<span id="el_actividad_fechaInicio">
<input type="text" data-table="actividad" data-field="x_fechaInicio" data-format="7" name="x_fechaInicio" id="x_fechaInicio" size="30" placeholder="<?php echo ew_HtmlEncode($actividad->fechaInicio->getPlaceHolder()) ?>" value="<?php echo $actividad->fechaInicio->EditValue ?>"<?php echo $actividad->fechaInicio->EditAttributes() ?>>
<?php if (!$actividad->fechaInicio->ReadOnly && !$actividad->fechaInicio->Disabled && !isset($actividad->fechaInicio->EditAttrs["readonly"]) && !isset($actividad->fechaInicio->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("factividadedit", "x_fechaInicio", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $actividad->fechaInicio->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($actividad->fechaFin->Visible) { // fechaFin ?>
	<tr id="r_fechaFin">
		<td><span id="elh_actividad_fechaFin"><?php echo $actividad->fechaFin->FldCaption() ?></span></td>
		<td<?php echo $actividad->fechaFin->CellAttributes() ?>>
<span id="el_actividad_fechaFin">
<input type="text" data-table="actividad" data-field="x_fechaFin" data-format="7" name="x_fechaFin" id="x_fechaFin" size="30" placeholder="<?php echo ew_HtmlEncode($actividad->fechaFin->getPlaceHolder()) ?>" value="<?php echo $actividad->fechaFin->EditValue ?>"<?php echo $actividad->fechaFin->EditAttributes() ?>>
<?php if (!$actividad->fechaFin->ReadOnly && !$actividad->fechaFin->Disabled && !isset($actividad->fechaFin->EditAttrs["readonly"]) && !isset($actividad->fechaFin->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("factividadedit", "x_fechaFin", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $actividad->fechaFin->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($actividad->predecesora->Visible) { // predecesora ?>
	<tr id="r_predecesora">
		<td><span id="elh_actividad_predecesora"><?php echo $actividad->predecesora->FldCaption() ?></span></td>
		<td<?php echo $actividad->predecesora->CellAttributes() ?>>
<span id="el_actividad_predecesora">
<input type="text" data-table="actividad" data-field="x_predecesora" name="x_predecesora" id="x_predecesora" size="5" maxlength="5" placeholder="<?php echo ew_HtmlEncode($actividad->predecesora->getPlaceHolder()) ?>" value="<?php echo $actividad->predecesora->EditValue ?>"<?php echo $actividad->predecesora->EditAttributes() ?>>
</span>
<?php echo $actividad->predecesora->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($actividad->recurso->Visible) { // recurso ?>
	<tr id="r_recurso">
		<td><span id="elh_actividad_recurso"><?php echo $actividad->recurso->FldCaption() ?></span></td>
		<td<?php echo $actividad->recurso->CellAttributes() ?>>
<span id="el_actividad_recurso">
<select data-table="actividad" data-field="x_recurso" data-value-separator="<?php echo ew_HtmlEncode(is_array($actividad->recurso->DisplayValueSeparator) ? json_encode($actividad->recurso->DisplayValueSeparator) : $actividad->recurso->DisplayValueSeparator) ?>" id="x_recurso" name="x_recurso"<?php echo $actividad->recurso->EditAttributes() ?>>
<?php
if (is_array($actividad->recurso->EditValue)) {
	$arwrk = $actividad->recurso->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($actividad->recurso->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $actividad->recurso->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($actividad->recurso->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($actividad->recurso->CurrentValue) ?>" selected><?php echo $actividad->recurso->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `idUsuario`, `nombres` AS `DispFld`, `apellidos` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `usuario`";
$sWhereWrk = "";
$lookuptblfilter = "`tipoUsuario` = 4";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
if (!$GLOBALS["actividad"]->UserIDAllow("edit")) $sWhereWrk = $GLOBALS["usuario"]->AddUserIDFilter($sWhereWrk);
$actividad->recurso->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$actividad->recurso->LookupFilters += array("f0" => "`idUsuario` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$actividad->Lookup_Selecting($actividad->recurso, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $actividad->recurso->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_recurso" id="s_x_recurso" value="<?php echo $actividad->recurso->LookupFilterQuery() ?>">
</span>
<?php echo $actividad->recurso->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($actividad->estado->Visible) { // estado ?>
	<tr id="r_estado">
		<td><span id="elh_actividad_estado"><?php echo $actividad->estado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $actividad->estado->CellAttributes() ?>>
<span id="el_actividad_estado">
<input type="text" data-table="actividad" data-field="x_estado" name="x_estado" id="x_estado" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($actividad->estado->getPlaceHolder()) ?>" value="<?php echo $actividad->estado->EditValue ?>"<?php echo $actividad->estado->EditAttributes() ?>>
</span>
<?php echo $actividad->estado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($actividad->estatus->Visible) { // estatus ?>
	<tr id="r_estatus">
		<td><span id="elh_actividad_estatus"><?php echo $actividad->estatus->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $actividad->estatus->CellAttributes() ?>>
<span id="el_actividad_estatus">
<select data-table="actividad" data-field="x_estatus" data-value-separator="<?php echo ew_HtmlEncode(is_array($actividad->estatus->DisplayValueSeparator) ? json_encode($actividad->estatus->DisplayValueSeparator) : $actividad->estatus->DisplayValueSeparator) ?>" id="x_estatus" name="x_estatus"<?php echo $actividad->estatus->EditAttributes() ?>>
<?php
if (is_array($actividad->estatus->EditValue)) {
	$arwrk = $actividad->estatus->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($actividad->estatus->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $actividad->estatus->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($actividad->estatus->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($actividad->estatus->CurrentValue) ?>" selected><?php echo $actividad->estatus->CurrentValue ?></option>
<?php
    }
}
?>
</select>
</span>
<?php echo $actividad->estatus->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($actividad->Resultado->Visible) { // Resultado ?>
	<tr id="r_Resultado">
		<td><span id="elh_actividad_Resultado"><?php echo $actividad->Resultado->FldCaption() ?></span></td>
		<td<?php echo $actividad->Resultado->CellAttributes() ?>>
<?php if ($actividad->Resultado->getSessionValue() <> "") { ?>
<span id="el_actividad_Resultado">
<span<?php echo $actividad->Resultado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $actividad->Resultado->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_Resultado" name="x_Resultado" value="<?php echo ew_HtmlEncode($actividad->Resultado->CurrentValue) ?>">
<?php } else { ?>
<span id="el_actividad_Resultado">
<select data-table="actividad" data-field="x_Resultado" data-value-separator="<?php echo ew_HtmlEncode(is_array($actividad->Resultado->DisplayValueSeparator) ? json_encode($actividad->Resultado->DisplayValueSeparator) : $actividad->Resultado->DisplayValueSeparator) ?>" id="x_Resultado" name="x_Resultado"<?php echo $actividad->Resultado->EditAttributes() ?>>
<?php
if (is_array($actividad->Resultado->EditValue)) {
	$arwrk = $actividad->Resultado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($actividad->Resultado->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $actividad->Resultado->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($actividad->Resultado->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($actividad->Resultado->CurrentValue) ?>" selected><?php echo $actividad->Resultado->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `idResultado`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `resultado`";
$sWhereWrk = "";
$actividad->Resultado->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$actividad->Resultado->LookupFilters += array("f0" => "`idResultado` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$actividad->Lookup_Selecting($actividad->Resultado, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $actividad->Resultado->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_Resultado" id="s_x_Resultado" value="<?php echo $actividad->Resultado->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php echo $actividad->Resultado->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
<input type="hidden" data-table="actividad" data-field="x_idActividad" name="x_idActividad" id="x_idActividad" value="<?php echo ew_HtmlEncode($actividad->idActividad->CurrentValue) ?>">
<div class="ewDesktopButton">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $actividad_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</div>
</form>
<script type="text/javascript">
factividadedit.Init();
</script>
<?php
$actividad_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$actividad_edit->Page_Terminate();
?>
