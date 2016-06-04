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

$actividad_list = NULL; // Initialize page object first

class cactividad_list extends cactividad {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}";

	// Table name
	var $TableName = 'actividad';

	// Page object name
	var $PageObjName = 'actividad_list';

	// Grid form hidden field names
	var $FormName = 'factividadlist';
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

		// Table object (actividad)
		if (!isset($GLOBALS["actividad"]) || get_class($GLOBALS["actividad"]) == "cactividad") {
			$GLOBALS["actividad"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["actividad"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "actividadadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "actividaddelete.php";
		$this->MultiUpdateUrl = "actividadupdate.php";

		// Table object (resultado)
		if (!isset($GLOBALS['resultado'])) $GLOBALS['resultado'] = new cresultado();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption factividadlistsrch";

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

		// Create form object
		$objForm = new cFormObj();

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
		$this->idActividad->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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

		// Set up master detail parameters
		$this->SetUpMasterParms();

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

			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$this->CurrentAction = $_GET["a"];

				// Clear inline mode
				if ($this->CurrentAction == "cancel")
					$this->ClearInlineMode();

				// Switch to inline add mode
				if ($this->CurrentAction == "add" || $this->CurrentAction == "copy")
					$this->InlineAddMode();
			} else {
				if (@$_POST["a_list"] <> "") {
					$this->CurrentAction = $_POST["a_list"]; // Get action

					// Insert Inline
					if ($this->CurrentAction == "insert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "add")
						$this->InlineInsert();
				}
			}

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

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Restore filter list
			$this->RestoreFilterList();

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

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "resultado") {
			global $resultado;
			$rsmaster = $resultado->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("resultadolist.php"); // Return to master page
			} else {
				$resultado->LoadListRowValues($rsmaster);
				$resultado->RowType = EW_ROWTYPE_MASTER; // Master row
				$resultado->RenderListRow();
				$rsmaster->Close();
			}
		}

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

	//  Exit inline mode
	function ClearInlineMode() {
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Inline Add mode
	function InlineAddMode() {
		global $Security, $Language;
		if (!$Security->CanAdd())
			$this->Page_Terminate("login.php"); // Return to login page
		$this->CurrentAction = "add";
		$_SESSION[EW_SESSION_INLINE_MODE] = "add"; // Enable inline add
	}

	// Perform update to Inline Add/Copy record
	function InlineInsert() {
		global $Language, $objForm, $gsFormError;
		$this->LoadOldRecord(); // Load old recordset
		$objForm->Index = 0;
		$this->LoadFormValues(); // Get form values

		// Validate form
		if (!$this->ValidateForm()) {
			$this->setFailureMessage($gsFormError); // Set validation error message
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
			return;
		}
		$this->SendEmail = TRUE; // Send email on add success
		if ($this->AddRow($this->OldRecordset)) { // Add record
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up add success message
			$this->ClearInlineMode(); // Clear inline add mode
		} else { // Add failed
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
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
			$this->idActividad->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->idActividad->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->idActividad->AdvancedSearch->ToJSON(), ","); // Field idActividad
		$sFilterList = ew_Concat($sFilterList, $this->avance->AdvancedSearch->ToJSON(), ","); // Field avance
		$sFilterList = ew_Concat($sFilterList, $this->nombre->AdvancedSearch->ToJSON(), ","); // Field nombre
		$sFilterList = ew_Concat($sFilterList, $this->duracion->AdvancedSearch->ToJSON(), ","); // Field duracion
		$sFilterList = ew_Concat($sFilterList, $this->tipoDuracion->AdvancedSearch->ToJSON(), ","); // Field tipoDuracion
		$sFilterList = ew_Concat($sFilterList, $this->fechaInicio->AdvancedSearch->ToJSON(), ","); // Field fechaInicio
		$sFilterList = ew_Concat($sFilterList, $this->fechaFin->AdvancedSearch->ToJSON(), ","); // Field fechaFin
		$sFilterList = ew_Concat($sFilterList, $this->predecesora->AdvancedSearch->ToJSON(), ","); // Field predecesora
		$sFilterList = ew_Concat($sFilterList, $this->recurso->AdvancedSearch->ToJSON(), ","); // Field recurso
		$sFilterList = ew_Concat($sFilterList, $this->estado->AdvancedSearch->ToJSON(), ","); // Field estado
		$sFilterList = ew_Concat($sFilterList, $this->estatus->AdvancedSearch->ToJSON(), ","); // Field estatus
		$sFilterList = ew_Concat($sFilterList, $this->Resultado->AdvancedSearch->ToJSON(), ","); // Field Resultado
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

		// Field idActividad
		$this->idActividad->AdvancedSearch->SearchValue = @$filter["x_idActividad"];
		$this->idActividad->AdvancedSearch->SearchOperator = @$filter["z_idActividad"];
		$this->idActividad->AdvancedSearch->SearchCondition = @$filter["v_idActividad"];
		$this->idActividad->AdvancedSearch->SearchValue2 = @$filter["y_idActividad"];
		$this->idActividad->AdvancedSearch->SearchOperator2 = @$filter["w_idActividad"];
		$this->idActividad->AdvancedSearch->Save();

		// Field avance
		$this->avance->AdvancedSearch->SearchValue = @$filter["x_avance"];
		$this->avance->AdvancedSearch->SearchOperator = @$filter["z_avance"];
		$this->avance->AdvancedSearch->SearchCondition = @$filter["v_avance"];
		$this->avance->AdvancedSearch->SearchValue2 = @$filter["y_avance"];
		$this->avance->AdvancedSearch->SearchOperator2 = @$filter["w_avance"];
		$this->avance->AdvancedSearch->Save();

		// Field nombre
		$this->nombre->AdvancedSearch->SearchValue = @$filter["x_nombre"];
		$this->nombre->AdvancedSearch->SearchOperator = @$filter["z_nombre"];
		$this->nombre->AdvancedSearch->SearchCondition = @$filter["v_nombre"];
		$this->nombre->AdvancedSearch->SearchValue2 = @$filter["y_nombre"];
		$this->nombre->AdvancedSearch->SearchOperator2 = @$filter["w_nombre"];
		$this->nombre->AdvancedSearch->Save();

		// Field duracion
		$this->duracion->AdvancedSearch->SearchValue = @$filter["x_duracion"];
		$this->duracion->AdvancedSearch->SearchOperator = @$filter["z_duracion"];
		$this->duracion->AdvancedSearch->SearchCondition = @$filter["v_duracion"];
		$this->duracion->AdvancedSearch->SearchValue2 = @$filter["y_duracion"];
		$this->duracion->AdvancedSearch->SearchOperator2 = @$filter["w_duracion"];
		$this->duracion->AdvancedSearch->Save();

		// Field tipoDuracion
		$this->tipoDuracion->AdvancedSearch->SearchValue = @$filter["x_tipoDuracion"];
		$this->tipoDuracion->AdvancedSearch->SearchOperator = @$filter["z_tipoDuracion"];
		$this->tipoDuracion->AdvancedSearch->SearchCondition = @$filter["v_tipoDuracion"];
		$this->tipoDuracion->AdvancedSearch->SearchValue2 = @$filter["y_tipoDuracion"];
		$this->tipoDuracion->AdvancedSearch->SearchOperator2 = @$filter["w_tipoDuracion"];
		$this->tipoDuracion->AdvancedSearch->Save();

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

		// Field predecesora
		$this->predecesora->AdvancedSearch->SearchValue = @$filter["x_predecesora"];
		$this->predecesora->AdvancedSearch->SearchOperator = @$filter["z_predecesora"];
		$this->predecesora->AdvancedSearch->SearchCondition = @$filter["v_predecesora"];
		$this->predecesora->AdvancedSearch->SearchValue2 = @$filter["y_predecesora"];
		$this->predecesora->AdvancedSearch->SearchOperator2 = @$filter["w_predecesora"];
		$this->predecesora->AdvancedSearch->Save();

		// Field recurso
		$this->recurso->AdvancedSearch->SearchValue = @$filter["x_recurso"];
		$this->recurso->AdvancedSearch->SearchOperator = @$filter["z_recurso"];
		$this->recurso->AdvancedSearch->SearchCondition = @$filter["v_recurso"];
		$this->recurso->AdvancedSearch->SearchValue2 = @$filter["y_recurso"];
		$this->recurso->AdvancedSearch->SearchOperator2 = @$filter["w_recurso"];
		$this->recurso->AdvancedSearch->Save();

		// Field estado
		$this->estado->AdvancedSearch->SearchValue = @$filter["x_estado"];
		$this->estado->AdvancedSearch->SearchOperator = @$filter["z_estado"];
		$this->estado->AdvancedSearch->SearchCondition = @$filter["v_estado"];
		$this->estado->AdvancedSearch->SearchValue2 = @$filter["y_estado"];
		$this->estado->AdvancedSearch->SearchOperator2 = @$filter["w_estado"];
		$this->estado->AdvancedSearch->Save();

		// Field estatus
		$this->estatus->AdvancedSearch->SearchValue = @$filter["x_estatus"];
		$this->estatus->AdvancedSearch->SearchOperator = @$filter["z_estatus"];
		$this->estatus->AdvancedSearch->SearchCondition = @$filter["v_estatus"];
		$this->estatus->AdvancedSearch->SearchValue2 = @$filter["y_estatus"];
		$this->estatus->AdvancedSearch->SearchOperator2 = @$filter["w_estatus"];
		$this->estatus->AdvancedSearch->Save();

		// Field Resultado
		$this->Resultado->AdvancedSearch->SearchValue = @$filter["x_Resultado"];
		$this->Resultado->AdvancedSearch->SearchOperator = @$filter["z_Resultado"];
		$this->Resultado->AdvancedSearch->SearchCondition = @$filter["v_Resultado"];
		$this->Resultado->AdvancedSearch->SearchValue2 = @$filter["y_Resultado"];
		$this->Resultado->AdvancedSearch->SearchOperator2 = @$filter["w_Resultado"];
		$this->Resultado->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->avance, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->nombre, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->duracion, $arKeywords, $type);
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
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->idActividad); // idActividad
			$this->UpdateSort($this->avance); // avance
			$this->UpdateSort($this->nombre); // nombre
			$this->UpdateSort($this->duracion); // duracion
			$this->UpdateSort($this->tipoDuracion); // tipoDuracion
			$this->UpdateSort($this->fechaInicio); // fechaInicio
			$this->UpdateSort($this->fechaFin); // fechaFin
			$this->UpdateSort($this->predecesora); // predecesora
			$this->UpdateSort($this->recurso); // recurso
			$this->UpdateSort($this->estatus); // estatus
			$this->UpdateSort($this->Resultado); // Resultado
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

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->Resultado->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->idActividad->setSort("");
				$this->avance->setSort("");
				$this->nombre->setSort("");
				$this->duracion->setSort("");
				$this->tipoDuracion->setSort("");
				$this->fechaInicio->setSort("");
				$this->fechaFin->setSort("");
				$this->predecesora->setSort("");
				$this->recurso->setSort("");
				$this->estatus->setSort("");
				$this->Resultado->setSort("");
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

		// Set up row action and key
		if (is_numeric($this->RowIndex) && $this->CurrentMode <> "view") {
			$objForm->Index = $this->RowIndex;
			$ActionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
			$OldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormOldKeyName);
			$KeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormKeyName);
			$BlankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $ActionName . "\" id=\"" . $ActionName . "\" value=\"" . $this->RowAction . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if (($this->CurrentAction == "add" || $this->CurrentAction == "copy") && $this->RowType == EW_ROWTYPE_ADD) { // Inline Add/Copy
			$this->ListOptions->CustomItem = "copy"; // Show copy column only
			$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
			$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
				"<a class=\"ewGridLink ewInlineInsert\" title=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->PageName() . "');\">" . $Language->Phrase("InsertLink") . "</a>&nbsp;" .
				"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("CancelLink") . "</a>" .
				"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"insert\"></div>";
			return;
		}

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

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->idActividad->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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

		// Inline Add
		$item = &$option->Add("inlineadd");
		$item->Body = "<a class=\"ewAddEdit ewInlineAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" href=\"" . ew_HtmlEncode($this->InlineAddUrl) . "\">" .$Language->Phrase("InlineAddLink") . "</a>";
		$item->Visible = ($this->InlineAddUrl <> "" && $Security->CanAdd());
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"factividadlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"factividadlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.factividadlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"factividadlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

	// Load default values
	function LoadDefaultValues() {
		$this->idActividad->CurrentValue = NULL;
		$this->idActividad->OldValue = $this->idActividad->CurrentValue;
		$this->avance->CurrentValue = 1;
		$this->nombre->CurrentValue = NULL;
		$this->nombre->OldValue = $this->nombre->CurrentValue;
		$this->duracion->CurrentValue = 0;
		$this->tipoDuracion->CurrentValue = "Dia";
		$this->fechaInicio->CurrentValue = ew_CurrentDateTime();
		$this->fechaFin->CurrentValue = ew_CurrentDateTime();
		$this->predecesora->CurrentValue = NULL;
		$this->predecesora->OldValue = $this->predecesora->CurrentValue;
		$this->recurso->CurrentValue = NULL;
		$this->recurso->OldValue = $this->recurso->CurrentValue;
		$this->estatus->CurrentValue = "Asignado";
		$this->Resultado->CurrentValue = NULL;
		$this->Resultado->OldValue = $this->Resultado->CurrentValue;
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->idActividad->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->idActividad->setFormValue($objForm->GetValue("x_idActividad"));
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
		if (!$this->estatus->FldIsDetailKey) {
			$this->estatus->setFormValue($objForm->GetValue("x_estatus"));
		}
		if (!$this->Resultado->FldIsDetailKey) {
			$this->Resultado->setFormValue($objForm->GetValue("x_Resultado"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
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
		$this->estatus->CurrentValue = $this->estatus->FormValue;
		$this->Resultado->CurrentValue = $this->Resultado->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("idActividad")) <> "")
			$this->idActividad->CurrentValue = $this->getKey("idActividad"); // idActividad
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

			// idActividad
			$this->idActividad->LinkCustomAttributes = "";
			$this->idActividad->HrefValue = "";
			$this->idActividad->TooltipValue = "";

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

			// estatus
			$this->estatus->LinkCustomAttributes = "";
			$this->estatus->HrefValue = "";
			$this->estatus->TooltipValue = "";

			// Resultado
			$this->Resultado->LinkCustomAttributes = "";
			$this->Resultado->HrefValue = "";
			$this->Resultado->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// idActividad
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
			if (!$GLOBALS["actividad"]->UserIDAllow($GLOBALS["actividad"]->CurrentAction)) $sWhereWrk = $GLOBALS["usuario"]->AddUserIDFilter($sWhereWrk);
			$this->Lookup_Selecting($this->recurso, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->recurso->EditValue = $arwrk;

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

			// Add refer script
			// idActividad

			$this->idActividad->LinkCustomAttributes = "";
			$this->idActividad->HrefValue = "";

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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// avance
		$this->avance->SetDbValueDef($rsnew, $this->avance->CurrentValue, 0, strval($this->avance->CurrentValue) == "");

		// nombre
		$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, "", FALSE);

		// duracion
		$this->duracion->SetDbValueDef($rsnew, $this->duracion->CurrentValue, 0, strval($this->duracion->CurrentValue) == "");

		// tipoDuracion
		$this->tipoDuracion->SetDbValueDef($rsnew, $this->tipoDuracion->CurrentValue, "", strval($this->tipoDuracion->CurrentValue) == "");

		// fechaInicio
		$this->fechaInicio->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechaInicio->CurrentValue, 7), NULL, FALSE);

		// fechaFin
		$this->fechaFin->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechaFin->CurrentValue, 7), NULL, FALSE);

		// predecesora
		$this->predecesora->SetDbValueDef($rsnew, $this->predecesora->CurrentValue, NULL, FALSE);

		// recurso
		$this->recurso->SetDbValueDef($rsnew, $this->recurso->CurrentValue, NULL, FALSE);

		// estatus
		$this->estatus->SetDbValueDef($rsnew, $this->estatus->CurrentValue, "", strval($this->estatus->CurrentValue) == "");

		// Resultado
		$this->Resultado->SetDbValueDef($rsnew, $this->Resultado->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->idActividad->setDbValue($conn->Insert_ID());
				$rsnew['idActividad'] = $this->idActividad->DbValue;
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
		$item->Body = "<button id=\"emf_actividad\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_actividad',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.factividadlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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

		// Export master record
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "resultado") {
			global $resultado;
			if (!isset($resultado)) $resultado = new cresultado;
			$rsmaster = $resultado->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$Doc->Table = &$resultado;
					$resultado->ExportDocument($Doc, $rsmaster, 1, 1);
					$Doc->ExportEmptyRow();
					$Doc->Table = &$this;
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsmaster->Close();
			}
		}
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

			// Update URL
			$this->AddUrl = $this->AddMasterUrl($this->AddUrl);
			$this->InlineAddUrl = $this->AddMasterUrl($this->InlineAddUrl);
			$this->GridAddUrl = $this->AddMasterUrl($this->GridAddUrl);
			$this->GridEditUrl = $this->AddMasterUrl($this->GridEditUrl);

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

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
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'actividad';
		$usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnAdd) return;
		$table = 'actividad';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['idActividad'];

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
if (!isset($actividad_list)) $actividad_list = new cactividad_list();

// Page init
$actividad_list->Page_Init();

// Page main
$actividad_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$actividad_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($actividad->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = factividadlist = new ew_Form("factividadlist", "list");
factividadlist.FormKeyCountName = '<?php echo $actividad_list->FormKeyCountName ?>';

// Validate form
factividadlist.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_estatus");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $actividad->estatus->FldCaption(), $actividad->estatus->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
factividadlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
factividadlist.ValidateRequired = true;
<?php } else { ?>
factividadlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
factividadlist.Lists["x_avance"] = {"LinkField":"x_idAvance","Ajax":true,"AutoFill":false,"DisplayFields":["x_cantidad","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
factividadlist.Lists["x_tipoDuracion"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
factividadlist.Lists["x_tipoDuracion"].Options = <?php echo json_encode($actividad->tipoDuracion->Options()) ?>;
factividadlist.Lists["x_recurso"] = {"LinkField":"x_idUsuario","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_apellidos","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
factividadlist.Lists["x_estatus"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
factividadlist.Lists["x_estatus"].Options = <?php echo json_encode($actividad->estatus->Options()) ?>;
factividadlist.Lists["x_Resultado"] = {"LinkField":"x_idResultado","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
var CurrentSearchForm = factividadlistsrch = new ew_Form("factividadlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($actividad->Export == "") { ?>
<div class="ewToolbar">
<?php if ($actividad->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($actividad_list->TotalRecs > 0 && $actividad_list->ExportOptions->Visible()) { ?>
<?php $actividad_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($actividad_list->SearchOptions->Visible()) { ?>
<?php $actividad_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($actividad_list->FilterOptions->Visible()) { ?>
<?php $actividad_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($actividad->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if (($actividad->Export == "") || (EW_EXPORT_MASTER_RECORD && $actividad->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "resultadolist.php";
if ($actividad_list->DbMasterFilter <> "" && $actividad->getCurrentMasterTable() == "resultado") {
	if ($actividad_list->MasterRecordExists) {
		if ($actividad->getCurrentMasterTable() == $actividad->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php include_once "resultadomaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = $actividad_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($actividad_list->TotalRecs <= 0)
			$actividad_list->TotalRecs = $actividad->SelectRecordCount();
	} else {
		if (!$actividad_list->Recordset && ($actividad_list->Recordset = $actividad_list->LoadRecordset()))
			$actividad_list->TotalRecs = $actividad_list->Recordset->RecordCount();
	}
	$actividad_list->StartRec = 1;
	if ($actividad_list->DisplayRecs <= 0 || ($actividad->Export <> "" && $actividad->ExportAll)) // Display all records
		$actividad_list->DisplayRecs = $actividad_list->TotalRecs;
	if (!($actividad->Export <> "" && $actividad->ExportAll))
		$actividad_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$actividad_list->Recordset = $actividad_list->LoadRecordset($actividad_list->StartRec-1, $actividad_list->DisplayRecs);

	// Set no record found message
	if ($actividad->CurrentAction == "" && $actividad_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$actividad_list->setWarningMessage(ew_DeniedMsg());
		if ($actividad_list->SearchWhere == "0=101")
			$actividad_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$actividad_list->setWarningMessage($Language->Phrase("NoRecord"));
	}

	// Audit trail on search
	if ($actividad_list->AuditTrailOnSearch && $actividad_list->Command == "search" && !$actividad_list->RestoreSearch) {
		$searchparm = ew_ServerVar("QUERY_STRING");
		$searchsql = $actividad_list->getSessionWhere();
		$actividad_list->WriteAuditTrailOnSearch($searchparm, $searchsql);
	}
$actividad_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($actividad->Export == "" && $actividad->CurrentAction == "") { ?>
<form name="factividadlistsrch" id="factividadlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($actividad_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="factividadlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="actividad">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($actividad_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($actividad_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $actividad_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($actividad_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($actividad_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($actividad_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($actividad_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $actividad_list->ShowPageHeader(); ?>
<?php
$actividad_list->ShowMessage();
?>
<?php if ($actividad_list->TotalRecs > 0 || $actividad->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<?php if ($actividad->Export == "") { ?>
<div class="panel-heading ewGridUpperPanel">
<?php if ($actividad->CurrentAction <> "gridadd" && $actividad->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($actividad_list->Pager)) $actividad_list->Pager = new cPrevNextPager($actividad_list->StartRec, $actividad_list->DisplayRecs, $actividad_list->TotalRecs) ?>
<?php if ($actividad_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($actividad_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $actividad_list->PageUrl() ?>start=<?php echo $actividad_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($actividad_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $actividad_list->PageUrl() ?>start=<?php echo $actividad_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $actividad_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($actividad_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $actividad_list->PageUrl() ?>start=<?php echo $actividad_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($actividad_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $actividad_list->PageUrl() ?>start=<?php echo $actividad_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $actividad_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $actividad_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $actividad_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $actividad_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($actividad_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="actividad">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="20"<?php if ($actividad_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($actividad_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="ALL"<?php if ($actividad->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($actividad_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="factividadlist" id="factividadlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($actividad_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $actividad_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="actividad">
<?php if ($actividad->getCurrentMasterTable() == "resultado" && $actividad->CurrentAction <> "") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="resultado">
<input type="hidden" name="fk_idResultado" value="<?php echo $actividad->Resultado->getSessionValue() ?>">
<?php } ?>
<div id="gmp_actividad" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($actividad_list->TotalRecs > 0 || $actividad->CurrentAction == "add" || $actividad->CurrentAction == "copy") { ?>
<table id="tbl_actividadlist" class="table ewTable">
<?php echo $actividad->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$actividad_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$actividad_list->RenderListOptions();

// Render list options (header, left)
$actividad_list->ListOptions->Render("header", "left");
?>
<?php if ($actividad->idActividad->Visible) { // idActividad ?>
	<?php if ($actividad->SortUrl($actividad->idActividad) == "") { ?>
		<th data-name="idActividad"><div id="elh_actividad_idActividad" class="actividad_idActividad"><div class="ewTableHeaderCaption"><?php echo $actividad->idActividad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idActividad"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $actividad->SortUrl($actividad->idActividad) ?>',1);"><div id="elh_actividad_idActividad" class="actividad_idActividad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->idActividad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->idActividad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->idActividad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($actividad->avance->Visible) { // avance ?>
	<?php if ($actividad->SortUrl($actividad->avance) == "") { ?>
		<th data-name="avance"><div id="elh_actividad_avance" class="actividad_avance"><div class="ewTableHeaderCaption"><?php echo $actividad->avance->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="avance"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $actividad->SortUrl($actividad->avance) ?>',1);"><div id="elh_actividad_avance" class="actividad_avance">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->avance->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->avance->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->avance->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($actividad->nombre->Visible) { // nombre ?>
	<?php if ($actividad->SortUrl($actividad->nombre) == "") { ?>
		<th data-name="nombre"><div id="elh_actividad_nombre" class="actividad_nombre"><div class="ewTableHeaderCaption"><?php echo $actividad->nombre->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombre"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $actividad->SortUrl($actividad->nombre) ?>',1);"><div id="elh_actividad_nombre" class="actividad_nombre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->nombre->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($actividad->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($actividad->duracion->Visible) { // duracion ?>
	<?php if ($actividad->SortUrl($actividad->duracion) == "") { ?>
		<th data-name="duracion"><div id="elh_actividad_duracion" class="actividad_duracion"><div class="ewTableHeaderCaption"><?php echo $actividad->duracion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="duracion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $actividad->SortUrl($actividad->duracion) ?>',1);"><div id="elh_actividad_duracion" class="actividad_duracion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->duracion->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($actividad->duracion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->duracion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($actividad->tipoDuracion->Visible) { // tipoDuracion ?>
	<?php if ($actividad->SortUrl($actividad->tipoDuracion) == "") { ?>
		<th data-name="tipoDuracion"><div id="elh_actividad_tipoDuracion" class="actividad_tipoDuracion"><div class="ewTableHeaderCaption"><?php echo $actividad->tipoDuracion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipoDuracion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $actividad->SortUrl($actividad->tipoDuracion) ?>',1);"><div id="elh_actividad_tipoDuracion" class="actividad_tipoDuracion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->tipoDuracion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->tipoDuracion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->tipoDuracion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($actividad->fechaInicio->Visible) { // fechaInicio ?>
	<?php if ($actividad->SortUrl($actividad->fechaInicio) == "") { ?>
		<th data-name="fechaInicio"><div id="elh_actividad_fechaInicio" class="actividad_fechaInicio"><div class="ewTableHeaderCaption"><?php echo $actividad->fechaInicio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechaInicio"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $actividad->SortUrl($actividad->fechaInicio) ?>',1);"><div id="elh_actividad_fechaInicio" class="actividad_fechaInicio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->fechaInicio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->fechaInicio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->fechaInicio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($actividad->fechaFin->Visible) { // fechaFin ?>
	<?php if ($actividad->SortUrl($actividad->fechaFin) == "") { ?>
		<th data-name="fechaFin"><div id="elh_actividad_fechaFin" class="actividad_fechaFin"><div class="ewTableHeaderCaption"><?php echo $actividad->fechaFin->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechaFin"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $actividad->SortUrl($actividad->fechaFin) ?>',1);"><div id="elh_actividad_fechaFin" class="actividad_fechaFin">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->fechaFin->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->fechaFin->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->fechaFin->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($actividad->predecesora->Visible) { // predecesora ?>
	<?php if ($actividad->SortUrl($actividad->predecesora) == "") { ?>
		<th data-name="predecesora"><div id="elh_actividad_predecesora" class="actividad_predecesora"><div class="ewTableHeaderCaption"><?php echo $actividad->predecesora->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="predecesora"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $actividad->SortUrl($actividad->predecesora) ?>',1);"><div id="elh_actividad_predecesora" class="actividad_predecesora">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->predecesora->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->predecesora->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->predecesora->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($actividad->recurso->Visible) { // recurso ?>
	<?php if ($actividad->SortUrl($actividad->recurso) == "") { ?>
		<th data-name="recurso"><div id="elh_actividad_recurso" class="actividad_recurso"><div class="ewTableHeaderCaption"><?php echo $actividad->recurso->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="recurso"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $actividad->SortUrl($actividad->recurso) ?>',1);"><div id="elh_actividad_recurso" class="actividad_recurso">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->recurso->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->recurso->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->recurso->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($actividad->estatus->Visible) { // estatus ?>
	<?php if ($actividad->SortUrl($actividad->estatus) == "") { ?>
		<th data-name="estatus"><div id="elh_actividad_estatus" class="actividad_estatus"><div class="ewTableHeaderCaption"><?php echo $actividad->estatus->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estatus"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $actividad->SortUrl($actividad->estatus) ?>',1);"><div id="elh_actividad_estatus" class="actividad_estatus">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->estatus->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->estatus->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->estatus->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($actividad->Resultado->Visible) { // Resultado ?>
	<?php if ($actividad->SortUrl($actividad->Resultado) == "") { ?>
		<th data-name="Resultado"><div id="elh_actividad_Resultado" class="actividad_Resultado"><div class="ewTableHeaderCaption"><?php echo $actividad->Resultado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Resultado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $actividad->SortUrl($actividad->Resultado) ?>',1);"><div id="elh_actividad_Resultado" class="actividad_Resultado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->Resultado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->Resultado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->Resultado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$actividad_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($actividad->CurrentAction == "add" || $actividad->CurrentAction == "copy") {
		$actividad_list->RowIndex = 0;
		$actividad_list->KeyCount = $actividad_list->RowIndex;
		if ($actividad->CurrentAction == "add")
			$actividad_list->LoadDefaultValues();
		if ($actividad->EventCancelled) // Insert failed
			$actividad_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$actividad->ResetAttrs();
		$actividad->RowAttrs = array_merge($actividad->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_actividad', 'data-rowtype'=>EW_ROWTYPE_ADD));
		$actividad->RowType = EW_ROWTYPE_ADD;

		// Render row
		$actividad_list->RenderRow();

		// Render list options
		$actividad_list->RenderListOptions();
		$actividad_list->StartRowCnt = 0;
?>
	<tr<?php echo $actividad->RowAttributes() ?>>
<?php

// Render list options (body, left)
$actividad_list->ListOptions->Render("body", "left", $actividad_list->RowCnt);
?>
	<?php if ($actividad->idActividad->Visible) { // idActividad ?>
		<td data-name="idActividad">
<input type="hidden" data-table="actividad" data-field="x_idActividad" name="o<?php echo $actividad_list->RowIndex ?>_idActividad" id="o<?php echo $actividad_list->RowIndex ?>_idActividad" value="<?php echo ew_HtmlEncode($actividad->idActividad->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($actividad->avance->Visible) { // avance ?>
		<td data-name="avance">
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_avance" class="form-group actividad_avance">
<select data-table="actividad" data-field="x_avance" data-value-separator="<?php echo ew_HtmlEncode(is_array($actividad->avance->DisplayValueSeparator) ? json_encode($actividad->avance->DisplayValueSeparator) : $actividad->avance->DisplayValueSeparator) ?>" id="x<?php echo $actividad_list->RowIndex ?>_avance" name="x<?php echo $actividad_list->RowIndex ?>_avance"<?php echo $actividad->avance->EditAttributes() ?>>
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
<input type="hidden" name="s_x<?php echo $actividad_list->RowIndex ?>_avance" id="s_x<?php echo $actividad_list->RowIndex ?>_avance" value="<?php echo $actividad->avance->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="actividad" data-field="x_avance" name="o<?php echo $actividad_list->RowIndex ?>_avance" id="o<?php echo $actividad_list->RowIndex ?>_avance" value="<?php echo ew_HtmlEncode($actividad->avance->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($actividad->nombre->Visible) { // nombre ?>
		<td data-name="nombre">
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_nombre" class="form-group actividad_nombre">
<input type="text" data-table="actividad" data-field="x_nombre" name="x<?php echo $actividad_list->RowIndex ?>_nombre" id="x<?php echo $actividad_list->RowIndex ?>_nombre" size="25" maxlength="128" placeholder="<?php echo ew_HtmlEncode($actividad->nombre->getPlaceHolder()) ?>" value="<?php echo $actividad->nombre->EditValue ?>"<?php echo $actividad->nombre->EditAttributes() ?>>
</span>
<input type="hidden" data-table="actividad" data-field="x_nombre" name="o<?php echo $actividad_list->RowIndex ?>_nombre" id="o<?php echo $actividad_list->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($actividad->nombre->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($actividad->duracion->Visible) { // duracion ?>
		<td data-name="duracion">
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_duracion" class="form-group actividad_duracion">
<input type="text" data-table="actividad" data-field="x_duracion" name="x<?php echo $actividad_list->RowIndex ?>_duracion" id="x<?php echo $actividad_list->RowIndex ?>_duracion" size="3" placeholder="<?php echo ew_HtmlEncode($actividad->duracion->getPlaceHolder()) ?>" value="<?php echo $actividad->duracion->EditValue ?>"<?php echo $actividad->duracion->EditAttributes() ?>>
</span>
<input type="hidden" data-table="actividad" data-field="x_duracion" name="o<?php echo $actividad_list->RowIndex ?>_duracion" id="o<?php echo $actividad_list->RowIndex ?>_duracion" value="<?php echo ew_HtmlEncode($actividad->duracion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($actividad->tipoDuracion->Visible) { // tipoDuracion ?>
		<td data-name="tipoDuracion">
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_tipoDuracion" class="form-group actividad_tipoDuracion">
<select data-table="actividad" data-field="x_tipoDuracion" data-value-separator="<?php echo ew_HtmlEncode(is_array($actividad->tipoDuracion->DisplayValueSeparator) ? json_encode($actividad->tipoDuracion->DisplayValueSeparator) : $actividad->tipoDuracion->DisplayValueSeparator) ?>" id="x<?php echo $actividad_list->RowIndex ?>_tipoDuracion" name="x<?php echo $actividad_list->RowIndex ?>_tipoDuracion"<?php echo $actividad->tipoDuracion->EditAttributes() ?>>
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
<input type="hidden" data-table="actividad" data-field="x_tipoDuracion" name="o<?php echo $actividad_list->RowIndex ?>_tipoDuracion" id="o<?php echo $actividad_list->RowIndex ?>_tipoDuracion" value="<?php echo ew_HtmlEncode($actividad->tipoDuracion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($actividad->fechaInicio->Visible) { // fechaInicio ?>
		<td data-name="fechaInicio">
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_fechaInicio" class="form-group actividad_fechaInicio">
<input type="text" data-table="actividad" data-field="x_fechaInicio" data-format="7" name="x<?php echo $actividad_list->RowIndex ?>_fechaInicio" id="x<?php echo $actividad_list->RowIndex ?>_fechaInicio" size="30" placeholder="<?php echo ew_HtmlEncode($actividad->fechaInicio->getPlaceHolder()) ?>" value="<?php echo $actividad->fechaInicio->EditValue ?>"<?php echo $actividad->fechaInicio->EditAttributes() ?>>
<?php if (!$actividad->fechaInicio->ReadOnly && !$actividad->fechaInicio->Disabled && !isset($actividad->fechaInicio->EditAttrs["readonly"]) && !isset($actividad->fechaInicio->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("factividadlist", "x<?php echo $actividad_list->RowIndex ?>_fechaInicio", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-table="actividad" data-field="x_fechaInicio" name="o<?php echo $actividad_list->RowIndex ?>_fechaInicio" id="o<?php echo $actividad_list->RowIndex ?>_fechaInicio" value="<?php echo ew_HtmlEncode($actividad->fechaInicio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($actividad->fechaFin->Visible) { // fechaFin ?>
		<td data-name="fechaFin">
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_fechaFin" class="form-group actividad_fechaFin">
<input type="text" data-table="actividad" data-field="x_fechaFin" data-format="7" name="x<?php echo $actividad_list->RowIndex ?>_fechaFin" id="x<?php echo $actividad_list->RowIndex ?>_fechaFin" size="30" placeholder="<?php echo ew_HtmlEncode($actividad->fechaFin->getPlaceHolder()) ?>" value="<?php echo $actividad->fechaFin->EditValue ?>"<?php echo $actividad->fechaFin->EditAttributes() ?>>
<?php if (!$actividad->fechaFin->ReadOnly && !$actividad->fechaFin->Disabled && !isset($actividad->fechaFin->EditAttrs["readonly"]) && !isset($actividad->fechaFin->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("factividadlist", "x<?php echo $actividad_list->RowIndex ?>_fechaFin", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-table="actividad" data-field="x_fechaFin" name="o<?php echo $actividad_list->RowIndex ?>_fechaFin" id="o<?php echo $actividad_list->RowIndex ?>_fechaFin" value="<?php echo ew_HtmlEncode($actividad->fechaFin->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($actividad->predecesora->Visible) { // predecesora ?>
		<td data-name="predecesora">
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_predecesora" class="form-group actividad_predecesora">
<input type="text" data-table="actividad" data-field="x_predecesora" name="x<?php echo $actividad_list->RowIndex ?>_predecesora" id="x<?php echo $actividad_list->RowIndex ?>_predecesora" size="5" maxlength="5" placeholder="<?php echo ew_HtmlEncode($actividad->predecesora->getPlaceHolder()) ?>" value="<?php echo $actividad->predecesora->EditValue ?>"<?php echo $actividad->predecesora->EditAttributes() ?>>
</span>
<input type="hidden" data-table="actividad" data-field="x_predecesora" name="o<?php echo $actividad_list->RowIndex ?>_predecesora" id="o<?php echo $actividad_list->RowIndex ?>_predecesora" value="<?php echo ew_HtmlEncode($actividad->predecesora->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($actividad->recurso->Visible) { // recurso ?>
		<td data-name="recurso">
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_recurso" class="form-group actividad_recurso">
<select data-table="actividad" data-field="x_recurso" data-value-separator="<?php echo ew_HtmlEncode(is_array($actividad->recurso->DisplayValueSeparator) ? json_encode($actividad->recurso->DisplayValueSeparator) : $actividad->recurso->DisplayValueSeparator) ?>" id="x<?php echo $actividad_list->RowIndex ?>_recurso" name="x<?php echo $actividad_list->RowIndex ?>_recurso"<?php echo $actividad->recurso->EditAttributes() ?>>
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
if (!$GLOBALS["actividad"]->UserIDAllow($GLOBALS["actividad"]->CurrentAction)) $sWhereWrk = $GLOBALS["usuario"]->AddUserIDFilter($sWhereWrk);
$actividad->recurso->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$actividad->recurso->LookupFilters += array("f0" => "`idUsuario` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$actividad->Lookup_Selecting($actividad->recurso, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $actividad->recurso->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $actividad_list->RowIndex ?>_recurso" id="s_x<?php echo $actividad_list->RowIndex ?>_recurso" value="<?php echo $actividad->recurso->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="actividad" data-field="x_recurso" name="o<?php echo $actividad_list->RowIndex ?>_recurso" id="o<?php echo $actividad_list->RowIndex ?>_recurso" value="<?php echo ew_HtmlEncode($actividad->recurso->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($actividad->estatus->Visible) { // estatus ?>
		<td data-name="estatus">
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_estatus" class="form-group actividad_estatus">
<select data-table="actividad" data-field="x_estatus" data-value-separator="<?php echo ew_HtmlEncode(is_array($actividad->estatus->DisplayValueSeparator) ? json_encode($actividad->estatus->DisplayValueSeparator) : $actividad->estatus->DisplayValueSeparator) ?>" id="x<?php echo $actividad_list->RowIndex ?>_estatus" name="x<?php echo $actividad_list->RowIndex ?>_estatus"<?php echo $actividad->estatus->EditAttributes() ?>>
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
<input type="hidden" data-table="actividad" data-field="x_estatus" name="o<?php echo $actividad_list->RowIndex ?>_estatus" id="o<?php echo $actividad_list->RowIndex ?>_estatus" value="<?php echo ew_HtmlEncode($actividad->estatus->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($actividad->Resultado->Visible) { // Resultado ?>
		<td data-name="Resultado">
<?php if ($actividad->Resultado->getSessionValue() <> "") { ?>
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_Resultado" class="form-group actividad_Resultado">
<span<?php echo $actividad->Resultado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $actividad->Resultado->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $actividad_list->RowIndex ?>_Resultado" name="x<?php echo $actividad_list->RowIndex ?>_Resultado" value="<?php echo ew_HtmlEncode($actividad->Resultado->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_Resultado" class="form-group actividad_Resultado">
<select data-table="actividad" data-field="x_Resultado" data-value-separator="<?php echo ew_HtmlEncode(is_array($actividad->Resultado->DisplayValueSeparator) ? json_encode($actividad->Resultado->DisplayValueSeparator) : $actividad->Resultado->DisplayValueSeparator) ?>" id="x<?php echo $actividad_list->RowIndex ?>_Resultado" name="x<?php echo $actividad_list->RowIndex ?>_Resultado"<?php echo $actividad->Resultado->EditAttributes() ?>>
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
<input type="hidden" name="s_x<?php echo $actividad_list->RowIndex ?>_Resultado" id="s_x<?php echo $actividad_list->RowIndex ?>_Resultado" value="<?php echo $actividad->Resultado->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="actividad" data-field="x_Resultado" name="o<?php echo $actividad_list->RowIndex ?>_Resultado" id="o<?php echo $actividad_list->RowIndex ?>_Resultado" value="<?php echo ew_HtmlEncode($actividad->Resultado->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$actividad_list->ListOptions->Render("body", "right", $actividad_list->RowCnt);
?>
<script type="text/javascript">
factividadlist.UpdateOpts(<?php echo $actividad_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($actividad->ExportAll && $actividad->Export <> "") {
	$actividad_list->StopRec = $actividad_list->TotalRecs;
} else {

	// Set the last record to display
	if ($actividad_list->TotalRecs > $actividad_list->StartRec + $actividad_list->DisplayRecs - 1)
		$actividad_list->StopRec = $actividad_list->StartRec + $actividad_list->DisplayRecs - 1;
	else
		$actividad_list->StopRec = $actividad_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($actividad_list->FormKeyCountName) && ($actividad->CurrentAction == "gridadd" || $actividad->CurrentAction == "gridedit" || $actividad->CurrentAction == "F")) {
		$actividad_list->KeyCount = $objForm->GetValue($actividad_list->FormKeyCountName);
		$actividad_list->StopRec = $actividad_list->StartRec + $actividad_list->KeyCount - 1;
	}
}
$actividad_list->RecCnt = $actividad_list->StartRec - 1;
if ($actividad_list->Recordset && !$actividad_list->Recordset->EOF) {
	$actividad_list->Recordset->MoveFirst();
	$bSelectLimit = $actividad_list->UseSelectLimit;
	if (!$bSelectLimit && $actividad_list->StartRec > 1)
		$actividad_list->Recordset->Move($actividad_list->StartRec - 1);
} elseif (!$actividad->AllowAddDeleteRow && $actividad_list->StopRec == 0) {
	$actividad_list->StopRec = $actividad->GridAddRowCount;
}

// Initialize aggregate
$actividad->RowType = EW_ROWTYPE_AGGREGATEINIT;
$actividad->ResetAttrs();
$actividad_list->RenderRow();
while ($actividad_list->RecCnt < $actividad_list->StopRec) {
	$actividad_list->RecCnt++;
	if (intval($actividad_list->RecCnt) >= intval($actividad_list->StartRec)) {
		$actividad_list->RowCnt++;

		// Set up key count
		$actividad_list->KeyCount = $actividad_list->RowIndex;

		// Init row class and style
		$actividad->ResetAttrs();
		$actividad->CssClass = "";
		if ($actividad->CurrentAction == "gridadd") {
			$actividad_list->LoadDefaultValues(); // Load default values
		} else {
			$actividad_list->LoadRowValues($actividad_list->Recordset); // Load row values
		}
		$actividad->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$actividad->RowAttrs = array_merge($actividad->RowAttrs, array('data-rowindex'=>$actividad_list->RowCnt, 'id'=>'r' . $actividad_list->RowCnt . '_actividad', 'data-rowtype'=>$actividad->RowType));

		// Render row
		$actividad_list->RenderRow();

		// Render list options
		$actividad_list->RenderListOptions();
?>
	<tr<?php echo $actividad->RowAttributes() ?>>
<?php

// Render list options (body, left)
$actividad_list->ListOptions->Render("body", "left", $actividad_list->RowCnt);
?>
	<?php if ($actividad->idActividad->Visible) { // idActividad ?>
		<td data-name="idActividad"<?php echo $actividad->idActividad->CellAttributes() ?>>
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_idActividad" class="actividad_idActividad">
<span<?php echo $actividad->idActividad->ViewAttributes() ?>>
<?php echo $actividad->idActividad->ListViewValue() ?></span>
</span>
<a id="<?php echo $actividad_list->PageObjName . "_row_" . $actividad_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($actividad->avance->Visible) { // avance ?>
		<td data-name="avance"<?php echo $actividad->avance->CellAttributes() ?>>
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_avance" class="actividad_avance">
<span<?php echo $actividad->avance->ViewAttributes() ?>>
<?php echo $actividad->avance->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($actividad->nombre->Visible) { // nombre ?>
		<td data-name="nombre"<?php echo $actividad->nombre->CellAttributes() ?>>
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_nombre" class="actividad_nombre">
<span<?php echo $actividad->nombre->ViewAttributes() ?>>
<?php echo $actividad->nombre->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($actividad->duracion->Visible) { // duracion ?>
		<td data-name="duracion"<?php echo $actividad->duracion->CellAttributes() ?>>
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_duracion" class="actividad_duracion">
<span<?php echo $actividad->duracion->ViewAttributes() ?>>
<?php echo $actividad->duracion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($actividad->tipoDuracion->Visible) { // tipoDuracion ?>
		<td data-name="tipoDuracion"<?php echo $actividad->tipoDuracion->CellAttributes() ?>>
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_tipoDuracion" class="actividad_tipoDuracion">
<span<?php echo $actividad->tipoDuracion->ViewAttributes() ?>>
<?php echo $actividad->tipoDuracion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($actividad->fechaInicio->Visible) { // fechaInicio ?>
		<td data-name="fechaInicio"<?php echo $actividad->fechaInicio->CellAttributes() ?>>
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_fechaInicio" class="actividad_fechaInicio">
<span<?php echo $actividad->fechaInicio->ViewAttributes() ?>>
<?php echo $actividad->fechaInicio->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($actividad->fechaFin->Visible) { // fechaFin ?>
		<td data-name="fechaFin"<?php echo $actividad->fechaFin->CellAttributes() ?>>
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_fechaFin" class="actividad_fechaFin">
<span<?php echo $actividad->fechaFin->ViewAttributes() ?>>
<?php echo $actividad->fechaFin->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($actividad->predecesora->Visible) { // predecesora ?>
		<td data-name="predecesora"<?php echo $actividad->predecesora->CellAttributes() ?>>
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_predecesora" class="actividad_predecesora">
<span<?php echo $actividad->predecesora->ViewAttributes() ?>>
<?php echo $actividad->predecesora->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($actividad->recurso->Visible) { // recurso ?>
		<td data-name="recurso"<?php echo $actividad->recurso->CellAttributes() ?>>
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_recurso" class="actividad_recurso">
<span<?php echo $actividad->recurso->ViewAttributes() ?>>
<?php echo $actividad->recurso->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($actividad->estatus->Visible) { // estatus ?>
		<td data-name="estatus"<?php echo $actividad->estatus->CellAttributes() ?>>
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_estatus" class="actividad_estatus">
<span<?php echo $actividad->estatus->ViewAttributes() ?>>
<?php echo $actividad->estatus->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($actividad->Resultado->Visible) { // Resultado ?>
		<td data-name="Resultado"<?php echo $actividad->Resultado->CellAttributes() ?>>
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_Resultado" class="actividad_Resultado">
<span<?php echo $actividad->Resultado->ViewAttributes() ?>>
<?php echo $actividad->Resultado->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$actividad_list->ListOptions->Render("body", "right", $actividad_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($actividad->CurrentAction <> "gridadd")
		$actividad_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($actividad->CurrentAction == "add" || $actividad->CurrentAction == "copy") { ?>
<input type="hidden" name="<?php echo $actividad_list->FormKeyCountName ?>" id="<?php echo $actividad_list->FormKeyCountName ?>" value="<?php echo $actividad_list->KeyCount ?>">
<?php } ?>
<?php if ($actividad->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($actividad_list->Recordset)
	$actividad_list->Recordset->Close();
?>
<?php if ($actividad->Export == "") { ?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($actividad->CurrentAction <> "gridadd" && $actividad->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($actividad_list->Pager)) $actividad_list->Pager = new cPrevNextPager($actividad_list->StartRec, $actividad_list->DisplayRecs, $actividad_list->TotalRecs) ?>
<?php if ($actividad_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($actividad_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $actividad_list->PageUrl() ?>start=<?php echo $actividad_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($actividad_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $actividad_list->PageUrl() ?>start=<?php echo $actividad_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $actividad_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($actividad_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $actividad_list->PageUrl() ?>start=<?php echo $actividad_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($actividad_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $actividad_list->PageUrl() ?>start=<?php echo $actividad_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $actividad_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $actividad_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $actividad_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $actividad_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($actividad_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="actividad">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="20"<?php if ($actividad_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($actividad_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="ALL"<?php if ($actividad->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($actividad_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($actividad_list->TotalRecs == 0 && $actividad->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($actividad_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($actividad->Export == "") { ?>
<script type="text/javascript">
factividadlistsrch.Init();
factividadlistsrch.FilterList = <?php echo $actividad_list->GetFilterList() ?>;
factividadlist.Init();
</script>
<?php } ?>
<?php
$actividad_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($actividad->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$actividad_list->Page_Terminate();
?>
