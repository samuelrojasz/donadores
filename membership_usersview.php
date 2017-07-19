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

$membership_users_view = NULL; // Initialize page object first

class cmembership_users_view extends cmembership_users {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{A9B917F6-72DB-4C37-BB0D-F508A0EFFBF8}";

	// Table name
	var $TableName = 'membership_users';

	// Page object name
	var $PageObjName = 'membership_users_view';

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
		$KeyUrl = "";
		if (@$_GET["memberID"] <> "") {
			$this->RecKey["memberID"] = $_GET["memberID"];
			$KeyUrl .= "&amp;memberID=" . urlencode($this->RecKey["memberID"]);
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
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("membership_userslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
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
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["memberID"] <> "") {
				$this->memberID->setQueryStringValue($_GET["memberID"]);
				$this->RecKey["memberID"] = $this->memberID->QueryStringValue;
			} elseif (@$_POST["memberID"] <> "") {
				$this->memberID->setFormValue($_POST["memberID"]);
				$this->RecKey["memberID"] = $this->memberID->FormValue;
			} else {
				$sReturnUrl = "membership_userslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "membership_userslist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "membership_userslist.php"; // Not page request, return to list
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
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Copy
		$item = &$option->Add("copy");
		$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->CanAdd());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());

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

		// custom1
		$this->custom1->ViewValue = $this->custom1->CurrentValue;
		$this->custom1->ViewCustomAttributes = "";

		// custom2
		$this->custom2->ViewValue = $this->custom2->CurrentValue;
		$this->custom2->ViewCustomAttributes = "";

		// custom3
		$this->custom3->ViewValue = $this->custom3->CurrentValue;
		$this->custom3->ViewCustomAttributes = "";

		// custom4
		$this->custom4->ViewValue = $this->custom4->CurrentValue;
		$this->custom4->ViewCustomAttributes = "";

		// comments
		$this->comments->ViewValue = $this->comments->CurrentValue;
		$this->comments->ViewCustomAttributes = "";

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

			// custom1
			$this->custom1->LinkCustomAttributes = "";
			$this->custom1->HrefValue = "";
			$this->custom1->TooltipValue = "";

			// custom2
			$this->custom2->LinkCustomAttributes = "";
			$this->custom2->HrefValue = "";
			$this->custom2->TooltipValue = "";

			// custom3
			$this->custom3->LinkCustomAttributes = "";
			$this->custom3->HrefValue = "";
			$this->custom3->TooltipValue = "";

			// custom4
			$this->custom4->LinkCustomAttributes = "";
			$this->custom4->HrefValue = "";
			$this->custom4->TooltipValue = "";

			// comments
			$this->comments->LinkCustomAttributes = "";
			$this->comments->HrefValue = "";
			$this->comments->TooltipValue = "";

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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "membership_userslist.php", "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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
if (!isset($membership_users_view)) $membership_users_view = new cmembership_users_view();

// Page init
$membership_users_view->Page_Init();

// Page main
$membership_users_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$membership_users_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fmembership_usersview = new ew_Form("fmembership_usersview", "view");

// Form_CustomValidate event
fmembership_usersview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmembership_usersview.ValidateRequired = true;
<?php } else { ?>
fmembership_usersview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fmembership_usersview.Lists["x_groupID"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $membership_users_view->ExportOptions->Render("body") ?>
<?php
	foreach ($membership_users_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $membership_users_view->ShowPageHeader(); ?>
<?php
$membership_users_view->ShowMessage();
?>
<form name="fmembership_usersview" id="fmembership_usersview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($membership_users_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $membership_users_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="membership_users">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($membership_users->memberID->Visible) { // memberID ?>
	<tr id="r_memberID">
		<td><span id="elh_membership_users_memberID"><?php echo $membership_users->memberID->FldCaption() ?></span></td>
		<td data-name="memberID"<?php echo $membership_users->memberID->CellAttributes() ?>>
<span id="el_membership_users_memberID">
<span<?php echo $membership_users->memberID->ViewAttributes() ?>>
<?php echo $membership_users->memberID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membership_users->passMD5->Visible) { // passMD5 ?>
	<tr id="r_passMD5">
		<td><span id="elh_membership_users_passMD5"><?php echo $membership_users->passMD5->FldCaption() ?></span></td>
		<td data-name="passMD5"<?php echo $membership_users->passMD5->CellAttributes() ?>>
<span id="el_membership_users_passMD5">
<span<?php echo $membership_users->passMD5->ViewAttributes() ?>>
<?php echo $membership_users->passMD5->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membership_users->_email->Visible) { // email ?>
	<tr id="r__email">
		<td><span id="elh_membership_users__email"><?php echo $membership_users->_email->FldCaption() ?></span></td>
		<td data-name="_email"<?php echo $membership_users->_email->CellAttributes() ?>>
<span id="el_membership_users__email">
<span<?php echo $membership_users->_email->ViewAttributes() ?>>
<?php echo $membership_users->_email->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membership_users->signupDate->Visible) { // signupDate ?>
	<tr id="r_signupDate">
		<td><span id="elh_membership_users_signupDate"><?php echo $membership_users->signupDate->FldCaption() ?></span></td>
		<td data-name="signupDate"<?php echo $membership_users->signupDate->CellAttributes() ?>>
<span id="el_membership_users_signupDate">
<span<?php echo $membership_users->signupDate->ViewAttributes() ?>>
<?php echo $membership_users->signupDate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membership_users->groupID->Visible) { // groupID ?>
	<tr id="r_groupID">
		<td><span id="elh_membership_users_groupID"><?php echo $membership_users->groupID->FldCaption() ?></span></td>
		<td data-name="groupID"<?php echo $membership_users->groupID->CellAttributes() ?>>
<span id="el_membership_users_groupID">
<span<?php echo $membership_users->groupID->ViewAttributes() ?>>
<?php echo $membership_users->groupID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membership_users->isBanned->Visible) { // isBanned ?>
	<tr id="r_isBanned">
		<td><span id="elh_membership_users_isBanned"><?php echo $membership_users->isBanned->FldCaption() ?></span></td>
		<td data-name="isBanned"<?php echo $membership_users->isBanned->CellAttributes() ?>>
<span id="el_membership_users_isBanned">
<span<?php echo $membership_users->isBanned->ViewAttributes() ?>>
<?php echo $membership_users->isBanned->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membership_users->isApproved->Visible) { // isApproved ?>
	<tr id="r_isApproved">
		<td><span id="elh_membership_users_isApproved"><?php echo $membership_users->isApproved->FldCaption() ?></span></td>
		<td data-name="isApproved"<?php echo $membership_users->isApproved->CellAttributes() ?>>
<span id="el_membership_users_isApproved">
<span<?php echo $membership_users->isApproved->ViewAttributes() ?>>
<?php echo $membership_users->isApproved->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membership_users->custom1->Visible) { // custom1 ?>
	<tr id="r_custom1">
		<td><span id="elh_membership_users_custom1"><?php echo $membership_users->custom1->FldCaption() ?></span></td>
		<td data-name="custom1"<?php echo $membership_users->custom1->CellAttributes() ?>>
<span id="el_membership_users_custom1">
<span<?php echo $membership_users->custom1->ViewAttributes() ?>>
<?php echo $membership_users->custom1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membership_users->custom2->Visible) { // custom2 ?>
	<tr id="r_custom2">
		<td><span id="elh_membership_users_custom2"><?php echo $membership_users->custom2->FldCaption() ?></span></td>
		<td data-name="custom2"<?php echo $membership_users->custom2->CellAttributes() ?>>
<span id="el_membership_users_custom2">
<span<?php echo $membership_users->custom2->ViewAttributes() ?>>
<?php echo $membership_users->custom2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membership_users->custom3->Visible) { // custom3 ?>
	<tr id="r_custom3">
		<td><span id="elh_membership_users_custom3"><?php echo $membership_users->custom3->FldCaption() ?></span></td>
		<td data-name="custom3"<?php echo $membership_users->custom3->CellAttributes() ?>>
<span id="el_membership_users_custom3">
<span<?php echo $membership_users->custom3->ViewAttributes() ?>>
<?php echo $membership_users->custom3->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membership_users->custom4->Visible) { // custom4 ?>
	<tr id="r_custom4">
		<td><span id="elh_membership_users_custom4"><?php echo $membership_users->custom4->FldCaption() ?></span></td>
		<td data-name="custom4"<?php echo $membership_users->custom4->CellAttributes() ?>>
<span id="el_membership_users_custom4">
<span<?php echo $membership_users->custom4->ViewAttributes() ?>>
<?php echo $membership_users->custom4->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membership_users->comments->Visible) { // comments ?>
	<tr id="r_comments">
		<td><span id="elh_membership_users_comments"><?php echo $membership_users->comments->FldCaption() ?></span></td>
		<td data-name="comments"<?php echo $membership_users->comments->CellAttributes() ?>>
<span id="el_membership_users_comments">
<span<?php echo $membership_users->comments->ViewAttributes() ?>>
<?php echo $membership_users->comments->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membership_users->pass_reset_key->Visible) { // pass_reset_key ?>
	<tr id="r_pass_reset_key">
		<td><span id="elh_membership_users_pass_reset_key"><?php echo $membership_users->pass_reset_key->FldCaption() ?></span></td>
		<td data-name="pass_reset_key"<?php echo $membership_users->pass_reset_key->CellAttributes() ?>>
<span id="el_membership_users_pass_reset_key">
<span<?php echo $membership_users->pass_reset_key->ViewAttributes() ?>>
<?php echo $membership_users->pass_reset_key->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membership_users->pass_reset_expiry->Visible) { // pass_reset_expiry ?>
	<tr id="r_pass_reset_expiry">
		<td><span id="elh_membership_users_pass_reset_expiry"><?php echo $membership_users->pass_reset_expiry->FldCaption() ?></span></td>
		<td data-name="pass_reset_expiry"<?php echo $membership_users->pass_reset_expiry->CellAttributes() ?>>
<span id="el_membership_users_pass_reset_expiry">
<span<?php echo $membership_users->pass_reset_expiry->ViewAttributes() ?>>
<?php echo $membership_users->pass_reset_expiry->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fmembership_usersview.Init();
</script>
<?php
$membership_users_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$membership_users_view->Page_Terminate();
?>
