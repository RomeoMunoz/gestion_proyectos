<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$usuario_add = NULL; // Initialize page object first

class cusuario_add extends cusuario {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}";

	// Table name
	var $TableName = 'usuario';

	// Page object name
	var $PageObjName = 'usuario_add';

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

		// Table object (usuario)
		if (!isset($GLOBALS["usuario"]) || get_class($GLOBALS["usuario"]) == "cusuario") {
			$GLOBALS["usuario"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["usuario"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'usuario', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("usuariolist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
			if (strval($Security->CurrentUserID()) == "") {
				$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
				$this->Page_Terminate(ew_GetUrl("usuariolist.php"));
			}
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
		global $EW_EXPORT, $usuario;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($usuario);
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
			if (@$_GET["idUsuario"] != "") {
				$this->idUsuario->setQueryStringValue($_GET["idUsuario"]);
				$this->setKey("idUsuario", $this->idUsuario->CurrentValue); // Set up key
			} else {
				$this->setKey("idUsuario", ""); // Clear key
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
					$this->Page_Terminate("usuariolist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "usuariolist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "usuarioview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
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
		$this->usuario->CurrentValue = NULL;
		$this->usuario->OldValue = $this->usuario->CurrentValue;
		$this->password->CurrentValue = NULL;
		$this->password->OldValue = $this->password->CurrentValue;
		$this->nombres->CurrentValue = NULL;
		$this->nombres->OldValue = $this->nombres->CurrentValue;
		$this->apellidos->CurrentValue = NULL;
		$this->apellidos->OldValue = $this->apellidos->CurrentValue;
		$this->direccion->CurrentValue = NULL;
		$this->direccion->OldValue = $this->direccion->CurrentValue;
		$this->telefonos->CurrentValue = NULL;
		$this->telefonos->OldValue = $this->telefonos->CurrentValue;
		$this->tipoUsuario->CurrentValue = NULL;
		$this->tipoUsuario->OldValue = $this->tipoUsuario->CurrentValue;
		$this->tipoIngreso->CurrentValue = NULL;
		$this->tipoIngreso->OldValue = $this->tipoIngreso->CurrentValue;
		$this->grupo->CurrentValue = NULL;
		$this->grupo->OldValue = $this->grupo->CurrentValue;
		$this->etiquetas->CurrentValue = NULL;
		$this->etiquetas->OldValue = $this->etiquetas->CurrentValue;
		$this->iniciales->CurrentValue = NULL;
		$this->iniciales->OldValue = $this->iniciales->CurrentValue;
		$this->sueldo->CurrentValue = 0.00;
		$this->tipoSueldo->CurrentValue = "Mes";
		$this->horaExtra->CurrentValue = NULL;
		$this->horaExtra->OldValue = $this->horaExtra->CurrentValue;
		$this->empresa->CurrentValue = NULL;
		$this->empresa->OldValue = $this->empresa->CurrentValue;
		$this->userlevelid->CurrentValue = NULL;
		$this->userlevelid->OldValue = $this->userlevelid->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->usuario->FldIsDetailKey) {
			$this->usuario->setFormValue($objForm->GetValue("x_usuario"));
		}
		if (!$this->password->FldIsDetailKey) {
			$this->password->setFormValue($objForm->GetValue("x_password"));
		}
		if (!$this->nombres->FldIsDetailKey) {
			$this->nombres->setFormValue($objForm->GetValue("x_nombres"));
		}
		if (!$this->apellidos->FldIsDetailKey) {
			$this->apellidos->setFormValue($objForm->GetValue("x_apellidos"));
		}
		if (!$this->direccion->FldIsDetailKey) {
			$this->direccion->setFormValue($objForm->GetValue("x_direccion"));
		}
		if (!$this->telefonos->FldIsDetailKey) {
			$this->telefonos->setFormValue($objForm->GetValue("x_telefonos"));
		}
		if (!$this->tipoUsuario->FldIsDetailKey) {
			$this->tipoUsuario->setFormValue($objForm->GetValue("x_tipoUsuario"));
		}
		if (!$this->tipoIngreso->FldIsDetailKey) {
			$this->tipoIngreso->setFormValue($objForm->GetValue("x_tipoIngreso"));
		}
		if (!$this->grupo->FldIsDetailKey) {
			$this->grupo->setFormValue($objForm->GetValue("x_grupo"));
		}
		if (!$this->etiquetas->FldIsDetailKey) {
			$this->etiquetas->setFormValue($objForm->GetValue("x_etiquetas"));
		}
		if (!$this->iniciales->FldIsDetailKey) {
			$this->iniciales->setFormValue($objForm->GetValue("x_iniciales"));
		}
		if (!$this->sueldo->FldIsDetailKey) {
			$this->sueldo->setFormValue($objForm->GetValue("x_sueldo"));
		}
		if (!$this->tipoSueldo->FldIsDetailKey) {
			$this->tipoSueldo->setFormValue($objForm->GetValue("x_tipoSueldo"));
		}
		if (!$this->horaExtra->FldIsDetailKey) {
			$this->horaExtra->setFormValue($objForm->GetValue("x_horaExtra"));
		}
		if (!$this->empresa->FldIsDetailKey) {
			$this->empresa->setFormValue($objForm->GetValue("x_empresa"));
		}
		if (!$this->userlevelid->FldIsDetailKey) {
			$this->userlevelid->setFormValue($objForm->GetValue("x_userlevelid"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->usuario->CurrentValue = $this->usuario->FormValue;
		$this->password->CurrentValue = $this->password->FormValue;
		$this->nombres->CurrentValue = $this->nombres->FormValue;
		$this->apellidos->CurrentValue = $this->apellidos->FormValue;
		$this->direccion->CurrentValue = $this->direccion->FormValue;
		$this->telefonos->CurrentValue = $this->telefonos->FormValue;
		$this->tipoUsuario->CurrentValue = $this->tipoUsuario->FormValue;
		$this->tipoIngreso->CurrentValue = $this->tipoIngreso->FormValue;
		$this->grupo->CurrentValue = $this->grupo->FormValue;
		$this->etiquetas->CurrentValue = $this->etiquetas->FormValue;
		$this->iniciales->CurrentValue = $this->iniciales->FormValue;
		$this->sueldo->CurrentValue = $this->sueldo->FormValue;
		$this->tipoSueldo->CurrentValue = $this->tipoSueldo->FormValue;
		$this->horaExtra->CurrentValue = $this->horaExtra->FormValue;
		$this->empresa->CurrentValue = $this->empresa->FormValue;
		$this->userlevelid->CurrentValue = $this->userlevelid->FormValue;
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

		// Check if valid user id
		if ($res) {
			$res = $this->ShowOptionLink('add');
			if (!$res) {
				$sUserIdMsg = ew_DeniedMsg();
				$this->setFailureMessage($sUserIdMsg);
			}
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->idUsuario->setDbValue($rs->fields('idUsuario'));
		$this->usuario->setDbValue($rs->fields('usuario'));
		$this->password->setDbValue($rs->fields('password'));
		$this->nombres->setDbValue($rs->fields('nombres'));
		$this->apellidos->setDbValue($rs->fields('apellidos'));
		$this->direccion->setDbValue($rs->fields('direccion'));
		$this->telefonos->setDbValue($rs->fields('telefonos'));
		$this->tipoUsuario->setDbValue($rs->fields('tipoUsuario'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->tipoIngreso->setDbValue($rs->fields('tipoIngreso'));
		$this->grupo->setDbValue($rs->fields('grupo'));
		$this->etiquetas->setDbValue($rs->fields('etiquetas'));
		$this->iniciales->setDbValue($rs->fields('iniciales'));
		$this->sueldo->setDbValue($rs->fields('sueldo'));
		$this->tipoSueldo->setDbValue($rs->fields('tipoSueldo'));
		$this->horaExtra->setDbValue($rs->fields('horaExtra'));
		$this->empresa->setDbValue($rs->fields('empresa'));
		$this->userlevelid->setDbValue($rs->fields('userlevelid'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->idUsuario->DbValue = $row['idUsuario'];
		$this->usuario->DbValue = $row['usuario'];
		$this->password->DbValue = $row['password'];
		$this->nombres->DbValue = $row['nombres'];
		$this->apellidos->DbValue = $row['apellidos'];
		$this->direccion->DbValue = $row['direccion'];
		$this->telefonos->DbValue = $row['telefonos'];
		$this->tipoUsuario->DbValue = $row['tipoUsuario'];
		$this->estado->DbValue = $row['estado'];
		$this->tipoIngreso->DbValue = $row['tipoIngreso'];
		$this->grupo->DbValue = $row['grupo'];
		$this->etiquetas->DbValue = $row['etiquetas'];
		$this->iniciales->DbValue = $row['iniciales'];
		$this->sueldo->DbValue = $row['sueldo'];
		$this->tipoSueldo->DbValue = $row['tipoSueldo'];
		$this->horaExtra->DbValue = $row['horaExtra'];
		$this->empresa->DbValue = $row['empresa'];
		$this->userlevelid->DbValue = $row['userlevelid'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("idUsuario")) <> "")
			$this->idUsuario->CurrentValue = $this->getKey("idUsuario"); // idUsuario
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
		// Convert decimal values if posted back

		if ($this->sueldo->FormValue == $this->sueldo->CurrentValue && is_numeric(ew_StrToFloat($this->sueldo->CurrentValue)))
			$this->sueldo->CurrentValue = ew_StrToFloat($this->sueldo->CurrentValue);

		// Convert decimal values if posted back
		if ($this->horaExtra->FormValue == $this->horaExtra->CurrentValue && is_numeric(ew_StrToFloat($this->horaExtra->CurrentValue)))
			$this->horaExtra->CurrentValue = ew_StrToFloat($this->horaExtra->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// idUsuario
		// usuario
		// password
		// nombres
		// apellidos
		// direccion
		// telefonos
		// tipoUsuario
		// estado
		// tipoIngreso
		// grupo
		// etiquetas
		// iniciales
		// sueldo
		// tipoSueldo
		// horaExtra
		// empresa
		// userlevelid

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// idUsuario
		$this->idUsuario->ViewValue = $this->idUsuario->CurrentValue;
		$this->idUsuario->ViewCustomAttributes = "";

		// usuario
		$this->usuario->ViewValue = $this->usuario->CurrentValue;
		$this->usuario->ViewCustomAttributes = "";

		// password
		$this->password->ViewValue = $Language->Phrase("PasswordMask");
		$this->password->ViewCustomAttributes = "";

		// nombres
		$this->nombres->ViewValue = $this->nombres->CurrentValue;
		$this->nombres->ViewCustomAttributes = "";

		// apellidos
		$this->apellidos->ViewValue = $this->apellidos->CurrentValue;
		$this->apellidos->ViewCustomAttributes = "";

		// direccion
		$this->direccion->ViewValue = $this->direccion->CurrentValue;
		$this->direccion->ViewCustomAttributes = "";

		// telefonos
		$this->telefonos->ViewValue = $this->telefonos->CurrentValue;
		$this->telefonos->ViewCustomAttributes = "";

		// tipoUsuario
		if (strval($this->tipoUsuario->CurrentValue) <> "") {
			$sFilterWrk = "`idUsuarioTipo`" . ew_SearchString("=", $this->tipoUsuario->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `idUsuarioTipo`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `usuario_tipo`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->tipoUsuario, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->tipoUsuario->ViewValue = $this->tipoUsuario->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->tipoUsuario->ViewValue = $this->tipoUsuario->CurrentValue;
			}
		} else {
			$this->tipoUsuario->ViewValue = NULL;
		}
		$this->tipoUsuario->ViewCustomAttributes = "";

		// estado
		if (strval($this->estado->CurrentValue) <> "") {
			$this->estado->ViewValue = $this->estado->OptionCaption($this->estado->CurrentValue);
		} else {
			$this->estado->ViewValue = NULL;
		}
		$this->estado->ViewCustomAttributes = "";

		// tipoIngreso
		if (strval($this->tipoIngreso->CurrentValue) <> "") {
			$sFilterWrk = "`idIngresoTipo`" . ew_SearchString("=", $this->tipoIngreso->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `idIngresoTipo`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `ingreso_tipo`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->tipoIngreso, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->tipoIngreso->ViewValue = $this->tipoIngreso->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->tipoIngreso->ViewValue = $this->tipoIngreso->CurrentValue;
			}
		} else {
			$this->tipoIngreso->ViewValue = NULL;
		}
		$this->tipoIngreso->ViewCustomAttributes = "";

		// grupo
		$this->grupo->ViewValue = $this->grupo->CurrentValue;
		$this->grupo->ViewCustomAttributes = "";

		// etiquetas
		$this->etiquetas->ViewValue = $this->etiquetas->CurrentValue;
		$this->etiquetas->ViewCustomAttributes = "";

		// iniciales
		$this->iniciales->ViewValue = $this->iniciales->CurrentValue;
		$this->iniciales->ViewCustomAttributes = "";

		// sueldo
		$this->sueldo->ViewValue = $this->sueldo->CurrentValue;
		$this->sueldo->ViewCustomAttributes = "";

		// tipoSueldo
		if (strval($this->tipoSueldo->CurrentValue) <> "") {
			$this->tipoSueldo->ViewValue = $this->tipoSueldo->OptionCaption($this->tipoSueldo->CurrentValue);
		} else {
			$this->tipoSueldo->ViewValue = NULL;
		}
		$this->tipoSueldo->ViewCustomAttributes = "";

		// horaExtra
		$this->horaExtra->ViewValue = $this->horaExtra->CurrentValue;
		$this->horaExtra->ViewCustomAttributes = "";

		// empresa
		if (strval($this->empresa->CurrentValue) <> "") {
			$sFilterWrk = "`idEmpresa`" . ew_SearchString("=", $this->empresa->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `idEmpresa`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empresa`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->empresa, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->empresa->ViewValue = $this->empresa->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->empresa->ViewValue = $this->empresa->CurrentValue;
			}
		} else {
			$this->empresa->ViewValue = NULL;
		}
		$this->empresa->ViewCustomAttributes = "";

		// userlevelid
		if ($Security->CanAdmin()) { // System admin
		if (strval($this->userlevelid->CurrentValue) <> "") {
			$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->userlevelid->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->userlevelid, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->userlevelid->ViewValue = $this->userlevelid->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->userlevelid->ViewValue = $this->userlevelid->CurrentValue;
			}
		} else {
			$this->userlevelid->ViewValue = NULL;
		}
		} else {
			$this->userlevelid->ViewValue = $Language->Phrase("PasswordMask");
		}
		$this->userlevelid->ViewCustomAttributes = "";

			// usuario
			$this->usuario->LinkCustomAttributes = "";
			$this->usuario->HrefValue = "";
			$this->usuario->TooltipValue = "";

			// password
			$this->password->LinkCustomAttributes = "";
			$this->password->HrefValue = "";
			$this->password->TooltipValue = "";

			// nombres
			$this->nombres->LinkCustomAttributes = "";
			$this->nombres->HrefValue = "";
			$this->nombres->TooltipValue = "";

			// apellidos
			$this->apellidos->LinkCustomAttributes = "";
			$this->apellidos->HrefValue = "";
			$this->apellidos->TooltipValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";
			$this->direccion->TooltipValue = "";

			// telefonos
			$this->telefonos->LinkCustomAttributes = "";
			$this->telefonos->HrefValue = "";
			$this->telefonos->TooltipValue = "";

			// tipoUsuario
			$this->tipoUsuario->LinkCustomAttributes = "";
			$this->tipoUsuario->HrefValue = "";
			$this->tipoUsuario->TooltipValue = "";

			// tipoIngreso
			$this->tipoIngreso->LinkCustomAttributes = "";
			$this->tipoIngreso->HrefValue = "";
			$this->tipoIngreso->TooltipValue = "";

			// grupo
			$this->grupo->LinkCustomAttributes = "";
			$this->grupo->HrefValue = "";
			$this->grupo->TooltipValue = "";

			// etiquetas
			$this->etiquetas->LinkCustomAttributes = "";
			$this->etiquetas->HrefValue = "";
			$this->etiquetas->TooltipValue = "";

			// iniciales
			$this->iniciales->LinkCustomAttributes = "";
			$this->iniciales->HrefValue = "";
			$this->iniciales->TooltipValue = "";

			// sueldo
			$this->sueldo->LinkCustomAttributes = "";
			$this->sueldo->HrefValue = "";
			$this->sueldo->TooltipValue = "";

			// tipoSueldo
			$this->tipoSueldo->LinkCustomAttributes = "";
			$this->tipoSueldo->HrefValue = "";
			$this->tipoSueldo->TooltipValue = "";

			// horaExtra
			$this->horaExtra->LinkCustomAttributes = "";
			$this->horaExtra->HrefValue = "";
			$this->horaExtra->TooltipValue = "";

			// empresa
			$this->empresa->LinkCustomAttributes = "";
			$this->empresa->HrefValue = "";
			$this->empresa->TooltipValue = "";

			// userlevelid
			$this->userlevelid->LinkCustomAttributes = "";
			$this->userlevelid->HrefValue = "";
			$this->userlevelid->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// usuario
			$this->usuario->EditAttrs["class"] = "form-control";
			$this->usuario->EditCustomAttributes = "";
			$this->usuario->EditValue = ew_HtmlEncode($this->usuario->CurrentValue);
			$this->usuario->PlaceHolder = ew_RemoveHtml($this->usuario->FldCaption());

			// password
			$this->password->EditAttrs["class"] = "form-control ewPasswordStrength";
			$this->password->EditCustomAttributes = "";
			$this->password->EditValue = ew_HtmlEncode($this->password->CurrentValue);
			$this->password->PlaceHolder = ew_RemoveHtml($this->password->FldCaption());

			// nombres
			$this->nombres->EditAttrs["class"] = "form-control";
			$this->nombres->EditCustomAttributes = "";
			$this->nombres->EditValue = ew_HtmlEncode($this->nombres->CurrentValue);
			$this->nombres->PlaceHolder = ew_RemoveHtml($this->nombres->FldCaption());

			// apellidos
			$this->apellidos->EditAttrs["class"] = "form-control";
			$this->apellidos->EditCustomAttributes = "";
			$this->apellidos->EditValue = ew_HtmlEncode($this->apellidos->CurrentValue);
			$this->apellidos->PlaceHolder = ew_RemoveHtml($this->apellidos->FldCaption());

			// direccion
			$this->direccion->EditAttrs["class"] = "form-control";
			$this->direccion->EditCustomAttributes = "";
			$this->direccion->EditValue = ew_HtmlEncode($this->direccion->CurrentValue);
			$this->direccion->PlaceHolder = ew_RemoveHtml($this->direccion->FldCaption());

			// telefonos
			$this->telefonos->EditAttrs["class"] = "form-control";
			$this->telefonos->EditCustomAttributes = "";
			$this->telefonos->EditValue = ew_HtmlEncode($this->telefonos->CurrentValue);
			$this->telefonos->PlaceHolder = ew_RemoveHtml($this->telefonos->FldCaption());

			// tipoUsuario
			$this->tipoUsuario->EditAttrs["class"] = "form-control";
			$this->tipoUsuario->EditCustomAttributes = "";
			if (trim(strval($this->tipoUsuario->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idUsuarioTipo`" . ew_SearchString("=", $this->tipoUsuario->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `idUsuarioTipo`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `usuario_tipo`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->tipoUsuario, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->tipoUsuario->EditValue = $arwrk;

			// tipoIngreso
			$this->tipoIngreso->EditAttrs["class"] = "form-control";
			$this->tipoIngreso->EditCustomAttributes = "";
			if (trim(strval($this->tipoIngreso->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idIngresoTipo`" . ew_SearchString("=", $this->tipoIngreso->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `idIngresoTipo`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `ingreso_tipo`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->tipoIngreso, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->tipoIngreso->EditValue = $arwrk;

			// grupo
			$this->grupo->EditAttrs["class"] = "form-control";
			$this->grupo->EditCustomAttributes = "";
			$this->grupo->EditValue = ew_HtmlEncode($this->grupo->CurrentValue);
			$this->grupo->PlaceHolder = ew_RemoveHtml($this->grupo->FldCaption());

			// etiquetas
			$this->etiquetas->EditAttrs["class"] = "form-control";
			$this->etiquetas->EditCustomAttributes = "";
			$this->etiquetas->EditValue = ew_HtmlEncode($this->etiquetas->CurrentValue);
			$this->etiquetas->PlaceHolder = ew_RemoveHtml($this->etiquetas->FldCaption());

			// iniciales
			$this->iniciales->EditAttrs["class"] = "form-control";
			$this->iniciales->EditCustomAttributes = "";
			$this->iniciales->EditValue = ew_HtmlEncode($this->iniciales->CurrentValue);
			$this->iniciales->PlaceHolder = ew_RemoveHtml($this->iniciales->FldCaption());

			// sueldo
			$this->sueldo->EditAttrs["class"] = "form-control";
			$this->sueldo->EditCustomAttributes = "";
			$this->sueldo->EditValue = ew_HtmlEncode($this->sueldo->CurrentValue);
			$this->sueldo->PlaceHolder = ew_RemoveHtml($this->sueldo->FldCaption());
			if (strval($this->sueldo->EditValue) <> "" && is_numeric($this->sueldo->EditValue)) $this->sueldo->EditValue = ew_FormatNumber($this->sueldo->EditValue, -2, -1, -2, 0);

			// tipoSueldo
			$this->tipoSueldo->EditAttrs["class"] = "form-control";
			$this->tipoSueldo->EditCustomAttributes = "";
			$this->tipoSueldo->EditValue = $this->tipoSueldo->Options(TRUE);

			// horaExtra
			$this->horaExtra->EditAttrs["class"] = "form-control";
			$this->horaExtra->EditCustomAttributes = "";
			$this->horaExtra->EditValue = ew_HtmlEncode($this->horaExtra->CurrentValue);
			$this->horaExtra->PlaceHolder = ew_RemoveHtml($this->horaExtra->FldCaption());
			if (strval($this->horaExtra->EditValue) <> "" && is_numeric($this->horaExtra->EditValue)) $this->horaExtra->EditValue = ew_FormatNumber($this->horaExtra->EditValue, -2, -1, -2, 0);

			// empresa
			$this->empresa->EditAttrs["class"] = "form-control";
			$this->empresa->EditCustomAttributes = "";
			if (trim(strval($this->empresa->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idEmpresa`" . ew_SearchString("=", $this->empresa->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `idEmpresa`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `empresa`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->empresa, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->empresa->EditValue = $arwrk;

			// userlevelid
			$this->userlevelid->EditAttrs["class"] = "form-control";
			$this->userlevelid->EditCustomAttributes = "";
			if (!$Security->CanAdmin()) { // System admin
				$this->userlevelid->EditValue = $Language->Phrase("PasswordMask");
			} else {
			if (trim(strval($this->userlevelid->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->userlevelid->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `userlevels`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->userlevelid, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->userlevelid->EditValue = $arwrk;
			}

			// Add refer script
			// usuario

			$this->usuario->LinkCustomAttributes = "";
			$this->usuario->HrefValue = "";

			// password
			$this->password->LinkCustomAttributes = "";
			$this->password->HrefValue = "";

			// nombres
			$this->nombres->LinkCustomAttributes = "";
			$this->nombres->HrefValue = "";

			// apellidos
			$this->apellidos->LinkCustomAttributes = "";
			$this->apellidos->HrefValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";

			// telefonos
			$this->telefonos->LinkCustomAttributes = "";
			$this->telefonos->HrefValue = "";

			// tipoUsuario
			$this->tipoUsuario->LinkCustomAttributes = "";
			$this->tipoUsuario->HrefValue = "";

			// tipoIngreso
			$this->tipoIngreso->LinkCustomAttributes = "";
			$this->tipoIngreso->HrefValue = "";

			// grupo
			$this->grupo->LinkCustomAttributes = "";
			$this->grupo->HrefValue = "";

			// etiquetas
			$this->etiquetas->LinkCustomAttributes = "";
			$this->etiquetas->HrefValue = "";

			// iniciales
			$this->iniciales->LinkCustomAttributes = "";
			$this->iniciales->HrefValue = "";

			// sueldo
			$this->sueldo->LinkCustomAttributes = "";
			$this->sueldo->HrefValue = "";

			// tipoSueldo
			$this->tipoSueldo->LinkCustomAttributes = "";
			$this->tipoSueldo->HrefValue = "";

			// horaExtra
			$this->horaExtra->LinkCustomAttributes = "";
			$this->horaExtra->HrefValue = "";

			// empresa
			$this->empresa->LinkCustomAttributes = "";
			$this->empresa->HrefValue = "";

			// userlevelid
			$this->userlevelid->LinkCustomAttributes = "";
			$this->userlevelid->HrefValue = "";
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
		if (!$this->usuario->FldIsDetailKey && !is_null($this->usuario->FormValue) && $this->usuario->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->usuario->FldCaption(), $this->usuario->ReqErrMsg));
		}
		if (!$this->password->FldIsDetailKey && !is_null($this->password->FormValue) && $this->password->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->password->FldCaption(), $this->password->ReqErrMsg));
		}
		if (!$this->nombres->FldIsDetailKey && !is_null($this->nombres->FormValue) && $this->nombres->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nombres->FldCaption(), $this->nombres->ReqErrMsg));
		}
		if (!$this->apellidos->FldIsDetailKey && !is_null($this->apellidos->FormValue) && $this->apellidos->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->apellidos->FldCaption(), $this->apellidos->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->sueldo->FormValue)) {
			ew_AddMessage($gsFormError, $this->sueldo->FldErrMsg());
		}
		if (!ew_CheckNumber($this->horaExtra->FormValue)) {
			ew_AddMessage($gsFormError, $this->horaExtra->FldErrMsg());
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

		// Check if valid User ID
		$bValidUser = FALSE;
		if ($Security->CurrentUserID() <> "" && !ew_Empty($this->idUsuario->CurrentValue) && !$Security->IsAdmin()) { // Non system admin
			$bValidUser = $Security->IsValidUserID($this->idUsuario->CurrentValue);
			if (!$bValidUser) {
				$sUserIdMsg = str_replace("%c", CurrentUserID(), $Language->Phrase("UnAuthorizedUserID"));
				$sUserIdMsg = str_replace("%u", $this->idUsuario->CurrentValue, $sUserIdMsg);
				$this->setFailureMessage($sUserIdMsg);
				return FALSE;
			}
		}
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// usuario
		$this->usuario->SetDbValueDef($rsnew, $this->usuario->CurrentValue, NULL, FALSE);

		// password
		$this->password->SetDbValueDef($rsnew, $this->password->CurrentValue, NULL, FALSE);

		// nombres
		$this->nombres->SetDbValueDef($rsnew, $this->nombres->CurrentValue, NULL, FALSE);

		// apellidos
		$this->apellidos->SetDbValueDef($rsnew, $this->apellidos->CurrentValue, NULL, FALSE);

		// direccion
		$this->direccion->SetDbValueDef($rsnew, $this->direccion->CurrentValue, NULL, FALSE);

		// telefonos
		$this->telefonos->SetDbValueDef($rsnew, $this->telefonos->CurrentValue, NULL, FALSE);

		// tipoUsuario
		$this->tipoUsuario->SetDbValueDef($rsnew, $this->tipoUsuario->CurrentValue, NULL, FALSE);

		// tipoIngreso
		$this->tipoIngreso->SetDbValueDef($rsnew, $this->tipoIngreso->CurrentValue, NULL, FALSE);

		// grupo
		$this->grupo->SetDbValueDef($rsnew, $this->grupo->CurrentValue, NULL, FALSE);

		// etiquetas
		$this->etiquetas->SetDbValueDef($rsnew, $this->etiquetas->CurrentValue, NULL, FALSE);

		// iniciales
		$this->iniciales->SetDbValueDef($rsnew, $this->iniciales->CurrentValue, NULL, FALSE);

		// sueldo
		$this->sueldo->SetDbValueDef($rsnew, $this->sueldo->CurrentValue, NULL, strval($this->sueldo->CurrentValue) == "");

		// tipoSueldo
		$this->tipoSueldo->SetDbValueDef($rsnew, $this->tipoSueldo->CurrentValue, NULL, strval($this->tipoSueldo->CurrentValue) == "");

		// horaExtra
		$this->horaExtra->SetDbValueDef($rsnew, $this->horaExtra->CurrentValue, NULL, FALSE);

		// empresa
		$this->empresa->SetDbValueDef($rsnew, $this->empresa->CurrentValue, NULL, FALSE);

		// userlevelid
		if ($Security->CanAdmin()) { // System admin
		$this->userlevelid->SetDbValueDef($rsnew, $this->userlevelid->CurrentValue, NULL, FALSE);
		}

		// idUsuario
		// Call Row Inserting event

		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->idUsuario->setDbValue($conn->Insert_ID());
				$rsnew['idUsuario'] = $this->idUsuario->DbValue;
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
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
			$this->WriteAuditTrailOnAdd($rsnew);
		}
		return $AddRow;
	}

	// Show link optionally based on User ID
	function ShowOptionLink($id = "") {
		global $Security;
		if ($Security->IsLoggedIn() && !$Security->IsAdmin() && !$this->UserIDAllow($id))
			return $Security->IsValidUserID($this->idUsuario->CurrentValue);
		return TRUE;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("usuariolist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'usuario';
		$usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnAdd) return;
		$table = 'usuario';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['idUsuario'];

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
				if ($fldname == 'password')
					$newvalue = $Language->Phrase("PasswordMask");
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
if (!isset($usuario_add)) $usuario_add = new cusuario_add();

// Page init
$usuario_add->Page_Init();

// Page main
$usuario_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$usuario_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fusuarioadd = new ew_Form("fusuarioadd", "add");

// Validate form
fusuarioadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_usuario");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $usuario->usuario->FldCaption(), $usuario->usuario->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_password");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $usuario->password->FldCaption(), $usuario->password->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_password");
			if (elm && $(elm).hasClass("ewPasswordStrength") && !$(elm).data("validated"))
				return this.OnError(elm, ewLanguage.Phrase("PasswordTooSimple"));
			elm = this.GetElements("x" + infix + "_nombres");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $usuario->nombres->FldCaption(), $usuario->nombres->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_apellidos");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $usuario->apellidos->FldCaption(), $usuario->apellidos->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_sueldo");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($usuario->sueldo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_horaExtra");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($usuario->horaExtra->FldErrMsg()) ?>");

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
fusuarioadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fusuarioadd.ValidateRequired = true;
<?php } else { ?>
fusuarioadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fusuarioadd.Lists["x_tipoUsuario"] = {"LinkField":"x_idUsuarioTipo","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusuarioadd.Lists["x_tipoIngreso"] = {"LinkField":"x_idIngresoTipo","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusuarioadd.Lists["x_tipoSueldo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusuarioadd.Lists["x_tipoSueldo"].Options = <?php echo json_encode($usuario->tipoSueldo->Options()) ?>;
fusuarioadd.Lists["x_empresa"] = {"LinkField":"x_idEmpresa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusuarioadd.Lists["x_userlevelid"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

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
<?php $usuario_add->ShowPageHeader(); ?>
<?php
$usuario_add->ShowMessage();
?>
<form name="fusuarioadd" id="fusuarioadd" class="<?php echo $usuario_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($usuario_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $usuario_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="usuario">
<input type="hidden" name="a_add" id="a_add" value="A">
<!-- Fields to prevent google autofill -->
<input class="hidden" type="text" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<input class="hidden" type="password" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<div class="ewDesktop">
<div>
<table id="tbl_usuarioadd" class="table table-bordered table-striped ewDesktopTable">
<?php if ($usuario->usuario->Visible) { // usuario ?>
	<tr id="r_usuario">
		<td><span id="elh_usuario_usuario"><?php echo $usuario->usuario->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $usuario->usuario->CellAttributes() ?>>
<span id="el_usuario_usuario">
<input type="text" data-table="usuario" data-field="x_usuario" name="x_usuario" id="x_usuario" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($usuario->usuario->getPlaceHolder()) ?>" value="<?php echo $usuario->usuario->EditValue ?>"<?php echo $usuario->usuario->EditAttributes() ?>>
</span>
<?php echo $usuario->usuario->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($usuario->password->Visible) { // password ?>
	<tr id="r_password">
		<td><span id="elh_usuario_password"><?php echo $usuario->password->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $usuario->password->CellAttributes() ?>>
<span id="el_usuario_password">
<div class="input-group" id="ig_password">
<input type="password" data-password-strength="pst_password" data-password-generated="pgt_password" data-table="usuario" data-field="x_password" name="x_password" id="x_password" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($usuario->password->getPlaceHolder()) ?>"<?php echo $usuario->password->EditAttributes() ?>>
<span class="input-group-btn">
	<button type="button" class="btn btn-default ewPasswordGenerator" title="<?php echo ew_HtmlTitle($Language->Phrase("GeneratePassword")) ?>" data-password-field="x_password" data-password-confirm="c_password" data-password-strength="pst_password" data-password-generated="pgt_password"><?php echo $Language->Phrase("GeneratePassword") ?></button>
</span>
</div>
<span class="help-block" id="pgt_password" style="display: none;"></span>
<div class="progress ewPasswordStrengthBar" id="pst_password" style="display: none;">
	<div class="progress-bar" role="progressbar"></div>
</div>
</span>
<?php echo $usuario->password->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($usuario->nombres->Visible) { // nombres ?>
	<tr id="r_nombres">
		<td><span id="elh_usuario_nombres"><?php echo $usuario->nombres->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $usuario->nombres->CellAttributes() ?>>
<span id="el_usuario_nombres">
<input type="text" data-table="usuario" data-field="x_nombres" name="x_nombres" id="x_nombres" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($usuario->nombres->getPlaceHolder()) ?>" value="<?php echo $usuario->nombres->EditValue ?>"<?php echo $usuario->nombres->EditAttributes() ?>>
</span>
<?php echo $usuario->nombres->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($usuario->apellidos->Visible) { // apellidos ?>
	<tr id="r_apellidos">
		<td><span id="elh_usuario_apellidos"><?php echo $usuario->apellidos->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $usuario->apellidos->CellAttributes() ?>>
<span id="el_usuario_apellidos">
<input type="text" data-table="usuario" data-field="x_apellidos" name="x_apellidos" id="x_apellidos" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($usuario->apellidos->getPlaceHolder()) ?>" value="<?php echo $usuario->apellidos->EditValue ?>"<?php echo $usuario->apellidos->EditAttributes() ?>>
</span>
<?php echo $usuario->apellidos->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($usuario->direccion->Visible) { // direccion ?>
	<tr id="r_direccion">
		<td><span id="elh_usuario_direccion"><?php echo $usuario->direccion->FldCaption() ?></span></td>
		<td<?php echo $usuario->direccion->CellAttributes() ?>>
<span id="el_usuario_direccion">
<input type="text" data-table="usuario" data-field="x_direccion" name="x_direccion" id="x_direccion" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($usuario->direccion->getPlaceHolder()) ?>" value="<?php echo $usuario->direccion->EditValue ?>"<?php echo $usuario->direccion->EditAttributes() ?>>
</span>
<?php echo $usuario->direccion->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($usuario->telefonos->Visible) { // telefonos ?>
	<tr id="r_telefonos">
		<td><span id="elh_usuario_telefonos"><?php echo $usuario->telefonos->FldCaption() ?></span></td>
		<td<?php echo $usuario->telefonos->CellAttributes() ?>>
<span id="el_usuario_telefonos">
<input type="text" data-table="usuario" data-field="x_telefonos" name="x_telefonos" id="x_telefonos" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($usuario->telefonos->getPlaceHolder()) ?>" value="<?php echo $usuario->telefonos->EditValue ?>"<?php echo $usuario->telefonos->EditAttributes() ?>>
</span>
<?php echo $usuario->telefonos->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($usuario->tipoUsuario->Visible) { // tipoUsuario ?>
	<tr id="r_tipoUsuario">
		<td><span id="elh_usuario_tipoUsuario"><?php echo $usuario->tipoUsuario->FldCaption() ?></span></td>
		<td<?php echo $usuario->tipoUsuario->CellAttributes() ?>>
<span id="el_usuario_tipoUsuario">
<select data-table="usuario" data-field="x_tipoUsuario" data-value-separator="<?php echo ew_HtmlEncode(is_array($usuario->tipoUsuario->DisplayValueSeparator) ? json_encode($usuario->tipoUsuario->DisplayValueSeparator) : $usuario->tipoUsuario->DisplayValueSeparator) ?>" id="x_tipoUsuario" name="x_tipoUsuario"<?php echo $usuario->tipoUsuario->EditAttributes() ?>>
<?php
if (is_array($usuario->tipoUsuario->EditValue)) {
	$arwrk = $usuario->tipoUsuario->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($usuario->tipoUsuario->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $usuario->tipoUsuario->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($usuario->tipoUsuario->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($usuario->tipoUsuario->CurrentValue) ?>" selected><?php echo $usuario->tipoUsuario->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `idUsuarioTipo`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `usuario_tipo`";
$sWhereWrk = "";
$usuario->tipoUsuario->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$usuario->tipoUsuario->LookupFilters += array("f0" => "`idUsuarioTipo` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$usuario->Lookup_Selecting($usuario->tipoUsuario, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $usuario->tipoUsuario->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_tipoUsuario" id="s_x_tipoUsuario" value="<?php echo $usuario->tipoUsuario->LookupFilterQuery() ?>">
</span>
<?php echo $usuario->tipoUsuario->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($usuario->tipoIngreso->Visible) { // tipoIngreso ?>
	<tr id="r_tipoIngreso">
		<td><span id="elh_usuario_tipoIngreso"><?php echo $usuario->tipoIngreso->FldCaption() ?></span></td>
		<td<?php echo $usuario->tipoIngreso->CellAttributes() ?>>
<span id="el_usuario_tipoIngreso">
<select data-table="usuario" data-field="x_tipoIngreso" data-value-separator="<?php echo ew_HtmlEncode(is_array($usuario->tipoIngreso->DisplayValueSeparator) ? json_encode($usuario->tipoIngreso->DisplayValueSeparator) : $usuario->tipoIngreso->DisplayValueSeparator) ?>" id="x_tipoIngreso" name="x_tipoIngreso"<?php echo $usuario->tipoIngreso->EditAttributes() ?>>
<?php
if (is_array($usuario->tipoIngreso->EditValue)) {
	$arwrk = $usuario->tipoIngreso->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($usuario->tipoIngreso->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $usuario->tipoIngreso->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($usuario->tipoIngreso->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($usuario->tipoIngreso->CurrentValue) ?>" selected><?php echo $usuario->tipoIngreso->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `idIngresoTipo`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `ingreso_tipo`";
$sWhereWrk = "";
$usuario->tipoIngreso->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$usuario->tipoIngreso->LookupFilters += array("f0" => "`idIngresoTipo` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$usuario->Lookup_Selecting($usuario->tipoIngreso, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $usuario->tipoIngreso->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_tipoIngreso" id="s_x_tipoIngreso" value="<?php echo $usuario->tipoIngreso->LookupFilterQuery() ?>">
</span>
<?php echo $usuario->tipoIngreso->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($usuario->grupo->Visible) { // grupo ?>
	<tr id="r_grupo">
		<td><span id="elh_usuario_grupo"><?php echo $usuario->grupo->FldCaption() ?></span></td>
		<td<?php echo $usuario->grupo->CellAttributes() ?>>
<span id="el_usuario_grupo">
<input type="text" data-table="usuario" data-field="x_grupo" name="x_grupo" id="x_grupo" size="30" maxlength="128" placeholder="<?php echo ew_HtmlEncode($usuario->grupo->getPlaceHolder()) ?>" value="<?php echo $usuario->grupo->EditValue ?>"<?php echo $usuario->grupo->EditAttributes() ?>>
</span>
<?php echo $usuario->grupo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($usuario->etiquetas->Visible) { // etiquetas ?>
	<tr id="r_etiquetas">
		<td><span id="elh_usuario_etiquetas"><?php echo $usuario->etiquetas->FldCaption() ?></span></td>
		<td<?php echo $usuario->etiquetas->CellAttributes() ?>>
<span id="el_usuario_etiquetas">
<input type="text" data-table="usuario" data-field="x_etiquetas" name="x_etiquetas" id="x_etiquetas" size="30" maxlength="128" placeholder="<?php echo ew_HtmlEncode($usuario->etiquetas->getPlaceHolder()) ?>" value="<?php echo $usuario->etiquetas->EditValue ?>"<?php echo $usuario->etiquetas->EditAttributes() ?>>
</span>
<?php echo $usuario->etiquetas->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($usuario->iniciales->Visible) { // iniciales ?>
	<tr id="r_iniciales">
		<td><span id="elh_usuario_iniciales"><?php echo $usuario->iniciales->FldCaption() ?></span></td>
		<td<?php echo $usuario->iniciales->CellAttributes() ?>>
<span id="el_usuario_iniciales">
<input type="text" data-table="usuario" data-field="x_iniciales" name="x_iniciales" id="x_iniciales" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($usuario->iniciales->getPlaceHolder()) ?>" value="<?php echo $usuario->iniciales->EditValue ?>"<?php echo $usuario->iniciales->EditAttributes() ?>>
</span>
<?php echo $usuario->iniciales->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($usuario->sueldo->Visible) { // sueldo ?>
	<tr id="r_sueldo">
		<td><span id="elh_usuario_sueldo"><?php echo $usuario->sueldo->FldCaption() ?></span></td>
		<td<?php echo $usuario->sueldo->CellAttributes() ?>>
<span id="el_usuario_sueldo">
<input type="text" data-table="usuario" data-field="x_sueldo" name="x_sueldo" id="x_sueldo" size="30" placeholder="<?php echo ew_HtmlEncode($usuario->sueldo->getPlaceHolder()) ?>" value="<?php echo $usuario->sueldo->EditValue ?>"<?php echo $usuario->sueldo->EditAttributes() ?>>
</span>
<?php echo $usuario->sueldo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($usuario->tipoSueldo->Visible) { // tipoSueldo ?>
	<tr id="r_tipoSueldo">
		<td><span id="elh_usuario_tipoSueldo"><?php echo $usuario->tipoSueldo->FldCaption() ?></span></td>
		<td<?php echo $usuario->tipoSueldo->CellAttributes() ?>>
<span id="el_usuario_tipoSueldo">
<select data-table="usuario" data-field="x_tipoSueldo" data-value-separator="<?php echo ew_HtmlEncode(is_array($usuario->tipoSueldo->DisplayValueSeparator) ? json_encode($usuario->tipoSueldo->DisplayValueSeparator) : $usuario->tipoSueldo->DisplayValueSeparator) ?>" id="x_tipoSueldo" name="x_tipoSueldo"<?php echo $usuario->tipoSueldo->EditAttributes() ?>>
<?php
if (is_array($usuario->tipoSueldo->EditValue)) {
	$arwrk = $usuario->tipoSueldo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($usuario->tipoSueldo->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $usuario->tipoSueldo->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($usuario->tipoSueldo->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($usuario->tipoSueldo->CurrentValue) ?>" selected><?php echo $usuario->tipoSueldo->CurrentValue ?></option>
<?php
    }
}
?>
</select>
</span>
<?php echo $usuario->tipoSueldo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($usuario->horaExtra->Visible) { // horaExtra ?>
	<tr id="r_horaExtra">
		<td><span id="elh_usuario_horaExtra"><?php echo $usuario->horaExtra->FldCaption() ?></span></td>
		<td<?php echo $usuario->horaExtra->CellAttributes() ?>>
<span id="el_usuario_horaExtra">
<input type="text" data-table="usuario" data-field="x_horaExtra" name="x_horaExtra" id="x_horaExtra" size="30" placeholder="<?php echo ew_HtmlEncode($usuario->horaExtra->getPlaceHolder()) ?>" value="<?php echo $usuario->horaExtra->EditValue ?>"<?php echo $usuario->horaExtra->EditAttributes() ?>>
</span>
<?php echo $usuario->horaExtra->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($usuario->empresa->Visible) { // empresa ?>
	<tr id="r_empresa">
		<td><span id="elh_usuario_empresa"><?php echo $usuario->empresa->FldCaption() ?></span></td>
		<td<?php echo $usuario->empresa->CellAttributes() ?>>
<span id="el_usuario_empresa">
<select data-table="usuario" data-field="x_empresa" data-value-separator="<?php echo ew_HtmlEncode(is_array($usuario->empresa->DisplayValueSeparator) ? json_encode($usuario->empresa->DisplayValueSeparator) : $usuario->empresa->DisplayValueSeparator) ?>" id="x_empresa" name="x_empresa"<?php echo $usuario->empresa->EditAttributes() ?>>
<?php
if (is_array($usuario->empresa->EditValue)) {
	$arwrk = $usuario->empresa->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($usuario->empresa->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $usuario->empresa->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($usuario->empresa->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($usuario->empresa->CurrentValue) ?>" selected><?php echo $usuario->empresa->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `idEmpresa`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empresa`";
$sWhereWrk = "";
$usuario->empresa->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$usuario->empresa->LookupFilters += array("f0" => "`idEmpresa` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$usuario->Lookup_Selecting($usuario->empresa, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $usuario->empresa->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_empresa" id="s_x_empresa" value="<?php echo $usuario->empresa->LookupFilterQuery() ?>">
</span>
<?php echo $usuario->empresa->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($usuario->userlevelid->Visible) { // userlevelid ?>
	<tr id="r_userlevelid">
		<td><span id="elh_usuario_userlevelid"><?php echo $usuario->userlevelid->FldCaption() ?></span></td>
		<td<?php echo $usuario->userlevelid->CellAttributes() ?>>
<?php if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin ?>
<span id="el_usuario_userlevelid">
<p class="form-control-static"><?php echo $usuario->userlevelid->EditValue ?></p>
</span>
<?php } else { ?>
<span id="el_usuario_userlevelid">
<select data-table="usuario" data-field="x_userlevelid" data-value-separator="<?php echo ew_HtmlEncode(is_array($usuario->userlevelid->DisplayValueSeparator) ? json_encode($usuario->userlevelid->DisplayValueSeparator) : $usuario->userlevelid->DisplayValueSeparator) ?>" id="x_userlevelid" name="x_userlevelid"<?php echo $usuario->userlevelid->EditAttributes() ?>>
<?php
if (is_array($usuario->userlevelid->EditValue)) {
	$arwrk = $usuario->userlevelid->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($usuario->userlevelid->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $usuario->userlevelid->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($usuario->userlevelid->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($usuario->userlevelid->CurrentValue) ?>" selected><?php echo $usuario->userlevelid->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
$sWhereWrk = "";
$usuario->userlevelid->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$usuario->userlevelid->LookupFilters += array("f0" => "`userlevelid` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$usuario->Lookup_Selecting($usuario->userlevelid, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $usuario->userlevelid->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_userlevelid" id="s_x_userlevelid" value="<?php echo $usuario->userlevelid->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php echo $usuario->userlevelid->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
<div class="ewDesktopButton">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $usuario_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</div>
</form>
<script type="text/javascript">
fusuarioadd.Init();
</script>
<?php
$usuario_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$usuario_add->Page_Terminate();
?>
