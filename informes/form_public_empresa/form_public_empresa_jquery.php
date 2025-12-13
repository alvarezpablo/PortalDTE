
function scJQGeneralAdd() {
  $('input:text.sc-js-input').listen();
  $('input:password.sc-js-input').listen();
  $('textarea.sc-js-input').listen();

} // scJQGeneralAdd

function scFocusField(sField) {
  var $oField = $('#id_sc_field_' + sField);

  if (0 == $oField.length) {
    $oField = $('input[name=' + sField + ']');
  }

  if (0 == $oField.length && document.F1.elements[sField]) {
    $oField = $(document.F1.elements[sField]);
  }

  if (0 < $oField.length && 0 < $oField[0].offsetHeight && 0 < $oField[0].offsetWidth && !$oField[0].disabled) {
    $oField[0].focus();
  }
} // scFocusField

function scJQEventsAdd(iSeqRow) {
  $('#id_sc_field_rs_empr' + iSeqRow).bind('blur', function() { sc_form_public_empresa_rs_empr_onblur(this, iSeqRow) })
                                     .bind('focus', function() { sc_form_public_empresa_rs_empr_onfocus(this, iSeqRow) });
  $('#id_sc_field_dir_empr' + iSeqRow).bind('blur', function() { sc_form_public_empresa_dir_empr_onblur(this, iSeqRow) })
                                      .bind('focus', function() { sc_form_public_empresa_dir_empr_onfocus(this, iSeqRow) });
  $('#id_sc_field_com_emp' + iSeqRow).bind('blur', function() { sc_form_public_empresa_com_emp_onblur(this, iSeqRow) })
                                     .bind('focus', function() { sc_form_public_empresa_com_emp_onfocus(this, iSeqRow) });
  $('#id_sc_field_rut_usu_sii' + iSeqRow).bind('blur', function() { sc_form_public_empresa_rut_usu_sii_onblur(this, iSeqRow) })
                                         .bind('focus', function() { sc_form_public_empresa_rut_usu_sii_onfocus(this, iSeqRow) });
  $('#id_sc_field_clave_usu_sii' + iSeqRow).bind('blur', function() { sc_form_public_empresa_clave_usu_sii_onblur(this, iSeqRow) })
                                           .bind('focus', function() { sc_form_public_empresa_clave_usu_sii_onfocus(this, iSeqRow) });
} // scJQEventsAdd

function sc_form_public_empresa_rs_empr_onblur(oThis, iSeqRow) {
  do_ajax_form_public_empresa_validate_rs_empr();
  scCssBlur(oThis);
}

function sc_form_public_empresa_rs_empr_onfocus(oThis, iSeqRow) {
  scCssFocus(oThis);
}

function sc_form_public_empresa_dir_empr_onblur(oThis, iSeqRow) {
  do_ajax_form_public_empresa_validate_dir_empr();
  scCssBlur(oThis);
}

function sc_form_public_empresa_dir_empr_onfocus(oThis, iSeqRow) {
  scCssFocus(oThis);
}

function sc_form_public_empresa_com_emp_onblur(oThis, iSeqRow) {
  do_ajax_form_public_empresa_validate_com_emp();
  scCssBlur(oThis);
}

function sc_form_public_empresa_com_emp_onfocus(oThis, iSeqRow) {
  scCssFocus(oThis);
}

function sc_form_public_empresa_rut_usu_sii_onblur(oThis, iSeqRow) {
  do_ajax_form_public_empresa_validate_rut_usu_sii();
  scCssBlur(oThis);
}

function sc_form_public_empresa_rut_usu_sii_onfocus(oThis, iSeqRow) {
  scCssFocus(oThis);
}

function sc_form_public_empresa_clave_usu_sii_onblur(oThis, iSeqRow) {
  do_ajax_form_public_empresa_validate_clave_usu_sii();
  scCssBlur(oThis);
}

function sc_form_public_empresa_clave_usu_sii_onfocus(oThis, iSeqRow) {
  scCssFocus(oThis);
}

function scJQUploadAdd(iSeqRow) {
} // scJQUploadAdd


function scJQElementsAdd(iLine) {
  scJQEventsAdd(iLine);
  scJQUploadAdd(iLine);
} // scJQElementsAdd

