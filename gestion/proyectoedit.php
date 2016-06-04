<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "proyectoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "objetivogridcls.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$proyecto_edit = NULL; // Initialize page object first

class cproyecto_edit extends cproyecto {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}";

	// Table name
	var $TableName = 'proyecto';

	// Page object name
	var $PageObjName = 'proyecto_edit';

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

		// Table object (proyecto)
		if (!isset($GLOBALS["proyecto"]) || get_class($GLOBALS["proyecto"]) == "cproyecto") {
			$GLOBALS["proyecto"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["proyecto"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'proyecto', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("proyectolist.php"));
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

			// Process auto fill for detail table 'objetivo'
			if (@$_POST["grid"] == "fobjetivogrid") {
				if (!isset($GLOBALS["objetivo_grid"])) $GLOBALS["objetivo_grid"] = new cobjetivo_grid;
				$GLOBALS["objetivo_grid"]->Page_Init();
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
		global $EW_EXPORT, $proyecto;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($proyecto);
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
		if (@$_GET["idProyecto"] <> "") {
			$this->idProyecto->setQueryStringValue($_GET["idProyecto"]);
		}

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
		if ($this->idProyecto->CurrentValue == "")
			$this->Page_Terminate("proyectolist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("proyectolist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			Case "U": // Update
				if ($this->getCurrentDetailTable() <> "") // Master/detail edit
					$sReturnUrl = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
				else
					$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "proyectolist.php")
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
		if (!$this->descripcion->FldIsDetailKey) {
			$this->descripcion->setFormValue($objForm->GetValue("x_descripcion"));
		}
		if (!$this->fechaInicio->FldIsDetailKey) {
			$this->fechaInicio->setFormValue($objForm->GetValue("x_fechaInicio"));
			$this->fechaInicio->CurrentValue = ew_UnFormatDateTime($this->fechaInicio->CurrentValue, 7);
		}
		if (!$this->fechaFin->FldIsDetailKey) {
			$this->fechaFin->setFormValue($objForm->GetValue("x_fechaFin"));
			$this->fechaFin->CurrentValue = ew_UnFormatDateTime($this->fechaFin->CurrentValue, 7);
		}
		if (!$this->usuarioLider->FldIsDetailKey) {
			$this->usuarioLider->setFormValue($objForm->GetValue("x_usuarioLider"));
		}
		if (!$this->usuarioEncargado->FldIsDetailKey) {
			$this->usuarioEncargado->setFormValue($objForm->GetValue("x_usuarioEncargado"));
		}
		if (!$this->prioridad->FldIsDetailKey) {
			$this->prioridad->setFormValue($objForm->GetValue("x_prioridad"));
		}
		if (!$this->estado->FldIsDetailKey) {
			$this->estado->setFormValue($objForm->GetValue("x_estado"));
		}
		if (!$this->idProyecto->FldIsDetailKey)
			$this->idProyecto->setFormValue($objForm->GetValue("x_idProyecto"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->idProyecto->CurrentValue = $this->idProyecto->FormValue;
		$this->nombre->CurrentValue = $this->nombre->FormValue;
		$this->descripcion->CurrentValue = $this->descripcion->FormValue;
		$this->fechaInicio->CurrentValue = $this->fechaInicio->FormValue;
		$this->fechaInicio->CurrentValue = ew_UnFormatDateTime($this->fechaInicio->CurrentValue, 7);
		$this->fechaFin->CurrentValue = $this->fechaFin->FormValue;
		$this->fechaFin->CurrentValue = ew_UnFormatDateTime($this->fechaFin->CurrentValue, 7);
		$this->usuarioLider->CurrentValue = $this->usuarioLider->FormValue;
		$this->usuarioEncargado->CurrentValue = $this->usuarioEncargado->FormValue;
		$this->prioridad->CurrentValue = $this->prioridad->FormValue;
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->idProyecto->DbValue = $row['idProyecto'];
		$this->nombre->DbValue = $row['nombre'];
		$this->descripcion->DbValue = $row['descripcion'];
		$this->fechaInicio->DbValue = $row['fechaInicio'];
		$this->fechaFin->DbValue = $row['fechaFin'];
		$this->fechaCreacion->DbValue = $row['fechaCreacion'];
		$this->usuarioCreacion->DbValue = $row['usuarioCreacion'];
		$this->usuarioLider->DbValue = $row['usuarioLider'];
		$this->usuarioEncargado->DbValue = $row['usuarioEncargado'];
		$this->cliente->DbValue = $row['cliente'];
		$this->prioridad->DbValue = $row['prioridad'];
		$this->fechaUltimoAcceso->DbValue = $row['fechaUltimoAcceso'];
		$this->fechaModificacion->DbValue = $row['fechaModificacion'];
		$this->usuarioModificacion->DbValue = $row['usuarioModificacion'];
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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

			// usuarioLider
			$this->usuarioLider->LinkCustomAttributes = "";
			$this->usuarioLider->HrefValue = "";
			$this->usuarioLider->TooltipValue = "";

			// usuarioEncargado
			$this->usuarioEncargado->LinkCustomAttributes = "";
			$this->usuarioEncargado->HrefValue = "";
			$this->usuarioEncargado->TooltipValue = "";

			// prioridad
			$this->prioridad->LinkCustomAttributes = "";
			$this->prioridad->HrefValue = "";
			$this->prioridad->TooltipValue = "";

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

			// descripcion
			$this->descripcion->EditAttrs["class"] = "form-control";
			$this->descripcion->EditCustomAttributes = "";
			$this->descripcion->EditValue = ew_HtmlEncode($this->descripcion->CurrentValue);
			$this->descripcion->PlaceHolder = ew_RemoveHtml($this->descripcion->FldCaption());

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

			// usuarioLider
			$this->usuarioLider->EditAttrs["class"] = "form-control";
			$this->usuarioLider->EditCustomAttributes = "";
			if (trim(strval($this->usuarioLider->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idUsuario`" . ew_SearchString("=", $this->usuarioLider->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `idUsuario`, `nombres` AS `DispFld`, `apellidos` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `usuario`";
			$sWhereWrk = "";
			$lookuptblfilter = "`tipoUsuario` = 3";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			if (!$GLOBALS["proyecto"]->UserIDAllow("edit")) $sWhereWrk = $GLOBALS["usuario"]->AddUserIDFilter($sWhereWrk);
			$this->Lookup_Selecting($this->usuarioLider, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->usuarioLider->EditValue = $arwrk;

			// usuarioEncargado
			$this->usuarioEncargado->EditAttrs["class"] = "form-control";
			$this->usuarioEncargado->EditCustomAttributes = "";
			if (trim(strval($this->usuarioEncargado->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idUsuario`" . ew_SearchString("=", $this->usuarioEncargado->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `idUsuario`, `nombres` AS `DispFld`, `apellidos` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `usuario`";
			$sWhereWrk = "";
			$lookuptblfilter = "`tipoUsuario` = 2";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			if (!$GLOBALS["proyecto"]->UserIDAllow("edit")) $sWhereWrk = $GLOBALS["usuario"]->AddUserIDFilter($sWhereWrk);
			$this->Lookup_Selecting($this->usuarioEncargado, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->usuarioEncargado->EditValue = $arwrk;

			// prioridad
			$this->prioridad->EditAttrs["class"] = "form-control";
			$this->prioridad->EditCustomAttributes = "";
			$this->prioridad->EditValue = $this->prioridad->Options(TRUE);

			// estado
			$this->estado->EditAttrs["class"] = "form-control";
			$this->estado->EditCustomAttributes = "";
			$this->estado->EditValue = $this->estado->Options(TRUE);

			// Edit refer script
			// nombre

			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";

			// descripcion
			$this->descripcion->LinkCustomAttributes = "";
			$this->descripcion->HrefValue = "";

			// fechaInicio
			$this->fechaInicio->LinkCustomAttributes = "";
			$this->fechaInicio->HrefValue = "";

			// fechaFin
			$this->fechaFin->LinkCustomAttributes = "";
			$this->fechaFin->HrefValue = "";

			// usuarioLider
			$this->usuarioLider->LinkCustomAttributes = "";
			$this->usuarioLider->HrefValue = "";

			// usuarioEncargado
			$this->usuarioEncargado->LinkCustomAttributes = "";
			$this->usuarioEncargado->HrefValue = "";

			// prioridad
			$this->prioridad->LinkCustomAttributes = "";
			$this->prioridad->HrefValue = "";

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
		if (!$this->fechaInicio->FldIsDetailKey && !is_null($this->fechaInicio->FormValue) && $this->fechaInicio->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fechaInicio->FldCaption(), $this->fechaInicio->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->fechaInicio->FormValue)) {
			ew_AddMessage($gsFormError, $this->fechaInicio->FldErrMsg());
		}
		if (!$this->fechaFin->FldIsDetailKey && !is_null($this->fechaFin->FormValue) && $this->fechaFin->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fechaFin->FldCaption(), $this->fechaFin->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->fechaFin->FormValue)) {
			ew_AddMessage($gsFormError, $this->fechaFin->FldErrMsg());
		}
		if (!$this->usuarioLider->FldIsDetailKey && !is_null($this->usuarioLider->FormValue) && $this->usuarioLider->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->usuarioLider->FldCaption(), $this->usuarioLider->ReqErrMsg));
		}
		if (!$this->usuarioEncargado->FldIsDetailKey && !is_null($this->usuarioEncargado->FormValue) && $this->usuarioEncargado->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->usuarioEncargado->FldCaption(), $this->usuarioEncargado->ReqErrMsg));
		}
		if (!$this->prioridad->FldIsDetailKey && !is_null($this->prioridad->FormValue) && $this->prioridad->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->prioridad->FldCaption(), $this->prioridad->ReqErrMsg));
		}
		if (!$this->estado->FldIsDetailKey && !is_null($this->estado->FormValue) && $this->estado->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->estado->FldCaption(), $this->estado->ReqErrMsg));
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("objetivo", $DetailTblVar) && $GLOBALS["objetivo"]->DetailEdit) {
			if (!isset($GLOBALS["objetivo_grid"])) $GLOBALS["objetivo_grid"] = new cobjetivo_grid(); // get detail page object
			$GLOBALS["objetivo_grid"]->ValidateGridForm();
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

			// descripcion
			$this->descripcion->SetDbValueDef($rsnew, $this->descripcion->CurrentValue, NULL, $this->descripcion->ReadOnly);

			// fechaInicio
			$this->fechaInicio->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechaInicio->CurrentValue, 7), NULL, $this->fechaInicio->ReadOnly);

			// fechaFin
			$this->fechaFin->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechaFin->CurrentValue, 7), NULL, $this->fechaFin->ReadOnly);

			// usuarioLider
			$this->usuarioLider->SetDbValueDef($rsnew, $this->usuarioLider->CurrentValue, 0, $this->usuarioLider->ReadOnly);

			// usuarioEncargado
			$this->usuarioEncargado->SetDbValueDef($rsnew, $this->usuarioEncargado->CurrentValue, 0, $this->usuarioEncargado->ReadOnly);

			// prioridad
			$this->prioridad->SetDbValueDef($rsnew, $this->prioridad->CurrentValue, "", $this->prioridad->ReadOnly);

			// estado
			$this->estado->SetDbValueDef($rsnew, $this->estado->CurrentValue, "", $this->estado->ReadOnly);

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
					if (in_array("objetivo", $DetailTblVar) && $GLOBALS["objetivo"]->DetailEdit) {
						if (!isset($GLOBALS["objetivo_grid"])) $GLOBALS["objetivo_grid"] = new cobjetivo_grid(); // Get detail page object
						$EditRow = $GLOBALS["objetivo_grid"]->GridUpdate();
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
			if (in_array("objetivo", $DetailTblVar)) {
				if (!isset($GLOBALS["objetivo_grid"]))
					$GLOBALS["objetivo_grid"] = new cobjetivo_grid;
				if ($GLOBALS["objetivo_grid"]->DetailEdit) {
					$GLOBALS["objetivo_grid"]->CurrentMode = "edit";
					$GLOBALS["objetivo_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["objetivo_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["objetivo_grid"]->setStartRecordNumber(1);
					$GLOBALS["objetivo_grid"]->proyecto->FldIsDetailKey = TRUE;
					$GLOBALS["objetivo_grid"]->proyecto->CurrentValue = $this->idProyecto->CurrentValue;
					$GLOBALS["objetivo_grid"]->proyecto->setSessionValue($GLOBALS["objetivo_grid"]->proyecto->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("proyectolist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'proyecto';
		$usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		global $Language;
		if (!$this->AuditTrailOnEdit) return;
		$table = 'proyecto';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['idProyecto'];

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
if (!isset($proyecto_edit)) $proyecto_edit = new cproyecto_edit();

// Page init
$proyecto_edit->Page_Init();

// Page main
$proyecto_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$proyecto_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fproyectoedit = new ew_Form("fproyectoedit", "edit");

// Validate form
fproyectoedit.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $proyecto->nombre->FldCaption(), $proyecto->nombre->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fechaInicio");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $proyecto->fechaInicio->FldCaption(), $proyecto->fechaInicio->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fechaInicio");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($proyecto->fechaInicio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fechaFin");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $proyecto->fechaFin->FldCaption(), $proyecto->fechaFin->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fechaFin");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($proyecto->fechaFin->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_usuarioLider");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $proyecto->usuarioLider->FldCaption(), $proyecto->usuarioLider->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_usuarioEncargado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $proyecto->usuarioEncargado->FldCaption(), $proyecto->usuarioEncargado->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_prioridad");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $proyecto->prioridad->FldCaption(), $proyecto->prioridad->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_estado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $proyecto->estado->FldCaption(), $proyecto->estado->ReqErrMsg)) ?>");

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
fproyectoedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fproyectoedit.ValidateRequired = true;
<?php } else { ?>
fproyectoedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fproyectoedit.Lists["x_usuarioLider"] = {"LinkField":"x_idUsuario","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_apellidos","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fproyectoedit.Lists["x_usuarioEncargado"] = {"LinkField":"x_idUsuario","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_apellidos","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fproyectoedit.Lists["x_prioridad"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fproyectoedit.Lists["x_prioridad"].Options = <?php echo json_encode($proyecto->prioridad->Options()) ?>;
fproyectoedit.Lists["x_estado"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fproyectoedit.Lists["x_estado"].Options = <?php echo json_encode($proyecto->estado->Options()) ?>;

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
<?php $proyecto_edit->ShowPageHeader(); ?>
<?php
$proyecto_edit->ShowMessage();
?>
<form name="fproyectoedit" id="fproyectoedit" class="<?php echo $proyecto_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($proyecto_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $proyecto_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="proyecto">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div class="ewDesktop">
<div>
<table id="tbl_proyectoedit" class="table table-bordered table-striped ewDesktopTable">
<?php if ($proyecto->nombre->Visible) { // nombre ?>
	<tr id="r_nombre">
		<td><span id="elh_proyecto_nombre"><?php echo $proyecto->nombre->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $proyecto->nombre->CellAttributes() ?>>
<span id="el_proyecto_nombre">
<input type="text" data-table="proyecto" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="128" placeholder="<?php echo ew_HtmlEncode($proyecto->nombre->getPlaceHolder()) ?>" value="<?php echo $proyecto->nombre->EditValue ?>"<?php echo $proyecto->nombre->EditAttributes() ?>>
</span>
<?php echo $proyecto->nombre->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($proyecto->descripcion->Visible) { // descripcion ?>
	<tr id="r_descripcion">
		<td><span id="elh_proyecto_descripcion"><?php echo $proyecto->descripcion->FldCaption() ?></span></td>
		<td<?php echo $proyecto->descripcion->CellAttributes() ?>>
<span id="el_proyecto_descripcion">
<input type="text" data-table="proyecto" data-field="x_descripcion" name="x_descripcion" id="x_descripcion" size="30" maxlength="128" placeholder="<?php echo ew_HtmlEncode($proyecto->descripcion->getPlaceHolder()) ?>" value="<?php echo $proyecto->descripcion->EditValue ?>"<?php echo $proyecto->descripcion->EditAttributes() ?>>
</span>
<?php echo $proyecto->descripcion->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($proyecto->fechaInicio->Visible) { // fechaInicio ?>
	<tr id="r_fechaInicio">
		<td><span id="elh_proyecto_fechaInicio"><?php echo $proyecto->fechaInicio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $proyecto->fechaInicio->CellAttributes() ?>>
<span id="el_proyecto_fechaInicio">
<input type="text" data-table="proyecto" data-field="x_fechaInicio" data-format="7" name="x_fechaInicio" id="x_fechaInicio" placeholder="<?php echo ew_HtmlEncode($proyecto->fechaInicio->getPlaceHolder()) ?>" value="<?php echo $proyecto->fechaInicio->EditValue ?>"<?php echo $proyecto->fechaInicio->EditAttributes() ?>>
<?php if (!$proyecto->fechaInicio->ReadOnly && !$proyecto->fechaInicio->Disabled && !isset($proyecto->fechaInicio->EditAttrs["readonly"]) && !isset($proyecto->fechaInicio->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fproyectoedit", "x_fechaInicio", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $proyecto->fechaInicio->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($proyecto->fechaFin->Visible) { // fechaFin ?>
	<tr id="r_fechaFin">
		<td><span id="elh_proyecto_fechaFin"><?php echo $proyecto->fechaFin->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $proyecto->fechaFin->CellAttributes() ?>>
<span id="el_proyecto_fechaFin">
<input type="text" data-table="proyecto" data-field="x_fechaFin" data-format="7" name="x_fechaFin" id="x_fechaFin" placeholder="<?php echo ew_HtmlEncode($proyecto->fechaFin->getPlaceHolder()) ?>" value="<?php echo $proyecto->fechaFin->EditValue ?>"<?php echo $proyecto->fechaFin->EditAttributes() ?>>
<?php if (!$proyecto->fechaFin->ReadOnly && !$proyecto->fechaFin->Disabled && !isset($proyecto->fechaFin->EditAttrs["readonly"]) && !isset($proyecto->fechaFin->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fproyectoedit", "x_fechaFin", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $proyecto->fechaFin->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($proyecto->usuarioLider->Visible) { // usuarioLider ?>
	<tr id="r_usuarioLider">
		<td><span id="elh_proyecto_usuarioLider"><?php echo $proyecto->usuarioLider->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $proyecto->usuarioLider->CellAttributes() ?>>
<span id="el_proyecto_usuarioLider">
<select data-table="proyecto" data-field="x_usuarioLider" data-value-separator="<?php echo ew_HtmlEncode(is_array($proyecto->usuarioLider->DisplayValueSeparator) ? json_encode($proyecto->usuarioLider->DisplayValueSeparator) : $proyecto->usuarioLider->DisplayValueSeparator) ?>" id="x_usuarioLider" name="x_usuarioLider"<?php echo $proyecto->usuarioLider->EditAttributes() ?>>
<?php
if (is_array($proyecto->usuarioLider->EditValue)) {
	$arwrk = $proyecto->usuarioLider->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($proyecto->usuarioLider->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $proyecto->usuarioLider->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($proyecto->usuarioLider->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($proyecto->usuarioLider->CurrentValue) ?>" selected><?php echo $proyecto->usuarioLider->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `idUsuario`, `nombres` AS `DispFld`, `apellidos` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `usuario`";
$sWhereWrk = "";
$lookuptblfilter = "`tipoUsuario` = 3";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
if (!$GLOBALS["proyecto"]->UserIDAllow("edit")) $sWhereWrk = $GLOBALS["usuario"]->AddUserIDFilter($sWhereWrk);
$proyecto->usuarioLider->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$proyecto->usuarioLider->LookupFilters += array("f0" => "`idUsuario` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$proyecto->Lookup_Selecting($proyecto->usuarioLider, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $proyecto->usuarioLider->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_usuarioLider" id="s_x_usuarioLider" value="<?php echo $proyecto->usuarioLider->LookupFilterQuery() ?>">
</span>
<?php echo $proyecto->usuarioLider->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($proyecto->usuarioEncargado->Visible) { // usuarioEncargado ?>
	<tr id="r_usuarioEncargado">
		<td><span id="elh_proyecto_usuarioEncargado"><?php echo $proyecto->usuarioEncargado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $proyecto->usuarioEncargado->CellAttributes() ?>>
<span id="el_proyecto_usuarioEncargado">
<select data-table="proyecto" data-field="x_usuarioEncargado" data-value-separator="<?php echo ew_HtmlEncode(is_array($proyecto->usuarioEncargado->DisplayValueSeparator) ? json_encode($proyecto->usuarioEncargado->DisplayValueSeparator) : $proyecto->usuarioEncargado->DisplayValueSeparator) ?>" id="x_usuarioEncargado" name="x_usuarioEncargado"<?php echo $proyecto->usuarioEncargado->EditAttributes() ?>>
<?php
if (is_array($proyecto->usuarioEncargado->EditValue)) {
	$arwrk = $proyecto->usuarioEncargado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($proyecto->usuarioEncargado->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $proyecto->usuarioEncargado->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($proyecto->usuarioEncargado->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($proyecto->usuarioEncargado->CurrentValue) ?>" selected><?php echo $proyecto->usuarioEncargado->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `idUsuario`, `nombres` AS `DispFld`, `apellidos` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `usuario`";
$sWhereWrk = "";
$lookuptblfilter = "`tipoUsuario` = 2";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
if (!$GLOBALS["proyecto"]->UserIDAllow("edit")) $sWhereWrk = $GLOBALS["usuario"]->AddUserIDFilter($sWhereWrk);
$proyecto->usuarioEncargado->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$proyecto->usuarioEncargado->LookupFilters += array("f0" => "`idUsuario` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$proyecto->Lookup_Selecting($proyecto->usuarioEncargado, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $proyecto->usuarioEncargado->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_usuarioEncargado" id="s_x_usuarioEncargado" value="<?php echo $proyecto->usuarioEncargado->LookupFilterQuery() ?>">
</span>
<?php echo $proyecto->usuarioEncargado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($proyecto->prioridad->Visible) { // prioridad ?>
	<tr id="r_prioridad">
		<td><span id="elh_proyecto_prioridad"><?php echo $proyecto->prioridad->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $proyecto->prioridad->CellAttributes() ?>>
<span id="el_proyecto_prioridad">
<select data-table="proyecto" data-field="x_prioridad" data-value-separator="<?php echo ew_HtmlEncode(is_array($proyecto->prioridad->DisplayValueSeparator) ? json_encode($proyecto->prioridad->DisplayValueSeparator) : $proyecto->prioridad->DisplayValueSeparator) ?>" id="x_prioridad" name="x_prioridad"<?php echo $proyecto->prioridad->EditAttributes() ?>>
<?php
if (is_array($proyecto->prioridad->EditValue)) {
	$arwrk = $proyecto->prioridad->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($proyecto->prioridad->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $proyecto->prioridad->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($proyecto->prioridad->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($proyecto->prioridad->CurrentValue) ?>" selected><?php echo $proyecto->prioridad->CurrentValue ?></option>
<?php
    }
}
?>
</select>
</span>
<?php echo $proyecto->prioridad->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($proyecto->estado->Visible) { // estado ?>
	<tr id="r_estado">
		<td><span id="elh_proyecto_estado"><?php echo $proyecto->estado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $proyecto->estado->CellAttributes() ?>>
<span id="el_proyecto_estado">
<select data-table="proyecto" data-field="x_estado" data-value-separator="<?php echo ew_HtmlEncode(is_array($proyecto->estado->DisplayValueSeparator) ? json_encode($proyecto->estado->DisplayValueSeparator) : $proyecto->estado->DisplayValueSeparator) ?>" id="x_estado" name="x_estado"<?php echo $proyecto->estado->EditAttributes() ?>>
<?php
if (is_array($proyecto->estado->EditValue)) {
	$arwrk = $proyecto->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($proyecto->estado->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $proyecto->estado->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($proyecto->estado->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($proyecto->estado->CurrentValue) ?>" selected><?php echo $proyecto->estado->CurrentValue ?></option>
<?php
    }
}
?>
</select>
</span>
<?php echo $proyecto->estado->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
<input type="hidden" data-table="proyecto" data-field="x_idProyecto" name="x_idProyecto" id="x_idProyecto" value="<?php echo ew_HtmlEncode($proyecto->idProyecto->CurrentValue) ?>">
<?php
	if (in_array("objetivo", explode(",", $proyecto->getCurrentDetailTable())) && $objetivo->DetailEdit) {
?>
<?php if ($proyecto->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("objetivo", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "objetivogrid.php" ?>
<?php } ?>
<div class="ewDesktopButton">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $proyecto_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</div>
</form>
<script type="text/javascript">
fproyectoedit.Init();
</script>
<?php
$proyecto_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$proyecto_edit->Page_Terminate();
?>
