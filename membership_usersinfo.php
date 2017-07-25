<?php

// Global variable for table object
$membership_users = NULL;

//
// Table class for membership_users
//
class cmembership_users extends cTable {
	var $memberID;
	var $passMD5;
	var $_email;
	var $signupDate;
	var $groupID;
	var $isBanned;
	var $isApproved;
	var $custom1;
	var $custom2;
	var $custom3;
	var $custom4;
	var $comments;
	var $pass_reset_key;
	var $pass_reset_expiry;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'membership_users';
		$this->TableName = 'membership_users';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`membership_users`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// memberID
		$this->memberID = new cField('membership_users', 'membership_users', 'x_memberID', 'memberID', '`memberID`', '`memberID`', 200, -1, FALSE, '`memberID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['memberID'] = &$this->memberID;

		// passMD5
		$this->passMD5 = new cField('membership_users', 'membership_users', 'x_passMD5', 'passMD5', '`passMD5`', '`passMD5`', 200, -1, FALSE, '`passMD5`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['passMD5'] = &$this->passMD5;

		// email
		$this->_email = new cField('membership_users', 'membership_users', 'x__email', 'email', '`email`', '`email`', 200, -1, FALSE, '`email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['email'] = &$this->_email;

		// signupDate
		$this->signupDate = new cField('membership_users', 'membership_users', 'x_signupDate', 'signupDate', '`signupDate`', 'DATE_FORMAT(`signupDate`, \'%Y/%m/%d\')', 133, 5, FALSE, '`signupDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->signupDate->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['signupDate'] = &$this->signupDate;

		// groupID
		$this->groupID = new cField('membership_users', 'membership_users', 'x_groupID', 'groupID', '`groupID`', '`groupID`', 19, -1, FALSE, '`groupID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->groupID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['groupID'] = &$this->groupID;

		// isBanned
		$this->isBanned = new cField('membership_users', 'membership_users', 'x_isBanned', 'isBanned', '`isBanned`', '`isBanned`', 16, -1, FALSE, '`isBanned`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->isBanned->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['isBanned'] = &$this->isBanned;

		// isApproved
		$this->isApproved = new cField('membership_users', 'membership_users', 'x_isApproved', 'isApproved', '`isApproved`', '`isApproved`', 16, -1, FALSE, '`isApproved`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->isApproved->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['isApproved'] = &$this->isApproved;

		// custom1
		$this->custom1 = new cField('membership_users', 'membership_users', 'x_custom1', 'custom1', '`custom1`', '`custom1`', 201, -1, FALSE, '`custom1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fields['custom1'] = &$this->custom1;

		// custom2
		$this->custom2 = new cField('membership_users', 'membership_users', 'x_custom2', 'custom2', '`custom2`', '`custom2`', 201, -1, FALSE, '`custom2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fields['custom2'] = &$this->custom2;

		// custom3
		$this->custom3 = new cField('membership_users', 'membership_users', 'x_custom3', 'custom3', '`custom3`', '`custom3`', 201, -1, FALSE, '`custom3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fields['custom3'] = &$this->custom3;

		// custom4
		$this->custom4 = new cField('membership_users', 'membership_users', 'x_custom4', 'custom4', '`custom4`', '`custom4`', 201, -1, FALSE, '`custom4`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fields['custom4'] = &$this->custom4;

		// comments
		$this->comments = new cField('membership_users', 'membership_users', 'x_comments', 'comments', '`comments`', '`comments`', 201, -1, FALSE, '`comments`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fields['comments'] = &$this->comments;

		// pass_reset_key
		$this->pass_reset_key = new cField('membership_users', 'membership_users', 'x_pass_reset_key', 'pass_reset_key', '`pass_reset_key`', '`pass_reset_key`', 200, -1, FALSE, '`pass_reset_key`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['pass_reset_key'] = &$this->pass_reset_key;

		// pass_reset_expiry
		$this->pass_reset_expiry = new cField('membership_users', 'membership_users', 'x_pass_reset_expiry', 'pass_reset_expiry', '`pass_reset_expiry`', '`pass_reset_expiry`', 19, -1, FALSE, '`pass_reset_expiry`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pass_reset_expiry->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['pass_reset_expiry'] = &$this->pass_reset_expiry;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`membership_users`";
	}

	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
    	return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
    	$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
    	return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
    	$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
    	return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
    	$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
    	return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
    	$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
    	return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
    	$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		global $Security;

		// Add User ID filter
		if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
			$sFilter = $this->AddUserIDFilter($sFilter);
		}
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = $this->UserIDAllowSecurity;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			if (EW_ENCRYPTED_PASSWORD && $name == 'passMD5')
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			if (EW_ENCRYPTED_PASSWORD && $name == 'passMD5') {
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
			}
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('memberID', $rs))
				ew_AddFilter($where, ew_QuotedName('memberID', $this->DBID) . '=' . ew_QuotedValue($rs['memberID'], $this->memberID->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`memberID` = '@memberID@'";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		$sKeyFilter = str_replace("@memberID@", ew_AdjustSql($this->memberID->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "membership_userslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "membership_userslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("membership_usersview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("membership_usersview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "membership_usersadd.php?" . $this->UrlParm($parm);
		else
			$url = "membership_usersadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("membership_usersedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("membership_usersadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("membership_usersdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "memberID:" . ew_VarToJson($this->memberID->CurrentValue, "string", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->memberID->CurrentValue)) {
			$sUrl .= "memberID=" . urlencode($this->memberID->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			$arKeys[] = $isPost ? ew_StripSlashes(@$_POST["memberID"]) : ew_StripSlashes(@$_GET["memberID"]); // memberID

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->memberID->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
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

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// memberID
		$this->memberID->EditAttrs["class"] = "form-control";
		$this->memberID->EditCustomAttributes = "";
		$this->memberID->EditValue = $this->memberID->CurrentValue;
		$this->memberID->ViewCustomAttributes = "";

		// passMD5
		$this->passMD5->EditAttrs["class"] = "form-control ewPasswordStrength";
		$this->passMD5->EditCustomAttributes = "";
		$this->passMD5->EditValue = $this->passMD5->CurrentValue;
		$this->passMD5->PlaceHolder = ew_RemoveHtml($this->passMD5->FldCaption());

		// email
		$this->_email->EditAttrs["class"] = "form-control";
		$this->_email->EditCustomAttributes = "";
		$this->_email->EditValue = $this->_email->CurrentValue;
		$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

		// signupDate
		$this->signupDate->EditAttrs["class"] = "form-control";
		$this->signupDate->EditCustomAttributes = "";
		$this->signupDate->EditValue = ew_FormatDateTime($this->signupDate->CurrentValue, 5);
		$this->signupDate->PlaceHolder = ew_RemoveHtml($this->signupDate->FldCaption());

		// groupID
		$this->groupID->EditAttrs["class"] = "form-control";
		$this->groupID->EditCustomAttributes = "";
		if (!$Security->CanAdmin()) { // System admin
			$this->groupID->EditValue = $Language->Phrase("PasswordMask");
		} else {
		}

		// isBanned
		$this->isBanned->EditAttrs["class"] = "form-control";
		$this->isBanned->EditCustomAttributes = "";
		$this->isBanned->EditValue = $this->isBanned->CurrentValue;
		$this->isBanned->PlaceHolder = ew_RemoveHtml($this->isBanned->FldCaption());

		// isApproved
		$this->isApproved->EditAttrs["class"] = "form-control";
		$this->isApproved->EditCustomAttributes = "";
		$this->isApproved->EditValue = $this->isApproved->CurrentValue;
		$this->isApproved->PlaceHolder = ew_RemoveHtml($this->isApproved->FldCaption());

		// custom1
		$this->custom1->EditAttrs["class"] = "form-control";
		$this->custom1->EditCustomAttributes = "";
		if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin
			if (strval($this->memberID->CurrentValue) == strval(CurrentUserID())) {
		$this->custom1->EditValue = $this->custom1->CurrentValue;
		$this->custom1->ViewCustomAttributes = "";
			} else {
		$sFilterWrk = "";
		$sFilterWrk = $GLOBALS["membership_users"]->AddParentUserIDFilter("", $this->memberID->CurrentValue);
		switch (@$gsLanguage) {
			case "es":
				$sSqlWrk = "SELECT `memberID`, `memberID` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `membership_users`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `memberID`, `memberID` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `membership_users`";
				$sWhereWrk = "";
				break;
		}
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->custom1, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$rswrk = Conn()->Execute($sSqlWrk);
		$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
		if ($rswrk) $rswrk->Close();
		array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
		$this->custom1->EditValue = $arwrk;
			}
		} else {
		$this->custom1->EditValue = $this->custom1->CurrentValue;
		$this->custom1->PlaceHolder = ew_RemoveHtml($this->custom1->FldCaption());
		}

		// custom2
		$this->custom2->EditAttrs["class"] = "form-control";
		$this->custom2->EditCustomAttributes = "";
		$this->custom2->EditValue = $this->custom2->CurrentValue;
		$this->custom2->PlaceHolder = ew_RemoveHtml($this->custom2->FldCaption());

		// custom3
		$this->custom3->EditAttrs["class"] = "form-control";
		$this->custom3->EditCustomAttributes = "";
		$this->custom3->EditValue = $this->custom3->CurrentValue;
		$this->custom3->PlaceHolder = ew_RemoveHtml($this->custom3->FldCaption());

		// custom4
		$this->custom4->EditAttrs["class"] = "form-control";
		$this->custom4->EditCustomAttributes = "";
		$this->custom4->EditValue = $this->custom4->CurrentValue;
		$this->custom4->PlaceHolder = ew_RemoveHtml($this->custom4->FldCaption());

		// comments
		$this->comments->EditAttrs["class"] = "form-control";
		$this->comments->EditCustomAttributes = "";
		$this->comments->EditValue = $this->comments->CurrentValue;
		$this->comments->PlaceHolder = ew_RemoveHtml($this->comments->FldCaption());

		// pass_reset_key
		$this->pass_reset_key->EditAttrs["class"] = "form-control";
		$this->pass_reset_key->EditCustomAttributes = "";
		$this->pass_reset_key->EditValue = $this->pass_reset_key->CurrentValue;
		$this->pass_reset_key->PlaceHolder = ew_RemoveHtml($this->pass_reset_key->FldCaption());

		// pass_reset_expiry
		$this->pass_reset_expiry->EditAttrs["class"] = "form-control";
		$this->pass_reset_expiry->EditCustomAttributes = "";
		$this->pass_reset_expiry->EditValue = $this->pass_reset_expiry->CurrentValue;
		$this->pass_reset_expiry->PlaceHolder = ew_RemoveHtml($this->pass_reset_expiry->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->memberID->Exportable) $Doc->ExportCaption($this->memberID);
					if ($this->passMD5->Exportable) $Doc->ExportCaption($this->passMD5);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->signupDate->Exportable) $Doc->ExportCaption($this->signupDate);
					if ($this->groupID->Exportable) $Doc->ExportCaption($this->groupID);
					if ($this->isBanned->Exportable) $Doc->ExportCaption($this->isBanned);
					if ($this->isApproved->Exportable) $Doc->ExportCaption($this->isApproved);
					if ($this->custom1->Exportable) $Doc->ExportCaption($this->custom1);
					if ($this->custom2->Exportable) $Doc->ExportCaption($this->custom2);
					if ($this->custom3->Exportable) $Doc->ExportCaption($this->custom3);
					if ($this->custom4->Exportable) $Doc->ExportCaption($this->custom4);
					if ($this->comments->Exportable) $Doc->ExportCaption($this->comments);
					if ($this->pass_reset_key->Exportable) $Doc->ExportCaption($this->pass_reset_key);
					if ($this->pass_reset_expiry->Exportable) $Doc->ExportCaption($this->pass_reset_expiry);
				} else {
					if ($this->memberID->Exportable) $Doc->ExportCaption($this->memberID);
					if ($this->passMD5->Exportable) $Doc->ExportCaption($this->passMD5);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->signupDate->Exportable) $Doc->ExportCaption($this->signupDate);
					if ($this->groupID->Exportable) $Doc->ExportCaption($this->groupID);
					if ($this->isBanned->Exportable) $Doc->ExportCaption($this->isBanned);
					if ($this->isApproved->Exportable) $Doc->ExportCaption($this->isApproved);
					if ($this->pass_reset_key->Exportable) $Doc->ExportCaption($this->pass_reset_key);
					if ($this->pass_reset_expiry->Exportable) $Doc->ExportCaption($this->pass_reset_expiry);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->memberID->Exportable) $Doc->ExportField($this->memberID);
						if ($this->passMD5->Exportable) $Doc->ExportField($this->passMD5);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->signupDate->Exportable) $Doc->ExportField($this->signupDate);
						if ($this->groupID->Exportable) $Doc->ExportField($this->groupID);
						if ($this->isBanned->Exportable) $Doc->ExportField($this->isBanned);
						if ($this->isApproved->Exportable) $Doc->ExportField($this->isApproved);
						if ($this->custom1->Exportable) $Doc->ExportField($this->custom1);
						if ($this->custom2->Exportable) $Doc->ExportField($this->custom2);
						if ($this->custom3->Exportable) $Doc->ExportField($this->custom3);
						if ($this->custom4->Exportable) $Doc->ExportField($this->custom4);
						if ($this->comments->Exportable) $Doc->ExportField($this->comments);
						if ($this->pass_reset_key->Exportable) $Doc->ExportField($this->pass_reset_key);
						if ($this->pass_reset_expiry->Exportable) $Doc->ExportField($this->pass_reset_expiry);
					} else {
						if ($this->memberID->Exportable) $Doc->ExportField($this->memberID);
						if ($this->passMD5->Exportable) $Doc->ExportField($this->passMD5);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->signupDate->Exportable) $Doc->ExportField($this->signupDate);
						if ($this->groupID->Exportable) $Doc->ExportField($this->groupID);
						if ($this->isBanned->Exportable) $Doc->ExportField($this->isBanned);
						if ($this->isApproved->Exportable) $Doc->ExportField($this->isApproved);
						if ($this->pass_reset_key->Exportable) $Doc->ExportField($this->pass_reset_key);
						if ($this->pass_reset_expiry->Exportable) $Doc->ExportField($this->pass_reset_expiry);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// User ID filter
	function UserIDFilter($userid) {
		$sUserIDFilter = '`memberID` = ' . ew_QuotedValue($userid, EW_DATATYPE_STRING, EW_USER_TABLE_DBID);
		$sParentUserIDFilter = '`memberID` IN (SELECT `memberID` FROM ' . "`membership_users`" . ' WHERE `custom1` = ' . ew_QuotedValue($userid, EW_DATATYPE_STRING, EW_USER_TABLE_DBID) . ')';
		$sUserIDFilter = "($sUserIDFilter) OR ($sParentUserIDFilter)";
		return $sUserIDFilter;
	}

	// Add User ID filter
	function AddUserIDFilter($sFilter) {
		global $Security;
		$sFilterWrk = "";
		$id = (CurrentPageID() == "list") ? $this->CurrentAction : CurrentPageID();
		if (!$this->UserIDAllow($id) && !$Security->IsAdmin()) {
			$sFilterWrk = $Security->UserIDList();
			if ($sFilterWrk <> "")
				$sFilterWrk = '`memberID` IN (' . $sFilterWrk . ')';
		}

		// Call User ID Filtering event
		$this->UserID_Filtering($sFilterWrk);
		ew_AddFilter($sFilter, $sFilterWrk);
		return $sFilter;
	}

	// Add Parent User ID filter
	function AddParentUserIDFilter($sFilter, $userid) {
		global $Security;
		if (!$Security->IsAdmin()) {
			$result = $Security->ParentUserIDList($userid);
			if ($result <> "")
				$result = '`memberID` IN (' . $result . ')';
			ew_AddFilter($result, $sFilter);
			return $result;
		} else {
			return $sFilter;
		}
	}

	// User ID subquery
	function GetUserIDSubquery(&$fld, &$masterfld) {
		global $UserTableConn;
		$sWrk = "";
		$sSql = "SELECT " . $masterfld->FldExpression . " FROM `membership_users`";
		$sFilter = $this->AddUserIDFilter("");
		if ($sFilter <> "") $sSql .= " WHERE " . $sFilter;

		// Use subquery
		if (EW_USE_SUBQUERY_FOR_MASTER_USER_ID) {
			$sWrk = $sSql;
		} else {

			// List all values
			if ($rs = $UserTableConn->Execute($sSql)) {
				while (!$rs->EOF) {
					if ($sWrk <> "") $sWrk .= ",";
					$sWrk .= ew_QuotedValue($rs->fields[0], $masterfld->FldDataType, EW_USER_TABLE_DBID);
					$rs->MoveNext();
				}
				$rs->Close();
			}
		}
		if ($sWrk <> "") {
			$sWrk = $fld->FldExpression . " IN (" . $sWrk . ")";
		}
		return $sWrk;
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
