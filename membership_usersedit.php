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

$membership_users_edit = NULL; // Initialize page object first

class cmembership_users_edit extends cmembership_users {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{A9B917F6-72DB-4C37-BB0D-F508A0EFFBF8}";

	// Table name
	var $TableName = 'membership_users';

	// Page object name
	var $PageObjName = 'membership_users_edit';

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

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

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
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("membership_userslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["memberID"] <> "") {
			$this->memberID->setQueryStringValue($_GET["memberID"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->memberID->CurrentValue == "")
			$this->Page_Terminate("membership_userslist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("membership_userslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
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
		if (!$this->memberID->FldIsDetailKey) {
			$this->memberID->setFormValue($objForm->GetValue("x_memberID"));
		}
		if (!$this->passMD5->FldIsDetailKey) {
			$this->passMD5->setFormValue($objForm->GetValue("x_passMD5"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->signupDate->FldIsDetailKey) {
			$this->signupDate->setFormValue($objForm->GetValue("x_signupDate"));
			$this->signupDate->CurrentValue = ew_UnFormatDateTime($this->signupDate->CurrentValue, 5);
		}
		if (!$this->groupID->FldIsDetailKey) {
			$this->groupID->setFormValue($objForm->GetValue("x_groupID"));
		}
		if (!$this->isBanned->FldIsDetailKey) {
			$this->isBanned->setFormValue($objForm->GetValue("x_isBanned"));
		}
		if (!$this->isApproved->FldIsDetailKey) {
			$this->isApproved->setFormValue($objForm->GetValue("x_isApproved"));
		}
		if (!$this->custom1->FldIsDetailKey) {
			$this->custom1->setFormValue($objForm->GetValue("x_custom1"));
		}
		if (!$this->custom2->FldIsDetailKey) {
			$this->custom2->setFormValue($objForm->GetValue("x_custom2"));
		}
		if (!$this->custom3->FldIsDetailKey) {
			$this->custom3->setFormValue($objForm->GetValue("x_custom3"));
		}
		if (!$this->custom4->FldIsDetailKey) {
			$this->custom4->setFormValue($objForm->GetValue("x_custom4"));
		}
		if (!$this->comments->FldIsDetailKey) {
			$this->comments->setFormValue($objForm->GetValue("x_comments"));
		}
		if (!$this->pass_reset_key->FldIsDetailKey) {
			$this->pass_reset_key->setFormValue($objForm->GetValue("x_pass_reset_key"));
		}
		if (!$this->pass_reset_expiry->FldIsDetailKey) {
			$this->pass_reset_expiry->setFormValue($objForm->GetValue("x_pass_reset_expiry"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->memberID->CurrentValue = $this->memberID->FormValue;
		$this->passMD5->CurrentValue = $this->passMD5->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->signupDate->CurrentValue = $this->signupDate->FormValue;
		$this->signupDate->CurrentValue = ew_UnFormatDateTime($this->signupDate->CurrentValue, 5);
		$this->groupID->CurrentValue = $this->groupID->FormValue;
		$this->isBanned->CurrentValue = $this->isBanned->FormValue;
		$this->isApproved->CurrentValue = $this->isApproved->FormValue;
		$this->custom1->CurrentValue = $this->custom1->FormValue;
		$this->custom2->CurrentValue = $this->custom2->FormValue;
		$this->custom3->CurrentValue = $this->custom3->FormValue;
		$this->custom4->CurrentValue = $this->custom4->FormValue;
		$this->comments->CurrentValue = $this->comments->FormValue;
		$this->pass_reset_key->CurrentValue = $this->pass_reset_key->FormValue;
		$this->pass_reset_expiry->CurrentValue = $this->pass_reset_expiry->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// memberID
			$this->memberID->EditAttrs["class"] = "form-control";
			$this->memberID->EditCustomAttributes = "";
			$this->memberID->EditValue = $this->memberID->CurrentValue;
			$this->memberID->ViewCustomAttributes = "";

			// passMD5
			$this->passMD5->EditAttrs["class"] = "form-control ewPasswordStrength";
			$this->passMD5->EditCustomAttributes = "";
			$this->passMD5->EditValue = ew_HtmlEncode($this->passMD5->CurrentValue);
			$this->passMD5->PlaceHolder = ew_RemoveHtml($this->passMD5->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// signupDate
			$this->signupDate->EditAttrs["class"] = "form-control";
			$this->signupDate->EditCustomAttributes = "";
			$this->signupDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->signupDate->CurrentValue, 5));
			$this->signupDate->PlaceHolder = ew_RemoveHtml($this->signupDate->FldCaption());

			// groupID
			$this->groupID->EditAttrs["class"] = "form-control";
			$this->groupID->EditCustomAttributes = "";
			if (!$Security->CanAdmin()) { // System admin
				$this->groupID->EditValue = $Language->Phrase("PasswordMask");
			} else {
			if (trim(strval($this->groupID->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->groupID->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			switch (@$gsLanguage) {
				case "es":
					$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `userlevels`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `userlevels`";
					$sWhereWrk = "";
					break;
			}
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->groupID, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->groupID->EditValue = $arwrk;
			}

			// isBanned
			$this->isBanned->EditAttrs["class"] = "form-control";
			$this->isBanned->EditCustomAttributes = "";
			$this->isBanned->EditValue = ew_HtmlEncode($this->isBanned->CurrentValue);
			$this->isBanned->PlaceHolder = ew_RemoveHtml($this->isBanned->FldCaption());

			// isApproved
			$this->isApproved->EditAttrs["class"] = "form-control";
			$this->isApproved->EditCustomAttributes = "";
			$this->isApproved->EditValue = ew_HtmlEncode($this->isApproved->CurrentValue);
			$this->isApproved->PlaceHolder = ew_RemoveHtml($this->isApproved->FldCaption());

			// custom1
			$this->custom1->EditAttrs["class"] = "form-control";
			$this->custom1->EditCustomAttributes = "";
			$this->custom1->EditValue = ew_HtmlEncode($this->custom1->CurrentValue);
			$this->custom1->PlaceHolder = ew_RemoveHtml($this->custom1->FldCaption());

			// custom2
			$this->custom2->EditAttrs["class"] = "form-control";
			$this->custom2->EditCustomAttributes = "";
			$this->custom2->EditValue = ew_HtmlEncode($this->custom2->CurrentValue);
			$this->custom2->PlaceHolder = ew_RemoveHtml($this->custom2->FldCaption());

			// custom3
			$this->custom3->EditAttrs["class"] = "form-control";
			$this->custom3->EditCustomAttributes = "";
			$this->custom3->EditValue = ew_HtmlEncode($this->custom3->CurrentValue);
			$this->custom3->PlaceHolder = ew_RemoveHtml($this->custom3->FldCaption());

			// custom4
			$this->custom4->EditAttrs["class"] = "form-control";
			$this->custom4->EditCustomAttributes = "";
			$this->custom4->EditValue = ew_HtmlEncode($this->custom4->CurrentValue);
			$this->custom4->PlaceHolder = ew_RemoveHtml($this->custom4->FldCaption());

			// comments
			$this->comments->EditAttrs["class"] = "form-control";
			$this->comments->EditCustomAttributes = "";
			$this->comments->EditValue = ew_HtmlEncode($this->comments->CurrentValue);
			$this->comments->PlaceHolder = ew_RemoveHtml($this->comments->FldCaption());

			// pass_reset_key
			$this->pass_reset_key->EditAttrs["class"] = "form-control";
			$this->pass_reset_key->EditCustomAttributes = "";
			$this->pass_reset_key->EditValue = ew_HtmlEncode($this->pass_reset_key->CurrentValue);
			$this->pass_reset_key->PlaceHolder = ew_RemoveHtml($this->pass_reset_key->FldCaption());

			// pass_reset_expiry
			$this->pass_reset_expiry->EditAttrs["class"] = "form-control";
			$this->pass_reset_expiry->EditCustomAttributes = "";
			$this->pass_reset_expiry->EditValue = ew_HtmlEncode($this->pass_reset_expiry->CurrentValue);
			$this->pass_reset_expiry->PlaceHolder = ew_RemoveHtml($this->pass_reset_expiry->FldCaption());

			// Edit refer script
			// memberID

			$this->memberID->HrefValue = "";

			// passMD5
			$this->passMD5->HrefValue = "";

			// email
			$this->_email->HrefValue = "";

			// signupDate
			$this->signupDate->HrefValue = "";

			// groupID
			$this->groupID->HrefValue = "";

			// isBanned
			$this->isBanned->HrefValue = "";

			// isApproved
			$this->isApproved->HrefValue = "";

			// custom1
			$this->custom1->HrefValue = "";

			// custom2
			$this->custom2->HrefValue = "";

			// custom3
			$this->custom3->HrefValue = "";

			// custom4
			$this->custom4->HrefValue = "";

			// comments
			$this->comments->HrefValue = "";

			// pass_reset_key
			$this->pass_reset_key->HrefValue = "";

			// pass_reset_expiry
			$this->pass_reset_expiry->HrefValue = "";
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
		if (!$this->memberID->FldIsDetailKey && !is_null($this->memberID->FormValue) && $this->memberID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->memberID->FldCaption(), $this->memberID->ReqErrMsg));
		}
		if (!ew_CheckDate($this->signupDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->signupDate->FldErrMsg());
		}
		if (!ew_CheckInteger($this->isBanned->FormValue)) {
			ew_AddMessage($gsFormError, $this->isBanned->FldErrMsg());
		}
		if (!ew_CheckInteger($this->isApproved->FormValue)) {
			ew_AddMessage($gsFormError, $this->isApproved->FldErrMsg());
		}
		if (!ew_CheckInteger($this->pass_reset_expiry->FormValue)) {
			ew_AddMessage($gsFormError, $this->pass_reset_expiry->FldErrMsg());
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

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// memberID
			// passMD5

			$this->passMD5->SetDbValueDef($rsnew, $this->passMD5->CurrentValue, NULL, $this->passMD5->ReadOnly || (EW_ENCRYPTED_PASSWORD && $rs->fields('passMD5') == $this->passMD5->CurrentValue));

			// email
			$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, NULL, $this->_email->ReadOnly);

			// signupDate
			$this->signupDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->signupDate->CurrentValue, 5), NULL, $this->signupDate->ReadOnly);

			// groupID
			if ($Security->CanAdmin()) { // System admin
			$this->groupID->SetDbValueDef($rsnew, $this->groupID->CurrentValue, NULL, $this->groupID->ReadOnly);
			}

			// isBanned
			$this->isBanned->SetDbValueDef($rsnew, $this->isBanned->CurrentValue, NULL, $this->isBanned->ReadOnly);

			// isApproved
			$this->isApproved->SetDbValueDef($rsnew, $this->isApproved->CurrentValue, NULL, $this->isApproved->ReadOnly);

			// custom1
			$this->custom1->SetDbValueDef($rsnew, $this->custom1->CurrentValue, NULL, $this->custom1->ReadOnly);

			// custom2
			$this->custom2->SetDbValueDef($rsnew, $this->custom2->CurrentValue, NULL, $this->custom2->ReadOnly);

			// custom3
			$this->custom3->SetDbValueDef($rsnew, $this->custom3->CurrentValue, NULL, $this->custom3->ReadOnly);

			// custom4
			$this->custom4->SetDbValueDef($rsnew, $this->custom4->CurrentValue, NULL, $this->custom4->ReadOnly);

			// comments
			$this->comments->SetDbValueDef($rsnew, $this->comments->CurrentValue, NULL, $this->comments->ReadOnly);

			// pass_reset_key
			$this->pass_reset_key->SetDbValueDef($rsnew, $this->pass_reset_key->CurrentValue, NULL, $this->pass_reset_key->ReadOnly);

			// pass_reset_expiry
			$this->pass_reset_expiry->SetDbValueDef($rsnew, $this->pass_reset_expiry->CurrentValue, NULL, $this->pass_reset_expiry->ReadOnly);

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
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "membership_userslist.php", "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
if (!isset($membership_users_edit)) $membership_users_edit = new cmembership_users_edit();

// Page init
$membership_users_edit->Page_Init();

// Page main
$membership_users_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$membership_users_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fmembership_usersedit = new ew_Form("fmembership_usersedit", "edit");

// Validate form
fmembership_usersedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_memberID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $membership_users->memberID->FldCaption(), $membership_users->memberID->ReqErrMsg)) ?>");
			if ($(fobj.x_passMD5).hasClass("ewPasswordStrength") && !$(fobj.x_passMD5).data("validated"))
				return this.OnError(fobj.x_passMD5, ewLanguage.Phrase("PasswordTooSimple"));
			elm = this.GetElements("x" + infix + "_signupDate");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($membership_users->signupDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_isBanned");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($membership_users->isBanned->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_isApproved");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($membership_users->isApproved->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pass_reset_expiry");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($membership_users->pass_reset_expiry->FldErrMsg()) ?>");

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
fmembership_usersedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmembership_usersedit.ValidateRequired = true;
<?php } else { ?>
fmembership_usersedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fmembership_usersedit.Lists["x_groupID"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

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
<?php $membership_users_edit->ShowPageHeader(); ?>
<?php
$membership_users_edit->ShowMessage();
?>
<form name="fmembership_usersedit" id="fmembership_usersedit" class="<?php echo $membership_users_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($membership_users_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $membership_users_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="membership_users">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<!-- Fields to prevent google autofill -->
<input class="hidden" type="text" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<input class="hidden" type="password" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<div>
<?php if ($membership_users->memberID->Visible) { // memberID ?>
	<div id="r_memberID" class="form-group">
		<label id="elh_membership_users_memberID" for="x_memberID" class="col-sm-2 control-label ewLabel"><?php echo $membership_users->memberID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $membership_users->memberID->CellAttributes() ?>>
<span id="el_membership_users_memberID">
<span<?php echo $membership_users->memberID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $membership_users->memberID->EditValue ?></p></span>
</span>
<input type="hidden" data-table="membership_users" data-field="x_memberID" name="x_memberID" id="x_memberID" value="<?php echo ew_HtmlEncode($membership_users->memberID->CurrentValue) ?>">
<?php echo $membership_users->memberID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membership_users->passMD5->Visible) { // passMD5 ?>
	<div id="r_passMD5" class="form-group">
		<label id="elh_membership_users_passMD5" for="x_passMD5" class="col-sm-2 control-label ewLabel"><?php echo $membership_users->passMD5->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membership_users->passMD5->CellAttributes() ?>>
<span id="el_membership_users_passMD5">
<div class="input-group" id="ig_x_passMD5">
<input type="text" data-password-strength="pst_x_passMD5" data-password-generated="pgt_x_passMD5" data-table="membership_users" data-field="x_passMD5" name="x_passMD5" id="x_passMD5" value="<?php echo $membership_users->passMD5->EditValue ?>" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($membership_users->passMD5->getPlaceHolder()) ?>"<?php echo $membership_users->passMD5->EditAttributes() ?>>
<span class="input-group-btn">
	<button type="button" class="btn btn-default ewPasswordGenerator" title="<?php echo ew_HtmlTitle($Language->Phrase("GeneratePassword")) ?>" data-password-field="x_passMD5" data-password-confirm="c_passMD5" data-password-strength="pst_x_passMD5" data-password-generated="pgt_x_passMD5"><?php echo $Language->Phrase("GeneratePassword") ?></button>
</span>
</div>
<span class="help-block" id="pgt_x_passMD5" style="display: none;"></span>
<div class="progress ewPasswordStrengthBar" id="pst_x_passMD5" style="display: none;">
	<div class="progress-bar" role="progressbar"></div>
</div>
</span>
<?php echo $membership_users->passMD5->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membership_users->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_membership_users__email" for="x__email" class="col-sm-2 control-label ewLabel"><?php echo $membership_users->_email->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membership_users->_email->CellAttributes() ?>>
<span id="el_membership_users__email">
<input type="text" data-table="membership_users" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($membership_users->_email->getPlaceHolder()) ?>" value="<?php echo $membership_users->_email->EditValue ?>"<?php echo $membership_users->_email->EditAttributes() ?>>
</span>
<?php echo $membership_users->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membership_users->signupDate->Visible) { // signupDate ?>
	<div id="r_signupDate" class="form-group">
		<label id="elh_membership_users_signupDate" for="x_signupDate" class="col-sm-2 control-label ewLabel"><?php echo $membership_users->signupDate->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membership_users->signupDate->CellAttributes() ?>>
<span id="el_membership_users_signupDate">
<input type="text" data-table="membership_users" data-field="x_signupDate" data-format="5" name="x_signupDate" id="x_signupDate" placeholder="<?php echo ew_HtmlEncode($membership_users->signupDate->getPlaceHolder()) ?>" value="<?php echo $membership_users->signupDate->EditValue ?>"<?php echo $membership_users->signupDate->EditAttributes() ?>>
</span>
<?php echo $membership_users->signupDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membership_users->groupID->Visible) { // groupID ?>
	<div id="r_groupID" class="form-group">
		<label id="elh_membership_users_groupID" for="x_groupID" class="col-sm-2 control-label ewLabel"><?php echo $membership_users->groupID->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membership_users->groupID->CellAttributes() ?>>
<?php if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin ?>
<span id="el_membership_users_groupID">
<p class="form-control-static"><?php echo $membership_users->groupID->EditValue ?></p>
</span>
<?php } else { ?>
<span id="el_membership_users_groupID">
<select data-table="membership_users" data-field="x_groupID" data-value-separator="<?php echo ew_HtmlEncode(is_array($membership_users->groupID->DisplayValueSeparator) ? json_encode($membership_users->groupID->DisplayValueSeparator) : $membership_users->groupID->DisplayValueSeparator) ?>" id="x_groupID" name="x_groupID"<?php echo $membership_users->groupID->EditAttributes() ?>>
<?php
if (is_array($membership_users->groupID->EditValue)) {
	$arwrk = $membership_users->groupID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($membership_users->groupID->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $membership_users->groupID->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($membership_users->groupID->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($membership_users->groupID->CurrentValue) ?>" selected><?php echo $membership_users->groupID->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
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
$membership_users->groupID->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$membership_users->groupID->LookupFilters += array("f0" => "`userlevelid` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$membership_users->Lookup_Selecting($membership_users->groupID, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $membership_users->groupID->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_groupID" id="s_x_groupID" value="<?php echo $membership_users->groupID->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php echo $membership_users->groupID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membership_users->isBanned->Visible) { // isBanned ?>
	<div id="r_isBanned" class="form-group">
		<label id="elh_membership_users_isBanned" for="x_isBanned" class="col-sm-2 control-label ewLabel"><?php echo $membership_users->isBanned->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membership_users->isBanned->CellAttributes() ?>>
<span id="el_membership_users_isBanned">
<input type="text" data-table="membership_users" data-field="x_isBanned" name="x_isBanned" id="x_isBanned" size="30" placeholder="<?php echo ew_HtmlEncode($membership_users->isBanned->getPlaceHolder()) ?>" value="<?php echo $membership_users->isBanned->EditValue ?>"<?php echo $membership_users->isBanned->EditAttributes() ?>>
</span>
<?php echo $membership_users->isBanned->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membership_users->isApproved->Visible) { // isApproved ?>
	<div id="r_isApproved" class="form-group">
		<label id="elh_membership_users_isApproved" for="x_isApproved" class="col-sm-2 control-label ewLabel"><?php echo $membership_users->isApproved->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membership_users->isApproved->CellAttributes() ?>>
<span id="el_membership_users_isApproved">
<input type="text" data-table="membership_users" data-field="x_isApproved" name="x_isApproved" id="x_isApproved" size="30" placeholder="<?php echo ew_HtmlEncode($membership_users->isApproved->getPlaceHolder()) ?>" value="<?php echo $membership_users->isApproved->EditValue ?>"<?php echo $membership_users->isApproved->EditAttributes() ?>>
</span>
<?php echo $membership_users->isApproved->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membership_users->custom1->Visible) { // custom1 ?>
	<div id="r_custom1" class="form-group">
		<label id="elh_membership_users_custom1" for="x_custom1" class="col-sm-2 control-label ewLabel"><?php echo $membership_users->custom1->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membership_users->custom1->CellAttributes() ?>>
<span id="el_membership_users_custom1">
<textarea data-table="membership_users" data-field="x_custom1" name="x_custom1" id="x_custom1" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($membership_users->custom1->getPlaceHolder()) ?>"<?php echo $membership_users->custom1->EditAttributes() ?>><?php echo $membership_users->custom1->EditValue ?></textarea>
</span>
<?php echo $membership_users->custom1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membership_users->custom2->Visible) { // custom2 ?>
	<div id="r_custom2" class="form-group">
		<label id="elh_membership_users_custom2" for="x_custom2" class="col-sm-2 control-label ewLabel"><?php echo $membership_users->custom2->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membership_users->custom2->CellAttributes() ?>>
<span id="el_membership_users_custom2">
<textarea data-table="membership_users" data-field="x_custom2" name="x_custom2" id="x_custom2" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($membership_users->custom2->getPlaceHolder()) ?>"<?php echo $membership_users->custom2->EditAttributes() ?>><?php echo $membership_users->custom2->EditValue ?></textarea>
</span>
<?php echo $membership_users->custom2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membership_users->custom3->Visible) { // custom3 ?>
	<div id="r_custom3" class="form-group">
		<label id="elh_membership_users_custom3" for="x_custom3" class="col-sm-2 control-label ewLabel"><?php echo $membership_users->custom3->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membership_users->custom3->CellAttributes() ?>>
<span id="el_membership_users_custom3">
<textarea data-table="membership_users" data-field="x_custom3" name="x_custom3" id="x_custom3" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($membership_users->custom3->getPlaceHolder()) ?>"<?php echo $membership_users->custom3->EditAttributes() ?>><?php echo $membership_users->custom3->EditValue ?></textarea>
</span>
<?php echo $membership_users->custom3->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membership_users->custom4->Visible) { // custom4 ?>
	<div id="r_custom4" class="form-group">
		<label id="elh_membership_users_custom4" for="x_custom4" class="col-sm-2 control-label ewLabel"><?php echo $membership_users->custom4->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membership_users->custom4->CellAttributes() ?>>
<span id="el_membership_users_custom4">
<textarea data-table="membership_users" data-field="x_custom4" name="x_custom4" id="x_custom4" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($membership_users->custom4->getPlaceHolder()) ?>"<?php echo $membership_users->custom4->EditAttributes() ?>><?php echo $membership_users->custom4->EditValue ?></textarea>
</span>
<?php echo $membership_users->custom4->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membership_users->comments->Visible) { // comments ?>
	<div id="r_comments" class="form-group">
		<label id="elh_membership_users_comments" for="x_comments" class="col-sm-2 control-label ewLabel"><?php echo $membership_users->comments->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membership_users->comments->CellAttributes() ?>>
<span id="el_membership_users_comments">
<textarea data-table="membership_users" data-field="x_comments" name="x_comments" id="x_comments" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($membership_users->comments->getPlaceHolder()) ?>"<?php echo $membership_users->comments->EditAttributes() ?>><?php echo $membership_users->comments->EditValue ?></textarea>
</span>
<?php echo $membership_users->comments->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membership_users->pass_reset_key->Visible) { // pass_reset_key ?>
	<div id="r_pass_reset_key" class="form-group">
		<label id="elh_membership_users_pass_reset_key" for="x_pass_reset_key" class="col-sm-2 control-label ewLabel"><?php echo $membership_users->pass_reset_key->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membership_users->pass_reset_key->CellAttributes() ?>>
<span id="el_membership_users_pass_reset_key">
<input type="text" data-table="membership_users" data-field="x_pass_reset_key" name="x_pass_reset_key" id="x_pass_reset_key" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($membership_users->pass_reset_key->getPlaceHolder()) ?>" value="<?php echo $membership_users->pass_reset_key->EditValue ?>"<?php echo $membership_users->pass_reset_key->EditAttributes() ?>>
</span>
<?php echo $membership_users->pass_reset_key->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membership_users->pass_reset_expiry->Visible) { // pass_reset_expiry ?>
	<div id="r_pass_reset_expiry" class="form-group">
		<label id="elh_membership_users_pass_reset_expiry" for="x_pass_reset_expiry" class="col-sm-2 control-label ewLabel"><?php echo $membership_users->pass_reset_expiry->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membership_users->pass_reset_expiry->CellAttributes() ?>>
<span id="el_membership_users_pass_reset_expiry">
<input type="text" data-table="membership_users" data-field="x_pass_reset_expiry" name="x_pass_reset_expiry" id="x_pass_reset_expiry" size="30" placeholder="<?php echo ew_HtmlEncode($membership_users->pass_reset_expiry->getPlaceHolder()) ?>" value="<?php echo $membership_users->pass_reset_expiry->EditValue ?>"<?php echo $membership_users->pass_reset_expiry->EditAttributes() ?>>
</span>
<?php echo $membership_users->pass_reset_expiry->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $membership_users_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fmembership_usersedit.Init();
</script>
<?php
$membership_users_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$membership_users_edit->Page_Terminate();
?>
