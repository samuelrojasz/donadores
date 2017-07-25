<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "membership_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$membership_users_list = NULL; // Initialize page object first

class cmembership_users_list extends cmembership_users {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{A9B917F6-72DB-4C37-BB0D-F508A0EFFBF8}";

	// Table name
	var $TableName = 'membership_users';

	// Page object name
	var $PageObjName = 'membership_users_list';

	// Grid form hidden field names
	var $FormName = 'fmembership_userslist';
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
		$hidden = FALSE;
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

		// Table object (membership_users)
		if (!isset($GLOBALS["membership_users"]) || get_class($GLOBALS["membership_users"]) == "cmembership_users") {
			$GLOBALS["membership_users"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["membership_users"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "membership_usersadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "membership_usersdelete.php";
		$this->MultiUpdateUrl = "membership_usersupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'membership_users', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (membership_users)
		if (!isset($UserTable)) {
			$UserTable = new cmembership_users();
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
		$this->FilterOptions->TagClassName = "ewFilterOption fmembership_userslistsrch";

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
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
			if (strval($Security->CurrentUserID()) == "") {
				$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
				$this->Page_Terminate();
			}
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

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
		global $EW_EXPORT, $membership_users;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($membership_users);
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
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

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
			$this->memberID->setFormValue($arrKeyFlds[0]);
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->memberID->AdvancedSearch->ToJSON(), ","); // Field memberID
		$sFilterList = ew_Concat($sFilterList, $this->passMD5->AdvancedSearch->ToJSON(), ","); // Field passMD5
		$sFilterList = ew_Concat($sFilterList, $this->_email->AdvancedSearch->ToJSON(), ","); // Field email
		$sFilterList = ew_Concat($sFilterList, $this->signupDate->AdvancedSearch->ToJSON(), ","); // Field signupDate
		$sFilterList = ew_Concat($sFilterList, $this->groupID->AdvancedSearch->ToJSON(), ","); // Field groupID
		$sFilterList = ew_Concat($sFilterList, $this->isBanned->AdvancedSearch->ToJSON(), ","); // Field isBanned
		$sFilterList = ew_Concat($sFilterList, $this->isApproved->AdvancedSearch->ToJSON(), ","); // Field isApproved
		$sFilterList = ew_Concat($sFilterList, $this->custom1->AdvancedSearch->ToJSON(), ","); // Field custom1
		$sFilterList = ew_Concat($sFilterList, $this->custom2->AdvancedSearch->ToJSON(), ","); // Field custom2
		$sFilterList = ew_Concat($sFilterList, $this->custom3->AdvancedSearch->ToJSON(), ","); // Field custom3
		$sFilterList = ew_Concat($sFilterList, $this->custom4->AdvancedSearch->ToJSON(), ","); // Field custom4
		$sFilterList = ew_Concat($sFilterList, $this->comments->AdvancedSearch->ToJSON(), ","); // Field comments
		$sFilterList = ew_Concat($sFilterList, $this->pass_reset_key->AdvancedSearch->ToJSON(), ","); // Field pass_reset_key
		$sFilterList = ew_Concat($sFilterList, $this->pass_reset_expiry->AdvancedSearch->ToJSON(), ","); // Field pass_reset_expiry
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

		// Field memberID
		$this->memberID->AdvancedSearch->SearchValue = @$filter["x_memberID"];
		$this->memberID->AdvancedSearch->SearchOperator = @$filter["z_memberID"];
		$this->memberID->AdvancedSearch->SearchCondition = @$filter["v_memberID"];
		$this->memberID->AdvancedSearch->SearchValue2 = @$filter["y_memberID"];
		$this->memberID->AdvancedSearch->SearchOperator2 = @$filter["w_memberID"];
		$this->memberID->AdvancedSearch->Save();

		// Field passMD5
		$this->passMD5->AdvancedSearch->SearchValue = @$filter["x_passMD5"];
		$this->passMD5->AdvancedSearch->SearchOperator = @$filter["z_passMD5"];
		$this->passMD5->AdvancedSearch->SearchCondition = @$filter["v_passMD5"];
		$this->passMD5->AdvancedSearch->SearchValue2 = @$filter["y_passMD5"];
		$this->passMD5->AdvancedSearch->SearchOperator2 = @$filter["w_passMD5"];
		$this->passMD5->AdvancedSearch->Save();

		// Field email
		$this->_email->AdvancedSearch->SearchValue = @$filter["x__email"];
		$this->_email->AdvancedSearch->SearchOperator = @$filter["z__email"];
		$this->_email->AdvancedSearch->SearchCondition = @$filter["v__email"];
		$this->_email->AdvancedSearch->SearchValue2 = @$filter["y__email"];
		$this->_email->AdvancedSearch->SearchOperator2 = @$filter["w__email"];
		$this->_email->AdvancedSearch->Save();

		// Field signupDate
		$this->signupDate->AdvancedSearch->SearchValue = @$filter["x_signupDate"];
		$this->signupDate->AdvancedSearch->SearchOperator = @$filter["z_signupDate"];
		$this->signupDate->AdvancedSearch->SearchCondition = @$filter["v_signupDate"];
		$this->signupDate->AdvancedSearch->SearchValue2 = @$filter["y_signupDate"];
		$this->signupDate->AdvancedSearch->SearchOperator2 = @$filter["w_signupDate"];
		$this->signupDate->AdvancedSearch->Save();

		// Field groupID
		$this->groupID->AdvancedSearch->SearchValue = @$filter["x_groupID"];
		$this->groupID->AdvancedSearch->SearchOperator = @$filter["z_groupID"];
		$this->groupID->AdvancedSearch->SearchCondition = @$filter["v_groupID"];
		$this->groupID->AdvancedSearch->SearchValue2 = @$filter["y_groupID"];
		$this->groupID->AdvancedSearch->SearchOperator2 = @$filter["w_groupID"];
		$this->groupID->AdvancedSearch->Save();

		// Field isBanned
		$this->isBanned->AdvancedSearch->SearchValue = @$filter["x_isBanned"];
		$this->isBanned->AdvancedSearch->SearchOperator = @$filter["z_isBanned"];
		$this->isBanned->AdvancedSearch->SearchCondition = @$filter["v_isBanned"];
		$this->isBanned->AdvancedSearch->SearchValue2 = @$filter["y_isBanned"];
		$this->isBanned->AdvancedSearch->SearchOperator2 = @$filter["w_isBanned"];
		$this->isBanned->AdvancedSearch->Save();

		// Field isApproved
		$this->isApproved->AdvancedSearch->SearchValue = @$filter["x_isApproved"];
		$this->isApproved->AdvancedSearch->SearchOperator = @$filter["z_isApproved"];
		$this->isApproved->AdvancedSearch->SearchCondition = @$filter["v_isApproved"];
		$this->isApproved->AdvancedSearch->SearchValue2 = @$filter["y_isApproved"];
		$this->isApproved->AdvancedSearch->SearchOperator2 = @$filter["w_isApproved"];
		$this->isApproved->AdvancedSearch->Save();

		// Field custom1
		$this->custom1->AdvancedSearch->SearchValue = @$filter["x_custom1"];
		$this->custom1->AdvancedSearch->SearchOperator = @$filter["z_custom1"];
		$this->custom1->AdvancedSearch->SearchCondition = @$filter["v_custom1"];
		$this->custom1->AdvancedSearch->SearchValue2 = @$filter["y_custom1"];
		$this->custom1->AdvancedSearch->SearchOperator2 = @$filter["w_custom1"];
		$this->custom1->AdvancedSearch->Save();

		// Field custom2
		$this->custom2->AdvancedSearch->SearchValue = @$filter["x_custom2"];
		$this->custom2->AdvancedSearch->SearchOperator = @$filter["z_custom2"];
		$this->custom2->AdvancedSearch->SearchCondition = @$filter["v_custom2"];
		$this->custom2->AdvancedSearch->SearchValue2 = @$filter["y_custom2"];
		$this->custom2->AdvancedSearch->SearchOperator2 = @$filter["w_custom2"];
		$this->custom2->AdvancedSearch->Save();

		// Field custom3
		$this->custom3->AdvancedSearch->SearchValue = @$filter["x_custom3"];
		$this->custom3->AdvancedSearch->SearchOperator = @$filter["z_custom3"];
		$this->custom3->AdvancedSearch->SearchCondition = @$filter["v_custom3"];
		$this->custom3->AdvancedSearch->SearchValue2 = @$filter["y_custom3"];
		$this->custom3->AdvancedSearch->SearchOperator2 = @$filter["w_custom3"];
		$this->custom3->AdvancedSearch->Save();

		// Field custom4
		$this->custom4->AdvancedSearch->SearchValue = @$filter["x_custom4"];
		$this->custom4->AdvancedSearch->SearchOperator = @$filter["z_custom4"];
		$this->custom4->AdvancedSearch->SearchCondition = @$filter["v_custom4"];
		$this->custom4->AdvancedSearch->SearchValue2 = @$filter["y_custom4"];
		$this->custom4->AdvancedSearch->SearchOperator2 = @$filter["w_custom4"];
		$this->custom4->AdvancedSearch->Save();

		// Field comments
		$this->comments->AdvancedSearch->SearchValue = @$filter["x_comments"];
		$this->comments->AdvancedSearch->SearchOperator = @$filter["z_comments"];
		$this->comments->AdvancedSearch->SearchCondition = @$filter["v_comments"];
		$this->comments->AdvancedSearch->SearchValue2 = @$filter["y_comments"];
		$this->comments->AdvancedSearch->SearchOperator2 = @$filter["w_comments"];
		$this->comments->AdvancedSearch->Save();

		// Field pass_reset_key
		$this->pass_reset_key->AdvancedSearch->SearchValue = @$filter["x_pass_reset_key"];
		$this->pass_reset_key->AdvancedSearch->SearchOperator = @$filter["z_pass_reset_key"];
		$this->pass_reset_key->AdvancedSearch->SearchCondition = @$filter["v_pass_reset_key"];
		$this->pass_reset_key->AdvancedSearch->SearchValue2 = @$filter["y_pass_reset_key"];
		$this->pass_reset_key->AdvancedSearch->SearchOperator2 = @$filter["w_pass_reset_key"];
		$this->pass_reset_key->AdvancedSearch->Save();

		// Field pass_reset_expiry
		$this->pass_reset_expiry->AdvancedSearch->SearchValue = @$filter["x_pass_reset_expiry"];
		$this->pass_reset_expiry->AdvancedSearch->SearchOperator = @$filter["z_pass_reset_expiry"];
		$this->pass_reset_expiry->AdvancedSearch->SearchCondition = @$filter["v_pass_reset_expiry"];
		$this->pass_reset_expiry->AdvancedSearch->SearchValue2 = @$filter["y_pass_reset_expiry"];
		$this->pass_reset_expiry->AdvancedSearch->SearchOperator2 = @$filter["w_pass_reset_expiry"];
		$this->pass_reset_expiry->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->memberID, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->passMD5, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->_email, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->custom1, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->custom2, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->custom3, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->custom4, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->comments, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->pass_reset_key, $arKeywords, $type);
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
			$this->UpdateSort($this->memberID); // memberID
			$this->UpdateSort($this->passMD5); // passMD5
			$this->UpdateSort($this->_email); // email
			$this->UpdateSort($this->signupDate); // signupDate
			$this->UpdateSort($this->groupID); // groupID
			$this->UpdateSort($this->isBanned); // isBanned
			$this->UpdateSort($this->isApproved); // isApproved
			$this->UpdateSort($this->pass_reset_key); // pass_reset_key
			$this->UpdateSort($this->pass_reset_expiry); // pass_reset_expiry
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
				$this->memberID->setSort("");
				$this->passMD5->setSort("");
				$this->_email->setSort("");
				$this->signupDate->setSort("");
				$this->groupID->setSort("");
				$this->isBanned->setSort("");
				$this->isApproved->setSort("");
				$this->pass_reset_key->setSort("");
				$this->pass_reset_expiry->setSort("");
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
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanAdd();
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = FALSE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
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

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete() && $this->ShowOptionLink('delete'))
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt) {
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->memberID->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fmembership_userslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fmembership_userslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fmembership_userslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
					$user = $row['email'];
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fmembership_userslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		$this->memberID->setDbValue($rs->fields('memberID'));
		$this->passMD5->setDbValue($rs->fields('passMD5'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->signupDate->setDbValue($rs->fields('signupDate'));
		$this->groupID->setDbValue($rs->fields('groupID'));
		$this->isBanned->setDbValue($rs->fields('isBanned'));
		$this->isApproved->setDbValue($rs->fields('isApproved'));
		$this->custom1->setDbValue($rs->fields('custom1'));
		$this->custom2->setDbValue($rs->fields('custom2'));
		$this->custom3->setDbValue($rs->fields('custom3'));
		$this->custom4->setDbValue($rs->fields('custom4'));
		$this->comments->setDbValue($rs->fields('comments'));
		$this->pass_reset_key->setDbValue($rs->fields('pass_reset_key'));
		$this->pass_reset_expiry->setDbValue($rs->fields('pass_reset_expiry'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->memberID->DbValue = $row['memberID'];
		$this->passMD5->DbValue = $row['passMD5'];
		$this->_email->DbValue = $row['email'];
		$this->signupDate->DbValue = $row['signupDate'];
		$this->groupID->DbValue = $row['groupID'];
		$this->isBanned->DbValue = $row['isBanned'];
		$this->isApproved->DbValue = $row['isApproved'];
		$this->custom1->DbValue = $row['custom1'];
		$this->custom2->DbValue = $row['custom2'];
		$this->custom3->DbValue = $row['custom3'];
		$this->custom4->DbValue = $row['custom4'];
		$this->comments->DbValue = $row['comments'];
		$this->pass_reset_key->DbValue = $row['pass_reset_key'];
		$this->pass_reset_expiry->DbValue = $row['pass_reset_expiry'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("memberID")) <> "")
			$this->memberID->CurrentValue = $this->getKey("memberID"); // memberID
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
		// memberID
		// passMD5
		// email
		// signupDate
		// groupID
		// isBanned
		// isApproved
		// custom1
		// custom2
		// custom3
		// custom4
		// comments
		// pass_reset_key
		// pass_reset_expiry

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// memberID
		$this->memberID->ViewValue = $this->memberID->CurrentValue;
		$this->memberID->ViewCustomAttributes = "";

		// passMD5
		$this->passMD5->ViewValue = $this->passMD5->CurrentValue;
		$this->passMD5->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// signupDate
		$this->signupDate->ViewValue = $this->signupDate->CurrentValue;
		$this->signupDate->ViewValue = ew_FormatDateTime($this->signupDate->ViewValue, 5);
		$this->signupDate->ViewCustomAttributes = "";

		// groupID
		if ($Security->CanAdmin()) { // System admin
		if (strval($this->groupID->CurrentValue) <> "") {
			$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->groupID->CurrentValue, EW_DATATYPE_NUMBER, "");
		switch (@$gsLanguage) {
			case "es":
				$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
				$sWhereWrk = "";
				break;
		}
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->groupID, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->groupID->ViewValue = $this->groupID->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->groupID->ViewValue = $this->groupID->CurrentValue;
			}
		} else {
			$this->groupID->ViewValue = NULL;
		}
		} else {
			$this->groupID->ViewValue = $Language->Phrase("PasswordMask");
		}
		$this->groupID->ViewCustomAttributes = "";

		// isBanned
		$this->isBanned->ViewValue = $this->isBanned->CurrentValue;
		$this->isBanned->ViewCustomAttributes = "";

		// isApproved
		$this->isApproved->ViewValue = $this->isApproved->CurrentValue;
		$this->isApproved->ViewCustomAttributes = "";

		// pass_reset_key
		$this->pass_reset_key->ViewValue = $this->pass_reset_key->CurrentValue;
		$this->pass_reset_key->ViewCustomAttributes = "";

		// pass_reset_expiry
		$this->pass_reset_expiry->ViewValue = $this->pass_reset_expiry->CurrentValue;
		$this->pass_reset_expiry->ViewCustomAttributes = "";

			// memberID
			$this->memberID->LinkCustomAttributes = "";
			$this->memberID->HrefValue = "";
			$this->memberID->TooltipValue = "";

			// passMD5
			$this->passMD5->LinkCustomAttributes = "";
			$this->passMD5->HrefValue = "";
			$this->passMD5->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// signupDate
			$this->signupDate->LinkCustomAttributes = "";
			$this->signupDate->HrefValue = "";
			$this->signupDate->TooltipValue = "";

			// groupID
			$this->groupID->LinkCustomAttributes = "";
			$this->groupID->HrefValue = "";
			$this->groupID->TooltipValue = "";

			// isBanned
			$this->isBanned->LinkCustomAttributes = "";
			$this->isBanned->HrefValue = "";
			$this->isBanned->TooltipValue = "";

			// isApproved
			$this->isApproved->LinkCustomAttributes = "";
			$this->isApproved->HrefValue = "";
			$this->isApproved->TooltipValue = "";

			// pass_reset_key
			$this->pass_reset_key->LinkCustomAttributes = "";
			$this->pass_reset_key->HrefValue = "";
			$this->pass_reset_key->TooltipValue = "";

			// pass_reset_expiry
			$this->pass_reset_expiry->LinkCustomAttributes = "";
			$this->pass_reset_expiry->HrefValue = "";
			$this->pass_reset_expiry->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Show link optionally based on User ID
	function ShowOptionLink($id = "") {
		global $Security;
		if ($Security->IsLoggedIn() && !$Security->IsAdmin() && !$this->UserIDAllow($id))
			return $Security->IsValidUserID($this->memberID->CurrentValue);
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
if (!isset($membership_users_list)) $membership_users_list = new cmembership_users_list();

// Page init
$membership_users_list->Page_Init();

// Page main
$membership_users_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$membership_users_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fmembership_userslist = new ew_Form("fmembership_userslist", "list");
fmembership_userslist.FormKeyCountName = '<?php echo $membership_users_list->FormKeyCountName ?>';

// Form_CustomValidate event
fmembership_userslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmembership_userslist.ValidateRequired = true;
<?php } else { ?>
fmembership_userslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fmembership_userslist.Lists["x_groupID"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
var CurrentSearchForm = fmembership_userslistsrch = new ew_Form("fmembership_userslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($membership_users_list->TotalRecs > 0 && $membership_users_list->ExportOptions->Visible()) { ?>
<?php $membership_users_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($membership_users_list->SearchOptions->Visible()) { ?>
<?php $membership_users_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($membership_users_list->FilterOptions->Visible()) { ?>
<?php $membership_users_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $membership_users_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($membership_users_list->TotalRecs <= 0)
			$membership_users_list->TotalRecs = $membership_users->SelectRecordCount();
	} else {
		if (!$membership_users_list->Recordset && ($membership_users_list->Recordset = $membership_users_list->LoadRecordset()))
			$membership_users_list->TotalRecs = $membership_users_list->Recordset->RecordCount();
	}
	$membership_users_list->StartRec = 1;
	if ($membership_users_list->DisplayRecs <= 0 || ($membership_users->Export <> "" && $membership_users->ExportAll)) // Display all records
		$membership_users_list->DisplayRecs = $membership_users_list->TotalRecs;
	if (!($membership_users->Export <> "" && $membership_users->ExportAll))
		$membership_users_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$membership_users_list->Recordset = $membership_users_list->LoadRecordset($membership_users_list->StartRec-1, $membership_users_list->DisplayRecs);

	// Set no record found message
	if ($membership_users->CurrentAction == "" && $membership_users_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$membership_users_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($membership_users_list->SearchWhere == "0=101")
			$membership_users_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$membership_users_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$membership_users_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($membership_users->Export == "" && $membership_users->CurrentAction == "") { ?>
<form name="fmembership_userslistsrch" id="fmembership_userslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($membership_users_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fmembership_userslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="membership_users">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($membership_users_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($membership_users_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $membership_users_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($membership_users_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($membership_users_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($membership_users_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($membership_users_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $membership_users_list->ShowPageHeader(); ?>
<?php
$membership_users_list->ShowMessage();
?>
<?php if ($membership_users_list->TotalRecs > 0 || $membership_users->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<form name="fmembership_userslist" id="fmembership_userslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($membership_users_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $membership_users_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="membership_users">
<div id="gmp_membership_users" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($membership_users_list->TotalRecs > 0) { ?>
<table id="tbl_membership_userslist" class="table ewTable">
<?php echo $membership_users->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$membership_users_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$membership_users_list->RenderListOptions();

// Render list options (header, left)
$membership_users_list->ListOptions->Render("header", "left");
?>
<?php if ($membership_users->memberID->Visible) { // memberID ?>
	<?php if ($membership_users->SortUrl($membership_users->memberID) == "") { ?>
		<th data-name="memberID"><div id="elh_membership_users_memberID" class="membership_users_memberID"><div class="ewTableHeaderCaption"><?php echo $membership_users->memberID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="memberID"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $membership_users->SortUrl($membership_users->memberID) ?>',1);"><div id="elh_membership_users_memberID" class="membership_users_memberID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $membership_users->memberID->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($membership_users->memberID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($membership_users->memberID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($membership_users->passMD5->Visible) { // passMD5 ?>
	<?php if ($membership_users->SortUrl($membership_users->passMD5) == "") { ?>
		<th data-name="passMD5"><div id="elh_membership_users_passMD5" class="membership_users_passMD5"><div class="ewTableHeaderCaption"><?php echo $membership_users->passMD5->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="passMD5"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $membership_users->SortUrl($membership_users->passMD5) ?>',1);"><div id="elh_membership_users_passMD5" class="membership_users_passMD5">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $membership_users->passMD5->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($membership_users->passMD5->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($membership_users->passMD5->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($membership_users->_email->Visible) { // email ?>
	<?php if ($membership_users->SortUrl($membership_users->_email) == "") { ?>
		<th data-name="_email"><div id="elh_membership_users__email" class="membership_users__email"><div class="ewTableHeaderCaption"><?php echo $membership_users->_email->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_email"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $membership_users->SortUrl($membership_users->_email) ?>',1);"><div id="elh_membership_users__email" class="membership_users__email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $membership_users->_email->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($membership_users->_email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($membership_users->_email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($membership_users->signupDate->Visible) { // signupDate ?>
	<?php if ($membership_users->SortUrl($membership_users->signupDate) == "") { ?>
		<th data-name="signupDate"><div id="elh_membership_users_signupDate" class="membership_users_signupDate"><div class="ewTableHeaderCaption"><?php echo $membership_users->signupDate->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="signupDate"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $membership_users->SortUrl($membership_users->signupDate) ?>',1);"><div id="elh_membership_users_signupDate" class="membership_users_signupDate">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $membership_users->signupDate->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($membership_users->signupDate->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($membership_users->signupDate->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($membership_users->groupID->Visible) { // groupID ?>
	<?php if ($membership_users->SortUrl($membership_users->groupID) == "") { ?>
		<th data-name="groupID"><div id="elh_membership_users_groupID" class="membership_users_groupID"><div class="ewTableHeaderCaption"><?php echo $membership_users->groupID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="groupID"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $membership_users->SortUrl($membership_users->groupID) ?>',1);"><div id="elh_membership_users_groupID" class="membership_users_groupID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $membership_users->groupID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($membership_users->groupID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($membership_users->groupID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($membership_users->isBanned->Visible) { // isBanned ?>
	<?php if ($membership_users->SortUrl($membership_users->isBanned) == "") { ?>
		<th data-name="isBanned"><div id="elh_membership_users_isBanned" class="membership_users_isBanned"><div class="ewTableHeaderCaption"><?php echo $membership_users->isBanned->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="isBanned"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $membership_users->SortUrl($membership_users->isBanned) ?>',1);"><div id="elh_membership_users_isBanned" class="membership_users_isBanned">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $membership_users->isBanned->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($membership_users->isBanned->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($membership_users->isBanned->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($membership_users->isApproved->Visible) { // isApproved ?>
	<?php if ($membership_users->SortUrl($membership_users->isApproved) == "") { ?>
		<th data-name="isApproved"><div id="elh_membership_users_isApproved" class="membership_users_isApproved"><div class="ewTableHeaderCaption"><?php echo $membership_users->isApproved->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="isApproved"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $membership_users->SortUrl($membership_users->isApproved) ?>',1);"><div id="elh_membership_users_isApproved" class="membership_users_isApproved">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $membership_users->isApproved->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($membership_users->isApproved->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($membership_users->isApproved->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($membership_users->pass_reset_key->Visible) { // pass_reset_key ?>
	<?php if ($membership_users->SortUrl($membership_users->pass_reset_key) == "") { ?>
		<th data-name="pass_reset_key"><div id="elh_membership_users_pass_reset_key" class="membership_users_pass_reset_key"><div class="ewTableHeaderCaption"><?php echo $membership_users->pass_reset_key->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pass_reset_key"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $membership_users->SortUrl($membership_users->pass_reset_key) ?>',1);"><div id="elh_membership_users_pass_reset_key" class="membership_users_pass_reset_key">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $membership_users->pass_reset_key->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($membership_users->pass_reset_key->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($membership_users->pass_reset_key->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($membership_users->pass_reset_expiry->Visible) { // pass_reset_expiry ?>
	<?php if ($membership_users->SortUrl($membership_users->pass_reset_expiry) == "") { ?>
		<th data-name="pass_reset_expiry"><div id="elh_membership_users_pass_reset_expiry" class="membership_users_pass_reset_expiry"><div class="ewTableHeaderCaption"><?php echo $membership_users->pass_reset_expiry->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pass_reset_expiry"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $membership_users->SortUrl($membership_users->pass_reset_expiry) ?>',1);"><div id="elh_membership_users_pass_reset_expiry" class="membership_users_pass_reset_expiry">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $membership_users->pass_reset_expiry->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($membership_users->pass_reset_expiry->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($membership_users->pass_reset_expiry->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$membership_users_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($membership_users->ExportAll && $membership_users->Export <> "") {
	$membership_users_list->StopRec = $membership_users_list->TotalRecs;
} else {

	// Set the last record to display
	if ($membership_users_list->TotalRecs > $membership_users_list->StartRec + $membership_users_list->DisplayRecs - 1)
		$membership_users_list->StopRec = $membership_users_list->StartRec + $membership_users_list->DisplayRecs - 1;
	else
		$membership_users_list->StopRec = $membership_users_list->TotalRecs;
}
$membership_users_list->RecCnt = $membership_users_list->StartRec - 1;
if ($membership_users_list->Recordset && !$membership_users_list->Recordset->EOF) {
	$membership_users_list->Recordset->MoveFirst();
	$bSelectLimit = $membership_users_list->UseSelectLimit;
	if (!$bSelectLimit && $membership_users_list->StartRec > 1)
		$membership_users_list->Recordset->Move($membership_users_list->StartRec - 1);
} elseif (!$membership_users->AllowAddDeleteRow && $membership_users_list->StopRec == 0) {
	$membership_users_list->StopRec = $membership_users->GridAddRowCount;
}

// Initialize aggregate
$membership_users->RowType = EW_ROWTYPE_AGGREGATEINIT;
$membership_users->ResetAttrs();
$membership_users_list->RenderRow();
while ($membership_users_list->RecCnt < $membership_users_list->StopRec) {
	$membership_users_list->RecCnt++;
	if (intval($membership_users_list->RecCnt) >= intval($membership_users_list->StartRec)) {
		$membership_users_list->RowCnt++;

		// Set up key count
		$membership_users_list->KeyCount = $membership_users_list->RowIndex;

		// Init row class and style
		$membership_users->ResetAttrs();
		$membership_users->CssClass = "";
		if ($membership_users->CurrentAction == "gridadd") {
		} else {
			$membership_users_list->LoadRowValues($membership_users_list->Recordset); // Load row values
		}
		$membership_users->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$membership_users->RowAttrs = array_merge($membership_users->RowAttrs, array('data-rowindex'=>$membership_users_list->RowCnt, 'id'=>'r' . $membership_users_list->RowCnt . '_membership_users', 'data-rowtype'=>$membership_users->RowType));

		// Render row
		$membership_users_list->RenderRow();

		// Render list options
		$membership_users_list->RenderListOptions();
?>
	<tr<?php echo $membership_users->RowAttributes() ?>>
<?php

// Render list options (body, left)
$membership_users_list->ListOptions->Render("body", "left", $membership_users_list->RowCnt);
?>
	<?php if ($membership_users->memberID->Visible) { // memberID ?>
		<td data-name="memberID"<?php echo $membership_users->memberID->CellAttributes() ?>>
<span id="el<?php echo $membership_users_list->RowCnt ?>_membership_users_memberID" class="membership_users_memberID">
<span<?php echo $membership_users->memberID->ViewAttributes() ?>>
<?php echo $membership_users->memberID->ListViewValue() ?></span>
</span>
<a id="<?php echo $membership_users_list->PageObjName . "_row_" . $membership_users_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($membership_users->passMD5->Visible) { // passMD5 ?>
		<td data-name="passMD5"<?php echo $membership_users->passMD5->CellAttributes() ?>>
<span id="el<?php echo $membership_users_list->RowCnt ?>_membership_users_passMD5" class="membership_users_passMD5">
<span<?php echo $membership_users->passMD5->ViewAttributes() ?>>
<?php echo $membership_users->passMD5->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($membership_users->_email->Visible) { // email ?>
		<td data-name="_email"<?php echo $membership_users->_email->CellAttributes() ?>>
<span id="el<?php echo $membership_users_list->RowCnt ?>_membership_users__email" class="membership_users__email">
<span<?php echo $membership_users->_email->ViewAttributes() ?>>
<?php echo $membership_users->_email->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($membership_users->signupDate->Visible) { // signupDate ?>
		<td data-name="signupDate"<?php echo $membership_users->signupDate->CellAttributes() ?>>
<span id="el<?php echo $membership_users_list->RowCnt ?>_membership_users_signupDate" class="membership_users_signupDate">
<span<?php echo $membership_users->signupDate->ViewAttributes() ?>>
<?php echo $membership_users->signupDate->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($membership_users->groupID->Visible) { // groupID ?>
		<td data-name="groupID"<?php echo $membership_users->groupID->CellAttributes() ?>>
<span id="el<?php echo $membership_users_list->RowCnt ?>_membership_users_groupID" class="membership_users_groupID">
<span<?php echo $membership_users->groupID->ViewAttributes() ?>>
<?php echo $membership_users->groupID->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($membership_users->isBanned->Visible) { // isBanned ?>
		<td data-name="isBanned"<?php echo $membership_users->isBanned->CellAttributes() ?>>
<span id="el<?php echo $membership_users_list->RowCnt ?>_membership_users_isBanned" class="membership_users_isBanned">
<span<?php echo $membership_users->isBanned->ViewAttributes() ?>>
<?php echo $membership_users->isBanned->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($membership_users->isApproved->Visible) { // isApproved ?>
		<td data-name="isApproved"<?php echo $membership_users->isApproved->CellAttributes() ?>>
<span id="el<?php echo $membership_users_list->RowCnt ?>_membership_users_isApproved" class="membership_users_isApproved">
<span<?php echo $membership_users->isApproved->ViewAttributes() ?>>
<?php echo $membership_users->isApproved->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($membership_users->pass_reset_key->Visible) { // pass_reset_key ?>
		<td data-name="pass_reset_key"<?php echo $membership_users->pass_reset_key->CellAttributes() ?>>
<span id="el<?php echo $membership_users_list->RowCnt ?>_membership_users_pass_reset_key" class="membership_users_pass_reset_key">
<span<?php echo $membership_users->pass_reset_key->ViewAttributes() ?>>
<?php echo $membership_users->pass_reset_key->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($membership_users->pass_reset_expiry->Visible) { // pass_reset_expiry ?>
		<td data-name="pass_reset_expiry"<?php echo $membership_users->pass_reset_expiry->CellAttributes() ?>>
<span id="el<?php echo $membership_users_list->RowCnt ?>_membership_users_pass_reset_expiry" class="membership_users_pass_reset_expiry">
<span<?php echo $membership_users->pass_reset_expiry->ViewAttributes() ?>>
<?php echo $membership_users->pass_reset_expiry->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$membership_users_list->ListOptions->Render("body", "right", $membership_users_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($membership_users->CurrentAction <> "gridadd")
		$membership_users_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($membership_users->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($membership_users_list->Recordset)
	$membership_users_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($membership_users->CurrentAction <> "gridadd" && $membership_users->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($membership_users_list->Pager)) $membership_users_list->Pager = new cPrevNextPager($membership_users_list->StartRec, $membership_users_list->DisplayRecs, $membership_users_list->TotalRecs) ?>
<?php if ($membership_users_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($membership_users_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $membership_users_list->PageUrl() ?>start=<?php echo $membership_users_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($membership_users_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $membership_users_list->PageUrl() ?>start=<?php echo $membership_users_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $membership_users_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($membership_users_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $membership_users_list->PageUrl() ?>start=<?php echo $membership_users_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($membership_users_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $membership_users_list->PageUrl() ?>start=<?php echo $membership_users_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $membership_users_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $membership_users_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $membership_users_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $membership_users_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($membership_users_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($membership_users_list->TotalRecs == 0 && $membership_users->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($membership_users_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fmembership_userslistsrch.Init();
fmembership_userslistsrch.FilterList = <?php echo $membership_users_list->GetFilterList() ?>;
fmembership_userslist.Init();
</script>
<?php
$membership_users_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$membership_users_list->Page_Terminate();
?>
