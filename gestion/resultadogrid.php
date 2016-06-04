<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($resultado_grid)) $resultado_grid = new cresultado_grid();

// Page init
$resultado_grid->Page_Init();

// Page main
$resultado_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$resultado_grid->Page_Render();
?>
<?php if ($resultado->Export == "") { ?>
<script type="text/javascript">

// Form object
var fresultadogrid = new ew_Form("fresultadogrid", "grid");
fresultadogrid.FormKeyCountName = '<?php echo $resultado_grid->FormKeyCountName ?>';

// Validate form
fresultadogrid.Validate = function() {
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
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_objetivo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $resultado->objetivo->FldCaption(), $resultado->objetivo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nombre");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $resultado->nombre->FldCaption(), $resultado->nombre->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tiempoEstimado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $resultado->tiempoEstimado->FldCaption(), $resultado->tiempoEstimado->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tiempoEstimado");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($resultado->tiempoEstimado->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tiempoTipo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $resultado->tiempoTipo->FldCaption(), $resultado->tiempoTipo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fechaInicio");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($resultado->fechaInicio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fechaFin");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($resultado->fechaFin->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_estatus");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $resultado->estatus->FldCaption(), $resultado->estatus->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fresultadogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "objetivo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nombre", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tiempoEstimado", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tiempoTipo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fechaInicio", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fechaFin", false)) return false;
	if (ew_ValueChanged(fobj, infix, "estatus", false)) return false;
	return true;
}

// Form_CustomValidate event
fresultadogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fresultadogrid.ValidateRequired = true;
<?php } else { ?>
fresultadogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fresultadogrid.Lists["x_objetivo"] = {"LinkField":"x_idObjetivo","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fresultadogrid.Lists["x_tiempoTipo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fresultadogrid.Lists["x_tiempoTipo"].Options = <?php echo json_encode($resultado->tiempoTipo->Options()) ?>;
fresultadogrid.Lists["x_estatus"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fresultadogrid.Lists["x_estatus"].Options = <?php echo json_encode($resultado->estatus->Options()) ?>;

// Form object for search
</script>
<?php } ?>
<?php
if ($resultado->CurrentAction == "gridadd") {
	if ($resultado->CurrentMode == "copy") {
		$bSelectLimit = $resultado_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$resultado_grid->TotalRecs = $resultado->SelectRecordCount();
			$resultado_grid->Recordset = $resultado_grid->LoadRecordset($resultado_grid->StartRec-1, $resultado_grid->DisplayRecs);
		} else {
			if ($resultado_grid->Recordset = $resultado_grid->LoadRecordset())
				$resultado_grid->TotalRecs = $resultado_grid->Recordset->RecordCount();
		}
		$resultado_grid->StartRec = 1;
		$resultado_grid->DisplayRecs = $resultado_grid->TotalRecs;
	} else {
		$resultado->CurrentFilter = "0=1";
		$resultado_grid->StartRec = 1;
		$resultado_grid->DisplayRecs = $resultado->GridAddRowCount;
	}
	$resultado_grid->TotalRecs = $resultado_grid->DisplayRecs;
	$resultado_grid->StopRec = $resultado_grid->DisplayRecs;
} else {
	$bSelectLimit = $resultado_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($resultado_grid->TotalRecs <= 0)
			$resultado_grid->TotalRecs = $resultado->SelectRecordCount();
	} else {
		if (!$resultado_grid->Recordset && ($resultado_grid->Recordset = $resultado_grid->LoadRecordset()))
			$resultado_grid->TotalRecs = $resultado_grid->Recordset->RecordCount();
	}
	$resultado_grid->StartRec = 1;
	$resultado_grid->DisplayRecs = $resultado_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$resultado_grid->Recordset = $resultado_grid->LoadRecordset($resultado_grid->StartRec-1, $resultado_grid->DisplayRecs);

	// Set no record found message
	if ($resultado->CurrentAction == "" && $resultado_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$resultado_grid->setWarningMessage(ew_DeniedMsg());
		if ($resultado_grid->SearchWhere == "0=101")
			$resultado_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$resultado_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$resultado_grid->RenderOtherOptions();
?>
<?php $resultado_grid->ShowPageHeader(); ?>
<?php
$resultado_grid->ShowMessage();
?>
<?php if ($resultado_grid->TotalRecs > 0 || $resultado->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<div id="fresultadogrid" class="ewForm form-inline">
<?php if ($resultado_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($resultado_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_resultado" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_resultadogrid" class="table ewTable">
<?php echo $resultado->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$resultado_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$resultado_grid->RenderListOptions();

// Render list options (header, left)
$resultado_grid->ListOptions->Render("header", "left");
?>
<?php if ($resultado->idResultado->Visible) { // idResultado ?>
	<?php if ($resultado->SortUrl($resultado->idResultado) == "") { ?>
		<th data-name="idResultado"><div id="elh_resultado_idResultado" class="resultado_idResultado"><div class="ewTableHeaderCaption"><?php echo $resultado->idResultado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idResultado"><div><div id="elh_resultado_idResultado" class="resultado_idResultado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $resultado->idResultado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($resultado->idResultado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($resultado->idResultado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($resultado->objetivo->Visible) { // objetivo ?>
	<?php if ($resultado->SortUrl($resultado->objetivo) == "") { ?>
		<th data-name="objetivo"><div id="elh_resultado_objetivo" class="resultado_objetivo"><div class="ewTableHeaderCaption"><?php echo $resultado->objetivo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="objetivo"><div><div id="elh_resultado_objetivo" class="resultado_objetivo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $resultado->objetivo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($resultado->objetivo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($resultado->objetivo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($resultado->nombre->Visible) { // nombre ?>
	<?php if ($resultado->SortUrl($resultado->nombre) == "") { ?>
		<th data-name="nombre"><div id="elh_resultado_nombre" class="resultado_nombre"><div class="ewTableHeaderCaption"><?php echo $resultado->nombre->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombre"><div><div id="elh_resultado_nombre" class="resultado_nombre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $resultado->nombre->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($resultado->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($resultado->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($resultado->tiempoEstimado->Visible) { // tiempoEstimado ?>
	<?php if ($resultado->SortUrl($resultado->tiempoEstimado) == "") { ?>
		<th data-name="tiempoEstimado"><div id="elh_resultado_tiempoEstimado" class="resultado_tiempoEstimado"><div class="ewTableHeaderCaption"><?php echo $resultado->tiempoEstimado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tiempoEstimado"><div><div id="elh_resultado_tiempoEstimado" class="resultado_tiempoEstimado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $resultado->tiempoEstimado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($resultado->tiempoEstimado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($resultado->tiempoEstimado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($resultado->tiempoTipo->Visible) { // tiempoTipo ?>
	<?php if ($resultado->SortUrl($resultado->tiempoTipo) == "") { ?>
		<th data-name="tiempoTipo"><div id="elh_resultado_tiempoTipo" class="resultado_tiempoTipo"><div class="ewTableHeaderCaption"><?php echo $resultado->tiempoTipo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tiempoTipo"><div><div id="elh_resultado_tiempoTipo" class="resultado_tiempoTipo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $resultado->tiempoTipo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($resultado->tiempoTipo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($resultado->tiempoTipo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($resultado->fechaInicio->Visible) { // fechaInicio ?>
	<?php if ($resultado->SortUrl($resultado->fechaInicio) == "") { ?>
		<th data-name="fechaInicio"><div id="elh_resultado_fechaInicio" class="resultado_fechaInicio"><div class="ewTableHeaderCaption"><?php echo $resultado->fechaInicio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechaInicio"><div><div id="elh_resultado_fechaInicio" class="resultado_fechaInicio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $resultado->fechaInicio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($resultado->fechaInicio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($resultado->fechaInicio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($resultado->fechaFin->Visible) { // fechaFin ?>
	<?php if ($resultado->SortUrl($resultado->fechaFin) == "") { ?>
		<th data-name="fechaFin"><div id="elh_resultado_fechaFin" class="resultado_fechaFin"><div class="ewTableHeaderCaption"><?php echo $resultado->fechaFin->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechaFin"><div><div id="elh_resultado_fechaFin" class="resultado_fechaFin">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $resultado->fechaFin->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($resultado->fechaFin->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($resultado->fechaFin->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($resultado->estatus->Visible) { // estatus ?>
	<?php if ($resultado->SortUrl($resultado->estatus) == "") { ?>
		<th data-name="estatus"><div id="elh_resultado_estatus" class="resultado_estatus"><div class="ewTableHeaderCaption"><?php echo $resultado->estatus->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estatus"><div><div id="elh_resultado_estatus" class="resultado_estatus">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $resultado->estatus->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($resultado->estatus->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($resultado->estatus->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$resultado_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$resultado_grid->StartRec = 1;
$resultado_grid->StopRec = $resultado_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($resultado_grid->FormKeyCountName) && ($resultado->CurrentAction == "gridadd" || $resultado->CurrentAction == "gridedit" || $resultado->CurrentAction == "F")) {
		$resultado_grid->KeyCount = $objForm->GetValue($resultado_grid->FormKeyCountName);
		$resultado_grid->StopRec = $resultado_grid->StartRec + $resultado_grid->KeyCount - 1;
	}
}
$resultado_grid->RecCnt = $resultado_grid->StartRec - 1;
if ($resultado_grid->Recordset && !$resultado_grid->Recordset->EOF) {
	$resultado_grid->Recordset->MoveFirst();
	$bSelectLimit = $resultado_grid->UseSelectLimit;
	if (!$bSelectLimit && $resultado_grid->StartRec > 1)
		$resultado_grid->Recordset->Move($resultado_grid->StartRec - 1);
} elseif (!$resultado->AllowAddDeleteRow && $resultado_grid->StopRec == 0) {
	$resultado_grid->StopRec = $resultado->GridAddRowCount;
}

// Initialize aggregate
$resultado->RowType = EW_ROWTYPE_AGGREGATEINIT;
$resultado->ResetAttrs();
$resultado_grid->RenderRow();
if ($resultado->CurrentAction == "gridadd")
	$resultado_grid->RowIndex = 0;
if ($resultado->CurrentAction == "gridedit")
	$resultado_grid->RowIndex = 0;
while ($resultado_grid->RecCnt < $resultado_grid->StopRec) {
	$resultado_grid->RecCnt++;
	if (intval($resultado_grid->RecCnt) >= intval($resultado_grid->StartRec)) {
		$resultado_grid->RowCnt++;
		if ($resultado->CurrentAction == "gridadd" || $resultado->CurrentAction == "gridedit" || $resultado->CurrentAction == "F") {
			$resultado_grid->RowIndex++;
			$objForm->Index = $resultado_grid->RowIndex;
			if ($objForm->HasValue($resultado_grid->FormActionName))
				$resultado_grid->RowAction = strval($objForm->GetValue($resultado_grid->FormActionName));
			elseif ($resultado->CurrentAction == "gridadd")
				$resultado_grid->RowAction = "insert";
			else
				$resultado_grid->RowAction = "";
		}

		// Set up key count
		$resultado_grid->KeyCount = $resultado_grid->RowIndex;

		// Init row class and style
		$resultado->ResetAttrs();
		$resultado->CssClass = "";
		if ($resultado->CurrentAction == "gridadd") {
			if ($resultado->CurrentMode == "copy") {
				$resultado_grid->LoadRowValues($resultado_grid->Recordset); // Load row values
				$resultado_grid->SetRecordKey($resultado_grid->RowOldKey, $resultado_grid->Recordset); // Set old record key
			} else {
				$resultado_grid->LoadDefaultValues(); // Load default values
				$resultado_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$resultado_grid->LoadRowValues($resultado_grid->Recordset); // Load row values
		}
		$resultado->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($resultado->CurrentAction == "gridadd") // Grid add
			$resultado->RowType = EW_ROWTYPE_ADD; // Render add
		if ($resultado->CurrentAction == "gridadd" && $resultado->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$resultado_grid->RestoreCurrentRowFormValues($resultado_grid->RowIndex); // Restore form values
		if ($resultado->CurrentAction == "gridedit") { // Grid edit
			if ($resultado->EventCancelled) {
				$resultado_grid->RestoreCurrentRowFormValues($resultado_grid->RowIndex); // Restore form values
			}
			if ($resultado_grid->RowAction == "insert")
				$resultado->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$resultado->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($resultado->CurrentAction == "gridedit" && ($resultado->RowType == EW_ROWTYPE_EDIT || $resultado->RowType == EW_ROWTYPE_ADD) && $resultado->EventCancelled) // Update failed
			$resultado_grid->RestoreCurrentRowFormValues($resultado_grid->RowIndex); // Restore form values
		if ($resultado->RowType == EW_ROWTYPE_EDIT) // Edit row
			$resultado_grid->EditRowCnt++;
		if ($resultado->CurrentAction == "F") // Confirm row
			$resultado_grid->RestoreCurrentRowFormValues($resultado_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$resultado->RowAttrs = array_merge($resultado->RowAttrs, array('data-rowindex'=>$resultado_grid->RowCnt, 'id'=>'r' . $resultado_grid->RowCnt . '_resultado', 'data-rowtype'=>$resultado->RowType));

		// Render row
		$resultado_grid->RenderRow();

		// Render list options
		$resultado_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($resultado_grid->RowAction <> "delete" && $resultado_grid->RowAction <> "insertdelete" && !($resultado_grid->RowAction == "insert" && $resultado->CurrentAction == "F" && $resultado_grid->EmptyRow())) {
?>
	<tr<?php echo $resultado->RowAttributes() ?>>
<?php

// Render list options (body, left)
$resultado_grid->ListOptions->Render("body", "left", $resultado_grid->RowCnt);
?>
	<?php if ($resultado->idResultado->Visible) { // idResultado ?>
		<td data-name="idResultado"<?php echo $resultado->idResultado->CellAttributes() ?>>
<?php if ($resultado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="resultado" data-field="x_idResultado" name="o<?php echo $resultado_grid->RowIndex ?>_idResultado" id="o<?php echo $resultado_grid->RowIndex ?>_idResultado" value="<?php echo ew_HtmlEncode($resultado->idResultado->OldValue) ?>">
<?php } ?>
<?php if ($resultado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $resultado_grid->RowCnt ?>_resultado_idResultado" class="form-group resultado_idResultado">
<span<?php echo $resultado->idResultado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $resultado->idResultado->EditValue ?></p></span>
</span>
<input type="hidden" data-table="resultado" data-field="x_idResultado" name="x<?php echo $resultado_grid->RowIndex ?>_idResultado" id="x<?php echo $resultado_grid->RowIndex ?>_idResultado" value="<?php echo ew_HtmlEncode($resultado->idResultado->CurrentValue) ?>">
<?php } ?>
<?php if ($resultado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $resultado_grid->RowCnt ?>_resultado_idResultado" class="resultado_idResultado">
<span<?php echo $resultado->idResultado->ViewAttributes() ?>>
<?php echo $resultado->idResultado->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="resultado" data-field="x_idResultado" name="x<?php echo $resultado_grid->RowIndex ?>_idResultado" id="x<?php echo $resultado_grid->RowIndex ?>_idResultado" value="<?php echo ew_HtmlEncode($resultado->idResultado->FormValue) ?>">
<input type="hidden" data-table="resultado" data-field="x_idResultado" name="o<?php echo $resultado_grid->RowIndex ?>_idResultado" id="o<?php echo $resultado_grid->RowIndex ?>_idResultado" value="<?php echo ew_HtmlEncode($resultado->idResultado->OldValue) ?>">
<?php } ?>
<a id="<?php echo $resultado_grid->PageObjName . "_row_" . $resultado_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($resultado->objetivo->Visible) { // objetivo ?>
		<td data-name="objetivo"<?php echo $resultado->objetivo->CellAttributes() ?>>
<?php if ($resultado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($resultado->objetivo->getSessionValue() <> "") { ?>
<span id="el<?php echo $resultado_grid->RowCnt ?>_resultado_objetivo" class="form-group resultado_objetivo">
<span<?php echo $resultado->objetivo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $resultado->objetivo->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $resultado_grid->RowIndex ?>_objetivo" name="x<?php echo $resultado_grid->RowIndex ?>_objetivo" value="<?php echo ew_HtmlEncode($resultado->objetivo->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $resultado_grid->RowCnt ?>_resultado_objetivo" class="form-group resultado_objetivo">
<select data-table="resultado" data-field="x_objetivo" data-value-separator="<?php echo ew_HtmlEncode(is_array($resultado->objetivo->DisplayValueSeparator) ? json_encode($resultado->objetivo->DisplayValueSeparator) : $resultado->objetivo->DisplayValueSeparator) ?>" id="x<?php echo $resultado_grid->RowIndex ?>_objetivo" name="x<?php echo $resultado_grid->RowIndex ?>_objetivo"<?php echo $resultado->objetivo->EditAttributes() ?>>
<?php
if (is_array($resultado->objetivo->EditValue)) {
	$arwrk = $resultado->objetivo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($resultado->objetivo->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $resultado->objetivo->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($resultado->objetivo->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($resultado->objetivo->CurrentValue) ?>" selected><?php echo $resultado->objetivo->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $resultado->objetivo->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `idObjetivo`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `objetivo`";
$sWhereWrk = "";
$resultado->objetivo->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$resultado->objetivo->LookupFilters += array("f0" => "`idObjetivo` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$resultado->Lookup_Selecting($resultado->objetivo, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $resultado->objetivo->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $resultado_grid->RowIndex ?>_objetivo" id="s_x<?php echo $resultado_grid->RowIndex ?>_objetivo" value="<?php echo $resultado->objetivo->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="resultado" data-field="x_objetivo" name="o<?php echo $resultado_grid->RowIndex ?>_objetivo" id="o<?php echo $resultado_grid->RowIndex ?>_objetivo" value="<?php echo ew_HtmlEncode($resultado->objetivo->OldValue) ?>">
<?php } ?>
<?php if ($resultado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($resultado->objetivo->getSessionValue() <> "") { ?>
<span id="el<?php echo $resultado_grid->RowCnt ?>_resultado_objetivo" class="form-group resultado_objetivo">
<span<?php echo $resultado->objetivo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $resultado->objetivo->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $resultado_grid->RowIndex ?>_objetivo" name="x<?php echo $resultado_grid->RowIndex ?>_objetivo" value="<?php echo ew_HtmlEncode($resultado->objetivo->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $resultado_grid->RowCnt ?>_resultado_objetivo" class="form-group resultado_objetivo">
<select data-table="resultado" data-field="x_objetivo" data-value-separator="<?php echo ew_HtmlEncode(is_array($resultado->objetivo->DisplayValueSeparator) ? json_encode($resultado->objetivo->DisplayValueSeparator) : $resultado->objetivo->DisplayValueSeparator) ?>" id="x<?php echo $resultado_grid->RowIndex ?>_objetivo" name="x<?php echo $resultado_grid->RowIndex ?>_objetivo"<?php echo $resultado->objetivo->EditAttributes() ?>>
<?php
if (is_array($resultado->objetivo->EditValue)) {
	$arwrk = $resultado->objetivo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($resultado->objetivo->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $resultado->objetivo->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($resultado->objetivo->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($resultado->objetivo->CurrentValue) ?>" selected><?php echo $resultado->objetivo->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $resultado->objetivo->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `idObjetivo`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `objetivo`";
$sWhereWrk = "";
$resultado->objetivo->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$resultado->objetivo->LookupFilters += array("f0" => "`idObjetivo` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$resultado->Lookup_Selecting($resultado->objetivo, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $resultado->objetivo->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $resultado_grid->RowIndex ?>_objetivo" id="s_x<?php echo $resultado_grid->RowIndex ?>_objetivo" value="<?php echo $resultado->objetivo->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } ?>
<?php if ($resultado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $resultado_grid->RowCnt ?>_resultado_objetivo" class="resultado_objetivo">
<span<?php echo $resultado->objetivo->ViewAttributes() ?>>
<?php echo $resultado->objetivo->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="resultado" data-field="x_objetivo" name="x<?php echo $resultado_grid->RowIndex ?>_objetivo" id="x<?php echo $resultado_grid->RowIndex ?>_objetivo" value="<?php echo ew_HtmlEncode($resultado->objetivo->FormValue) ?>">
<input type="hidden" data-table="resultado" data-field="x_objetivo" name="o<?php echo $resultado_grid->RowIndex ?>_objetivo" id="o<?php echo $resultado_grid->RowIndex ?>_objetivo" value="<?php echo ew_HtmlEncode($resultado->objetivo->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($resultado->nombre->Visible) { // nombre ?>
		<td data-name="nombre"<?php echo $resultado->nombre->CellAttributes() ?>>
<?php if ($resultado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $resultado_grid->RowCnt ?>_resultado_nombre" class="form-group resultado_nombre">
<input type="text" data-table="resultado" data-field="x_nombre" name="x<?php echo $resultado_grid->RowIndex ?>_nombre" id="x<?php echo $resultado_grid->RowIndex ?>_nombre" size="30" maxlength="128" placeholder="<?php echo ew_HtmlEncode($resultado->nombre->getPlaceHolder()) ?>" value="<?php echo $resultado->nombre->EditValue ?>"<?php echo $resultado->nombre->EditAttributes() ?>>
</span>
<input type="hidden" data-table="resultado" data-field="x_nombre" name="o<?php echo $resultado_grid->RowIndex ?>_nombre" id="o<?php echo $resultado_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($resultado->nombre->OldValue) ?>">
<?php } ?>
<?php if ($resultado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $resultado_grid->RowCnt ?>_resultado_nombre" class="form-group resultado_nombre">
<input type="text" data-table="resultado" data-field="x_nombre" name="x<?php echo $resultado_grid->RowIndex ?>_nombre" id="x<?php echo $resultado_grid->RowIndex ?>_nombre" size="30" maxlength="128" placeholder="<?php echo ew_HtmlEncode($resultado->nombre->getPlaceHolder()) ?>" value="<?php echo $resultado->nombre->EditValue ?>"<?php echo $resultado->nombre->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($resultado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $resultado_grid->RowCnt ?>_resultado_nombre" class="resultado_nombre">
<span<?php echo $resultado->nombre->ViewAttributes() ?>>
<?php echo $resultado->nombre->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="resultado" data-field="x_nombre" name="x<?php echo $resultado_grid->RowIndex ?>_nombre" id="x<?php echo $resultado_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($resultado->nombre->FormValue) ?>">
<input type="hidden" data-table="resultado" data-field="x_nombre" name="o<?php echo $resultado_grid->RowIndex ?>_nombre" id="o<?php echo $resultado_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($resultado->nombre->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($resultado->tiempoEstimado->Visible) { // tiempoEstimado ?>
		<td data-name="tiempoEstimado"<?php echo $resultado->tiempoEstimado->CellAttributes() ?>>
<?php if ($resultado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $resultado_grid->RowCnt ?>_resultado_tiempoEstimado" class="form-group resultado_tiempoEstimado">
<input type="text" data-table="resultado" data-field="x_tiempoEstimado" name="x<?php echo $resultado_grid->RowIndex ?>_tiempoEstimado" id="x<?php echo $resultado_grid->RowIndex ?>_tiempoEstimado" size="30" placeholder="<?php echo ew_HtmlEncode($resultado->tiempoEstimado->getPlaceHolder()) ?>" value="<?php echo $resultado->tiempoEstimado->EditValue ?>"<?php echo $resultado->tiempoEstimado->EditAttributes() ?>>
</span>
<input type="hidden" data-table="resultado" data-field="x_tiempoEstimado" name="o<?php echo $resultado_grid->RowIndex ?>_tiempoEstimado" id="o<?php echo $resultado_grid->RowIndex ?>_tiempoEstimado" value="<?php echo ew_HtmlEncode($resultado->tiempoEstimado->OldValue) ?>">
<?php } ?>
<?php if ($resultado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $resultado_grid->RowCnt ?>_resultado_tiempoEstimado" class="form-group resultado_tiempoEstimado">
<input type="text" data-table="resultado" data-field="x_tiempoEstimado" name="x<?php echo $resultado_grid->RowIndex ?>_tiempoEstimado" id="x<?php echo $resultado_grid->RowIndex ?>_tiempoEstimado" size="30" placeholder="<?php echo ew_HtmlEncode($resultado->tiempoEstimado->getPlaceHolder()) ?>" value="<?php echo $resultado->tiempoEstimado->EditValue ?>"<?php echo $resultado->tiempoEstimado->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($resultado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $resultado_grid->RowCnt ?>_resultado_tiempoEstimado" class="resultado_tiempoEstimado">
<span<?php echo $resultado->tiempoEstimado->ViewAttributes() ?>>
<?php echo $resultado->tiempoEstimado->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="resultado" data-field="x_tiempoEstimado" name="x<?php echo $resultado_grid->RowIndex ?>_tiempoEstimado" id="x<?php echo $resultado_grid->RowIndex ?>_tiempoEstimado" value="<?php echo ew_HtmlEncode($resultado->tiempoEstimado->FormValue) ?>">
<input type="hidden" data-table="resultado" data-field="x_tiempoEstimado" name="o<?php echo $resultado_grid->RowIndex ?>_tiempoEstimado" id="o<?php echo $resultado_grid->RowIndex ?>_tiempoEstimado" value="<?php echo ew_HtmlEncode($resultado->tiempoEstimado->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($resultado->tiempoTipo->Visible) { // tiempoTipo ?>
		<td data-name="tiempoTipo"<?php echo $resultado->tiempoTipo->CellAttributes() ?>>
<?php if ($resultado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $resultado_grid->RowCnt ?>_resultado_tiempoTipo" class="form-group resultado_tiempoTipo">
<select data-table="resultado" data-field="x_tiempoTipo" data-value-separator="<?php echo ew_HtmlEncode(is_array($resultado->tiempoTipo->DisplayValueSeparator) ? json_encode($resultado->tiempoTipo->DisplayValueSeparator) : $resultado->tiempoTipo->DisplayValueSeparator) ?>" id="x<?php echo $resultado_grid->RowIndex ?>_tiempoTipo" name="x<?php echo $resultado_grid->RowIndex ?>_tiempoTipo"<?php echo $resultado->tiempoTipo->EditAttributes() ?>>
<?php
if (is_array($resultado->tiempoTipo->EditValue)) {
	$arwrk = $resultado->tiempoTipo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($resultado->tiempoTipo->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $resultado->tiempoTipo->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($resultado->tiempoTipo->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($resultado->tiempoTipo->CurrentValue) ?>" selected><?php echo $resultado->tiempoTipo->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $resultado->tiempoTipo->OldValue = "";
?>
</select>
</span>
<input type="hidden" data-table="resultado" data-field="x_tiempoTipo" name="o<?php echo $resultado_grid->RowIndex ?>_tiempoTipo" id="o<?php echo $resultado_grid->RowIndex ?>_tiempoTipo" value="<?php echo ew_HtmlEncode($resultado->tiempoTipo->OldValue) ?>">
<?php } ?>
<?php if ($resultado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $resultado_grid->RowCnt ?>_resultado_tiempoTipo" class="form-group resultado_tiempoTipo">
<select data-table="resultado" data-field="x_tiempoTipo" data-value-separator="<?php echo ew_HtmlEncode(is_array($resultado->tiempoTipo->DisplayValueSeparator) ? json_encode($resultado->tiempoTipo->DisplayValueSeparator) : $resultado->tiempoTipo->DisplayValueSeparator) ?>" id="x<?php echo $resultado_grid->RowIndex ?>_tiempoTipo" name="x<?php echo $resultado_grid->RowIndex ?>_tiempoTipo"<?php echo $resultado->tiempoTipo->EditAttributes() ?>>
<?php
if (is_array($resultado->tiempoTipo->EditValue)) {
	$arwrk = $resultado->tiempoTipo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($resultado->tiempoTipo->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $resultado->tiempoTipo->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($resultado->tiempoTipo->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($resultado->tiempoTipo->CurrentValue) ?>" selected><?php echo $resultado->tiempoTipo->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $resultado->tiempoTipo->OldValue = "";
?>
</select>
</span>
<?php } ?>
<?php if ($resultado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $resultado_grid->RowCnt ?>_resultado_tiempoTipo" class="resultado_tiempoTipo">
<span<?php echo $resultado->tiempoTipo->ViewAttributes() ?>>
<?php echo $resultado->tiempoTipo->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="resultado" data-field="x_tiempoTipo" name="x<?php echo $resultado_grid->RowIndex ?>_tiempoTipo" id="x<?php echo $resultado_grid->RowIndex ?>_tiempoTipo" value="<?php echo ew_HtmlEncode($resultado->tiempoTipo->FormValue) ?>">
<input type="hidden" data-table="resultado" data-field="x_tiempoTipo" name="o<?php echo $resultado_grid->RowIndex ?>_tiempoTipo" id="o<?php echo $resultado_grid->RowIndex ?>_tiempoTipo" value="<?php echo ew_HtmlEncode($resultado->tiempoTipo->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($resultado->fechaInicio->Visible) { // fechaInicio ?>
		<td data-name="fechaInicio"<?php echo $resultado->fechaInicio->CellAttributes() ?>>
<?php if ($resultado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $resultado_grid->RowCnt ?>_resultado_fechaInicio" class="form-group resultado_fechaInicio">
<input type="text" data-table="resultado" data-field="x_fechaInicio" data-format="7" name="x<?php echo $resultado_grid->RowIndex ?>_fechaInicio" id="x<?php echo $resultado_grid->RowIndex ?>_fechaInicio" placeholder="<?php echo ew_HtmlEncode($resultado->fechaInicio->getPlaceHolder()) ?>" value="<?php echo $resultado->fechaInicio->EditValue ?>"<?php echo $resultado->fechaInicio->EditAttributes() ?>>
<?php if (!$resultado->fechaInicio->ReadOnly && !$resultado->fechaInicio->Disabled && !isset($resultado->fechaInicio->EditAttrs["readonly"]) && !isset($resultado->fechaInicio->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fresultadogrid", "x<?php echo $resultado_grid->RowIndex ?>_fechaInicio", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-table="resultado" data-field="x_fechaInicio" name="o<?php echo $resultado_grid->RowIndex ?>_fechaInicio" id="o<?php echo $resultado_grid->RowIndex ?>_fechaInicio" value="<?php echo ew_HtmlEncode($resultado->fechaInicio->OldValue) ?>">
<?php } ?>
<?php if ($resultado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $resultado_grid->RowCnt ?>_resultado_fechaInicio" class="form-group resultado_fechaInicio">
<input type="text" data-table="resultado" data-field="x_fechaInicio" data-format="7" name="x<?php echo $resultado_grid->RowIndex ?>_fechaInicio" id="x<?php echo $resultado_grid->RowIndex ?>_fechaInicio" placeholder="<?php echo ew_HtmlEncode($resultado->fechaInicio->getPlaceHolder()) ?>" value="<?php echo $resultado->fechaInicio->EditValue ?>"<?php echo $resultado->fechaInicio->EditAttributes() ?>>
<?php if (!$resultado->fechaInicio->ReadOnly && !$resultado->fechaInicio->Disabled && !isset($resultado->fechaInicio->EditAttrs["readonly"]) && !isset($resultado->fechaInicio->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fresultadogrid", "x<?php echo $resultado_grid->RowIndex ?>_fechaInicio", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($resultado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $resultado_grid->RowCnt ?>_resultado_fechaInicio" class="resultado_fechaInicio">
<span<?php echo $resultado->fechaInicio->ViewAttributes() ?>>
<?php echo $resultado->fechaInicio->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="resultado" data-field="x_fechaInicio" name="x<?php echo $resultado_grid->RowIndex ?>_fechaInicio" id="x<?php echo $resultado_grid->RowIndex ?>_fechaInicio" value="<?php echo ew_HtmlEncode($resultado->fechaInicio->FormValue) ?>">
<input type="hidden" data-table="resultado" data-field="x_fechaInicio" name="o<?php echo $resultado_grid->RowIndex ?>_fechaInicio" id="o<?php echo $resultado_grid->RowIndex ?>_fechaInicio" value="<?php echo ew_HtmlEncode($resultado->fechaInicio->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($resultado->fechaFin->Visible) { // fechaFin ?>
		<td data-name="fechaFin"<?php echo $resultado->fechaFin->CellAttributes() ?>>
<?php if ($resultado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $resultado_grid->RowCnt ?>_resultado_fechaFin" class="form-group resultado_fechaFin">
<input type="text" data-table="resultado" data-field="x_fechaFin" data-format="7" name="x<?php echo $resultado_grid->RowIndex ?>_fechaFin" id="x<?php echo $resultado_grid->RowIndex ?>_fechaFin" placeholder="<?php echo ew_HtmlEncode($resultado->fechaFin->getPlaceHolder()) ?>" value="<?php echo $resultado->fechaFin->EditValue ?>"<?php echo $resultado->fechaFin->EditAttributes() ?>>
<?php if (!$resultado->fechaFin->ReadOnly && !$resultado->fechaFin->Disabled && !isset($resultado->fechaFin->EditAttrs["readonly"]) && !isset($resultado->fechaFin->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fresultadogrid", "x<?php echo $resultado_grid->RowIndex ?>_fechaFin", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-table="resultado" data-field="x_fechaFin" name="o<?php echo $resultado_grid->RowIndex ?>_fechaFin" id="o<?php echo $resultado_grid->RowIndex ?>_fechaFin" value="<?php echo ew_HtmlEncode($resultado->fechaFin->OldValue) ?>">
<?php } ?>
<?php if ($resultado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $resultado_grid->RowCnt ?>_resultado_fechaFin" class="form-group resultado_fechaFin">
<input type="text" data-table="resultado" data-field="x_fechaFin" data-format="7" name="x<?php echo $resultado_grid->RowIndex ?>_fechaFin" id="x<?php echo $resultado_grid->RowIndex ?>_fechaFin" placeholder="<?php echo ew_HtmlEncode($resultado->fechaFin->getPlaceHolder()) ?>" value="<?php echo $resultado->fechaFin->EditValue ?>"<?php echo $resultado->fechaFin->EditAttributes() ?>>
<?php if (!$resultado->fechaFin->ReadOnly && !$resultado->fechaFin->Disabled && !isset($resultado->fechaFin->EditAttrs["readonly"]) && !isset($resultado->fechaFin->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fresultadogrid", "x<?php echo $resultado_grid->RowIndex ?>_fechaFin", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($resultado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $resultado_grid->RowCnt ?>_resultado_fechaFin" class="resultado_fechaFin">
<span<?php echo $resultado->fechaFin->ViewAttributes() ?>>
<?php echo $resultado->fechaFin->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="resultado" data-field="x_fechaFin" name="x<?php echo $resultado_grid->RowIndex ?>_fechaFin" id="x<?php echo $resultado_grid->RowIndex ?>_fechaFin" value="<?php echo ew_HtmlEncode($resultado->fechaFin->FormValue) ?>">
<input type="hidden" data-table="resultado" data-field="x_fechaFin" name="o<?php echo $resultado_grid->RowIndex ?>_fechaFin" id="o<?php echo $resultado_grid->RowIndex ?>_fechaFin" value="<?php echo ew_HtmlEncode($resultado->fechaFin->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($resultado->estatus->Visible) { // estatus ?>
		<td data-name="estatus"<?php echo $resultado->estatus->CellAttributes() ?>>
<?php if ($resultado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $resultado_grid->RowCnt ?>_resultado_estatus" class="form-group resultado_estatus">
<select data-table="resultado" data-field="x_estatus" data-value-separator="<?php echo ew_HtmlEncode(is_array($resultado->estatus->DisplayValueSeparator) ? json_encode($resultado->estatus->DisplayValueSeparator) : $resultado->estatus->DisplayValueSeparator) ?>" id="x<?php echo $resultado_grid->RowIndex ?>_estatus" name="x<?php echo $resultado_grid->RowIndex ?>_estatus"<?php echo $resultado->estatus->EditAttributes() ?>>
<?php
if (is_array($resultado->estatus->EditValue)) {
	$arwrk = $resultado->estatus->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($resultado->estatus->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $resultado->estatus->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($resultado->estatus->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($resultado->estatus->CurrentValue) ?>" selected><?php echo $resultado->estatus->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $resultado->estatus->OldValue = "";
?>
</select>
</span>
<input type="hidden" data-table="resultado" data-field="x_estatus" name="o<?php echo $resultado_grid->RowIndex ?>_estatus" id="o<?php echo $resultado_grid->RowIndex ?>_estatus" value="<?php echo ew_HtmlEncode($resultado->estatus->OldValue) ?>">
<?php } ?>
<?php if ($resultado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $resultado_grid->RowCnt ?>_resultado_estatus" class="form-group resultado_estatus">
<select data-table="resultado" data-field="x_estatus" data-value-separator="<?php echo ew_HtmlEncode(is_array($resultado->estatus->DisplayValueSeparator) ? json_encode($resultado->estatus->DisplayValueSeparator) : $resultado->estatus->DisplayValueSeparator) ?>" id="x<?php echo $resultado_grid->RowIndex ?>_estatus" name="x<?php echo $resultado_grid->RowIndex ?>_estatus"<?php echo $resultado->estatus->EditAttributes() ?>>
<?php
if (is_array($resultado->estatus->EditValue)) {
	$arwrk = $resultado->estatus->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($resultado->estatus->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $resultado->estatus->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($resultado->estatus->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($resultado->estatus->CurrentValue) ?>" selected><?php echo $resultado->estatus->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $resultado->estatus->OldValue = "";
?>
</select>
</span>
<?php } ?>
<?php if ($resultado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $resultado_grid->RowCnt ?>_resultado_estatus" class="resultado_estatus">
<span<?php echo $resultado->estatus->ViewAttributes() ?>>
<?php echo $resultado->estatus->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="resultado" data-field="x_estatus" name="x<?php echo $resultado_grid->RowIndex ?>_estatus" id="x<?php echo $resultado_grid->RowIndex ?>_estatus" value="<?php echo ew_HtmlEncode($resultado->estatus->FormValue) ?>">
<input type="hidden" data-table="resultado" data-field="x_estatus" name="o<?php echo $resultado_grid->RowIndex ?>_estatus" id="o<?php echo $resultado_grid->RowIndex ?>_estatus" value="<?php echo ew_HtmlEncode($resultado->estatus->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$resultado_grid->ListOptions->Render("body", "right", $resultado_grid->RowCnt);
?>
	</tr>
<?php if ($resultado->RowType == EW_ROWTYPE_ADD || $resultado->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fresultadogrid.UpdateOpts(<?php echo $resultado_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($resultado->CurrentAction <> "gridadd" || $resultado->CurrentMode == "copy")
		if (!$resultado_grid->Recordset->EOF) $resultado_grid->Recordset->MoveNext();
}
?>
<?php
	if ($resultado->CurrentMode == "add" || $resultado->CurrentMode == "copy" || $resultado->CurrentMode == "edit") {
		$resultado_grid->RowIndex = '$rowindex$';
		$resultado_grid->LoadDefaultValues();

		// Set row properties
		$resultado->ResetAttrs();
		$resultado->RowAttrs = array_merge($resultado->RowAttrs, array('data-rowindex'=>$resultado_grid->RowIndex, 'id'=>'r0_resultado', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($resultado->RowAttrs["class"], "ewTemplate");
		$resultado->RowType = EW_ROWTYPE_ADD;

		// Render row
		$resultado_grid->RenderRow();

		// Render list options
		$resultado_grid->RenderListOptions();
		$resultado_grid->StartRowCnt = 0;
?>
	<tr<?php echo $resultado->RowAttributes() ?>>
<?php

// Render list options (body, left)
$resultado_grid->ListOptions->Render("body", "left", $resultado_grid->RowIndex);
?>
	<?php if ($resultado->idResultado->Visible) { // idResultado ?>
		<td data-name="idResultado">
<?php if ($resultado->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_resultado_idResultado" class="form-group resultado_idResultado">
<span<?php echo $resultado->idResultado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $resultado->idResultado->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="resultado" data-field="x_idResultado" name="x<?php echo $resultado_grid->RowIndex ?>_idResultado" id="x<?php echo $resultado_grid->RowIndex ?>_idResultado" value="<?php echo ew_HtmlEncode($resultado->idResultado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="resultado" data-field="x_idResultado" name="o<?php echo $resultado_grid->RowIndex ?>_idResultado" id="o<?php echo $resultado_grid->RowIndex ?>_idResultado" value="<?php echo ew_HtmlEncode($resultado->idResultado->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($resultado->objetivo->Visible) { // objetivo ?>
		<td data-name="objetivo">
<?php if ($resultado->CurrentAction <> "F") { ?>
<?php if ($resultado->objetivo->getSessionValue() <> "") { ?>
<span id="el$rowindex$_resultado_objetivo" class="form-group resultado_objetivo">
<span<?php echo $resultado->objetivo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $resultado->objetivo->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $resultado_grid->RowIndex ?>_objetivo" name="x<?php echo $resultado_grid->RowIndex ?>_objetivo" value="<?php echo ew_HtmlEncode($resultado->objetivo->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_resultado_objetivo" class="form-group resultado_objetivo">
<select data-table="resultado" data-field="x_objetivo" data-value-separator="<?php echo ew_HtmlEncode(is_array($resultado->objetivo->DisplayValueSeparator) ? json_encode($resultado->objetivo->DisplayValueSeparator) : $resultado->objetivo->DisplayValueSeparator) ?>" id="x<?php echo $resultado_grid->RowIndex ?>_objetivo" name="x<?php echo $resultado_grid->RowIndex ?>_objetivo"<?php echo $resultado->objetivo->EditAttributes() ?>>
<?php
if (is_array($resultado->objetivo->EditValue)) {
	$arwrk = $resultado->objetivo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($resultado->objetivo->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $resultado->objetivo->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($resultado->objetivo->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($resultado->objetivo->CurrentValue) ?>" selected><?php echo $resultado->objetivo->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $resultado->objetivo->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `idObjetivo`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `objetivo`";
$sWhereWrk = "";
$resultado->objetivo->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$resultado->objetivo->LookupFilters += array("f0" => "`idObjetivo` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$resultado->Lookup_Selecting($resultado->objetivo, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $resultado->objetivo->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $resultado_grid->RowIndex ?>_objetivo" id="s_x<?php echo $resultado_grid->RowIndex ?>_objetivo" value="<?php echo $resultado->objetivo->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_resultado_objetivo" class="form-group resultado_objetivo">
<span<?php echo $resultado->objetivo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $resultado->objetivo->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="resultado" data-field="x_objetivo" name="x<?php echo $resultado_grid->RowIndex ?>_objetivo" id="x<?php echo $resultado_grid->RowIndex ?>_objetivo" value="<?php echo ew_HtmlEncode($resultado->objetivo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="resultado" data-field="x_objetivo" name="o<?php echo $resultado_grid->RowIndex ?>_objetivo" id="o<?php echo $resultado_grid->RowIndex ?>_objetivo" value="<?php echo ew_HtmlEncode($resultado->objetivo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($resultado->nombre->Visible) { // nombre ?>
		<td data-name="nombre">
<?php if ($resultado->CurrentAction <> "F") { ?>
<span id="el$rowindex$_resultado_nombre" class="form-group resultado_nombre">
<input type="text" data-table="resultado" data-field="x_nombre" name="x<?php echo $resultado_grid->RowIndex ?>_nombre" id="x<?php echo $resultado_grid->RowIndex ?>_nombre" size="30" maxlength="128" placeholder="<?php echo ew_HtmlEncode($resultado->nombre->getPlaceHolder()) ?>" value="<?php echo $resultado->nombre->EditValue ?>"<?php echo $resultado->nombre->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_resultado_nombre" class="form-group resultado_nombre">
<span<?php echo $resultado->nombre->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $resultado->nombre->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="resultado" data-field="x_nombre" name="x<?php echo $resultado_grid->RowIndex ?>_nombre" id="x<?php echo $resultado_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($resultado->nombre->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="resultado" data-field="x_nombre" name="o<?php echo $resultado_grid->RowIndex ?>_nombre" id="o<?php echo $resultado_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($resultado->nombre->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($resultado->tiempoEstimado->Visible) { // tiempoEstimado ?>
		<td data-name="tiempoEstimado">
<?php if ($resultado->CurrentAction <> "F") { ?>
<span id="el$rowindex$_resultado_tiempoEstimado" class="form-group resultado_tiempoEstimado">
<input type="text" data-table="resultado" data-field="x_tiempoEstimado" name="x<?php echo $resultado_grid->RowIndex ?>_tiempoEstimado" id="x<?php echo $resultado_grid->RowIndex ?>_tiempoEstimado" size="30" placeholder="<?php echo ew_HtmlEncode($resultado->tiempoEstimado->getPlaceHolder()) ?>" value="<?php echo $resultado->tiempoEstimado->EditValue ?>"<?php echo $resultado->tiempoEstimado->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_resultado_tiempoEstimado" class="form-group resultado_tiempoEstimado">
<span<?php echo $resultado->tiempoEstimado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $resultado->tiempoEstimado->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="resultado" data-field="x_tiempoEstimado" name="x<?php echo $resultado_grid->RowIndex ?>_tiempoEstimado" id="x<?php echo $resultado_grid->RowIndex ?>_tiempoEstimado" value="<?php echo ew_HtmlEncode($resultado->tiempoEstimado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="resultado" data-field="x_tiempoEstimado" name="o<?php echo $resultado_grid->RowIndex ?>_tiempoEstimado" id="o<?php echo $resultado_grid->RowIndex ?>_tiempoEstimado" value="<?php echo ew_HtmlEncode($resultado->tiempoEstimado->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($resultado->tiempoTipo->Visible) { // tiempoTipo ?>
		<td data-name="tiempoTipo">
<?php if ($resultado->CurrentAction <> "F") { ?>
<span id="el$rowindex$_resultado_tiempoTipo" class="form-group resultado_tiempoTipo">
<select data-table="resultado" data-field="x_tiempoTipo" data-value-separator="<?php echo ew_HtmlEncode(is_array($resultado->tiempoTipo->DisplayValueSeparator) ? json_encode($resultado->tiempoTipo->DisplayValueSeparator) : $resultado->tiempoTipo->DisplayValueSeparator) ?>" id="x<?php echo $resultado_grid->RowIndex ?>_tiempoTipo" name="x<?php echo $resultado_grid->RowIndex ?>_tiempoTipo"<?php echo $resultado->tiempoTipo->EditAttributes() ?>>
<?php
if (is_array($resultado->tiempoTipo->EditValue)) {
	$arwrk = $resultado->tiempoTipo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($resultado->tiempoTipo->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $resultado->tiempoTipo->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($resultado->tiempoTipo->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($resultado->tiempoTipo->CurrentValue) ?>" selected><?php echo $resultado->tiempoTipo->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $resultado->tiempoTipo->OldValue = "";
?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_resultado_tiempoTipo" class="form-group resultado_tiempoTipo">
<span<?php echo $resultado->tiempoTipo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $resultado->tiempoTipo->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="resultado" data-field="x_tiempoTipo" name="x<?php echo $resultado_grid->RowIndex ?>_tiempoTipo" id="x<?php echo $resultado_grid->RowIndex ?>_tiempoTipo" value="<?php echo ew_HtmlEncode($resultado->tiempoTipo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="resultado" data-field="x_tiempoTipo" name="o<?php echo $resultado_grid->RowIndex ?>_tiempoTipo" id="o<?php echo $resultado_grid->RowIndex ?>_tiempoTipo" value="<?php echo ew_HtmlEncode($resultado->tiempoTipo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($resultado->fechaInicio->Visible) { // fechaInicio ?>
		<td data-name="fechaInicio">
<?php if ($resultado->CurrentAction <> "F") { ?>
<span id="el$rowindex$_resultado_fechaInicio" class="form-group resultado_fechaInicio">
<input type="text" data-table="resultado" data-field="x_fechaInicio" data-format="7" name="x<?php echo $resultado_grid->RowIndex ?>_fechaInicio" id="x<?php echo $resultado_grid->RowIndex ?>_fechaInicio" placeholder="<?php echo ew_HtmlEncode($resultado->fechaInicio->getPlaceHolder()) ?>" value="<?php echo $resultado->fechaInicio->EditValue ?>"<?php echo $resultado->fechaInicio->EditAttributes() ?>>
<?php if (!$resultado->fechaInicio->ReadOnly && !$resultado->fechaInicio->Disabled && !isset($resultado->fechaInicio->EditAttrs["readonly"]) && !isset($resultado->fechaInicio->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fresultadogrid", "x<?php echo $resultado_grid->RowIndex ?>_fechaInicio", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_resultado_fechaInicio" class="form-group resultado_fechaInicio">
<span<?php echo $resultado->fechaInicio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $resultado->fechaInicio->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="resultado" data-field="x_fechaInicio" name="x<?php echo $resultado_grid->RowIndex ?>_fechaInicio" id="x<?php echo $resultado_grid->RowIndex ?>_fechaInicio" value="<?php echo ew_HtmlEncode($resultado->fechaInicio->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="resultado" data-field="x_fechaInicio" name="o<?php echo $resultado_grid->RowIndex ?>_fechaInicio" id="o<?php echo $resultado_grid->RowIndex ?>_fechaInicio" value="<?php echo ew_HtmlEncode($resultado->fechaInicio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($resultado->fechaFin->Visible) { // fechaFin ?>
		<td data-name="fechaFin">
<?php if ($resultado->CurrentAction <> "F") { ?>
<span id="el$rowindex$_resultado_fechaFin" class="form-group resultado_fechaFin">
<input type="text" data-table="resultado" data-field="x_fechaFin" data-format="7" name="x<?php echo $resultado_grid->RowIndex ?>_fechaFin" id="x<?php echo $resultado_grid->RowIndex ?>_fechaFin" placeholder="<?php echo ew_HtmlEncode($resultado->fechaFin->getPlaceHolder()) ?>" value="<?php echo $resultado->fechaFin->EditValue ?>"<?php echo $resultado->fechaFin->EditAttributes() ?>>
<?php if (!$resultado->fechaFin->ReadOnly && !$resultado->fechaFin->Disabled && !isset($resultado->fechaFin->EditAttrs["readonly"]) && !isset($resultado->fechaFin->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fresultadogrid", "x<?php echo $resultado_grid->RowIndex ?>_fechaFin", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_resultado_fechaFin" class="form-group resultado_fechaFin">
<span<?php echo $resultado->fechaFin->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $resultado->fechaFin->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="resultado" data-field="x_fechaFin" name="x<?php echo $resultado_grid->RowIndex ?>_fechaFin" id="x<?php echo $resultado_grid->RowIndex ?>_fechaFin" value="<?php echo ew_HtmlEncode($resultado->fechaFin->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="resultado" data-field="x_fechaFin" name="o<?php echo $resultado_grid->RowIndex ?>_fechaFin" id="o<?php echo $resultado_grid->RowIndex ?>_fechaFin" value="<?php echo ew_HtmlEncode($resultado->fechaFin->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($resultado->estatus->Visible) { // estatus ?>
		<td data-name="estatus">
<?php if ($resultado->CurrentAction <> "F") { ?>
<span id="el$rowindex$_resultado_estatus" class="form-group resultado_estatus">
<select data-table="resultado" data-field="x_estatus" data-value-separator="<?php echo ew_HtmlEncode(is_array($resultado->estatus->DisplayValueSeparator) ? json_encode($resultado->estatus->DisplayValueSeparator) : $resultado->estatus->DisplayValueSeparator) ?>" id="x<?php echo $resultado_grid->RowIndex ?>_estatus" name="x<?php echo $resultado_grid->RowIndex ?>_estatus"<?php echo $resultado->estatus->EditAttributes() ?>>
<?php
if (is_array($resultado->estatus->EditValue)) {
	$arwrk = $resultado->estatus->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($resultado->estatus->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $resultado->estatus->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($resultado->estatus->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($resultado->estatus->CurrentValue) ?>" selected><?php echo $resultado->estatus->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $resultado->estatus->OldValue = "";
?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_resultado_estatus" class="form-group resultado_estatus">
<span<?php echo $resultado->estatus->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $resultado->estatus->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="resultado" data-field="x_estatus" name="x<?php echo $resultado_grid->RowIndex ?>_estatus" id="x<?php echo $resultado_grid->RowIndex ?>_estatus" value="<?php echo ew_HtmlEncode($resultado->estatus->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="resultado" data-field="x_estatus" name="o<?php echo $resultado_grid->RowIndex ?>_estatus" id="o<?php echo $resultado_grid->RowIndex ?>_estatus" value="<?php echo ew_HtmlEncode($resultado->estatus->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$resultado_grid->ListOptions->Render("body", "right", $resultado_grid->RowCnt);
?>
<script type="text/javascript">
fresultadogrid.UpdateOpts(<?php echo $resultado_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($resultado->CurrentMode == "add" || $resultado->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $resultado_grid->FormKeyCountName ?>" id="<?php echo $resultado_grid->FormKeyCountName ?>" value="<?php echo $resultado_grid->KeyCount ?>">
<?php echo $resultado_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($resultado->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $resultado_grid->FormKeyCountName ?>" id="<?php echo $resultado_grid->FormKeyCountName ?>" value="<?php echo $resultado_grid->KeyCount ?>">
<?php echo $resultado_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($resultado->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fresultadogrid">
</div>
<?php

// Close recordset
if ($resultado_grid->Recordset)
	$resultado_grid->Recordset->Close();
?>
<?php if ($resultado_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($resultado_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($resultado_grid->TotalRecs == 0 && $resultado->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($resultado_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($resultado->Export == "") { ?>
<script type="text/javascript">
fresultadogrid.Init();
</script>
<?php } ?>
<?php
$resultado_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$resultado_grid->Page_Terminate();
?>
