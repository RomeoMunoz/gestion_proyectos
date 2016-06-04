<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($objetivo_grid)) $objetivo_grid = new cobjetivo_grid();

// Page init
$objetivo_grid->Page_Init();

// Page main
$objetivo_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$objetivo_grid->Page_Render();
?>
<?php if ($objetivo->Export == "") { ?>
<script type="text/javascript">

// Form object
var fobjetivogrid = new ew_Form("fobjetivogrid", "grid");
fobjetivogrid.FormKeyCountName = '<?php echo $objetivo_grid->FormKeyCountName ?>';

// Validate form
fobjetivogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_idObjetivo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($objetivo->idObjetivo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nombre");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $objetivo->nombre->FldCaption(), $objetivo->nombre->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_duracion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $objetivo->duracion->FldCaption(), $objetivo->duracion->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_duracion");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($objetivo->duracion->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fechaInicio");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $objetivo->fechaInicio->FldCaption(), $objetivo->fechaInicio->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fechaInicio");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($objetivo->fechaInicio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fechFin");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $objetivo->fechFin->FldCaption(), $objetivo->fechFin->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fechFin");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($objetivo->fechFin->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_proyecto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $objetivo->proyecto->FldCaption(), $objetivo->proyecto->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fobjetivogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nombre", false)) return false;
	if (ew_ValueChanged(fobj, infix, "comentarios", false)) return false;
	if (ew_ValueChanged(fobj, infix, "duracion", false)) return false;
	if (ew_ValueChanged(fobj, infix, "formatoDuracion", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fechaInicio", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fechFin", false)) return false;
	if (ew_ValueChanged(fobj, infix, "proyecto", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tipo", false)) return false;
	return true;
}

// Form_CustomValidate event
fobjetivogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fobjetivogrid.ValidateRequired = true;
<?php } else { ?>
fobjetivogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fobjetivogrid.Lists["x_formatoDuracion"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fobjetivogrid.Lists["x_formatoDuracion"].Options = <?php echo json_encode($objetivo->formatoDuracion->Options()) ?>;
fobjetivogrid.Lists["x_proyecto"] = {"LinkField":"x_idProyecto","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fobjetivogrid.Lists["x_tipo"] = {"LinkField":"x_idObjetivosTipo","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<?php } ?>
<?php
if ($objetivo->CurrentAction == "gridadd") {
	if ($objetivo->CurrentMode == "copy") {
		$bSelectLimit = $objetivo_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$objetivo_grid->TotalRecs = $objetivo->SelectRecordCount();
			$objetivo_grid->Recordset = $objetivo_grid->LoadRecordset($objetivo_grid->StartRec-1, $objetivo_grid->DisplayRecs);
		} else {
			if ($objetivo_grid->Recordset = $objetivo_grid->LoadRecordset())
				$objetivo_grid->TotalRecs = $objetivo_grid->Recordset->RecordCount();
		}
		$objetivo_grid->StartRec = 1;
		$objetivo_grid->DisplayRecs = $objetivo_grid->TotalRecs;
	} else {
		$objetivo->CurrentFilter = "0=1";
		$objetivo_grid->StartRec = 1;
		$objetivo_grid->DisplayRecs = $objetivo->GridAddRowCount;
	}
	$objetivo_grid->TotalRecs = $objetivo_grid->DisplayRecs;
	$objetivo_grid->StopRec = $objetivo_grid->DisplayRecs;
} else {
	$bSelectLimit = $objetivo_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($objetivo_grid->TotalRecs <= 0)
			$objetivo_grid->TotalRecs = $objetivo->SelectRecordCount();
	} else {
		if (!$objetivo_grid->Recordset && ($objetivo_grid->Recordset = $objetivo_grid->LoadRecordset()))
			$objetivo_grid->TotalRecs = $objetivo_grid->Recordset->RecordCount();
	}
	$objetivo_grid->StartRec = 1;
	$objetivo_grid->DisplayRecs = $objetivo_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$objetivo_grid->Recordset = $objetivo_grid->LoadRecordset($objetivo_grid->StartRec-1, $objetivo_grid->DisplayRecs);

	// Set no record found message
	if ($objetivo->CurrentAction == "" && $objetivo_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$objetivo_grid->setWarningMessage(ew_DeniedMsg());
		if ($objetivo_grid->SearchWhere == "0=101")
			$objetivo_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$objetivo_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$objetivo_grid->RenderOtherOptions();
?>
<?php $objetivo_grid->ShowPageHeader(); ?>
<?php
$objetivo_grid->ShowMessage();
?>
<?php if ($objetivo_grid->TotalRecs > 0 || $objetivo->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<div id="fobjetivogrid" class="ewForm form-inline">
<?php if ($objetivo_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($objetivo_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_objetivo" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_objetivogrid" class="table ewTable">
<?php echo $objetivo->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$objetivo_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$objetivo_grid->RenderListOptions();

// Render list options (header, left)
$objetivo_grid->ListOptions->Render("header", "left");
?>
<?php if ($objetivo->idObjetivo->Visible) { // idObjetivo ?>
	<?php if ($objetivo->SortUrl($objetivo->idObjetivo) == "") { ?>
		<th data-name="idObjetivo"><div id="elh_objetivo_idObjetivo" class="objetivo_idObjetivo"><div class="ewTableHeaderCaption"><?php echo $objetivo->idObjetivo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idObjetivo"><div><div id="elh_objetivo_idObjetivo" class="objetivo_idObjetivo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $objetivo->idObjetivo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($objetivo->idObjetivo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($objetivo->idObjetivo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($objetivo->nombre->Visible) { // nombre ?>
	<?php if ($objetivo->SortUrl($objetivo->nombre) == "") { ?>
		<th data-name="nombre"><div id="elh_objetivo_nombre" class="objetivo_nombre"><div class="ewTableHeaderCaption"><?php echo $objetivo->nombre->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombre"><div><div id="elh_objetivo_nombre" class="objetivo_nombre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $objetivo->nombre->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($objetivo->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($objetivo->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($objetivo->comentarios->Visible) { // comentarios ?>
	<?php if ($objetivo->SortUrl($objetivo->comentarios) == "") { ?>
		<th data-name="comentarios"><div id="elh_objetivo_comentarios" class="objetivo_comentarios"><div class="ewTableHeaderCaption"><?php echo $objetivo->comentarios->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="comentarios"><div><div id="elh_objetivo_comentarios" class="objetivo_comentarios">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $objetivo->comentarios->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($objetivo->comentarios->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($objetivo->comentarios->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($objetivo->duracion->Visible) { // duracion ?>
	<?php if ($objetivo->SortUrl($objetivo->duracion) == "") { ?>
		<th data-name="duracion"><div id="elh_objetivo_duracion" class="objetivo_duracion"><div class="ewTableHeaderCaption"><?php echo $objetivo->duracion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="duracion"><div><div id="elh_objetivo_duracion" class="objetivo_duracion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $objetivo->duracion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($objetivo->duracion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($objetivo->duracion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($objetivo->formatoDuracion->Visible) { // formatoDuracion ?>
	<?php if ($objetivo->SortUrl($objetivo->formatoDuracion) == "") { ?>
		<th data-name="formatoDuracion"><div id="elh_objetivo_formatoDuracion" class="objetivo_formatoDuracion"><div class="ewTableHeaderCaption"><?php echo $objetivo->formatoDuracion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="formatoDuracion"><div><div id="elh_objetivo_formatoDuracion" class="objetivo_formatoDuracion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $objetivo->formatoDuracion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($objetivo->formatoDuracion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($objetivo->formatoDuracion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($objetivo->fechaInicio->Visible) { // fechaInicio ?>
	<?php if ($objetivo->SortUrl($objetivo->fechaInicio) == "") { ?>
		<th data-name="fechaInicio"><div id="elh_objetivo_fechaInicio" class="objetivo_fechaInicio"><div class="ewTableHeaderCaption"><?php echo $objetivo->fechaInicio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechaInicio"><div><div id="elh_objetivo_fechaInicio" class="objetivo_fechaInicio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $objetivo->fechaInicio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($objetivo->fechaInicio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($objetivo->fechaInicio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($objetivo->fechFin->Visible) { // fechFin ?>
	<?php if ($objetivo->SortUrl($objetivo->fechFin) == "") { ?>
		<th data-name="fechFin"><div id="elh_objetivo_fechFin" class="objetivo_fechFin"><div class="ewTableHeaderCaption"><?php echo $objetivo->fechFin->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechFin"><div><div id="elh_objetivo_fechFin" class="objetivo_fechFin">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $objetivo->fechFin->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($objetivo->fechFin->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($objetivo->fechFin->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($objetivo->proyecto->Visible) { // proyecto ?>
	<?php if ($objetivo->SortUrl($objetivo->proyecto) == "") { ?>
		<th data-name="proyecto"><div id="elh_objetivo_proyecto" class="objetivo_proyecto"><div class="ewTableHeaderCaption"><?php echo $objetivo->proyecto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="proyecto"><div><div id="elh_objetivo_proyecto" class="objetivo_proyecto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $objetivo->proyecto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($objetivo->proyecto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($objetivo->proyecto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($objetivo->tipo->Visible) { // tipo ?>
	<?php if ($objetivo->SortUrl($objetivo->tipo) == "") { ?>
		<th data-name="tipo"><div id="elh_objetivo_tipo" class="objetivo_tipo"><div class="ewTableHeaderCaption"><?php echo $objetivo->tipo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipo"><div><div id="elh_objetivo_tipo" class="objetivo_tipo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $objetivo->tipo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($objetivo->tipo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($objetivo->tipo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$objetivo_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$objetivo_grid->StartRec = 1;
$objetivo_grid->StopRec = $objetivo_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($objetivo_grid->FormKeyCountName) && ($objetivo->CurrentAction == "gridadd" || $objetivo->CurrentAction == "gridedit" || $objetivo->CurrentAction == "F")) {
		$objetivo_grid->KeyCount = $objForm->GetValue($objetivo_grid->FormKeyCountName);
		$objetivo_grid->StopRec = $objetivo_grid->StartRec + $objetivo_grid->KeyCount - 1;
	}
}
$objetivo_grid->RecCnt = $objetivo_grid->StartRec - 1;
if ($objetivo_grid->Recordset && !$objetivo_grid->Recordset->EOF) {
	$objetivo_grid->Recordset->MoveFirst();
	$bSelectLimit = $objetivo_grid->UseSelectLimit;
	if (!$bSelectLimit && $objetivo_grid->StartRec > 1)
		$objetivo_grid->Recordset->Move($objetivo_grid->StartRec - 1);
} elseif (!$objetivo->AllowAddDeleteRow && $objetivo_grid->StopRec == 0) {
	$objetivo_grid->StopRec = $objetivo->GridAddRowCount;
}

// Initialize aggregate
$objetivo->RowType = EW_ROWTYPE_AGGREGATEINIT;
$objetivo->ResetAttrs();
$objetivo_grid->RenderRow();
if ($objetivo->CurrentAction == "gridadd")
	$objetivo_grid->RowIndex = 0;
if ($objetivo->CurrentAction == "gridedit")
	$objetivo_grid->RowIndex = 0;
while ($objetivo_grid->RecCnt < $objetivo_grid->StopRec) {
	$objetivo_grid->RecCnt++;
	if (intval($objetivo_grid->RecCnt) >= intval($objetivo_grid->StartRec)) {
		$objetivo_grid->RowCnt++;
		if ($objetivo->CurrentAction == "gridadd" || $objetivo->CurrentAction == "gridedit" || $objetivo->CurrentAction == "F") {
			$objetivo_grid->RowIndex++;
			$objForm->Index = $objetivo_grid->RowIndex;
			if ($objForm->HasValue($objetivo_grid->FormActionName))
				$objetivo_grid->RowAction = strval($objForm->GetValue($objetivo_grid->FormActionName));
			elseif ($objetivo->CurrentAction == "gridadd")
				$objetivo_grid->RowAction = "insert";
			else
				$objetivo_grid->RowAction = "";
		}

		// Set up key count
		$objetivo_grid->KeyCount = $objetivo_grid->RowIndex;

		// Init row class and style
		$objetivo->ResetAttrs();
		$objetivo->CssClass = "";
		if ($objetivo->CurrentAction == "gridadd") {
			if ($objetivo->CurrentMode == "copy") {
				$objetivo_grid->LoadRowValues($objetivo_grid->Recordset); // Load row values
				$objetivo_grid->SetRecordKey($objetivo_grid->RowOldKey, $objetivo_grid->Recordset); // Set old record key
			} else {
				$objetivo_grid->LoadDefaultValues(); // Load default values
				$objetivo_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$objetivo_grid->LoadRowValues($objetivo_grid->Recordset); // Load row values
		}
		$objetivo->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($objetivo->CurrentAction == "gridadd") // Grid add
			$objetivo->RowType = EW_ROWTYPE_ADD; // Render add
		if ($objetivo->CurrentAction == "gridadd" && $objetivo->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$objetivo_grid->RestoreCurrentRowFormValues($objetivo_grid->RowIndex); // Restore form values
		if ($objetivo->CurrentAction == "gridedit") { // Grid edit
			if ($objetivo->EventCancelled) {
				$objetivo_grid->RestoreCurrentRowFormValues($objetivo_grid->RowIndex); // Restore form values
			}
			if ($objetivo_grid->RowAction == "insert")
				$objetivo->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$objetivo->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($objetivo->CurrentAction == "gridedit" && ($objetivo->RowType == EW_ROWTYPE_EDIT || $objetivo->RowType == EW_ROWTYPE_ADD) && $objetivo->EventCancelled) // Update failed
			$objetivo_grid->RestoreCurrentRowFormValues($objetivo_grid->RowIndex); // Restore form values
		if ($objetivo->RowType == EW_ROWTYPE_EDIT) // Edit row
			$objetivo_grid->EditRowCnt++;
		if ($objetivo->CurrentAction == "F") // Confirm row
			$objetivo_grid->RestoreCurrentRowFormValues($objetivo_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$objetivo->RowAttrs = array_merge($objetivo->RowAttrs, array('data-rowindex'=>$objetivo_grid->RowCnt, 'id'=>'r' . $objetivo_grid->RowCnt . '_objetivo', 'data-rowtype'=>$objetivo->RowType));

		// Render row
		$objetivo_grid->RenderRow();

		// Render list options
		$objetivo_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($objetivo_grid->RowAction <> "delete" && $objetivo_grid->RowAction <> "insertdelete" && !($objetivo_grid->RowAction == "insert" && $objetivo->CurrentAction == "F" && $objetivo_grid->EmptyRow())) {
?>
	<tr<?php echo $objetivo->RowAttributes() ?>>
<?php

// Render list options (body, left)
$objetivo_grid->ListOptions->Render("body", "left", $objetivo_grid->RowCnt);
?>
	<?php if ($objetivo->idObjetivo->Visible) { // idObjetivo ?>
		<td data-name="idObjetivo"<?php echo $objetivo->idObjetivo->CellAttributes() ?>>
<?php if ($objetivo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="objetivo" data-field="x_idObjetivo" name="o<?php echo $objetivo_grid->RowIndex ?>_idObjetivo" id="o<?php echo $objetivo_grid->RowIndex ?>_idObjetivo" value="<?php echo ew_HtmlEncode($objetivo->idObjetivo->OldValue) ?>">
<?php } ?>
<?php if ($objetivo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_idObjetivo" class="form-group objetivo_idObjetivo">
<span<?php echo $objetivo->idObjetivo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $objetivo->idObjetivo->EditValue ?></p></span>
</span>
<input type="hidden" data-table="objetivo" data-field="x_idObjetivo" name="x<?php echo $objetivo_grid->RowIndex ?>_idObjetivo" id="x<?php echo $objetivo_grid->RowIndex ?>_idObjetivo" value="<?php echo ew_HtmlEncode($objetivo->idObjetivo->CurrentValue) ?>">
<?php } ?>
<?php if ($objetivo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_idObjetivo" class="objetivo_idObjetivo">
<span<?php echo $objetivo->idObjetivo->ViewAttributes() ?>>
<?php echo $objetivo->idObjetivo->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="objetivo" data-field="x_idObjetivo" name="x<?php echo $objetivo_grid->RowIndex ?>_idObjetivo" id="x<?php echo $objetivo_grid->RowIndex ?>_idObjetivo" value="<?php echo ew_HtmlEncode($objetivo->idObjetivo->FormValue) ?>">
<input type="hidden" data-table="objetivo" data-field="x_idObjetivo" name="o<?php echo $objetivo_grid->RowIndex ?>_idObjetivo" id="o<?php echo $objetivo_grid->RowIndex ?>_idObjetivo" value="<?php echo ew_HtmlEncode($objetivo->idObjetivo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $objetivo_grid->PageObjName . "_row_" . $objetivo_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($objetivo->nombre->Visible) { // nombre ?>
		<td data-name="nombre"<?php echo $objetivo->nombre->CellAttributes() ?>>
<?php if ($objetivo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_nombre" class="form-group objetivo_nombre">
<input type="text" data-table="objetivo" data-field="x_nombre" name="x<?php echo $objetivo_grid->RowIndex ?>_nombre" id="x<?php echo $objetivo_grid->RowIndex ?>_nombre" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($objetivo->nombre->getPlaceHolder()) ?>" value="<?php echo $objetivo->nombre->EditValue ?>"<?php echo $objetivo->nombre->EditAttributes() ?>>
</span>
<input type="hidden" data-table="objetivo" data-field="x_nombre" name="o<?php echo $objetivo_grid->RowIndex ?>_nombre" id="o<?php echo $objetivo_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($objetivo->nombre->OldValue) ?>">
<?php } ?>
<?php if ($objetivo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_nombre" class="form-group objetivo_nombre">
<input type="text" data-table="objetivo" data-field="x_nombre" name="x<?php echo $objetivo_grid->RowIndex ?>_nombre" id="x<?php echo $objetivo_grid->RowIndex ?>_nombre" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($objetivo->nombre->getPlaceHolder()) ?>" value="<?php echo $objetivo->nombre->EditValue ?>"<?php echo $objetivo->nombre->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($objetivo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_nombre" class="objetivo_nombre">
<span<?php echo $objetivo->nombre->ViewAttributes() ?>>
<?php echo $objetivo->nombre->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="objetivo" data-field="x_nombre" name="x<?php echo $objetivo_grid->RowIndex ?>_nombre" id="x<?php echo $objetivo_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($objetivo->nombre->FormValue) ?>">
<input type="hidden" data-table="objetivo" data-field="x_nombre" name="o<?php echo $objetivo_grid->RowIndex ?>_nombre" id="o<?php echo $objetivo_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($objetivo->nombre->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($objetivo->comentarios->Visible) { // comentarios ?>
		<td data-name="comentarios"<?php echo $objetivo->comentarios->CellAttributes() ?>>
<?php if ($objetivo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_comentarios" class="form-group objetivo_comentarios">
<input type="text" data-table="objetivo" data-field="x_comentarios" name="x<?php echo $objetivo_grid->RowIndex ?>_comentarios" id="x<?php echo $objetivo_grid->RowIndex ?>_comentarios" size="30" maxlength="128" placeholder="<?php echo ew_HtmlEncode($objetivo->comentarios->getPlaceHolder()) ?>" value="<?php echo $objetivo->comentarios->EditValue ?>"<?php echo $objetivo->comentarios->EditAttributes() ?>>
</span>
<input type="hidden" data-table="objetivo" data-field="x_comentarios" name="o<?php echo $objetivo_grid->RowIndex ?>_comentarios" id="o<?php echo $objetivo_grid->RowIndex ?>_comentarios" value="<?php echo ew_HtmlEncode($objetivo->comentarios->OldValue) ?>">
<?php } ?>
<?php if ($objetivo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_comentarios" class="form-group objetivo_comentarios">
<input type="text" data-table="objetivo" data-field="x_comentarios" name="x<?php echo $objetivo_grid->RowIndex ?>_comentarios" id="x<?php echo $objetivo_grid->RowIndex ?>_comentarios" size="30" maxlength="128" placeholder="<?php echo ew_HtmlEncode($objetivo->comentarios->getPlaceHolder()) ?>" value="<?php echo $objetivo->comentarios->EditValue ?>"<?php echo $objetivo->comentarios->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($objetivo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_comentarios" class="objetivo_comentarios">
<span<?php echo $objetivo->comentarios->ViewAttributes() ?>>
<?php echo $objetivo->comentarios->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="objetivo" data-field="x_comentarios" name="x<?php echo $objetivo_grid->RowIndex ?>_comentarios" id="x<?php echo $objetivo_grid->RowIndex ?>_comentarios" value="<?php echo ew_HtmlEncode($objetivo->comentarios->FormValue) ?>">
<input type="hidden" data-table="objetivo" data-field="x_comentarios" name="o<?php echo $objetivo_grid->RowIndex ?>_comentarios" id="o<?php echo $objetivo_grid->RowIndex ?>_comentarios" value="<?php echo ew_HtmlEncode($objetivo->comentarios->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($objetivo->duracion->Visible) { // duracion ?>
		<td data-name="duracion"<?php echo $objetivo->duracion->CellAttributes() ?>>
<?php if ($objetivo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_duracion" class="form-group objetivo_duracion">
<input type="text" data-table="objetivo" data-field="x_duracion" name="x<?php echo $objetivo_grid->RowIndex ?>_duracion" id="x<?php echo $objetivo_grid->RowIndex ?>_duracion" size="30" placeholder="<?php echo ew_HtmlEncode($objetivo->duracion->getPlaceHolder()) ?>" value="<?php echo $objetivo->duracion->EditValue ?>"<?php echo $objetivo->duracion->EditAttributes() ?>>
</span>
<input type="hidden" data-table="objetivo" data-field="x_duracion" name="o<?php echo $objetivo_grid->RowIndex ?>_duracion" id="o<?php echo $objetivo_grid->RowIndex ?>_duracion" value="<?php echo ew_HtmlEncode($objetivo->duracion->OldValue) ?>">
<?php } ?>
<?php if ($objetivo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_duracion" class="form-group objetivo_duracion">
<input type="text" data-table="objetivo" data-field="x_duracion" name="x<?php echo $objetivo_grid->RowIndex ?>_duracion" id="x<?php echo $objetivo_grid->RowIndex ?>_duracion" size="30" placeholder="<?php echo ew_HtmlEncode($objetivo->duracion->getPlaceHolder()) ?>" value="<?php echo $objetivo->duracion->EditValue ?>"<?php echo $objetivo->duracion->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($objetivo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_duracion" class="objetivo_duracion">
<span<?php echo $objetivo->duracion->ViewAttributes() ?>>
<?php echo $objetivo->duracion->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="objetivo" data-field="x_duracion" name="x<?php echo $objetivo_grid->RowIndex ?>_duracion" id="x<?php echo $objetivo_grid->RowIndex ?>_duracion" value="<?php echo ew_HtmlEncode($objetivo->duracion->FormValue) ?>">
<input type="hidden" data-table="objetivo" data-field="x_duracion" name="o<?php echo $objetivo_grid->RowIndex ?>_duracion" id="o<?php echo $objetivo_grid->RowIndex ?>_duracion" value="<?php echo ew_HtmlEncode($objetivo->duracion->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($objetivo->formatoDuracion->Visible) { // formatoDuracion ?>
		<td data-name="formatoDuracion"<?php echo $objetivo->formatoDuracion->CellAttributes() ?>>
<?php if ($objetivo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_formatoDuracion" class="form-group objetivo_formatoDuracion">
<select data-table="objetivo" data-field="x_formatoDuracion" data-value-separator="<?php echo ew_HtmlEncode(is_array($objetivo->formatoDuracion->DisplayValueSeparator) ? json_encode($objetivo->formatoDuracion->DisplayValueSeparator) : $objetivo->formatoDuracion->DisplayValueSeparator) ?>" id="x<?php echo $objetivo_grid->RowIndex ?>_formatoDuracion" name="x<?php echo $objetivo_grid->RowIndex ?>_formatoDuracion"<?php echo $objetivo->formatoDuracion->EditAttributes() ?>>
<?php
if (is_array($objetivo->formatoDuracion->EditValue)) {
	$arwrk = $objetivo->formatoDuracion->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($objetivo->formatoDuracion->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $objetivo->formatoDuracion->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($objetivo->formatoDuracion->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($objetivo->formatoDuracion->CurrentValue) ?>" selected><?php echo $objetivo->formatoDuracion->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $objetivo->formatoDuracion->OldValue = "";
?>
</select>
</span>
<input type="hidden" data-table="objetivo" data-field="x_formatoDuracion" name="o<?php echo $objetivo_grid->RowIndex ?>_formatoDuracion" id="o<?php echo $objetivo_grid->RowIndex ?>_formatoDuracion" value="<?php echo ew_HtmlEncode($objetivo->formatoDuracion->OldValue) ?>">
<?php } ?>
<?php if ($objetivo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_formatoDuracion" class="form-group objetivo_formatoDuracion">
<select data-table="objetivo" data-field="x_formatoDuracion" data-value-separator="<?php echo ew_HtmlEncode(is_array($objetivo->formatoDuracion->DisplayValueSeparator) ? json_encode($objetivo->formatoDuracion->DisplayValueSeparator) : $objetivo->formatoDuracion->DisplayValueSeparator) ?>" id="x<?php echo $objetivo_grid->RowIndex ?>_formatoDuracion" name="x<?php echo $objetivo_grid->RowIndex ?>_formatoDuracion"<?php echo $objetivo->formatoDuracion->EditAttributes() ?>>
<?php
if (is_array($objetivo->formatoDuracion->EditValue)) {
	$arwrk = $objetivo->formatoDuracion->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($objetivo->formatoDuracion->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $objetivo->formatoDuracion->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($objetivo->formatoDuracion->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($objetivo->formatoDuracion->CurrentValue) ?>" selected><?php echo $objetivo->formatoDuracion->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $objetivo->formatoDuracion->OldValue = "";
?>
</select>
</span>
<?php } ?>
<?php if ($objetivo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_formatoDuracion" class="objetivo_formatoDuracion">
<span<?php echo $objetivo->formatoDuracion->ViewAttributes() ?>>
<?php echo $objetivo->formatoDuracion->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="objetivo" data-field="x_formatoDuracion" name="x<?php echo $objetivo_grid->RowIndex ?>_formatoDuracion" id="x<?php echo $objetivo_grid->RowIndex ?>_formatoDuracion" value="<?php echo ew_HtmlEncode($objetivo->formatoDuracion->FormValue) ?>">
<input type="hidden" data-table="objetivo" data-field="x_formatoDuracion" name="o<?php echo $objetivo_grid->RowIndex ?>_formatoDuracion" id="o<?php echo $objetivo_grid->RowIndex ?>_formatoDuracion" value="<?php echo ew_HtmlEncode($objetivo->formatoDuracion->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($objetivo->fechaInicio->Visible) { // fechaInicio ?>
		<td data-name="fechaInicio"<?php echo $objetivo->fechaInicio->CellAttributes() ?>>
<?php if ($objetivo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_fechaInicio" class="form-group objetivo_fechaInicio">
<input type="text" data-table="objetivo" data-field="x_fechaInicio" data-format="7" name="x<?php echo $objetivo_grid->RowIndex ?>_fechaInicio" id="x<?php echo $objetivo_grid->RowIndex ?>_fechaInicio" placeholder="<?php echo ew_HtmlEncode($objetivo->fechaInicio->getPlaceHolder()) ?>" value="<?php echo $objetivo->fechaInicio->EditValue ?>"<?php echo $objetivo->fechaInicio->EditAttributes() ?>>
<?php if (!$objetivo->fechaInicio->ReadOnly && !$objetivo->fechaInicio->Disabled && !isset($objetivo->fechaInicio->EditAttrs["readonly"]) && !isset($objetivo->fechaInicio->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fobjetivogrid", "x<?php echo $objetivo_grid->RowIndex ?>_fechaInicio", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-table="objetivo" data-field="x_fechaInicio" name="o<?php echo $objetivo_grid->RowIndex ?>_fechaInicio" id="o<?php echo $objetivo_grid->RowIndex ?>_fechaInicio" value="<?php echo ew_HtmlEncode($objetivo->fechaInicio->OldValue) ?>">
<?php } ?>
<?php if ($objetivo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_fechaInicio" class="form-group objetivo_fechaInicio">
<input type="text" data-table="objetivo" data-field="x_fechaInicio" data-format="7" name="x<?php echo $objetivo_grid->RowIndex ?>_fechaInicio" id="x<?php echo $objetivo_grid->RowIndex ?>_fechaInicio" placeholder="<?php echo ew_HtmlEncode($objetivo->fechaInicio->getPlaceHolder()) ?>" value="<?php echo $objetivo->fechaInicio->EditValue ?>"<?php echo $objetivo->fechaInicio->EditAttributes() ?>>
<?php if (!$objetivo->fechaInicio->ReadOnly && !$objetivo->fechaInicio->Disabled && !isset($objetivo->fechaInicio->EditAttrs["readonly"]) && !isset($objetivo->fechaInicio->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fobjetivogrid", "x<?php echo $objetivo_grid->RowIndex ?>_fechaInicio", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($objetivo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_fechaInicio" class="objetivo_fechaInicio">
<span<?php echo $objetivo->fechaInicio->ViewAttributes() ?>>
<?php echo $objetivo->fechaInicio->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="objetivo" data-field="x_fechaInicio" name="x<?php echo $objetivo_grid->RowIndex ?>_fechaInicio" id="x<?php echo $objetivo_grid->RowIndex ?>_fechaInicio" value="<?php echo ew_HtmlEncode($objetivo->fechaInicio->FormValue) ?>">
<input type="hidden" data-table="objetivo" data-field="x_fechaInicio" name="o<?php echo $objetivo_grid->RowIndex ?>_fechaInicio" id="o<?php echo $objetivo_grid->RowIndex ?>_fechaInicio" value="<?php echo ew_HtmlEncode($objetivo->fechaInicio->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($objetivo->fechFin->Visible) { // fechFin ?>
		<td data-name="fechFin"<?php echo $objetivo->fechFin->CellAttributes() ?>>
<?php if ($objetivo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_fechFin" class="form-group objetivo_fechFin">
<input type="text" data-table="objetivo" data-field="x_fechFin" data-format="7" name="x<?php echo $objetivo_grid->RowIndex ?>_fechFin" id="x<?php echo $objetivo_grid->RowIndex ?>_fechFin" placeholder="<?php echo ew_HtmlEncode($objetivo->fechFin->getPlaceHolder()) ?>" value="<?php echo $objetivo->fechFin->EditValue ?>"<?php echo $objetivo->fechFin->EditAttributes() ?>>
<?php if (!$objetivo->fechFin->ReadOnly && !$objetivo->fechFin->Disabled && !isset($objetivo->fechFin->EditAttrs["readonly"]) && !isset($objetivo->fechFin->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fobjetivogrid", "x<?php echo $objetivo_grid->RowIndex ?>_fechFin", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-table="objetivo" data-field="x_fechFin" name="o<?php echo $objetivo_grid->RowIndex ?>_fechFin" id="o<?php echo $objetivo_grid->RowIndex ?>_fechFin" value="<?php echo ew_HtmlEncode($objetivo->fechFin->OldValue) ?>">
<?php } ?>
<?php if ($objetivo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_fechFin" class="form-group objetivo_fechFin">
<input type="text" data-table="objetivo" data-field="x_fechFin" data-format="7" name="x<?php echo $objetivo_grid->RowIndex ?>_fechFin" id="x<?php echo $objetivo_grid->RowIndex ?>_fechFin" placeholder="<?php echo ew_HtmlEncode($objetivo->fechFin->getPlaceHolder()) ?>" value="<?php echo $objetivo->fechFin->EditValue ?>"<?php echo $objetivo->fechFin->EditAttributes() ?>>
<?php if (!$objetivo->fechFin->ReadOnly && !$objetivo->fechFin->Disabled && !isset($objetivo->fechFin->EditAttrs["readonly"]) && !isset($objetivo->fechFin->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fobjetivogrid", "x<?php echo $objetivo_grid->RowIndex ?>_fechFin", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($objetivo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_fechFin" class="objetivo_fechFin">
<span<?php echo $objetivo->fechFin->ViewAttributes() ?>>
<?php echo $objetivo->fechFin->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="objetivo" data-field="x_fechFin" name="x<?php echo $objetivo_grid->RowIndex ?>_fechFin" id="x<?php echo $objetivo_grid->RowIndex ?>_fechFin" value="<?php echo ew_HtmlEncode($objetivo->fechFin->FormValue) ?>">
<input type="hidden" data-table="objetivo" data-field="x_fechFin" name="o<?php echo $objetivo_grid->RowIndex ?>_fechFin" id="o<?php echo $objetivo_grid->RowIndex ?>_fechFin" value="<?php echo ew_HtmlEncode($objetivo->fechFin->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($objetivo->proyecto->Visible) { // proyecto ?>
		<td data-name="proyecto"<?php echo $objetivo->proyecto->CellAttributes() ?>>
<?php if ($objetivo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($objetivo->proyecto->getSessionValue() <> "") { ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_proyecto" class="form-group objetivo_proyecto">
<span<?php echo $objetivo->proyecto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $objetivo->proyecto->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $objetivo_grid->RowIndex ?>_proyecto" name="x<?php echo $objetivo_grid->RowIndex ?>_proyecto" value="<?php echo ew_HtmlEncode($objetivo->proyecto->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_proyecto" class="form-group objetivo_proyecto">
<select data-table="objetivo" data-field="x_proyecto" data-value-separator="<?php echo ew_HtmlEncode(is_array($objetivo->proyecto->DisplayValueSeparator) ? json_encode($objetivo->proyecto->DisplayValueSeparator) : $objetivo->proyecto->DisplayValueSeparator) ?>" id="x<?php echo $objetivo_grid->RowIndex ?>_proyecto" name="x<?php echo $objetivo_grid->RowIndex ?>_proyecto"<?php echo $objetivo->proyecto->EditAttributes() ?>>
<?php
if (is_array($objetivo->proyecto->EditValue)) {
	$arwrk = $objetivo->proyecto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($objetivo->proyecto->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $objetivo->proyecto->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($objetivo->proyecto->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($objetivo->proyecto->CurrentValue) ?>" selected><?php echo $objetivo->proyecto->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $objetivo->proyecto->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `idProyecto`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `proyecto`";
$sWhereWrk = "";
$objetivo->proyecto->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$objetivo->proyecto->LookupFilters += array("f0" => "`idProyecto` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$objetivo->Lookup_Selecting($objetivo->proyecto, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $objetivo->proyecto->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $objetivo_grid->RowIndex ?>_proyecto" id="s_x<?php echo $objetivo_grid->RowIndex ?>_proyecto" value="<?php echo $objetivo->proyecto->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="objetivo" data-field="x_proyecto" name="o<?php echo $objetivo_grid->RowIndex ?>_proyecto" id="o<?php echo $objetivo_grid->RowIndex ?>_proyecto" value="<?php echo ew_HtmlEncode($objetivo->proyecto->OldValue) ?>">
<?php } ?>
<?php if ($objetivo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($objetivo->proyecto->getSessionValue() <> "") { ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_proyecto" class="form-group objetivo_proyecto">
<span<?php echo $objetivo->proyecto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $objetivo->proyecto->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $objetivo_grid->RowIndex ?>_proyecto" name="x<?php echo $objetivo_grid->RowIndex ?>_proyecto" value="<?php echo ew_HtmlEncode($objetivo->proyecto->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_proyecto" class="form-group objetivo_proyecto">
<select data-table="objetivo" data-field="x_proyecto" data-value-separator="<?php echo ew_HtmlEncode(is_array($objetivo->proyecto->DisplayValueSeparator) ? json_encode($objetivo->proyecto->DisplayValueSeparator) : $objetivo->proyecto->DisplayValueSeparator) ?>" id="x<?php echo $objetivo_grid->RowIndex ?>_proyecto" name="x<?php echo $objetivo_grid->RowIndex ?>_proyecto"<?php echo $objetivo->proyecto->EditAttributes() ?>>
<?php
if (is_array($objetivo->proyecto->EditValue)) {
	$arwrk = $objetivo->proyecto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($objetivo->proyecto->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $objetivo->proyecto->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($objetivo->proyecto->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($objetivo->proyecto->CurrentValue) ?>" selected><?php echo $objetivo->proyecto->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $objetivo->proyecto->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `idProyecto`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `proyecto`";
$sWhereWrk = "";
$objetivo->proyecto->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$objetivo->proyecto->LookupFilters += array("f0" => "`idProyecto` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$objetivo->Lookup_Selecting($objetivo->proyecto, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $objetivo->proyecto->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $objetivo_grid->RowIndex ?>_proyecto" id="s_x<?php echo $objetivo_grid->RowIndex ?>_proyecto" value="<?php echo $objetivo->proyecto->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } ?>
<?php if ($objetivo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_proyecto" class="objetivo_proyecto">
<span<?php echo $objetivo->proyecto->ViewAttributes() ?>>
<?php echo $objetivo->proyecto->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="objetivo" data-field="x_proyecto" name="x<?php echo $objetivo_grid->RowIndex ?>_proyecto" id="x<?php echo $objetivo_grid->RowIndex ?>_proyecto" value="<?php echo ew_HtmlEncode($objetivo->proyecto->FormValue) ?>">
<input type="hidden" data-table="objetivo" data-field="x_proyecto" name="o<?php echo $objetivo_grid->RowIndex ?>_proyecto" id="o<?php echo $objetivo_grid->RowIndex ?>_proyecto" value="<?php echo ew_HtmlEncode($objetivo->proyecto->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($objetivo->tipo->Visible) { // tipo ?>
		<td data-name="tipo"<?php echo $objetivo->tipo->CellAttributes() ?>>
<?php if ($objetivo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_tipo" class="form-group objetivo_tipo">
<select data-table="objetivo" data-field="x_tipo" data-value-separator="<?php echo ew_HtmlEncode(is_array($objetivo->tipo->DisplayValueSeparator) ? json_encode($objetivo->tipo->DisplayValueSeparator) : $objetivo->tipo->DisplayValueSeparator) ?>" id="x<?php echo $objetivo_grid->RowIndex ?>_tipo" name="x<?php echo $objetivo_grid->RowIndex ?>_tipo"<?php echo $objetivo->tipo->EditAttributes() ?>>
<?php
if (is_array($objetivo->tipo->EditValue)) {
	$arwrk = $objetivo->tipo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($objetivo->tipo->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $objetivo->tipo->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($objetivo->tipo->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($objetivo->tipo->CurrentValue) ?>" selected><?php echo $objetivo->tipo->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $objetivo->tipo->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `idObjetivosTipo`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `objetivos_tipo`";
$sWhereWrk = "";
$objetivo->tipo->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$objetivo->tipo->LookupFilters += array("f0" => "`idObjetivosTipo` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$objetivo->Lookup_Selecting($objetivo->tipo, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $objetivo->tipo->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $objetivo_grid->RowIndex ?>_tipo" id="s_x<?php echo $objetivo_grid->RowIndex ?>_tipo" value="<?php echo $objetivo->tipo->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="objetivo" data-field="x_tipo" name="o<?php echo $objetivo_grid->RowIndex ?>_tipo" id="o<?php echo $objetivo_grid->RowIndex ?>_tipo" value="<?php echo ew_HtmlEncode($objetivo->tipo->OldValue) ?>">
<?php } ?>
<?php if ($objetivo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_tipo" class="form-group objetivo_tipo">
<select data-table="objetivo" data-field="x_tipo" data-value-separator="<?php echo ew_HtmlEncode(is_array($objetivo->tipo->DisplayValueSeparator) ? json_encode($objetivo->tipo->DisplayValueSeparator) : $objetivo->tipo->DisplayValueSeparator) ?>" id="x<?php echo $objetivo_grid->RowIndex ?>_tipo" name="x<?php echo $objetivo_grid->RowIndex ?>_tipo"<?php echo $objetivo->tipo->EditAttributes() ?>>
<?php
if (is_array($objetivo->tipo->EditValue)) {
	$arwrk = $objetivo->tipo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($objetivo->tipo->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $objetivo->tipo->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($objetivo->tipo->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($objetivo->tipo->CurrentValue) ?>" selected><?php echo $objetivo->tipo->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $objetivo->tipo->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `idObjetivosTipo`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `objetivos_tipo`";
$sWhereWrk = "";
$objetivo->tipo->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$objetivo->tipo->LookupFilters += array("f0" => "`idObjetivosTipo` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$objetivo->Lookup_Selecting($objetivo->tipo, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $objetivo->tipo->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $objetivo_grid->RowIndex ?>_tipo" id="s_x<?php echo $objetivo_grid->RowIndex ?>_tipo" value="<?php echo $objetivo->tipo->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($objetivo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $objetivo_grid->RowCnt ?>_objetivo_tipo" class="objetivo_tipo">
<span<?php echo $objetivo->tipo->ViewAttributes() ?>>
<?php echo $objetivo->tipo->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="objetivo" data-field="x_tipo" name="x<?php echo $objetivo_grid->RowIndex ?>_tipo" id="x<?php echo $objetivo_grid->RowIndex ?>_tipo" value="<?php echo ew_HtmlEncode($objetivo->tipo->FormValue) ?>">
<input type="hidden" data-table="objetivo" data-field="x_tipo" name="o<?php echo $objetivo_grid->RowIndex ?>_tipo" id="o<?php echo $objetivo_grid->RowIndex ?>_tipo" value="<?php echo ew_HtmlEncode($objetivo->tipo->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$objetivo_grid->ListOptions->Render("body", "right", $objetivo_grid->RowCnt);
?>
	</tr>
<?php if ($objetivo->RowType == EW_ROWTYPE_ADD || $objetivo->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fobjetivogrid.UpdateOpts(<?php echo $objetivo_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($objetivo->CurrentAction <> "gridadd" || $objetivo->CurrentMode == "copy")
		if (!$objetivo_grid->Recordset->EOF) $objetivo_grid->Recordset->MoveNext();
}
?>
<?php
	if ($objetivo->CurrentMode == "add" || $objetivo->CurrentMode == "copy" || $objetivo->CurrentMode == "edit") {
		$objetivo_grid->RowIndex = '$rowindex$';
		$objetivo_grid->LoadDefaultValues();

		// Set row properties
		$objetivo->ResetAttrs();
		$objetivo->RowAttrs = array_merge($objetivo->RowAttrs, array('data-rowindex'=>$objetivo_grid->RowIndex, 'id'=>'r0_objetivo', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($objetivo->RowAttrs["class"], "ewTemplate");
		$objetivo->RowType = EW_ROWTYPE_ADD;

		// Render row
		$objetivo_grid->RenderRow();

		// Render list options
		$objetivo_grid->RenderListOptions();
		$objetivo_grid->StartRowCnt = 0;
?>
	<tr<?php echo $objetivo->RowAttributes() ?>>
<?php

// Render list options (body, left)
$objetivo_grid->ListOptions->Render("body", "left", $objetivo_grid->RowIndex);
?>
	<?php if ($objetivo->idObjetivo->Visible) { // idObjetivo ?>
		<td data-name="idObjetivo">
<?php if ($objetivo->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_objetivo_idObjetivo" class="form-group objetivo_idObjetivo">
<span<?php echo $objetivo->idObjetivo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $objetivo->idObjetivo->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="objetivo" data-field="x_idObjetivo" name="x<?php echo $objetivo_grid->RowIndex ?>_idObjetivo" id="x<?php echo $objetivo_grid->RowIndex ?>_idObjetivo" value="<?php echo ew_HtmlEncode($objetivo->idObjetivo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="objetivo" data-field="x_idObjetivo" name="o<?php echo $objetivo_grid->RowIndex ?>_idObjetivo" id="o<?php echo $objetivo_grid->RowIndex ?>_idObjetivo" value="<?php echo ew_HtmlEncode($objetivo->idObjetivo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($objetivo->nombre->Visible) { // nombre ?>
		<td data-name="nombre">
<?php if ($objetivo->CurrentAction <> "F") { ?>
<span id="el$rowindex$_objetivo_nombre" class="form-group objetivo_nombre">
<input type="text" data-table="objetivo" data-field="x_nombre" name="x<?php echo $objetivo_grid->RowIndex ?>_nombre" id="x<?php echo $objetivo_grid->RowIndex ?>_nombre" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($objetivo->nombre->getPlaceHolder()) ?>" value="<?php echo $objetivo->nombre->EditValue ?>"<?php echo $objetivo->nombre->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_objetivo_nombre" class="form-group objetivo_nombre">
<span<?php echo $objetivo->nombre->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $objetivo->nombre->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="objetivo" data-field="x_nombre" name="x<?php echo $objetivo_grid->RowIndex ?>_nombre" id="x<?php echo $objetivo_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($objetivo->nombre->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="objetivo" data-field="x_nombre" name="o<?php echo $objetivo_grid->RowIndex ?>_nombre" id="o<?php echo $objetivo_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($objetivo->nombre->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($objetivo->comentarios->Visible) { // comentarios ?>
		<td data-name="comentarios">
<?php if ($objetivo->CurrentAction <> "F") { ?>
<span id="el$rowindex$_objetivo_comentarios" class="form-group objetivo_comentarios">
<input type="text" data-table="objetivo" data-field="x_comentarios" name="x<?php echo $objetivo_grid->RowIndex ?>_comentarios" id="x<?php echo $objetivo_grid->RowIndex ?>_comentarios" size="30" maxlength="128" placeholder="<?php echo ew_HtmlEncode($objetivo->comentarios->getPlaceHolder()) ?>" value="<?php echo $objetivo->comentarios->EditValue ?>"<?php echo $objetivo->comentarios->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_objetivo_comentarios" class="form-group objetivo_comentarios">
<span<?php echo $objetivo->comentarios->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $objetivo->comentarios->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="objetivo" data-field="x_comentarios" name="x<?php echo $objetivo_grid->RowIndex ?>_comentarios" id="x<?php echo $objetivo_grid->RowIndex ?>_comentarios" value="<?php echo ew_HtmlEncode($objetivo->comentarios->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="objetivo" data-field="x_comentarios" name="o<?php echo $objetivo_grid->RowIndex ?>_comentarios" id="o<?php echo $objetivo_grid->RowIndex ?>_comentarios" value="<?php echo ew_HtmlEncode($objetivo->comentarios->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($objetivo->duracion->Visible) { // duracion ?>
		<td data-name="duracion">
<?php if ($objetivo->CurrentAction <> "F") { ?>
<span id="el$rowindex$_objetivo_duracion" class="form-group objetivo_duracion">
<input type="text" data-table="objetivo" data-field="x_duracion" name="x<?php echo $objetivo_grid->RowIndex ?>_duracion" id="x<?php echo $objetivo_grid->RowIndex ?>_duracion" size="30" placeholder="<?php echo ew_HtmlEncode($objetivo->duracion->getPlaceHolder()) ?>" value="<?php echo $objetivo->duracion->EditValue ?>"<?php echo $objetivo->duracion->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_objetivo_duracion" class="form-group objetivo_duracion">
<span<?php echo $objetivo->duracion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $objetivo->duracion->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="objetivo" data-field="x_duracion" name="x<?php echo $objetivo_grid->RowIndex ?>_duracion" id="x<?php echo $objetivo_grid->RowIndex ?>_duracion" value="<?php echo ew_HtmlEncode($objetivo->duracion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="objetivo" data-field="x_duracion" name="o<?php echo $objetivo_grid->RowIndex ?>_duracion" id="o<?php echo $objetivo_grid->RowIndex ?>_duracion" value="<?php echo ew_HtmlEncode($objetivo->duracion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($objetivo->formatoDuracion->Visible) { // formatoDuracion ?>
		<td data-name="formatoDuracion">
<?php if ($objetivo->CurrentAction <> "F") { ?>
<span id="el$rowindex$_objetivo_formatoDuracion" class="form-group objetivo_formatoDuracion">
<select data-table="objetivo" data-field="x_formatoDuracion" data-value-separator="<?php echo ew_HtmlEncode(is_array($objetivo->formatoDuracion->DisplayValueSeparator) ? json_encode($objetivo->formatoDuracion->DisplayValueSeparator) : $objetivo->formatoDuracion->DisplayValueSeparator) ?>" id="x<?php echo $objetivo_grid->RowIndex ?>_formatoDuracion" name="x<?php echo $objetivo_grid->RowIndex ?>_formatoDuracion"<?php echo $objetivo->formatoDuracion->EditAttributes() ?>>
<?php
if (is_array($objetivo->formatoDuracion->EditValue)) {
	$arwrk = $objetivo->formatoDuracion->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($objetivo->formatoDuracion->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $objetivo->formatoDuracion->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($objetivo->formatoDuracion->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($objetivo->formatoDuracion->CurrentValue) ?>" selected><?php echo $objetivo->formatoDuracion->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $objetivo->formatoDuracion->OldValue = "";
?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_objetivo_formatoDuracion" class="form-group objetivo_formatoDuracion">
<span<?php echo $objetivo->formatoDuracion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $objetivo->formatoDuracion->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="objetivo" data-field="x_formatoDuracion" name="x<?php echo $objetivo_grid->RowIndex ?>_formatoDuracion" id="x<?php echo $objetivo_grid->RowIndex ?>_formatoDuracion" value="<?php echo ew_HtmlEncode($objetivo->formatoDuracion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="objetivo" data-field="x_formatoDuracion" name="o<?php echo $objetivo_grid->RowIndex ?>_formatoDuracion" id="o<?php echo $objetivo_grid->RowIndex ?>_formatoDuracion" value="<?php echo ew_HtmlEncode($objetivo->formatoDuracion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($objetivo->fechaInicio->Visible) { // fechaInicio ?>
		<td data-name="fechaInicio">
<?php if ($objetivo->CurrentAction <> "F") { ?>
<span id="el$rowindex$_objetivo_fechaInicio" class="form-group objetivo_fechaInicio">
<input type="text" data-table="objetivo" data-field="x_fechaInicio" data-format="7" name="x<?php echo $objetivo_grid->RowIndex ?>_fechaInicio" id="x<?php echo $objetivo_grid->RowIndex ?>_fechaInicio" placeholder="<?php echo ew_HtmlEncode($objetivo->fechaInicio->getPlaceHolder()) ?>" value="<?php echo $objetivo->fechaInicio->EditValue ?>"<?php echo $objetivo->fechaInicio->EditAttributes() ?>>
<?php if (!$objetivo->fechaInicio->ReadOnly && !$objetivo->fechaInicio->Disabled && !isset($objetivo->fechaInicio->EditAttrs["readonly"]) && !isset($objetivo->fechaInicio->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fobjetivogrid", "x<?php echo $objetivo_grid->RowIndex ?>_fechaInicio", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_objetivo_fechaInicio" class="form-group objetivo_fechaInicio">
<span<?php echo $objetivo->fechaInicio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $objetivo->fechaInicio->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="objetivo" data-field="x_fechaInicio" name="x<?php echo $objetivo_grid->RowIndex ?>_fechaInicio" id="x<?php echo $objetivo_grid->RowIndex ?>_fechaInicio" value="<?php echo ew_HtmlEncode($objetivo->fechaInicio->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="objetivo" data-field="x_fechaInicio" name="o<?php echo $objetivo_grid->RowIndex ?>_fechaInicio" id="o<?php echo $objetivo_grid->RowIndex ?>_fechaInicio" value="<?php echo ew_HtmlEncode($objetivo->fechaInicio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($objetivo->fechFin->Visible) { // fechFin ?>
		<td data-name="fechFin">
<?php if ($objetivo->CurrentAction <> "F") { ?>
<span id="el$rowindex$_objetivo_fechFin" class="form-group objetivo_fechFin">
<input type="text" data-table="objetivo" data-field="x_fechFin" data-format="7" name="x<?php echo $objetivo_grid->RowIndex ?>_fechFin" id="x<?php echo $objetivo_grid->RowIndex ?>_fechFin" placeholder="<?php echo ew_HtmlEncode($objetivo->fechFin->getPlaceHolder()) ?>" value="<?php echo $objetivo->fechFin->EditValue ?>"<?php echo $objetivo->fechFin->EditAttributes() ?>>
<?php if (!$objetivo->fechFin->ReadOnly && !$objetivo->fechFin->Disabled && !isset($objetivo->fechFin->EditAttrs["readonly"]) && !isset($objetivo->fechFin->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fobjetivogrid", "x<?php echo $objetivo_grid->RowIndex ?>_fechFin", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_objetivo_fechFin" class="form-group objetivo_fechFin">
<span<?php echo $objetivo->fechFin->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $objetivo->fechFin->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="objetivo" data-field="x_fechFin" name="x<?php echo $objetivo_grid->RowIndex ?>_fechFin" id="x<?php echo $objetivo_grid->RowIndex ?>_fechFin" value="<?php echo ew_HtmlEncode($objetivo->fechFin->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="objetivo" data-field="x_fechFin" name="o<?php echo $objetivo_grid->RowIndex ?>_fechFin" id="o<?php echo $objetivo_grid->RowIndex ?>_fechFin" value="<?php echo ew_HtmlEncode($objetivo->fechFin->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($objetivo->proyecto->Visible) { // proyecto ?>
		<td data-name="proyecto">
<?php if ($objetivo->CurrentAction <> "F") { ?>
<?php if ($objetivo->proyecto->getSessionValue() <> "") { ?>
<span id="el$rowindex$_objetivo_proyecto" class="form-group objetivo_proyecto">
<span<?php echo $objetivo->proyecto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $objetivo->proyecto->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $objetivo_grid->RowIndex ?>_proyecto" name="x<?php echo $objetivo_grid->RowIndex ?>_proyecto" value="<?php echo ew_HtmlEncode($objetivo->proyecto->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_objetivo_proyecto" class="form-group objetivo_proyecto">
<select data-table="objetivo" data-field="x_proyecto" data-value-separator="<?php echo ew_HtmlEncode(is_array($objetivo->proyecto->DisplayValueSeparator) ? json_encode($objetivo->proyecto->DisplayValueSeparator) : $objetivo->proyecto->DisplayValueSeparator) ?>" id="x<?php echo $objetivo_grid->RowIndex ?>_proyecto" name="x<?php echo $objetivo_grid->RowIndex ?>_proyecto"<?php echo $objetivo->proyecto->EditAttributes() ?>>
<?php
if (is_array($objetivo->proyecto->EditValue)) {
	$arwrk = $objetivo->proyecto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($objetivo->proyecto->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $objetivo->proyecto->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($objetivo->proyecto->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($objetivo->proyecto->CurrentValue) ?>" selected><?php echo $objetivo->proyecto->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $objetivo->proyecto->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `idProyecto`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `proyecto`";
$sWhereWrk = "";
$objetivo->proyecto->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$objetivo->proyecto->LookupFilters += array("f0" => "`idProyecto` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$objetivo->Lookup_Selecting($objetivo->proyecto, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $objetivo->proyecto->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $objetivo_grid->RowIndex ?>_proyecto" id="s_x<?php echo $objetivo_grid->RowIndex ?>_proyecto" value="<?php echo $objetivo->proyecto->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_objetivo_proyecto" class="form-group objetivo_proyecto">
<span<?php echo $objetivo->proyecto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $objetivo->proyecto->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="objetivo" data-field="x_proyecto" name="x<?php echo $objetivo_grid->RowIndex ?>_proyecto" id="x<?php echo $objetivo_grid->RowIndex ?>_proyecto" value="<?php echo ew_HtmlEncode($objetivo->proyecto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="objetivo" data-field="x_proyecto" name="o<?php echo $objetivo_grid->RowIndex ?>_proyecto" id="o<?php echo $objetivo_grid->RowIndex ?>_proyecto" value="<?php echo ew_HtmlEncode($objetivo->proyecto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($objetivo->tipo->Visible) { // tipo ?>
		<td data-name="tipo">
<?php if ($objetivo->CurrentAction <> "F") { ?>
<span id="el$rowindex$_objetivo_tipo" class="form-group objetivo_tipo">
<select data-table="objetivo" data-field="x_tipo" data-value-separator="<?php echo ew_HtmlEncode(is_array($objetivo->tipo->DisplayValueSeparator) ? json_encode($objetivo->tipo->DisplayValueSeparator) : $objetivo->tipo->DisplayValueSeparator) ?>" id="x<?php echo $objetivo_grid->RowIndex ?>_tipo" name="x<?php echo $objetivo_grid->RowIndex ?>_tipo"<?php echo $objetivo->tipo->EditAttributes() ?>>
<?php
if (is_array($objetivo->tipo->EditValue)) {
	$arwrk = $objetivo->tipo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($objetivo->tipo->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $objetivo->tipo->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($objetivo->tipo->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($objetivo->tipo->CurrentValue) ?>" selected><?php echo $objetivo->tipo->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $objetivo->tipo->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `idObjetivosTipo`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `objetivos_tipo`";
$sWhereWrk = "";
$objetivo->tipo->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$objetivo->tipo->LookupFilters += array("f0" => "`idObjetivosTipo` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$objetivo->Lookup_Selecting($objetivo->tipo, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $objetivo->tipo->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $objetivo_grid->RowIndex ?>_tipo" id="s_x<?php echo $objetivo_grid->RowIndex ?>_tipo" value="<?php echo $objetivo->tipo->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_objetivo_tipo" class="form-group objetivo_tipo">
<span<?php echo $objetivo->tipo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $objetivo->tipo->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="objetivo" data-field="x_tipo" name="x<?php echo $objetivo_grid->RowIndex ?>_tipo" id="x<?php echo $objetivo_grid->RowIndex ?>_tipo" value="<?php echo ew_HtmlEncode($objetivo->tipo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="objetivo" data-field="x_tipo" name="o<?php echo $objetivo_grid->RowIndex ?>_tipo" id="o<?php echo $objetivo_grid->RowIndex ?>_tipo" value="<?php echo ew_HtmlEncode($objetivo->tipo->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$objetivo_grid->ListOptions->Render("body", "right", $objetivo_grid->RowCnt);
?>
<script type="text/javascript">
fobjetivogrid.UpdateOpts(<?php echo $objetivo_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($objetivo->CurrentMode == "add" || $objetivo->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $objetivo_grid->FormKeyCountName ?>" id="<?php echo $objetivo_grid->FormKeyCountName ?>" value="<?php echo $objetivo_grid->KeyCount ?>">
<?php echo $objetivo_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($objetivo->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $objetivo_grid->FormKeyCountName ?>" id="<?php echo $objetivo_grid->FormKeyCountName ?>" value="<?php echo $objetivo_grid->KeyCount ?>">
<?php echo $objetivo_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($objetivo->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fobjetivogrid">
</div>
<?php

// Close recordset
if ($objetivo_grid->Recordset)
	$objetivo_grid->Recordset->Close();
?>
<?php if ($objetivo_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($objetivo_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($objetivo_grid->TotalRecs == 0 && $objetivo->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($objetivo_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($objetivo->Export == "") { ?>
<script type="text/javascript">
fobjetivogrid.Init();
</script>
<?php } ?>
<?php
$objetivo_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$objetivo_grid->Page_Terminate();
?>
