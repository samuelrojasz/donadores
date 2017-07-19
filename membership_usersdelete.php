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

$membership_users_delete = NULL; // Initialize page object first

class cmembership_users_delete extends cmembership_users {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{A9B917F6-72DB-4C37-BB0D-F508A0EFFBF8}";

	// Table name
	var $TableName = 'membership_users';

	// Page object name
	var $PageObjName = 'membership_users_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("membership_userslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in membership_users class, membership_usersinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
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

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['memberID'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "membership_userslist.php", "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($membership_users_delete)) $membership_users_delete = new cmembership_users_delete();

// Page init
$membership_users_delete->Page_Init();

// Page main
$membership_users_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$membership_users_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fmembership_usersdelete = new ew_Form("fmembership_usersdelete", "delete");

// Form_CustomValidate event
fmembership_usersdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmembership_usersdelete.ValidateRequired = true;
<?php } else { ?>
fmembership_usersdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fmembership_usersdelete.Lists["x_groupID"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($membership_users_delete->Recordset = $membership_users_delete->LoadRecordset())
	$membership_users_deleteTotalRecs = $membership_users_delete->Recordset->RecordCount(); // Get record count
if ($membership_users_deleteTotalRecs <= 0) { // No record found, exit
	if ($membership_users_delete->Recordset)
		$membership_users_delete->Recordset->Close();
	$membership_users_delete->Page_Terminate("membership_userslist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $membership_users_delete->ShowPageHeader(); ?>
<?php
$membership_users_delete->ShowMessage();
?>
<form name="fmembership_usersdelete" id="fmembership_usersdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($membership_users_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $membership_users_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="membership_users">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($membership_users_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $membership_users->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($membership_users->memberID->Visible) { // memberID ?>
		<th><span id="elh_membership_users_memberID" class="membership_users_memberID"><?php echo $membership_users->memberID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($membership_users->passMD5->Visible) { // passMD5 ?>
		<th><span id="elh_membership_users_passMD5" class="membership_users_passMD5"><?php echo $membership_users->passMD5->FldCaption() ?></span></th>
<?php } ?>
<?php if ($membership_users->_email->Visible) { // email ?>
		<th><span id="elh_membership_users__email" class="membership_users__email"><?php echo $membership_users->_email->FldCaption() ?></span></th>
<?php } ?>
<?php if ($membership_users->signupDate->Visible) { // signupDate ?>
		<th><span id="elh_membership_users_signupDate" class="membership_users_signupDate"><?php echo $membership_users->signupDate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($membership_users->groupID->Visible) { // groupID ?>
		<th><span id="elh_membership_users_groupID" class="membership_users_groupID"><?php echo $membership_users->groupID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($membership_users->isBanned->Visible) { // isBanned ?>
		<th><span id="elh_membership_users_isBanned" class="membership_users_isBanned"><?php echo $membership_users->isBanned->FldCaption() ?></span></th>
<?php } ?>
<?php if ($membership_users->isApproved->Visible) { // isApproved ?>
		<th><span id="elh_membership_users_isApproved" class="membership_users_isApproved"><?php echo $membership_users->isApproved->FldCaption() ?></span></th>
<?php } ?>
<?php if ($membership_users->pass_reset_key->Visible) { // pass_reset_key ?>
		<th><span id="elh_membership_users_pass_reset_key" class="membership_users_pass_reset_key"><?php echo $membership_users->pass_reset_key->FldCaption() ?></span></th>
<?php } ?>
<?php if ($membership_users->pass_reset_expiry->Visible) { // pass_reset_expiry ?>
		<th><span id="elh_membership_users_pass_reset_expiry" class="membership_users_pass_reset_expiry"><?php echo $membership_users->pass_reset_expiry->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$membership_users_delete->RecCnt = 0;
$i = 0;
while (!$membership_users_delete->Recordset->EOF) {
	$membership_users_delete->RecCnt++;
	$membership_users_delete->RowCnt++;

	// Set row properties
	$membership_users->ResetAttrs();
	$membership_users->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$membership_users_delete->LoadRowValues($membership_users_delete->Recordset);

	// Render row
	$membership_users_delete->RenderRow();
?>
	<tr<?php echo $membership_users->RowAttributes() ?>>
<?php if ($membership_users->memberID->Visible) { // memberID ?>
		<td<?php echo $membership_users->memberID->CellAttributes() ?>>
<span id="el<?php echo $membership_users_delete->RowCnt ?>_membership_users_memberID" class="membership_users_memberID">
<span<?php echo $membership_users->memberID->ViewAttributes() ?>>
<?php echo $membership_users->memberID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($membership_users->passMD5->Visible) { // passMD5 ?>
		<td<?php echo $membership_users->passMD5->CellAttributes() ?>>
<span id="el<?php echo $membership_users_delete->RowCnt ?>_membership_users_passMD5" class="membership_users_passMD5">
<span<?php echo $membership_users->passMD5->ViewAttributes() ?>>
<?php echo $membership_users->passMD5->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($membership_users->_email->Visible) { // email ?>
		<td<?php echo $membership_users->_email->CellAttributes() ?>>
<span id="el<?php echo $membership_users_delete->RowCnt ?>_membership_users__email" class="membership_users__email">
<span<?php echo $membership_users->_email->ViewAttributes() ?>>
<?php echo $membership_users->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($membership_users->signupDate->Visible) { // signupDate ?>
		<td<?php echo $membership_users->signupDate->CellAttributes() ?>>
<span id="el<?php echo $membership_users_delete->RowCnt ?>_membership_users_signupDate" class="membership_users_signupDate">
<span<?php echo $membership_users->signupDate->ViewAttributes() ?>>
<?php echo $membership_users->signupDate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($membership_users->groupID->Visible) { // groupID ?>
		<td<?php echo $membership_users->groupID->CellAttributes() ?>>
<span id="el<?php echo $membership_users_delete->RowCnt ?>_membership_users_groupID" class="membership_users_groupID">
<span<?php echo $membership_users->groupID->ViewAttributes() ?>>
<?php echo $membership_users->groupID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($membership_users->isBanned->Visible) { // isBanned ?>
		<td<?php echo $membership_users->isBanned->CellAttributes() ?>>
<span id="el<?php echo $membership_users_delete->RowCnt ?>_membership_users_isBanned" class="membership_users_isBanned">
<span<?php echo $membership_users->isBanned->ViewAttributes() ?>>
<?php echo $membership_users->isBanned->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($membership_users->isApproved->Visible) { // isApproved ?>
		<td<?php echo $membership_users->isApproved->CellAttributes() ?>>
<span id="el<?php echo $membership_users_delete->RowCnt ?>_membership_users_isApproved" class="membership_users_isApproved">
<span<?php echo $membership_users->isApproved->ViewAttributes() ?>>
<?php echo $membership_users->isApproved->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($membership_users->pass_reset_key->Visible) { // pass_reset_key ?>
		<td<?php echo $membership_users->pass_reset_key->CellAttributes() ?>>
<span id="el<?php echo $membership_users_delete->RowCnt ?>_membership_users_pass_reset_key" class="membership_users_pass_reset_key">
<span<?php echo $membership_users->pass_reset_key->ViewAttributes() ?>>
<?php echo $membership_users->pass_reset_key->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($membership_users->pass_reset_expiry->Visible) { // pass_reset_expiry ?>
		<td<?php echo $membership_users->pass_reset_expiry->CellAttributes() ?>>
<span id="el<?php echo $membership_users_delete->RowCnt ?>_membership_users_pass_reset_expiry" class="membership_users_pass_reset_expiry">
<span<?php echo $membership_users->pass_reset_expiry->ViewAttributes() ?>>
<?php echo $membership_users->pass_reset_expiry->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$membership_users_delete->Recordset->MoveNext();
}
$membership_users_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $membership_users_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fmembership_usersdelete.Init();
</script>
<?php
$membership_users_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$membership_users_delete->Page_Terminate();
?>
