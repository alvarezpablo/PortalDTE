function grid_public_v_dte_recep_tipo_docu_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_tipo_docu" + seqRow).html();
}

function grid_public_v_dte_recep_tipo_docu_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_tipo_docu" + seqRow).html(newValue);
}

function grid_public_v_dte_recep_fact_ref_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_fact_ref" + seqRow).html();
}

function grid_public_v_dte_recep_fact_ref_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_fact_ref" + seqRow).html(newValue);
}

function grid_public_v_dte_recep_fec_emision_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_fec_emision" + seqRow).html();
}

function grid_public_v_dte_recep_fec_emision_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_fec_emision" + seqRow).html(newValue);
}

function grid_public_v_dte_recep_fec_recep_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_fec_recep" + seqRow).html();
}

function grid_public_v_dte_recep_fec_recep_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_fec_recep" + seqRow).html(newValue);
}

function grid_public_v_dte_recep_rut_emite_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_rut_emite" + seqRow).html();
}

function grid_public_v_dte_recep_rut_emite_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_rut_emite" + seqRow).html(newValue);
}

function grid_public_v_dte_recep_nom_emite_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_nom_emite" + seqRow).html();
}

function grid_public_v_dte_recep_nom_emite_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_nom_emite" + seqRow).html(newValue);
}

function grid_public_v_dte_recep_mntneto_dte_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_mntneto_dte" + seqRow).html();
}

function grid_public_v_dte_recep_mntneto_dte_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_mntneto_dte" + seqRow).html(newValue);
}

function grid_public_v_dte_recep_mnt_exen_dte_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_mnt_exen_dte" + seqRow).html();
}

function grid_public_v_dte_recep_mnt_exen_dte_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_mnt_exen_dte" + seqRow).html(newValue);
}

function grid_public_v_dte_recep_iva_dte_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_iva_dte" + seqRow).html();
}

function grid_public_v_dte_recep_iva_dte_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_iva_dte" + seqRow).html(newValue);
}

function grid_public_v_dte_recep_mont_tot_dte_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_mont_tot_dte" + seqRow).html();
}

function grid_public_v_dte_recep_mont_tot_dte_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_mont_tot_dte" + seqRow).html(newValue);
}

function grid_public_v_dte_recep_pdf_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_pdf" + seqRow).find("img").attr("src");
}

function grid_public_v_dte_recep_pdf_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_pdf" + seqRow).find("img").attr("src", newValue);
}

function grid_public_v_dte_recep_acuse_recibo_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_acuse_recibo" + seqRow).html();
}

function grid_public_v_dte_recep_acuse_recibo_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_acuse_recibo" + seqRow).html(newValue);
}

function grid_public_v_dte_recep_rec_mercaderia_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_rec_mercaderia" + seqRow).html();
}

function grid_public_v_dte_recep_rec_mercaderia_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_rec_mercaderia" + seqRow).html(newValue);
}

function grid_public_v_dte_recep_res_rev_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_res_rev" + seqRow).html();
}

function grid_public_v_dte_recep_res_rev_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_res_rev" + seqRow).html(newValue);
}

function grid_public_v_dte_recep_getValue(field, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  if ("tipo_docu" == field) {
    return grid_public_v_dte_recep_tipo_docu_getValue(seqRow);
  }
  if ("fact_ref" == field) {
    return grid_public_v_dte_recep_fact_ref_getValue(seqRow);
  }
  if ("fec_emision" == field) {
    return grid_public_v_dte_recep_fec_emision_getValue(seqRow);
  }
  if ("fec_recep" == field) {
    return grid_public_v_dte_recep_fec_recep_getValue(seqRow);
  }
  if ("rut_emite" == field) {
    return grid_public_v_dte_recep_rut_emite_getValue(seqRow);
  }
  if ("nom_emite" == field) {
    return grid_public_v_dte_recep_nom_emite_getValue(seqRow);
  }
  if ("mntneto_dte" == field) {
    return grid_public_v_dte_recep_mntneto_dte_getValue(seqRow);
  }
  if ("mnt_exen_dte" == field) {
    return grid_public_v_dte_recep_mnt_exen_dte_getValue(seqRow);
  }
  if ("iva_dte" == field) {
    return grid_public_v_dte_recep_iva_dte_getValue(seqRow);
  }
  if ("mont_tot_dte" == field) {
    return grid_public_v_dte_recep_mont_tot_dte_getValue(seqRow);
  }
  if ("pdf" == field) {
    return grid_public_v_dte_recep_pdf_getValue(seqRow);
  }
  if ("acuse_recibo" == field) {
    return grid_public_v_dte_recep_acuse_recibo_getValue(seqRow);
  }
  if ("rec_mercaderia" == field) {
    return grid_public_v_dte_recep_rec_mercaderia_getValue(seqRow);
  }
  if ("res_rev" == field) {
    return grid_public_v_dte_recep_res_rev_getValue(seqRow);
  }
}

function grid_public_v_dte_recep_setValue(field, newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  if ("tipo_docu" == field) {
    grid_public_v_dte_recep_tipo_docu_setValue(newValue, seqRow);
  }
  if ("fact_ref" == field) {
    grid_public_v_dte_recep_fact_ref_setValue(newValue, seqRow);
  }
  if ("fec_emision" == field) {
    grid_public_v_dte_recep_fec_emision_setValue(newValue, seqRow);
  }
  if ("fec_recep" == field) {
    grid_public_v_dte_recep_fec_recep_setValue(newValue, seqRow);
  }
  if ("rut_emite" == field) {
    grid_public_v_dte_recep_rut_emite_setValue(newValue, seqRow);
  }
  if ("nom_emite" == field) {
    grid_public_v_dte_recep_nom_emite_setValue(newValue, seqRow);
  }
  if ("mntneto_dte" == field) {
    grid_public_v_dte_recep_mntneto_dte_setValue(newValue, seqRow);
  }
  if ("mnt_exen_dte" == field) {
    grid_public_v_dte_recep_mnt_exen_dte_setValue(newValue, seqRow);
  }
  if ("iva_dte" == field) {
    grid_public_v_dte_recep_iva_dte_setValue(newValue, seqRow);
  }
  if ("mont_tot_dte" == field) {
    grid_public_v_dte_recep_mont_tot_dte_setValue(newValue, seqRow);
  }
  if ("pdf" == field) {
    grid_public_v_dte_recep_pdf_setValue(newValue, seqRow);
  }
  if ("acuse_recibo" == field) {
    grid_public_v_dte_recep_acuse_recibo_setValue(newValue, seqRow);
  }
  if ("rec_mercaderia" == field) {
    grid_public_v_dte_recep_rec_mercaderia_setValue(newValue, seqRow);
  }
  if ("res_rev" == field) {
    grid_public_v_dte_recep_res_rev_setValue(newValue, seqRow);
  }
}

function scJQAddEvents(seqRow) {
  seqRow = scSeqNormalize(seqRow);
}

function scSeqNormalize(seqRow) {
  var newSeqRow = seqRow.toString();
  if ("" == newSeqRow) {
    return "";
  }
  if ("_" != newSeqRow.substr(0, 1)) {
    return "_" + newSeqRow;
  }
  return newSeqRow;
}
function ajax_navigate(opc, parm)
{
    scAjaxProcOn();
    $.ajax({
      type: "POST",
      url: "grid_public_v_dte_recep.php",
      data: "nmgp_opcao=ajax_navigate&script_case_init=" + document.F4.script_case_init.value + "&script_case_session=" + document.F4.script_case_session.value + "&opc=" + opc  + "&parm=" + parm,
      success: function(jsonNavigate) {
        var i, oResp;
        eval("oResp = " + jsonNavigate);
        document.getElementById('nmsc_iframe_liga_A_grid_public_v_dte_recep').src = 'NM_Blank_Page.htm';
        document.getElementById('nmsc_iframe_liga_E_grid_public_v_dte_recep').src = 'NM_Blank_Page.htm';
        document.getElementById('nmsc_iframe_liga_D_grid_public_v_dte_recep').src = 'NM_Blank_Page.htm';
        document.getElementById('nmsc_iframe_liga_B_grid_public_v_dte_recep').src = 'NM_Blank_Page.htm';
        document.getElementById('nmsc_iframe_liga_A_grid_public_v_dte_recep').style.height = '0px';
        document.getElementById('nmsc_iframe_liga_E_grid_public_v_dte_recep').style.height = '0px';
        document.getElementById('nmsc_iframe_liga_D_grid_public_v_dte_recep').style.height = '0px';
        document.getElementById('nmsc_iframe_liga_B_grid_public_v_dte_recep').style.height = '0px';
        document.getElementById('nmsc_iframe_liga_A_grid_public_v_dte_recep').style.width  = '0px';
        document.getElementById('nmsc_iframe_liga_E_grid_public_v_dte_recep').style.width  = '0px';
        document.getElementById('nmsc_iframe_liga_D_grid_public_v_dte_recep').style.width  = '0px';
        document.getElementById('nmsc_iframe_liga_B_grid_public_v_dte_recep').style.width  = '0px';
        if (oResp["redirInfo"]) {
           scAjaxRedir(oResp);
        }
        if (oResp["setValue"]) {
          for (i = 0; i < oResp["setValue"].length; i++) {
               $("#" + oResp["setValue"][i]["field"]).html(oResp["setValue"][i]["value"]);
          }
        }
        if (oResp["setVar"]) {
          for (i = 0; i < oResp["setVar"].length; i++) {
               eval (oResp["setVar"][i]["var"] + ' = \"' + oResp["setVar"][i]["value"] + '\"');
          }
        }
        if (oResp["setDisplay"]) {
          for (i = 0; i < oResp["setDisplay"].length; i++) {
               document.getElementById(oResp["setDisplay"][i]["field"]).style.display = oResp["setDisplay"][i]["value"];
          }
        }
        if (oResp["setDisabled"]) {
          for (i = 0; i < oResp["setDisabled"].length; i++) {
               document.getElementById(oResp["setDisabled"][i]["field"]).disabled = oResp["setDisabled"][i]["value"];
          }
        }
        if (oResp["setClass"]) {
          for (i = 0; i < oResp["setClass"].length; i++) {
               document.getElementById(oResp["setClass"][i]["field"]).className = oResp["setClass"][i]["value"];
          }
        }
        if (oResp["setSrc"]) {
          for (i = 0; i < oResp["setSrc"].length; i++) {
               document.getElementById(oResp["setSrc"][i]["field"]).src = oResp["setSrc"][i]["value"];
          }
        }
        if (oResp["redirInfo"]) {
           scAjaxRedir(oResp);
        }
        if (oResp["htmOutput"]) {
           scAjaxShowDebug(oResp);
        }
        if (!SC_Link_View)
        {
            if (Qsearch_ok)
            {
                scQSInitVal = $("#SC_fast_search_top").val();
                scQSInit = true;
                scQuickSearchInit(false, '');
                $('#SC_fast_search_top').listen();
                scQuickSearchKeyUp('top', null);
                scQSInit = false;
            }
            SC_init_jquery();
            tb_init('a.thickbox, area.thickbox, input.thickbox');
        }
        scAjaxProcOff();
      }
    });
}
