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

$usuario_view = NULL; // Initialize page object first

class cusuario_view extends cusuario {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}";

	// Table name
	var $TableName = 'usuario';

	// Page object name
	var $PageObjName = 'usuario_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;
    var $AuditTrailOnAdd = FALSE;
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
		$KeyUrl = "";
		if (@$_GET["idUsuario"] <> "") {
			$this->RecKey["idUsuario"] = $_GET["idUsuario"];
			$KeyUrl .= "&amp;idUsuario=" . urlencode($this->RecKey["idUsuario"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

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

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (!$Security->CanView()) {
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

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header
		if (@$_GET["idUsuario"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["idUsuario"]);
		}

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Setup export options
		$this->SetupExportOptions();
		$this->idUsuario->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["idUsuario"] <> "") {
				$this->idUsuario->setQueryStringValue($_GET["idUsuario"]);
				$this->RecKey["idUsuario"] = $this->idUsuario->QueryStringValue;
			} elseif (@$_POST["idUsuario"] <> "") {
				$this->idUsuario->setFormValue($_POST["idUsuario"]);
				$this->RecKey["idUsuario"] = $this->idUsuario->FormValue;
			} else {
				$sReturnUrl = "usuariolist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "usuariolist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "usuariolist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit()&& $this->ShowOptionLink('edit'));

		// Copy
		$item = &$option->Add("copy");
		$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->CanAdd() && $this->ShowOptionLink('add'));

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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
		if ($this->AuditTrailOnView) $this->WriteAuditTrailOnView($row);
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

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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

			// idUsuario
			$this->idUsuario->LinkCustomAttributes = "";
			$this->idUsuario->HrefValue = "";
			$this->idUsuario->TooltipValue = "";

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

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
			$this->estado->TooltipValue = "";

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = FALSE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = FALSE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = TRUE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_usuario\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_usuario',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fusuarioview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = TRUE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = FALSE;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;
		$this->SetUpStartRec(); // Set up start record position

		// Set the last record to display
		if ($this->DisplayRecs <= 0) {
			$this->StopRec = $this->TotalRecs;
		} else {
			$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
		}
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "v");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "view");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Call Page Exported server event
		$this->Page_Exported();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED && $this->Export <> "pdf")
			echo ew_DebugMsg();

		// Output data
		$Doc->Export();
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
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'usuario';
		$usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
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

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

	    //$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($usuario_view)) $usuario_view = new cusuario_view();

// Page init
$usuario_view->Page_Init();

// Page main
$usuario_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$usuario_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($usuario->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fusuarioview = new ew_Form("fusuarioview", "view");

// Form_CustomValidate event
fusuarioview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fusuarioview.ValidateRequired = true;
<?php } else { ?>
fusuarioview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fusuarioview.Lists["x_tipoUsuario"] = {"LinkField":"x_idUsuarioTipo","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusuarioview.Lists["x_estado"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusuarioview.Lists["x_estado"].Options = <?php echo json_encode($usuario->estado->Options()) ?>;
fusuarioview.Lists["x_tipoIngreso"] = {"LinkField":"x_idIngresoTipo","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusuarioview.Lists["x_tipoSueldo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusuarioview.Lists["x_tipoSueldo"].Options = <?php echo json_encode($usuario->tipoSueldo->Options()) ?>;
fusuarioview.Lists["x_empresa"] = {"LinkField":"x_idEmpresa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusuarioview.Lists["x_userlevelid"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($usuario->Export == "") { ?>
<div class="ewToolbar">
<?php if ($usuario->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php $usuario_view->ExportOptions->Render("body") ?>
<?php
	foreach ($usuario_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if ($usuario->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $usuario_view->ShowPageHeader(); ?>
<?php
$usuario_view->ShowMessage();
?>
<form name="fusuarioview" id="fusuarioview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($usuario_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $usuario_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="usuario">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($usuario->idUsuario->Visible) { // idUsuario ?>
	<tr id="r_idUsuario">
		<td><span id="elh_usuario_idUsuario"><?php echo $usuario->idUsuario->FldCaption() ?></span></td>
		<td data-name="idUsuario"<?php echo $usuario->idUsuario->CellAttributes() ?>>
<span id="el_usuario_idUsuario">
<span<?php echo $usuario->idUsuario->ViewAttributes() ?>>
<?php echo $usuario->idUsuario->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($usuario->usuario->Visible) { // usuario ?>
	<tr id="r_usuario">
		<td><span id="elh_usuario_usuario"><?php echo $usuario->usuario->FldCaption() ?></span></td>
		<td data-name="usuario"<?php echo $usuario->usuario->CellAttributes() ?>>
<span id="el_usuario_usuario">
<span<?php echo $usuario->usuario->ViewAttributes() ?>>
<?php echo $usuario->usuario->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($usuario->password->Visible) { // password ?>
	<tr id="r_password">
		<td><span id="elh_usuario_password"><?php echo $usuario->password->FldCaption() ?></span></td>
		<td data-name="password"<?php echo $usuario->password->CellAttributes() ?>>
<span id="el_usuario_password">
<span<?php echo $usuario->password->ViewAttributes() ?>>
<?php echo $usuario->password->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($usuario->nombres->Visible) { // nombres ?>
	<tr id="r_nombres">
		<td><span id="elh_usuario_nombres"><?php echo $usuario->nombres->FldCaption() ?></span></td>
		<td data-name="nombres"<?php echo $usuario->nombres->CellAttributes() ?>>
<span id="el_usuario_nombres">
<span<?php echo $usuario->nombres->ViewAttributes() ?>>
<?php echo $usuario->nombres->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($usuario->apellidos->Visible) { // apellidos ?>
	<tr id="r_apellidos">
		<td><span id="elh_usuario_apellidos"><?php echo $usuario->apellidos->FldCaption() ?></span></td>
		<td data-name="apellidos"<?php echo $usuario->apellidos->CellAttributes() ?>>
<span id="el_usuario_apellidos">
<span<?php echo $usuario->apellidos->ViewAttributes() ?>>
<?php echo $usuario->apellidos->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($usuario->direccion->Visible) { // direccion ?>
	<tr id="r_direccion">
		<td><span id="elh_usuario_direccion"><?php echo $usuario->direccion->FldCaption() ?></span></td>
		<td data-name="direccion"<?php echo $usuario->direccion->CellAttributes() ?>>
<span id="el_usuario_direccion">
<span<?php echo $usuario->direccion->ViewAttributes() ?>>
<?php echo $usuario->direccion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($usuario->telefonos->Visible) { // telefonos ?>
	<tr id="r_telefonos">
		<td><span id="elh_usuario_telefonos"><?php echo $usuario->telefonos->FldCaption() ?></span></td>
		<td data-name="telefonos"<?php echo $usuario->telefonos->CellAttributes() ?>>
<span id="el_usuario_telefonos">
<span<?php echo $usuario->telefonos->ViewAttributes() ?>>
<?php echo $usuario->telefonos->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($usuario->tipoUsuario->Visible) { // tipoUsuario ?>
	<tr id="r_tipoUsuario">
		<td><span id="elh_usuario_tipoUsuario"><?php echo $usuario->tipoUsuario->FldCaption() ?></span></td>
		<td data-name="tipoUsuario"<?php echo $usuario->tipoUsuario->CellAttributes() ?>>
<span id="el_usuario_tipoUsuario">
<span<?php echo $usuario->tipoUsuario->ViewAttributes() ?>>
<?php echo $usuario->tipoUsuario->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($usuario->estado->Visible) { // estado ?>
	<tr id="r_estado">
		<td><span id="elh_usuario_estado"><?php echo $usuario->estado->FldCaption() ?></span></td>
		<td data-name="estado"<?php echo $usuario->estado->CellAttributes() ?>>
<span id="el_usuario_estado">
<span<?php echo $usuario->estado->ViewAttributes() ?>>
<?php echo $usuario->estado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($usuario->tipoIngreso->Visible) { // tipoIngreso ?>
	<tr id="r_tipoIngreso">
		<td><span id="elh_usuario_tipoIngreso"><?php echo $usuario->tipoIngreso->FldCaption() ?></span></td>
		<td data-name="tipoIngreso"<?php echo $usuario->tipoIngreso->CellAttributes() ?>>
<span id="el_usuario_tipoIngreso">
<span<?php echo $usuario->tipoIngreso->ViewAttributes() ?>>
<?php echo $usuario->tipoIngreso->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($usuario->grupo->Visible) { // grupo ?>
	<tr id="r_grupo">
		<td><span id="elh_usuario_grupo"><?php echo $usuario->grupo->FldCaption() ?></span></td>
		<td data-name="grupo"<?php echo $usuario->grupo->CellAttributes() ?>>
<span id="el_usuario_grupo">
<span<?php echo $usuario->grupo->ViewAttributes() ?>>
<?php echo $usuario->grupo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($usuario->etiquetas->Visible) { // etiquetas ?>
	<tr id="r_etiquetas">
		<td><span id="elh_usuario_etiquetas"><?php echo $usuario->etiquetas->FldCaption() ?></span></td>
		<td data-name="etiquetas"<?php echo $usuario->etiquetas->CellAttributes() ?>>
<span id="el_usuario_etiquetas">
<span<?php echo $usuario->etiquetas->ViewAttributes() ?>>
<?php echo $usuario->etiquetas->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($usuario->iniciales->Visible) { // iniciales ?>
	<tr id="r_iniciales">
		<td><span id="elh_usuario_iniciales"><?php echo $usuario->iniciales->FldCaption() ?></span></td>
		<td data-name="iniciales"<?php echo $usuario->iniciales->CellAttributes() ?>>
<span id="el_usuario_iniciales">
<span<?php echo $usuario->iniciales->ViewAttributes() ?>>
<?php echo $usuario->iniciales->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($usuario->sueldo->Visible) { // sueldo ?>
	<tr id="r_sueldo">
		<td><span id="elh_usuario_sueldo"><?php echo $usuario->sueldo->FldCaption() ?></span></td>
		<td data-name="sueldo"<?php echo $usuario->sueldo->CellAttributes() ?>>
<span id="el_usuario_sueldo">
<span<?php echo $usuario->sueldo->ViewAttributes() ?>>
<?php echo $usuario->sueldo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($usuario->tipoSueldo->Visible) { // tipoSueldo ?>
	<tr id="r_tipoSueldo">
		<td><span id="elh_usuario_tipoSueldo"><?php echo $usuario->tipoSueldo->FldCaption() ?></span></td>
		<td data-name="tipoSueldo"<?php echo $usuario->tipoSueldo->CellAttributes() ?>>
<span id="el_usuario_tipoSueldo">
<span<?php echo $usuario->tipoSueldo->ViewAttributes() ?>>
<?php echo $usuario->tipoSueldo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($usuario->horaExtra->Visible) { // horaExtra ?>
	<tr id="r_horaExtra">
		<td><span id="elh_usuario_horaExtra"><?php echo $usuario->horaExtra->FldCaption() ?></span></td>
		<td data-name="horaExtra"<?php echo $usuario->horaExtra->CellAttributes() ?>>
<span id="el_usuario_horaExtra">
<span<?php echo $usuario->horaExtra->ViewAttributes() ?>>
<?php echo $usuario->horaExtra->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($usuario->empresa->Visible) { // empresa ?>
	<tr id="r_empresa">
		<td><span id="elh_usuario_empresa"><?php echo $usuario->empresa->FldCaption() ?></span></td>
		<td data-name="empresa"<?php echo $usuario->empresa->CellAttributes() ?>>
<span id="el_usuario_empresa">
<span<?php echo $usuario->empresa->ViewAttributes() ?>>
<?php echo $usuario->empresa->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($usuario->userlevelid->Visible) { // userlevelid ?>
	<tr id="r_userlevelid">
		<td><span id="elh_usuario_userlevelid"><?php echo $usuario->userlevelid->FldCaption() ?></span></td>
		<td data-name="userlevelid"<?php echo $usuario->userlevelid->CellAttributes() ?>>
<span id="el_usuario_userlevelid">
<span<?php echo $usuario->userlevelid->ViewAttributes() ?>>
<?php echo $usuario->userlevelid->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<?php if ($usuario->Export == "") { ?>
<script type="text/javascript">
fusuarioview.Init();
</script>
<?php } ?>
<?php
$usuario_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($usuario->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$usuario_view->Page_Terminate();
?>
