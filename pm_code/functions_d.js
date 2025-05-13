(() => {
    "use strict";
    var __webpack_modules__ = {
        173: (
          __unused_webpack_module,
          __webpack_exports__,
          __webpack_require__,
        ) => {
          function showMessage(e, a, t, o) {
            null == t && (t = 5e3),
              null == o && (o = "هشدار"),
              window.dynaform.flashMessage({
                duration: t,
                emphasisMessage: o,
                message: e,
                type: a,
                absoluteTop: !0,
              });
          }
          function go_to_form(e, a, t, o, d) {
            null == t && (t = 2),
              null == o && (o = "EDIT"),
              null == d && (d = ""),
              (document.getElementById(e).action =
                "cases_Step?TYPE=DYNAFORM&UID=" +
                a +
                "&POSITION=" +
                t +
                "&ACTION=" +
                o +
                d),
              document.getElementById(e).submit();
          }
          function show_hide_field(
            newValue,
            inputId,
            inputValue,
            inputsHide,
            inputsShow,
            otherFunctions,
          ) {
            var evaluate = 1;
            if ($.isArray(newValue))
              for (var i = 0; i < newValue.length; i++) {
                if (
                  "string" == $.type(inputValue[i]) &&
                  -1 != inputValue[i].indexOf("*")
                ) {
                  for (
                    var temp = inputValue[i].split("*"), tempEvaluate = 0, j = 0;
                    j < temp.length;
                    j++
                  )
                    if (newValue[i] == temp[j]) {
                      tempEvaluate = 1;
                      break;
                    }
                  evaluate = tempEvaluate;
                } else newValue[i] != inputValue[i] && (evaluate = 0);
                if (0 == evaluate) break;
              }
            else if (
              "string" == $.type(inputValue) &&
              -1 != inputValue.indexOf("*")
            ) {
              for (
                var temp = inputValue.split("*"), tempEvaluate = 0, j = 0;
                j < temp.length;
                j++
              )
                if (newValue == temp[j]) {
                  tempEvaluate = 1;
                  break;
                }
              evaluate = tempEvaluate;
            } else newValue != inputValue && (evaluate = 0);
            if (1 == evaluate) {
              for (var i = 0; i < inputsHide.length; i++)
                $("#" + inputsHide[i]).hide(),
                  $("#" + inputsHide[i]).disableValidation();
              for (var i = 0; i < inputsShow.length; i++)
                $("#" + inputsShow[i]).show(),
                  $("#" + inputsShow[i]).enableValidation();
            } else {
              for (var i = 0; i < inputsHide.length; i++)
                $("#" + inputsHide[i]).show(),
                  $("#" + inputsHide[i]).enableValidation();
              for (var i = 0; i < inputsShow.length; i++)
                $("#" + inputsShow[i]).hide(),
                  $("#" + inputsShow[i]).disableValidation();
            }
            null == otherFunctions && (otherFunctions = []);
            for (var i = 0; i < otherFunctions.length; i++)
              eval(otherFunctions[i] + '("call");');
          }
          function check_grid_receiver_draft() {
            for (
              var e = $("#grid_receiver_draft").getNumberRows(), a = 1;
              a <= e;
              a++
            ) {
              if ("" == $("#grid_receiver_draft").getValue(a, 1))
                return void showMessage("کاربر پیش نویس را مشخص کنید", "danger");
              if (
                $("#grid_receiver_draft").getValue(a, 1) ==
                $("#hidden_user_logged").getValue()
              )
                return void showMessage(
                  "گیرنده پیش نویس نمی تواند کاربر جاری باشد",
                  "danger",
                );
            }
            return 1;
          }
          function check_grid_receiver(e) {
            e = null == e ? 1 : e;
            for (
              var a = $("#grid_receiver").getNumberRows(), t = 0, o = 1;
              o <= a;
              o++
            ) {
              if ("" == $("#grid_receiver").getValue(o, 1))
                return void showMessage("کاربر گیرنده را مشخص کنید", "danger");
              "main" == $("#grid_receiver").getValue(o, 3) && t++;
            }
            if (0 != t || 1 != e) return 1;
            showMessage("حداقل یک گیرنده اصلی در ارسال نامه لازم است", "danger");
          }
          function check_grid_attachment() {
            for (
              var e = $("#grid_attachment").getNumberRows(), a = 1;
              a <= e;
              a++
            )
              "" ==
                $(
                  "input[name='form\\[grid_attachment\\]\\[" +
                    a +
                    "\\]\\[grid_file_attach\\]\\[0\\]\\[appDocUid\\]']",
                ).val() &&
              "" ==
                $(
                  "#form\\[grid_attachment\\]\\[" +
                    a +
                    "\\]\\[grid_hidden_attach\\]",
                ).val()
                ? $("#grid_attachment").deleteRow(a)
                : 0;
            return 1;
          }
          function empty_tree_grid() {
            $("#treeModal .modal-body").html(
              '<table id="treegrid" class="table table-striped table-bordered table-hover"><thead><tr><th>#</th><th>از</th><th>تاریخ ارسال</th><th>نوع گیرنده</th><th>نوع ارجاع</th><th>به</th><th>مهلت اجرا</th><th>تاریخ مشاهده</th><th>تاریخ به روز رسانی</th><th>تاریخ اتمام</th><th>توضیحات</th></tr></thead><tbody><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table>',
            );
          }
          function set_grid_link(e, a, t, o, d, n) {
            document.getElementById(
              "[" + e + "][" + a + "][" + t + "]",
            ).innerHTML =
              '<img src="' +
              o +
              '" title="' +
              d +
              '" alt="' +
              d +
              '" style="cursor:pointer;" onclick="' +
              n +
              "(" +
              a +
              ')" />';
          }
          function set_user_from_group(e) {
            if ("" == $("#receiver_type").val())
              showMessage("نوع گیرنده را مشخص کنید", "danger");
            else {
              var a = 0;
              1 == $("#grid_receiver").getNumberRows() &&
                "" == $("#grid_receiver").getValue(1, 1) &&
                $("#grid_receiver").deleteRow(1);
              for (var t = 1; t <= e; t++)
                if (
                  null != $("#checkbox_" + t) &&
                  1 == $("#checkbox_" + t).prop("checked")
                ) {
                  for (
                    var o, a = 0, d = 1;
                    d <= $("#grid_receiver").getNumberRows();
                    d++
                  )
                    $("#grid_receiver").getValue(d, 1) == $("#user_" + t).val() &&
                      (a = 1);
                  1 != a &&
                    ($("#grid_receiver").addRow(),
                    (o = $("#grid_receiver").getNumberRows()),
                    $("#grid_receiver").setValue($("#receiver_type").val(), o, 3),
                    setTimeout(set_grid_receiver_user, 100, t, o));
                }
              $("#detailModal").modal("hide");
            }
          }
          function set_grid_receiver_user(e, a) {
            $("#grid_receiver").setValue($("#user_" + e).val(), a, 1);
          }
          function send_letter(e, a) {
            e =
              "cases_Step?UID=80355400465b26a0eaa7a52096315022&TYPE=DYNAFORM&POSITION=1&ACTION=EDIT&id=" +
              e;
            $("#sendModal .modal-title").html("ارجاع نامه " + a),
              $("#sendModal .modal-body").html(""),
              $("#sendModal .modal-body").html(
                '<iframe src="' +
                  e +
                  '" style="width:100%;min-height:500px;border:none;" />',
              ),
              $("#sendModal").modal();
          }
          function inner_show_print(e) {
            $("#n2_ajax_loading").fadeIn(),
              $.ajax({
                type: "PUT",
                url: "../automation/auto_ajax?REQ_TYPE=show_print",
                data: { letter_id: e },
              }).done(function (e) {
                e.error
                  ? showMessage(e.error, "danger")
                  : e.message
                    ? showMessage(e.message, "info")
                    : "" != e.data.pdf
                      ? (window.location =
                          "../templateMaker/pages/auto_viewer?file=" + e.data.pdf)
                      : tm_async_auto_generate(
                          e.data.templateName,
                          "",
                          e.data.result,
                        ),
                  $("#n2_ajax_loading").fadeOut();
              });
          }
          function add_action(e) {
            var a;
            null == e && (e = $("#hidden_letter_id").getValue()),
              "" == $("#new_action").val()
                ? showMessage("فیلد اقدام را مشخص کنید", "danger")
                : ($("#n2_ajax_loading").fadeIn(),
                  (a = new FormData()).append("letter_id", e),
                  a.append("new_action", $("#new_action").val()),
                  (e = document.getElementById("file_action").files[0]),
                  a.append("file", e),
                  $.ajax({
                    type: "POST",
                    url: "../automation/auto_ajax?REQ_TYPE=add_action",
                    data: a,
                    contentType: !1,
                    processData: !1,
                  }).done(function (e) {
                    e.error
                      ? showMessage(e.error, "danger")
                      : e.message
                        ? showMessage(e.message, "info")
                        : (showMessage(e.data.message, "success"),
                          $("#detailModal").modal("hide")),
                      $("#n2_ajax_loading").fadeOut();
                  }));
          }
          function delete_action(e) {
            confirm("برای حذف این اقدام مطمئنید؟") &&
              ($("#n2_ajax_loading").fadeIn(),
              $.ajax({
                type: "PUT",
                url: "../automation/auto_ajax?REQ_TYPE=delete_action",
                data: { id: e },
              }).done(function (e) {
                e.error
                  ? showMessage(e.error, "danger")
                  : e.message
                    ? showMessage(e.message, "info")
                    : (showMessage(e.success, "success", 5e3, "موفقیت"),
                      $("#detailModal").modal("hide")),
                  $("#n2_ajax_loading").fadeOut();
              }));
          }
          function show_receivers_archive(e, a, t, o) {
            empty_tree_grid(),
              $("#treegrid").fancytree({
                extensions: ["table"],
                rtl: !0,
                table: { indentation: 5, nodeColumnIdx: 5 },
                source: {
                  type: "PUT",
                  url: "../automation/auto_ajax?REQ_TYPE=show_receivers",
                  data: {
                    letter_id: e,
                    from_user: a,
                    from_APP_UID: t,
                    fancytree: 1,
                  },
                },
                renderColumns: function (e, a) {
                  var a = a.node,
                    t = $(a.tr).find(">td");
                  t.eq(0).text(a.getIndexHier()),
                    t.eq(1).text(a.data.from_user_name),
                    t.eq(2).text(a.data.DEL_DELEGATE_DATE_J),
                    t.eq(3).text(a.data.receiver_type_name),
                    t.eq(4).text(a.data.receive_type_name),
                    t.eq(6).text(a.data.DEL_TASK_DUE_DATE_J),
                    t.eq(7).text(a.data.DEL_INIT_DATE_J),
                    t.eq(8).text(a.data.APP_UPDATE_DATE_J),
                    t.eq(9).text(a.data.APP_FINISH_DATE_J),
                    t.eq(10).text(a.data.comment);
                },
              }),
              $("#treeModal .modal-title").html("ارجاعات " + o),
              $("#treeModal").modal();
          }
          function show_print_archive(e, a, t, o) {
            "import" != a
              ? "Word" == t
                ? show_print(e)
                : ((a = $("#hidden_printPath").getValue()),
                  "JS" == t
                    ? (a += "viewer.php?mrt=" + o + "&letter_id=" + e)
                    : "Flex" == t &&
                      (a +=
                        "index.php?stimulsoft_client_key=ViewerFx&stimulsoft_report_key=" +
                        o +
                        ".mrt&letter_id=" +
                        e),
                  window.open(a, "_blank").focus())
              : showMessage("این نامه خروجی چاپی ندارد", "info");
          }
          function show_print_word_archive(e, a, t, o) {
            "import" != a && "Word" == t
              ? show_print_word(e)
              : showMessage("این نامه خروجی ورد ندارد", "info");
          }
          function setOrder(e, a, t) {
            1 == a
              ? (a = "letter_type")
              : 2 == a
                ? (a = "dabirkhaneh_id")
                : 3 == a
                  ? (a = "insert_date")
                  : 4 == a
                    ? (a = "number")
                    : 5 == a
                      ? (a = "subject")
                      : 6 == a
                        ? (a = "from_user_name")
                        : 7 == a && (a = "to_user_name"),
              $("#orderColumn").setValue(a),
              $("#orderType").setValue(t),
              search_letter();
          }
          function add_organization() {
            var e = $("#import").getValue(),
              a = $("#export").getValue();
            getFormById(dyn_uid1).isValid() &&
              (0 == e && 0 == a
                ? showMessage("حداقل یک نوع نامه را مشخص کنید", "danger")
                : ($("#n2_ajax_loading").fadeIn(),
                  $.ajax({
                    type: "PUT",
                    url: "../automation/auto_ajax?REQ_TYPE=add_organization",
                    data: {
                      organization_name: $("#text_organization_name").getValue(),
                      phone: $("#phone").getValue(),
                      shenaseh_melli: $("#shenaseh_melli").getValue(),
                      code_eghtesadi: $("#code_eghtesadi").getValue(),
                      code_posti: $("#code_posti").getValue(),
                      address: $("#address").getValue(),
                      template: $("#dropdown_template").getValue(),
                      persons: $("#grid_persons").getValue(),
                      import: e,
                      export: a,
                    },
                  }).done(function (e) {
                    e.error
                      ? showMessage(e.error, "danger")
                      : e.message
                        ? showMessage(e.message, "info")
                        : (showMessage(e.success, "success", 5e3, "موفقیت"),
                          Xcrud.reload(),
                          cencel_edit_organization()),
                      $("#n2_ajax_loading").fadeOut();
                  })));
          }
          function check_edit_organization() {
            var e = $("#hidden_organization_id").getValue(),
              a = $("#import").getValue(),
              t = $("#export").getValue();
            getFormById(dyn_uid1).isValid() &&
              (0 == a && 0 == t
                ? showMessage("حداقل یک نوع نامه را مشخص کنید", "danger")
                : "" == e || 0 == e
                  ? showMessage("شناسه ویرایش به درستی مشخص نشده است", "danger")
                  : ($("#n2_ajax_loading").fadeIn(),
                    $.ajax({
                      type: "PUT",
                      url: "../automation/auto_ajax?REQ_TYPE=edit_organization",
                      data: {
                        id: e,
                        organization_name: $(
                          "#text_organization_name",
                        ).getValue(),
                        phone: $("#phone").getValue(),
                        shenaseh_melli: $("#shenaseh_melli").getValue(),
                        code_eghtesadi: $("#code_eghtesadi").getValue(),
                        code_posti: $("#code_posti").getValue(),
                        address: $("#address").getValue(),
                        template: $("#dropdown_template").getValue(),
                        persons: $("#grid_persons").getValue(),
                        import: a,
                        export: t,
                      },
                    }).done(function (e) {
                      e.error
                        ? showMessage(e.error, "danger")
                        : e.message
                          ? showMessage(e.message, "info")
                          : (showMessage(e.success, "success", 5e3, "موفقیت"),
                            Xcrud.reload(),
                            cencel_edit_organization()),
                        $("#n2_ajax_loading").fadeOut();
                    })));
          }
          function cencel_edit_organization() {
            $("#text_organization_name").disableValidation(),
              $("#hidden_organization_id").setValue(0),
              $("#text_organization_name").setValue(""),
              $("#phone").setValue(""),
              $("#shenaseh_melli").setValue(""),
              $("#code_eghtesadi").setValue(""),
              $("#code_posti").setValue(""),
              $("#address").setValue(""),
              $("#dropdown_template").setValue(0),
              $("#import").setValue(0),
              $("#export").setValue(0);
            for (var e = $("#grid_persons").getNumberRows(), a = 1; a <= e; a++)
              $("#grid_persons").deleteRow();
            $("#text_organization_name").enableValidation(),
              1 == $("#add_permission").getValue() &&
                $("#button_add_organization").show(),
              $("#button_edit_organization").hide(),
              $("#button_cancel_organization").hide();
          }
          function download_attach(e) {
            "" == e || 0 == e
              ? showMessage("شناسه دانلود فایل به درستی مشخص نشده است", "danger")
              : "" != e &&
                0 != e &&
                (window.location = "cases_ShowDocument?a=" + e);
          }
          function hideArrow() {
            $("#dyn_backward").length
              ? $("#dyn_backward").parent().closest("div").css("display", "none")
              : $("#dyn_forward").length &&
                $("#dyn_forward").parent().closest("div").css("display", "none");
          }
          function appendAjaxLoading() {
            $("body").append(
              '<div id="n2_ajax_loading" style="display: none; position: fixed; left: 0px; top: 0px; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.75) url(\'/images/ext/default/shared/large-loading.gif\') center center no-repeat;"></div>',
            );
          }
          function set_link(e, a, t, o, d, n) {
            null == n || 0 == n
              ? (document.getElementById(e).innerHTML =
                  '<img src="' +
                  a +
                  '" title="' +
                  t +
                  '" alt="' +
                  t +
                  '" style="cursor:pointer;" onclick="' +
                  o +
                  "('" +
                  d +
                  "')\" />")
              : (document.getElementById(e).innerHTML +=
                  ' <img src="' +
                  a +
                  '" title="' +
                  t +
                  '" alt="' +
                  t +
                  '" style="cursor:pointer;" onclick="' +
                  o +
                  "('" +
                  d +
                  "')\" />");
          }
          function set_grid_link2(e, a, t, o, d, n) {
            document.getElementById(
              "[" + e + "][" + a + "][" + t + "]",
            ).innerHTML =
              '<button type="button" class="btn btn-default btn-sm" title="' +
              d +
              '" onclick="' +
              n +
              "(" +
              a +
              ')"><span class="glyphicon glyphicon-' +
              o +
              '"></span></button>';
          }
          function dropdown_letter_title(e) {
            var t = "dropdown_letter_title",
              o = ["suggest_letter_unit"],
              d = [];
            "all" == (e = null == e ? "all" : e) &&
              $("#" + t).setOnchange(function (e, a) {
                show_hide_field(e, t, 1, o, d);
              }),
              show_hide_field($("#" + t).getValue(), t, 1, o, d);
          }
          function check_template_type(e) {
            0 < e.indexOf("_Word_0")
              ? ($("#textarea_content").hide(),
                $("#button_word").show(),
                $("#button_preview").show())
              : ($("#textarea_content").show(),
                $("#button_word").hide(),
                $("#button_preview").hide());
          }
          function open_word_file() {
            var e;
            0 == $("#hidden_word").getValue()
              ? ($("#n2_ajax_loading").fadeIn(),
                $.ajax({
                  type: "PUT",
                  url: "../automation/auto_ajax?REQ_TYPE=open_word_file",
                  data: { template: $("#dropdown_template").getValue() },
                }).done(function (e) {
                  e.error
                    ? showMessage(e.error, "danger")
                    : e.message
                      ? showMessage(e.message, "info")
                      : 0 != e.data &&
                        ($("#hidden_word").setValue(e.data),
                        $("#" + dyn_uid1).saveForm(),
                        (e =
                          "ms-word:ofe|u|" +
                          (host + "/uploads/" + $("#hidden_word").getValue())),
                        $("<iframe/>")
                          .width(1)
                          .height(1)
                          .css("visibility", "hidden")
                          .attr("src", e)
                          .appendTo($("body"))),
                    $("#n2_ajax_loading").fadeOut();
                }))
              : ((e =
                  "ms-word:ofe|u|" +
                  (host + "/uploads/" + $("#hidden_word").getValue())),
                $("<iframe/>")
                  .width(1)
                  .height(1)
                  .css("visibility", "hidden")
                  .attr("src", e)
                  .appendTo($("body")));
          }
          function set_organization_template(e) {
            "" != e &&
              ($("#n2_ajax_loading").fadeIn(),
              $.ajax({
                type: "PUT",
                url: "../automation/auto_ajax?REQ_TYPE=set_organization_template",
                data: { organization_id: e },
              }).done(function (e) {
                e.error
                  ? showMessage(e.error, "danger")
                  : e.message
                    ? showMessage(e.message, "info")
                    : "" != e.data && $("#dropdown_template").setValue(e.data),
                  $("#n2_ajax_loading").fadeOut();
              }));
          }
          function call_user_from_group() {
            "" == $("#dropdown_custom_group").getValue()
              ? showMessage("گروه شخصی مورد نظر را انتخاب کنید", "danger")
              : ($("#n2_ajax_loading").fadeIn(),
                $.ajax({
                  type: "PUT",
                  url: "../automation/auto_ajax?REQ_TYPE=show_user_from_group",
                  data: {
                    letter_type: $("#hidden_letter_type").getValue(),
                    custom_group_id: $("#dropdown_custom_group").getValue(),
                  },
                }).done(function (e) {
                  if (e.error) showMessage(e.error, "danger");
                  else if (e.message) showMessage(e.message, "info");
                  else {
                    for (
                      var a =
                          '<table class="table table-responsive table-hover table-bordered"><tr><td><select id="receiver_type" name="receiver_type" class="pmdynaform-control-dropdown form-control"><option value="main" selected="">اصلی</option><option value="bc">رونوشت</option><option value="bcc">رونوشت مخفی</option></select></td><td colspan="2"><button type="button" class="btn btn-primary" onclick="set_user_from_group(' +
                          Object.keys(e.data).length +
                          ');"><span>ارجاع کاربران</span></button></td></tr><tr><th>انتخاب</th><th>ردیف</th><th>کاربر</th></tr><tbody>',
                        t = 1;
                      t <= Object.keys(e.data).length;
                      t++
                    )
                      a +=
                        '<tr><td><input type="checkbox" name="checkbox_' +
                        t +
                        '" id="checkbox_' +
                        t +
                        '" checked="checked" /> <input type="hidden" name="user_' +
                        t +
                        '" id="user_' +
                        t +
                        '" value="' +
                        e.data[t].user_id +
                        '" /><input type="hidden" name="userName_' +
                        t +
                        '" id="userName_' +
                        t +
                        '" value="' +
                        e.data[t].name +
                        '" /><input type="hidden" name="dabirkhaneh_' +
                        t +
                        '" id="dabirkhaneh_' +
                        t +
                        '" value="' +
                        e.data[t].dabirkhaneh_id +
                        '" /></td><td>' +
                        t +
                        "</td><td>" +
                        e.data[t].name +
                        "</td></tr>";
                    (a += "</tbody></table>"),
                      $(".modal-title").html("لیست کاربران گروه شخصی"),
                      $(".modal-body").html(a),
                      $("#detailModal").modal();
                  }
                  $("#n2_ajax_loading").fadeOut();
                }));
          }
          function set_finish_flag(e) {
            $("#hidden_finish_flag").setValue(e);
          }
          function show_receivers(e, a) {
            null == a
              ? $("#treeModal").modal()
              : $("#treegrid").fancytree({
                  extensions: ["table"],
                  rtl: !0,
                  table: { indentation: 5, nodeColumnIdx: 5 },
                  source: {
                    type: "PUT",
                    url: "../automation/auto_ajax?REQ_TYPE=show_receivers",
                    data: {
                      letter_id: e,
                      from_user: $("#hidden_from_user").getValue(),
                      from_APP_UID: $("#hidden_from_APP_UID").getValue(),
                      fancytree: 1,
                    },
                  },
                  renderColumns: function (e, a) {
                    var a = a.node,
                      t = $(a.tr).find(">td");
                    t.eq(0).text(a.getIndexHier()),
                      t.eq(1).text(a.data.from_user_name),
                      t.eq(2).text(a.data.DEL_DELEGATE_DATE_J),
                      t.eq(3).text(a.data.receiver_type_name),
                      t.eq(4).text(a.data.receive_type_name),
                      t.eq(6).text(a.data.DEL_TASK_DUE_DATE_J),
                      t.eq(7).text(a.data.DEL_INIT_DATE_J),
                      t.eq(8).text(a.data.APP_UPDATE_DATE_J),
                      t.eq(9).text(a.data.APP_FINISH_DATE_J),
                      t.eq(10).text(a.data.comment);
                  },
                });
          }
          function show_attachment(e, d) {
            $("#n2_ajax_loading").fadeIn(),
              $.ajax({
                type: "PUT",
                url: "../automation/auto_ajax?REQ_TYPE=show_attachment",
                data: { letter_id: e },
              }).done(function (e) {
                if (e.error) showMessage(e.error, "danger");
                else if (e.message) showMessage(e.message, "info");
                else {
                  for (
                    var a = "پیوست های " + (null != d ? d : ""),
                      t =
                        '<table class="table table-responsive table-hover table-bordered"><thead><tr><th>ردیف</th><th>تاریخ</th><th>از</th><th>توضیحات</th><th>دانلود</th></tr></thead><tbody>',
                      o = 1;
                    o <= Object.keys(e.data).length;
                    o++
                  )
                    t +=
                      "<tr><td>" +
                      o +
                      "</td><td>" +
                      e.data[o].insert_date_j +
                      "</td><td>" +
                      e.data[o].from_user_name +
                      "</td><td>" +
                      e.data[o].comment +
                      '</td><td><button type="button" class="btn btn-default btn-sm" title="دانلود" onclick="download_attach(\'' +
                      e.data[o].attach +
                      '\')"><span class="glyphicon glyphicon-download"></span></button></td></tr>';
                  (t += "</tbody></table>"),
                    $("#detailModal .modal-title").html(a),
                    $("#detailModal .modal-body").html(t),
                    $("#detailModal").modal();
                }
                $("#n2_ajax_loading").fadeOut();
              });
          }
          function show_print(e) {
            $("#n2_ajax_loading").fadeIn(),
              $.ajax({
                type: "PUT",
                url: "../automation/auto_ajax?REQ_TYPE=show_print",
                data: { letter_id: e },
              }).done(function (e) {
                e.error
                  ? showMessage(e.error, "danger")
                  : e.message
                    ? showMessage(e.message, "info")
                    : "" != e.data.pdf
                      ? window
                          .open(
                            "../templateMaker/pages/auto_viewer?file=" +
                              e.data.pdf,
                            "_blank",
                          )
                          .focus()
                      : tm_auto_generate(e.data.templateName, "", e.data.result),
                  $("#n2_ajax_loading").fadeOut();
              });
          }
          function show_print_word(e) {
            $("#n2_ajax_loading").fadeIn(),
              $.ajax({
                type: "PUT",
                url: "../automation/auto_ajax?REQ_TYPE=show_print",
                data: { letter_id: e, type: "word" },
              }).done(function (e) {
                e.error
                  ? showMessage(e.error, "danger")
                  : e.message
                    ? showMessage(e.message, "info")
                    : tm_word_auto_generate(
                        e.data.templateName,
                        "",
                        e.data.result,
                      ),
                  $("#n2_ajax_loading").fadeOut();
              });
          }
          function show_actions(d, n) {
            $("#n2_ajax_loading").fadeIn(),
              $.ajax({
                type: "PUT",
                url: "../automation/auto_ajax?REQ_TYPE=show_actions",
                data: { letter_id: d },
              }).done(function (e) {
                if (e.error) showMessage(e.error, "danger");
                else if (e.message) showMessage(e.message, "info");
                else {
                  for (
                    var a = "اقدامات " + (null != n ? n : ""),
                      t =
                        '<table class="table table-responsive table-hover table-bordered"><thead><tr><th>ردیف</th><th>اقدام کننده</th><th>تاریخ اقدام</th><th>اقدام</th><th>پیوست</th><th>&nbsp;</th></tr></thead><tbody>',
                      o = 1;
                    o <= Object.keys(e.data).length;
                    o++
                  )
                    t +=
                      "<tr><td>" +
                      o +
                      "</td><td>" +
                      e.data[o].user_name +
                      "</td><td>" +
                      e.data[o].insert_date_j +
                      "</td><td>" +
                      e.data[o].action +
                      "</td><td>" +
                      (e.data[o].file_name
                        ? '<a title="دریافت فایل" href="../automation/auto_ajax.php?REQ_TYPE=download_file&type=action&id=' +
                          e.data[o].id +
                          '" class="glyphicon glyphicon-download" style="text-decoration: none;color: blue;cursor: pointer;"></a>'
                        : "") +
                      "</td><td>" +
                      (e.data[o].to_APP_UID == app_uid ||
                      1 == e.data[o].deleteAllow
                        ? '<span title="حذف" onclick="delete_action(' +
                          e.data[o].id +
                          ')" class="glyphicon glyphicon-trash" style="color: red;cursor: pointer;"></span>'
                        : "") +
                      "</td></tr>";
                  (t =
                    (t =
                      t +
                      "</tbody></table>" +
                      '<table class="table table-responsive table-hover table-bordered"><thead><tr><th>ثبت اقدام جدید</th><th>پیوست (اختیاری)</th><th>&nbsp;</th></tr></thead><tbody>') +
                    '<tr><td><textarea id="new_action" name="new_action" rows="3" style="width: 100%;"></textarea></td><td width="20%"><input type="file" id="file_action" name="file_action" style="display:none;"><button id="upload_f" class="btn btn-uploadfile">انتخاب فایل</button></td><td width="10%"><button type="button" class="btn btn-success" id="btn_action">ثبت</button></td></tr>' +
                    "</tbody></table>"),
                    $("#detailModal .modal-title").html(a),
                    $("#detailModal .modal-body").html(t),
                    $("#detailModal").modal(),
                    $("#btn_action").click(function () {
                      add_action(d);
                    }),
                    $("#upload_f").click(function () {
                      return $("#file_action").click(), !1;
                    });
                }
                $("#n2_ajax_loading").fadeOut();
              });
          }
          function dropdown_letter_type(e) {
            var t = "dropdown_letter_type",
              o = [],
              d = [
                "suggest_import_organization",
                "datetime_internal_letter_date",
                "text_internal_letter_number",
              ],
              n = [],
              r = ["suggest_export_organization"],
              i = [],
              _ = ["organization_person"];
            "all" == (e = null == e ? "all" : e) &&
              $("#" + t).setOnchange(function (e, a) {
                show_hide_field(e, t, "import", o, d),
                  show_hide_field(e, t, "export", n, r),
                  show_hide_field(e, t, "import*export", i, _);
              }),
              show_hide_field($("#" + t).getValue(), t, "import", o, d),
              show_hide_field($("#" + t).getValue(), t, "export", n, r),
              show_hide_field($("#" + t).getValue(), t, "import*export", i, _);
          }
          function search_letter() {
            var e = $("#dropdown_letter_type").getValue();
            $("#n2_ajax_loading").fadeIn(),
              $("#panel_grid .panel-body").html(),
              $.ajax({
                type: "PUT",
                url: "../automation/auto_ajax?REQ_TYPE=search_letter",
                data: {
                  letter_title: $("#dropdown_letter_title").getValue(),
                  letter_type: e,
                  dabirkhaneh: $("#dropdown_dabirkhaneh").getValue(),
                  vaziat: $("#dropdown_vaziat").getValue(),
                  type_erja: $("#dropdown_type_erja").getValue(),
                  subject: $("#text_subject").getValue(),
                  letter_date: $("#datetime_letter_date").getValue(),
                  letter_number: $("#text_letter_number").getValue(),
                  priority: $("#dropdown_priority").getValue(),
                  security: $("#dropdown_security").getValue(),
                  export_organization:
                    "export" == e
                      ? $("#suggest_export_organization").getValue()
                      : "",
                  import_organization:
                    "import" == e
                      ? $("#suggest_import_organization").getValue()
                      : "",
                  organization_person:
                    "import" == e || "export" == e
                      ? $("#organization_person").getValue()
                      : "",
                  internal_letter_date:
                    "import" == e
                      ? $("#datetime_internal_letter_date").getValue()
                      : "",
                  internal_letter_number:
                    "import" == e
                      ? $("#text_internal_letter_number").getValue()
                      : "",
                  search_all: $("#search_all").getValue(),
                  orderColumn: $("#orderColumn").getValue(),
                  orderType: $("#orderType").getValue(),
                  sender: $("#sender").getValue(),
                  receiver: $("#receiver").getValue(),
                },
              }).done(function (e) {
                e.error
                  ? showMessage(e.error, "danger")
                  : e.message
                    ? showMessage(e.message, "info")
                    : $("#panel_grid .panel-body").html(e);
                    $("#panel_grid .panel-body table tr").each(function () {
                      var letterType = $(this).find(".letter_type_name").text().trim();
                      if (letterType === "internal") {
                        $(this).remove();
                    }});
                  $("#n2_ajax_loading").fadeOut();
              });
          }
          function delete_search() {
            $("#dropdown_letter_title").setValue(""),
              $("#dropdown_letter_type").setValue(""),
              $("#dropdown_dabirkhaneh").setValue(""),
              $("#text_subject").setValue(""),
              $("#datetime_letter_date").setValue(""),
              $("#text_letter_number").setValue(""),
              $("#dropdown_priority").setValue(""),
              $("#dropdown_security").setValue(""),
              $("#sender").setValue(""),
              $("#receiver").setValue(""),
              $("#organization_person").setValue(""),
              $("#dropdown_vaziat").setValue(""),
              $("#dropdown_type_erja").setValue(""),
              $("#suggest_export_organization").setValue(""),
              $("#suggest_import_organization").setValue(""),
              $("#datetime_internal_letter_date").setValue(""),
              $("#text_internal_letter_number").setValue(""),
              $("#search_all").setValue(""),
              search_letter();
          }
          function HideAdvanced() {
            $("#dropdown_letter_title").hide(),
              $("#dropdown_letter_type").hide(),
              $("#dropdown_dabirkhaneh").hide(),
              $("#text_subject").hide(),
              $("#datetime_letter_date").hide(),
              $("#text_letter_number").hide(),
              $("#dropdown_priority").hide(),
              $("#dropdown_security").hide(),
              $("#organization_person").hide(),
              $("#dropdown_vaziat").hide(),
              $("#dropdown_type_erja").hide(),
              $("#suggest_export_organization").hide(),
              $("#suggest_import_organization").hide(),
              $("#datetime_internal_letter_date").hide(),
              $("#text_internal_letter_number").hide(),
              $("#sender").hide(),
              $("#receiver").hide(),
              $("#button_search").hide(),
              $("#button_delete_search").hide(),
              $(".downAdvanced").hide(),
              $(".leftAdvanced").show();
          }
          function ShowAdvanced() {
            $("#dropdown_letter_title").show(),
              $("#dropdown_letter_type").show(),
              $("#dropdown_dabirkhaneh").show(),
              $("#text_subject").show(),
              $("#datetime_letter_date").show(),
              $("#text_letter_number").show(),
              $("#dropdown_priority").show(),
              $("#dropdown_security").show(),
              $("#sender").show(),
              $("#receiver").show(),
              $("#button_search").show(),
              $("#button_delete_search").show(),
              $("#dropdown_vaziat").show(),
              $("#dropdown_type_erja").show(),
              dropdown_letter_type(),
              $(".downAdvanced").show(),
              $(".leftAdvanced").hide();
          }
          function setOrderLabels() {
            for (var e = 1; e <= 7; e++)
              ($("div.pmdynaform-grid-thead > div > span")[e].innerHTML +=
                '<span class="glyphicon glyphicon-chevron-down" style="cursor: pointer;" onclick="setOrder(\'grid_archive\', ' +
                e +
                ", 'desc');\">"),
                ($("div.pmdynaform-grid-thead > div > span")[e].innerHTML +=
                  '&nbsp;<span class="glyphicon glyphicon-chevron-up" style="cursor: pointer;" onclick="setOrder(\'grid_archive\', ' +
                  e +
                  ", 'asc');\">");
          }
          function add_signer() {
            var e = $("#suggest_user").getValue(),
              a =
                ($("#dropdown_letter_type").getValue(),
                $("#internal").getValue()),
              t = $("#export").getValue();
            if (getFormById(dyn_uid1).isValid())
              if (0 == a && 0 == t)
                showMessage("حداقل یک نوع نامه را مشخص کنید", "danger");
              else {
                for (
                  var o = [],
                    d = $("input[name^='form[multipleFile_sign]']").length / 3,
                    n = 0;
                  n < d &&
                  0 <
                    document.getElementsByName(
                      "form[multipleFile_sign][" + n + "][appDocUid]",
                    ).length;
                  n++
                ) {
                  var r = [];
                  (r[r.length] = document.getElementsByName(
                    "form[multipleFile_sign][" + n + "][appDocUid]",
                  )[0].value),
                    (r[r.length] = document.getElementsByName(
                      "form[multipleFile_sign][" + n + "][name]",
                    )[0].value),
                    (r[r.length] = document.getElementsByName(
                      "form[multipleFile_sign][" + n + "][version]",
                    )[0].value),
                    o.push(r);
                }
                0 == o.length
                  ? showMessage("تصویر امضا را مشخص کنید", "danger")
                  : 1 < o.length
                    ? showMessage("یک تصویر امضا می توانید درج کنید", "danger")
                    : ($("#n2_ajax_loading").fadeIn(),
                      $.ajax({
                        type: "PUT",
                        url: "../automation/auto_ajax?REQ_TYPE=add_signer",
                        data: {
                          user: e,
                          internal: a,
                          export: t,
                          uploadedFiles: o,
                        },
                      }).done(function (e) {
                        var a, t;
                        e.error
                          ? showMessage(e.error, "danger")
                          : e.message
                            ? showMessage(e.message, "info")
                            : (showMessage(
                                e.data.message,
                                "success",
                                5e3,
                                "موفقیت",
                              ),
                              1 ==
                                (t = (a = $("#grid_signer")).getNumberRows()) &&
                              "" == a.getValue(1, 1)
                                ? a.deleteRow(1)
                                : t++,
                              (e = [
                                { value: e.data.id },
                                { value: e.data.USR_UID },
                                { value: e.data.sign_file },
                                { value: e.data.signer_name },
                                { value: e.data.internal },
                                { value: e.data.export },
                              ]),
                              a.addRow(e),
                              $("input[id*=grid_internal]").click(function () {
                                update_signer(this);
                              }),
                              $("input[id*=grid_export]").click(function () {
                                update_signer(this);
                              }),
                              set_grid_link2(
                                "grid_signer",
                                t,
                                "link_download",
                                "download",
                                "دانلود",
                                "download_attach_2",
                              ),
                              set_grid_link2(
                                "grid_signer",
                                t,
                                "link_delete",
                                "trash",
                                "حذف",
                                "delete_signer",
                              ),
                              $("#suggest_user").disableValidation(),
                              $("#suggest_user").setValue(""),
                              $("#suggest_user").setText(""),
                              $("#suggest_user").enableValidation(),
                              $(".fa.fa-trash").trigger("click")),
                          $("#n2_ajax_loading").fadeOut();
                      }));
              }
          }
          function update_signer(e) {
            var a,
              t,
              o,
              d = e.id;
            0 <
              (d = (d = d.replace("form[", "")).substr(0, d.length - 1)).indexOf(
                "][",
              ) && ((a = (d = d.split("]["))[1]), (t = d[2])),
              "" == a || 0 == a
                ? showMessage("شناسه ویرایش را به درستی مشخص کنید", "danger")
                : ((d = $("#grid_signer").getValue(a, 1)),
                  (o = $("#grid_signer").getValue(a, 2)),
                  $("#n2_ajax_loading").fadeIn(),
                  $.ajax({
                    type: "PUT",
                    url: "../automation/auto_ajax?REQ_TYPE=update_signer",
                    data: {
                      id: d,
                      signer: o,
                      internal:
                        "grid_internal" == t
                          ? e.checked
                            ? 1
                            : 0
                          : $("#grid_signer").getValue(a, 5),
                      export:
                        "grid_export" == t
                          ? e.checked
                            ? 1
                            : 0
                          : $("#grid_signer").getValue(a, 6),
                    },
                  }).done(function (e) {
                    e.error
                      ? showMessage(e.error, "danger")
                      : e.message
                        ? showMessage(e.message, "info")
                        : showMessage(e.data.message, "success", 5e3, "موفقیت"),
                      $("#n2_ajax_loading").fadeOut();
                  }));
          }
          function delete_signer(a) {
            var e, t;
            "" == a || 0 == a
              ? showMessage("شناسه حذف به درستی مشخص نشده است", "danger")
              : ((e = $("#grid_signer").getValue(a, 1)),
                (t = $("#grid_signer").getValue(a, 2)),
                confirm("برای حذف این امضا کننده مطمئنید؟") &&
                  ($("#n2_ajax_loading").fadeIn(),
                  $.ajax({
                    type: "PUT",
                    url: "../automation/auto_ajax?REQ_TYPE=delete_signer",
                    data: { id: e, signer: t },
                  }).done(function (e) {
                    e.error
                      ? showMessage(e.error, "danger")
                      : e.message
                        ? showMessage(e.message, "info")
                        : (showMessage(e.data.message, "success", 5e3, "موفقیت"),
                          getFieldById("grid_signer")
                            .tableBody.children()
                            .eq(a - 1)
                            .css("display", "none")),
                      $("#n2_ajax_loading").fadeOut();
                  })));
          }
          function edit_organization(e, a, t, o, d, n, r, i, _, s) {
            cencel_edit_organization(),
              $("#n2_ajax_loading").fadeIn(),
              $.ajax({
                type: "PUT",
                url: "../automation/auto_ajax?REQ_TYPE=search_organization_persons",
                data: { id: e },
              }).done(function (e) {
                if (e.error) showMessage(e.error, "danger");
                else if (e.message) showMessage(e.message, "info");
                else
                  for (var a = 1; a <= Object.keys(e).length; a++) {
                    var t = [{ value: e[a].name }, { value: e[a].post }];
                    $("#grid_persons").addRow(t);
                  }
                $("#n2_ajax_loading").fadeOut();
              }),
              $("#hidden_organization_id").setValue(e),
              $("#text_organization_name").setValue(t),
              $("#phone").setValue(o),
              $("#shenaseh_melli").setValue(d),
              $("#code_eghtesadi").setValue(n),
              $("#code_posti").setValue(r),
              $("#address").setValue(i),
              $("#dropdown_template").setValue("" != a ? a : 0),
              $("#import").setValue(_),
              $("#export").setValue(s),
              $("#button_add_organization").hide(),
              $("#button_edit_organization").show(),
              $("#button_cancel_organization").show();
          }
          function delete_organization(e, a) {
            confirm("برای حذف این سازمان مطمئنید؟") &&
              ($("#n2_ajax_loading").fadeIn(),
              $.ajax({
                type: "PUT",
                url: "../automation/auto_ajax?REQ_TYPE=delete_organization",
                data: { id: e, organization_name: a },
              }).done(function (e) {
                e.error
                  ? showMessage(e.error, "danger")
                  : e.message
                    ? showMessage(e.message, "info")
                    : (showMessage(e.success, "success", 5e3, "موفقیت"),
                      Xcrud.reload()),
                  $("#n2_ajax_loading").fadeOut();
              }));
          }
          function add_semat() {
            var e = $("#USR_UID").getValue(),
              a = $("#semat").getValue();
            "" == e
              ? showMessage("نام و نام خانوادگی را مشخص کنید", "danger")
              : "" == a
                ? showMessage("سمت را مشخص کنید", "danger")
                : ($("#n2_ajax_loading").fadeIn(),
                  $.ajax({
                    type: "PUT",
                    url: "../automation/auto_ajax?REQ_TYPE=add_semat",
                    data: { USR_UID: e, semat: a },
                  }).done(function (e) {
                    e.error
                      ? showMessage(e.error, "danger")
                      : e.message
                        ? showMessage(e.message, "info")
                        : (showMessage(e.data.message, "success", 5e3, "موفقیت"),
                          Xcrud.reload(),
                          cencel_edit_semat()),
                      $("#n2_ajax_loading").fadeOut();
                  }));
          }
          function delete_semat(e) {
            confirm("برای حذف این سمت مطمئنید؟") &&
              ($("#n2_ajax_loading").fadeIn(),
              $.ajax({
                type: "PUT",
                url: "../automation/auto_ajax?REQ_TYPE=delete_semat",
                data: { id: e },
              }).done(function (e) {
                e.error
                  ? showMessage(e.error, "danger")
                  : e.message
                    ? showMessage(e.message, "info")
                    : (showMessage(e.data.message, "success", 5e3, "موفقیت"),
                      Xcrud.reload()),
                  $("#n2_ajax_loading").fadeOut();
              }));
          }
          function check_edit_semat() {
            var e = $("#USR_UID").getValue(),
              a = $("#semat").getValue(),
              t = $("#id").getValue();
            "" == e
              ? showMessage("نام و نام خانوادگی را مشخص کنید", "danger")
              : "" == a
                ? showMessage("سمت را مشخص کنید", "danger")
                : "" == t
                  ? showMessage("شناسه ویرایش به درستی مشخص نشده است", "danger")
                  : ($("#n2_ajax_loading").fadeIn(),
                    $.ajax({
                      type: "PUT",
                      url: "../automation/auto_ajax?REQ_TYPE=edit_semat",
                      data: { USR_UID: e, semat: a, id: t },
                    }).done(function (e) {
                      e.error
                        ? showMessage(e.error, "danger")
                        : e.message
                          ? showMessage(e.message, "info")
                          : (showMessage(
                              e.data.message,
                              "success",
                              5e3,
                              "موفقیت",
                            ),
                            $("#letter_type").setValue([""]),
                            Xcrud.reload(),
                            cencel_edit_semat()),
                        $("#n2_ajax_loading").fadeOut();
                    }));
          }
          function edit_semat(e, a, t) {
            cencel_edit_dabirkhaneh(),
              $("#id").setValue(e),
              $("#USR_UID").setValue(a),
              $("#semat").setValue(t),
              $("#button_add_semat").hide(),
              $("#button_edit_semat").show(),
              $("#button_cancel_semat").show();
          }
          function cencel_edit_semat() {
            $("#id").disableValidation(),
              $("#USR_UID").disableValidation(),
              $("#semat").disableValidation(),
              $("#id").setValue(""),
              $("#USR_UID").setValue(""),
              $("#semat").setValue(""),
              $("#id").enableValidation(),
              $("#USR_UID").enableValidation(),
              $("#semat").enableValidation(),
              $("#button_add_semat").show(),
              $("#button_edit_semat").hide(),
              $("#button_cancel_semat").hide();
          }
          function add_dabirkhaneh() {
            var e = $("#text_dabirkhaneh_name").getValue(),
              a = $("#text_number_structure").getValue(),
              t = $("#text_number_start").getValue(),
              o = $("#letter_type").getValue();
            "" == e
              ? showMessage("نام دبیرخانه را مشخص کنید", "danger")
              : "" == a
                ? showMessage("ساختار شماره نامه را مشخص کنید", "danger")
                : "" == t
                  ? showMessage("شروع شماره نامه را مشخص کنید", "danger")
                  : "" == o
                    ? showMessage("نوع قالب نامه را مشخص کنید", "danger")
                    : ($("#n2_ajax_loading").fadeIn(),
                      $.ajax({
                        type: "PUT",
                        url: "../automation/auto_ajax?REQ_TYPE=add_dabirkhaneh",
                        data: {
                          dabirkhaneh_name: e,
                          number_structure: a,
                          number_start: t,
                          letter_type: o,
                        },
                      }).done(function (e) {
                        e.error
                          ? showMessage(e.error, "danger")
                          : e.message
                            ? showMessage(e.message, "info")
                            : (showMessage(
                                e.data.message,
                                "success",
                                5e3,
                                "موفقیت",
                              ),
                              $("#letter_type").setValue([""]),
                              Xcrud.reload()),
                          $("#n2_ajax_loading").fadeOut();
                      }));
          }
          function delete_dabirkhaneh(e, a) {
            confirm("برای حذف این دبیرخانه مطمئنید؟") &&
              ($("#n2_ajax_loading").fadeIn(),
              $.ajax({
                type: "PUT",
                url: "../automation/auto_ajax?REQ_TYPE=delete_dabirkhaneh",
                data: { id: e, dabirkhaneh_name: a },
              }).done(function (e) {
                e.error
                  ? showMessage(e.error, "danger")
                  : e.message
                    ? showMessage(e.message, "info")
                    : (showMessage(e.data.message, "success", 5e3, "موفقیت"),
                      Xcrud.reload()),
                  $("#n2_ajax_loading").fadeOut();
              }));
          }
          function dabirkhaneh_user(e) {
            go_to_form(
              dyn_uid1,
              "5419247275a3de36563a2e6027748804",
              2,
              "EDIT",
              "&id=" + e,
            );
          }
          function check_edit_dabirkhaneh() {
            var e = $("#text_dabirkhaneh_name").getValue(),
              a = $("#text_number_structure").getValue(),
              t = $("#text_number_start").getValue(),
              o = $("#letter_type").getValue(),
              d = $("#hidden_dabirkhaneh_id").getValue();
            "" == e
              ? showMessage("نام دبیرخانه را مشخص کنید", "danger")
              : "" == a
                ? showMessage("ساختار شماره نامه را مشخص کنید", "danger")
                : "" == t
                  ? showMessage("شروع شماره نامه را مشخص کنید", "danger")
                  : "" == d || 0 == d
                    ? showMessage("شناسه ویرایش به درستی مشخص نشده است", "danger")
                    : "" == o || 0 == o
                      ? showMessage("نوع قالب نامه مشخص نشده است", "danger")
                      : ($("#n2_ajax_loading").fadeIn(),
                        $.ajax({
                          type: "PUT",
                          url: "../automation/auto_ajax?REQ_TYPE=edit_dabirkhaneh",
                          data: {
                            dabirkhaneh_name: e,
                            number_structure: a,
                            number_start: t,
                            letter_type: o,
                            id: d,
                          },
                        }).done(function (e) {
                          e.error
                            ? showMessage(e.error, "danger")
                            : e.message
                              ? showMessage(e.message, "info")
                              : (showMessage(
                                  e.data.message,
                                  "success",
                                  5e3,
                                  "موفقیت",
                                ),
                                $("#letter_type").setValue([""]),
                                Xcrud.reload(),
                                cencel_edit_dabirkhaneh()),
                            $("#n2_ajax_loading").fadeOut();
                        }));
          }
          function edit_dabirkhaneh(e, a, t, o, d) {
            cencel_edit_dabirkhaneh(),
              $("#text_dabirkhaneh_name").setValue(a),
              $("#text_number_structure").setValue(t),
              $("#text_number_start").setValue(o),
              $("#letter_type").setValue(d.split(",")),
              $("#hidden_dabirkhaneh_id").setValue(e),
              $("#button_add_dabirkhaneh").hide(),
              $("#button_edit_dabirkhaneh").show(),
              $("#button_cancel_dabirkhaneh").show();
          }
          function cencel_edit_dabirkhaneh() {
            $("#text_dabirkhaneh_name").disableValidation(),
              $("#text_number_structure").disableValidation(),
              $("#letter_type").disableValidation(),
              $("#text_dabirkhaneh_name").setValue(""),
              $("#text_number_structure").setValue(""),
              $("#text_number_start").setValue("1"),
              $("#letter_type").setValue([""]),
              $("#hidden_dabirkhaneh_id").setValue(0),
              $("#text_dabirkhaneh_name").enableValidation(),
              $("#text_number_structure").enableValidation(),
              $("#letter_type").enableValidation(),
              $("#button_add_dabirkhaneh").show(),
              $("#button_edit_dabirkhaneh").hide(),
              $("#button_cancel_dabirkhaneh").hide();
          }
          function add_dabirkhaneh_user() {
            var e = $("#suggest_user").getValue(),
              a = $("#suggest_group").getValue();
            getFormById(dyn_uid1).isValid() &&
              (("" == e && "" == a) || ("" != e && "" != a)
                ? showMessage("کاربر یا گروه کاربری را مشخص کنید", "danger")
                : ($("#n2_ajax_loading").fadeIn(),
                  $.ajax({
                    type: "PUT",
                    url: "../automation/auto_ajax?REQ_TYPE=add_dabirkhaneh_user",
                    data: {
                      dabirkhaneh: $("#dropdown_dabirkhaneh").getValue(),
                      user: e,
                      group: a,
                      supervisor: $("#checkbox_supervisor").getValue(),
                      view_secret: $("#view_secret").getValue(),
                      view_secret_file: $("#view_secret_file").getValue(),
                    },
                  }).done(function (e) {
                    e.error
                      ? showMessage(e.error, "danger")
                      : e.message
                        ? showMessage(e.message, "info")
                        : (showMessage(e.data.message, "success", 5e3, "موفقیت"),
                          Xcrud.reload(),
                          $("#suggest_user").setValue(""),
                          $("#suggest_group").setValue(""),
                          $("#checkbox_supervisor").setValue("0"),
                          $("#view_secret").setValue("0"),
                          $("#view_secret_file").setValue("0")),
                      $("#n2_ajax_loading").fadeOut();
                  })));
          }
          function delete_dabirkhaneh_user(e, a) {
            confirm("برای حذف این کاربر مطمئنید؟") &&
              ($("#n2_ajax_loading").fadeIn(),
              $.ajax({
                type: "PUT",
                url: "../automation/auto_ajax?REQ_TYPE=delete_dabirkhaneh_user",
                data: { user: a, dabirkhaneh: e },
              }).done(function (e) {
                e.error
                  ? showMessage(e.error, "danger")
                  : e.message
                    ? showMessage(e.message, "info")
                    : (showMessage(e.data.message, "success", 5e3, "موفقیت"),
                      Xcrud.reload()),
                  $("#n2_ajax_loading").fadeOut();
              }));
          }
          function edit_dabirkhaneh_user(e, a, t, o, d, n) {
            (t =
              '<table class="table table-responsive table-hover table-bordered"><thead><tr><th>این کاربر سوپروایزر است</th><th>مشاهده نامه های محرمانه</th><th>مشاهده فایل های محرمانه</th><th></th></tr></thead><tbody><tr><td><input type="radio" id="supervisor0" name="supervisor" value="0" ' +
              ("0" == t ? "checked" : "") +
              ' /><label>&nbsp;خیر&nbsp;</label><input type="radio" id="supervisor1" name="supervisor" value="1" ' +
              ("1" == t ? "checked" : "") +
              ' /><label>&nbsp;بله</label></td><td><input type="radio" id="view_secret0" name="view_secret" value="0" ' +
              ("0" == o ? "checked" : "") +
              ' /><label>&nbsp;خیر&nbsp;</label><input type="radio" id="view_secret1" name="view_secret" value="1" ' +
              ("1" == o ? "checked" : "") +
              ' /><label>&nbsp;بله</label></td><td><input type="radio" id="view_secret_file0" name="view_secret_file" value="0" ' +
              ("0" == d ? "checked" : "") +
              ' /><label>&nbsp;خیر&nbsp;</label><input type="radio" id="view_secret_file1" name="view_secret_file" value="1" ' +
              ("1" == d ? "checked" : "") +
              ' /><label>&nbsp;بله</label></td><td><input type="button" class="btn btn-success" value="ذخیره" onclick="update_dabirkhaneh_user();" /><input type="hidden" id="dabirkhaneh" name="dabirkhaneh" value="' +
              e +
              '" /><input type="hidden" id="user" name="user" value="' +
              a +
              '" /></td></tr></tbody></table>'),
              (o = "ویرایش " + n);
            $(".modal-title").html(o),
              $(".modal-body").html(t),
              $("#editModal").modal();
          }
          function update_dabirkhaneh_user() {
            $("#n2_ajax_loading").fadeIn(),
              $.ajax({
                type: "PUT",
                url: "../automation/auto_ajax?REQ_TYPE=update_dabirkhaneh_user",
                data: {
                  user: $("#user").val(),
                  dabirkhaneh: $("#dabirkhaneh").val(),
                  supervisor: $('input[name="supervisor"]:checked').val(),
                  view_secret: $('input[name="view_secret"]:checked').val(),
                  view_secret_file: $(
                    'input[name="view_secret_file"]:checked',
                  ).val(),
                },
              }).done(function (e) {
                e.error
                  ? showMessage(e.error, "danger")
                  : e.message
                    ? showMessage(e.message, "info")
                    : (showMessage(e.data.message, "success", 5e3, "موفقیت"),
                      $("#editModal").modal("hide"),
                      Xcrud.reload()),
                  $("#n2_ajax_loading").fadeOut();
              });
          }
          function back_to_dabirkhaneh() {
            go_to_form(dyn_uid1, "8349182405a38ba6f41ed48033795372", 1);
          }
          function dropdown_variable_type(e) {
            var t = "dropdown_variable_type",
              o = [],
              d = [
                "dropdown_database_name",
                "dropdown_database_table",
                "dropdown_database_column",
                "database_where",
              ],
              n = [],
              r = ["text_variable_value"];
            "all" == (e = null == e ? "all" : e) &&
              $("#" + t).setOnchange(function (e, a) {
                show_hide_field(e, t, "database", o, d),
                  show_hide_field(e, t, "variable", n, r);
              }),
              show_hide_field($("#" + t).getValue(), t, "database", o, d),
              show_hide_field($("#" + t).getValue(), t, "variable", n, r);
          }
          function add_variable() {
            var e = $("#text_variable_name").getValue(),
              a = $("#dropdown_variable_type").getValue(),
              t = $("#text_variable_key").getValue(),
              o = $("#text_variable_value").getValue(),
              d = $("#dropdown_database_name").getValue(),
              n = $("#dropdown_database_table").getValue(),
              r = $("#dropdown_database_column").getValue(),
              i = $("#database_where").getValue();
            if ("" == e) showMessage("نام متغیر را مشخص کنید", "danger");
            else if ("" == a) showMessage("نوع متغیر را مشخص کنید", "danger");
            else if ("" == t)
              showMessage("کلمه کلیدی متغیر را مشخص کنید", "danger");
            else {
              if ("database" == a) {
                if ("" == d)
                  return void showMessage("پایگاه داده را مشخص کنید", "danger");
                if ("" == n)
                  return void showMessage("جدول را مشخص کنید", "danger");
                if ("" == r)
                  return void showMessage("ستون را مشخص کنید", "danger");
              }
              $("#n2_ajax_loading").fadeIn(),
                $.ajax({
                  type: "PUT",
                  url: "../automation/auto_ajax?REQ_TYPE=add_variable",
                  data: {
                    variable_name: e,
                    variable_type: a,
                    variable_key: t,
                    variable_value: o,
                    database_name: d,
                    database_table: n,
                    database_column: r,
                    database_where: i,
                  },
                }).done(function (e) {
                  e.error
                    ? showMessage(e.error, "danger")
                    : e.message
                      ? showMessage(e.message, "info")
                      : (showMessage(e.data.message, "success", 5e3, "موفقیت"),
                        Xcrud.reload()),
                    $("#n2_ajax_loading").fadeOut();
                });
            }
          }
          function check_edit_variable() {
            var e = $("#text_variable_name").getValue(),
              a = $("#dropdown_variable_type").getValue(),
              t = $("#text_variable_key").getValue(),
              o = $("#text_variable_value").getValue(),
              d = $("#dropdown_database_name").getValue(),
              n = $("#dropdown_database_table").getValue(),
              r = $("#dropdown_database_column").getValue(),
              i = $("#database_where").getValue(),
              _ = $("#hidden_variable_id").getValue();
            if ("" == e) showMessage("نام متغیر را مشخص کنید", "danger");
            else if ("" == a) showMessage("نوع متغیر را مشخص کنید", "danger");
            else if ("" == t)
              showMessage("کلمه کلیدی متغیر را مشخص کنید", "danger");
            else if ("" == _ || 0 == _)
              showMessage("شناسه ویرایش به درستی مشخص نشده است", "danger");
            else {
              if ("database" == a) {
                if ("" == d)
                  return void showMessage("پایگاه داده را مشخص کنید", "danger");
                if ("" == n)
                  return void showMessage("جدول را مشخص کنید", "danger");
                if ("" == r)
                  return void showMessage("ستون را مشخص کنید", "danger");
              }
              $("#n2_ajax_loading").fadeIn(),
                $.ajax({
                  type: "PUT",
                  url: "../automation/auto_ajax?REQ_TYPE=edit_variable",
                  data: {
                    variable_name: e,
                    variable_type: a,
                    variable_key: t,
                    variable_value: o,
                    database_name: d,
                    database_table: n,
                    database_column: r,
                    database_where: i,
                    id: _,
                  },
                }).done(function (e) {
                  e.error
                    ? showMessage(e.error, "danger")
                    : e.message
                      ? showMessage(e.message, "info")
                      : (showMessage(e.data.message, "success", 5e3, "موفقیت"),
                        Xcrud.reload(),
                        cencel_edit_variable()),
                    $("#n2_ajax_loading").fadeOut();
                });
            }
          }
          function delete_variable(e, a) {
            confirm("برای حذف این متغیر مطمئنید؟") &&
              ($("#n2_ajax_loading").fadeIn(),
              $.ajax({
                type: "PUT",
                url: "../automation/auto_ajax?REQ_TYPE=delete_variable",
                data: { id: e, variable_key: a },
              }).done(function (e) {
                e.error
                  ? showMessage(e.error, "danger")
                  : e.message
                    ? showMessage(e.message, "info")
                    : (showMessage(e.data.message, "success", 5e3, "موفقیت"),
                      Xcrud.reload()),
                  $("#n2_ajax_loading").fadeOut();
              }));
          }
          function edit_variable(e, a, t, o, d, n, r, i, _) {
            cencel_edit_variable(),
              $("#hidden_variable_id").setValue(e),
              $("#text_variable_name").setValue(a),
              $("#dropdown_variable_type").setValue(t),
              $("#text_variable_key").setValue(o),
              $("#text_variable_value").setValue(d),
              $("#database_where").setValue(_),
              $("#button_add_variable").hide(),
              $("#button_edit_variable").show(),
              $("#button_cancel_variable").show(),
              $("#dropdown_database_name").setTextAsync(n, function () {
                $("#dropdown_database_table").setTextAsync(r, function () {
                  $("#dropdown_database_column").setTextAsync(i);
                });
              });
          }
          function cencel_edit_variable() {
            $("#text_variable_name").disableValidation(),
              $("#dropdown_variable_type").disableValidation(),
              $("#text_variable_key").disableValidation(),
              $("#text_variable_name").setValue(""),
              $("#dropdown_variable_type").setValue(""),
              $("#text_variable_key").setValue(""),
              $("#text_variable_value").setValue(""),
              $("#text_variable_name").enableValidation(),
              $("#dropdown_variable_type").enableValidation(),
              $("#text_variable_key").enableValidation(),
              $("#dropdown_database_name").disableValidation(),
              $("#dropdown_database_table").disableValidation(),
              $("#dropdown_database_column").disableValidation(),
              $("#dropdown_database_name").setValue(""),
              $("#dropdown_database_table").setValue(""),
              $("#dropdown_database_column").setValue(""),
              $("#database_where").setValue(""),
              $("#dropdown_database_name").enableValidation(),
              $("#dropdown_database_table").enableValidation(),
              $("#dropdown_database_column").enableValidation(),
              $("#button_add_variable").show(),
              $("#button_edit_variable").hide(),
              $("#button_cancel_variable").hide();
          }
          function dropdown_user_type_from(e) {
            var t = "dropdown_user_type_from",
              o = [],
              d = ["suggest_user_from"],
              n = [],
              r = ["suggest_group_from"],
              i = [],
              _ = ["suggest_department_from"],
              s = [],
              l = ["suggest_dabirkhaneh_from"];
            "all" == (e = null == e ? "all" : e) &&
              $("#" + t).setOnchange(function (e, a) {
                show_hide_field(e, t, "user", o, d),
                  show_hide_field(e, t, "dabirkhaneh", s, l),
                  show_hide_field(e, t, "department", i, _),
                  show_hide_field(e, t, "group", n, r);
              }),
              show_hide_field($("#" + t).getValue(), t, "user", o, d),
              show_hide_field($("#" + t).getValue(), t, "dabirkhaneh", s, l),
              show_hide_field($("#" + t).getValue(), t, "department", i, _),
              show_hide_field($("#" + t).getValue(), t, "group", n, r);
          }
          function dropdown_user_type_to(e) {
            var t = "dropdown_user_type_to",
              o = [],
              d = ["suggest_user_to"],
              n = [],
              r = ["suggest_group_to"],
              i = [],
              _ = ["suggest_department_to"],
              s = [],
              l = ["suggest_dabirkhaneh_to"];
            "all" == (e = null == e ? "all" : e) &&
              $("#" + t).setOnchange(function (e, a) {
                show_hide_field(e, t, "user", o, d),
                  show_hide_field(e, t, "dabirkhaneh", s, l),
                  show_hide_field(e, t, "department", i, _),
                  show_hide_field(e, t, "group", n, r);
              }),
              show_hide_field($("#" + t).getValue(), t, "user", o, d),
              show_hide_field($("#" + t).getValue(), t, "dabirkhaneh", s, l),
              show_hide_field($("#" + t).getValue(), t, "department", i, _),
              show_hide_field($("#" + t).getValue(), t, "group", n, r);
          }
          function add_permission_format() {
            var e = $("#dropdown_user_type_from").getValue(),
              a = $("#suggest_dabirkhaneh_from").getValue(),
              t = $("#suggest_department_from").getValue(),
              o = $("#suggest_group_from").getValue(),
              d = $("#suggest_user_from").getValue(),
              n = $("#dropdown_user_type_to").getValue(),
              r = $("#suggest_dabirkhaneh_to").getValue(),
              i = $("#suggest_department_to").getValue(),
              _ = $("#suggest_group_to").getValue(),
              s = $("#suggest_user_to").getValue(),
              l = $("#checkgroup_letter_type").getValue();
            getFormById(dyn_uid1).isValid() &&
              ($("#n2_ajax_loading").fadeIn(),
              $.ajax({
                type: "PUT",
                url: "../automation/auto_ajax?REQ_TYPE=add_permission_format",
                data: {
                  user_type_from: e,
                  dabirkhaneh_from: a,
                  department_from: t,
                  group_from: o,
                  user_from: d,
                  user_type_to: n,
                  dabirkhaneh_to: r,
                  department_to: i,
                  group_to: _,
                  user_to: s,
                  letter_type: l,
                },
              }).done(function (e) {
                e.error
                  ? showMessage(e.error, "danger")
                  : e.message
                    ? showMessage(e.message, "info")
                    : (showMessage(e.data.message, "success", 5e3, "موفقیت"),
                      Xcrud.reload()),
                  $("#n2_ajax_loading").fadeOut();
              }));
          }
          function delete_permission_format(e, a, t) {
            confirm("برای حذف این مجوز ارجاع نامه مطمئنید؟") &&
              ($("#n2_ajax_loading").fadeIn(),
              $.ajax({
                type: "PUT",
                url: "../automation/auto_ajax?REQ_TYPE=delete_permission_format",
                data: { id: e, user_from: a, user_to: t },
              }).done(function (e) {
                e.error
                  ? showMessage(e.error, "danger")
                  : e.message
                    ? showMessage(e.message, "info")
                    : (showMessage(e.data.message, "success", 5e3, "موفقیت"),
                      Xcrud.reload()),
                  $("#n2_ajax_loading").fadeOut();
              }));
          }
          function go_to_add_template() {
            go_to_form(dyn_uid1, "1318586155a3e1083a05aa5000503303", 2);
          }
          function edit_template(e) {
            "" == e || 0 == e
              ? showMessage("شناسه قالب به درستی مشخص نشده است", "danger")
              : ((e = $("#grid_template").getValue(e, 1)),
                go_to_form(
                  dyn_uid1,
                  "1318586155a3e1083a05aa5000503303",
                  2,
                  "EDIT",
                  "&id=" + e,
                ));
          }
          function delete_template(o) {
            var e, a;
            "" == o || 0 == o
              ? showMessage("شناسه حذف به درستی مشخص نشده است", "danger")
              : ((e = $("#grid_template").getValue(o, 1)),
                (a = $("#grid_template").getValue(o, 2)),
                confirm("برای حذف این قالب مطمئنید؟") &&
                  ($("#n2_ajax_loading").fadeIn(),
                  $.ajax({
                    type: "PUT",
                    url: "../automation/auto_ajax?REQ_TYPE=delete_template",
                    data: { id: e, name: a },
                  }).done(function (e) {
                    if (e.error) showMessage(e.error, "danger");
                    else if (e.message) showMessage(e.message, "info");
                    else {
                      showMessage(e.data.message, "success", 5e3, "موفقیت"),
                        $("#grid_template").deleteRow(o);
                      for (
                        var a = $("#grid_template").getNumberRows(), t = o;
                        t <= a;
                        t++
                      )
                        set_grid_link2(
                          "grid_template",
                          t,
                          "link_delete",
                          "trash",
                          "حذف",
                          "delete_template",
                        ),
                          set_grid_link2(
                            "grid_template",
                            t,
                            "link_edit",
                            "edit",
                            "ویرایش",
                            "edit_template",
                          );
                    }
                    $("#n2_ajax_loading").fadeOut();
                  })));
          }
          function back_to_template() {
            go_to_form(dyn_uid1, "2247575605a3e052c245f03086319554", 1);
          }
          function dropdown_print_type(e) {
            var t = "dropdown_print_type",
              o = [],
              d = ["flash_print_file_name"],
              n = [],
              r = ["js_print_file_name"],
              i = [],
              _ = ["word_print_file_name"];
            "all" == (e = null == e ? "all" : e) &&
              $("#" + t).setOnchange(function (e, a) {
                show_hide_field(e, t, "Flex", o, d),
                  show_hide_field(e, t, "JS", n, r),
                  show_hide_field(e, t, "Word", i, _);
              }),
              show_hide_field($("#" + t).getValue(), t, "Flex", o, d),
              show_hide_field($("#" + t).getValue(), t, "JS", n, r),
              show_hide_field($("#" + t).getValue(), t, "Word", i, _);
          }
          function add_template() {
            var e = $("#hidden_template").getValue(),
              a = $("#text_template_name").getValue(),
              t = $("#letter_type").getValue(),
              o = $("#dropdown_print_type").getValue(),
              d = $("#word_print_file_name").getValue();
            if (
              ("Flex" == o
                ? (d = $("#flash_print_file_name").getValue())
                : "JS" == o && (d = $("#js_print_file_name").getValue()),
              "" == a)
            )
              showMessage("نام قالب را مشخص کنید", "danger");
            else {
              for (
                var n = $("#grid_template_variables").getNumberRows(),
                  r = new Array(),
                  i = 1;
                i <= n;
                i++
              )
                "" ==
                $(
                  "#form\\[grid_template_variables\\]\\[" +
                    i +
                    "\\]\\[grid_dropdown_variable\\]",
                ).val()
                  ? $("#grid_template_variables").deleteRow(i)
                  : (r[r.length] = $(
                      "#form\\[grid_template_variables\\]\\[" +
                        i +
                        "\\]\\[grid_dropdown_variable\\]",
                    ).val());
              $("#n2_ajax_loading").fadeIn(),
                $.ajax({
                  type: "PUT",
                  url: "../automation/auto_ajax?REQ_TYPE=add_template",
                  data: {
                    id: e,
                    name: a,
                    letter_type: t,
                    print_type: o,
                    print_file_name: d,
                    is_default: $("#checkbox_template_default").getValue(),
                    is_default_export: $("#is_default_export").getValue(),
                    enable_editor: $("#enable_editor").getValue(),
                    template_variables: r,
                  },
                }).done(function (e) {
                  e.error
                    ? showMessage(e.error, "danger")
                    : e.message
                      ? showMessage(e.message, "info")
                      : (showMessage(e.data.message, "success", 5e3, "موفقیت"),
                        setTimeout(function () {
                          back_to_template();
                        }, 500)),
                    $("#n2_ajax_loading").fadeOut();
                });
            }
          }
          function add_custom_group() {
            var e = $("#text_custom_group_name").getValue();
            "" == e
              ? showMessage("نام گروه را مشخص کنید", "danger")
              : ($("#n2_ajax_loading").fadeIn(),
                $.ajax({
                  type: "PUT",
                  url: "../automation/auto_ajax?REQ_TYPE=add_custom_group",
                  data: { custom_group_name: e },
                }).done(function (e) {
                  e.error
                    ? showMessage(e.error, "danger")
                    : e.message
                      ? showMessage(e.message, "info")
                      : (showMessage(e.data.message, "success", 5e3, "موفقیت"),
                        Xcrud.reload(),
                        cencel_edit_custom_group()),
                    $("#n2_ajax_loading").fadeOut();
                }));
          }
          function check_edit_custom_group() {
            var e = $("#text_custom_group_name").getValue(),
              a = $("#hidden_custom_group_id").getValue();
            "" == e
              ? showMessage("نام گروه را مشخص کنید", "danger")
              : "" == a || 0 == a
                ? showMessage("شناسه ویرایش به درستی مشخص نشده است", "danger")
                : ($("#n2_ajax_loading").fadeIn(),
                  $.ajax({
                    type: "PUT",
                    url: "../automation/auto_ajax?REQ_TYPE=edit_custom_group",
                    data: { custom_group_name: e, id: a },
                  }).done(function (e) {
                    e.error
                      ? showMessage(e.error, "danger")
                      : e.message
                        ? showMessage(e.message, "info")
                        : (showMessage(e.data.message, "success", 5e3, "موفقیت"),
                          Xcrud.reload(),
                          cencel_edit_custom_group()),
                      $("#n2_ajax_loading").fadeOut();
                  }));
          }
          function edit_custom_group(e, a) {
            cencel_edit_custom_group(),
              $("#text_custom_group_name").setValue(a),
              $("#hidden_custom_group_id").setValue(e),
              $("#button_add_custom_group").hide(),
              $("#button_edit_custom_group").show(),
              $("#button_cancel_custom_group").show();
          }
          function cencel_edit_custom_group() {
            $("#text_custom_group_name").disableValidation(),
              $("#text_custom_group_name").setValue(""),
              $("#hidden_custom_group_id").setValue(0),
              $("#text_custom_group_name").enableValidation(),
              $("#button_add_custom_group").show(),
              $("#button_edit_custom_group").hide(),
              $("#button_cancel_custom_group").hide();
          }
          function delete_custom_group(e, a) {
            confirm("برای حذف این گروه مطمئنید؟") &&
              ($("#n2_ajax_loading").fadeIn(),
              $.ajax({
                type: "PUT",
                url: "../automation/auto_ajax?REQ_TYPE=delete_custom_group",
                data: { id: e, custom_group_name: a },
              }).done(function (e) {
                e.error
                  ? showMessage(e.error, "danger")
                  : e.message
                    ? showMessage(e.message, "info")
                    : (showMessage(e.data.message, "success", 5e3, "موفقیت"),
                      Xcrud.reload()),
                  $("#n2_ajax_loading").fadeOut();
              }));
          }
          function custom_group_user(e) {
            go_to_form(
              dyn_uid1,
              "9905196755a570ab00f56e9076349765",
              2,
              "EDIT",
              "&id=" + e,
            );
          }
          function add_custom_group_user() {
            var e = $("#dropdown_custom_group").getValue(),
              a = $("#suggest_user").getValue();
            "" == a
              ? showMessage("کاربر را مشخص کنید", "danger")
              : "" == e
                ? showMessage("گروه را مشخص کنید", "danger")
                : ($("#n2_ajax_loading").fadeIn(),
                  $.ajax({
                    type: "PUT",
                    url: "../automation/auto_ajax?REQ_TYPE=add_custom_group_user",
                    data: { user: a, custom_group: e },
                  }).done(function (e) {
                    var a, t;
                    e.error
                      ? showMessage(e.error, "danger")
                      : e.message
                        ? showMessage(e.message, "info")
                        : (showMessage(e.data.message, "success", 5e3, "موفقیت"),
                          1 ==
                            (t = (a = $(
                              "#grid_custom_group_user",
                            )).getNumberRows()) && "" == a.getValue(1, 1)
                            ? a.deleteRow(1)
                            : t++,
                          (e = [
                            { value: e.data.custom_group_id },
                            { value: e.data.USR_UID },
                          ]),
                          a.addRow(e),
                          set_grid_link2(
                            "grid_custom_group_user",
                            t,
                            "link_delete",
                            "trash",
                            "حذف",
                            "delete_custom_group_user",
                          ),
                          $("#suggest_user").disableValidation(),
                          $("#suggest_user").setValue(""),
                          $("#suggest_user").enableValidation()),
                      $("#n2_ajax_loading").fadeOut();
                  }));
          }
          function delete_custom_group_user(o) {
            var e, a;
            "" == o || 0 == o
              ? showMessage("شناسه حذف به درستی مشخص نشده است", "danger")
              : ((e = $("#grid_custom_group_user").getValue(o, 1)),
                (a = $("#grid_custom_group_user").getValue(o, 2)),
                confirm("برای حذف این کاربر مطمئنید؟") &&
                  ($("#n2_ajax_loading").fadeIn(),
                  $.ajax({
                    type: "PUT",
                    url: "../automation/auto_ajax?REQ_TYPE=delete_custom_group_user",
                    data: { user: a, custom_group: e },
                  }).done(function (e) {
                    if (e.error) showMessage(e.error, "danger");
                    else if (e.message) showMessage(e.message, "info");
                    else {
                      showMessage(e.data.message, "success", 5e3, "موفقیت"),
                        $("#grid_custom_group_user").deleteRow(o);
                      for (
                        var a = $("#grid_custom_group_user").getNumberRows(),
                          t = o;
                        t <= a;
                        t++
                      )
                        set_grid_link2(
                          "grid_custom_group_user",
                          t,
                          "link_delete",
                          "trash",
                          "حذف",
                          "delete_custom_group_user",
                        );
                    }
                    $("#n2_ajax_loading").fadeOut();
                  })));
          }
          function back_to_custom_group() {
            go_to_form(dyn_uid1, "5599109995a5702c1955551051231338", 1);
          }
          function delete_letters() {
            getFormById(dyn_uid1).isValid() &&
              confirm("برای حذف نامه ها مطمئنید؟") &&
              ($("#n2_ajax_loading").fadeIn(),
              $.ajax({
                type: "PUT",
                url: "../automation/auto_ajax?REQ_TYPE=delete_letters",
                data: { letters: $("#letters").getValue() },
              }).done(function (e) {
                e.error
                  ? showMessage(e.error, "danger")
                  : e.message
                    ? showMessage(e.message, "info")
                    : showMessage(e.success, "success", 5e3, "موفقیت"),
                  $("#n2_ajax_loading").fadeOut();
              }));
          }
          function download_attach_1(e) {
            var a;
            "" == e || 0 == e
              ? showMessage("شناسه دانلود فایل به درستی مشخص نشده است", "danger")
              : ((a = $(
                  "#form\\[grid_attachment\\]\\[" +
                    e +
                    "\\]\\[grid_hidden_attach\\]",
                ).val()),
                "" != e &&
                  0 != e &&
                  (window.location.href = "cases_ShowDocument?a=" + a));
          }
          function download_attach_2(e) {
            "" == e || 0 == e
              ? showMessage("شناسه دانلود فایل به درستی مشخص نشده است", "danger")
              : "" != (e = $("#grid_signer").getValue(e, 3)) &&
                0 != e &&
                (window.location.href = "cases_ShowDocument?a=" + e);
          }
          function check_form_1() {
            if (
              ($("#textarea_content").disableValidation(),
              !getFormById(dyn_uid1).isValid())
            )
              return !1;
            $("#textarea_content").enableValidation();
            var e = tinyMCE
              .get("form[textarea_content]")
              .getContent()
              .replace(/\n/g, "");
            return (
              (("" != e &&
                "<!DOCTYPE html><html><head></head><body></body></html>" != e) ||
                0 < $("#dropdown_template").getValue().indexOf("_Word_0")) &&
                $("#textarea_content").disableValidation(),
              !(
                0 < $("#dropdown_template").getValue().indexOf("_Word_0") &&
                0 == $("#hidden_word").getValue() &&
                (showMessage("فایل محتوای متن نامه را ایجاد کنید.", "danger"), 1)
              )
            );
          }
          function check_form_2() {
            $("#textarea_content").enableValidation();
            var e = tinyMCE
              .get("form[textarea_content]")
              .getContent()
              .replace(/\n/g, "");
            return (
              (("" != e &&
                "<!DOCTYPE html><html><head></head><body></body></html>" != e) ||
                0 < $("#dropdown_template").getValue().indexOf("_Word_0")) &&
                $("#textarea_content").disableValidation(),
              !(
                0 < $("#dropdown_template").getValue().indexOf("_Word_0") &&
                0 == $("#hidden_word").getValue() &&
                (showMessage("فایل محتوای متن نامه را ایجاد کنید.", "danger"), 1)
              )
            );
          }
          function check_form_3() {
            return !!(
              getFormById(dyn_uid1).isValid() &&
              check_grid_receiver_draft() &&
              check_grid_receiver(
                0 < parseInt($("#hidden_letter_id").getValue()) ? 2 : 1,
              ) &&
              check_grid_attachment()
            );
          }
          function check_form_4() {
            3 != $("#hidden_receive_type").getValue()
              ? (set_finish_flag(1),
                $("#" + dyn_uid1).saveForm(),
                $("#" + dyn_uid1).submitForm())
              : ($("#n2_ajax_loading").fadeIn(),
                $.ajax({
                  type: "PUT",
                  url: "../automation/auto_ajax?REQ_TYPE=show_actions",
                  data: { letter_id: $("#hidden_letter_id").getValue() },
                }).done(function (e) {
                  if (e.error) showMessage(e.error, "danger");
                  else if (e.message) showMessage(e.message, "info");
                  else if (0 == Object.keys(e.data).length)
                    showMessage("حداقل یک اقدام ثبت کنید", "danger");
                  else {
                    for (var a = 0, t = 1; t <= Object.keys(e.data).length; t++)
                      if (e.data[t].to_APP_UID == app_uid) {
                        a = 1;
                        break;
                      }
                    0 == a
                      ? showMessage("حداقل یک اقدام ثبت کنید", "danger")
                      : (set_finish_flag(1),
                        $("#" + dyn_uid1).saveForm(),
                        $("#" + dyn_uid1).submitForm());
                  }
                  $("#n2_ajax_loading").fadeOut();
                }));
          }
          function check_form_5() {
            if (!getFormById(dyn_uid1).isValid()) return !1;
            if (!check_grid_receiver(2)) return !1;
            $("#n2_ajax_loading").fadeIn();
            for (
              var e, a = $("#grid_attachment").getNumberRows(), t = 1;
              t <= a;
              t++
            )
              "" ==
                $(
                  "#form\\[grid_attachment\\]\\[" +
                    t +
                    "\\]\\[grid_hidden_attach\\]",
                ).val() &&
                null !=
                  document.getElementsByName(
                    "form[grid_attachment][" +
                      t +
                      "][grid_file_attach][0][appDocUid]",
                  ) &&
                ((e = document.getElementsByName(
                  "form[grid_attachment][" +
                    t +
                    "][grid_file_attach][0][appDocUid]",
                )[0].value),
                $("#grid_attachment").setValue(e, t, 4));
            if (!check_grid_attachment()) return !1;
            $.ajax({
              type: "PUT",
              url: "../automation/auto_ajax?REQ_TYPE=send_letter",
              data: {
                letter_id: $("#hidden_letter_id").getValue(),
                grid_receiver: $("#grid_receiver").getValue(),
                grid_attachment: $("#grid_attachment").getValue(),
              },
            }).done(function (e) {
              e.error
                ? showMessage(e.error, "danger")
                : e.message
                  ? showMessage(e.message, "info")
                  : window.parent.closeModal("sendModal"),
                $("#n2_ajax_loading").fadeOut();
            });
          }
          function closeModal(e) {
            $("#" + e).modal("hide");
          }
          function check_permission_organization() {
            1 != $("#add_permission").getValue() &&
              1 != $("#edit_permission").getValue() &&
              ($("#subtitle0000000001").hide(),
              $("#text_organization_name").hide(),
              $("#dropdown_template").hide(),
              $("#phone").hide(),
              $("#import").hide(),
              $("#export").hide(),
              $("#shenaseh_melli").hide(),
              $("#code_eghtesadi").hide(),
              $("#code_posti").hide(),
              $("#address").hide(),
              $("#grid_persons").hide()),
              1 == $("#add_permission").getValue()
                ? $("#button_add_organization").click(function () {
                    add_organization();
                  })
                : $("#button_add_organization").hide(),
              1 == $("#edit_permission").getValue() &&
                $("#button_edit_organization").click(function () {
                  check_edit_organization();
                }),
              (1 != $("#add_permission").getValue() &&
                1 != $("#edit_permission").getValue()) ||
                $("#button_cancel_organization").click(function () {
                  cencel_edit_organization();
                });
          }
          function auto_do_ready1() {
            hideArrow(),
              appendAjaxLoading(),
              $("#hidden_user_logged").hide(),
              dropdown_letter_title(),
              getFormById(dyn_uid1).setOnSubmit(function () {
                return check_form_1();
              }),
              $("#button_word").click(open_word_file),
              $("#dropdown_template").setOnchange(function (e, a) {
                check_template_type(e);
              }),
              check_template_type($("#dropdown_template").getValue()),
              "" != $("#hidden_js").getValue() &&
                eval($("#hidden_js").getValue()),
              $("#draft_comment").hide(),
              $("#draft_due_date").hide(),
              "" != $("#draft_comment").getValue() &&
                ($("#draft_comment").show(), $("#draft_due_date").show());
          }
          function auto_do_ready2() {
            hideArrow(),
              appendAjaxLoading(),
              $("#hidden_user_logged").hide(),
              getFormById(dyn_uid1).setOnSubmit(check_form_2),
              $("#button_word").click(open_word_file),
              $("#button_preview").click(get_preview2),
              $("#suggest_export_organization").setOnchange(function (e, a) {
                set_organization_template(e);
              }),
              $("#dropdown_template").setOnchange(function (e, a) {
                check_template_type(e);
              }),
              check_template_type($("#dropdown_template").getValue());
            for (
              var rows = $("#grid_organization_ronevesht").getNumberRows(), i = 1;
              i <= rows;
              i++
            )
              "" == $("#grid_organization_ronevesht").getValue(i, 1) &&
                $("#grid_organization_ronevesht").deleteRow();
            "" != $("#hidden_js").getValue() && eval($("#hidden_js").getValue()),
              $("#draft_comment").hide(),
              $("#draft_due_date").hide(),
              "" != $("#draft_comment").getValue() &&
                ($("#draft_comment").show(), $("#draft_due_date").show());
          }
          function auto_do_ready3() {
            appendAjaxLoading(),
              $("#dyn_backward").click(function () {
                $("#" + dyn_uid1).saveForm();
              }),
              $("#grid_attachment").hideColumn(4),
              $("#hidden_user_logged").hide(),
              $("#hidden_letter_type").hide(),
              $("#dropdown_dabirkhaneh").hide(),
              "" != $("#hidden_js").getValue() &&
                eval($("#hidden_js").getValue()),
              getFormById(dyn_uid1).setOnSubmit(function () {
                return check_form_3();
              }),
              $("#button_group").click(function () {
                call_user_from_group();
              }),
              $("#grid_receiver").onAddRow(function (e, a, t) {
                $("#grid_receiver").setValue(
                  $("#hidden_user_logged").getValue(),
                  t,
                  7,
                ),
                  $("#grid_receiver").setValue(
                    $("#hidden_letter_type").getValue(),
                    t,
                    8,
                  );
              }),
              $("#grid_receiver").onDeleteRow(function (e, a, t) {}),
              $("#grid_receiver_draft").onAddRow(function (e, a, t) {
                $("#grid_receiver_draft").setValue(
                  $("#hidden_user_logged").getValue(),
                  t,
                  4,
                ),
                  $("#grid_receiver_draft").setValue(
                    $("#hidden_letter_type").getValue(),
                    t,
                    5,
                  );
              });
            for (
              var rows = $("#grid_receiver").getNumberRows(), i = 1;
              i <= rows;
              i++
            )
              "" == $("#grid_receiver").getValue(i, 7) &&
                $("#grid_receiver").setValue(
                  $("#hidden_user_logged").getValue(),
                  i,
                  7,
                ),
                "" == $("#grid_receiver").getValue(i, 8) &&
                  $("#grid_receiver").setValue(
                    $("#hidden_letter_type").getValue(),
                    i,
                    8,
                  );
            for (
              var rows = $("#grid_receiver_draft").getNumberRows(), i = 1;
              i <= rows;
              i++
            )
              "" == $("#grid_receiver_draft").getValue(i, 4) &&
                $("#grid_receiver_draft").setValue(
                  $("#hidden_user_logged").getValue(),
                  i,
                  4,
                ),
                "" == $("#grid_receiver_draft").getValue(i, 5) &&
                  $("#grid_receiver_draft").setValue(
                    $("#hidden_letter_type").getValue(),
                    i,
                    5,
                  );
            rows = $("#grid_attachment").getNumberRows();
            for (var i = 1; i <= rows; i++)
              set_grid_link2(
                "grid_attachment",
                i,
                "link_download",
                "download",
                "دانلود",
                "download_attach_1",
              );
            $("#grid_receiver").hideColumn(7),
              $("#grid_receiver").hideColumn(8),
              "" != $("#hidden_js").getValue() &&
                eval($("#hidden_js").getValue());
          }
          function auto_do_ready4() {
            hideArrow(),
              appendAjaxLoading(),
              $("#hidden_user_logged").hide(),
              $("#hidden_letter_type").hide(),
              $("#dropdown_dabirkhaneh").hide(),
              $("#grid_receiver").onAddRow(function (e, a, t) {
                $("#grid_receiver").setValue(
                  $("#hidden_user_logged").getValue(),
                  t,
                  7,
                ),
                  $("#grid_receiver").setValue(
                    $("#hidden_letter_type").getValue(),
                    t,
                    8,
                  );
              });
            for (var e = $("#grid_receiver").getNumberRows(), a = 1; a <= e; a++)
              $("#grid_receiver").deleteRow();
            for (e = $("#grid_attachment").getNumberRows(), a = 1; a <= e; a++)
              $("#grid_attachment").deleteRow();
            $("#grid_receiver").hideColumn(7), $("#grid_receiver").hideColumn(8);
            var t = GetURLParameter("id");
            $("#n2_ajax_loading").fadeIn(),
              $.ajax({
                type: "PUT",
                url: "../automation/auto_ajax?REQ_TYPE=get_send_form_data",
                data: { letter_id: t },
              }).done(function (e) {
                e.error
                  ? showMessage(e.error, "danger")
                  : e.message
                    ? showMessage(e.message, "info")
                    : ($("#hidden_user_logged").setValue(e.user_logged),
                      $("#hidden_letter_type").setValue(e.letter_type),
                      $("#dropdown_dabirkhaneh").setValue(e.dabirkhaneh),
                      $("#hidden_letter_id").setValue(e.id),
                      $("#button_send").click(check_form_5)),
                  $("#n2_ajax_loading").fadeOut();
              });
          }
          function auto_do_ready5() {
            $("#1241703106564d7124efc12017356711").hide(),
              $("#button_preview").hide(),
              0 != $("#hidden_word").getValue() &&
                ($("#button_preview").show(),
                $("#button_preview").click(get_preview3));
          }
          function auto_do_ready6() {
            hideArrow(),
              appendAjaxLoading(),
              "" != $("#hidden_js").getValue() &&
                eval($("#hidden_js").getValue()),
              $("#form\\[submit_end\\]").addClass("btn-danger"),
              $("#form\\[submit_end_note\\]").addClass("btn-danger"),
              $("#submit_add").click(function () {
                set_finish_flag(0);
              }),
              $("#submit_end").click(function () {
                set_finish_flag(1), check_form_4();
              }),
              $("#submit_end_note").click(function () {
                set_finish_flag(2);
              }),
              $("#label_sender").attr(
                "title",
                "دبیرخانه/فرستنده/نوع گیرنده/نوع ارجاع",
              );
            var baseIconPath = "/plugin/automation/icons/";
            set_link(
              "link_receivers",
              baseIconPath + "refer.png",
              "ارجاعات",
              "show_receivers",
              $("#hidden_letter_id").getValue(),
            ),
              set_link(
                "link_receivers",
                baseIconPath + "attachment.png",
                "پیوست ها",
                "show_attachment",
                $("#hidden_letter_id").getValue(),
                1,
              ),
              "show_print" == $("#hidden_printPath").getValue()
                ? (set_link(
                    "link_receivers",
                    baseIconPath + "printer.png",
                    "چاپ",
                    "show_print",
                    $("#hidden_letter_id").getValue(),
                    1,
                  ),
                  set_link(
                    "link_receivers",
                    baseIconPath + "word.png",
                    "ورد",
                    "show_print_word",
                    $("#hidden_letter_id").getValue(),
                    1,
                  ))
                : (document.getElementById("link_receivers").innerHTML +=
                    ' <a href="' +
                    $("#hidden_printPath").getValue() +
                    '" target="_blank"><img src="/plugin/automation/icons/printer.png" title="چاپ" alt="چاپ" style="cursor:pointer;" /></a>'),
              set_link(
                "link_receivers",
                baseIconPath + "actions.png",
                "اقدامات",
                "show_actions",
                $("#hidden_letter_id").getValue(),
                1,
              ),
              show_receivers($("#hidden_letter_id").getValue(), 1),
              $("#submit_end_note").hide();
          }
          function auto_do_ready7() {
            hideArrow(),
              appendAjaxLoading(),
              "" != $("#hidden_js").getValue() &&
                eval($("#hidden_js").getValue()),
              $("#form\\[submit_end\\]").addClass("btn-danger"),
              $("#form\\[submit_end_note\\]").addClass("btn-danger"),
              $("#submit_add").click(function () {
                set_finish_flag(0);
              }),
              $("#submit_end").click(function () {
                set_finish_flag(1), check_form_4();
              }),
              $("#submit_end_note").click(function () {
                set_finish_flag(2);
              }),
              $("#label_sender").attr(
                "title",
                "دبیرخانه/فرستنده/نوع ارجاع/توضیحات ارجاع",
              );
            var baseIconPath = "/plugin/automation/icons/";
            set_link(
              "link_receivers",
              baseIconPath + "refer.png",
              "ارجاعات",
              "show_receivers",
              $("#hidden_letter_id").getValue(),
            ),
              set_link(
                "link_receivers",
                baseIconPath + "attachment.png",
                "پیوست ها",
                "show_attachment",
                $("#hidden_letter_id").getValue(),
                1,
              ),
              set_link(
                "link_receivers",
                baseIconPath + "actions.png",
                "اقدامات",
                "show_actions",
                $("#hidden_letter_id").getValue(),
                1,
              ),
              show_receivers($("#hidden_letter_id").getValue(), 1),
              $("#submit_end_note").hide();
          }
          function get_preview3() {
            if (check_form_3()) {
              for (
                var e,
                  a = new Object(),
                  t =
                    ((a.letter_date = $("#letter_date").getText()),
                    (a.letter_number = "پیش نویس"),
                    (a.subject = $("#text_subject").getValue()),
                    (a.peyvast =
                      0 < $("#grid_attachment").getNumberRows()
                        ? "دارد"
                        : "ندارد"),
                    []),
                  o = 1;
                o <= $("#grid_receiver").getNumberRows();
                o++
              )
                "main" == $("#grid_receiver").getValue(o, 3)
                  ? ((e = { girandeh: $("#grid_receiver").getText(o, 1) }),
                    t.push(e))
                  : "bc" == $("#grid_receiver").getValue(o, 3) &&
                    ((e = {
                      ronevesht: $("#grid_receiver").getText(o, 1),
                      comment: "",
                    }),
                    t.push(e));
              (a.girandegan = t),
                (a.roneveshts = []),
                tm_auto_generate(
                  host +
                    "/uploads/" +
                    $("#hidden_word").getValue() +
                    "?t=" +
                    Date.now(),
                  "",
                  a,
                );
            }
          }
          function get_preview2() {
            if (check_form_2()) {
              for (
                var e = new Object(),
                  a =
                    ((e.letter_date = $("#letter_date").getText()),
                    (e.letter_number = "پیش نویس"),
                    (e.organization_name = $(
                      "#suggest_export_organization",
                    ).getText()),
                    (e.organization_person =
                      "" != $("#organization_person").getValue()
                        ? $("#organization_person").getText()
                        : ""),
                    (e.organization_post = $("#organization_post").getValue()),
                    (e.subject = $("#text_subject").getValue()),
                    []),
                  t = 1;
                t <= $("#grid_organization_ronevesht").getNumberRows();
                t++
              ) {
                var o = {
                  ronevesht:
                    $("#grid_organization_ronevesht").getText(t, 1) +
                    " ، " +
                    $("#grid_organization_ronevesht").getText(t, 2) +
                    " ، " +
                    $("#grid_organization_ronevesht").getText(t, 3),
                };
                a.push(o);
              }
              (e.organization_roneveshts = a),
                tm_auto_generate(
                  host +
                    "/uploads/" +
                    $("#hidden_word").getValue() +
                    "?t=" +
                    Date.now(),
                  "",
                  e,
                );
            }
          }
          __webpack_require__.d(__webpack_exports__, {
            $M: () => download_attach_1,
            $m: () => download_attach,
            A$: () => open_word_file,
            A9: () => back_to_custom_group,
            AT: () => check_permission_organization,
            Af: () => set_finish_flag,
            Au: () => delete_signer,
            Bc: () => set_grid_link,
            Bq: () => show_actions,
            Bv: () => send_letter,
            CX: () => call_user_from_group,
            Cd: () => dropdown_variable_type,
            DI: () => add_custom_group,
            DT: () => add_signer,
            De: () => inner_show_print,
            Dp: () => get_preview2,
            E4: () => delete_variable,
            EA: () => add_custom_group_user,
            EU: () => dropdown_user_type_from,
            Ee: () => delete_organization,
            Eo: () => check_template_type,
            F$: () => hideArrow,
            FL: () => search_letter,
            H8: () => custom_group_user,
            Hw: () => add_semat,
            I2: () => check_form_5,
            IO: () => edit_dabirkhaneh_user,
            Iy: () => delete_dabirkhaneh_user,
            JW: () => go_to_add_template,
            Jj: () => delete_template,
            K: () => check_edit_organization,
            Kb: () => set_organization_template,
            Km: () => delete_custom_group_user,
            LZ: () => cencel_edit_variable,
            MB: () => check_edit_variable,
            Mr: () => closeModal,
            NJ: () => auto_do_ready3,
            Nx: () => cencel_edit_dabirkhaneh,
            O8: () => delete_custom_group,
            OL: () => setOrder,
            OY: () => add_dabirkhaneh_user,
            R5: () => delete_dabirkhaneh,
            R9: () => cencel_edit_custom_group,
            RT: () => dropdown_letter_title,
            Rj: () => update_dabirkhaneh_user,
            S0: () => back_to_template,
            S8: () => download_attach_2,
            TW: () => show_receivers_archive,
            UI: () => set_link,
            VT: () => show_attachment,
            VU: () => show_print_word,
            Vv: () => auto_do_ready5,
            Vx: () => add_variable,
            WC: () => check_edit_custom_group,
            Wb: () => dropdown_user_type_to,
            XO: () => add_permission_format,
            XZ: () => edit_custom_group,
            YW: () => delete_permission_format,
            Yf: () => edit_variable,
            Yu: () => auto_do_ready1,
            ZY: () => auto_do_ready6,
            _: () => HideAdvanced,
            _G: () => check_form_3,
            _f: () => check_form_1,
            _h: () => set_grid_link2,
            aX: () => check_form_2,
            bW: () => back_to_dabirkhaneh,
            be: () => show_receivers,
            cH: () => dropdown_letter_type,
            dL: () => delete_search,
            dy: () => set_user_from_group,
            ft: () => delete_letters,
            g_: () => auto_do_ready7,
            hW: () => add_organization,
            iW: () => edit_dabirkhaneh,
            if: () => cencel_edit_semat,
            iz: () => delete_action,
            jA: () => edit_organization,
            kW: () => cencel_edit_organization,
            ko: () => dropdown_print_type,
            ku: () => appendAjaxLoading,
            lJ: () => add_dabirkhaneh,
            m0: () => edit_semat,
            m3: () => delete_semat,
            m_: () => auto_do_ready2,
            oi: () => update_signer,
            ot: () => show_print_word_archive,
            pY: () => add_template,
            qc: () => auto_do_ready4,
            r1: () => show_print_archive,
            rG: () => check_edit_dabirkhaneh,
            w3: () => get_preview3,
            wN: () => edit_template,
            wP: () => dabirkhaneh_user,
            wv: () => add_action,
            x1: () => setOrderLabels,
            x9: () => check_form_4,
            z3: () => check_edit_semat,
            zQ: () => show_print,
            zg: () => ShowAdvanced,
          });
        },
      },
      __webpack_module_cache__ = {};
    function __webpack_require__(e) {
      var a = __webpack_module_cache__[e];
      return (
        void 0 !== a ||
          ((a = __webpack_module_cache__[e] = { exports: {} }),
          __webpack_modules__[e](a, a.exports, __webpack_require__)),
        a.exports
      );
    }
    (__webpack_require__.d = (e, a) => {
      for (var t in a)
        __webpack_require__.o(a, t) &&
          !__webpack_require__.o(e, t) &&
          Object.defineProperty(e, t, { enumerable: !0, get: a[t] });
    }),
      (__webpack_require__.o = (e, a) =>
        Object.prototype.hasOwnProperty.call(e, a));
    var __webpack_exports__ = {};
    (() => {
      var e = __webpack_require__(173);
      (window.hideArrow = e.F$),
        (window.appendAjaxLoading = e.ku),
        (window.set_link = e.UI),
        (window.set_grid_link = e.Bc),
        (window.set_grid_link2 = e._h),
        (window.dropdown_letter_title = e.RT),
        (window.check_template_type = e.Eo),
        (window.open_word_file = e.A$),
        (window.set_organization_template = e.Kb),
        (window.call_user_from_group = e.CX),
        (window.set_finish_flag = e.Af),
        (window.show_receivers = e.be),
        (window.show_attachment = e.VT),
        (window.show_print = e.zQ),
        (window.show_print_word = e.VU),
        (window.show_actions = e.Bq),
        (window.dropdown_letter_type = e.cH),
        (window.search_letter = e.FL),
        (window.delete_search = e.dL),
        (window.HideAdvanced = e._),
        (window.ShowAdvanced = e.zg),
        (window.setOrderLabels = e.x1),
        (window.add_signer = e.DT),
        (window.update_signer = e.oi),
        (window.delete_signer = e.Au),
        (window.add_dabirkhaneh = e.lJ),
        (window.add_semat = e.Hw),
        (window.check_edit_semat = e.z3),
        (window.cencel_edit_semat = e.if),
        (window.delete_semat = e.m3),
        (window.edit_semat = e.m0),
        (window.check_edit_dabirkhaneh = e.rG),
        (window.cencel_edit_dabirkhaneh = e.Nx),
        (window.add_dabirkhaneh_user = e.OY),
        (window.delete_dabirkhaneh_user = e.Iy),
        (window.edit_dabirkhaneh_user = e.IO),
        (window.update_dabirkhaneh_user = e.Rj),
        (window.back_to_dabirkhaneh = e.bW),
        (window.dropdown_variable_type = e.Cd),
        (window.add_variable = e.Vx),
        (window.check_edit_variable = e.MB),
        (window.cencel_edit_variable = e.LZ),
        (window.dropdown_user_type_from = e.EU),
        (window.dropdown_user_type_to = e.Wb),
        (window.add_permission_format = e.XO),
        (window.delete_permission_format = e.YW),
        (window.go_to_add_template = e.JW),
        (window.edit_template = e.wN),
        (window.delete_template = e.Jj),
        (window.back_to_template = e.S0),
        (window.dropdown_print_type = e.ko),
        (window.add_template = e.pY),
        (window.add_custom_group = e.DI),
        (window.check_edit_custom_group = e.WC),
        (window.cencel_edit_custom_group = e.R9),
        (window.add_custom_group_user = e.EA),
        (window.delete_custom_group_user = e.Km),
        (window.back_to_custom_group = e.A9),
        (window.delete_letters = e.ft),
        (window.download_attach_1 = e.$M),
        (window.download_attach_2 = e.S8),
        (window.check_form_1 = e._f),
        (window.check_form_2 = e.aX),
        (window.check_form_3 = e._G),
        (window.check_form_4 = e.x9),
        (window.check_form_5 = e.I2),
        (window.check_permission_organization = e.AT),
        (window.edit_organization = e.jA),
        (window.delete_organization = e.Ee),
        (window.delete_dabirkhaneh = e.R5),
        (window.edit_dabirkhaneh = e.iW),
        (window.dabirkhaneh_user = e.wP),
        (window.edit_variable = e.Yf),
        (window.delete_variable = e.E4),
        (window.edit_custom_group = e.XZ),
        (window.delete_custom_group = e.O8),
        (window.custom_group_user = e.H8),
        (window.set_user_from_group = e.dy),
        (window.add_action = e.wv),
        (window.delete_action = e.iz),
        (window.show_receivers_archive = e.TW),
        (window.show_print_archive = e.r1),
        (window.show_print_word_archive = e.ot),
        (window.setOrder = e.OL),
        (window.add_organization = e.hW),
        (window.check_edit_organization = e.K),
        (window.cencel_edit_organization = e.kW),
        (window.download_attach = e.$m),
        (window.inner_show_print = e.De),
        (window.auto_do_ready1 = e.Yu),
        (window.auto_do_ready2 = e.m_),
        (window.auto_do_ready3 = e.NJ),
        (window.auto_do_ready4 = e.qc),
        (window.auto_do_ready5 = e.Vv),
        (window.auto_do_ready6 = e.ZY),
        (window.auto_do_ready7 = e.g_),
        (window.get_preview2 = e.Dp),
        (window.get_preview3 = e.w3),
        (window.send_letter = e.Bv),
        (window.closeModal = e.Mr);
    })();
  })();
  