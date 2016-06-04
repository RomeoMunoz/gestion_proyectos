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

$proyecto_list = NULL; // Initialize page object first

class cproyecto_list extends cproyecto {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}";

	// Table name
	var $TableName = 'proyecto';

	// Page object name
	var $PageObjName = 'proyecto_list';

	// Grid form hidden field names
	var $FormName = 'fproyectolist';
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

		// Table object (proyecto)
		if (!isset($GLOBALS["proyecto"]) || get_class($GLOBALS["proyecto"]) == "cproyecto") {
			$GLOBALS["proyecto"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["proyecto"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "proyectoadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "proyectodelete.php";
		$this->MultiUpdateUrl = "proyectoupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fproyectolistsrch";

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
			$this->idProyecto->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->idProyecto->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->idProyecto->AdvancedSearch->ToJSON(), ","); // Field idProyecto
		$sFilterList = ew_Concat($sFilterList, $this->nombre->AdvancedSearch->ToJSON(), ","); // Field nombre
		$sFilterList = ew_Concat($sFilterList, $this->descripcion->AdvancedSearch->ToJSON(), ","); // Field descripcion
		$sFilterList = ew_Concat($sFilterList, $this->fechaInicio->AdvancedSearch->ToJSON(), ","); // Field fechaInicio
		$sFilterList = ew_Concat($sFilterList, $this->fechaFin->AdvancedSearch->ToJSON(), ","); // Field fechaFin
		$sFilterList = ew_Concat($sFilterList, $this->fechaCreacion->AdvancedSearch->ToJSON(), ","); // Field fechaCreacion
		$sFilterList = ew_Concat($sFilterList, $this->usuarioCreacion->AdvancedSearch->ToJSON(), ","); // Field usuarioCreacion
		$sFilterList = ew_Concat($sFilterList, $this->usuarioLider->AdvancedSearch->ToJSON(), ","); // Field usuarioLider
		$sFilterList = ew_Concat($sFilterList, $this->usuarioEncargado->AdvancedSearch->ToJSON(), ","); // Field usuarioEncargado
		$sFilterList = ew_Concat($sFilterList, $this->cliente->AdvancedSearch->ToJSON(), ","); // Field cliente
		$sFilterList = ew_Concat($sFilterList, $this->prioridad->AdvancedSearch->ToJSON(), ","); // Field prioridad
		$sFilterList = ew_Concat($sFilterList, $this->fechaUltimoAcceso->AdvancedSearch->ToJSON(), ","); // Field fechaUltimoAcceso
		$sFilterList = ew_Concat($sFilterList, $this->fechaModificacion->AdvancedSearch->ToJSON(), ","); // Field fechaModificacion
		$sFilterList = ew_Concat($sFilterList, $this->usuarioModificacion->AdvancedSearch->ToJSON(), ","); // Field usuarioModificacion
		$sFilterList = ew_Concat($sFilterList, $this->estatus->AdvancedSearch->ToJSON(), ","); // Field estatus
		$sFilterList = ew_Concat($sFilterList, $this->estado->AdvancedSearch->ToJSON(), ","); // Field estado
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

		// Field idProyecto
		$this->idProyecto->AdvancedSearch->SearchValue = @$filter["x_idProyecto"];
		$this->idProyecto->AdvancedSearch->SearchOperator = @$filter["z_idProyecto"];
		$this->idProyecto->AdvancedSearch->SearchCondition = @$filter["v_idProyecto"];
		$this->idProyecto->AdvancedSearch->SearchValue2 = @$filter["y_idProyecto"];
		$this->idProyecto->AdvancedSearch->SearchOperator2 = @$filter["w_idProyecto"];
		$this->idProyecto->AdvancedSearch->Save();

		// Field nombre
		$this->nombre->AdvancedSearch->SearchValue = @$filter["x_nombre"];
		$this->nombre->AdvancedSearch->SearchOperator = @$filter["z_nombre"];
		$this->nombre->AdvancedSearch->SearchCondition = @$filter["v_nombre"];
		$this->nombre->AdvancedSearch->SearchValue2 = @$filter["y_nombre"];
		$this->nombre->AdvancedSearch->SearchOperator2 = @$filter["w_nombre"];
		$this->nombre->AdvancedSearch->Save();

		// Field descripcion
		$this->descripcion->AdvancedSearch->SearchValue = @$filter["x_descripcion"];
		$this->descripcion->AdvancedSearch->SearchOperator = @$filter["z_descripcion"];
		$this->descripcion->AdvancedSearch->SearchCondition = @$filter["v_descripcion"];
		$this->descripcion->AdvancedSearch->SearchValue2 = @$filter["y_descripcion"];
		$this->descripcion->AdvancedSearch->SearchOperator2 = @$filter["w_descripcion"];
		$this->descripcion->AdvancedSearch->Save();

		// Field fechaInicio
		$this->fechaInicio->AdvancedSearch->SearchValue = @$filter["x_fechaInicio"];
		$this->fechaInicio->AdvancedSearch->SearchOperator = @$filter["z_fechaInicio"];
		$this->fechaInicio->AdvancedSearch->SearchCondition = @$filter["v_fechaInicio"];
		$this->fechaInicio->AdvancedSearch->SearchValue2 = @$filter["y_fechaInicio"];
		$this->fechaInicio->AdvancedSearch->SearchOperator2 = @$filter["w_fechaInicio"];
		$this->fechaInicio->AdvancedSearch->Save();

		// Field fechaFin
		$this->fechaFin->AdvancedSearch->SearchValue = @$filter["x_fechaFin"];
		$this->fechaFin->AdvancedSearch->SearchOperator = @$filter["z_fechaFin"];
		$this->fechaFin->AdvancedSearch->SearchCondition = @$filter["v_fechaFin"];
		$this->fechaFin->AdvancedSearch->SearchValue2 = @$filter["y_fechaFin"];
		$this->fechaFin->AdvancedSearch->SearchOperator2 = @$filter["w_fechaFin"];
		$this->fechaFin->AdvancedSearch->Save();

		// Field fechaCreacion
		$this->fechaCreacion->AdvancedSearch->SearchValue = @$filter["x_fechaCreacion"];
		$this->fechaCreacion->AdvancedSearch->SearchOperator = @$filter["z_fechaCreacion"];
		$this->fechaCreacion->AdvancedSearch->SearchCondition = @$filter["v_fechaCreacion"];
		$this->fechaCreacion->AdvancedSearch->SearchValue2 = @$filter["y_fechaCreacion"];
		$this->fechaCreacion->AdvancedSearch->SearchOperator2 = @$filter["w_fechaCreacion"];
		$this->fechaCreacion->AdvancedSearch->Save();

		// Field usuarioCreacion
		$this->usuarioCreacion->AdvancedSearch->SearchValue = @$filter["x_usuarioCreacion"];
		$this->usuarioCreacion->AdvancedSearch->SearchOperator = @$filter["z_usuarioCreacion"];
		$this->usuarioCreacion->AdvancedSearch->SearchCondition = @$filter["v_usuarioCreacion"];
		$this->usuarioCreacion->AdvancedSearch->SearchValue2 = @$filter["y_usuarioCreacion"];
		$this->usuarioCreacion->AdvancedSearch->SearchOperator2 = @$filter["w_usuarioCreacion"];
		$this->usuarioCreacion->AdvancedSearch->Save();

		// Field usuarioLider
		$this->usuarioLider->AdvancedSearch->SearchValue = @$filter["x_usuarioLider"];
		$this->usuarioLider->AdvancedSearch->SearchOperator = @$filter["z_usuarioLider"];
		$this->usuarioLider->AdvancedSearch->SearchCondition = @$filter["v_usuarioLider"];
		$this->usuarioLider->AdvancedSearch->SearchValue2 = @$filter["y_usuarioLider"];
		$this->usuarioLider->AdvancedSearch->SearchOperator2 = @$filter["w_usuarioLider"];
		$this->usuarioLider->AdvancedSearch->Save();

		// Field usuarioEncargado
		$this->usuarioEncargado->AdvancedSearch->SearchValue = @$filter["x_usuarioEncargado"];
		$this->usuarioEncargado->AdvancedSearch->SearchOperator = @$filter["z_usuarioEncargado"];
		$this->usuarioEncargado->AdvancedSearch->SearchCondition = @$filter["v_usuarioEncargado"];
		$this->usuarioEncargado->AdvancedSearch->SearchValue2 = @$filter["y_usuarioEncargado"];
		$this->usuarioEncargado->AdvancedSearch->SearchOperator2 = @$filter["w_usuarioEncargado"];
		$this->usuarioEncargado->AdvancedSearch->Save();

		// Field cliente
		$this->cliente->AdvancedSearch->SearchValue = @$filter["x_cliente"];
		$this->cliente->AdvancedSearch->SearchOperator = @$filter["z_cliente"];
		$this->cliente->AdvancedSearch->SearchCondition = @$filter["v_cliente"];
		$this->cliente->AdvancedSearch->SearchValue2 = @$filter["y_cliente"];
		$this->cliente->AdvancedSearch->SearchOperator2 = @$filter["w_cliente"];
		$this->cliente->AdvancedSearch->Save();

		// Field prioridad
		$this->prioridad->AdvancedSearch->SearchValue = @$filter["x_prioridad"];
		$this->prioridad->AdvancedSearch->SearchOperator = @$filter["z_prioridad"];
		$this->prioridad->AdvancedSearch->SearchCondition = @$filter["v_prioridad"];
		$this->prioridad->AdvancedSearch->SearchValue2 = @$filter["y_prioridad"];
		$this->prioridad->AdvancedSearch->SearchOperator2 = @$filter["w_prioridad"];
		$this->prioridad->AdvancedSearch->Save();

		// Field fechaUltimoAcceso
		$this->fechaUltimoAcceso->AdvancedSearch->SearchValue = @$filter["x_fechaUltimoAcceso"];
		$this->fechaUltimoAcceso->AdvancedSearch->SearchOperator = @$filter["z_fechaUltimoAcceso"];
		$this->fechaUltimoAcceso->AdvancedSearch->SearchCondition = @$filter["v_fechaUltimoAcceso"];
		$this->fechaUltimoAcceso->AdvancedSearch->SearchValue2 = @$filter["y_fechaUltimoAcceso"];
		$this->fechaUltimoAcceso->AdvancedSearch->SearchOperator2 = @$filter["w_fechaUltimoAcceso"];
		$this->fechaUltimoAcceso->AdvancedSearch->Save();

		// Field fechaModificacion
		$this->fechaModificacion->AdvancedSearch->SearchValue = @$filter["x_fechaModificacion"];
		$this->fechaModificacion->AdvancedSearch->SearchOperator = @$filter["z_fechaModificacion"];
		$this->fechaModificacion->AdvancedSearch->SearchCondition = @$filter["v_fechaModificacion"];
		$this->fechaModificacion->AdvancedSearch->SearchValue2 = @$filter["y_fechaModificacion"];
		$this->fechaModificacion->AdvancedSearch->SearchOperator2 = @$filter["w_fechaModificacion"];
		$this->fechaModificacion->AdvancedSearch->Save();

		// Field usuarioModificacion
		$this->usuarioModificacion->AdvancedSearch->SearchValue = @$filter["x_usuarioModificacion"];
		$this->usuarioModificacion->AdvancedSearch->SearchOperator = @$filter["z_usuarioModificacion"];
		$this->usuarioModificacion->AdvancedSearch->SearchCondition = @$filter["v_usuarioModificacion"];
		$this->usuarioModificacion->AdvancedSearch->SearchValue2 = @$filter["y_usuarioModificacion"];
		$this->usuarioModificacion->AdvancedSearch->SearchOperator2 = @$filter["w_usuarioModificacion"];
		$this->usuarioModificacion->AdvancedSearch->Save();

		// Field estatus
		$this->estatus->AdvancedSearch->SearchValue = @$filter["x_estatus"];
		$this->estatus->AdvancedSearch->SearchOperator = @$filter["z_estatus"];
		$this->estatus->AdvancedSearch->SearchCondition = @$filter["v_estatus"];
		$this->estatus->AdvancedSearch->SearchValue2 = @$filter["y_estatus"];
		$this->estatus->AdvancedSearch->SearchOperator2 = @$filter["w_estatus"];
		$this->estatus->AdvancedSearch->Save();

		// Field estado
		$this->estado->AdvancedSearch->SearchValue = @$filter["x_estado"];
		$this->estado->AdvancedSearch->SearchOperator = @$filter["z_estado"];
		$this->estado->AdvancedSearch->SearchCondition = @$filter["v_estado"];
		$this->estado->AdvancedSearch->SearchValue2 = @$filter["y_estado"];
		$this->estado->AdvancedSearch->SearchOperator2 = @$filter["w_estado"];
		$this->estado->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->idProyecto, $Default, FALSE); // idProyecto
		$this->BuildSearchSql($sWhere, $this->nombre, $Default, FALSE); // nombre
		$this->BuildSearchSql($sWhere, $this->descripcion, $Default, FALSE); // descripcion
		$this->BuildSearchSql($sWhere, $this->fechaInicio, $Default, FALSE); // fechaInicio
		$this->BuildSearchSql($sWhere, $this->fechaFin, $Default, FALSE); // fechaFin
		$this->BuildSearchSql($sWhere, $this->fechaCreacion, $Default, FALSE); // fechaCreacion
		$this->BuildSearchSql($sWhere, $this->usuarioCreacion, $Default, FALSE); // usuarioCreacion
		$this->BuildSearchSql($sWhere, $this->usuarioLider, $Default, FALSE); // usuarioLider
		$this->BuildSearchSql($sWhere, $this->usuarioEncargado, $Default, FALSE); // usuarioEncargado
		$this->BuildSearchSql($sWhere, $this->cliente, $Default, FALSE); // cliente
		$this->BuildSearchSql($sWhere, $this->prioridad, $Default, FALSE); // prioridad
		$this->BuildSearchSql($sWhere, $this->fechaUltimoAcceso, $Default, FALSE); // fechaUltimoAcceso
		$this->BuildSearchSql($sWhere, $this->fechaModificacion, $Default, FALSE); // fechaModificacion
		$this->BuildSearchSql($sWhere, $this->usuarioModificacion, $Default, FALSE); // usuarioModificacion
		$this->BuildSearchSql($sWhere, $this->estatus, $Default, FALSE); // estatus
		$this->BuildSearchSql($sWhere, $this->estado, $Default, FALSE); // estado

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->idProyecto->AdvancedSearch->Save(); // idProyecto
			$this->nombre->AdvancedSearch->Save(); // nombre
			$this->descripcion->AdvancedSearch->Save(); // descripcion
			$this->fechaInicio->AdvancedSearch->Save(); // fechaInicio
			$this->fechaFin->AdvancedSearch->Save(); // fechaFin
			$this->fechaCreacion->AdvancedSearch->Save(); // fechaCreacion
			$this->usuarioCreacion->AdvancedSearch->Save(); // usuarioCreacion
			$this->usuarioLider->AdvancedSearch->Save(); // usuarioLider
			$this->usuarioEncargado->AdvancedSearch->Save(); // usuarioEncargado
			$this->cliente->AdvancedSearch->Save(); // cliente
			$this->prioridad->AdvancedSearch->Save(); // prioridad
			$this->fechaUltimoAcceso->AdvancedSearch->Save(); // fechaUltimoAcceso
			$this->fechaModificacion->AdvancedSearch->Save(); // fechaModificacion
			$this->usuarioModificacion->AdvancedSearch->Save(); // usuarioModificacion
			$this->estatus->AdvancedSearch->Save(); // estatus
			$this->estado->AdvancedSearch->Save(); // estado
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
		$this->BuildBasicSearchSQL($sWhere, $this->nombre, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->descripcion, $arKeywords, $type);
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
		if ($this->idProyecto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nombre->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->descripcion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fechaInicio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fechaFin->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fechaCreacion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->usuarioCreacion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->usuarioLider->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->usuarioEncargado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->cliente->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->prioridad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fechaUltimoAcceso->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fechaModificacion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->usuarioModificacion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->estatus->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->estado->AdvancedSearch->IssetSession())
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
		$this->idProyecto->AdvancedSearch->UnsetSession();
		$this->nombre->AdvancedSearch->UnsetSession();
		$this->descripcion->AdvancedSearch->UnsetSession();
		$this->fechaInicio->AdvancedSearch->UnsetSession();
		$this->fechaFin->AdvancedSearch->UnsetSession();
		$this->fechaCreacion->AdvancedSearch->UnsetSession();
		$this->usuarioCreacion->AdvancedSearch->UnsetSession();
		$this->usuarioLider->AdvancedSearch->UnsetSession();
		$this->usuarioEncargado->AdvancedSearch->UnsetSession();
		$this->cliente->AdvancedSearch->UnsetSession();
		$this->prioridad->AdvancedSearch->UnsetSession();
		$this->fechaUltimoAcceso->AdvancedSearch->UnsetSession();
		$this->fechaModificacion->AdvancedSearch->UnsetSession();
		$this->usuarioModificacion->AdvancedSearch->UnsetSession();
		$this->estatus->AdvancedSearch->UnsetSession();
		$this->estado->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->idProyecto->AdvancedSearch->Load();
		$this->nombre->AdvancedSearch->Load();
		$this->descripcion->AdvancedSearch->Load();
		$this->fechaInicio->AdvancedSearch->Load();
		$this->fechaFin->AdvancedSearch->Load();
		$this->fechaCreacion->AdvancedSearch->Load();
		$this->usuarioCreacion->AdvancedSearch->Load();
		$this->usuarioLider->AdvancedSearch->Load();
		$this->usuarioEncargado->AdvancedSearch->Load();
		$this->cliente->AdvancedSearch->Load();
		$this->prioridad->AdvancedSearch->Load();
		$this->fechaUltimoAcceso->AdvancedSearch->Load();
		$this->fechaModificacion->AdvancedSearch->Load();
		$this->usuarioModificacion->AdvancedSearch->Load();
		$this->estatus->AdvancedSearch->Load();
		$this->estado->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->nombre); // nombre
			$this->UpdateSort($this->fechaInicio); // fechaInicio
			$this->UpdateSort($this->fechaFin); // fechaFin
			$this->UpdateSort($this->usuarioLider); // usuarioLider
			$this->UpdateSort($this->usuarioEncargado); // usuarioEncargado
			$this->UpdateSort($this->cliente); // cliente
			$this->UpdateSort($this->estatus); // estatus
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
				$this->nombre->setSort("");
				$this->fechaInicio->setSort("");
				$this->fechaFin->setSort("");
				$this->usuarioLider->setSort("");
				$this->usuarioEncargado->setSort("");
				$this->cliente->setSort("");
				$this->estatus->setSort("");
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

		// "detail_objetivo"
		$item = &$this->ListOptions->Add("detail_objetivo");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'objetivo') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["objetivo_grid"])) $GLOBALS["objetivo_grid"] = new cobjetivo_grid;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$this->ListOptions->Add("details");
			$item->CssStyle = "white-space: nowrap;";
			$item->Visible = $this->ShowMultipleDetails;
			$item->OnLeft = TRUE;
			$item->ShowInButtonGroup = FALSE;
		}

		// Set up detail pages
		$pages = new cSubPages();
		$pages->Add("objetivo");
		$this->DetailPages = $pages;

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
		if ($Security->CanView())
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if ($Security->CanAdd()) {
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
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_objetivo"
		$oListOpt = &$this->ListOptions->Items["detail_objetivo"];
		if ($Security->AllowList(CurrentProjectID() . 'objetivo')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("objetivo", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("objetivolist.php?" . EW_TABLE_SHOW_MASTER . "=proyecto&fk_idProyecto=" . urlencode(strval($this->idProyecto->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["objetivo_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'objetivo')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=objetivo")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "objetivo";
			}
			if ($GLOBALS["objetivo_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'objetivo')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=objetivo")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "objetivo";
			}
			if ($GLOBALS["objetivo_grid"]->DetailAdd && $Security->CanAdd() && $Security->AllowAdd(CurrentProjectID() . 'objetivo')) {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=objetivo")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
				if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
				$DetailCopyTblVar .= "objetivo";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<div class=\"btn-group\">";
			$links = "";
			if ($DetailViewTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			}
			if ($DetailEditTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailEditTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			}
			if ($DetailCopyTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailCopyTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewMasterDetail\" title=\"" . ew_HtmlTitle($Language->Phrase("MultipleMasterDetails")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("MultipleMasterDetails") . "<b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu ewMenu\">". $links . "</ul>";
			}
			$body .= "</div>";

			// Multiple details
			$oListOpt = &$this->ListOptions->Items["details"];
			$oListOpt->Body = $body;
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->idProyecto->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$option = $options["detail"];
		$DetailTableLink = "";
		$item = &$option->Add("detailadd_objetivo");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=objetivo");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["objetivo"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["objetivo"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'objetivo') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "objetivo";
		}

		// Add multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$option->Add("detailsadd");
			$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailTableLink);
			$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $Language->Phrase("AddMasterDetailLink") . "</a>";
			$item->Visible = ($DetailTableLink <> "" && $Security->CanAdd());

			// Hide single master/detail items
			$ar = explode(",", $DetailTableLink);
			$cnt = count($ar);
			for ($i = 0; $i < $cnt; $i++) {
				if ($item = &$option->GetItem("detailadd_" . $ar[$i]))
					$item->Visible = FALSE;
			}
		}
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fproyectolistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fproyectolistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fproyectolist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fproyectolistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		// idProyecto

		$this->idProyecto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idProyecto"]);
		if ($this->idProyecto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idProyecto->AdvancedSearch->SearchOperator = @$_GET["z_idProyecto"];

		// nombre
		$this->nombre->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nombre"]);
		if ($this->nombre->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nombre->AdvancedSearch->SearchOperator = @$_GET["z_nombre"];

		// descripcion
		$this->descripcion->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_descripcion"]);
		if ($this->descripcion->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->descripcion->AdvancedSearch->SearchOperator = @$_GET["z_descripcion"];

		// fechaInicio
		$this->fechaInicio->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fechaInicio"]);
		if ($this->fechaInicio->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fechaInicio->AdvancedSearch->SearchOperator = @$_GET["z_fechaInicio"];

		// fechaFin
		$this->fechaFin->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fechaFin"]);
		if ($this->fechaFin->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fechaFin->AdvancedSearch->SearchOperator = @$_GET["z_fechaFin"];

		// fechaCreacion
		$this->fechaCreacion->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fechaCreacion"]);
		if ($this->fechaCreacion->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fechaCreacion->AdvancedSearch->SearchOperator = @$_GET["z_fechaCreacion"];
		$this->fechaCreacion->AdvancedSearch->SearchCondition = @$_GET["v_fechaCreacion"];
		$this->fechaCreacion->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_fechaCreacion"]);
		if ($this->fechaCreacion->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->fechaCreacion->AdvancedSearch->SearchOperator2 = @$_GET["w_fechaCreacion"];

		// usuarioCreacion
		$this->usuarioCreacion->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_usuarioCreacion"]);
		if ($this->usuarioCreacion->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->usuarioCreacion->AdvancedSearch->SearchOperator = @$_GET["z_usuarioCreacion"];

		// usuarioLider
		$this->usuarioLider->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_usuarioLider"]);
		if ($this->usuarioLider->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->usuarioLider->AdvancedSearch->SearchOperator = @$_GET["z_usuarioLider"];

		// usuarioEncargado
		$this->usuarioEncargado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_usuarioEncargado"]);
		if ($this->usuarioEncargado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->usuarioEncargado->AdvancedSearch->SearchOperator = @$_GET["z_usuarioEncargado"];

		// cliente
		$this->cliente->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_cliente"]);
		if ($this->cliente->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->cliente->AdvancedSearch->SearchOperator = @$_GET["z_cliente"];

		// prioridad
		$this->prioridad->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_prioridad"]);
		if ($this->prioridad->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->prioridad->AdvancedSearch->SearchOperator = @$_GET["z_prioridad"];

		// fechaUltimoAcceso
		$this->fechaUltimoAcceso->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fechaUltimoAcceso"]);
		if ($this->fechaUltimoAcceso->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fechaUltimoAcceso->AdvancedSearch->SearchOperator = @$_GET["z_fechaUltimoAcceso"];

		// fechaModificacion
		$this->fechaModificacion->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fechaModificacion"]);
		if ($this->fechaModificacion->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fechaModificacion->AdvancedSearch->SearchOperator = @$_GET["z_fechaModificacion"];

		// usuarioModificacion
		$this->usuarioModificacion->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_usuarioModificacion"]);
		if ($this->usuarioModificacion->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->usuarioModificacion->AdvancedSearch->SearchOperator = @$_GET["z_usuarioModificacion"];

		// estatus
		$this->estatus->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_estatus"]);
		if ($this->estatus->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->estatus->AdvancedSearch->SearchOperator = @$_GET["z_estatus"];

		// estado
		$this->estado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_estado"]);
		if ($this->estado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->estado->AdvancedSearch->SearchOperator = @$_GET["z_estado"];
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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

			// estatus
			$this->estatus->LinkCustomAttributes = "";
			$this->estatus->HrefValue = "";
			$this->estatus->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// nombre
			$this->nombre->EditAttrs["class"] = "form-control";
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->AdvancedSearch->SearchValue);
			$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

			// fechaInicio
			$this->fechaInicio->EditAttrs["class"] = "form-control";
			$this->fechaInicio->EditCustomAttributes = "";
			$this->fechaInicio->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fechaInicio->AdvancedSearch->SearchValue, 7), 7));
			$this->fechaInicio->PlaceHolder = ew_RemoveHtml($this->fechaInicio->FldCaption());

			// fechaFin
			$this->fechaFin->EditAttrs["class"] = "form-control";
			$this->fechaFin->EditCustomAttributes = "";
			$this->fechaFin->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fechaFin->AdvancedSearch->SearchValue, 7), 7));
			$this->fechaFin->PlaceHolder = ew_RemoveHtml($this->fechaFin->FldCaption());

			// usuarioLider
			$this->usuarioLider->EditAttrs["class"] = "form-control";
			$this->usuarioLider->EditCustomAttributes = "";

			// usuarioEncargado
			$this->usuarioEncargado->EditAttrs["class"] = "form-control";
			$this->usuarioEncargado->EditCustomAttributes = "";

			// cliente
			$this->cliente->EditAttrs["class"] = "form-control";
			$this->cliente->EditCustomAttributes = "";
			if (trim(strval($this->cliente->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idCliente`" . ew_SearchString("=", $this->cliente->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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

			// estatus
			$this->estatus->EditAttrs["class"] = "form-control";
			$this->estatus->EditCustomAttributes = "";
			$this->estatus->EditValue = $this->estatus->Options(TRUE);
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
		$this->idProyecto->AdvancedSearch->Load();
		$this->nombre->AdvancedSearch->Load();
		$this->descripcion->AdvancedSearch->Load();
		$this->fechaInicio->AdvancedSearch->Load();
		$this->fechaFin->AdvancedSearch->Load();
		$this->fechaCreacion->AdvancedSearch->Load();
		$this->usuarioCreacion->AdvancedSearch->Load();
		$this->usuarioLider->AdvancedSearch->Load();
		$this->usuarioEncargado->AdvancedSearch->Load();
		$this->cliente->AdvancedSearch->Load();
		$this->prioridad->AdvancedSearch->Load();
		$this->fechaUltimoAcceso->AdvancedSearch->Load();
		$this->fechaModificacion->AdvancedSearch->Load();
		$this->usuarioModificacion->AdvancedSearch->Load();
		$this->estatus->AdvancedSearch->Load();
		$this->estado->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_proyecto\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_proyecto',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fproyectolist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
		$table = 'proyecto';
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
if (!isset($proyecto_list)) $proyecto_list = new cproyecto_list();

// Page init
$proyecto_list->Page_Init();

// Page main
$proyecto_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$proyecto_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($proyecto->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fproyectolist = new ew_Form("fproyectolist", "list");
fproyectolist.FormKeyCountName = '<?php echo $proyecto_list->FormKeyCountName ?>';

// Form_CustomValidate event
fproyectolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fproyectolist.ValidateRequired = true;
<?php } else { ?>
fproyectolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fproyectolist.Lists["x_usuarioLider"] = {"LinkField":"x_idUsuario","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_apellidos","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fproyectolist.Lists["x_usuarioEncargado"] = {"LinkField":"x_idUsuario","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_apellidos","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fproyectolist.Lists["x_cliente"] = {"LinkField":"x_idCliente","Ajax":true,"AutoFill":false,"DisplayFields":["x_cliente","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fproyectolist.Lists["x_estatus"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fproyectolist.Lists["x_estatus"].Options = <?php echo json_encode($proyecto->estatus->Options()) ?>;

// Form object for search
var CurrentSearchForm = fproyectolistsrch = new ew_Form("fproyectolistsrch");

// Validate function for search
fproyectolistsrch.Validate = function(fobj) {
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
fproyectolistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fproyectolistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fproyectolistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fproyectolistsrch.Lists["x_cliente"] = {"LinkField":"x_idCliente","Ajax":true,"AutoFill":false,"DisplayFields":["x_cliente","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fproyectolistsrch.Lists["x_estatus"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fproyectolistsrch.Lists["x_estatus"].Options = <?php echo json_encode($proyecto->estatus->Options()) ?>;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($proyecto->Export == "") { ?>
<div class="ewToolbar">
<?php if ($proyecto->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($proyecto_list->TotalRecs > 0 && $proyecto_list->ExportOptions->Visible()) { ?>
<?php $proyecto_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($proyecto_list->SearchOptions->Visible()) { ?>
<?php $proyecto_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($proyecto_list->FilterOptions->Visible()) { ?>
<?php $proyecto_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($proyecto->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $proyecto_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($proyecto_list->TotalRecs <= 0)
			$proyecto_list->TotalRecs = $proyecto->SelectRecordCount();
	} else {
		if (!$proyecto_list->Recordset && ($proyecto_list->Recordset = $proyecto_list->LoadRecordset()))
			$proyecto_list->TotalRecs = $proyecto_list->Recordset->RecordCount();
	}
	$proyecto_list->StartRec = 1;
	if ($proyecto_list->DisplayRecs <= 0 || ($proyecto->Export <> "" && $proyecto->ExportAll)) // Display all records
		$proyecto_list->DisplayRecs = $proyecto_list->TotalRecs;
	if (!($proyecto->Export <> "" && $proyecto->ExportAll))
		$proyecto_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$proyecto_list->Recordset = $proyecto_list->LoadRecordset($proyecto_list->StartRec-1, $proyecto_list->DisplayRecs);

	// Set no record found message
	if ($proyecto->CurrentAction == "" && $proyecto_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$proyecto_list->setWarningMessage(ew_DeniedMsg());
		if ($proyecto_list->SearchWhere == "0=101")
			$proyecto_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$proyecto_list->setWarningMessage($Language->Phrase("NoRecord"));
	}

	// Audit trail on search
	if ($proyecto_list->AuditTrailOnSearch && $proyecto_list->Command == "search" && !$proyecto_list->RestoreSearch) {
		$searchparm = ew_ServerVar("QUERY_STRING");
		$searchsql = $proyecto_list->getSessionWhere();
		$proyecto_list->WriteAuditTrailOnSearch($searchparm, $searchsql);
	}
$proyecto_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($proyecto->Export == "" && $proyecto->CurrentAction == "") { ?>
<form name="fproyectolistsrch" id="fproyectolistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($proyecto_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fproyectolistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="proyecto">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$proyecto_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$proyecto->RowType = EW_ROWTYPE_SEARCH;

// Render row
$proyecto->ResetAttrs();
$proyecto_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($proyecto->cliente->Visible) { // cliente ?>
	<div id="xsc_cliente" class="ewCell form-group">
		<label for="x_cliente" class="ewSearchCaption ewLabel"><?php echo $proyecto->cliente->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_cliente" id="z_cliente" value="="></span>
		<span class="ewSearchField">
<select data-table="proyecto" data-field="x_cliente" data-value-separator="<?php echo ew_HtmlEncode(is_array($proyecto->cliente->DisplayValueSeparator) ? json_encode($proyecto->cliente->DisplayValueSeparator) : $proyecto->cliente->DisplayValueSeparator) ?>" id="x_cliente" name="x_cliente"<?php echo $proyecto->cliente->EditAttributes() ?>>
<?php
if (is_array($proyecto->cliente->EditValue)) {
	$arwrk = $proyecto->cliente->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($proyecto->cliente->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
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
	</div>
<?php } ?>
<?php if ($proyecto->estatus->Visible) { // estatus ?>
	<div id="xsc_estatus" class="ewCell form-group">
		<label for="x_estatus" class="ewSearchCaption ewLabel"><?php echo $proyecto->estatus->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_estatus" id="z_estatus" value="="></span>
		<span class="ewSearchField">
<select data-table="proyecto" data-field="x_estatus" data-value-separator="<?php echo ew_HtmlEncode(is_array($proyecto->estatus->DisplayValueSeparator) ? json_encode($proyecto->estatus->DisplayValueSeparator) : $proyecto->estatus->DisplayValueSeparator) ?>" id="x_estatus" name="x_estatus"<?php echo $proyecto->estatus->EditAttributes() ?>>
<?php
if (is_array($proyecto->estatus->EditValue)) {
	$arwrk = $proyecto->estatus->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($proyecto->estatus->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $proyecto->estatus->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($proyecto->estatus->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($proyecto->estatus->CurrentValue) ?>" selected><?php echo $proyecto->estatus->CurrentValue ?></option>
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
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($proyecto_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($proyecto_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $proyecto_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($proyecto_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($proyecto_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($proyecto_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($proyecto_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $proyecto_list->ShowPageHeader(); ?>
<?php
$proyecto_list->ShowMessage();
?>
<?php if ($proyecto_list->TotalRecs > 0 || $proyecto->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<?php if ($proyecto->Export == "") { ?>
<div class="panel-heading ewGridUpperPanel">
<?php if ($proyecto->CurrentAction <> "gridadd" && $proyecto->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($proyecto_list->Pager)) $proyecto_list->Pager = new cPrevNextPager($proyecto_list->StartRec, $proyecto_list->DisplayRecs, $proyecto_list->TotalRecs) ?>
<?php if ($proyecto_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($proyecto_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $proyecto_list->PageUrl() ?>start=<?php echo $proyecto_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($proyecto_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $proyecto_list->PageUrl() ?>start=<?php echo $proyecto_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $proyecto_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($proyecto_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $proyecto_list->PageUrl() ?>start=<?php echo $proyecto_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($proyecto_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $proyecto_list->PageUrl() ?>start=<?php echo $proyecto_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $proyecto_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $proyecto_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $proyecto_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $proyecto_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($proyecto_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="proyecto">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="20"<?php if ($proyecto_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($proyecto_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="ALL"<?php if ($proyecto->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($proyecto_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fproyectolist" id="fproyectolist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($proyecto_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $proyecto_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="proyecto">
<div id="gmp_proyecto" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($proyecto_list->TotalRecs > 0) { ?>
<table id="tbl_proyectolist" class="table ewTable">
<?php echo $proyecto->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$proyecto_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$proyecto_list->RenderListOptions();

// Render list options (header, left)
$proyecto_list->ListOptions->Render("header", "left");
?>
<?php if ($proyecto->nombre->Visible) { // nombre ?>
	<?php if ($proyecto->SortUrl($proyecto->nombre) == "") { ?>
		<th data-name="nombre"><div id="elh_proyecto_nombre" class="proyecto_nombre"><div class="ewTableHeaderCaption"><?php echo $proyecto->nombre->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombre"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $proyecto->SortUrl($proyecto->nombre) ?>',1);"><div id="elh_proyecto_nombre" class="proyecto_nombre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $proyecto->nombre->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($proyecto->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($proyecto->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($proyecto->fechaInicio->Visible) { // fechaInicio ?>
	<?php if ($proyecto->SortUrl($proyecto->fechaInicio) == "") { ?>
		<th data-name="fechaInicio"><div id="elh_proyecto_fechaInicio" class="proyecto_fechaInicio"><div class="ewTableHeaderCaption"><?php echo $proyecto->fechaInicio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechaInicio"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $proyecto->SortUrl($proyecto->fechaInicio) ?>',1);"><div id="elh_proyecto_fechaInicio" class="proyecto_fechaInicio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $proyecto->fechaInicio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($proyecto->fechaInicio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($proyecto->fechaInicio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($proyecto->fechaFin->Visible) { // fechaFin ?>
	<?php if ($proyecto->SortUrl($proyecto->fechaFin) == "") { ?>
		<th data-name="fechaFin"><div id="elh_proyecto_fechaFin" class="proyecto_fechaFin"><div class="ewTableHeaderCaption"><?php echo $proyecto->fechaFin->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechaFin"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $proyecto->SortUrl($proyecto->fechaFin) ?>',1);"><div id="elh_proyecto_fechaFin" class="proyecto_fechaFin">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $proyecto->fechaFin->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($proyecto->fechaFin->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($proyecto->fechaFin->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($proyecto->usuarioLider->Visible) { // usuarioLider ?>
	<?php if ($proyecto->SortUrl($proyecto->usuarioLider) == "") { ?>
		<th data-name="usuarioLider"><div id="elh_proyecto_usuarioLider" class="proyecto_usuarioLider"><div class="ewTableHeaderCaption"><?php echo $proyecto->usuarioLider->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="usuarioLider"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $proyecto->SortUrl($proyecto->usuarioLider) ?>',1);"><div id="elh_proyecto_usuarioLider" class="proyecto_usuarioLider">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $proyecto->usuarioLider->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($proyecto->usuarioLider->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($proyecto->usuarioLider->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($proyecto->usuarioEncargado->Visible) { // usuarioEncargado ?>
	<?php if ($proyecto->SortUrl($proyecto->usuarioEncargado) == "") { ?>
		<th data-name="usuarioEncargado"><div id="elh_proyecto_usuarioEncargado" class="proyecto_usuarioEncargado"><div class="ewTableHeaderCaption"><?php echo $proyecto->usuarioEncargado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="usuarioEncargado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $proyecto->SortUrl($proyecto->usuarioEncargado) ?>',1);"><div id="elh_proyecto_usuarioEncargado" class="proyecto_usuarioEncargado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $proyecto->usuarioEncargado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($proyecto->usuarioEncargado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($proyecto->usuarioEncargado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($proyecto->cliente->Visible) { // cliente ?>
	<?php if ($proyecto->SortUrl($proyecto->cliente) == "") { ?>
		<th data-name="cliente"><div id="elh_proyecto_cliente" class="proyecto_cliente"><div class="ewTableHeaderCaption"><?php echo $proyecto->cliente->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cliente"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $proyecto->SortUrl($proyecto->cliente) ?>',1);"><div id="elh_proyecto_cliente" class="proyecto_cliente">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $proyecto->cliente->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($proyecto->cliente->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($proyecto->cliente->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($proyecto->estatus->Visible) { // estatus ?>
	<?php if ($proyecto->SortUrl($proyecto->estatus) == "") { ?>
		<th data-name="estatus"><div id="elh_proyecto_estatus" class="proyecto_estatus"><div class="ewTableHeaderCaption"><?php echo $proyecto->estatus->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estatus"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $proyecto->SortUrl($proyecto->estatus) ?>',1);"><div id="elh_proyecto_estatus" class="proyecto_estatus">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $proyecto->estatus->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($proyecto->estatus->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($proyecto->estatus->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$proyecto_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($proyecto->ExportAll && $proyecto->Export <> "") {
	$proyecto_list->StopRec = $proyecto_list->TotalRecs;
} else {

	// Set the last record to display
	if ($proyecto_list->TotalRecs > $proyecto_list->StartRec + $proyecto_list->DisplayRecs - 1)
		$proyecto_list->StopRec = $proyecto_list->StartRec + $proyecto_list->DisplayRecs - 1;
	else
		$proyecto_list->StopRec = $proyecto_list->TotalRecs;
}
$proyecto_list->RecCnt = $proyecto_list->StartRec - 1;
if ($proyecto_list->Recordset && !$proyecto_list->Recordset->EOF) {
	$proyecto_list->Recordset->MoveFirst();
	$bSelectLimit = $proyecto_list->UseSelectLimit;
	if (!$bSelectLimit && $proyecto_list->StartRec > 1)
		$proyecto_list->Recordset->Move($proyecto_list->StartRec - 1);
} elseif (!$proyecto->AllowAddDeleteRow && $proyecto_list->StopRec == 0) {
	$proyecto_list->StopRec = $proyecto->GridAddRowCount;
}

// Initialize aggregate
$proyecto->RowType = EW_ROWTYPE_AGGREGATEINIT;
$proyecto->ResetAttrs();
$proyecto_list->RenderRow();
while ($proyecto_list->RecCnt < $proyecto_list->StopRec) {
	$proyecto_list->RecCnt++;
	if (intval($proyecto_list->RecCnt) >= intval($proyecto_list->StartRec)) {
		$proyecto_list->RowCnt++;

		// Set up key count
		$proyecto_list->KeyCount = $proyecto_list->RowIndex;

		// Init row class and style
		$proyecto->ResetAttrs();
		$proyecto->CssClass = "";
		if ($proyecto->CurrentAction == "gridadd") {
		} else {
			$proyecto_list->LoadRowValues($proyecto_list->Recordset); // Load row values
		}
		$proyecto->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$proyecto->RowAttrs = array_merge($proyecto->RowAttrs, array('data-rowindex'=>$proyecto_list->RowCnt, 'id'=>'r' . $proyecto_list->RowCnt . '_proyecto', 'data-rowtype'=>$proyecto->RowType));

		// Render row
		$proyecto_list->RenderRow();

		// Render list options
		$proyecto_list->RenderListOptions();
?>
	<tr<?php echo $proyecto->RowAttributes() ?>>
<?php

// Render list options (body, left)
$proyecto_list->ListOptions->Render("body", "left", $proyecto_list->RowCnt);
?>
	<?php if ($proyecto->nombre->Visible) { // nombre ?>
		<td data-name="nombre"<?php echo $proyecto->nombre->CellAttributes() ?>>
<span id="el<?php echo $proyecto_list->RowCnt ?>_proyecto_nombre" class="proyecto_nombre">
<span<?php echo $proyecto->nombre->ViewAttributes() ?>>
<?php echo $proyecto->nombre->ListViewValue() ?></span>
</span>
<a id="<?php echo $proyecto_list->PageObjName . "_row_" . $proyecto_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($proyecto->fechaInicio->Visible) { // fechaInicio ?>
		<td data-name="fechaInicio"<?php echo $proyecto->fechaInicio->CellAttributes() ?>>
<span id="el<?php echo $proyecto_list->RowCnt ?>_proyecto_fechaInicio" class="proyecto_fechaInicio">
<span<?php echo $proyecto->fechaInicio->ViewAttributes() ?>>
<?php echo $proyecto->fechaInicio->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($proyecto->fechaFin->Visible) { // fechaFin ?>
		<td data-name="fechaFin"<?php echo $proyecto->fechaFin->CellAttributes() ?>>
<span id="el<?php echo $proyecto_list->RowCnt ?>_proyecto_fechaFin" class="proyecto_fechaFin">
<span<?php echo $proyecto->fechaFin->ViewAttributes() ?>>
<?php echo $proyecto->fechaFin->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($proyecto->usuarioLider->Visible) { // usuarioLider ?>
		<td data-name="usuarioLider"<?php echo $proyecto->usuarioLider->CellAttributes() ?>>
<span id="el<?php echo $proyecto_list->RowCnt ?>_proyecto_usuarioLider" class="proyecto_usuarioLider">
<span<?php echo $proyecto->usuarioLider->ViewAttributes() ?>>
<?php echo $proyecto->usuarioLider->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($proyecto->usuarioEncargado->Visible) { // usuarioEncargado ?>
		<td data-name="usuarioEncargado"<?php echo $proyecto->usuarioEncargado->CellAttributes() ?>>
<span id="el<?php echo $proyecto_list->RowCnt ?>_proyecto_usuarioEncargado" class="proyecto_usuarioEncargado">
<span<?php echo $proyecto->usuarioEncargado->ViewAttributes() ?>>
<?php echo $proyecto->usuarioEncargado->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($proyecto->cliente->Visible) { // cliente ?>
		<td data-name="cliente"<?php echo $proyecto->cliente->CellAttributes() ?>>
<span id="el<?php echo $proyecto_list->RowCnt ?>_proyecto_cliente" class="proyecto_cliente">
<span<?php echo $proyecto->cliente->ViewAttributes() ?>>
<?php echo $proyecto->cliente->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($proyecto->estatus->Visible) { // estatus ?>
		<td data-name="estatus"<?php echo $proyecto->estatus->CellAttributes() ?>>
<span id="el<?php echo $proyecto_list->RowCnt ?>_proyecto_estatus" class="proyecto_estatus">
<span<?php echo $proyecto->estatus->ViewAttributes() ?>>
<?php echo $proyecto->estatus->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$proyecto_list->ListOptions->Render("body", "right", $proyecto_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($proyecto->CurrentAction <> "gridadd")
		$proyecto_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($proyecto->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($proyecto_list->Recordset)
	$proyecto_list->Recordset->Close();
?>
<?php if ($proyecto->Export == "") { ?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($proyecto->CurrentAction <> "gridadd" && $proyecto->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($proyecto_list->Pager)) $proyecto_list->Pager = new cPrevNextPager($proyecto_list->StartRec, $proyecto_list->DisplayRecs, $proyecto_list->TotalRecs) ?>
<?php if ($proyecto_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($proyecto_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $proyecto_list->PageUrl() ?>start=<?php echo $proyecto_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($proyecto_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $proyecto_list->PageUrl() ?>start=<?php echo $proyecto_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $proyecto_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($proyecto_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $proyecto_list->PageUrl() ?>start=<?php echo $proyecto_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($proyecto_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $proyecto_list->PageUrl() ?>start=<?php echo $proyecto_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $proyecto_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $proyecto_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $proyecto_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $proyecto_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($proyecto_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="proyecto">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="20"<?php if ($proyecto_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($proyecto_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="ALL"<?php if ($proyecto->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($proyecto_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($proyecto_list->TotalRecs == 0 && $proyecto->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($proyecto_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($proyecto->Export == "") { ?>
<script type="text/javascript">
fproyectolistsrch.Init();
fproyectolistsrch.FilterList = <?php echo $proyecto_list->GetFilterList() ?>;
fproyectolist.Init();
</script>
<?php } ?>
<?php
$proyecto_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($proyecto->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$proyecto_list->Page_Terminate();
?>
