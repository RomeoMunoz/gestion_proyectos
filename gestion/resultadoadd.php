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
<?php include_once "actividadgridcls.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$resultado_add = NULL; // Initialize page object first

class cresultado_add extends cresultado {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}";

	// Table name
	var $TableName = 'resultado';

	// Page object name
	var $PageObjName = 'resultado_add';

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
    var $AuditTrailOnAdd = TRUE;
    var $AuditTrailOnEdit = FALSE;
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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
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

			// Process auto fill for detail table 'actividad'
			if (@$_POST["grid"] == "factividadgrid") {
				if (!isset($GLOBALS["actividad_grid"])) $GLOBALS["actividad_grid"] = new cactividad_grid;
				$GLOBALS["actividad_grid"]->Page_Init();
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		$this->FormClassName = "ewForm ewAddForm";
		if (ew_IsMobile())
			$this->FormClassName = ew_Concat("form-horizontal", $this->FormClassName, " ");

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["idResultado"] != "") {
				$this->idResultado->setQueryStringValue($_GET["idResultado"]);
				$this->setKey("idResultado", $this->idResultado->CurrentValue); // Set up key
			} else {
				$this->setKey("idResultado", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Set up detail parameters
		$this->SetUpDetailParms();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("resultadolist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					if ($this->getCurrentDetailTable() <> "") // Master/detail add
						$sReturnUrl = $this->GetDetailUrl();
					else
						$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "resultadolist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "resultadoview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values

					// Set up detail parameters
					$this->SetUpDetailParms();
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->objetivo->CurrentValue = NULL;
		$this->objetivo->OldValue = $this->objetivo->CurrentValue;
		$this->nombre->CurrentValue = NULL;
		$this->nombre->OldValue = $this->nombre->CurrentValue;
		$this->tiempoEstimado->CurrentValue = 0;
		$this->tiempoTipo->CurrentValue = "Dia";
		$this->fechaInicio->CurrentValue = NULL;
		$this->fechaInicio->OldValue = $this->fechaInicio->CurrentValue;
		$this->fechaFin->CurrentValue = NULL;
		$this->fechaFin->OldValue = $this->fechaFin->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->objetivo->FldIsDetailKey) {
			$this->objetivo->setFormValue($objForm->GetValue("x_objetivo"));
		}
		if (!$this->nombre->FldIsDetailKey) {
			$this->nombre->setFormValue($objForm->GetValue("x_nombre"));
		}
		if (!$this->tiempoEstimado->FldIsDetailKey) {
			$this->tiempoEstimado->setFormValue($objForm->GetValue("x_tiempoEstimado"));
		}
		if (!$this->tiempoTipo->FldIsDetailKey) {
			$this->tiempoTipo->setFormValue($objForm->GetValue("x_tiempoTipo"));
		}
		if (!$this->fechaInicio->FldIsDetailKey) {
			$this->fechaInicio->setFormValue($objForm->GetValue("x_fechaInicio"));
			$this->fechaInicio->CurrentValue = ew_UnFormatDateTime($this->fechaInicio->CurrentValue, 7);
		}
		if (!$this->fechaFin->FldIsDetailKey) {
			$this->fechaFin->setFormValue($objForm->GetValue("x_fechaFin"));
			$this->fechaFin->CurrentValue = ew_UnFormatDateTime($this->fechaFin->CurrentValue, 7);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->objetivo->CurrentValue = $this->objetivo->FormValue;
		$this->nombre->CurrentValue = $this->nombre->FormValue;
		$this->tiempoEstimado->CurrentValue = $this->tiempoEstimado->FormValue;
		$this->tiempoTipo->CurrentValue = $this->tiempoTipo->FormValue;
		$this->fechaInicio->CurrentValue = $this->fechaInicio->FormValue;
		$this->fechaInicio->CurrentValue = ew_UnFormatDateTime($this->fechaInicio->CurrentValue, 7);
		$this->fechaFin->CurrentValue = $this->fechaFin->FormValue;
		$this->fechaFin->CurrentValue = ew_UnFormatDateTime($this->fechaFin->CurrentValue, 7);
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("idResultado")) <> "")
			$this->idResultado->CurrentValue = $this->getKey("idResultado"); // idResultado
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// objetivo
			$this->objetivo->EditAttrs["class"] = "form-control";
			$this->objetivo->EditCustomAttributes = "";
			if ($this->objetivo->getSessionValue() <> "") {
				$this->objetivo->CurrentValue = $this->objetivo->getSessionValue();
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
			} else {
			if (trim(strval($this->objetivo->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idObjetivo`" . ew_SearchString("=", $this->objetivo->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `idObjetivo`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `objetivo`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->objetivo, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->objetivo->EditValue = $arwrk;
			}

			// nombre
			$this->nombre->EditAttrs["class"] = "form-control";
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->CurrentValue);
			$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

			// tiempoEstimado
			$this->tiempoEstimado->EditAttrs["class"] = "form-control";
			$this->tiempoEstimado->EditCustomAttributes = "";
			$this->tiempoEstimado->EditValue = ew_HtmlEncode($this->tiempoEstimado->CurrentValue);
			$this->tiempoEstimado->PlaceHolder = ew_RemoveHtml($this->tiempoEstimado->FldCaption());

			// tiempoTipo
			$this->tiempoTipo->EditAttrs["class"] = "form-control";
			$this->tiempoTipo->EditCustomAttributes = "";
			$this->tiempoTipo->EditValue = $this->tiempoTipo->Options(TRUE);

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

			// Add refer script
			// objetivo

			$this->objetivo->LinkCustomAttributes = "";
			$this->objetivo->HrefValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";

			// tiempoEstimado
			$this->tiempoEstimado->LinkCustomAttributes = "";
			$this->tiempoEstimado->HrefValue = "";

			// tiempoTipo
			$this->tiempoTipo->LinkCustomAttributes = "";
			$this->tiempoTipo->HrefValue = "";

			// fechaInicio
			$this->fechaInicio->LinkCustomAttributes = "";
			$this->fechaInicio->HrefValue = "";

			// fechaFin
			$this->fechaFin->LinkCustomAttributes = "";
			$this->fechaFin->HrefValue = "";
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
		if (!$this->objetivo->FldIsDetailKey && !is_null($this->objetivo->FormValue) && $this->objetivo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->objetivo->FldCaption(), $this->objetivo->ReqErrMsg));
		}
		if (!$this->nombre->FldIsDetailKey && !is_null($this->nombre->FormValue) && $this->nombre->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nombre->FldCaption(), $this->nombre->ReqErrMsg));
		}
		if (!$this->tiempoEstimado->FldIsDetailKey && !is_null($this->tiempoEstimado->FormValue) && $this->tiempoEstimado->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tiempoEstimado->FldCaption(), $this->tiempoEstimado->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->tiempoEstimado->FormValue)) {
			ew_AddMessage($gsFormError, $this->tiempoEstimado->FldErrMsg());
		}
		if (!$this->tiempoTipo->FldIsDetailKey && !is_null($this->tiempoTipo->FormValue) && $this->tiempoTipo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tiempoTipo->FldCaption(), $this->tiempoTipo->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->fechaInicio->FormValue)) {
			ew_AddMessage($gsFormError, $this->fechaInicio->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->fechaFin->FormValue)) {
			ew_AddMessage($gsFormError, $this->fechaFin->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("actividad", $DetailTblVar) && $GLOBALS["actividad"]->DetailAdd) {
			if (!isset($GLOBALS["actividad_grid"])) $GLOBALS["actividad_grid"] = new cactividad_grid(); // get detail page object
			$GLOBALS["actividad_grid"]->ValidateGridForm();
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// objetivo
		$this->objetivo->SetDbValueDef($rsnew, $this->objetivo->CurrentValue, 0, FALSE);

		// nombre
		$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, "", FALSE);

		// tiempoEstimado
		$this->tiempoEstimado->SetDbValueDef($rsnew, $this->tiempoEstimado->CurrentValue, 0, strval($this->tiempoEstimado->CurrentValue) == "");

		// tiempoTipo
		$this->tiempoTipo->SetDbValueDef($rsnew, $this->tiempoTipo->CurrentValue, "", strval($this->tiempoTipo->CurrentValue) == "");

		// fechaInicio
		$this->fechaInicio->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechaInicio->CurrentValue, 7), NULL, FALSE);

		// fechaFin
		$this->fechaFin->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechaFin->CurrentValue, 7), NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->idResultado->setDbValue($conn->Insert_ID());
				$rsnew['idResultado'] = $this->idResultado->DbValue;
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("actividad", $DetailTblVar) && $GLOBALS["actividad"]->DetailAdd) {
				$GLOBALS["actividad"]->Resultado->setSessionValue($this->idResultado->CurrentValue); // Set master key
				if (!isset($GLOBALS["actividad_grid"])) $GLOBALS["actividad_grid"] = new cactividad_grid(); // Get detail page object
				$AddRow = $GLOBALS["actividad_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["actividad"]->Resultado->setSessionValue(""); // Clear master key if insert failed
			}
		}

		// Commit/Rollback transaction
		if ($this->getCurrentDetailTable() <> "") {
			if ($AddRow) {
				$conn->CommitTrans(); // Commit transaction
			} else {
				$conn->RollbackTrans(); // Rollback transaction
			}
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
			$this->WriteAuditTrailOnAdd($rsnew);
		}
		return $AddRow;
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
			if (in_array("actividad", $DetailTblVar)) {
				if (!isset($GLOBALS["actividad_grid"]))
					$GLOBALS["actividad_grid"] = new cactividad_grid;
				if ($GLOBALS["actividad_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["actividad_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["actividad_grid"]->CurrentMode = "add";
					$GLOBALS["actividad_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["actividad_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["actividad_grid"]->setStartRecordNumber(1);
					$GLOBALS["actividad_grid"]->Resultado->FldIsDetailKey = TRUE;
					$GLOBALS["actividad_grid"]->Resultado->CurrentValue = $this->idResultado->CurrentValue;
					$GLOBALS["actividad_grid"]->Resultado->setSessionValue($GLOBALS["actividad_grid"]->Resultado->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("resultadolist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'resultado';
		$usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnAdd) return;
		$table = 'resultado';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['idResultado'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$newvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$newvalue = $rs[$fldname];
					else
						$newvalue = "[MEMO]"; // Memo Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$newvalue = "[XML]"; // XML Field
				} else {
					$newvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $usr, "A", $table, $fldname, $key, "", $newvalue);
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
if (!isset($resultado_add)) $resultado_add = new cresultado_add();

// Page init
$resultado_add->Page_Init();

// Page main
$resultado_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$resultado_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fresultadoadd = new ew_Form("fresultadoadd", "add");

// Validate form
fresultadoadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_objetivo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $resultado->objetivo->FldCaption(), $resultado->objetivo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nombre");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $resultado->nombre->FldCaption(), $resultado->nombre->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tiempoEstimado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $resultado->tiempoEstimado->FldCaption(), $resultado->tiempoEstimado->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tiempoEstimado");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($resultado->tiempoEstimado->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tiempoTipo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $resultado->tiempoTipo->FldCaption(), $resultado->tiempoTipo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fechaInicio");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($resultado->fechaInicio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fechaFin");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($resultado->fechaFin->FldErrMsg()) ?>");

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
fresultadoadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fresultadoadd.ValidateRequired = true;
<?php } else { ?>
fresultadoadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fresultadoadd.Lists["x_objetivo"] = {"LinkField":"x_idObjetivo","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fresultadoadd.Lists["x_tiempoTipo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fresultadoadd.Lists["x_tiempoTipo"].Options = <?php echo json_encode($resultado->tiempoTipo->Options()) ?>;

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
<?php $resultado_add->ShowPageHeader(); ?>
<?php
$resultado_add->ShowMessage();
?>
<form name="fresultadoadd" id="fresultadoadd" class="<?php echo $resultado_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($resultado_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $resultado_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="resultado">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($resultado->getCurrentMasterTable() == "objetivo") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="objetivo">
<input type="hidden" name="fk_idObjetivo" value="<?php echo $resultado->objetivo->getSessionValue() ?>">
<?php } ?>
<div class="ewDesktop">
<div>
<table id="tbl_resultadoadd" class="table table-bordered table-striped ewDesktopTable">
<?php if ($resultado->objetivo->Visible) { // objetivo ?>
	<tr id="r_objetivo">
		<td><span id="elh_resultado_objetivo"><?php echo $resultado->objetivo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $resultado->objetivo->CellAttributes() ?>>
<?php if ($resultado->objetivo->getSessionValue() <> "") { ?>
<span id="el_resultado_objetivo">
<span<?php echo $resultado->objetivo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $resultado->objetivo->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_objetivo" name="x_objetivo" value="<?php echo ew_HtmlEncode($resultado->objetivo->CurrentValue) ?>">
<?php } else { ?>
<span id="el_resultado_objetivo">
<select data-table="resultado" data-field="x_objetivo" data-value-separator="<?php echo ew_HtmlEncode(is_array($resultado->objetivo->DisplayValueSeparator) ? json_encode($resultado->objetivo->DisplayValueSeparator) : $resultado->objetivo->DisplayValueSeparator) ?>" id="x_objetivo" name="x_objetivo"<?php echo $resultado->objetivo->EditAttributes() ?>>
<?php
if (is_array($resultado->objetivo->EditValue)) {
	$arwrk = $resultado->objetivo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($resultado->objetivo->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $resultado->objetivo->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($resultado->objetivo->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($resultado->objetivo->CurrentValue) ?>" selected><?php echo $resultado->objetivo->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `idObjetivo`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `objetivo`";
$sWhereWrk = "";
$resultado->objetivo->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$resultado->objetivo->LookupFilters += array("f0" => "`idObjetivo` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$resultado->Lookup_Selecting($resultado->objetivo, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $resultado->objetivo->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_objetivo" id="s_x_objetivo" value="<?php echo $resultado->objetivo->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php echo $resultado->objetivo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($resultado->nombre->Visible) { // nombre ?>
	<tr id="r_nombre">
		<td><span id="elh_resultado_nombre"><?php echo $resultado->nombre->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $resultado->nombre->CellAttributes() ?>>
<span id="el_resultado_nombre">
<input type="text" data-table="resultado" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="128" placeholder="<?php echo ew_HtmlEncode($resultado->nombre->getPlaceHolder()) ?>" value="<?php echo $resultado->nombre->EditValue ?>"<?php echo $resultado->nombre->EditAttributes() ?>>
</span>
<?php echo $resultado->nombre->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($resultado->tiempoEstimado->Visible) { // tiempoEstimado ?>
	<tr id="r_tiempoEstimado">
		<td><span id="elh_resultado_tiempoEstimado"><?php echo $resultado->tiempoEstimado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $resultado->tiempoEstimado->CellAttributes() ?>>
<span id="el_resultado_tiempoEstimado">
<input type="text" data-table="resultado" data-field="x_tiempoEstimado" name="x_tiempoEstimado" id="x_tiempoEstimado" size="30" placeholder="<?php echo ew_HtmlEncode($resultado->tiempoEstimado->getPlaceHolder()) ?>" value="<?php echo $resultado->tiempoEstimado->EditValue ?>"<?php echo $resultado->tiempoEstimado->EditAttributes() ?>>
</span>
<?php echo $resultado->tiempoEstimado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($resultado->tiempoTipo->Visible) { // tiempoTipo ?>
	<tr id="r_tiempoTipo">
		<td><span id="elh_resultado_tiempoTipo"><?php echo $resultado->tiempoTipo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $resultado->tiempoTipo->CellAttributes() ?>>
<span id="el_resultado_tiempoTipo">
<select data-table="resultado" data-field="x_tiempoTipo" data-value-separator="<?php echo ew_HtmlEncode(is_array($resultado->tiempoTipo->DisplayValueSeparator) ? json_encode($resultado->tiempoTipo->DisplayValueSeparator) : $resultado->tiempoTipo->DisplayValueSeparator) ?>" id="x_tiempoTipo" name="x_tiempoTipo"<?php echo $resultado->tiempoTipo->EditAttributes() ?>>
<?php
if (is_array($resultado->tiempoTipo->EditValue)) {
	$arwrk = $resultado->tiempoTipo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($resultado->tiempoTipo->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $resultado->tiempoTipo->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($resultado->tiempoTipo->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($resultado->tiempoTipo->CurrentValue) ?>" selected><?php echo $resultado->tiempoTipo->CurrentValue ?></option>
<?php
    }
}
?>
</select>
</span>
<?php echo $resultado->tiempoTipo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($resultado->fechaInicio->Visible) { // fechaInicio ?>
	<tr id="r_fechaInicio">
		<td><span id="elh_resultado_fechaInicio"><?php echo $resultado->fechaInicio->FldCaption() ?></span></td>
		<td<?php echo $resultado->fechaInicio->CellAttributes() ?>>
<span id="el_resultado_fechaInicio">
<input type="text" data-table="resultado" data-field="x_fechaInicio" data-format="7" name="x_fechaInicio" id="x_fechaInicio" placeholder="<?php echo ew_HtmlEncode($resultado->fechaInicio->getPlaceHolder()) ?>" value="<?php echo $resultado->fechaInicio->EditValue ?>"<?php echo $resultado->fechaInicio->EditAttributes() ?>>
<?php if (!$resultado->fechaInicio->ReadOnly && !$resultado->fechaInicio->Disabled && !isset($resultado->fechaInicio->EditAttrs["readonly"]) && !isset($resultado->fechaInicio->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fresultadoadd", "x_fechaInicio", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $resultado->fechaInicio->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($resultado->fechaFin->Visible) { // fechaFin ?>
	<tr id="r_fechaFin">
		<td><span id="elh_resultado_fechaFin"><?php echo $resultado->fechaFin->FldCaption() ?></span></td>
		<td<?php echo $resultado->fechaFin->CellAttributes() ?>>
<span id="el_resultado_fechaFin">
<input type="text" data-table="resultado" data-field="x_fechaFin" data-format="7" name="x_fechaFin" id="x_fechaFin" placeholder="<?php echo ew_HtmlEncode($resultado->fechaFin->getPlaceHolder()) ?>" value="<?php echo $resultado->fechaFin->EditValue ?>"<?php echo $resultado->fechaFin->EditAttributes() ?>>
<?php if (!$resultado->fechaFin->ReadOnly && !$resultado->fechaFin->Disabled && !isset($resultado->fechaFin->EditAttrs["readonly"]) && !isset($resultado->fechaFin->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fresultadoadd", "x_fechaFin", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $resultado->fechaFin->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
<?php
	if (in_array("actividad", explode(",", $resultado->getCurrentDetailTable())) && $actividad->DetailAdd) {
?>
<?php if ($resultado->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("actividad", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "actividadgrid.php" ?>
<?php } ?>
<div class="ewDesktopButton">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $resultado_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</div>
</form>
<script type="text/javascript">
fresultadoadd.Init();
</script>
<?php
$resultado_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$resultado_add->Page_Terminate();
?>
