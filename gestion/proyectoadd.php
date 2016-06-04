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

$proyecto_add = NULL; // Initialize page object first

class cproyecto_add extends cproyecto {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}";

	// Table name
	var $TableName = 'proyecto';

	// Page object name
	var $PageObjName = 'proyecto_add';

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

		// Table object (proyecto)
		if (!isset($GLOBALS["proyecto"]) || get_class($GLOBALS["proyecto"]) == "cproyecto") {
			$GLOBALS["proyecto"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["proyecto"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
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

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["idProyecto"] != "") {
				$this->idProyecto->setQueryStringValue($_GET["idProyecto"]);
				$this->setKey("idProyecto", $this->idProyecto->CurrentValue); // Set up key
			} else {
				$this->setKey("idProyecto", ""); // Clear key
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
					$this->Page_Terminate("proyectolist.php"); // No matching record, return to list
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
					if (ew_GetPageName($sReturnUrl) == "proyectolist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "proyectoview.php")
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
		$this->nombre->CurrentValue = NULL;
		$this->nombre->OldValue = $this->nombre->CurrentValue;
		$this->descripcion->CurrentValue = NULL;
		$this->descripcion->OldValue = $this->descripcion->CurrentValue;
		$this->fechaInicio->CurrentValue = NULL;
		$this->fechaInicio->OldValue = $this->fechaInicio->CurrentValue;
		$this->fechaFin->CurrentValue = NULL;
		$this->fechaFin->OldValue = $this->fechaFin->CurrentValue;
		$this->usuarioLider->CurrentValue = NULL;
		$this->usuarioLider->OldValue = $this->usuarioLider->CurrentValue;
		$this->usuarioEncargado->CurrentValue = NULL;
		$this->usuarioEncargado->OldValue = $this->usuarioEncargado->CurrentValue;
		$this->cliente->CurrentValue = NULL;
		$this->cliente->OldValue = $this->cliente->CurrentValue;
		$this->prioridad->CurrentValue = "Estándar";
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
		if (!$this->cliente->FldIsDetailKey) {
			$this->cliente->setFormValue($objForm->GetValue("x_cliente"));
		}
		if (!$this->prioridad->FldIsDetailKey) {
			$this->prioridad->setFormValue($objForm->GetValue("x_prioridad"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nombre->CurrentValue = $this->nombre->FormValue;
		$this->descripcion->CurrentValue = $this->descripcion->FormValue;
		$this->fechaInicio->CurrentValue = $this->fechaInicio->FormValue;
		$this->fechaInicio->CurrentValue = ew_UnFormatDateTime($this->fechaInicio->CurrentValue, 7);
		$this->fechaFin->CurrentValue = $this->fechaFin->FormValue;
		$this->fechaFin->CurrentValue = ew_UnFormatDateTime($this->fechaFin->CurrentValue, 7);
		$this->usuarioLider->CurrentValue = $this->usuarioLider->FormValue;
		$this->usuarioEncargado->CurrentValue = $this->usuarioEncargado->FormValue;
		$this->cliente->CurrentValue = $this->cliente->FormValue;
		$this->prioridad->CurrentValue = $this->prioridad->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("idProyecto")) <> "")
			$this->idProyecto->CurrentValue = $this->getKey("idProyecto"); // idProyecto
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

			// cliente
			$this->cliente->LinkCustomAttributes = "";
			$this->cliente->HrefValue = "";
			$this->cliente->TooltipValue = "";

			// prioridad
			$this->prioridad->LinkCustomAttributes = "";
			$this->prioridad->HrefValue = "";
			$this->prioridad->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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
			if (!$GLOBALS["proyecto"]->UserIDAllow("add")) $sWhereWrk = $GLOBALS["usuario"]->AddUserIDFilter($sWhereWrk);
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
			if (!$GLOBALS["proyecto"]->UserIDAllow("add")) $sWhereWrk = $GLOBALS["usuario"]->AddUserIDFilter($sWhereWrk);
			$this->Lookup_Selecting($this->usuarioEncargado, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->usuarioEncargado->EditValue = $arwrk;

			// cliente
			$this->cliente->EditAttrs["class"] = "form-control";
			$this->cliente->EditCustomAttributes = "";
			if (trim(strval($this->cliente->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idCliente`" . ew_SearchString("=", $this->cliente->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `idCliente`, `cliente` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cliente`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->cliente, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->cliente->EditValue = $arwrk;

			// prioridad
			$this->prioridad->EditAttrs["class"] = "form-control";
			$this->prioridad->EditCustomAttributes = "";
			$this->prioridad->EditValue = $this->prioridad->Options(TRUE);

			// Add refer script
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

			// cliente
			$this->cliente->LinkCustomAttributes = "";
			$this->cliente->HrefValue = "";

			// prioridad
			$this->prioridad->LinkCustomAttributes = "";
			$this->prioridad->HrefValue = "";
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
		if (!$this->cliente->FldIsDetailKey && !is_null($this->cliente->FormValue) && $this->cliente->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->cliente->FldCaption(), $this->cliente->ReqErrMsg));
		}
		if (!$this->prioridad->FldIsDetailKey && !is_null($this->prioridad->FormValue) && $this->prioridad->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->prioridad->FldCaption(), $this->prioridad->ReqErrMsg));
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("objetivo", $DetailTblVar) && $GLOBALS["objetivo"]->DetailAdd) {
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

		// nombre
		$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, "", FALSE);

		// descripcion
		$this->descripcion->SetDbValueDef($rsnew, $this->descripcion->CurrentValue, NULL, FALSE);

		// fechaInicio
		$this->fechaInicio->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechaInicio->CurrentValue, 7), NULL, FALSE);

		// fechaFin
		$this->fechaFin->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechaFin->CurrentValue, 7), NULL, FALSE);

		// usuarioLider
		$this->usuarioLider->SetDbValueDef($rsnew, $this->usuarioLider->CurrentValue, 0, FALSE);

		// usuarioEncargado
		$this->usuarioEncargado->SetDbValueDef($rsnew, $this->usuarioEncargado->CurrentValue, 0, FALSE);

		// cliente
		$this->cliente->SetDbValueDef($rsnew, $this->cliente->CurrentValue, 0, FALSE);

		// prioridad
		$this->prioridad->SetDbValueDef($rsnew, $this->prioridad->CurrentValue, "", strval($this->prioridad->CurrentValue) == "");

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->idProyecto->setDbValue($conn->Insert_ID());
				$rsnew['idProyecto'] = $this->idProyecto->DbValue;
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
			if (in_array("objetivo", $DetailTblVar) && $GLOBALS["objetivo"]->DetailAdd) {
				$GLOBALS["objetivo"]->proyecto->setSessionValue($this->idProyecto->CurrentValue); // Set master key
				if (!isset($GLOBALS["objetivo_grid"])) $GLOBALS["objetivo_grid"] = new cobjetivo_grid(); // Get detail page object
				$AddRow = $GLOBALS["objetivo_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["objetivo"]->proyecto->setSessionValue(""); // Clear master key if insert failed
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
				if ($GLOBALS["objetivo_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["objetivo_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["objetivo_grid"]->CurrentMode = "add";
					$GLOBALS["objetivo_grid"]->CurrentAction = "gridadd";

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
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'proyecto';
		$usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnAdd) return;
		$table = 'proyecto';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['idProyecto'];

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
if (!isset($proyecto_add)) $proyecto_add = new cproyecto_add();

// Page init
$proyecto_add->Page_Init();

// Page main
$proyecto_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$proyecto_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fproyectoadd = new ew_Form("fproyectoadd", "add");

// Validate form
fproyectoadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_cliente");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $proyecto->cliente->FldCaption(), $proyecto->cliente->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_prioridad");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $proyecto->prioridad->FldCaption(), $proyecto->prioridad->ReqErrMsg)) ?>");

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
fproyectoadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fproyectoadd.ValidateRequired = true;
<?php } else { ?>
fproyectoadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fproyectoadd.Lists["x_usuarioLider"] = {"LinkField":"x_idUsuario","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_apellidos","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fproyectoadd.Lists["x_usuarioEncargado"] = {"LinkField":"x_idUsuario","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_apellidos","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fproyectoadd.Lists["x_cliente"] = {"LinkField":"x_idCliente","Ajax":true,"AutoFill":false,"DisplayFields":["x_cliente","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fproyectoadd.Lists["x_prioridad"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fproyectoadd.Lists["x_prioridad"].Options = <?php echo json_encode($proyecto->prioridad->Options()) ?>;

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
<?php $proyecto_add->ShowPageHeader(); ?>
<?php
$proyecto_add->ShowMessage();
?>
<form name="fproyectoadd" id="fproyectoadd" class="<?php echo $proyecto_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($proyecto_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $proyecto_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="proyecto">
<input type="hidden" name="a_add" id="a_add" value="A">
<div class="ewDesktop">
<div>
<table id="tbl_proyectoadd" class="table table-bordered table-striped ewDesktopTable">
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
ew_CreateCalendar("fproyectoadd", "x_fechaInicio", "%d/%m/%Y");
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
ew_CreateCalendar("fproyectoadd", "x_fechaFin", "%d/%m/%Y");
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
if (!$GLOBALS["proyecto"]->UserIDAllow("add")) $sWhereWrk = $GLOBALS["usuario"]->AddUserIDFilter($sWhereWrk);
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
if (!$GLOBALS["proyecto"]->UserIDAllow("add")) $sWhereWrk = $GLOBALS["usuario"]->AddUserIDFilter($sWhereWrk);
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
<?php if ($proyecto->cliente->Visible) { // cliente ?>
	<tr id="r_cliente">
		<td><span id="elh_proyecto_cliente"><?php echo $proyecto->cliente->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $proyecto->cliente->CellAttributes() ?>>
<span id="el_proyecto_cliente">
<select data-table="proyecto" data-field="x_cliente" data-value-separator="<?php echo ew_HtmlEncode(is_array($proyecto->cliente->DisplayValueSeparator) ? json_encode($proyecto->cliente->DisplayValueSeparator) : $proyecto->cliente->DisplayValueSeparator) ?>" id="x_cliente" name="x_cliente"<?php echo $proyecto->cliente->EditAttributes() ?>>
<?php
if (is_array($proyecto->cliente->EditValue)) {
	$arwrk = $proyecto->cliente->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($proyecto->cliente->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $proyecto->cliente->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($proyecto->cliente->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($proyecto->cliente->CurrentValue) ?>" selected><?php echo $proyecto->cliente->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `idCliente`, `cliente` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cliente`";
$sWhereWrk = "";
$proyecto->cliente->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$proyecto->cliente->LookupFilters += array("f0" => "`idCliente` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$proyecto->Lookup_Selecting($proyecto->cliente, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $proyecto->cliente->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_cliente" id="s_x_cliente" value="<?php echo $proyecto->cliente->LookupFilterQuery() ?>">
</span>
<?php echo $proyecto->cliente->CustomMsg ?></td>
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
</table>
</div>
<?php
	if (in_array("objetivo", explode(",", $proyecto->getCurrentDetailTable())) && $objetivo->DetailAdd) {
?>
<?php if ($proyecto->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("objetivo", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "objetivogrid.php" ?>
<?php } ?>
<div class="ewDesktopButton">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $proyecto_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</div>
</form>
<script type="text/javascript">
fproyectoadd.Init();
</script>
<?php
$proyecto_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$proyecto_add->Page_Terminate();
?>
