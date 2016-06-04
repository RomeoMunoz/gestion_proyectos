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

$usuario_list = NULL; // Initialize page object first

class cusuario_list extends cusuario {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}";

	// Table name
	var $TableName = 'usuario';

	// Page object name
	var $PageObjName = 'usuario_list';

	// Grid form hidden field names
	var $FormName = 'fusuariolist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "usuarioadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "usuariodelete.php";
		$this->MultiUpdateUrl = "usuarioupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fusuariolistsrch";

		// List actions
		$this->ListActions = new cListActions();
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
			if (strval($Security->CurrentUserID()) == "") {
				$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
				$this->Page_Terminate();
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

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();

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

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Set up records per page
			$this->SetUpDisplayRecs();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values

			// Restore filter list
			$this->RestoreFilterList();
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Set up number of records displayed per page
	function SetUpDisplayRecs() {
		$sWrk = @$_GET[EW_TABLE_REC_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayRecs = intval($sWrk);
			} else {
				if (strtolower($sWrk) == "all") { // Display all records
					$this->DisplayRecs = -1;
				} else {
					$this->DisplayRecs = 20; // Non-numeric, load default
				}
			}
			$this->setRecordsPerPage($this->DisplayRecs); // Save to Session

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->idUsuario->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->idUsuario->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->idUsuario->AdvancedSearch->ToJSON(), ","); // Field idUsuario
		$sFilterList = ew_Concat($sFilterList, $this->usuario->AdvancedSearch->ToJSON(), ","); // Field usuario
		$sFilterList = ew_Concat($sFilterList, $this->password->AdvancedSearch->ToJSON(), ","); // Field password
		$sFilterList = ew_Concat($sFilterList, $this->nombres->AdvancedSearch->ToJSON(), ","); // Field nombres
		$sFilterList = ew_Concat($sFilterList, $this->apellidos->AdvancedSearch->ToJSON(), ","); // Field apellidos
		$sFilterList = ew_Concat($sFilterList, $this->direccion->AdvancedSearch->ToJSON(), ","); // Field direccion
		$sFilterList = ew_Concat($sFilterList, $this->telefonos->AdvancedSearch->ToJSON(), ","); // Field telefonos
		$sFilterList = ew_Concat($sFilterList, $this->tipoUsuario->AdvancedSearch->ToJSON(), ","); // Field tipoUsuario
		$sFilterList = ew_Concat($sFilterList, $this->estado->AdvancedSearch->ToJSON(), ","); // Field estado
		$sFilterList = ew_Concat($sFilterList, $this->tipoIngreso->AdvancedSearch->ToJSON(), ","); // Field tipoIngreso
		$sFilterList = ew_Concat($sFilterList, $this->grupo->AdvancedSearch->ToJSON(), ","); // Field grupo
		$sFilterList = ew_Concat($sFilterList, $this->etiquetas->AdvancedSearch->ToJSON(), ","); // Field etiquetas
		$sFilterList = ew_Concat($sFilterList, $this->iniciales->AdvancedSearch->ToJSON(), ","); // Field iniciales
		$sFilterList = ew_Concat($sFilterList, $this->sueldo->AdvancedSearch->ToJSON(), ","); // Field sueldo
		$sFilterList = ew_Concat($sFilterList, $this->tipoSueldo->AdvancedSearch->ToJSON(), ","); // Field tipoSueldo
		$sFilterList = ew_Concat($sFilterList, $this->horaExtra->AdvancedSearch->ToJSON(), ","); // Field horaExtra
		$sFilterList = ew_Concat($sFilterList, $this->empresa->AdvancedSearch->ToJSON(), ","); // Field empresa
		$sFilterList = ew_Concat($sFilterList, $this->userlevelid->AdvancedSearch->ToJSON(), ","); // Field userlevelid
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}

		// Return filter list in json
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		// Field idUsuario
		$this->idUsuario->AdvancedSearch->SearchValue = @$filter["x_idUsuario"];
		$this->idUsuario->AdvancedSearch->SearchOperator = @$filter["z_idUsuario"];
		$this->idUsuario->AdvancedSearch->SearchCondition = @$filter["v_idUsuario"];
		$this->idUsuario->AdvancedSearch->SearchValue2 = @$filter["y_idUsuario"];
		$this->idUsuario->AdvancedSearch->SearchOperator2 = @$filter["w_idUsuario"];
		$this->idUsuario->AdvancedSearch->Save();

		// Field usuario
		$this->usuario->AdvancedSearch->SearchValue = @$filter["x_usuario"];
		$this->usuario->AdvancedSearch->SearchOperator = @$filter["z_usuario"];
		$this->usuario->AdvancedSearch->SearchCondition = @$filter["v_usuario"];
		$this->usuario->AdvancedSearch->SearchValue2 = @$filter["y_usuario"];
		$this->usuario->AdvancedSearch->SearchOperator2 = @$filter["w_usuario"];
		$this->usuario->AdvancedSearch->Save();

		// Field password
		$this->password->AdvancedSearch->SearchValue = @$filter["x_password"];
		$this->password->AdvancedSearch->SearchOperator = @$filter["z_password"];
		$this->password->AdvancedSearch->SearchCondition = @$filter["v_password"];
		$this->password->AdvancedSearch->SearchValue2 = @$filter["y_password"];
		$this->password->AdvancedSearch->SearchOperator2 = @$filter["w_password"];
		$this->password->AdvancedSearch->Save();

		// Field nombres
		$this->nombres->AdvancedSearch->SearchValue = @$filter["x_nombres"];
		$this->nombres->AdvancedSearch->SearchOperator = @$filter["z_nombres"];
		$this->nombres->AdvancedSearch->SearchCondition = @$filter["v_nombres"];
		$this->nombres->AdvancedSearch->SearchValue2 = @$filter["y_nombres"];
		$this->nombres->AdvancedSearch->SearchOperator2 = @$filter["w_nombres"];
		$this->nombres->AdvancedSearch->Save();

		// Field apellidos
		$this->apellidos->AdvancedSearch->SearchValue = @$filter["x_apellidos"];
		$this->apellidos->AdvancedSearch->SearchOperator = @$filter["z_apellidos"];
		$this->apellidos->AdvancedSearch->SearchCondition = @$filter["v_apellidos"];
		$this->apellidos->AdvancedSearch->SearchValue2 = @$filter["y_apellidos"];
		$this->apellidos->AdvancedSearch->SearchOperator2 = @$filter["w_apellidos"];
		$this->apellidos->AdvancedSearch->Save();

		// Field direccion
		$this->direccion->AdvancedSearch->SearchValue = @$filter["x_direccion"];
		$this->direccion->AdvancedSearch->SearchOperator = @$filter["z_direccion"];
		$this->direccion->AdvancedSearch->SearchCondition = @$filter["v_direccion"];
		$this->direccion->AdvancedSearch->SearchValue2 = @$filter["y_direccion"];
		$this->direccion->AdvancedSearch->SearchOperator2 = @$filter["w_direccion"];
		$this->direccion->AdvancedSearch->Save();

		// Field telefonos
		$this->telefonos->AdvancedSearch->SearchValue = @$filter["x_telefonos"];
		$this->telefonos->AdvancedSearch->SearchOperator = @$filter["z_telefonos"];
		$this->telefonos->AdvancedSearch->SearchCondition = @$filter["v_telefonos"];
		$this->telefonos->AdvancedSearch->SearchValue2 = @$filter["y_telefonos"];
		$this->telefonos->AdvancedSearch->SearchOperator2 = @$filter["w_telefonos"];
		$this->telefonos->AdvancedSearch->Save();

		// Field tipoUsuario
		$this->tipoUsuario->AdvancedSearch->SearchValue = @$filter["x_tipoUsuario"];
		$this->tipoUsuario->AdvancedSearch->SearchOperator = @$filter["z_tipoUsuario"];
		$this->tipoUsuario->AdvancedSearch->SearchCondition = @$filter["v_tipoUsuario"];
		$this->tipoUsuario->AdvancedSearch->SearchValue2 = @$filter["y_tipoUsuario"];
		$this->tipoUsuario->AdvancedSearch->SearchOperator2 = @$filter["w_tipoUsuario"];
		$this->tipoUsuario->AdvancedSearch->Save();

		// Field estado
		$this->estado->AdvancedSearch->SearchValue = @$filter["x_estado"];
		$this->estado->AdvancedSearch->SearchOperator = @$filter["z_estado"];
		$this->estado->AdvancedSearch->SearchCondition = @$filter["v_estado"];
		$this->estado->AdvancedSearch->SearchValue2 = @$filter["y_estado"];
		$this->estado->AdvancedSearch->SearchOperator2 = @$filter["w_estado"];
		$this->estado->AdvancedSearch->Save();

		// Field tipoIngreso
		$this->tipoIngreso->AdvancedSearch->SearchValue = @$filter["x_tipoIngreso"];
		$this->tipoIngreso->AdvancedSearch->SearchOperator = @$filter["z_tipoIngreso"];
		$this->tipoIngreso->AdvancedSearch->SearchCondition = @$filter["v_tipoIngreso"];
		$this->tipoIngreso->AdvancedSearch->SearchValue2 = @$filter["y_tipoIngreso"];
		$this->tipoIngreso->AdvancedSearch->SearchOperator2 = @$filter["w_tipoIngreso"];
		$this->tipoIngreso->AdvancedSearch->Save();

		// Field grupo
		$this->grupo->AdvancedSearch->SearchValue = @$filter["x_grupo"];
		$this->grupo->AdvancedSearch->SearchOperator = @$filter["z_grupo"];
		$this->grupo->AdvancedSearch->SearchCondition = @$filter["v_grupo"];
		$this->grupo->AdvancedSearch->SearchValue2 = @$filter["y_grupo"];
		$this->grupo->AdvancedSearch->SearchOperator2 = @$filter["w_grupo"];
		$this->grupo->AdvancedSearch->Save();

		// Field etiquetas
		$this->etiquetas->AdvancedSearch->SearchValue = @$filter["x_etiquetas"];
		$this->etiquetas->AdvancedSearch->SearchOperator = @$filter["z_etiquetas"];
		$this->etiquetas->AdvancedSearch->SearchCondition = @$filter["v_etiquetas"];
		$this->etiquetas->AdvancedSearch->SearchValue2 = @$filter["y_etiquetas"];
		$this->etiquetas->AdvancedSearch->SearchOperator2 = @$filter["w_etiquetas"];
		$this->etiquetas->AdvancedSearch->Save();

		// Field iniciales
		$this->iniciales->AdvancedSearch->SearchValue = @$filter["x_iniciales"];
		$this->iniciales->AdvancedSearch->SearchOperator = @$filter["z_iniciales"];
		$this->iniciales->AdvancedSearch->SearchCondition = @$filter["v_iniciales"];
		$this->iniciales->AdvancedSearch->SearchValue2 = @$filter["y_iniciales"];
		$this->iniciales->AdvancedSearch->SearchOperator2 = @$filter["w_iniciales"];
		$this->iniciales->AdvancedSearch->Save();

		// Field sueldo
		$this->sueldo->AdvancedSearch->SearchValue = @$filter["x_sueldo"];
		$this->sueldo->AdvancedSearch->SearchOperator = @$filter["z_sueldo"];
		$this->sueldo->AdvancedSearch->SearchCondition = @$filter["v_sueldo"];
		$this->sueldo->AdvancedSearch->SearchValue2 = @$filter["y_sueldo"];
		$this->sueldo->AdvancedSearch->SearchOperator2 = @$filter["w_sueldo"];
		$this->sueldo->AdvancedSearch->Save();

		// Field tipoSueldo
		$this->tipoSueldo->AdvancedSearch->SearchValue = @$filter["x_tipoSueldo"];
		$this->tipoSueldo->AdvancedSearch->SearchOperator = @$filter["z_tipoSueldo"];
		$this->tipoSueldo->AdvancedSearch->SearchCondition = @$filter["v_tipoSueldo"];
		$this->tipoSueldo->AdvancedSearch->SearchValue2 = @$filter["y_tipoSueldo"];
		$this->tipoSueldo->AdvancedSearch->SearchOperator2 = @$filter["w_tipoSueldo"];
		$this->tipoSueldo->AdvancedSearch->Save();

		// Field horaExtra
		$this->horaExtra->AdvancedSearch->SearchValue = @$filter["x_horaExtra"];
		$this->horaExtra->AdvancedSearch->SearchOperator = @$filter["z_horaExtra"];
		$this->horaExtra->AdvancedSearch->SearchCondition = @$filter["v_horaExtra"];
		$this->horaExtra->AdvancedSearch->SearchValue2 = @$filter["y_horaExtra"];
		$this->horaExtra->AdvancedSearch->SearchOperator2 = @$filter["w_horaExtra"];
		$this->horaExtra->AdvancedSearch->Save();

		// Field empresa
		$this->empresa->AdvancedSearch->SearchValue = @$filter["x_empresa"];
		$this->empresa->AdvancedSearch->SearchOperator = @$filter["z_empresa"];
		$this->empresa->AdvancedSearch->SearchCondition = @$filter["v_empresa"];
		$this->empresa->AdvancedSearch->SearchValue2 = @$filter["y_empresa"];
		$this->empresa->AdvancedSearch->SearchOperator2 = @$filter["w_empresa"];
		$this->empresa->AdvancedSearch->Save();

		// Field userlevelid
		$this->userlevelid->AdvancedSearch->SearchValue = @$filter["x_userlevelid"];
		$this->userlevelid->AdvancedSearch->SearchOperator = @$filter["z_userlevelid"];
		$this->userlevelid->AdvancedSearch->SearchCondition = @$filter["v_userlevelid"];
		$this->userlevelid->AdvancedSearch->SearchValue2 = @$filter["y_userlevelid"];
		$this->userlevelid->AdvancedSearch->SearchOperator2 = @$filter["w_userlevelid"];
		$this->userlevelid->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->idUsuario, $Default, FALSE); // idUsuario
		$this->BuildSearchSql($sWhere, $this->usuario, $Default, FALSE); // usuario
		$this->BuildSearchSql($sWhere, $this->password, $Default, FALSE); // password
		$this->BuildSearchSql($sWhere, $this->nombres, $Default, FALSE); // nombres
		$this->BuildSearchSql($sWhere, $this->apellidos, $Default, FALSE); // apellidos
		$this->BuildSearchSql($sWhere, $this->direccion, $Default, FALSE); // direccion
		$this->BuildSearchSql($sWhere, $this->telefonos, $Default, FALSE); // telefonos
		$this->BuildSearchSql($sWhere, $this->tipoUsuario, $Default, FALSE); // tipoUsuario
		$this->BuildSearchSql($sWhere, $this->estado, $Default, FALSE); // estado
		$this->BuildSearchSql($sWhere, $this->tipoIngreso, $Default, FALSE); // tipoIngreso
		$this->BuildSearchSql($sWhere, $this->grupo, $Default, FALSE); // grupo
		$this->BuildSearchSql($sWhere, $this->etiquetas, $Default, FALSE); // etiquetas
		$this->BuildSearchSql($sWhere, $this->iniciales, $Default, FALSE); // iniciales
		$this->BuildSearchSql($sWhere, $this->sueldo, $Default, FALSE); // sueldo
		$this->BuildSearchSql($sWhere, $this->tipoSueldo, $Default, FALSE); // tipoSueldo
		$this->BuildSearchSql($sWhere, $this->horaExtra, $Default, FALSE); // horaExtra
		$this->BuildSearchSql($sWhere, $this->empresa, $Default, FALSE); // empresa
		$this->BuildSearchSql($sWhere, $this->userlevelid, $Default, FALSE); // userlevelid

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->idUsuario->AdvancedSearch->Save(); // idUsuario
			$this->usuario->AdvancedSearch->Save(); // usuario
			$this->password->AdvancedSearch->Save(); // password
			$this->nombres->AdvancedSearch->Save(); // nombres
			$this->apellidos->AdvancedSearch->Save(); // apellidos
			$this->direccion->AdvancedSearch->Save(); // direccion
			$this->telefonos->AdvancedSearch->Save(); // telefonos
			$this->tipoUsuario->AdvancedSearch->Save(); // tipoUsuario
			$this->estado->AdvancedSearch->Save(); // estado
			$this->tipoIngreso->AdvancedSearch->Save(); // tipoIngreso
			$this->grupo->AdvancedSearch->Save(); // grupo
			$this->etiquetas->AdvancedSearch->Save(); // etiquetas
			$this->iniciales->AdvancedSearch->Save(); // iniciales
			$this->sueldo->AdvancedSearch->Save(); // sueldo
			$this->tipoSueldo->AdvancedSearch->Save(); // tipoSueldo
			$this->horaExtra->AdvancedSearch->Save(); // horaExtra
			$this->empresa->AdvancedSearch->Save(); // empresa
			$this->userlevelid->AdvancedSearch->Save(); // userlevelid
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1 || $FldOpr <> "LIKE" ||
			($FldOpr2 <> "LIKE" && $FldVal2 <> ""))
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal, $this->DBID) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2, $this->DBID) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2, $this->DBID);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->usuario, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->password, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->nombres, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->apellidos, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->direccion, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->telefonos, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->grupo, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->etiquetas, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->iniciales, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $arKeywords, $type) {
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if (EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace(EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual && $Fld->FldVirtualSearch) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));

				// Search keyword in any fields
				if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
					foreach ($ar as $sKeyword) {
						if ($sKeyword <> "") {
							if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
							$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
						}
					}
				} else {
					$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		if ($this->idUsuario->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->usuario->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->password->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nombres->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->apellidos->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->direccion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->telefonos->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tipoUsuario->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->estado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tipoIngreso->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->grupo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->etiquetas->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->iniciales->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->sueldo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tipoSueldo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->horaExtra->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->empresa->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->userlevelid->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->idUsuario->AdvancedSearch->UnsetSession();
		$this->usuario->AdvancedSearch->UnsetSession();
		$this->password->AdvancedSearch->UnsetSession();
		$this->nombres->AdvancedSearch->UnsetSession();
		$this->apellidos->AdvancedSearch->UnsetSession();
		$this->direccion->AdvancedSearch->UnsetSession();
		$this->telefonos->AdvancedSearch->UnsetSession();
		$this->tipoUsuario->AdvancedSearch->UnsetSession();
		$this->estado->AdvancedSearch->UnsetSession();
		$this->tipoIngreso->AdvancedSearch->UnsetSession();
		$this->grupo->AdvancedSearch->UnsetSession();
		$this->etiquetas->AdvancedSearch->UnsetSession();
		$this->iniciales->AdvancedSearch->UnsetSession();
		$this->sueldo->AdvancedSearch->UnsetSession();
		$this->tipoSueldo->AdvancedSearch->UnsetSession();
		$this->horaExtra->AdvancedSearch->UnsetSession();
		$this->empresa->AdvancedSearch->UnsetSession();
		$this->userlevelid->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->idUsuario->AdvancedSearch->Load();
		$this->usuario->AdvancedSearch->Load();
		$this->password->AdvancedSearch->Load();
		$this->nombres->AdvancedSearch->Load();
		$this->apellidos->AdvancedSearch->Load();
		$this->direccion->AdvancedSearch->Load();
		$this->telefonos->AdvancedSearch->Load();
		$this->tipoUsuario->AdvancedSearch->Load();
		$this->estado->AdvancedSearch->Load();
		$this->tipoIngreso->AdvancedSearch->Load();
		$this->grupo->AdvancedSearch->Load();
		$this->etiquetas->AdvancedSearch->Load();
		$this->iniciales->AdvancedSearch->Load();
		$this->sueldo->AdvancedSearch->Load();
		$this->tipoSueldo->AdvancedSearch->Load();
		$this->horaExtra->AdvancedSearch->Load();
		$this->empresa->AdvancedSearch->Load();
		$this->userlevelid->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->nombres); // nombres
			$this->UpdateSort($this->apellidos); // apellidos
			$this->UpdateSort($this->tipoIngreso); // tipoIngreso
			$this->UpdateSort($this->grupo); // grupo
			$this->UpdateSort($this->etiquetas); // etiquetas
			$this->UpdateSort($this->iniciales); // iniciales
			$this->UpdateSort($this->sueldo); // sueldo
			$this->UpdateSort($this->tipoSueldo); // tipoSueldo
			$this->UpdateSort($this->horaExtra); // horaExtra
			$this->UpdateSort($this->userlevelid); // userlevelid
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->nombres->setSort("");
				$this->apellidos->setSort("");
				$this->tipoIngreso->setSort("");
				$this->grupo->setSort("");
				$this->etiquetas->setSort("");
				$this->iniciales->setSort("");
				$this->sueldo->setSort("");
				$this->tipoSueldo->setSort("");
				$this->horaExtra->setSort("");
				$this->userlevelid->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = TRUE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = TRUE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanAdd();
		$item->OnLeft = TRUE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = TRUE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->CanView() && $this->ShowOptionLink('view'))
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->CanEdit() && $this->ShowOptionLink('edit')) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if ($Security->CanAdd() && $this->ShowOptionLink('add')) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->idUsuario->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fusuariolistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fusuariolistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fusuariolist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$user = $row['usuario'];
					if ($userlist <> "") $userlist .= ",";
					$userlist .= $user;
					if ($UserAction == "resendregisteremail")
						$Processed = FALSE;
					elseif ($UserAction == "resetconcurrentuser")
						$Processed = FALSE;
					elseif ($UserAction == "resetloginretry")
						$Processed = FALSE;
					elseif ($UserAction == "setpasswordexpired")
						$Processed = FALSE;
					else
						$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fusuariolistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// idUsuario

		$this->idUsuario->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idUsuario"]);
		if ($this->idUsuario->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idUsuario->AdvancedSearch->SearchOperator = @$_GET["z_idUsuario"];

		// usuario
		$this->usuario->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_usuario"]);
		if ($this->usuario->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->usuario->AdvancedSearch->SearchOperator = @$_GET["z_usuario"];

		// password
		$this->password->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_password"]);
		if ($this->password->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->password->AdvancedSearch->SearchOperator = @$_GET["z_password"];

		// nombres
		$this->nombres->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nombres"]);
		if ($this->nombres->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nombres->AdvancedSearch->SearchOperator = @$_GET["z_nombres"];

		// apellidos
		$this->apellidos->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_apellidos"]);
		if ($this->apellidos->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->apellidos->AdvancedSearch->SearchOperator = @$_GET["z_apellidos"];

		// direccion
		$this->direccion->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_direccion"]);
		if ($this->direccion->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->direccion->AdvancedSearch->SearchOperator = @$_GET["z_direccion"];

		// telefonos
		$this->telefonos->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_telefonos"]);
		if ($this->telefonos->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->telefonos->AdvancedSearch->SearchOperator = @$_GET["z_telefonos"];

		// tipoUsuario
		$this->tipoUsuario->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_tipoUsuario"]);
		if ($this->tipoUsuario->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->tipoUsuario->AdvancedSearch->SearchOperator = @$_GET["z_tipoUsuario"];

		// estado
		$this->estado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_estado"]);
		if ($this->estado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->estado->AdvancedSearch->SearchOperator = @$_GET["z_estado"];

		// tipoIngreso
		$this->tipoIngreso->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_tipoIngreso"]);
		if ($this->tipoIngreso->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->tipoIngreso->AdvancedSearch->SearchOperator = @$_GET["z_tipoIngreso"];

		// grupo
		$this->grupo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_grupo"]);
		if ($this->grupo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->grupo->AdvancedSearch->SearchOperator = @$_GET["z_grupo"];

		// etiquetas
		$this->etiquetas->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_etiquetas"]);
		if ($this->etiquetas->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->etiquetas->AdvancedSearch->SearchOperator = @$_GET["z_etiquetas"];

		// iniciales
		$this->iniciales->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_iniciales"]);
		if ($this->iniciales->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->iniciales->AdvancedSearch->SearchOperator = @$_GET["z_iniciales"];

		// sueldo
		$this->sueldo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_sueldo"]);
		if ($this->sueldo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->sueldo->AdvancedSearch->SearchOperator = @$_GET["z_sueldo"];

		// tipoSueldo
		$this->tipoSueldo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_tipoSueldo"]);
		if ($this->tipoSueldo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->tipoSueldo->AdvancedSearch->SearchOperator = @$_GET["z_tipoSueldo"];

		// horaExtra
		$this->horaExtra->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_horaExtra"]);
		if ($this->horaExtra->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->horaExtra->AdvancedSearch->SearchOperator = @$_GET["z_horaExtra"];

		// empresa
		$this->empresa->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_empresa"]);
		if ($this->empresa->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->empresa->AdvancedSearch->SearchOperator = @$_GET["z_empresa"];

		// userlevelid
		$this->userlevelid->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_userlevelid"]);
		if ($this->userlevelid->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->userlevelid->AdvancedSearch->SearchOperator = @$_GET["z_userlevelid"];
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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

			// nombres
			$this->nombres->LinkCustomAttributes = "";
			$this->nombres->HrefValue = "";
			$this->nombres->TooltipValue = "";

			// apellidos
			$this->apellidos->LinkCustomAttributes = "";
			$this->apellidos->HrefValue = "";
			$this->apellidos->TooltipValue = "";

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

			// userlevelid
			$this->userlevelid->LinkCustomAttributes = "";
			$this->userlevelid->HrefValue = "";
			$this->userlevelid->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// nombres
			$this->nombres->EditAttrs["class"] = "form-control";
			$this->nombres->EditCustomAttributes = "";
			$this->nombres->EditValue = ew_HtmlEncode($this->nombres->AdvancedSearch->SearchValue);
			$this->nombres->PlaceHolder = ew_RemoveHtml($this->nombres->FldCaption());

			// apellidos
			$this->apellidos->EditAttrs["class"] = "form-control";
			$this->apellidos->EditCustomAttributes = "";
			$this->apellidos->EditValue = ew_HtmlEncode($this->apellidos->AdvancedSearch->SearchValue);
			$this->apellidos->PlaceHolder = ew_RemoveHtml($this->apellidos->FldCaption());

			// tipoIngreso
			$this->tipoIngreso->EditAttrs["class"] = "form-control";
			$this->tipoIngreso->EditCustomAttributes = "";

			// grupo
			$this->grupo->EditAttrs["class"] = "form-control";
			$this->grupo->EditCustomAttributes = "";
			$this->grupo->EditValue = ew_HtmlEncode($this->grupo->AdvancedSearch->SearchValue);
			$this->grupo->PlaceHolder = ew_RemoveHtml($this->grupo->FldCaption());

			// etiquetas
			$this->etiquetas->EditAttrs["class"] = "form-control";
			$this->etiquetas->EditCustomAttributes = "";
			$this->etiquetas->EditValue = ew_HtmlEncode($this->etiquetas->AdvancedSearch->SearchValue);
			$this->etiquetas->PlaceHolder = ew_RemoveHtml($this->etiquetas->FldCaption());

			// iniciales
			$this->iniciales->EditAttrs["class"] = "form-control";
			$this->iniciales->EditCustomAttributes = "";
			$this->iniciales->EditValue = ew_HtmlEncode($this->iniciales->AdvancedSearch->SearchValue);
			$this->iniciales->PlaceHolder = ew_RemoveHtml($this->iniciales->FldCaption());

			// sueldo
			$this->sueldo->EditAttrs["class"] = "form-control";
			$this->sueldo->EditCustomAttributes = "";
			$this->sueldo->EditValue = ew_HtmlEncode($this->sueldo->AdvancedSearch->SearchValue);
			$this->sueldo->PlaceHolder = ew_RemoveHtml($this->sueldo->FldCaption());

			// tipoSueldo
			$this->tipoSueldo->EditAttrs["class"] = "form-control";
			$this->tipoSueldo->EditCustomAttributes = "";
			$this->tipoSueldo->EditValue = $this->tipoSueldo->Options(TRUE);

			// horaExtra
			$this->horaExtra->EditAttrs["class"] = "form-control";
			$this->horaExtra->EditCustomAttributes = "";
			$this->horaExtra->EditValue = ew_HtmlEncode($this->horaExtra->AdvancedSearch->SearchValue);
			$this->horaExtra->PlaceHolder = ew_RemoveHtml($this->horaExtra->FldCaption());

			// userlevelid
			$this->userlevelid->EditAttrs["class"] = "form-control";
			$this->userlevelid->EditCustomAttributes = "";
			if (!$Security->CanAdmin()) { // System admin
				$this->userlevelid->EditValue = $Language->Phrase("PasswordMask");
			} else {
			}
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

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->idUsuario->AdvancedSearch->Load();
		$this->usuario->AdvancedSearch->Load();
		$this->password->AdvancedSearch->Load();
		$this->nombres->AdvancedSearch->Load();
		$this->apellidos->AdvancedSearch->Load();
		$this->direccion->AdvancedSearch->Load();
		$this->telefonos->AdvancedSearch->Load();
		$this->tipoUsuario->AdvancedSearch->Load();
		$this->estado->AdvancedSearch->Load();
		$this->tipoIngreso->AdvancedSearch->Load();
		$this->grupo->AdvancedSearch->Load();
		$this->etiquetas->AdvancedSearch->Load();
		$this->iniciales->AdvancedSearch->Load();
		$this->sueldo->AdvancedSearch->Load();
		$this->tipoSueldo->AdvancedSearch->Load();
		$this->horaExtra->AdvancedSearch->Load();
		$this->empresa->AdvancedSearch->Load();
		$this->userlevelid->AdvancedSearch->Load();
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
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_usuario\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_usuario',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fusuariolist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = $this->UseSelectLimit;

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

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "h");
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
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "");
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
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
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
if (!isset($usuario_list)) $usuario_list = new cusuario_list();

// Page init
$usuario_list->Page_Init();

// Page main
$usuario_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$usuario_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($usuario->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fusuariolist = new ew_Form("fusuariolist", "list");
fusuariolist.FormKeyCountName = '<?php echo $usuario_list->FormKeyCountName ?>';

// Form_CustomValidate event
fusuariolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fusuariolist.ValidateRequired = true;
<?php } else { ?>
fusuariolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fusuariolist.Lists["x_tipoIngreso"] = {"LinkField":"x_idIngresoTipo","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusuariolist.Lists["x_tipoSueldo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusuariolist.Lists["x_tipoSueldo"].Options = <?php echo json_encode($usuario->tipoSueldo->Options()) ?>;
fusuariolist.Lists["x_userlevelid"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
var CurrentSearchForm = fusuariolistsrch = new ew_Form("fusuariolistsrch");

// Validate function for search
fusuariolistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fusuariolistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fusuariolistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fusuariolistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fusuariolistsrch.Lists["x_tipoSueldo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusuariolistsrch.Lists["x_tipoSueldo"].Options = <?php echo json_encode($usuario->tipoSueldo->Options()) ?>;
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
<?php if ($usuario_list->TotalRecs > 0 && $usuario_list->ExportOptions->Visible()) { ?>
<?php $usuario_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($usuario_list->SearchOptions->Visible()) { ?>
<?php $usuario_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($usuario_list->FilterOptions->Visible()) { ?>
<?php $usuario_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($usuario->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $usuario_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($usuario_list->TotalRecs <= 0)
			$usuario_list->TotalRecs = $usuario->SelectRecordCount();
	} else {
		if (!$usuario_list->Recordset && ($usuario_list->Recordset = $usuario_list->LoadRecordset()))
			$usuario_list->TotalRecs = $usuario_list->Recordset->RecordCount();
	}
	$usuario_list->StartRec = 1;
	if ($usuario_list->DisplayRecs <= 0 || ($usuario->Export <> "" && $usuario->ExportAll)) // Display all records
		$usuario_list->DisplayRecs = $usuario_list->TotalRecs;
	if (!($usuario->Export <> "" && $usuario->ExportAll))
		$usuario_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$usuario_list->Recordset = $usuario_list->LoadRecordset($usuario_list->StartRec-1, $usuario_list->DisplayRecs);

	// Set no record found message
	if ($usuario->CurrentAction == "" && $usuario_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$usuario_list->setWarningMessage(ew_DeniedMsg());
		if ($usuario_list->SearchWhere == "0=101")
			$usuario_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$usuario_list->setWarningMessage($Language->Phrase("NoRecord"));
	}

	// Audit trail on search
	if ($usuario_list->AuditTrailOnSearch && $usuario_list->Command == "search" && !$usuario_list->RestoreSearch) {
		$searchparm = ew_ServerVar("QUERY_STRING");
		$searchsql = $usuario_list->getSessionWhere();
		$usuario_list->WriteAuditTrailOnSearch($searchparm, $searchsql);
	}
$usuario_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($usuario->Export == "" && $usuario->CurrentAction == "") { ?>
<form name="fusuariolistsrch" id="fusuariolistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($usuario_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fusuariolistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="usuario">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$usuario_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$usuario->RowType = EW_ROWTYPE_SEARCH;

// Render row
$usuario->ResetAttrs();
$usuario_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($usuario->tipoSueldo->Visible) { // tipoSueldo ?>
	<div id="xsc_tipoSueldo" class="ewCell form-group">
		<label for="x_tipoSueldo" class="ewSearchCaption ewLabel"><?php echo $usuario->tipoSueldo->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_tipoSueldo" id="z_tipoSueldo" value="="></span>
		<span class="ewSearchField">
<select data-table="usuario" data-field="x_tipoSueldo" data-value-separator="<?php echo ew_HtmlEncode(is_array($usuario->tipoSueldo->DisplayValueSeparator) ? json_encode($usuario->tipoSueldo->DisplayValueSeparator) : $usuario->tipoSueldo->DisplayValueSeparator) ?>" id="x_tipoSueldo" name="x_tipoSueldo"<?php echo $usuario->tipoSueldo->EditAttributes() ?>>
<?php
if (is_array($usuario->tipoSueldo->EditValue)) {
	$arwrk = $usuario->tipoSueldo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($usuario->tipoSueldo->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
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
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($usuario_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($usuario_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $usuario_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($usuario_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($usuario_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($usuario_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($usuario_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $usuario_list->ShowPageHeader(); ?>
<?php
$usuario_list->ShowMessage();
?>
<?php if ($usuario_list->TotalRecs > 0 || $usuario->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<?php if ($usuario->Export == "") { ?>
<div class="panel-heading ewGridUpperPanel">
<?php if ($usuario->CurrentAction <> "gridadd" && $usuario->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($usuario_list->Pager)) $usuario_list->Pager = new cPrevNextPager($usuario_list->StartRec, $usuario_list->DisplayRecs, $usuario_list->TotalRecs) ?>
<?php if ($usuario_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($usuario_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $usuario_list->PageUrl() ?>start=<?php echo $usuario_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($usuario_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $usuario_list->PageUrl() ?>start=<?php echo $usuario_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $usuario_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($usuario_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $usuario_list->PageUrl() ?>start=<?php echo $usuario_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($usuario_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $usuario_list->PageUrl() ?>start=<?php echo $usuario_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $usuario_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $usuario_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $usuario_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $usuario_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($usuario_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="usuario">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="20"<?php if ($usuario_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($usuario_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="ALL"<?php if ($usuario->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($usuario_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fusuariolist" id="fusuariolist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($usuario_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $usuario_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="usuario">
<div id="gmp_usuario" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($usuario_list->TotalRecs > 0) { ?>
<table id="tbl_usuariolist" class="table ewTable">
<?php echo $usuario->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$usuario_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$usuario_list->RenderListOptions();

// Render list options (header, left)
$usuario_list->ListOptions->Render("header", "left");
?>
<?php if ($usuario->nombres->Visible) { // nombres ?>
	<?php if ($usuario->SortUrl($usuario->nombres) == "") { ?>
		<th data-name="nombres"><div id="elh_usuario_nombres" class="usuario_nombres"><div class="ewTableHeaderCaption"><?php echo $usuario->nombres->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombres"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $usuario->SortUrl($usuario->nombres) ?>',1);"><div id="elh_usuario_nombres" class="usuario_nombres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $usuario->nombres->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($usuario->nombres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($usuario->nombres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($usuario->apellidos->Visible) { // apellidos ?>
	<?php if ($usuario->SortUrl($usuario->apellidos) == "") { ?>
		<th data-name="apellidos"><div id="elh_usuario_apellidos" class="usuario_apellidos"><div class="ewTableHeaderCaption"><?php echo $usuario->apellidos->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="apellidos"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $usuario->SortUrl($usuario->apellidos) ?>',1);"><div id="elh_usuario_apellidos" class="usuario_apellidos">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $usuario->apellidos->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($usuario->apellidos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($usuario->apellidos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($usuario->tipoIngreso->Visible) { // tipoIngreso ?>
	<?php if ($usuario->SortUrl($usuario->tipoIngreso) == "") { ?>
		<th data-name="tipoIngreso"><div id="elh_usuario_tipoIngreso" class="usuario_tipoIngreso"><div class="ewTableHeaderCaption"><?php echo $usuario->tipoIngreso->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipoIngreso"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $usuario->SortUrl($usuario->tipoIngreso) ?>',1);"><div id="elh_usuario_tipoIngreso" class="usuario_tipoIngreso">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $usuario->tipoIngreso->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($usuario->tipoIngreso->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($usuario->tipoIngreso->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($usuario->grupo->Visible) { // grupo ?>
	<?php if ($usuario->SortUrl($usuario->grupo) == "") { ?>
		<th data-name="grupo"><div id="elh_usuario_grupo" class="usuario_grupo"><div class="ewTableHeaderCaption"><?php echo $usuario->grupo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="grupo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $usuario->SortUrl($usuario->grupo) ?>',1);"><div id="elh_usuario_grupo" class="usuario_grupo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $usuario->grupo->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($usuario->grupo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($usuario->grupo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($usuario->etiquetas->Visible) { // etiquetas ?>
	<?php if ($usuario->SortUrl($usuario->etiquetas) == "") { ?>
		<th data-name="etiquetas"><div id="elh_usuario_etiquetas" class="usuario_etiquetas"><div class="ewTableHeaderCaption"><?php echo $usuario->etiquetas->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="etiquetas"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $usuario->SortUrl($usuario->etiquetas) ?>',1);"><div id="elh_usuario_etiquetas" class="usuario_etiquetas">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $usuario->etiquetas->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($usuario->etiquetas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($usuario->etiquetas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($usuario->iniciales->Visible) { // iniciales ?>
	<?php if ($usuario->SortUrl($usuario->iniciales) == "") { ?>
		<th data-name="iniciales"><div id="elh_usuario_iniciales" class="usuario_iniciales"><div class="ewTableHeaderCaption"><?php echo $usuario->iniciales->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="iniciales"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $usuario->SortUrl($usuario->iniciales) ?>',1);"><div id="elh_usuario_iniciales" class="usuario_iniciales">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $usuario->iniciales->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($usuario->iniciales->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($usuario->iniciales->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($usuario->sueldo->Visible) { // sueldo ?>
	<?php if ($usuario->SortUrl($usuario->sueldo) == "") { ?>
		<th data-name="sueldo"><div id="elh_usuario_sueldo" class="usuario_sueldo"><div class="ewTableHeaderCaption"><?php echo $usuario->sueldo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sueldo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $usuario->SortUrl($usuario->sueldo) ?>',1);"><div id="elh_usuario_sueldo" class="usuario_sueldo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $usuario->sueldo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($usuario->sueldo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($usuario->sueldo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($usuario->tipoSueldo->Visible) { // tipoSueldo ?>
	<?php if ($usuario->SortUrl($usuario->tipoSueldo) == "") { ?>
		<th data-name="tipoSueldo"><div id="elh_usuario_tipoSueldo" class="usuario_tipoSueldo"><div class="ewTableHeaderCaption"><?php echo $usuario->tipoSueldo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipoSueldo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $usuario->SortUrl($usuario->tipoSueldo) ?>',1);"><div id="elh_usuario_tipoSueldo" class="usuario_tipoSueldo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $usuario->tipoSueldo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($usuario->tipoSueldo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($usuario->tipoSueldo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($usuario->horaExtra->Visible) { // horaExtra ?>
	<?php if ($usuario->SortUrl($usuario->horaExtra) == "") { ?>
		<th data-name="horaExtra"><div id="elh_usuario_horaExtra" class="usuario_horaExtra"><div class="ewTableHeaderCaption"><?php echo $usuario->horaExtra->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="horaExtra"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $usuario->SortUrl($usuario->horaExtra) ?>',1);"><div id="elh_usuario_horaExtra" class="usuario_horaExtra">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $usuario->horaExtra->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($usuario->horaExtra->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($usuario->horaExtra->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($usuario->userlevelid->Visible) { // userlevelid ?>
	<?php if ($usuario->SortUrl($usuario->userlevelid) == "") { ?>
		<th data-name="userlevelid"><div id="elh_usuario_userlevelid" class="usuario_userlevelid"><div class="ewTableHeaderCaption"><?php echo $usuario->userlevelid->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="userlevelid"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $usuario->SortUrl($usuario->userlevelid) ?>',1);"><div id="elh_usuario_userlevelid" class="usuario_userlevelid">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $usuario->userlevelid->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($usuario->userlevelid->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($usuario->userlevelid->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$usuario_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($usuario->ExportAll && $usuario->Export <> "") {
	$usuario_list->StopRec = $usuario_list->TotalRecs;
} else {

	// Set the last record to display
	if ($usuario_list->TotalRecs > $usuario_list->StartRec + $usuario_list->DisplayRecs - 1)
		$usuario_list->StopRec = $usuario_list->StartRec + $usuario_list->DisplayRecs - 1;
	else
		$usuario_list->StopRec = $usuario_list->TotalRecs;
}
$usuario_list->RecCnt = $usuario_list->StartRec - 1;
if ($usuario_list->Recordset && !$usuario_list->Recordset->EOF) {
	$usuario_list->Recordset->MoveFirst();
	$bSelectLimit = $usuario_list->UseSelectLimit;
	if (!$bSelectLimit && $usuario_list->StartRec > 1)
		$usuario_list->Recordset->Move($usuario_list->StartRec - 1);
} elseif (!$usuario->AllowAddDeleteRow && $usuario_list->StopRec == 0) {
	$usuario_list->StopRec = $usuario->GridAddRowCount;
}

// Initialize aggregate
$usuario->RowType = EW_ROWTYPE_AGGREGATEINIT;
$usuario->ResetAttrs();
$usuario_list->RenderRow();
while ($usuario_list->RecCnt < $usuario_list->StopRec) {
	$usuario_list->RecCnt++;
	if (intval($usuario_list->RecCnt) >= intval($usuario_list->StartRec)) {
		$usuario_list->RowCnt++;

		// Set up key count
		$usuario_list->KeyCount = $usuario_list->RowIndex;

		// Init row class and style
		$usuario->ResetAttrs();
		$usuario->CssClass = "";
		if ($usuario->CurrentAction == "gridadd") {
		} else {
			$usuario_list->LoadRowValues($usuario_list->Recordset); // Load row values
		}
		$usuario->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$usuario->RowAttrs = array_merge($usuario->RowAttrs, array('data-rowindex'=>$usuario_list->RowCnt, 'id'=>'r' . $usuario_list->RowCnt . '_usuario', 'data-rowtype'=>$usuario->RowType));

		// Render row
		$usuario_list->RenderRow();

		// Render list options
		$usuario_list->RenderListOptions();
?>
	<tr<?php echo $usuario->RowAttributes() ?>>
<?php

// Render list options (body, left)
$usuario_list->ListOptions->Render("body", "left", $usuario_list->RowCnt);
?>
	<?php if ($usuario->nombres->Visible) { // nombres ?>
		<td data-name="nombres"<?php echo $usuario->nombres->CellAttributes() ?>>
<span id="el<?php echo $usuario_list->RowCnt ?>_usuario_nombres" class="usuario_nombres">
<span<?php echo $usuario->nombres->ViewAttributes() ?>>
<?php echo $usuario->nombres->ListViewValue() ?></span>
</span>
<a id="<?php echo $usuario_list->PageObjName . "_row_" . $usuario_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($usuario->apellidos->Visible) { // apellidos ?>
		<td data-name="apellidos"<?php echo $usuario->apellidos->CellAttributes() ?>>
<span id="el<?php echo $usuario_list->RowCnt ?>_usuario_apellidos" class="usuario_apellidos">
<span<?php echo $usuario->apellidos->ViewAttributes() ?>>
<?php echo $usuario->apellidos->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($usuario->tipoIngreso->Visible) { // tipoIngreso ?>
		<td data-name="tipoIngreso"<?php echo $usuario->tipoIngreso->CellAttributes() ?>>
<span id="el<?php echo $usuario_list->RowCnt ?>_usuario_tipoIngreso" class="usuario_tipoIngreso">
<span<?php echo $usuario->tipoIngreso->ViewAttributes() ?>>
<?php echo $usuario->tipoIngreso->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($usuario->grupo->Visible) { // grupo ?>
		<td data-name="grupo"<?php echo $usuario->grupo->CellAttributes() ?>>
<span id="el<?php echo $usuario_list->RowCnt ?>_usuario_grupo" class="usuario_grupo">
<span<?php echo $usuario->grupo->ViewAttributes() ?>>
<?php echo $usuario->grupo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($usuario->etiquetas->Visible) { // etiquetas ?>
		<td data-name="etiquetas"<?php echo $usuario->etiquetas->CellAttributes() ?>>
<span id="el<?php echo $usuario_list->RowCnt ?>_usuario_etiquetas" class="usuario_etiquetas">
<span<?php echo $usuario->etiquetas->ViewAttributes() ?>>
<?php echo $usuario->etiquetas->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($usuario->iniciales->Visible) { // iniciales ?>
		<td data-name="iniciales"<?php echo $usuario->iniciales->CellAttributes() ?>>
<span id="el<?php echo $usuario_list->RowCnt ?>_usuario_iniciales" class="usuario_iniciales">
<span<?php echo $usuario->iniciales->ViewAttributes() ?>>
<?php echo $usuario->iniciales->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($usuario->sueldo->Visible) { // sueldo ?>
		<td data-name="sueldo"<?php echo $usuario->sueldo->CellAttributes() ?>>
<span id="el<?php echo $usuario_list->RowCnt ?>_usuario_sueldo" class="usuario_sueldo">
<span<?php echo $usuario->sueldo->ViewAttributes() ?>>
<?php echo $usuario->sueldo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($usuario->tipoSueldo->Visible) { // tipoSueldo ?>
		<td data-name="tipoSueldo"<?php echo $usuario->tipoSueldo->CellAttributes() ?>>
<span id="el<?php echo $usuario_list->RowCnt ?>_usuario_tipoSueldo" class="usuario_tipoSueldo">
<span<?php echo $usuario->tipoSueldo->ViewAttributes() ?>>
<?php echo $usuario->tipoSueldo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($usuario->horaExtra->Visible) { // horaExtra ?>
		<td data-name="horaExtra"<?php echo $usuario->horaExtra->CellAttributes() ?>>
<span id="el<?php echo $usuario_list->RowCnt ?>_usuario_horaExtra" class="usuario_horaExtra">
<span<?php echo $usuario->horaExtra->ViewAttributes() ?>>
<?php echo $usuario->horaExtra->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($usuario->userlevelid->Visible) { // userlevelid ?>
		<td data-name="userlevelid"<?php echo $usuario->userlevelid->CellAttributes() ?>>
<span id="el<?php echo $usuario_list->RowCnt ?>_usuario_userlevelid" class="usuario_userlevelid">
<span<?php echo $usuario->userlevelid->ViewAttributes() ?>>
<?php echo $usuario->userlevelid->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$usuario_list->ListOptions->Render("body", "right", $usuario_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($usuario->CurrentAction <> "gridadd")
		$usuario_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($usuario->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($usuario_list->Recordset)
	$usuario_list->Recordset->Close();
?>
<?php if ($usuario->Export == "") { ?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($usuario->CurrentAction <> "gridadd" && $usuario->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($usuario_list->Pager)) $usuario_list->Pager = new cPrevNextPager($usuario_list->StartRec, $usuario_list->DisplayRecs, $usuario_list->TotalRecs) ?>
<?php if ($usuario_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($usuario_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $usuario_list->PageUrl() ?>start=<?php echo $usuario_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($usuario_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $usuario_list->PageUrl() ?>start=<?php echo $usuario_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $usuario_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($usuario_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $usuario_list->PageUrl() ?>start=<?php echo $usuario_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($usuario_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $usuario_list->PageUrl() ?>start=<?php echo $usuario_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $usuario_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $usuario_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $usuario_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $usuario_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($usuario_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="usuario">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="20"<?php if ($usuario_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($usuario_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="ALL"<?php if ($usuario->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($usuario_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($usuario_list->TotalRecs == 0 && $usuario->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($usuario_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($usuario->Export == "") { ?>
<script type="text/javascript">
fusuariolistsrch.Init();
fusuariolistsrch.FilterList = <?php echo $usuario_list->GetFilterList() ?>;
fusuariolist.Init();
</script>
<?php } ?>
<?php
$usuario_list->ShowPageFooter();
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
$usuario_list->Page_Terminate();
?>
