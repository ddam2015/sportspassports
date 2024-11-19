function g365_form_start_up(e) {
  $("select[data-g365_select]", e).each(function() {
    $('option[value="' + $(this).attr("data-g365_select") + '"]', $(this)).prop("selected", !0)
  }), $(".crop_img", e).each(function() {
    g365_start_croppie($(this))
  }), $(".change-title", e).each(function() {
    var t = $(this),
      n = t.attr("data-g365_change_targets");
    "" !== n && null !== n && "undefined" !== n && (n = $(n.split("|").join(), e), "" !== n && null !== n && "undefined" !== n && n.on("keyup", function() {
      var e = "";
      n.each(function() {
        e += this.value + " "
      }), t.html("" === e.trim() ? t.attr("data-default_value") : e)
    }).trigger("keyup"))
  }), g365_livesearch_init(e);
  var t = e.children(".primary-form");
  t.length && t.submit(g365_handle_form_submit)
}

function g365_form_section_expand_collapse() {
  var e = $(this).attr("data-click-target"),
    t = $("#" + e + "_fieldset"),
    n = $("#" + e + "_fieldset_title");
  $("span", n).html($(".change-title", t).html()), t.toggleClass("hide"), n.toggleClass("hide")
}

function g365_form_section_closer() {
  var e = $(this).parent().parent().parent();
  $(this).parent().parent().slideUp("normal", function() {
    $(this).remove()
  }), 0 === e.children().length && e.next(".g365_form_sub_block").hide(), e.prev().is("form") && e.prev(".search:hidden").slideDown()
}

function g365_form_message_clear(e) {
  function t(n) {
    e = n.data.wrapper_form, $("#" + e.attr("id") + "_message").html("").addClass("hidden_element"), e.off("focus", ":input", t)
  }
  e.on("focus", ":input", {
    wrapper_form: e
  }, t)
}

function g365_start_croppie(e) {
  function t(e) {
    if (e.files && e.files[0]) {
      var t = new FileReader;
      t.onload = function(e) {
        n.parent().addClass("crop-size").removeClass("hidden_element"), n.croppie("bind", {
          url: e.target.result
        }).then(function() {})
      }, t.readAsDataURL(e.files[0])
    } else alert("Sorry - you're browser doesn't support the FileReader API."), e.parentNode.parentNode.innerHTML = "<p>Sorry - you're browser doesn't support the FileReader API</p>"
  }
  var n;
  n = $(".crop_upload_canvas", e).croppie({
    viewport: {
      width: 400,
      height: 800,
      type: "square"
    },
    enableExif: !0,
    mouseWheelZoom: !1
  }), n.on("update.croppie", function(t, i) {
    n.croppie("result", {
      type: "base64",
      format: "jpeg",
      size: {
        width: 400,
        height: 600
      },
      quality: .8
    }).then(function(t) {
      $(".profile_img_data", e).attr("name", $(".profile_img_data", e).attr("data-g365_name")).val(t), $(".profile_img", e).removeAttr("name"), $(".remove-profile", e).removeClass("hidden_element")
    })
  }), $(".crop_uploader", e).on("change", function() {
    t(this)
  }), $(".remove-profile", e).on("click", function() {
    $(".crop_upload", e).removeClass("hidden_element"), $(".crop_uploader", e).val(""), $(".crop_upload_canvas_wrap", e).removeClass("crop-size"), $(".profile_img_data", e).removeAttr("name"), $(".profile_img", e).attr("name", $(".profile_img", e).attr("data-g365_name")).val(""), $(".remove-profile, .cropped_img, .crop_upload_canvas_wrap", e).addClass("hidden_element")
  }), "" !== e.attr("data-g365_profile_img_url") && ($(".crop_upload", e).addClass("hidden_element"), $(".remove-profile", e).removeClass("hidden_element"))
}

function g365_serv_con(e, t) {
  var n = {
    g365_session: g365_sess_data.id,
    g365_token: g365_sess_data.token,
    g365_time: g365_sess_data.time,
    g365_data_set: e,
    g365_settings: 2 === g365_serv_con.arguments.length ? t : null
  };
  return $.ajax({
    type: "post",
    url: g365_script_ajax,
    cache: !1,
    headers: {
      "X-Requested-With": "XMLHttpRequest"
    },
    data: n,
    dataType: "json"
  })
}

function g365_form_validation_check(e) {
  return console.log("Validate Me :)"), !1
}

function g365_build_template_from_data(e) {
  var t = $.Deferred(),
    n = e.template_format ? g365_form_data.form[e.query_type][e.template_format] : g365_form_data.form[e.query_type].form_template;
  if ("string" != typeof n || 0 === n.length) return "<p>Form Template malformation. Please try your request again.</p>";
  if ("string" != typeof e.field_group || "" === e.field_group) return "<p>Need unique identifier for field set.</p>";
  n = n.replace(new RegExp("{{field-set-id}}", "g"), e.field_group), "undefined" != typeof e.field_origin_id && (n = n.replace(new RegExp("{{field-set-id-origin}}", "g"), e.field_origin_id));
  var i = n.match(/{{(.+?)}}/g);
  if (i = i.filter(function(e, t, n) {
      return n.indexOf(e) === t
    }), null === e.id) t.resolve(n.replace(/{{(.+?)}}/g, ""));
  else {
    var o = {};
    o[e.query_type] = {
      proc_type: "get_data",
      ids: e.id,
      admin_key: "undefined" != typeof g365_sess_data.admin_key ? g365_sess_data.admin_key : null
    }, g365_serv_con(o).done(function(t) {
      if (console.log(t), "success" === t.status) {
        var i = "";
        $.each(t.message[e.query_type], function(t, o) {
          var r = n;
          "undefined" == typeof g365_form_data[e.query_type] && (g365_form_data[e.query_type] = {}), "undefined" == typeof g365_form_data[e.query_type][t] && (g365_form_data[e.query_type][t] = {}), $.each(o, function(n, i) {
            r = r.replace(new RegExp("{{" + n + "}}", "ig"), i), g365_form_data[e.query_type][t][n] = i
          }), g365_form_data[e.query_type][t].form_template = r, i += r
        }), n = i
      } else console.log("false"), n = t.message
    }).fail(function(e) {
      console.log("error", e), n = e.responseText
    }).always(function(e) {
      t.resolve(n.replace(/{{(.+?)}}/g, ""))
    })
  }
  return t.promise()
}

function g365_handle_form_submit(e) {
  var t = $(this),
    n = "",
    i = {};
  return i[t.attr("data-g365_type")] = {
    proc_type: "proc_data",
    form_data: t.serialize(),
    admin_key: "undefined" != typeof g365_sess_data.admin_key ? g365_sess_data.admin_key : null
  }, g365_serv_con(i).done(function(e) {
    "object" == typeof e.message ? ("success" === e.status, $.each(e.message, function(e, i) {
      var o = g365_form_data.form[e].form_template_result;
      if ("string" != typeof o || 0 === o.length) return void(n += "<p>Form Result Template malformation for " + e + ". Data write successful.</p>");
      if ("object" != typeof i || $.isEmptyObject(i)) return void(n += "<p>No results returned " + e + ".</p>");
      n += "<div><ul>", $.each(i, function(t, i) {
        var r = /success/i.test(i.message) ? "success" : "error";
        isNaN(parseInt(t)) ? n += o.replace(new RegExp("{{li_class}}", "g"), r).replace(new RegExp("{{result_title}}", "g"), $("#" + t + "_fieldset .change-title").html()).replace(new RegExp("{{result_status}}", "g"), i.message) : ("undefined" == typeof g365_form_data[e] && (g365_form_data[e] = {}), "undefined" == typeof g365_form_data[e][t] && (g365_form_data[e][t] = {}, $("#" + i.wrapper_id + "_id").val(t)), $.each(i, function(n, i) {
          g365_form_data[e][t][n] = i
        }), n += o.replace(new RegExp("{{li_class}}", "g"), r).replace(new RegExp("{{result_title}}", "g"), g365_form_data[e][t].element_title).replace(new RegExp("{{result_status}}", "g"), i.message))
      }), n += "</ul></p>";
      var r = $("#" + t.attr("data-target_field"));
      if (r.length) {
        var a = i[$.map(i, function(e, t) {
          return t
        })[0]];
        if ("undefined" != typeof a.id) return r.val(a.element_title).trigger("ajaxlivesearch:hide_result"), $("#" + r.attr("data-ls_target")).val(a.id), r.parent().slideDown(), void t.parent().fadeOut(400, "swing", function() {
          t.parent().remove()
        })
      } else g365_form_message_clear(t)
    })) : (console.log("false"), n = "<p>" + e.message + "</p>")
  }).fail(function(e) {
    console.log("error", e), n = "<p>" + e.responseText + "</p>"
  }).always(function(e) {
    var i = $("#" + t.attr("id") + "_message", t);
    i.length && i.html(n).removeClass("hidden_element")
  }), !1
}

function g365_form_start(e) {
  function t(e) {
    return "" === e || null === e || "undefined" === e ? (console.log("Need proper form type variable."), !1) : (e = e.split(","), void e.forEach(function(t) {
      return t == e[0] ? void(i[t] = {
        proc_type: "get_form",
        ids: []
      }) : void i[e[0]].ids.push(t)
    }))
  }

  function n(t) {
    return "undefined" != typeof t && (g365_form_data.error = t), "undefined" == typeof g365_form_data.wrapper && (g365_form_data.wrapper = $("<div id='g365_form_wrap' class='g365_form_wrap'></div>").insertBefore(e)), "" === g365_form_data.error ? $.each(i, function(e, t) {
      var n = $(g365_form_data.form[e].form_template_init).appendTo(g365_form_data.wrapper);
      g365_form_start_up(n)
    }) : g365_form_data.wrapper.html(g365_form_data.error), "undefined" == typeof t
  }
  if (g365_set_script(), g365_script_anchor === !1) return "Missing global connector.";
  var i = {};
  if ("undefined" == typeof g365_form_data.form && (g365_form_data.form = {}), g365_form_data.error = "", e = "undefined" != typeof e ? document.getElementById(e.wrapper_target) : g365_script_element, "undefined" == typeof e && n("Missing form wrapper target.") === !1) return !1;
  var o = e.dataset.g365_type;
  if ("undefined" == typeof e && n("Missing form datatypes.") === !1) return !1;
  if (o.split("|").forEach(t), 0 === Object.keys(i).length && n("Error parsing form types.") === !1) return !1;
  var r = {};
  "false" == g365_script_element.dataset.g365_styles && (r.styles = "false"), 0 === Object.keys(r).length && (r = null), g365_serv_con(i, r).done(function(e) {
    "success" === e.status ? $.each(e.message, function(e, t) {
      "undefined" == typeof g365_form_data.form[e] && (g365_form_data.form[e] = {}), $.each(t, function(t, n) {
        g365_form_data.form[e][t] = n
      })
    }) : (console.log("false", e), g365_form_data.error = e.message)
  }).fail(function(e) {
    console.log("error", e), g365_form_data.error = e.responseText
  }).always(function(e) {
    n(), "undefined" != typeof e.style && g365_form_data.wrapper.prepend(e.style)
  })
}(function() {
  function e(e) {
    return !!e.exifdata
  }

  function t(e, t) {
    t = t || e.match(/^data\:([^\;]+)\;base64,/im)[1] || "", e = e.replace(/^data\:([^\;]+)\;base64,/gim, "");
    for (var n = atob(e), i = n.length, o = new ArrayBuffer(i), r = new Uint8Array(o), a = 0; a < i; a++) r[a] = n.charCodeAt(a);
    return o
  }

  function n(e, t) {
    var n = new XMLHttpRequest;
    n.open("GET", e, !0), n.responseType = "blob", n.onload = function(e) {
      200 != this.status && 0 !== this.status || t(this.response)
    }, n.send()
  }

  function i(e, i) {
    function a(t) {
      var n = o(t);
      e.exifdata = n || {};
      var a = r(t);
      if (e.iptcdata = a || {}, y.isXmpEnabled) {
        var s = f(t);
        e.xmpdata = s || {}
      }
      i && i.call(e)
    }
    if (e.src)
      if (/^data\:/i.test(e.src)) {
        var s = t(e.src);
        a(s)
      } else if (/^blob\:/i.test(e.src)) {
      var l = new FileReader;
      l.onload = function(e) {
        a(e.target.result)
      }, n(e.src, function(e) {
        l.readAsArrayBuffer(e)
      })
    } else {
      var u = new XMLHttpRequest;
      u.onload = function() {
        if (200 != this.status && 0 !== this.status) throw "Could not load image";
        a(u.response), u = null
      }, u.open("GET", e.src, !0), u.responseType = "arraybuffer", u.send(null)
    } else if (self.FileReader && (e instanceof self.Blob || e instanceof self.File)) {
      var l = new FileReader;
      l.onload = function(e) {
        g && console.log("Got file of length " + e.target.result.byteLength), a(e.target.result)
      }, l.readAsArrayBuffer(e)
    }
  }

  function o(e) {
    var t = new DataView(e);
    if (g && console.log("Got file of length " + e.byteLength), 255 != t.getUint8(0) || 216 != t.getUint8(1)) return g && console.log("Not a valid JPEG"), !1;
    for (var n, i = 2, o = e.byteLength; i < o;) {
      if (255 != t.getUint8(i)) return g && console.log("Not a valid marker at offset " + i + ", found: " + t.getUint8(i)), !1;
      if (n = t.getUint8(i + 1), g && console.log(n), 225 == n) return g && console.log("Found 0xFFE1 marker"), p(t, i + 4, t.getUint16(i + 2) - 2);
      i += 2 + t.getUint16(i + 2)
    }
  }

  function r(e) {
    var t = new DataView(e);
    if (g && console.log("Got file of length " + e.byteLength), 255 != t.getUint8(0) || 216 != t.getUint8(1)) return g && console.log("Not a valid JPEG"), !1;
    for (var n = 2, i = e.byteLength, o = function(e, t) {
        return 56 === e.getUint8(t) && 66 === e.getUint8(t + 1) && 73 === e.getUint8(t + 2) && 77 === e.getUint8(t + 3) && 4 === e.getUint8(t + 4) && 4 === e.getUint8(t + 5)
      }; n < i;) {
      if (o(t, n)) {
        var r = t.getUint8(n + 7);
        r % 2 !== 0 && (r += 1), 0 === r && (r = 4);
        var s = n + 8 + r,
          l = t.getUint16(n + 6 + r);
        return a(e, s, l)
      }
      n++
    }
  }

  function a(e, t, n) {
    for (var i, o, r, a, s, l = new DataView(e), u = {}, c = t; c < t + n;) 28 === l.getUint8(c) && 2 === l.getUint8(c + 1) && (a = l.getUint8(c + 2), a in S && (r = l.getInt16(c + 3), s = r + 5, o = S[a], i = d(l, c + 5, r), u.hasOwnProperty(o) ? u[o] instanceof Array ? u[o].push(i) : u[o] = [u[o], i] : u[o] = i)), c++;
    return u
  }

  function s(e, t, n, i, o) {
    var r, a, s, u = e.getUint16(n, !o),
      c = {};
    for (s = 0; s < u; s++) r = n + 12 * s + 2, a = i[e.getUint16(r, !o)], !a && g && console.log("Unknown tag: " + e.getUint16(r, !o)), c[a] = l(e, r, t, n, o);
    return c
  }

  function l(e, t, n, i, o) {
    var r, a, s, l, u, c, p = e.getUint16(t + 2, !o),
      f = e.getUint32(t + 4, !o),
      m = e.getUint32(t + 8, !o) + n;
    switch (p) {
      case 1:
      case 7:
        if (1 == f) return e.getUint8(t + 8, !o);
        for (r = f > 4 ? m : t + 8, a = [], l = 0; l < f; l++) a[l] = e.getUint8(r + l);
        return a;
      case 2:
        return r = f > 4 ? m : t + 8, d(e, r, f - 1);
      case 3:
        if (1 == f) return e.getUint16(t + 8, !o);
        for (r = f > 2 ? m : t + 8, a = [], l = 0; l < f; l++) a[l] = e.getUint16(r + 2 * l, !o);
        return a;
      case 4:
        if (1 == f) return e.getUint32(t + 8, !o);
        for (a = [], l = 0; l < f; l++) a[l] = e.getUint32(m + 4 * l, !o);
        return a;
      case 5:
        if (1 == f) return u = e.getUint32(m, !o), c = e.getUint32(m + 4, !o), s = new Number(u / c), s.numerator = u, s.denominator = c, s;
        for (a = [], l = 0; l < f; l++) u = e.getUint32(m + 8 * l, !o), c = e.getUint32(m + 4 + 8 * l, !o), a[l] = new Number(u / c), a[l].numerator = u, a[l].denominator = c;
        return a;
      case 9:
        if (1 == f) return e.getInt32(t + 8, !o);
        for (a = [], l = 0; l < f; l++) a[l] = e.getInt32(m + 4 * l, !o);
        return a;
      case 10:
        if (1 == f) return e.getInt32(m, !o) / e.getInt32(m + 4, !o);
        for (a = [], l = 0; l < f; l++) a[l] = e.getInt32(m + 8 * l, !o) / e.getInt32(m + 4 + 8 * l, !o);
        return a
    }
  }

  function u(e, t, n) {
    var i = e.getUint16(t, !n);
    return e.getUint32(t + 2 + 12 * i, !n)
  }

  function c(e, t, n, i) {
    var o = u(e, t + n, i);
    if (!o) return {};
    if (o > e.byteLength) return {};
    var r = s(e, t, t + o, x, i);
    if (r.Compression) switch (r.Compression) {
      case 6:
        if (r.JpegIFOffset && r.JpegIFByteCount) {
          var a = t + r.JpegIFOffset,
            l = r.JpegIFByteCount;
          r.blob = new Blob([new Uint8Array(e.buffer, a, l)], {
            type: "image/jpeg"
          })
        }
        break;
      case 1:
        console.log("Thumbnail image format is TIFF, which is not implemented.");
        break;
      default:
        console.log("Unknown thumbnail image format '%s'", r.Compression)
    } else 2 == r.PhotometricInterpretation && console.log("Thumbnail image format is RGB, which is not implemented.");
    return r
  }

  function d(e, t, n) {
    for (var i = "", o = t; o < t + n; o++) i += String.fromCharCode(e.getUint8(o));
    return i
  }

  function p(e, t) {
    if ("Exif" != d(e, t, 4)) return g && console.log("Not valid EXIF data! " + d(e, t, 4)), !1;
    var n, i, o, r, a, l = t + 6;
    if (18761 == e.getUint16(l)) n = !1;
    else {
      if (19789 != e.getUint16(l)) return g && console.log("Not valid TIFF data! (no 0x4949 or 0x4D4D)"), !1;
      n = !0
    }
    if (42 != e.getUint16(l + 2, !n)) return g && console.log("Not valid TIFF data! (no 0x002A)"), !1;
    var u = e.getUint32(l + 4, !n);
    if (u < 8) return g && console.log("Not valid TIFF data! (First offset less than 8)", e.getUint32(l + 4, !n)), !1;
    if (i = s(e, l, l + u, _, n), i.ExifIFDPointer) {
      r = s(e, l, l + i.ExifIFDPointer, w, n);
      for (o in r) {
        switch (o) {
          case "LightSource":
          case "Flash":
          case "MeteringMode":
          case "ExposureProgram":
          case "SensingMethod":
          case "SceneCaptureType":
          case "SceneType":
          case "CustomRendered":
          case "WhiteBalance":
          case "GainControl":
          case "Contrast":
          case "Saturation":
          case "Sharpness":
          case "SubjectDistanceRange":
          case "FileSource":
            r[o] = C[o][r[o]];
            break;
          case "ExifVersion":
          case "FlashpixVersion":
            r[o] = String.fromCharCode(r[o][0], r[o][1], r[o][2], r[o][3]);
            break;
          case "ComponentsConfiguration":
            r[o] = C.Components[r[o][0]] + C.Components[r[o][1]] + C.Components[r[o][2]] + C.Components[r[o][3]]
        }
        i[o] = r[o]
      }
    }
    if (i.GPSInfoIFDPointer) {
      a = s(e, l, l + i.GPSInfoIFDPointer, b, n);
      for (o in a) {
        switch (o) {
          case "GPSVersionID":
            a[o] = a[o][0] + "." + a[o][1] + "." + a[o][2] + "." + a[o][3]
        }
        i[o] = a[o]
      }
    }
    return i.thumbnail = c(e, l, u, n), i
  }

  function f(e) {
    if ("DOMParser" in self) {
      var t = new DataView(e);
      if (g && console.log("Got file of length " + e.byteLength), 255 != t.getUint8(0) || 216 != t.getUint8(1)) return g && console.log("Not a valid JPEG"), !1;
      for (var n = 2, i = e.byteLength, o = new DOMParser; n < i - 4;) {
        if ("http" == d(t, n, 4)) {
          var r = n - 1,
            a = t.getUint16(n - 2) - 1,
            s = d(t, r, a),
            l = s.indexOf("xmpmeta>") + 8;
          s = s.substring(s.indexOf("<x:xmpmeta"), l);
          var u = s.indexOf("x:xmpmeta") + 10;
          s = s.slice(0, u) + 'xmlns:Iptc4xmpCore="http://iptc.org/std/Iptc4xmpCore/1.0/xmlns/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:tiff="http://ns.adobe.com/tiff/1.0/" xmlns:plus="http://schemas.android.com/apk/lib/com.google.android.gms.plus" xmlns:ext="http://www.gettyimages.com/xsltExtension/1.0" xmlns:exif="http://ns.adobe.com/exif/1.0/" xmlns:stEvt="http://ns.adobe.com/xap/1.0/sType/ResourceEvent#" xmlns:stRef="http://ns.adobe.com/xap/1.0/sType/ResourceRef#" xmlns:crs="http://ns.adobe.com/camera-raw-settings/1.0/" xmlns:xapGImg="http://ns.adobe.com/xap/1.0/g/img/" xmlns:Iptc4xmpExt="http://iptc.org/std/Iptc4xmpExt/2008-02-29/" ' + s.slice(u);
          var c = o.parseFromString(s, "text/xml");
          return h(c)
        }
        n++
      }
    }
  }

  function m(e) {
    var t = {};
    if (1 == e.nodeType) {
      if (e.attributes.length > 0) {
        t["@attributes"] = {};
        for (var n = 0; n < e.attributes.length; n++) {
          var i = e.attributes.item(n);
          t["@attributes"][i.nodeName] = i.nodeValue
        }
      }
    } else if (3 == e.nodeType) return e.nodeValue;
    if (e.hasChildNodes())
      for (var o = 0; o < e.childNodes.length; o++) {
        var r = e.childNodes.item(o),
          a = r.nodeName;
        if (null == t[a]) t[a] = m(r);
        else {
          if (null == t[a].push) {
            var s = t[a];
            t[a] = [], t[a].push(s)
          }
          t[a].push(m(r))
        }
      }
    return t
  }

  function h(e) {
    try {
      var t = {};
      if (e.children.length > 0)
        for (var n = 0; n < e.children.length; n++) {
          var i = e.children.item(n),
            o = i.attributes;
          for (var r in o) {
            var a = o[r],
              s = a.nodeName,
              l = a.nodeValue;
            void 0 !== s && (t[s] = l)
          }
          var u = i.nodeName;
          if ("undefined" == typeof t[u]) t[u] = m(i);
          else {
            if ("undefined" == typeof t[u].push) {
              var c = t[u];
              t[u] = [], t[u].push(c)
            }
            t[u].push(m(i))
          }
        } else t = e.textContent;
      return t
    } catch (d) {
      console.log(d.message)
    }
  }
  var g = !1,
    v = this,
    y = function(e) {
      return e instanceof y ? e : this instanceof y ? void(this.EXIFwrapped = e) : new y(e)
    };
  "undefined" != typeof exports ? ("undefined" != typeof module && module.exports && (exports = module.exports = y), exports.EXIF = y) : v.EXIF = y;
  var w = y.Tags = {
      36864: "ExifVersion",
      40960: "FlashpixVersion",
      40961: "ColorSpace",
      40962: "PixelXDimension",
      40963: "PixelYDimension",
      37121: "ComponentsConfiguration",
      37122: "CompressedBitsPerPixel",
      37500: "MakerNote",
      37510: "UserComment",
      40964: "RelatedSoundFile",
      36867: "DateTimeOriginal",
      36868: "DateTimeDigitized",
      37520: "SubsecTime",
      37521: "SubsecTimeOriginal",
      37522: "SubsecTimeDigitized",
      33434: "ExposureTime",
      33437: "FNumber",
      34850: "ExposureProgram",
      34852: "SpectralSensitivity",
      34855: "ISOSpeedRatings",
      34856: "OECF",
      37377: "ShutterSpeedValue",
      37378: "ApertureValue",
      37379: "BrightnessValue",
      37380: "ExposureBias",
      37381: "MaxApertureValue",
      37382: "SubjectDistance",
      37383: "MeteringMode",
      37384: "LightSource",
      37385: "Flash",
      37396: "SubjectArea",
      37386: "FocalLength",
      41483: "FlashEnergy",
      41484: "SpatialFrequencyResponse",
      41486: "FocalPlaneXResolution",
      41487: "FocalPlaneYResolution",
      41488: "FocalPlaneResolutionUnit",
      41492: "SubjectLocation",
      41493: "ExposureIndex",
      41495: "SensingMethod",
      41728: "FileSource",
      41729: "SceneType",
      41730: "CFAPattern",
      41985: "CustomRendered",
      41986: "ExposureMode",
      41987: "WhiteBalance",
      41988: "DigitalZoomRation",
      41989: "FocalLengthIn35mmFilm",
      41990: "SceneCaptureType",
      41991: "GainControl",
      41992: "Contrast",
      41993: "Saturation",
      41994: "Sharpness",
      41995: "DeviceSettingDescription",
      41996: "SubjectDistanceRange",
      40965: "InteroperabilityIFDPointer",
      42016: "ImageUniqueID"
    },
    _ = y.TiffTags = {
      256: "ImageWidth",
      257: "ImageHeight",
      34665: "ExifIFDPointer",
      34853: "GPSInfoIFDPointer",
      40965: "InteroperabilityIFDPointer",
      258: "BitsPerSample",
      259: "Compression",
      262: "PhotometricInterpretation",
      274: "Orientation",
      277: "SamplesPerPixel",
      284: "PlanarConfiguration",
      530: "YCbCrSubSampling",
      531: "YCbCrPositioning",
      282: "XResolution",
      283: "YResolution",
      296: "ResolutionUnit",
      273: "StripOffsets",
      278: "RowsPerStrip",
      279: "StripByteCounts",
      513: "JPEGInterchangeFormat",
      514: "JPEGInterchangeFormatLength",
      301: "TransferFunction",
      318: "WhitePoint",
      319: "PrimaryChromaticities",
      529: "YCbCrCoefficients",
      532: "ReferenceBlackWhite",
      306: "DateTime",
      270: "ImageDescription",
      271: "Make",
      272: "Model",
      305: "Software",
      315: "Artist",
      33432: "Copyright"
    },
    b = y.GPSTags = {
      0: "GPSVersionID",
      1: "GPSLatitudeRef",
      2: "GPSLatitude",
      3: "GPSLongitudeRef",
      4: "GPSLongitude",
      5: "GPSAltitudeRef",
      6: "GPSAltitude",
      7: "GPSTimeStamp",
      8: "GPSSatellites",
      9: "GPSStatus",
      10: "GPSMeasureMode",
      11: "GPSDOP",
      12: "GPSSpeedRef",
      13: "GPSSpeed",
      14: "GPSTrackRef",
      15: "GPSTrack",
      16: "GPSImgDirectionRef",
      17: "GPSImgDirection",
      18: "GPSMapDatum",
      19: "GPSDestLatitudeRef",
      20: "GPSDestLatitude",
      21: "GPSDestLongitudeRef",
      22: "GPSDestLongitude",
      23: "GPSDestBearingRef",
      24: "GPSDestBearing",
      25: "GPSDestDistanceRef",
      26: "GPSDestDistance",
      27: "GPSProcessingMethod",
      28: "GPSAreaInformation",
      29: "GPSDateStamp",
      30: "GPSDifferential"
    },
    x = y.IFD1Tags = {
      256: "ImageWidth",
      257: "ImageHeight",
      258: "BitsPerSample",
      259: "Compression",
      262: "PhotometricInterpretation",
      273: "StripOffsets",
      274: "Orientation",
      277: "SamplesPerPixel",
      278: "RowsPerStrip",
      279: "StripByteCounts",
      282: "XResolution",
      283: "YResolution",
      284: "PlanarConfiguration",
      296: "ResolutionUnit",
      513: "JpegIFOffset",
      514: "JpegIFByteCount",
      529: "YCbCrCoefficients",
      530: "YCbCrSubSampling",
      531: "YCbCrPositioning",
      532: "ReferenceBlackWhite"
    },
    C = y.StringValues = {
      ExposureProgram: {
        0: "Not defined",
        1: "Manual",
        2: "Normal program",
        3: "Aperture priority",
        4: "Shutter priority",
        5: "Creative program",
        6: "Action program",
        7: "Portrait mode",
        8: "Landscape mode"
      },
      MeteringMode: {
        0: "Unknown",
        1: "Average",
        2: "CenterWeightedAverage",
        3: "Spot",
        4: "MultiSpot",
        5: "Pattern",
        6: "Partial",
        255: "Other"
      },
      LightSource: {
        0: "Unknown",
        1: "Daylight",
        2: "Fluorescent",
        3: "Tungsten (incandescent light)",
        4: "Flash",
        9: "Fine weather",
        10: "Cloudy weather",
        11: "Shade",
        12: "Daylight fluorescent (D 5700 - 7100K)",
        13: "Day white fluorescent (N 4600 - 5400K)",
        14: "Cool white fluorescent (W 3900 - 4500K)",
        15: "White fluorescent (WW 3200 - 3700K)",
        17: "Standard light A",
        18: "Standard light B",
        19: "Standard light C",
        20: "D55",
        21: "D65",
        22: "D75",
        23: "D50",
        24: "ISO studio tungsten",
        255: "Other"
      },
      Flash: {
        0: "Flash did not fire",
        1: "Flash fired",
        5: "Strobe return light not detected",
        7: "Strobe return light detected",
        9: "Flash fired, compulsory flash mode",
        13: "Flash fired, compulsory flash mode, return light not detected",
        15: "Flash fired, compulsory flash mode, return light detected",
        16: "Flash did not fire, compulsory flash mode",
        24: "Flash did not fire, auto mode",
        25: "Flash fired, auto mode",
        29: "Flash fired, auto mode, return light not detected",
        31: "Flash fired, auto mode, return light detected",
        32: "No flash function",
        65: "Flash fired, red-eye reduction mode",
        69: "Flash fired, red-eye reduction mode, return light not detected",
        71: "Flash fired, red-eye reduction mode, return light detected",
        73: "Flash fired, compulsory flash mode, red-eye reduction mode",
        77: "Flash fired, compulsory flash mode, red-eye reduction mode, return light not detected",
        79: "Flash fired, compulsory flash mode, red-eye reduction mode, return light detected",
        89: "Flash fired, auto mode, red-eye reduction mode",
        93: "Flash fired, auto mode, return light not detected, red-eye reduction mode",
        95: "Flash fired, auto mode, return light detected, red-eye reduction mode"
      },
      SensingMethod: {
        1: "Not defined",
        2: "One-chip color area sensor",
        3: "Two-chip color area sensor",
        4: "Three-chip color area sensor",
        5: "Color sequential area sensor",
        7: "Trilinear sensor",
        8: "Color sequential linear sensor"
      },
      SceneCaptureType: {
        0: "Standard",
        1: "Landscape",
        2: "Portrait",
        3: "Night scene"
      },
      SceneType: {
        1: "Directly photographed"
      },
      CustomRendered: {
        0: "Normal process",
        1: "Custom process"
      },
      WhiteBalance: {
        0: "Auto white balance",
        1: "Manual white balance"
      },
      GainControl: {
        0: "None",
        1: "Low gain up",
        2: "High gain up",
        3: "Low gain down",
        4: "High gain down"
      },
      Contrast: {
        0: "Normal",
        1: "Soft",
        2: "Hard"
      },
      Saturation: {
        0: "Normal",
        1: "Low saturation",
        2: "High saturation"
      },
      Sharpness: {
        0: "Normal",
        1: "Soft",
        2: "Hard"
      },
      SubjectDistanceRange: {
        0: "Unknown",
        1: "Macro",
        2: "Close view",
        3: "Distant view"
      },
      FileSource: {
        3: "DSC"
      },
      Components: {
        0: "",
        1: "Y",
        2: "Cb",
        3: "Cr",
        4: "R",
        5: "G",
        6: "B"
      }
    },
    S = {
      120: "caption",
      110: "credit",
      25: "keywords",
      55: "dateCreated",
      80: "byline",
      85: "bylineTitle",
      122: "captionWriter",
      105: "headline",
      116: "copyright",
      15: "category"
    };
  y.enableXmp = function() {
    y.isXmpEnabled = !0
  }, y.disableXmp = function() {
    y.isXmpEnabled = !1
  }, y.getData = function(t, n) {
    return !((self.Image && t instanceof self.Image || self.HTMLImageElement && t instanceof self.HTMLImageElement) && !t.complete) && (e(t) ? n && n.call(t) : i(t, n), !0)
  }, y.getTag = function(t, n) {
    if (e(t)) return t.exifdata[n]
  }, y.getIptcTag = function(t, n) {
    if (e(t)) return t.iptcdata[n]
  }, y.getAllTags = function(t) {
    if (!e(t)) return {};
    var n, i = t.exifdata,
      o = {};
    for (n in i) i.hasOwnProperty(n) && (o[n] = i[n]);
    return o
  }, y.getAllIptcTags = function(t) {
    if (!e(t)) return {};
    var n, i = t.iptcdata,
      o = {};
    for (n in i) i.hasOwnProperty(n) && (o[n] = i[n]);
    return o
  }, y.pretty = function(t) {
    if (!e(t)) return "";
    var n, i = t.exifdata,
      o = "";
    for (n in i) i.hasOwnProperty(n) && (o += "object" == typeof i[n] ? i[n] instanceof Number ? n + " : " + i[n] + " [" + i[n].numerator + "/" + i[n].denominator + "]\r\n" : n + " : [" + i[n].length + " values]\r\n" : n + " : " + i[n] + "\r\n");
    return o
  }, y.readFromBinaryFile = function(e) {
    return o(e)
  }, "function" == typeof define && define.amd && define("exif-js", [], function() {
    return y
  })
}).call(this),
  function(e, t) {
    "function" == typeof define && define.amd ? define(["exports"], t) : t("object" == typeof exports && "string" != typeof exports.nodeName ? exports : e.commonJsStrict = {})
  }(this, function(e) {
    function t(e) {
      if (e in V) return e;
      for (var t = e[0].toUpperCase() + e.slice(1), n = q.length; n--;)
        if (e = q[n] + t, e in V) return e
    }

    function n(e, t) {
      var n = J.indexOf(e) > -1 ? J : K,
        i = n.indexOf(e),
        o = t / 90 % n.length;
      return n[(n.length + i + o % n.length) % n.length]
    }

    function i(e, t) {
      e = e || {};
      for (var n in t) t[n] && t[n].constructor && t[n].constructor === Object ? (e[n] = e[n] || {}, i(e[n], t[n])) : e[n] = t[n];
      return e
    }

    function o(e) {
      return i({}, e)
    }

    function r(e, t, n) {
      var i;
      return function() {
        var o = this,
          r = arguments,
          a = function() {
            i = null, n || e.apply(o, r)
          },
          s = n && !i;
        clearTimeout(i), i = setTimeout(a, t), s && e.apply(o, r)
      }
    }

    function a(e) {
      if ("createEvent" in document) {
        var t = document.createEvent("HTMLEvents");
        t.initEvent("change", !1, !0), e.dispatchEvent(t)
      } else e.fireEvent("onchange")
    }

    function s(e, t, n) {
      if ("string" == typeof t) {
        var i = t;
        t = {}, t[i] = n
      }
      for (var o in t) e.style[o] = t[o]
    }

    function l(e, t) {
      e.classList ? e.classList.add(t) : e.className += " " + t
    }

    function u(e, t) {
      e.classList ? e.classList.remove(t) : e.className = e.className.replace(t, "")
    }

    function c(e, t) {
      for (var n in t) e.setAttribute(n, t[n])
    }

    function d(e) {
      return parseInt(e, 10)
    }

    function p(e, t) {
      var n = new Image;
      return n.style.opacity = 0, new Promise(function(i) {
        function o() {
          n.style.opacity = 1, setTimeout(function() {
            i(n)
          }, 1)
        }
        n.removeAttribute("crossOrigin"), e.match(/^https?:\/\/|^\/\//) && n.setAttribute("crossOrigin", "anonymous"), n.onload = function() {
          t ? EXIF.getData(n, function() {
            o()
          }) : o()
        }, n.src = e
      })
    }

    function f(e, t) {
      var n = e.naturalWidth,
        i = e.naturalHeight,
        o = t || m(e);
      if (o && o >= 5) {
        var r = n;
        n = i, i = r
      }
      return {
        width: n,
        height: i
      }
    }

    function m(e) {
      return e.exifdata && e.exifdata.Orientation ? d(e.exifdata.Orientation) : 1
    }

    function h(e, t, n) {
      var i = t.width,
        o = t.height,
        r = e.getContext("2d");
      switch (e.width = t.width, e.height = t.height, r.save(), n) {
        case 2:
          r.translate(i, 0), r.scale(-1, 1);
          break;
        case 3:
          r.translate(i, o), r.rotate(180 * Math.PI / 180);
          break;
        case 4:
          r.translate(0, o), r.scale(1, -1);
          break;
        case 5:
          e.width = o, e.height = i, r.rotate(90 * Math.PI / 180), r.scale(1, -1);
          break;
        case 6:
          e.width = o, e.height = i, r.rotate(90 * Math.PI / 180), r.translate(0, -o);
          break;
        case 7:
          e.width = o, e.height = i, r.rotate(-90 * Math.PI / 180), r.translate(-i, o), r.scale(1, -1);
          break;
        case 8:
          e.width = o, e.height = i, r.translate(0, i), r.rotate(-90 * Math.PI / 180)
      }
      r.drawImage(t, 0, 0, i, o), r.restore()
    }

    function g() {
      var e, t, n, i, o, r, a = this,
        u = "croppie-container",
        d = a.options.viewport.type ? "cr-vp-" + a.options.viewport.type : null;
      a.options.useCanvas = a.options.enableOrientation || v.call(a), a.data = {}, a.elements = {}, e = a.elements.boundary = document.createElement("div"), n = a.elements.viewport = document.createElement("div"), t = a.elements.img = document.createElement("img"), i = a.elements.overlay = document.createElement("div"), a.options.useCanvas ? (a.elements.canvas = document.createElement("canvas"), a.elements.preview = a.elements.canvas) : a.elements.preview = a.elements.img, l(e, "cr-boundary"), e.setAttribute("aria-dropeffect", "none"), o = a.options.boundary.width, r = a.options.boundary.height, s(e, {
        width: o + (isNaN(o) ? "" : "px"),
        height: r + (isNaN(r) ? "" : "px")
      }), l(n, "cr-viewport"), d && l(n, d), s(n, {
        width: a.options.viewport.width + "px",
        height: a.options.viewport.height + "px"
      }), n.setAttribute("tabindex", 0), l(a.elements.preview, "cr-image"), c(a.elements.preview, {
        alt: "preview",
        "aria-grabbed": "false"
      }), l(i, "cr-overlay"), a.element.appendChild(e), e.appendChild(a.elements.preview), e.appendChild(n), e.appendChild(i), l(a.element, u), a.options.customClass && l(a.element, a.options.customClass), S.call(this), a.options.enableZoom && _.call(a), a.options.enableResize && y.call(a)
    }

    function v() {
      return this.options.enableExif && window.EXIF
    }

    function y() {
      function e(e) {
        if ((void 0 === e.button || 0 === e.button) && (e.preventDefault(), !m)) {
          var s = p.elements.overlay.getBoundingClientRect();
          if (m = !0, o = e.pageX, r = e.pageY, i = e.currentTarget.className.indexOf("vertical") !== -1 ? "v" : "h", a = s.width, u = s.height, e.touches) {
            var l = e.touches[0];
            o = l.pageX, r = l.pageY
          }
          window.addEventListener("mousemove", t), window.addEventListener("touchmove", t), window.addEventListener("mouseup", n), window.addEventListener("touchend", n), document.body.style[Z] = "none"
        }
      }

      function t(e) {
        var t = e.pageX,
          n = e.pageY;
        if (e.preventDefault(), e.touches) {
          var l = e.touches[0];
          t = l.pageX, n = l.pageY
        }
        var c = t - o,
          d = n - r,
          m = p.options.viewport.height + d,
          g = p.options.viewport.width + c;
        "v" === i && m >= h && m <= u ? (s(f, {
          height: m + "px"
        }), p.options.boundary.height += d, s(p.elements.boundary, {
          height: p.options.boundary.height + "px"
        }), p.options.viewport.height += d, s(p.elements.viewport, {
          height: p.options.viewport.height + "px"
        })) : "h" === i && g >= h && g <= a && (s(f, {
          width: g + "px"
        }), p.options.boundary.width += c, s(p.elements.boundary, {
          width: p.options.boundary.width + "px"
        }), p.options.viewport.width += c, s(p.elements.viewport, {
          width: p.options.viewport.width + "px"
        })), E.call(p), R.call(p), C.call(p), F.call(p), r = n, o = t
      }

      function n() {
        m = !1, window.removeEventListener("mousemove", t), window.removeEventListener("touchmove", t), window.removeEventListener("mouseup", n), window.removeEventListener("touchend", n), document.body.style[Z] = ""
      }
      var i, o, r, a, u, c, d, p = this,
        f = document.createElement("div"),
        m = !1,
        h = 50;
      l(f, "cr-resizer"), s(f, {
        width: this.options.viewport.width + "px",
        height: this.options.viewport.height + "px"
      }), this.options.resizeControls.height && (c = document.createElement("div"), l(c, "cr-resizer-vertical"), f.appendChild(c)), this.options.resizeControls.width && (d = document.createElement("div"), l(d, "cr-resizer-horisontal"), f.appendChild(d)), c && (c.addEventListener("mousedown", e), c.addEventListener("touchstart", e)), d && (d.addEventListener("mousedown", e), d.addEventListener("touchstart", e)), this.elements.boundary.appendChild(f)
    }

    function w(e) {
      if (this.options.enableZoom) {
        var t = this.elements.zoomer,
          n = G(e, 4);
        t.value = Math.max(t.min, Math.min(t.max, n))
      }
    }

    function _() {
      function e() {
        b.call(n, {
          value: parseFloat(o.value),
          origin: new te(n.elements.preview),
          viewportRect: n.elements.viewport.getBoundingClientRect(),
          transform: ee.parse(n.elements.preview)
        })
      }

      function t(t) {
        var i, o;
        return "ctrl" === n.options.mouseWheelZoom && t.ctrlKey !== !0 ? 0 : (i = t.wheelDelta ? t.wheelDelta / 1200 : t.deltaY ? t.deltaY / 1060 : t.detail ? t.detail / -60 : 0, o = n._currentZoom + i * n._currentZoom, t.preventDefault(), w.call(n, o), void e.call(n))
      }
      var n = this,
        i = n.elements.zoomerWrap = document.createElement("div"),
        o = n.elements.zoomer = document.createElement("input");
      l(i, "cr-slider-wrap"), l(o, "cr-slider"), o.type = "range", o.step = "0.0001", o.value = 1, o.style.display = n.options.showZoomer ? "" : "none", o.setAttribute("aria-label", "zoom"), n.element.appendChild(i), i.appendChild(o), n._currentZoom = 1, n.elements.zoomer.addEventListener("input", e), n.elements.zoomer.addEventListener("change", e), n.options.mouseWheelZoom && (n.elements.boundary.addEventListener("mousewheel", t), n.elements.boundary.addEventListener("DOMMouseScroll", t))
    }

    function b(e) {
      function t() {
        var e = {};
        e[H] = i.toString(), e[z] = r.toString(), s(n.elements.preview, e)
      }
      var n = this,
        i = e ? e.transform : ee.parse(n.elements.preview),
        o = e ? e.viewportRect : n.elements.viewport.getBoundingClientRect(),
        r = e ? e.origin : new te(n.elements.preview);
      if (n._currentZoom = e ? e.value : n._currentZoom, i.scale = n._currentZoom, n.elements.zoomer.setAttribute("aria-valuenow", n._currentZoom), t(), n.options.enforceBoundary) {
        var a = x.call(n, o),
          l = a.translate,
          u = a.origin;
        i.x >= l.maxX && (r.x = u.minX, i.x = l.maxX), i.x <= l.minX && (r.x = u.maxX, i.x = l.minX), i.y >= l.maxY && (r.y = u.minY, i.y = l.maxY), i.y <= l.minY && (r.y = u.maxY, i.y = l.minY)
      }
      t(), ne.call(n), F.call(n)
    }

    function x(e) {
      var t = this,
        n = t._currentZoom,
        i = e.width,
        o = e.height,
        r = t.elements.boundary.clientWidth / 2,
        a = t.elements.boundary.clientHeight / 2,
        s = t.elements.preview.getBoundingClientRect(),
        l = s.width,
        u = s.height,
        c = i / 2,
        d = o / 2,
        p = (c / n - r) * -1,
        f = p - (l * (1 / n) - i * (1 / n)),
        m = (d / n - a) * -1,
        h = m - (u * (1 / n) - o * (1 / n)),
        g = 1 / n * c,
        v = l * (1 / n) - g,
        y = 1 / n * d,
        w = u * (1 / n) - y;
      return {
        translate: {
          maxX: p,
          minX: f,
          maxY: m,
          minY: h
        },
        origin: {
          maxX: v,
          minX: g,
          maxY: w,
          minY: y
        }
      }
    }

    function C() {
      var e = this,
        t = e._currentZoom,
        n = e.elements.preview.getBoundingClientRect(),
        i = e.elements.viewport.getBoundingClientRect(),
        o = ee.parse(e.elements.preview.style[H]),
        r = new te(e.elements.preview),
        a = i.top - n.top + i.height / 2,
        l = i.left - n.left + i.width / 2,
        u = {},
        c = {};
      u.y = a / t, u.x = l / t, c.y = (u.y - r.y) * (1 - t), c.x = (u.x - r.x) * (1 - t), o.x -= c.x, o.y -= c.y;
      var d = {};
      d[z] = u.x + "px " + u.y + "px", d[H] = o.toString(), s(e.elements.preview, d)
    }

    function S() {
      function e(e, t) {
        var n = m.elements.preview.getBoundingClientRect(),
          i = f.y + t,
          o = f.x + e;
        m.options.enforceBoundary ? (p.top > n.top + t && p.bottom < n.bottom + t && (f.y = i), p.left > n.left + e && p.right < n.right + e && (f.x = o)) : (f.y = i, f.x = o)
      }

      function t(e) {
        m.elements.preview.setAttribute("aria-grabbed", e), m.elements.boundary.setAttribute("aria-dropeffect", e ? "move" : "none")
      }

      function n(e) {
        function t(e) {
          switch (e) {
            case n:
              return [1, 0];
            case o:
              return [0, 1];
            case r:
              return [-1, 0];
            case a:
              return [0, -1]
          }
        }
        var n = 37,
          o = 38,
          r = 39,
          a = 40;
        if (!e.shiftKey || e.keyCode !== o && e.keyCode !== a) {
          if (m.options.enableKeyMovement && e.keyCode >= 37 && e.keyCode <= 40) {
            e.preventDefault();
            var s = t(e.keyCode);
            f = ee.parse(m.elements.preview), document.body.style[Z] = "none", p = m.elements.viewport.getBoundingClientRect(), i(s)
          }
        } else {
          var l = 0;
          l = e.keyCode === o ? parseFloat(m.elements.zoomer.value, 10) + parseFloat(m.elements.zoomer.step, 10) : parseFloat(m.elements.zoomer.value, 10) - parseFloat(m.elements.zoomer.step, 10), m.setZoom(l)
        }
      }

      function i(t) {
        var n = t[0],
          i = t[1],
          o = {};
        e(n, i), o[H] = f.toString(), s(m.elements.preview, o), E.call(m), document.body.style[Z] = "", C.call(m), F.call(m), d = 0
      }

      function o(e) {
        if ((void 0 === e.button || 0 === e.button) && (e.preventDefault(), !h)) {
          if (h = !0, u = e.pageX, c = e.pageY, e.touches) {
            var n = e.touches[0];
            u = n.pageX, c = n.pageY
          }
          t(h), f = ee.parse(m.elements.preview), window.addEventListener("mousemove", r), window.addEventListener("touchmove", r), window.addEventListener("mouseup", l), window.addEventListener("touchend", l), document.body.style[Z] = "none", p = m.elements.viewport.getBoundingClientRect()
        }
      }

      function r(t) {
        t.preventDefault();
        var n = t.pageX,
          i = t.pageY;
        if (t.touches) {
          var o = t.touches[0];
          n = o.pageX, i = o.pageY
        }
        var r = n - u,
          l = i - c,
          p = {};
        if ("touchmove" === t.type && t.touches.length > 1) {
          var h = t.touches[0],
            g = t.touches[1],
            v = Math.sqrt((h.pageX - g.pageX) * (h.pageX - g.pageX) + (h.pageY - g.pageY) * (h.pageY - g.pageY));
          d || (d = v / m._currentZoom);
          var y = v / d;
          return w.call(m, y), void a(m.elements.zoomer)
        }
        e(r, l), p[H] = f.toString(), s(m.elements.preview, p), E.call(m), c = i, u = n
      }

      function l() {
        h = !1, t(h), window.removeEventListener("mousemove", r), window.removeEventListener("touchmove", r), window.removeEventListener("mouseup", l), window.removeEventListener("touchend", l), document.body.style[Z] = "", C.call(m), F.call(m), d = 0
      }
      var u, c, d, p, f, m = this,
        h = !1;
      m.elements.overlay.addEventListener("mousedown", o), m.elements.viewport.addEventListener("keydown", n), m.elements.overlay.addEventListener("touchstart", o)
    }

    function E() {
      if (this.elements) {
        var e = this,
          t = e.elements.boundary.getBoundingClientRect(),
          n = e.elements.preview.getBoundingClientRect();
        s(e.elements.overlay, {
          width: n.width + "px",
          height: n.height + "px",
          top: n.top - t.top + "px",
          left: n.left - t.left + "px"
        })
      }
    }

    function F() {
      var e, t = this,
        n = t.get();
      if (P.call(t))
        if (t.options.update.call(t, n), t.$ && "undefined" == typeof Prototype) t.$(t.element).trigger("update.croppie", n);
        else {
          var e;
          window.CustomEvent ? e = new CustomEvent("update", {
            detail: n
          }) : (e = document.createEvent("CustomEvent"), e.initCustomEvent("update", !0, !0, n)), t.element.dispatchEvent(e)
        }
    }

    function P() {
      return this.elements.preview.offsetHeight > 0 && this.elements.preview.offsetWidth > 0
    }

    function I() {
      var e = this,
        t = 1,
        n = {},
        i = e.elements.preview,
        o = null,
        r = new ee(0, 0, t),
        a = new te,
        l = P.call(e);
      l && !e.data.bound && (e.data.bound = !0, n[H] = r.toString(), n[z] = a.toString(), n.opacity = 1, s(i, n), o = e.elements.preview.getBoundingClientRect(), e._originalImageWidth = o.width, e._originalImageHeight = o.height, e.data.orientation = m(e.elements.img), e.options.enableZoom ? R.call(e, !0) : e._currentZoom = t, r.scale = e._currentZoom, n[H] = r.toString(), s(i, n), e.data.points.length ? D.call(e, e.data.points) : L.call(e), C.call(e), E.call(e))
    }

    function R(e) {
      var t, n, i, o, r = this,
        s = 0,
        l = r.options.maxZoom || 1.5,
        u = r.elements.zoomer,
        c = parseFloat(u.value),
        d = r.elements.boundary.getBoundingClientRect(),
        p = f(r.elements.img, r.data.orientation),
        m = r.elements.viewport.getBoundingClientRect();
      r.options.enforceBoundary && (i = m.width / p.width, o = m.height / p.height, s = Math.max(i, o)), s >= l && (l = s + 1), u.min = G(s, 4), u.max = G(l, 4), !e && (c < u.min || c > u.max) ? w.call(r, c < u.min ? u.min : u.max) : e && (n = Math.max(d.width / p.width, d.height / p.height), t = null !== r.data.boundZoom ? r.data.boundZoom : n, w.call(r, t)), a(u)
    }

    function D(e) {
      if (4 !== e.length) throw "Croppie - Invalid number of points supplied: " + e;
      var t = this,
        n = e[2] - e[0],
        i = t.elements.viewport.getBoundingClientRect(),
        o = t.elements.boundary.getBoundingClientRect(),
        r = {
          left: i.left - o.left,
          top: i.top - o.top
        },
        a = i.width / n,
        l = e[1],
        u = e[0],
        c = -1 * e[1] + r.top,
        d = -1 * e[0] + r.left,
        p = {};
      p[z] = u + "px " + l + "px", p[H] = new ee(d, c, a).toString(), s(t.elements.preview, p), w.call(t, a), t._currentZoom = a
    }

    function L() {
      var e = this,
        t = e.elements.preview.getBoundingClientRect(),
        n = e.elements.viewport.getBoundingClientRect(),
        i = e.elements.boundary.getBoundingClientRect(),
        o = n.left - i.left,
        r = n.top - i.top,
        a = o - (t.width - n.width) / 2,
        l = r - (t.height - n.height) / 2,
        u = new ee(a, l, e._currentZoom);
      s(e.elements.preview, H, u.toString())
    }

    function T(e) {
      var t = this,
        n = t.elements.canvas,
        i = t.elements.img,
        o = n.getContext("2d");
      o.clearRect(0, 0, n.width, n.height), n.width = i.width, n.height = i.height;
      var r = t.options.enableOrientation && e || m(i);
      h(n, i, r)
    }

    function U(e) {
      var t = this,
        n = e.points,
        i = d(n[0]),
        o = d(n[1]),
        r = d(n[2]),
        a = d(n[3]),
        s = r - i,
        l = a - o,
        u = e.circle,
        c = document.createElement("canvas"),
        p = c.getContext("2d"),
        f = 0,
        m = 0,
        h = e.outputWidth || s,
        g = e.outputHeight || l;
      e.outputWidth && e.outputHeight;
      return c.width = h, c.height = g, e.backgroundColor && (p.fillStyle = e.backgroundColor, p.fillRect(0, 0, h, g)), t.options.enforceBoundary !== !1 && (s = Math.min(s, t._originalImageWidth), l = Math.min(l, t._originalImageHeight)), p.drawImage(this.elements.preview, i, o, s, l, f, m, h, g), u && (p.fillStyle = "#fff", p.globalCompositeOperation = "destination-in", p.beginPath(), p.arc(c.width / 2, c.height / 2, c.width / 2, 0, 2 * Math.PI, !0), p.closePath(), p.fill()), c
    }

    function M(e) {
      var t = e.points,
        n = document.createElement("div"),
        i = document.createElement("img"),
        o = t[2] - t[0],
        r = t[3] - t[1];
      return l(n, "croppie-result"), n.appendChild(i), s(i, {
        left: -1 * t[0] + "px",
        top: -1 * t[1] + "px"
      }), i.src = e.url, s(n, {
        width: o + "px",
        height: r + "px"
      }), n
    }

    function B(e) {
      return U.call(this, e).toDataURL(e.format, e.quality)
    }

    function k(e) {
      var t = this;
      return new Promise(function(n, i) {
        U.call(t, e).toBlob(function(e) {
          n(e)
        }, e.format, e.quality)
      })
    }

    function N(e) {
      this.elements.img.parentNode && (Array.prototype.forEach.call(this.elements.img.classList, function(t) {
        e.classList.add(t)
      }), this.elements.img.parentNode.replaceChild(e, this.elements.img), this.elements.preview = e), this.elements.img = e
    }

    function $(e, t) {
      var n, i = this,
        o = [],
        r = null,
        a = v.call(i);
      if ("string" == typeof e) n = e, e = {};
      else if (Array.isArray(e)) o = e.slice();
      else {
        if ("undefined" == typeof e && i.data.url) return I.call(i), F.call(i), null;
        n = e.url, o = e.points || [], r = "undefined" == typeof e.zoom ? null : e.zoom
      }
      return i.data.bound = !1, i.data.url = n || i.data.url, i.data.boundZoom = r, p(n, a).then(function(n) {
        if (N.call(i, n), o.length) i.options.relative && (o = [o[0] * n.naturalWidth / 100, o[1] * n.naturalHeight / 100, o[2] * n.naturalWidth / 100, o[3] * n.naturalHeight / 100]);
        else {
          var r, a, s = f(n),
            l = i.elements.viewport.getBoundingClientRect(),
            u = l.width / l.height,
            c = s.width / s.height;
          c > u ? (a = s.height, r = a * u) : (r = s.width, a = s.height / u);
          var d = (s.width - r) / 2,
            p = (s.height - a) / 2,
            m = d + r,
            h = p + a;
          i.data.points = [d, p, m, h]
        }
        i.data.points = o.map(function(e) {
          return parseFloat(e)
        }), i.options.useCanvas && T.call(i, e.orientation), I.call(i), F.call(i), t && t()
      })["catch"](function(e) {
        console.error("Croppie:" + e)
      })
    }

    function G(e, t) {
      return parseFloat(e).toFixed(t || 0)
    }

    function O() {
      var e = this,
        t = e.elements.preview.getBoundingClientRect(),
        n = e.elements.viewport.getBoundingClientRect(),
        i = n.left - t.left,
        o = n.top - t.top,
        r = (n.width - e.elements.viewport.offsetWidth) / 2,
        a = (n.height - e.elements.viewport.offsetHeight) / 2,
        s = i + e.elements.viewport.offsetWidth + r,
        l = o + e.elements.viewport.offsetHeight + a,
        u = e._currentZoom;
      (u === 1 / 0 || isNaN(u)) && (u = 1);
      var c = e.options.enforceBoundary ? 0 : Number.NEGATIVE_INFINITY;
      return i = Math.max(c, i / u), o = Math.max(c, o / u), s = Math.max(c, s / u), l = Math.max(c, l / u), {
        points: [G(i), G(o), G(s), G(l)],
        zoom: u,
        orientation: e.data.orientation
      }
    }

    function A(e) {
      var t, n = this,
        r = O.call(n),
        a = i(o(ie), o(e)),
        s = "string" == typeof e ? e : a.type || "base64",
        l = a.size || "viewport",
        u = a.format,
        c = a.quality,
        d = a.backgroundColor,
        p = "boolean" == typeof a.circle ? a.circle : "circle" === n.options.viewport.type,
        f = n.elements.viewport.getBoundingClientRect(),
        m = f.width / f.height;
      return "viewport" === l ? (r.outputWidth = f.width, r.outputHeight = f.height) : "object" == typeof l && (l.width && l.height ? (r.outputWidth = l.width, r.outputHeight = l.height) : l.width ? (r.outputWidth = l.width, r.outputHeight = l.width / m) : l.height && (r.outputWidth = l.height * m, r.outputHeight = l.height)), oe.indexOf(u) > -1 && (r.format = "image/" + u, r.quality = c), r.circle = p, r.url = n.data.url, r.backgroundColor = d, t = new Promise(function(e, t) {
        switch (s.toLowerCase()) {
          case "rawcanvas":
            e(U.call(n, r));
            break;
          case "canvas":
          case "base64":
            e(B.call(n, r));
            break;
          case "blob":
            k.call(n, r).then(e);
            break;
          default:
            e(M.call(n, r))
        }
      })
    }

    function X() {
      I.call(this)
    }

    function j(e) {
      if (!this.options.useCanvas || !this.options.enableOrientation) throw "Croppie: Cannot rotate without enableOrientation && EXIF.js included";
      var t = this,
        i = t.elements.canvas;
      t.data.orientation = n(t.data.orientation, e), h(i, t.elements.img, t.data.orientation), R.call(t), b.call(t), copy = null
    }

    function W() {
      var e = this;
      e.element.removeChild(e.elements.boundary), u(e.element, "croppie-container"), e.options.enableZoom && e.element.removeChild(e.elements.zoomerWrap), delete e.elements
    }

    function Y(e, t) {
      if (e.className.indexOf("croppie-container") > -1) throw new Error("Croppie: Can't initialize croppie more than once");
      if (this.element = e, this.options = i(o(Y.defaults), t), "img" === this.element.tagName.toLowerCase()) {
        var n = this.element;
        l(n, "cr-original-image"), c(n, {
          "aria-hidden": "true",
          alt: ""
        });
        var r = document.createElement("div");
        this.element.parentNode.appendChild(r), r.appendChild(n), this.element = r, this.options.url = this.options.url || n.src
      }
      if (g.call(this), this.options.url) {
        var a = {
          url: this.options.url,
          points: this.options.points
        };
        delete this.options.url, delete this.options.points, $.call(this, a)
      }
    }
    "function" != typeof Promise && ! function(e) {
      function t(e, t) {
        return function() {
          e.apply(t, arguments)
        }
      }

      function n(e) {
        if ("object" != typeof this) throw new TypeError("Promises must be constructed via new");
        if ("function" != typeof e) throw new TypeError("not a function");
        this._state = null, this._value = null, this._deferreds = [], l(e, t(o, this), t(r, this))
      }

      function i(e) {
        var t = this;
        return null === this._state ? void this._deferreds.push(e) : void c(function() {
          var n = t._state ? e.onFulfilled : e.onRejected;
          if (null === n) return void(t._state ? e.resolve : e.reject)(t._value);
          var i;
          try {
            i = n(t._value)
          } catch (o) {
            return void e.reject(o)
          }
          e.resolve(i)
        })
      }

      function o(e) {
        try {
          if (e === this) throw new TypeError("A promise cannot be resolved with itself.");
          if (e && ("object" == typeof e || "function" == typeof e)) {
            var n = e.then;
            if ("function" == typeof n) return void l(t(n, e), t(o, this), t(r, this))
          }
          this._state = !0, this._value = e, a.call(this)
        } catch (i) {
          r.call(this, i)
        }
      }

      function r(e) {
        this._state = !1, this._value = e, a.call(this)
      }

      function a() {
        for (var e = 0, t = this._deferreds.length; t > e; e++) i.call(this, this._deferreds[e]);
        this._deferreds = null
      }

      function s(e, t, n, i) {
        this.onFulfilled = "function" == typeof e ? e : null, this.onRejected = "function" == typeof t ? t : null, this.resolve = n, this.reject = i
      }

      function l(e, t, n) {
        var i = !1;
        try {
          e(function(e) {
            i || (i = !0, t(e))
          }, function(e) {
            i || (i = !0, n(e))
          })
        } catch (o) {
          if (i) return;
          i = !0, n(o)
        }
      }
      var u = setTimeout,
        c = "function" == typeof setImmediate && setImmediate || function(e) {
          u(e, 1)
        },
        d = Array.isArray || function(e) {
          return "[object Array]" === Object.prototype.toString.call(e)
        };
      n.prototype["catch"] = function(e) {
        return this.then(null, e)
      }, n.prototype.then = function(e, t) {
        var o = this;
        return new n(function(n, r) {
          i.call(o, new s(e, t, n, r))
        })
      }, n.all = function() {
        var e = Array.prototype.slice.call(1 === arguments.length && d(arguments[0]) ? arguments[0] : arguments);
        return new n(function(t, n) {
          function i(r, a) {
            try {
              if (a && ("object" == typeof a || "function" == typeof a)) {
                var s = a.then;
                if ("function" == typeof s) return void s.call(a, function(e) {
                  i(r, e)
                }, n)
              }
              e[r] = a, 0 === --o && t(e)
            } catch (l) {
              n(l)
            }
          }
          if (0 === e.length) return t([]);
          for (var o = e.length, r = 0; r < e.length; r++) i(r, e[r])
        })
      }, n.resolve = function(e) {
        return e && "object" == typeof e && e.constructor === n ? e : new n(function(t) {
          t(e)
        })
      }, n.reject = function(e) {
        return new n(function(t, n) {
          n(e)
        })
      }, n.race = function(e) {
        return new n(function(t, n) {
          for (var i = 0, o = e.length; o > i; i++) e[i].then(t, n)
        })
      }, n._setImmediateFn = function(e) {
        c = e
      }, "undefined" != typeof module && module.exports ? module.exports = n : e.Promise || (e.Promise = n)
    }(this), "function" != typeof window.CustomEvent && ! function() {
      function e(e, t) {
        t = t || {
          bubbles: !1,
          cancelable: !1,
          detail: void 0
        };
        var n = document.createEvent("CustomEvent");
        return n.initCustomEvent(e, t.bubbles, t.cancelable, t.detail), n
      }
      e.prototype = window.Event.prototype, window.CustomEvent = e
    }(), HTMLCanvasElement.prototype.toBlob || Object.defineProperty(HTMLCanvasElement.prototype, "toBlob", {
      value: function(e, t, n) {
        for (var i = atob(this.toDataURL(t, n).split(",")[1]), o = i.length, r = new Uint8Array(o), a = 0; a < o; a++) r[a] = i.charCodeAt(a);
        e(new Blob([r], {
          type: t || "image/png"
        }))
      }
    });
    var z, H, Z, q = ["Webkit", "Moz", "ms"],
      V = document.createElement("div").style,
      J = [1, 8, 3, 6],
      K = [2, 7, 4, 5];
    H = t("transform"), z = t("transformOrigin"), Z = t("userSelect");
    var Q = {
        translate3d: {
          suffix: ", 0px"
        },
        translate: {
          suffix: ""
        }
      },
      ee = function(e, t, n) {
        this.x = parseFloat(e), this.y = parseFloat(t), this.scale = parseFloat(n)
      };
    ee.parse = function(e) {
      return e.style ? ee.parse(e.style[H]) : e.indexOf("matrix") > -1 || e.indexOf("none") > -1 ? ee.fromMatrix(e) : ee.fromString(e)
    }, ee.fromMatrix = function(e) {
      var t = e.substring(7).split(",");
      return t.length && "none" !== e || (t = [1, 0, 0, 1, 0, 0]), new ee(d(t[4]), d(t[5]), parseFloat(t[0]))
    }, ee.fromString = function(e) {
      var t = e.split(") "),
        n = t[0].substring(Y.globals.translate.length + 1).split(","),
        i = t.length > 1 ? t[1].substring(6) : 1,
        o = n.length > 1 ? n[0] : 0,
        r = n.length > 1 ? n[1] : 0;
      return new ee(o, r, i)
    }, ee.prototype.toString = function() {
      var e = Q[Y.globals.translate].suffix || "";
      return Y.globals.translate + "(" + this.x + "px, " + this.y + "px" + e + ") scale(" + this.scale + ")"
    };
    var te = function(e) {
      if (!e || !e.style[z]) return this.x = 0, void(this.y = 0);
      var t = e.style[z].split(" ");
      this.x = parseFloat(t[0]), this.y = parseFloat(t[1])
    };
    te.prototype.toString = function() {
      return this.x + "px " + this.y + "px"
    };
    var ne = r(E, 500),
      ie = {
        type: "canvas",
        format: "png",
        quality: 1
      },
      oe = ["jpeg", "webp", "png"];
    if (window.jQuery) {
      var re = window.jQuery;
      re.fn.croppie = function(e) {
        var t = typeof e;
        if ("string" === t) {
          var n = Array.prototype.slice.call(arguments, 1),
            i = re(this).data("croppie");
          return "get" === e ? i.get() : "result" === e ? i.result.apply(i, n) : "bind" === e ? i.bind.apply(i, n) : this.each(function() {
            var t = re(this).data("croppie");
            if (t) {
              var i = t[e];
              if (!re.isFunction(i)) throw "Croppie " + e + " method not found";
              i.apply(t, n), "destroy" === e && re(this).removeData("croppie")
            }
          })
        }
        return this.each(function() {
          var t = new Y(this, e);
          t.$ = re, re(this).data("croppie", t)
        })
      }
    }
    Y.defaults = {
      viewport: {
        width: 100,
        height: 100,
        type: "square"
      },
      boundary: {},
      orientationControls: {
        enabled: !0,
        leftClass: "",
        rightClass: ""
      },
      resizeControls: {
        width: !0,
        height: !0
      },
      customClass: "",
      showZoomer: !0,
      enableZoom: !0,
      enableResize: !1,
      mouseWheelZoom: !0,
      enableExif: !1,
      enforceBoundary: !0,
      enableOrientation: !1,
      enableKeyMovement: !0,
      update: function() {}
    }, Y.globals = {
      translate: "translate3d"
    }, i(Y.prototype, {
      bind: function(e, t) {
        return $.call(this, e, t)
      },
      get: function() {
        var e = O.call(this),
          t = e.points;
        return this.options.relative && (t[0] /= this.elements.img.naturalWidth / 100, t[1] /= this.elements.img.naturalHeight / 100, t[2] /= this.elements.img.naturalWidth / 100, t[3] /= this.elements.img.naturalHeight / 100), e
      },
      result: function(e) {
        return A.call(this, e)
      },
      refresh: function() {
        return X.call(this)
      },
      setZoom: function(e) {
        w.call(this, e), a(this.elements.zoomer)
      },
      rotate: function(e) {
        j.call(this, e)
      },
      destroy: function() {
        return W.call(this)
      }
    }), e.Croppie = window.Croppie = Y
  });
var g365_form_data = {};
if ("undefined" == typeof g365_form_details) var g365_form_details = {
  items: {}
};
var g365_start_status = !1;
1 === g365_func_wrapper.sess_init ? Object.keys(g365_form_details.items).length > 0 ? g365_form_start(g365_form_details) : g365_form_start() : Object.keys(g365_form_details.items).length > 0 ? g365_func_wrapper.sess[g365_func_wrapper.sess.length] = {
  name: g365_form_start,
  args: [g365_form_details]
} : g365_func_wrapper.sess[g365_func_wrapper.sess.length] = {
  name: g365_form_start,
  args: []
};
console.error('works 1');

//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImczNjVfZm9ybV9tYW5hZ2VyLmpzIiwiZXhpZi5qcyIsImNyb3BwaWUuanMiXSwibmFtZXMiOlsiZzM2NV9mb3JtX3N0YXJ0X3VwIiwidGFyZ2V0X2NvbnRhaW5lciIsIiQiLCJlYWNoIiwidGhpcyIsImF0dHIiLCJwcm9wIiwiZzM2NV9zdGFydF9jcm9wcGllIiwiZWxlIiwiZWxlX3RhcmdldHMiLCJzcGxpdCIsImpvaW4iLCJvbiIsImZ1bGxfbmFtZSIsInZhbHVlIiwiaHRtbCIsInRyaW0iLCJ0cmlnZ2VyIiwiZzM2NV9saXZlc2VhcmNoX2luaXQiLCJmb3JtX2NoaWxkIiwiY2hpbGRyZW4iLCJsZW5ndGgiLCJzdWJtaXQiLCJnMzY1X2hhbmRsZV9mb3JtX3N1Ym1pdCIsImczNjVfZm9ybV9zZWN0aW9uX2V4cGFuZF9jb2xsYXBzZSIsInRhcmdldF9zZWN0aW9uX3N0cmluZyIsInRhcmdldF9zZWN0aW9uIiwidGFyZ2V0X3NlY3Rpb25fdGl0bGUiLCJ0b2dnbGVDbGFzcyIsImczNjVfZm9ybV9zZWN0aW9uX2Nsb3NlciIsImZpZWxkX3dyYXBwZXIiLCJwYXJlbnQiLCJzbGlkZVVwIiwicmVtb3ZlIiwibmV4dCIsImhpZGUiLCJwcmV2IiwiaXMiLCJzbGlkZURvd24iLCJnMzY1X2Zvcm1fbWVzc2FnZV9jbGVhciIsImZvcm0iLCJnMzY1X2NsZWFyX21lc3NhZ2UiLCJldmVudCIsImRhdGEiLCJ3cmFwcGVyX2Zvcm0iLCJhZGRDbGFzcyIsIm9mZiIsInRhcmdldF9lbGVtZW50IiwicmVhZEZpbGUiLCJpbnB1dCIsImZpbGVzIiwicmVhZGVyIiwiRmlsZVJlYWRlciIsIm9ubG9hZCIsImUiLCIkdXBsb2FkQ3JvcCIsInJlbW92ZUNsYXNzIiwiY3JvcHBpZSIsInVybCIsInRhcmdldCIsInJlc3VsdCIsInRoZW4iLCJyZWFkQXNEYXRhVVJMIiwiYWxlcnQiLCJwYXJlbnROb2RlIiwiaW5uZXJIVE1MIiwidmlld3BvcnQiLCJ3aWR0aCIsImhlaWdodCIsInR5cGUiLCJlbmFibGVFeGlmIiwibW91c2VXaGVlbFpvb20iLCJldiIsImZvcm1hdCIsInNpemUiLCJxdWFsaXR5IiwicmVzcCIsInZhbCIsInJlbW92ZUF0dHIiLCJnMzY1X3NlcnZfY29uIiwiZGF0YV9zZXQiLCJzZXR0aW5ncyIsInNlbmRfZGF0YSIsImczNjVfc2Vzc2lvbiIsImczNjVfc2Vzc19kYXRhIiwiaWQiLCJnMzY1X3Rva2VuIiwidG9rZW4iLCJnMzY1X3RpbWUiLCJ0aW1lIiwiZzM2NV9kYXRhX3NldCIsImczNjVfc2V0dGluZ3MiLCJhcmd1bWVudHMiLCJhamF4IiwiZzM2NV9zY3JpcHRfYWpheCIsImNhY2hlIiwiaGVhZGVycyIsIlgtUmVxdWVzdGVkLVdpdGgiLCJkYXRhVHlwZSIsImczNjVfZm9ybV92YWxpZGF0aW9uX2NoZWNrIiwiY29uc29sZSIsImxvZyIsImczNjVfYnVpbGRfdGVtcGxhdGVfZnJvbV9kYXRhIiwiZm9ybV9kYXRhIiwiZGVmT2JqIiwiRGVmZXJyZWQiLCJuZXdDb250ZW50IiwiZzM2NV9mb3JtX2RhdGEiLCJxdWVyeV90eXBlIiwidGVtcGxhdGVfZm9ybWF0IiwiZm9ybV90ZW1wbGF0ZSIsImZpZWxkX2dyb3VwIiwicmVwbGFjZSIsIlJlZ0V4cCIsImZpZWxkX29yaWdpbl9pZCIsImNvbnRlbnRWYXJzIiwibWF0Y2giLCJmaWx0ZXIiLCJpbmRleCIsInNlbGYiLCJpbmRleE9mIiwicmVzb2x2ZSIsInByb2NfdHlwZSIsImlkcyIsImFkbWluX2tleSIsImRvbmUiLCJyZXNwb25zZSIsInN0YXR1cyIsIm5ld0NvbnRlbnRfYWxsIiwibWVzc2FnZSIsImRhdGFfaWQiLCJkYXRhX2FyciIsIm5ld0NvbnRlbnRfcGFydCIsImRhdGFfa2V5IiwiZGF0YV92YWwiLCJmYWlsIiwicmVzcG9uc2VUZXh0IiwiYWx3YXlzIiwicHJvbWlzZSIsImZvcm1fZXZlbnQiLCJ0YXJnZXRfZm9ybSIsInNlcmlhbGl6ZSIsImRhdGFfdHlwZSIsImRhdGFfcmVzdWx0cyIsInJlc3VsdF90ZW1wbGF0ZSIsImZvcm1fdGVtcGxhdGVfcmVzdWx0IiwiaXNFbXB0eU9iamVjdCIsImRhdGFfcmVzdWx0IiwibGlfY2xhc3MiLCJ0ZXN0IiwiaXNOYU4iLCJwYXJzZUludCIsIndyYXBwZXJfaWQiLCJlbGVtZW50X3RpdGxlIiwidGFyZ2V0X2Zvcm1fZWxlbWVudCIsInJlc3BvbnNlX3JlZmVyZW5jZSIsIm1hcCIsImtleSIsImZhZGVPdXQiLCJtZXNzYWdlX3dyYXBwZXIiLCJnMzY1X2Zvcm1fc3RhcnQiLCJnMzY1X3Byb2Nfc3RhcnRfZGF0YSIsImZvckVhY2giLCJlbCIsInB1c2giLCJnMzY1X2lubmVyX2Zvcm1fc3RhcnQiLCJlcnJvcl9tZXNzYWdlIiwiZXJyb3IiLCJ3cmFwcGVyIiwiaW5zZXJ0QmVmb3JlIiwiY3VycmVudF9mb3JtIiwiZm9ybV90ZW1wbGF0ZV9pbml0IiwiYXBwZW5kVG8iLCJnMzY1X3NldF9zY3JpcHQiLCJnMzY1X3NjcmlwdF9hbmNob3IiLCJkb2N1bWVudCIsImdldEVsZW1lbnRCeUlkIiwid3JhcHBlcl90YXJnZXQiLCJnMzY1X3NjcmlwdF9lbGVtZW50IiwidGFyZ2V0X3R5cGUiLCJkYXRhc2V0IiwiZzM2NV90eXBlIiwiT2JqZWN0Iiwia2V5cyIsImczNjVfc3R5bGVzIiwic3R5bGVzIiwidHlwZV9rZXkiLCJ0eXBlX3ZhbHVlcyIsInZhbHVlc19rZXkiLCJzdHlsZSIsInByZXBlbmQiLCJpbWFnZUhhc0RhdGEiLCJpbWciLCJiYXNlNjRUb0FycmF5QnVmZmVyIiwiYmFzZTY0IiwiY29udGVudFR5cGUiLCJiaW5hcnkiLCJhdG9iIiwibGVuIiwiYnVmZmVyIiwiQXJyYXlCdWZmZXIiLCJ2aWV3IiwiVWludDhBcnJheSIsImkiLCJjaGFyQ29kZUF0Iiwib2JqZWN0VVJMVG9CbG9iIiwiY2FsbGJhY2siLCJodHRwIiwiWE1MSHR0cFJlcXVlc3QiLCJvcGVuIiwicmVzcG9uc2VUeXBlIiwic2VuZCIsImdldEltYWdlRGF0YSIsImhhbmRsZUJpbmFyeUZpbGUiLCJiaW5GaWxlIiwiZmluZEVYSUZpbkpQRUciLCJleGlmZGF0YSIsImlwdGNkYXRhIiwiZmluZElQVENpbkpQRUciLCJFWElGIiwiaXNYbXBFbmFibGVkIiwieG1wZGF0YSIsImZpbmRYTVBpbkpQRUciLCJjYWxsIiwic3JjIiwiYXJyYXlCdWZmZXIiLCJmaWxlUmVhZGVyIiwiYmxvYiIsInJlYWRBc0FycmF5QnVmZmVyIiwiQmxvYiIsIkZpbGUiLCJkZWJ1ZyIsImJ5dGVMZW5ndGgiLCJmaWxlIiwiZGF0YVZpZXciLCJEYXRhVmlldyIsImdldFVpbnQ4IiwibWFya2VyIiwib2Zmc2V0IiwicmVhZEVYSUZEYXRhIiwiZ2V0VWludDE2IiwiaXNGaWVsZFNlZ21lbnRTdGFydCIsIm5hbWVIZWFkZXJMZW5ndGgiLCJzdGFydE9mZnNldCIsInNlY3Rpb25MZW5ndGgiLCJyZWFkSVBUQ0RhdGEiLCJmaWVsZFZhbHVlIiwiZmllbGROYW1lIiwiZGF0YVNpemUiLCJzZWdtZW50VHlwZSIsInNlZ21lbnRTaXplIiwic2VnbWVudFN0YXJ0UG9zIiwiSXB0Y0ZpZWxkTWFwIiwiZ2V0SW50MTYiLCJnZXRTdHJpbmdGcm9tREIiLCJoYXNPd25Qcm9wZXJ0eSIsIkFycmF5IiwicmVhZFRhZ3MiLCJ0aWZmU3RhcnQiLCJkaXJTdGFydCIsInN0cmluZ3MiLCJiaWdFbmQiLCJlbnRyeU9mZnNldCIsInRhZyIsImVudHJpZXMiLCJ0YWdzIiwicmVhZFRhZ1ZhbHVlIiwidmFscyIsIm4iLCJudW1lcmF0b3IiLCJkZW5vbWluYXRvciIsIm51bVZhbHVlcyIsImdldFVpbnQzMiIsInZhbHVlT2Zmc2V0IiwiTnVtYmVyIiwiZ2V0SW50MzIiLCJnZXROZXh0SUZET2Zmc2V0IiwicmVhZFRodW1ibmFpbEltYWdlIiwiZmlyc3RJRkRPZmZzZXQiLCJJRkQxT2Zmc2V0UG9pbnRlciIsInRodW1iVGFncyIsIklGRDFUYWdzIiwiSnBlZ0lGT2Zmc2V0IiwiSnBlZ0lGQnl0ZUNvdW50IiwidE9mZnNldCIsInRMZW5ndGgiLCJzdGFydCIsIm91dHN0ciIsIlN0cmluZyIsImZyb21DaGFyQ29kZSIsImV4aWZEYXRhIiwiZ3BzRGF0YSIsInRpZmZPZmZzZXQiLCJUaWZmVGFncyIsIkV4aWZJRkRQb2ludGVyIiwiRXhpZlRhZ3MiLCJTdHJpbmdWYWx1ZXMiLCJDb21wb25lbnRzIiwiR1BTSW5mb0lGRFBvaW50ZXIiLCJHUFNUYWdzIiwiZG9tIiwiRE9NUGFyc2VyIiwieG1wU3RyaW5nIiwieG1wRW5kSW5kZXgiLCJzdWJzdHJpbmciLCJpbmRleE9mWG1wIiwic2xpY2UiLCJkb21Eb2N1bWVudCIsInBhcnNlRnJvbVN0cmluZyIsInhtbDJPYmplY3QiLCJ4bWwyanNvbiIsInhtbCIsImpzb24iLCJub2RlVHlwZSIsImF0dHJpYnV0ZXMiLCJqIiwiYXR0cmlidXRlIiwiaXRlbSIsIm5vZGVOYW1lIiwibm9kZVZhbHVlIiwiaGFzQ2hpbGROb2RlcyIsImNoaWxkTm9kZXMiLCJjaGlsZCIsIm9sZCIsIm9iaiIsImlkeCIsIml0ZW1BdHQiLCJkYXRhS2V5IiwiZGF0YVZhbHVlIiwidW5kZWZpbmVkIiwidGV4dENvbnRlbnQiLCJyb290IiwiRVhJRndyYXBwZWQiLCJleHBvcnRzIiwibW9kdWxlIiwiVGFncyIsIjM2ODY0IiwiNDA5NjAiLCI0MDk2MSIsIjQwOTYyIiwiNDA5NjMiLCIzNzEyMSIsIjM3MTIyIiwiMzc1MDAiLCIzNzUxMCIsIjQwOTY0IiwiMzY4NjciLCIzNjg2OCIsIjM3NTIwIiwiMzc1MjEiLCIzNzUyMiIsIjMzNDM0IiwiMzM0MzciLCIzNDg1MCIsIjM0ODUyIiwiMzQ4NTUiLCIzNDg1NiIsIjM3Mzc3IiwiMzczNzgiLCIzNzM3OSIsIjM3MzgwIiwiMzczODEiLCIzNzM4MiIsIjM3MzgzIiwiMzczODQiLCIzNzM4NSIsIjM3Mzk2IiwiMzczODYiLCI0MTQ4MyIsIjQxNDg0IiwiNDE0ODYiLCI0MTQ4NyIsIjQxNDg4IiwiNDE0OTIiLCI0MTQ5MyIsIjQxNDk1IiwiNDE3MjgiLCI0MTcyOSIsIjQxNzMwIiwiNDE5ODUiLCI0MTk4NiIsIjQxOTg3IiwiNDE5ODgiLCI0MTk4OSIsIjQxOTkwIiwiNDE5OTEiLCI0MTk5MiIsIjQxOTkzIiwiNDE5OTQiLCI0MTk5NSIsIjQxOTk2IiwiNDA5NjUiLCI0MjAxNiIsIjI1NiIsIjI1NyIsIjM0NjY1IiwiMzQ4NTMiLCIyNTgiLCIyNTkiLCIyNjIiLCIyNzQiLCIyNzciLCIyODQiLCI1MzAiLCI1MzEiLCIyODIiLCIyODMiLCIyOTYiLCIyNzMiLCIyNzgiLCIyNzkiLCI1MTMiLCI1MTQiLCIzMDEiLCIzMTgiLCIzMTkiLCI1MjkiLCI1MzIiLCIzMDYiLCIyNzAiLCIyNzEiLCIyNzIiLCIzMDUiLCIzMTUiLCIzMzQzMiIsIjAiLCIxIiwiMiIsIjMiLCI0IiwiNSIsIjYiLCI3IiwiOCIsIjkiLCIxMCIsIjExIiwiMTIiLCIxMyIsIjE0IiwiMTUiLCIxNiIsIjE3IiwiMTgiLCIxOSIsIjIwIiwiMjEiLCIyMiIsIjIzIiwiMjQiLCIyNSIsIjI2IiwiMjciLCIyOCIsIjI5IiwiMzAiLCJFeHBvc3VyZVByb2dyYW0iLCJNZXRlcmluZ01vZGUiLCIyNTUiLCJMaWdodFNvdXJjZSIsIkZsYXNoIiwiMzEiLCIzMiIsIjY1IiwiNjkiLCI3MSIsIjczIiwiNzciLCI3OSIsIjg5IiwiOTMiLCI5NSIsIlNlbnNpbmdNZXRob2QiLCJTY2VuZUNhcHR1cmVUeXBlIiwiU2NlbmVUeXBlIiwiQ3VzdG9tUmVuZGVyZWQiLCJXaGl0ZUJhbGFuY2UiLCJHYWluQ29udHJvbCIsIkNvbnRyYXN0IiwiU2F0dXJhdGlvbiIsIlNoYXJwbmVzcyIsIlN1YmplY3REaXN0YW5jZVJhbmdlIiwiRmlsZVNvdXJjZSIsIjEyMCIsIjExMCIsIjU1IiwiODAiLCI4NSIsIjEyMiIsIjEwNSIsIjExNiIsImVuYWJsZVhtcCIsImRpc2FibGVYbXAiLCJnZXREYXRhIiwiSW1hZ2UiLCJIVE1MSW1hZ2VFbGVtZW50IiwiY29tcGxldGUiLCJnZXRUYWciLCJnZXRJcHRjVGFnIiwiZ2V0QWxsVGFncyIsImEiLCJnZXRBbGxJcHRjVGFncyIsInByZXR0eSIsInN0clByZXR0eSIsInJlYWRGcm9tQmluYXJ5RmlsZSIsImRlZmluZSIsImFtZCIsImZhY3RvcnkiLCJjb21tb25Kc1N0cmljdCIsInZlbmRvclByZWZpeCIsImVtcHR5U3R5bGVzIiwiY2FwUHJvcCIsInRvVXBwZXJDYXNlIiwiY3NzUHJlZml4ZXMiLCJnZXRFeGlmT2Zmc2V0Iiwib3JudCIsInJvdGF0ZSIsImFyciIsIkVYSUZfTk9STSIsIkVYSUZfRkxJUCIsImRlZXBFeHRlbmQiLCJkZXN0aW5hdGlvbiIsInNvdXJjZSIsInByb3BlcnR5IiwiY29uc3RydWN0b3IiLCJjbG9uZSIsIm9iamVjdCIsImRlYm91bmNlIiwiZnVuYyIsIndhaXQiLCJpbW1lZGlhdGUiLCJ0aW1lb3V0IiwiY29udGV4dCIsImFyZ3MiLCJsYXRlciIsImFwcGx5IiwiY2FsbE5vdyIsImNsZWFyVGltZW91dCIsInNldFRpbWVvdXQiLCJkaXNwYXRjaENoYW5nZSIsImVsZW1lbnQiLCJldnQiLCJjcmVhdGVFdmVudCIsImluaXRFdmVudCIsImRpc3BhdGNoRXZlbnQiLCJmaXJlRXZlbnQiLCJjc3MiLCJ0bXAiLCJjIiwiY2xhc3NMaXN0IiwiYWRkIiwiY2xhc3NOYW1lIiwic2V0QXR0cmlidXRlcyIsImF0dHJzIiwic2V0QXR0cmlidXRlIiwibnVtIiwidiIsImxvYWRJbWFnZSIsImRvRXhpZiIsIm9wYWNpdHkiLCJQcm9taXNlIiwiX3Jlc29sdmUiLCJyZW1vdmVBdHRyaWJ1dGUiLCJuYXR1cmFsSW1hZ2VEaW1lbnNpb25zIiwidyIsIm5hdHVyYWxXaWR0aCIsImgiLCJuYXR1cmFsSGVpZ2h0Iiwib3JpZW50IiwiZ2V0RXhpZk9yaWVudGF0aW9uIiwieCIsIk9yaWVudGF0aW9uIiwiZHJhd0NhbnZhcyIsImNhbnZhcyIsIm9yaWVudGF0aW9uIiwiY3R4IiwiZ2V0Q29udGV4dCIsInNhdmUiLCJ0cmFuc2xhdGUiLCJzY2FsZSIsIk1hdGgiLCJQSSIsImRyYXdJbWFnZSIsInJlc3RvcmUiLCJfY3JlYXRlIiwiYm91bmRhcnkiLCJvdmVybGF5IiwiYnciLCJiaCIsImNvbnRDbGFzcyIsImN1c3RvbVZpZXdwb3J0Q2xhc3MiLCJvcHRpb25zIiwidXNlQ2FudmFzIiwiZW5hYmxlT3JpZW50YXRpb24iLCJfaGFzRXhpZiIsImVsZW1lbnRzIiwiY3JlYXRlRWxlbWVudCIsInByZXZpZXciLCJhbHQiLCJhcmlhLWdyYWJiZWQiLCJhcHBlbmRDaGlsZCIsImN1c3RvbUNsYXNzIiwiX2luaXREcmFnZ2FibGUiLCJlbmFibGVab29tIiwiX2luaXRpYWxpemVab29tIiwiZW5hYmxlUmVzaXplIiwiX2luaXRpYWxpemVSZXNpemUiLCJ3aW5kb3ciLCJtb3VzZURvd24iLCJidXR0b24iLCJwcmV2ZW50RGVmYXVsdCIsImlzRHJhZ2dpbmciLCJvdmVybGF5UmVjdCIsImdldEJvdW5kaW5nQ2xpZW50UmVjdCIsIm9yaWdpbmFsWCIsInBhZ2VYIiwib3JpZ2luYWxZIiwicGFnZVkiLCJkaXJlY3Rpb24iLCJjdXJyZW50VGFyZ2V0IiwibWF4V2lkdGgiLCJtYXhIZWlnaHQiLCJ0b3VjaGVzIiwiYWRkRXZlbnRMaXN0ZW5lciIsIm1vdXNlTW92ZSIsIm1vdXNlVXAiLCJib2R5IiwiQ1NTX1VTRVJTRUxFQ1QiLCJkZWx0YVgiLCJkZWx0YVkiLCJuZXdIZWlnaHQiLCJuZXdXaWR0aCIsIm1pblNpemUiLCJ3cmFwIiwiX3VwZGF0ZU92ZXJsYXkiLCJfdXBkYXRlWm9vbUxpbWl0cyIsIl91cGRhdGVDZW50ZXJQb2ludCIsIl90cmlnZ2VyVXBkYXRlIiwicmVtb3ZlRXZlbnRMaXN0ZW5lciIsInZyIiwiaHIiLCJyZXNpemVDb250cm9scyIsIl9zZXRab29tZXJWYWwiLCJ6Iiwiem9vbWVyIiwiZml4IiwibWF4IiwibWluIiwiY2hhbmdlIiwiX29uWm9vbSIsInBhcnNlRmxvYXQiLCJvcmlnaW4iLCJUcmFuc2Zvcm1PcmlnaW4iLCJ2aWV3cG9ydFJlY3QiLCJ0cmFuc2Zvcm0iLCJUcmFuc2Zvcm0iLCJwYXJzZSIsInNjcm9sbCIsImRlbHRhIiwidGFyZ2V0Wm9vbSIsImN0cmxLZXkiLCJ3aGVlbERlbHRhIiwiZGV0YWlsIiwiX2N1cnJlbnRab29tIiwiem9vbWVyV3JhcCIsInN0ZXAiLCJkaXNwbGF5Iiwic2hvd1pvb21lciIsInVpIiwiYXBwbHlDc3MiLCJ0cmFuc0NzcyIsIkNTU19UUkFOU0ZPUk0iLCJ0b1N0cmluZyIsIkNTU19UUkFOU19PUkciLCJ2cFJlY3QiLCJlbmZvcmNlQm91bmRhcnkiLCJib3VuZGFyaWVzIiwiX2dldFZpcnR1YWxCb3VuZGFyaWVzIiwidHJhbnNCb3VuZGFyaWVzIiwib0JvdW5kYXJpZXMiLCJtYXhYIiwibWluWCIsInkiLCJtYXhZIiwibWluWSIsIl9kZWJvdW5jZWRPdmVybGF5IiwidnBXaWR0aCIsInZwSGVpZ2h0IiwiY2VudGVyRnJvbUJvdW5kYXJ5WCIsImNsaWVudFdpZHRoIiwiY2VudGVyRnJvbUJvdW5kYXJ5WSIsImNsaWVudEhlaWdodCIsImltZ1JlY3QiLCJjdXJJbWdXaWR0aCIsImN1ckltZ0hlaWdodCIsImhhbGZXaWR0aCIsImhhbGZIZWlnaHQiLCJvcmlnaW5NaW5YIiwib3JpZ2luTWF4WCIsIm9yaWdpbk1pblkiLCJvcmlnaW5NYXhZIiwidnBEYXRhIiwicGMiLCJ0b3AiLCJsZWZ0IiwiY2VudGVyIiwiYWRqIiwibmV3Q3NzIiwiYXNzaWduVHJhbnNmb3JtQ29vcmRpbmF0ZXMiLCJib3R0b20iLCJyaWdodCIsInRvZ2dsZUdyYWJTdGF0ZSIsImtleURvd24iLCJwYXJzZUtleURvd24iLCJMRUZUX0FSUk9XIiwiVVBfQVJST1ciLCJSSUdIVF9BUlJPVyIsIkRPV05fQVJST1ciLCJzaGlmdEtleSIsImtleUNvZGUiLCJlbmFibGVLZXlNb3ZlbWVudCIsIm1vdmVtZW50Iiwia2V5TW92ZSIsInpvb20iLCJzZXRab29tIiwib3JpZ2luYWxEaXN0YW5jZSIsInRvdWNoMSIsInRvdWNoMiIsImRpc3QiLCJzcXJ0IiwiYm91bmRSZWN0IiwiaW1nRGF0YSIsImdldCIsIl9pc1Zpc2libGUiLCJ1cGRhdGUiLCJQcm90b3R5cGUiLCJDdXN0b21FdmVudCIsImluaXRDdXN0b21FdmVudCIsIm9mZnNldEhlaWdodCIsIm9mZnNldFdpZHRoIiwiX3VwZGF0ZVByb3BlcnRpZXNGcm9tSW1hZ2UiLCJpbml0aWFsWm9vbSIsImNzc1Jlc2V0IiwidHJhbnNmb3JtUmVzZXQiLCJvcmlnaW5SZXNldCIsImlzVmlzaWJsZSIsImJvdW5kIiwiX29yaWdpbmFsSW1hZ2VXaWR0aCIsIl9vcmlnaW5hbEltYWdlSGVpZ2h0IiwicG9pbnRzIiwiX2JpbmRQb2ludHMiLCJfY2VudGVySW1hZ2UiLCJpbml0aWFsIiwiZGVmYXVsdEluaXRpYWxab29tIiwibWluVyIsIm1pbkgiLCJtaW5ab29tIiwibWF4Wm9vbSIsImJvdW5kYXJ5RGF0YSIsImJvdW5kWm9vbSIsInBvaW50c1dpZHRoIiwidnBPZmZzZXQiLCJvcmlnaW5Ub3AiLCJvcmlnaW5MZWZ0IiwidHJhbnNmb3JtVG9wIiwidHJhbnNmb3JtTGVmdCIsImltZ0RpbSIsInZwRGltIiwiYm91bmREaW0iLCJ2cExlZnQiLCJ2cFRvcCIsIl90cmFuc2ZlckltYWdlVG9DYW52YXMiLCJjdXN0b21PcmllbnRhdGlvbiIsImNsZWFyUmVjdCIsIl9nZXRDYW52YXMiLCJjaXJjbGUiLCJzdGFydFgiLCJzdGFydFkiLCJjYW52YXNXaWR0aCIsIm91dHB1dFdpZHRoIiwiY2FudmFzSGVpZ2h0Iiwib3V0cHV0SGVpZ2h0IiwiYmFja2dyb3VuZENvbG9yIiwiZmlsbFN0eWxlIiwiZmlsbFJlY3QiLCJnbG9iYWxDb21wb3NpdGVPcGVyYXRpb24iLCJiZWdpblBhdGgiLCJhcmMiLCJjbG9zZVBhdGgiLCJmaWxsIiwiX2dldEh0bWxSZXN1bHQiLCJkaXYiLCJfZ2V0QmFzZTY0UmVzdWx0IiwidG9EYXRhVVJMIiwiX2dldEJsb2JSZXN1bHQiLCJyZWplY3QiLCJ0b0Jsb2IiLCJfcmVwbGFjZUltYWdlIiwicHJvdG90eXBlIiwicmVwbGFjZUNoaWxkIiwiX2JpbmQiLCJjYiIsImhhc0V4aWYiLCJpc0FycmF5IiwicmVsYXRpdmUiLCJuYXREaW0iLCJyZWN0IiwiYXNwZWN0UmF0aW8iLCJpbWdBc3BlY3RSYXRpbyIsIngwIiwieTAiLCJ4MSIsInkxIiwicCIsImVyciIsImRlY2ltYWxQb2ludHMiLCJ0b0ZpeGVkIiwiX2dldCIsIndpZHRoRGlmZiIsImhlaWdodERpZmYiLCJ4MiIsInkyIiwiSW5maW5pdHkiLCJORUdBVElWRV9JTkZJTklUWSIsIl9yZXN1bHQiLCJwcm9tIiwib3B0cyIsIlJFU1VMVF9ERUZBVUxUUyIsInJlc3VsdFR5cGUiLCJyYXRpbyIsIlJFU1VMVF9GT1JNQVRTIiwidG9Mb3dlckNhc2UiLCJfcmVmcmVzaCIsIl9yb3RhdGUiLCJkZWciLCJjb3B5IiwiX2Rlc3Ryb3kiLCJyZW1vdmVDaGlsZCIsIkNyb3BwaWUiLCJFcnJvciIsImRlZmF1bHRzIiwidGFnTmFtZSIsIm9yaWdJbWFnZSIsImFyaWEtaGlkZGVuIiwicmVwbGFjZW1lbnREaXYiLCJiaW5kT3B0cyIsImIiLCJUeXBlRXJyb3IiLCJfc3RhdGUiLCJfdmFsdWUiLCJfZGVmZXJyZWRzIiwiZiIsImQiLCJrIiwib25GdWxmaWxsZWQiLCJvblJlamVjdGVkIiwiZyIsInNldEltbWVkaWF0ZSIsImwiLCJhbGwiLCJyYWNlIiwiX3NldEltbWVkaWF0ZUZuIiwicGFyYW1zIiwiYnViYmxlcyIsImNhbmNlbGFibGUiLCJFdmVudCIsIkhUTUxDYW52YXNFbGVtZW50IiwiZGVmaW5lUHJvcGVydHkiLCJiaW5TdHIiLCJUUkFOU0xBVEVfT1BUUyIsInRyYW5zbGF0ZTNkIiwic3VmZml4IiwiZnJvbU1hdHJpeCIsImZyb21TdHJpbmciLCJ2YWx1ZXMiLCJnbG9iYWxzIiwialF1ZXJ5IiwiZm4iLCJvdCIsInNpbmdsZUluc3QiLCJiaW5kIiwibWV0aG9kIiwiaXNGdW5jdGlvbiIsInJlbW92ZURhdGEiLCJvcmllbnRhdGlvbkNvbnRyb2xzIiwiZW5hYmxlZCIsImxlZnRDbGFzcyIsInJpZ2h0Q2xhc3MiLCJyZWZyZXNoIiwiZGVzdHJveSIsImczNjVfZm9ybV9kZXRhaWxzIiwiaXRlbXMiLCJnMzY1X3N0YXJ0X3N0YXR1cyIsImczNjVfZnVuY193cmFwcGVyIiwic2Vzc19pbml0Iiwic2VzcyIsIm5hbWUiXSwibWFwcGluZ3MiOiJBQUtBLFFBQUFBLG9CQUFBQyxHQUVBQyxFQUFBLDJCQUFBRCxHQUFBRSxLQUFBLFdBQUFELEVBQUEsaUJBQUFBLEVBQUFFLE1BQUFDLEtBQUEsb0JBQUEsS0FBQUgsRUFBQUUsT0FBQUUsS0FBQSxZQUFBLEtBQ0FKLEVBQUEsWUFBQUQsR0FBQUUsS0FBQSxXQUFBSSxtQkFBQUwsRUFBQUUsU0FDQUYsRUFBQSxnQkFBQUQsR0FBQUUsS0FBQSxXQUNBLEdBQUFLLEdBQUFOLEVBQUFFLE1BQ0FLLEVBQUFELEVBQUFILEtBQUEsMkJBQ0EsTUFBQUksR0FBQSxPQUFBQSxHQUFBLGNBQUFBLElBQ0FBLEVBQUFQLEVBQUFPLEVBQUFDLE1BQUEsS0FBQUMsT0FBQVYsR0FDQSxLQUFBUSxHQUFBLE9BQUFBLEdBQUEsY0FBQUEsR0FDQUEsRUFBQUcsR0FBQSxRQUFBLFdBQ0EsR0FBQUMsR0FBQSxFQUNBSixHQUFBTixLQUFBLFdBQUFVLEdBQUFULEtBQUFVLE1BQUEsTUFDQU4sRUFBQU8sS0FBQSxLQUFBRixFQUFBRyxPQUFBUixFQUFBSCxLQUFBLHNCQUFBUSxLQUNBSSxRQUFBLFlBRUFDLHFCQUFBakIsRUFDQSxJQUFBa0IsR0FBQWxCLEVBQUFtQixTQUFBLGdCQUNBRCxHQUFBRSxRQUFBRixFQUFBRyxPQUFBQyx5QkFJQSxRQUFBQyxxQ0FDQSxHQUFBQyxHQUFBdkIsRUFBQUUsTUFBQUMsS0FBQSxxQkFDQXFCLEVBQUF4QixFQUFBLElBQUF1QixFQUFBLGFBQ0FFLEVBQUF6QixFQUFBLElBQUF1QixFQUFBLGtCQUVBdkIsR0FBQSxPQUFBeUIsR0FBQVosS0FBQWIsRUFBQSxnQkFBQXdCLEdBQUFYLFFBRUFXLEVBQUFFLFlBQUEsUUFFQUQsRUFBQUMsWUFBQSxRQUdBLFFBQUFDLDRCQUVBLEdBQUFDLEdBQUE1QixFQUFBRSxNQUFBMkIs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cmVsYXRlZCB0byB0aGUgZm9ybVxudmFyIGczNjVfZm9ybV9kYXRhID0ge307XG5pZiggdHlwZW9mIGczNjVfZm9ybV9kZXRhaWxzID09PSAndW5kZWZpbmVkJyAgKSB2YXIgZzM2NV9mb3JtX2RldGFpbHMgPSB7aXRlbXM6IHt9fTtcbnZhciBnMzY1X3N0YXJ0X3N0YXR1cyA9IGZhbHNlO1xuXG5mdW5jdGlvbiBnMzY1X2Zvcm1fc3RhcnRfdXAoIHRhcmdldF9jb250YWluZXIgKSB7XG5cdC8vc2VsZWN0IG9wdGlvbnMgd2l0aCBrZXlcblx0JCgnc2VsZWN0W2RhdGEtZzM2NV9zZWxlY3RdJywgdGFyZ2V0X2NvbnRhaW5lcikuZWFjaChmdW5jdGlvbigpeyQoJ29wdGlvblt2YWx1ZT1cIicgKyAkKHRoaXMpLmF0dHIoJ2RhdGEtZzM2NV9zZWxlY3QnKSArICdcIl0nLCAkKHRoaXMpKS5wcm9wKCdzZWxlY3RlZCcsIHRydWUpO30pO1xuXHQkKCcuY3JvcF9pbWcnLCB0YXJnZXRfY29udGFpbmVyKS5lYWNoKGZ1bmN0aW9uKCl7IGczNjVfc3RhcnRfY3JvcHBpZSggJCh0aGlzKSApOyB9KTtcbiAgJCgnLmNoYW5nZS10aXRsZScsIHRhcmdldF9jb250YWluZXIpLmVhY2goZnVuY3Rpb24oKXtcbiAgICB2YXIgZWxlID0gJCh0aGlzKTtcbiAgICB2YXIgZWxlX3RhcmdldHMgPSBlbGUuYXR0cignZGF0YS1nMzY1X2NoYW5nZV90YXJnZXRzJyk7XG4gICAgaWYoIGVsZV90YXJnZXRzID09PSAnJyB8fCBlbGVfdGFyZ2V0cyA9PT0gbnVsbCB8fCBlbGVfdGFyZ2V0cyA9PT0gJ3VuZGVmaW5lZCcgKSByZXR1cm47XG4gICAgZWxlX3RhcmdldHMgPSAkKGVsZV90YXJnZXRzLnNwbGl0KCd8Jykuam9pbigpLCB0YXJnZXRfY29udGFpbmVyKTtcbiAgICBpZiggZWxlX3RhcmdldHMgPT09ICcnIHx8IGVsZV90YXJnZXRzID09PSBudWxsIHx8IGVsZV90YXJnZXRzID09PSAndW5kZWZpbmVkJyApIHJldHVybjtcbiAgICBlbGVfdGFyZ2V0cy5vbigna2V5dXAnLCBmdW5jdGlvbigpe1xuICAgICAgdmFyIGZ1bGxfbmFtZSA9ICcnO1xuICAgICAgZWxlX3RhcmdldHMuZWFjaChmdW5jdGlvbigpe2Z1bGxfbmFtZSArPSB0aGlzLnZhbHVlICsgJyAnO30pO1xuICAgICAgZWxlLmh0bWwoKGZ1bGxfbmFtZS50cmltKCkgID09PSAnJykgPyBlbGUuYXR0cignZGF0YS1kZWZhdWx0X3ZhbHVlJykgOiBmdWxsX25hbWUpO1xuICAgIH0pLnRyaWdnZXIoJ2tleXVwJyk7XG4gIH0pO1xuICBnMzY1X2xpdmVzZWFyY2hfaW5pdCggdGFyZ2V0X2NvbnRhaW5lciApO1xuICB2YXIgZm9ybV9jaGlsZCA9IHRhcmdldF9jb250YWluZXIuY2hpbGRyZW4oJy5wcmltYXJ5LWZvcm0nKTtcbiAgaWYoIGZvcm1fY2hpbGQubGVuZ3RoICkgZm9ybV9jaGlsZC5zdWJtaXQoIGczNjVfaGFuZGxlX2Zvcm1fc3VibWl0ICk7XG59XG5cbi8vYWRkIGZ1bmN0aW9uYWxpdHkgdG8gY29sbGFwc2UgYW5kIHJlLWV4cGFuZCBmaWVsZCBzZWN0aW9uXG5mdW5jdGlvbiBnMzY1X2Zvcm1fc2VjdGlvbl9leHBhbmRfY29sbGFwc2UoKXtcbiAgdmFyIHRhcmdldF9zZWN0aW9uX3N0cmluZyA9ICQodGhpcykuYXR0cignZGF0YS1jbGljay10YXJnZXQnKTtcbiAgdmFyIHRhcmdldF9zZWN0aW9uID0gJCgnIycgKyB0YXJnZXRfc2VjdGlvbl9zdHJpbmcgKyAnX2ZpZWxkc2V0Jyk7XG4gIHZhciB0YXJnZXRfc2VjdGlvbl90aXRsZSA9ICQoJyMnICsgdGFyZ2V0X3NlY3Rpb25fc3RyaW5nICsgJ19maWVsZHNldF90aXRsZScpO1xuICAvL3VwZGF0ZSBzZWN0aW9uIG5hbWVcbiAgJCgnc3BhbicsIHRhcmdldF9zZWN0aW9uX3RpdGxlKS5odG1sKCAkKCcuY2hhbmdlLXRpdGxlJywgdGFyZ2V0X3NlY3Rpb24pLmh0bWwoKSApO1xuICAvL2Nsb3NlIGZpZWxkIHNlY3Rpb25cbiAgdGFyZ2V0X3NlY3Rpb24udG9nZ2xlQ2xhc3MoJ2hpZGUnKTtcbiAgLy9hY3RpdmF0ZSB0aGUgc2VjdGlvbiB0aXRsZVxuICB0YXJnZXRfc2VjdGlvbl90aXRsZS50b2dnbGVDbGFzcygnaGlkZScpO1xufVxuXG5mdW5jdGlvbiBnMzY1X2Zvcm1fc2VjdGlvbl9jbG9zZXIoKXtcbiAgLy9ncmFiIGEgcmVmZXJlbmNlIHRvIG1hbmFnZSBtYXN0ZXIgc3VibWl0IGJ1dHRvblxuICB2YXIgZmllbGRfd3JhcHBlciA9ICQodGhpcykucGFyZW50KCkucGFyZW50KCkucGFyZW50KCk7XG4gIC8vcmVtb3ZlIHNlY3Rpb24gZmllbGQgc2V0XG4gICQodGhpcykucGFyZW50KCkucGFyZW50KCkuc2xpZGVVcChcIm5vcm1hbFwiLCBmdW5jdGlvbigpIHsgJCh0aGlzKS5yZW1vdmUoKTsgfSApO1xuICAvL2lmIHRoZXJlIGFyZSBubyBtb3JlIHNlY3Rpb25zLCBoaWRlIHRoZSBtYXN0ZXIgc3VibWl0IGJ1dHRvbiAoZm9yIG1hc3RlciBmb3JtcylcbiAgaWYoIGZpZWxkX3dyYXBwZXIuY2hpbGRyZW4oKS5sZW5ndGggPT09IDAgKSBmaWVsZF93cmFwcGVyLm5leHQoJy5nMzY1X2Zvcm1fc3ViX2Jsb2NrJykuaGlkZSgpO1xuICAvL2lmIHRoZSBwcmV2aW91cyBzZWFyY2ggaW5wdXQgZm9ybSBpcyBoaWRkZW4sIHJlYWN0aXZhdGUgaXQgKGZvciBuZXN0ZWQgc2VsZWN0IGZvcm1zIG9ubHkpXG4gIGlmKCBmaWVsZF93cmFwcGVyLnByZXYoKS5pcygnZm9ybScpICkgZmllbGRfd3JhcHBlci5wcmV2KCcuc2VhcmNoOmhpZGRlbicpLnNsaWRlRG93bigpO1xufVxuXG5mdW5jdGlvbiBnMzY1X2Zvcm1fbWVzc2FnZV9jbGVhciggZm9ybSApIHtcbiAgZnVuY3Rpb24gZzM2NV9jbGVhcl9tZXNzYWdlKCBldmVudCApIHtcbiAgICBmb3JtID0gZXZlbnQuZGF0YS53cmFwcGVyX2Zvcm07XG4gICAgJCgnIycgKyBmb3JtLmF0dHIoJ2lkJykgKyAnX21lc3NhZ2UnKS5odG1sKCcnKS5hZGRDbGFzcygnaGlkZGVuX2VsZW1lbnQnKTtcbiAgICBmb3JtLm9mZiggJ2ZvY3VzJywgJzppbnB1dCcsIGczNjVfY2xlYXJfbWVzc2FnZSk7XG4gIH1cbiAgZm9ybS5vbiggJ2ZvY3VzJywgJzppbnB1dCcsIHsgd3JhcHBlcl9mb3JtOiBmb3JtIH0sIGczNjVfY2xlYXJfbWVzc2FnZSApO1xufVxuXG5mdW5jdGlvbiBnMzY1X3N0YXJ0X2Nyb3BwaWUoIHRhcmdldF9lbGVtZW50ICkge1xuXHR2YXIgJHVwbG9hZENyb3A7XG5cdGZ1bmN0aW9uIHJlYWRGaWxlKGlucHV0KSB7XG5cdFx0aWYgKGlucHV0LmZpbGVzICYmIGlucHV0LmZpbGVzWzBdKSB7XG5cdFx0XHR2YXIgcmVhZGVyID0gbmV3IEZpbGVSZWFkZXIoKTtcblx0XHRcdHJlYWRlci5vbmxvYWQgPSBmdW5jdGlvbiAoZSkge1xuXHRcdFx0XHQkdXBsb2FkQ3JvcC5wYXJlbnQoKS5hZGRDbGFzcygnY3JvcC1zaXplJykucmVtb3ZlQ2xhc3MoJ2hpZGRlbl9lbGVtZW50Jyk7XG5cdFx0XHRcdCR1cGxvYWRDcm9wLmNyb3BwaWUoJ2JpbmQnLCB7XG5cdFx0XHRcdFx0dXJsOiBlLnRhcmdldC5yZXN1bHRcblx0XHRcdFx0fSkudGhlbihmdW5jdGlvbigpe1xuXHRcdFx0XHR9KTtcblx0XHRcdH1cblx0XHRcdHJlYWRlci5yZWFkQXNEYXRhVVJMKGlucHV0LmZpbGVzWzBdKTtcblx0XHR9IGVsc2Uge1xuXHRcdFx0YWxlcnQoXCJTb3JyeSAtIHlvdSdyZSBicm93c2VyIGRvZXNuJ3Qgc3VwcG9ydCB0aGUgRmlsZVJlYWRlciBBUEkuXCIpO1xuICAgICAgaW5wdXQucGFyZW50Tm9kZS5wYXJlbnROb2RlLmlubmVySFRNTCA9IFwiPHA+U29ycnkgLSB5b3UncmUgYnJvd3NlciBkb2Vzbid0IHN1cHBvcnQgdGhlIEZpbGVSZWFkZXIgQVBJPC9wPlwiO1xuXHRcdH1cblx0fVxuXHQkdXBsb2FkQ3JvcCA9ICQoJy5jcm9wX3VwbG9hZF9jYW52YXMnLCB0YXJnZXRfZWxlbWVudCkuY3JvcHBpZSh7XG5cdFx0dmlld3BvcnQ6IHtcblx0XHRcdHdpZHRoOiAyMDAsXG5cdFx0XHRoZWlnaHQ6IDMwMCxcblx0XHRcdHR5cGU6ICdzcXVhcmUnXG5cdFx0fSxcblx0XHRlbmFibGVFeGlmOiB0cnVlLFxuXHRcdG1vdXNlV2hlZWxab29tOiBmYWxzZVxuXHR9KTtcblx0JHVwbG9hZENyb3Aub24oJ3VwZGF0ZS5jcm9wcGllJywgZnVuY3Rpb24gKGV2LCBkYXRhKSB7XG5cdFx0JHVwbG9hZENyb3AuY3JvcHBpZSgncmVzdWx0Jywge1xuXHRcdFx0dHlwZTogJ2Jhc2U2NCcsXG5cdFx0XHRmb3JtYXQ6ICdqcGVnJyxcblx0XHRcdHNpemU6IHsgd2lkdGg6IDQwMCwgaGVpZ2h0OiA2MDAgfSxcblx0XHRcdHF1YWxpdHk6IDAuOFxuXHRcdH0pLnRoZW4oZnVuY3Rpb24gKHJlc3ApIHtcblx0XHRcdCQoJy5wcm9maWxlX2ltZ19kYXRhJywgdGFyZ2V0X2VsZW1lbnQpLmF0dHIoJ25hbWUnLCAkKCcucHJvZmlsZV9pbWdfZGF0YScsIHRhcmdldF9lbGVtZW50KS5hdHRyKCdkYXRhLWczNjVfbmFtZScpKS52YWwocmVzcCk7XG5cdFx0XHQkKCcucHJvZmlsZV9pbWcnLCB0YXJnZXRfZWxlbWVudCkucmVtb3ZlQXR0cignbmFtZScpO1xuXHRcdFx0JCgnLnJlbW92ZS1wcm9maWxlJywgdGFyZ2V0X2VsZW1lbnQpLnJlbW92ZUNsYXNzKCdoaWRkZW5fZWxlbWVudCcpO1xuXHRcdH0pO1xuXHR9KTtcblx0JCgnLmNyb3BfdXBsb2FkZXInLCB0YXJnZXRfZWxlbWVudCkub24oJ2NoYW5nZScsIGZ1bmN0aW9uICgpIHsgcmVhZEZpbGUodGhpcyk7IH0pO1xuXHQkKCcucmVtb3ZlLXByb2ZpbGUnLCB0YXJnZXRfZWxlbWVudCkub24oJ2NsaWNrJywgZnVuY3Rpb24oKXtcblx0XHQkKCcuY3JvcF91cGxvYWQnLCB0YXJnZXRfZWxlbWVudCkucmVtb3ZlQ2xhc3MoJ2hpZGRlbl9lbGVtZW50Jyk7XG5cdFx0JCgnLmNyb3BfdXBsb2FkZXInLCB0YXJnZXRfZWxlbWVudCkudmFsKCcnKTtcblx0XHQkKCcuY3JvcF91cGxvYWRfY2FudmFzX3dyYXAnLCB0YXJnZXRfZWxlbWVudCkucmVtb3ZlQ2xhc3MoJ2Nyb3Atc2l6ZScpO1xuXHRcdCQoJy5wcm9maWxlX2ltZ19kYXRhJywgdGFyZ2V0X2VsZW1lbnQpLnJlbW92ZUF0dHIoJ25hbWUnKTtcblx0XHQkKCcucHJvZmlsZV9pbWcnLCB0YXJnZXRfZWxlbWVudCkuYXR0cignbmFtZScsICQoJy5wcm9maWxlX2ltZycsIHRhcmdldF9lbGVtZW50KS5hdHRyKCdkYXRhLWczNjVfbmFtZScpKS52YWwoJycpO1xuXHRcdCQoJy5yZW1vdmUtcHJvZmlsZSwgLmNyb3BwZWRfaW1nLCAuY3JvcF91cGxvYWRfY2FudmFzX3dyYXAnLCB0YXJnZXRfZWxlbWVudCkuYWRkQ2xhc3MoJ2hpZGRlbl9lbGVtZW50Jyk7XG5cdH0pO1xuXHRpZiggdGFyZ2V0X2VsZW1lbnQuYXR0cignZGF0YS1nMzY1X3Byb2ZpbGVfaW1nX3VybCcpICE9PSAnJyApIHtcblx0XHQkKCcuY3JvcF91cGxvYWQnLCB0YXJnZXRfZWxlbWVudCkuYWRkQ2xhc3MoJ2hpZGRlbl9lbGVtZW50Jyk7XG5cdFx0JCgnLnJlbW92ZS1wcm9maWxlJywgdGFyZ2V0X2VsZW1lbnQpLnJlbW92ZUNsYXNzKCdoaWRkZW5fZWxlbWVudCcpO1xuXHR9XG59XG5cbi8vc2VydmVyIGNvbm5lY3Rpb24gZnVuY3Rpb25cbmZ1bmN0aW9uIGczNjVfc2Vydl9jb24oIGRhdGFfc2V0LCBzZXR0aW5ncyApe1xuICB2YXIgc2VuZF9kYXRhID0ge1xuICAgIGczNjVfc2Vzc2lvbiA6IGczNjVfc2Vzc19kYXRhLmlkLFxuICAgIGczNjVfdG9rZW4gOiBnMzY1X3Nlc3NfZGF0YS50b2tlbixcbiAgICBnMzY1X3RpbWUgOiBnMzY1X3Nlc3NfZGF0YS50aW1lLFxuICAgIGczNjVfZGF0YV9zZXQgOiBkYXRhX3NldCxcbiAgICBnMzY1X3NldHRpbmdzIDogKCBnMzY1X3NlcnZfY29uLmFyZ3VtZW50cy5sZW5ndGggPT09IDIgKSA/IHNldHRpbmdzIDogbnVsbFxuICB9O1xuICByZXR1cm4gJC5hamF4KHtcbiAgICB0eXBlOiBcInBvc3RcIixcbiAgICB1cmw6IGczNjVfc2NyaXB0X2FqYXgsXG4gICAgY2FjaGU6IGZhbHNlLFxuICAgIGhlYWRlcnM6IHsnWC1SZXF1ZXN0ZWQtV2l0aCc6ICdYTUxIdHRwUmVxdWVzdCd9LFxuICAgIGRhdGE6IHNlbmRfZGF0YSxcbiAgICBkYXRhVHlwZTogXCJqc29uXCJcbiAgfSk7XG59XG5cbi8vdmFsaWRhdGUgZm9ybSBzZWN0aW9ucyBhbmQgaW5wdXQgcGFyYW1ldGVyc1xuZnVuY3Rpb24gZzM2NV9mb3JtX3ZhbGlkYXRpb25fY2hlY2soIHRhcmdldCApIHtcbiAgY29uc29sZS5sb2coJ1ZhbGlkYXRlIE1lIDopJyk7XG4gIHJldHVybiBmYWxzZTtcbn1cblxuLy9hc3NlbWJsZSBmb3Jtc1xuZnVuY3Rpb24gZzM2NV9idWlsZF90ZW1wbGF0ZV9mcm9tX2RhdGEoIGZvcm1fZGF0YSApIHtcblx0Ly90byBzdXBwb3J0IHRoZSBwb3RlbnRpYWwgb2YgYWpheCBmb3IgZ2V0dGluZyBkYXRhX3NldFxuXHR2YXIgZGVmT2JqID0gJC5EZWZlcnJlZCgpO1xuXHQvL2dldCByZXF1aXJlZCBjb250ZW50IHN0cmluZywgd2l0aCBhIGRlZmF1bHQgXG5cdHZhciBuZXdDb250ZW50ID0gKCBmb3JtX2RhdGEudGVtcGxhdGVfZm9ybWF0ICkgPyBnMzY1X2Zvcm1fZGF0YS5mb3JtW2Zvcm1fZGF0YS5xdWVyeV90eXBlXVtmb3JtX2RhdGEudGVtcGxhdGVfZm9ybWF0XSA6IGczNjVfZm9ybV9kYXRhLmZvcm1bZm9ybV9kYXRhLnF1ZXJ5X3R5cGVdLmZvcm1fdGVtcGxhdGU7XG5cdC8vbWFrZSBzdXJlIHRoYXQgdGhlIHRlbXBsYXRlIGlzIHRoZXJlXG5cdGlmKCB0eXBlb2YgbmV3Q29udGVudCAhPT0gJ3N0cmluZycgfHwgbmV3Q29udGVudC5sZW5ndGggPT09IDAgKSByZXR1cm4gJzxwPkZvcm0gVGVtcGxhdGUgbWFsZm9ybWF0aW9uLiBQbGVhc2UgdHJ5IHlvdXIgcmVxdWVzdCBhZ2Fpbi48L3A+Jztcblx0Ly9yZXR1cm4gaWYgd2UgZG9uJ3QgaGF2ZSBhIHVuaXF1ZSBoYW5kbGUgZm9yIHRoZSBmaWVsZCBzZXRcblx0aWYoIHR5cGVvZiBmb3JtX2RhdGEuZmllbGRfZ3JvdXAgIT09ICdzdHJpbmcnIHx8IGZvcm1fZGF0YS5maWVsZF9ncm91cCA9PT0gJycgKSByZXR1cm4gJzxwPk5lZWQgdW5pcXVlIGlkZW50aWZpZXIgZm9yIGZpZWxkIHNldC48L3A+Jztcblx0Ly9hZGQgdW5pcXVlIGlkZW50aWZpZXIgdG8gbmV3IGZvcm0gdGVtcGxhdGVcblx0bmV3Q29udGVudCA9IG5ld0NvbnRlbnQucmVwbGFjZShuZXcgUmVnRXhwKCd7e2ZpZWxkLXNldC1pZH19JywgJ2cnKSwgZm9ybV9kYXRhLmZpZWxkX2dyb3VwKTtcblx0aWYoIHR5cGVvZiBmb3JtX2RhdGEuZmllbGRfb3JpZ2luX2lkICE9PSAndW5kZWZpbmVkJyApIG5ld0NvbnRlbnQgPSBuZXdDb250ZW50LnJlcGxhY2UobmV3IFJlZ0V4cCgne3tmaWVsZC1zZXQtaWQtb3JpZ2lufX0nLCAnZycpLCBmb3JtX2RhdGEuZmllbGRfb3JpZ2luX2lkKTtcbi8vICAgY29uc29sZS5sb2coZm9ybV9kYXRhLCBnMzY1X2Zvcm1fZGV0YWlscyk7XG5cdC8vZmluZCBhbGwgdmFyaWFibGVzIHRoYXQgd2UgbmVlZCB0byByZXBsYWNlXG5cdHZhciBjb250ZW50VmFycyA9IG5ld0NvbnRlbnQubWF0Y2goL3t7KC4rPyl9fS9nKTtcblx0Ly9jYWxsIGJhY2sgZnVuY3Rpb24gdG8gbWFrZSBlbGltaW5hdGUgZHVwbGljYXRlc1xuXHRjb250ZW50VmFycyA9IGNvbnRlbnRWYXJzLmZpbHRlciggZnVuY3Rpb24gKHZhbHVlLCBpbmRleCwgc2VsZikgeyByZXR1cm4gc2VsZi5pbmRleE9mKHZhbHVlKSA9PT0gaW5kZXg7IH0gKTtcblx0aWYoIGZvcm1fZGF0YS5pZCA9PT0gbnVsbCApIHtcblx0XHQvL3JlZ2V4IHJlcGxhY2UgYWxsIHRlbXBsYXRlIHZhcmlhYmxlc1xuXHRcdGRlZk9iai5yZXNvbHZlKG5ld0NvbnRlbnQucmVwbGFjZSgve3soLis/KX19L2csICcnKSk7XG4vLyBcdFx0cmV0dXJuIG5ld0NvbnRlbnQucmVwbGFjZSgve3soLis/KX19L2csICcnKTtcblx0fSBlbHNlIHtcblx0XHQvL3VzZSB0aGUgdmFyaWFibGVzIGZyb20gdGhlIHRlbXBsYXRlIHRvIG1ha2UgYSBxdWVyeSBhbmQgZ2V0IHRoZSBwbGF5ZXJzIGV4aXN0aW5nIGRhdGFcblx0XHR2YXIgZGF0YV9zZXQgPSB7fTtcblx0XHRkYXRhX3NldFtmb3JtX2RhdGEucXVlcnlfdHlwZV0gPSB7XG5cdFx0XHRwcm9jX3R5cGUgOiAnZ2V0X2RhdGEnLFxuXHRcdFx0aWRzIDogZm9ybV9kYXRhLmlkLFxuXHRcdFx0Ly9pZiB0aGVyZSBpcyBhbiBhZG1pbiBrZXkgZm9yIHdvcmtpbmcgd2l0aCB0aGUgZnVsbCBkYXRhIHNldCwgYWRzIGl0XG5cdFx0XHRhZG1pbl9rZXkgOiAoIHR5cGVvZiBnMzY1X3Nlc3NfZGF0YS5hZG1pbl9rZXkgIT09ICd1bmRlZmluZWQnICkgPyBnMzY1X3Nlc3NfZGF0YS5hZG1pbl9rZXkgOiBudWxsXG5cdFx0fVxuXHRcdGczNjVfc2Vydl9jb24oIGRhdGFfc2V0IClcblx0XHQuZG9uZSggZnVuY3Rpb24ocmVzcG9uc2UpIHtcbiAgICAgIGNvbnNvbGUubG9nKHJlc3BvbnNlKTtcblx0XHRcdGlmIChyZXNwb25zZS5zdGF0dXMgPT09ICdzdWNjZXNzJykge1xuXHRcdFx0XHR2YXIgbmV3Q29udGVudF9hbGwgPSAnJztcblx0XHRcdFx0Ly9sb29wIHRocm91Z2ggdGVtcGxhdGUgdmFyaWFibGVzIHJlcGxhY2luZyBlYWNoIHdpdGggZGF0YSBmcm9tIHRoZSBxdWVyeVxuXHRcdFx0XHQkLmVhY2gocmVzcG9uc2UubWVzc2FnZVtmb3JtX2RhdGEucXVlcnlfdHlwZV0sIGZ1bmN0aW9uKGRhdGFfaWQsIGRhdGFfYXJyKXtcblx0XHRcdFx0XHR2YXIgbmV3Q29udGVudF9wYXJ0ID0gbmV3Q29udGVudDtcblx0XHRcdFx0XHRpZiggdHlwZW9mIGczNjVfZm9ybV9kYXRhW2Zvcm1fZGF0YS5xdWVyeV90eXBlXSA9PSAndW5kZWZpbmVkJyApIGczNjVfZm9ybV9kYXRhW2Zvcm1fZGF0YS5xdWVyeV90eXBlXSA9IHt9O1xuXHRcdFx0XHRcdGlmKCB0eXBlb2YgZzM2NV9mb3JtX2RhdGFbZm9ybV9kYXRhLnF1ZXJ5X3R5cGVdW2RhdGFfaWRdID09ICd1bmRlZmluZWQnICkgZzM2NV9mb3JtX2RhdGFbZm9ybV9kYXRhLnF1ZXJ5X3R5cGVdW2RhdGFfaWRdID0ge307XG5cdFx0XHRcdFx0JC5lYWNoKGRhdGFfYXJyLCBmdW5jdGlvbihkYXRhX2tleSwgZGF0YV92YWwpe1xuXHRcdFx0XHRcdFx0bmV3Q29udGVudF9wYXJ0ID0gbmV3Q29udGVudF9wYXJ0LnJlcGxhY2UobmV3IFJlZ0V4cCgne3snICsgZGF0YV9rZXkgKyAnfX0nLCAnaWcnKSwgZGF0YV92YWwpO1xuXHRcdFx0XHRcdFx0ZzM2NV9mb3JtX2RhdGFbZm9ybV9kYXRhLnF1ZXJ5X3R5cGVdW2RhdGFfaWRdW2RhdGFfa2V5XSA9IGRhdGFfdmFsO1xuXHRcdFx0XHRcdH0pO1xuXHRcdFx0XHRcdGczNjVfZm9ybV9kYXRhW2Zvcm1fZGF0YS5xdWVyeV90eXBlXVtkYXRhX2lkXS5mb3JtX3RlbXBsYXRlID0gbmV3Q29udGVudF9wYXJ0O1xuXHRcdFx0XHRcdG5ld0NvbnRlbnRfYWxsICs9IG5ld0NvbnRlbnRfcGFydDtcblx0XHRcdFx0fSk7XG5cdFx0XHRcdG5ld0NvbnRlbnQgPSBuZXdDb250ZW50X2FsbDtcblx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdGNvbnNvbGUubG9nKCdmYWxzZScpO1xuXHRcdFx0XHRuZXdDb250ZW50ID0gcmVzcG9uc2UubWVzc2FnZTtcblx0XHRcdH1cblx0XHR9KVxuXHRcdC5mYWlsKCBmdW5jdGlvbihyZXNwb25zZSkge1xuXHRcdFx0Y29uc29sZS5sb2coJ2Vycm9yJywgcmVzcG9uc2UpO1xuXHRcdFx0bmV3Q29udGVudCA9IHJlc3BvbnNlLnJlc3BvbnNlVGV4dDtcblx0XHR9KVxuXHRcdC5hbHdheXMoIGZ1bmN0aW9uKHJlc3BvbnNlKSB7XG4gICAgICBkZWZPYmoucmVzb2x2ZShuZXdDb250ZW50LnJlcGxhY2UoL3t7KC4rPyl9fS9nLCAnJykpO1xuXHRcdH0pO1xuXHR9XG5cdHJldHVybiBkZWZPYmoucHJvbWlzZSgpO1xufVxuXG4vL2hhbmRsZSBmb3JtIHN1Ym1pdHNcbmZ1bmN0aW9uIGczNjVfaGFuZGxlX2Zvcm1fc3VibWl0KCBmb3JtX2V2ZW50ICl7XG5cdHZhciB0YXJnZXRfZm9ybSA9ICQodGhpcyk7XG5cdHZhciBuZXdDb250ZW50ID0gJyc7XG5cdHZhciBkYXRhX3NldCA9IHt9O1xuXHRkYXRhX3NldFt0YXJnZXRfZm9ybS5hdHRyKCdkYXRhLWczNjVfdHlwZScpXSA9IHtcblx0XHRwcm9jX3R5cGUgOiAncHJvY19kYXRhJyxcblx0XHRmb3JtX2RhdGEgOiB0YXJnZXRfZm9ybS5zZXJpYWxpemUoKSxcblx0XHQvL2lmIHRoZXJlIGlzIGFuIGFkbWluIGtleSBmb3Igd29ya2luZyB3aXRoIHRoZSBmdWxsIGRhdGEgc2V0LCBhZHMgaXRcblx0XHRhZG1pbl9rZXkgOiAoIHR5cGVvZiBnMzY1X3Nlc3NfZGF0YS5hZG1pbl9rZXkgIT09ICd1bmRlZmluZWQnICkgPyBnMzY1X3Nlc3NfZGF0YS5hZG1pbl9rZXkgOiBudWxsXG5cdH1cblx0ZzM2NV9zZXJ2X2NvbiggZGF0YV9zZXQgKVxuXHQuZG9uZSggZnVuY3Rpb24ocmVzcG9uc2UpIHtcbiAgICBpZiAodHlwZW9mIHJlc3BvbnNlLm1lc3NhZ2UgPT09ICdvYmplY3QnKSB7XG4gICAgICAvL2lmIHdlIG5lZWQgYSBnbG9iYWwgc2V0dGluZyBmb3IgYSBzdWNjZXNzIG1lc3NhZ2VcbiAgICAgIGlmIChyZXNwb25zZS5zdGF0dXMgPT09ICdzdWNjZXNzJykge31cbiAgICAgIC8vbG9vcCB0aHJvdWdoIHRlbXBsYXRlIHZhcmlhYmxlcyByZXBsYWNpbmcgZWFjaCB3aXRoIGRhdGEgZnJvbSB0aGUgcXVlcnlcbiAgICAgICQuZWFjaChyZXNwb25zZS5tZXNzYWdlLCBmdW5jdGlvbihkYXRhX3R5cGUsIGRhdGFfcmVzdWx0cykge1xuICAgICAgICB2YXIgcmVzdWx0X3RlbXBsYXRlID0gZzM2NV9mb3JtX2RhdGEuZm9ybVtkYXRhX3R5cGVdLmZvcm1fdGVtcGxhdGVfcmVzdWx0O1xuICAgICAgICAvL21ha2Ugc3VyZSB0aGF0IHRoZSB0ZW1wbGF0ZSBpcyB0aGVyZVxuICAgICAgICBpZiggdHlwZW9mIHJlc3VsdF90ZW1wbGF0ZSAhPT0gJ3N0cmluZycgfHwgcmVzdWx0X3RlbXBsYXRlLmxlbmd0aCA9PT0gMCApIHtcbiAgICAgICAgICBuZXdDb250ZW50ICs9IFwiPHA+Rm9ybSBSZXN1bHQgVGVtcGxhdGUgbWFsZm9ybWF0aW9uIGZvciBcIiArIGRhdGFfdHlwZSArIFwiLiBEYXRhIHdyaXRlIHN1Y2Nlc3NmdWwuPC9wPlwiO1xuICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuICAgICAgICAvL2NoZWNrIGZvciByZXN1bHQgZGF0YVxuICAgICAgICBpZiggdHlwZW9mIGRhdGFfcmVzdWx0cyAhPT0gJ29iamVjdCcgfHwgJC5pc0VtcHR5T2JqZWN0KGRhdGFfcmVzdWx0cykgKSB7XG4gICAgICAgICAgbmV3Q29udGVudCArPSBcIjxwPk5vIHJlc3VsdHMgcmV0dXJuZWQgXCIgKyBkYXRhX3R5cGUgKyBcIi48L3A+XCI7XG4gICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG4gICAgICAgIC8vYnVpbGQgdGhlIHJlc3VsdCBibG9jayBhbmQgdXBkYXRlIG91ciBvYmplY3RcbiAgICAgICAgbmV3Q29udGVudCArPSAnPGRpdj48dWw+JztcbiAgICAgICAgJC5lYWNoKGRhdGFfcmVzdWx0cywgZnVuY3Rpb24oZGF0YV9pZCwgZGF0YV9yZXN1bHQpe1xuICAgICAgICAgIC8vc2V0IHRoZSBjbGFzcyBmb3IgdGhlIHJlc3VsdCBtZXNzYWdlO1xuICAgICAgICAgIHZhciBsaV9jbGFzcyA9ICgvc3VjY2Vzcy9pLnRlc3QoZGF0YV9yZXN1bHQubWVzc2FnZSkpID8gJ3N1Y2Nlc3MnIDogJ2Vycm9yJztcbiAgICAgICAgICAvL2lmIHdlIGhhdmUgYSBwcm9wZXIgaWQsIGZlZWwgZnJlZSB0byB1cGRhdGUgb3VyIGludGVybmFsIGRhdGFcbiAgICAgICAgICBpZiggIWlzTmFOKHBhcnNlSW50KGRhdGFfaWQpKSApIHtcbiAgICAgICAgICAgIGlmKCB0eXBlb2YgZzM2NV9mb3JtX2RhdGFbZGF0YV90eXBlXSA9PSAndW5kZWZpbmVkJyApIGczNjVfZm9ybV9kYXRhW2RhdGFfdHlwZV0gPSB7fTtcbiAgICAgICAgICAgIGlmKCB0eXBlb2YgZzM2NV9mb3JtX2RhdGFbZGF0YV90eXBlXVtkYXRhX2lkXSA9PSAndW5kZWZpbmVkJyApIHtcbiAgICAgICAgICAgICAgZzM2NV9mb3JtX2RhdGFbZGF0YV90eXBlXVtkYXRhX2lkXSA9IHt9O1xuICAgICAgICAgICAgICAvL2Fsc28gYWRkIHRoZSBpZCB0byB0aGUgZm9ybSBpbmNhc2UgdGhlIHVzZXIgc3VibWl0cyBhZ2Fpbi5cbiAgICAgICAgICAgICAgJCgnIycgKyBkYXRhX3Jlc3VsdC53cmFwcGVyX2lkICsgJ19pZCcpLnZhbChkYXRhX2lkKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgICQuZWFjaChkYXRhX3Jlc3VsdCwgZnVuY3Rpb24oZGF0YV9rZXksIGRhdGFfdmFsKXtcbiAgICAgICAgICAgICAgZzM2NV9mb3JtX2RhdGFbZGF0YV90eXBlXVtkYXRhX2lkXVtkYXRhX2tleV0gPSBkYXRhX3ZhbDtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgLy9wcm9jZXNzIHRoZSByZXN1bHQsIGVycm9yIG9yIHN1Y2Nlc3NcbiAgICAgICAgICAgIG5ld0NvbnRlbnQgKz0gcmVzdWx0X3RlbXBsYXRlLnJlcGxhY2UobmV3IFJlZ0V4cCgne3tsaV9jbGFzc319JywgJ2cnKSwgbGlfY2xhc3MgKS5yZXBsYWNlKG5ldyBSZWdFeHAoJ3t7cmVzdWx0X3RpdGxlfX0nLCAnZycpLCBnMzY1X2Zvcm1fZGF0YVtkYXRhX3R5cGVdW2RhdGFfaWRdLmVsZW1lbnRfdGl0bGUpLnJlcGxhY2UobmV3IFJlZ0V4cCgne3tyZXN1bHRfc3RhdHVzfX0nLCAnZycpLCBkYXRhX3Jlc3VsdC5tZXNzYWdlKTtcbiAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgLy9zZXQgdGhlIHRhcmdldFxuICAgICAgICAgICAgLy9wcm9jZXNzIHRoZSByZXN1bHQsIGVycm9yIG9yIHN1Y2Nlc3NcbiAgICAgICAgICAgIG5ld0NvbnRlbnQgKz0gcmVzdWx0X3RlbXBsYXRlLnJlcGxhY2UobmV3IFJlZ0V4cCgne3tsaV9jbGFzc319JywgJ2cnKSwgbGlfY2xhc3MgKS5yZXBsYWNlKG5ldyBSZWdFeHAoJ3t7cmVzdWx0X3RpdGxlfX0nLCAnZycpLCAkKCcjJyArIGRhdGFfaWQgKyAnX2ZpZWxkc2V0IC5jaGFuZ2UtdGl0bGUnKS5odG1sKCkpLnJlcGxhY2UobmV3IFJlZ0V4cCgne3tyZXN1bHRfc3RhdHVzfX0nLCAnZycpLCBkYXRhX3Jlc3VsdC5tZXNzYWdlKTtcbiAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgICAgICBuZXdDb250ZW50ICs9ICc8L3VsPjwvcD4nO1xuICAgICAgICAvL2lmIHdlIGFyZSBhIG5lc3RlZCBmb3JtLCBwdXNoIG91ciByZXN1bHRzIHRvIHRoZSBhcHByb3ByaWF0ZSBvcmdpbiBmaWVsZHMgYW5kIHdyYXAgdXAgdGhlIG5lc3RlZCBmb3JtXG4gICAgICAgIC8vd2UgZXhlY3V0ZSB0aGlzIGFmdGVyIHRoZSBhYm92ZSBlYWNoIGJsb2NrIHRvIGdldCB0aGUgZm9ybSBkYXRhIG9iamVjdCB1cGRhdGVkIGJlZm9yZSB3ZSBleGl0XG4gICAgICAgIHZhciB0YXJnZXRfZm9ybV9lbGVtZW50ID0gJCgnIycgKyB0YXJnZXRfZm9ybS5hdHRyKCdkYXRhLXRhcmdldF9maWVsZCcpKTtcbiAgICAgICAgaWYoIHRhcmdldF9mb3JtX2VsZW1lbnQubGVuZ3RoICl7XG4gICAgICAgICAgLy9vbmx5IGV2YWx1YXRlIHRoZSBmaXJzdCBlbnRyeSwgd2Ugb25seSBoYXZlIG9uZSByZWZlcmVuY2UgdG8gcGFzcyBkYXRhIGJhY2sgdG8uXG4gICAgICAgICAgdmFyIHJlc3BvbnNlX3JlZmVyZW5jZSA9IGRhdGFfcmVzdWx0c1skLm1hcChkYXRhX3Jlc3VsdHMsIGZ1bmN0aW9uKHZhbHVlLCBrZXkpe3JldHVybiBrZXk7fSlbMF1dO1xuICAgICAgICAgIC8vaWYgd2UgaGF2ZSBzdWNjZXNzXG4gICAgICAgICAgaWYoIHR5cGVvZiByZXNwb25zZV9yZWZlcmVuY2UuaWQgIT09ICd1bmRlZmluZWQnICkge1xuICAgICAgICAgICAgLy9zZXQgdGhlIHZhbml0eSBmaWVsZFxuICAgICAgICAgICAgdGFyZ2V0X2Zvcm1fZWxlbWVudC52YWwocmVzcG9uc2VfcmVmZXJlbmNlLmVsZW1lbnRfdGl0bGUpLnRyaWdnZXIoJ2FqYXhsaXZlc2VhcmNoOmhpZGVfcmVzdWx0Jyk7XG4gICAgICAgICAgICAvL3NldCB0aGUgcHJvcGVyIHRhcmdldCB3aXRoIHRoZSBuZXcgaWRcbiAgICAgICAgICAgICQoJyMnICsgdGFyZ2V0X2Zvcm1fZWxlbWVudC5hdHRyKCdkYXRhLWxzX3RhcmdldCcpKS52YWwocmVzcG9uc2VfcmVmZXJlbmNlLmlkKTtcbiAgICAgICAgICAgIC8vb3BlbiB0aGUgc2VhcmNoIGZpZWxkIGFnYWluXG4gICAgICAgICAgICB0YXJnZXRfZm9ybV9lbGVtZW50LnBhcmVudCgpLnNsaWRlRG93bigpO1xuICAgICAgICAgICAgLy9kZWxldGUgdGhlIGZvcm1fZXZlbnRcbiAgICAgICAgICAgIHRhcmdldF9mb3JtLnBhcmVudCgpLmZhZGVPdXQoNDAwLCAnc3dpbmcnLCBmdW5jdGlvbigpeyB0YXJnZXRfZm9ybS5wYXJlbnQoKS5yZW1vdmUoKTsgfSk7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgfVxuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIGczNjVfZm9ybV9tZXNzYWdlX2NsZWFyKCB0YXJnZXRfZm9ybSApO1xuICAgICAgICB9XG4gICAgICB9KTtcblx0XHR9IGVsc2Uge1xuXHRcdFx0Y29uc29sZS5sb2coJ2ZhbHNlJyk7XG5cdFx0XHRuZXdDb250ZW50ID0gJzxwPicgKyByZXNwb25zZS5tZXNzYWdlICsgJzwvcD4nO1xuXHRcdH1cblx0fSlcblx0LmZhaWwoIGZ1bmN0aW9uKHJlc3BvbnNlKSB7XG5cdFx0Y29uc29sZS5sb2coJ2Vycm9yJywgcmVzcG9uc2UpO1xuXHRcdG5ld0NvbnRlbnQgPSAnPHA+JyArIHJlc3BvbnNlLnJlc3BvbnNlVGV4dCArICc8L3A+Jztcblx0fSlcblx0LmFsd2F5cyggZnVuY3Rpb24ocmVzcG9uc2UpIHtcblx0XHR2YXIgbWVzc2FnZV93cmFwcGVyID0gJCgnIycgKyB0YXJnZXRfZm9ybS5hdHRyKCdpZCcpICsgJ19tZXNzYWdlJywgdGFyZ2V0X2Zvcm0pO1xuICAgIGlmKCBtZXNzYWdlX3dyYXBwZXIubGVuZ3RoICkgbWVzc2FnZV93cmFwcGVyLmh0bWwobmV3Q29udGVudCkucmVtb3ZlQ2xhc3MoJ2hpZGRlbl9lbGVtZW50Jyk7XG5cdH0pO1xuXHRyZXR1cm4gZmFsc2U7XG59XG5cbi8vZm9ybSBpbml0XG5mdW5jdGlvbiBnMzY1X2Zvcm1fc3RhcnQoIHRhcmdldCApIHtcbiAgLy9tYWtlIHN1cmUgdGhhdCB3ZSBoYXZlIHRoZSBzY3JpcHRzIHRvIG1hbmFnZSBmb3Jtc1xuICBnMzY1X3NldF9zY3JpcHQoKTtcbiAgLy9jaGVjayB0aGF0IHRoZSBwcm9jZXNzaW5nIGZsYWcgaXMgc2V0IFxuICBpZiggZzM2NV9zY3JpcHRfYW5jaG9yID09PSBmYWxzZSApIHJldHVybiBcIk1pc3NpbmcgZ2xvYmFsIGNvbm5lY3Rvci5cIjtcbiAgLy9nZXQgdHlwZXMgZnJvbSB0aGUgZWxlbWVudFxuXHR2YXIgZGF0YV9zZXQgPSB7fTtcbiAgLy9zZXR1cCB2YXJzXG4gIGlmKCB0eXBlb2YgZzM2NV9mb3JtX2RhdGEuZm9ybSA9PT0gJ3VuZGVmaW5lZCcgKSBnMzY1X2Zvcm1fZGF0YS5mb3JtID0ge307XG4gIC8vc2V0L3Jlc2V0IGVycm9yIHN0YXR1cy9jb2RlXG4gIGczNjVfZm9ybV9kYXRhLmVycm9yID0gJyc7XG4gIFxuICAvL2Z1bmN0aW9uIGZvciBwcm9jZXNzaW5nIHRoZSByZXF1ZXN0IGRhdGEgdHJlZVxuICBmdW5jdGlvbiBnMzY1X3Byb2Nfc3RhcnRfZGF0YShlKXtcbiAgICAvL25lZWQgdGhlIHR5cGUgdmFyIHRvIHNlbmRcbiAgICBpZiggZSA9PT0gJycgfHwgZSA9PT0gbnVsbCB8fCBlID09PSAndW5kZWZpbmVkJyApIHtcbiAgICAgIGNvbnNvbGUubG9nKCAnTmVlZCBwcm9wZXIgZm9ybSB0eXBlIHZhcmlhYmxlLicgKTtcbiAgICAgIHJldHVybiBmYWxzZTtcbiAgICB9XG4gICAgLy9pZiB3ZSBoYXZlIGlkcyBvbiB0aGUgZWxlbWVudCwgaW5jbHVkZSB0aGVtXG4gICAgZSA9IGUuc3BsaXQoJywnKTtcbiAgICBlLmZvckVhY2goZnVuY3Rpb24oZWwpe1xuICAgICAgaWYoIGVsID09IGVbMF0gKSB7XG4gICAgICAgIGRhdGFfc2V0W2VsXSA9IHtcbiAgICAgICAgICBwcm9jX3R5cGU6ICdnZXRfZm9ybScsXG4gICAgICAgICAgaWRzOiBbXVxuICAgICAgICB9O1xuICAgICAgICByZXR1cm47XG4gICAgICB9XG4gICAgICBkYXRhX3NldFtlWzBdXS5pZHMucHVzaChlbCk7XG4gICAgfSk7XG4gIH1cbiAgXG4gIGZ1bmN0aW9uIGczNjVfaW5uZXJfZm9ybV9zdGFydCggZXJyb3JfbWVzc2FnZSApe1xuICAgIGlmKCB0eXBlb2YgZXJyb3JfbWVzc2FnZSAhPT0gJ3VuZGVmaW5lZCcgKSBnMzY1X2Zvcm1fZGF0YS5lcnJvciA9IGVycm9yX21lc3NhZ2U7XG4gICAgaWYoIHR5cGVvZiBnMzY1X2Zvcm1fZGF0YS53cmFwcGVyID09PSAndW5kZWZpbmVkJyApIGczNjVfZm9ybV9kYXRhLndyYXBwZXIgPSAkKFwiPGRpdiBpZD0nZzM2NV9mb3JtX3dyYXAnIGNsYXNzPSdnMzY1X2Zvcm1fd3JhcCc+PC9kaXY+XCIpLmluc2VydEJlZm9yZSh0YXJnZXQpO1xuICAgIGlmKCBnMzY1X2Zvcm1fZGF0YS5lcnJvciA9PT0gJycgKSB7XG4gICAgICAkLmVhY2goIGRhdGFfc2V0LCBmdW5jdGlvbigga2V5LCB2YWx1ZSApIHtcbiAgICAgICAgdmFyIGN1cnJlbnRfZm9ybSA9ICQoIGczNjVfZm9ybV9kYXRhLmZvcm1ba2V5XS5mb3JtX3RlbXBsYXRlX2luaXQgKS5hcHBlbmRUbyggZzM2NV9mb3JtX2RhdGEud3JhcHBlciApO1xuICAgICAgICBnMzY1X2Zvcm1fc3RhcnRfdXAoIGN1cnJlbnRfZm9ybSApO1xuICAgICAgfSk7XG4gICAgfSBlbHNlIHtcbiAgICAgIGczNjVfZm9ybV9kYXRhLndyYXBwZXIuaHRtbCggZzM2NV9mb3JtX2RhdGEuZXJyb3IgKTtcbiAgICB9XG4gICAgaWYoIHR5cGVvZiBlcnJvcl9tZXNzYWdlICE9PSAndW5kZWZpbmVkJyApIHJldHVybiBmYWxzZTtcbiAgICByZXR1cm4gdHJ1ZTtcbiAgfVxuXG4gIC8vaWYgd2UgaGF2ZSBhIHRhcmdldCwgdXNlIHRoYXQgZm9yIGluZm9cbiAgdGFyZ2V0ID0gKCB0eXBlb2YgdGFyZ2V0ICE9ICd1bmRlZmluZWQnICkgPyBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCggdGFyZ2V0LndyYXBwZXJfdGFyZ2V0ICkgOiBnMzY1X3NjcmlwdF9lbGVtZW50O1xuICBpZiggdHlwZW9mIHRhcmdldCA9PSAndW5kZWZpbmVkJyApIGlmKCBnMzY1X2lubmVyX2Zvcm1fc3RhcnQoXCJNaXNzaW5nIGZvcm0gd3JhcHBlciB0YXJnZXQuXCIpID09PSBmYWxzZSApIHJldHVybiBmYWxzZTtcblxuICAvL3Byb2Nlc3MgdGhlIGRhdGEgZnJvbSB0aGUgc3VwcGxpZWQgdGFyZ2V0XG4gIHZhciB0YXJnZXRfdHlwZSA9IHRhcmdldC5kYXRhc2V0LmczNjVfdHlwZVxuICBpZiggdHlwZW9mIHRhcmdldCA9PSAndW5kZWZpbmVkJyApIGlmKCBnMzY1X2lubmVyX2Zvcm1fc3RhcnQoXCJNaXNzaW5nIGZvcm0gZGF0YXR5cGVzLlwiKSA9PT0gZmFsc2UgKSByZXR1cm4gZmFsc2U7XG4gIFxuICAvL2lmIHdlIGhhdmUgdGhlIGluZm8sIHByb2Nlc3MgaXRcbiAgdGFyZ2V0X3R5cGUuc3BsaXQoJ3wnKS5mb3JFYWNoKGczNjVfcHJvY19zdGFydF9kYXRhKTtcbiAgXG4gIC8vaWYgd2UgZG9uJ3QgaGF2ZSBhbnkgdHlwZXMgZ2V0IG91dFxuICBpZiggT2JqZWN0LmtleXMoZGF0YV9zZXQpLmxlbmd0aCA9PT0gMCApIGlmKCBnMzY1X2lubmVyX2Zvcm1fc3RhcnQoXCJFcnJvciBwYXJzaW5nIGZvcm0gdHlwZXMuXCIpID09PSBmYWxzZSApIHJldHVybiBmYWxzZTtcbiAgLy9hZGQgZ2xvYmFsIHNldHRpbmdzIGlmIG5lZWRlZFxuICB2YXIgc2V0dGluZ3MgPSB7fTtcbiAgLy9zdHlsZSBzd2l0Y2ggZm9yIGV4dGVybmFsIGRvbWFpbnMsIHN0eWxlcyBuZXZlciBsb2FkIG9uIGczNjUgZG9tYWluc1xuICBpZiggZzM2NV9zY3JpcHRfZWxlbWVudC5kYXRhc2V0LmczNjVfc3R5bGVzID09ICdmYWxzZScgKSBzZXR0aW5ncy5zdHlsZXMgPSAnZmFsc2UnO1xuICAvL3NldHRpbmdzIGRlZmF1bHQgdmFsdWVcbiAgaWYoIE9iamVjdC5rZXlzKHNldHRpbmdzKS5sZW5ndGggPT09IDAgKSBzZXR0aW5ncyA9IG51bGw7XG4gIC8vZ2V0IHRoZSBmb3JtIHRlbXBsYXRlc1xuICBnMzY1X3NlcnZfY29uKCBkYXRhX3NldCwgc2V0dGluZ3MgKVxuICAuZG9uZSggZnVuY3Rpb24ocmVzcG9uc2UpIHtcbiAgICBpZiAocmVzcG9uc2Uuc3RhdHVzID09PSAnc3VjY2VzcycpIHtcbiAgICAgIC8vbG9vcCB0aHJvdWdoIHJlc3BvbnNlIGFuZCBzZWxlY3RpdmVseSBhZGQgZGF0YSB0byBvdXIgdHJlZVxuICAgICAgJC5lYWNoKCByZXNwb25zZS5tZXNzYWdlLCBmdW5jdGlvbiggdHlwZV9rZXksIHR5cGVfdmFsdWVzICkge1xuXHRcdFx0XHRpZiggdHlwZW9mIGczNjVfZm9ybV9kYXRhLmZvcm1bdHlwZV9rZXldID09PSAndW5kZWZpbmVkJyApIGczNjVfZm9ybV9kYXRhLmZvcm1bdHlwZV9rZXldID0ge307XG4gICAgICAgICQuZWFjaCggdHlwZV92YWx1ZXMsIGZ1bmN0aW9uKCB2YWx1ZXNfa2V5LCB2YWx1ZSApIHtcblx0ICBcdFx0XHRnMzY1X2Zvcm1fZGF0YS5mb3JtW3R5cGVfa2V5XVt2YWx1ZXNfa2V5XSA9IHZhbHVlO1xuICBcdFx0XHR9KTtcblx0XHRcdH0pO1xuICAgICAgLy9wb3NzaWJseSBwZXJmb3JtIGFub3RoZXIgY2hlY2sgaGVyZVxuICAgIH0gZWxzZSB7XG4gICAgICBjb25zb2xlLmxvZygnZmFsc2UnLCByZXNwb25zZSk7XG4gICAgICBnMzY1X2Zvcm1fZGF0YS5lcnJvciA9IHJlc3BvbnNlLm1lc3NhZ2U7XG4gICAgfVxuICB9KVxuICAuZmFpbCggZnVuY3Rpb24ocmVzcG9uc2UpIHtcbiAgICBjb25zb2xlLmxvZygnZXJyb3InLCByZXNwb25zZSk7XG4gICAgZzM2NV9mb3JtX2RhdGEuZXJyb3IgPSByZXNwb25zZS5yZXNwb25zZVRleHQ7XG4gIH0pXG4gIC5hbHdheXMoIGZ1bmN0aW9uKHJlc3BvbnNlKSB7XG4gICAgZzM2NV9pbm5lcl9mb3JtX3N0YXJ0KCk7XG4gICAgaWYoIHR5cGVvZiByZXNwb25zZS5zdHlsZSAhPT0gJ3VuZGVmaW5lZCcgKSBnMzY1X2Zvcm1fZGF0YS53cmFwcGVyLnByZXBlbmQoIHJlc3BvbnNlLnN0eWxlICk7XG4gIH0pO1xufVxuLy9pZiB0aGUgc2Vzc2lvbiBoYXNuJ3QgZmlyZWQgYWRkIGl0IHRvIHRoZSBsaXN0LCBvdGhlcndpc2Ugc3RhcnQgdGhlIGZvcm0gaW1tZWlkYXRlbHlcbmlmKCBnMzY1X2Z1bmNfd3JhcHBlci5zZXNzX2luaXQgPT09IDEgKSB7XG4gICggT2JqZWN0LmtleXMoZzM2NV9mb3JtX2RldGFpbHMuaXRlbXMpLmxlbmd0aCA+IDAgKSA/IGczNjVfZm9ybV9zdGFydCggZzM2NV9mb3JtX2RldGFpbHMgKSA6IGczNjVfZm9ybV9zdGFydCgpO1xufSBlbHNlIHtcbiAgLy9zZXQgZm9ybSBmdW5jdGlvbiB0byBpbml0IGFmdGVyIHNlc3Npb24gZGF0YSBpcyBsb2FkZWQgb3IgdmVyaWZpZWRcbiAgKCBPYmplY3Qua2V5cyhnMzY1X2Zvcm1fZGV0YWlscy5pdGVtcykubGVuZ3RoID4gMCApID8gIGczNjVfZnVuY193cmFwcGVyLnNlc3NbZzM2NV9mdW5jX3dyYXBwZXIuc2Vzcy5sZW5ndGhdID0ge25hbWUgOiBnMzY1X2Zvcm1fc3RhcnQsIGFyZ3MgOiBbZzM2NV9mb3JtX2RldGFpbHNdfSA6IGczNjVfZnVuY193cmFwcGVyLnNlc3NbZzM2NV9mdW5jX3dyYXBwZXIuc2Vzcy5sZW5ndGhdID0ge25hbWUgOiBnMzY1X2Zvcm1fc3RhcnQsIGFyZ3MgOiBbXX07XG59IiwiKGZ1bmN0aW9uKCkge1xuXG4gICAgdmFyIGRlYnVnID0gZmFsc2U7XG5cbiAgICB2YXIgcm9vdCA9IHRoaXM7XG5cbiAgICB2YXIgRVhJRiA9IGZ1bmN0aW9uKG9iaikge1xuICAgICAgICBpZiAob2JqIGluc3RhbmNlb2YgRVhJRikgcmV0dXJuIG9iajtcbiAgICAgICAgaWYgKCEodGhpcyBpbnN0YW5jZW9mIEVYSUYpKSByZXR1cm4gbmV3IEVYSUYob2JqKTtcbiAgICAgICAgdGhpcy5FWElGd3JhcHBlZCA9IG9iajtcbiAgICB9O1xuXG4gICAgaWYgKHR5cGVvZiBleHBvcnRzICE9PSAndW5kZWZpbmVkJykge1xuICAgICAgICBpZiAodHlwZW9mIG1vZHVsZSAhPT0gJ3VuZGVmaW5lZCcgJiYgbW9kdWxlLmV4cG9ydHMpIHtcbiAgICAgICAgICAgIGV4cG9ydHMgPSBtb2R1bGUuZXhwb3J0cyA9IEVYSUY7XG4gICAgICAgIH1cbiAgICAgICAgZXhwb3J0cy5FWElGID0gRVhJRjtcbiAgICB9IGVsc2Uge1xuICAgICAgICByb290LkVYSUYgPSBFWElGO1xuICAgIH1cblxuICAgIHZhciBFeGlmVGFncyA9IEVYSUYuVGFncyA9IHtcblxuICAgICAgICAvLyB2ZXJzaW9uIHRhZ3NcbiAgICAgICAgMHg5MDAwIDogXCJFeGlmVmVyc2lvblwiLCAgICAgICAgICAgICAvLyBFWElGIHZlcnNpb25cbiAgICAgICAgMHhBMDAwIDogXCJGbGFzaHBpeFZlcnNpb25cIiwgICAgICAgICAvLyBGbGFzaHBpeCBmb3JtYXQgdmVyc2lvblxuXG4gICAgICAgIC8vIGNvbG9yc3BhY2UgdGFnc1xuICAgICAgICAweEEwMDEgOiBcIkNvbG9yU3BhY2VcIiwgICAgICAgICAgICAgIC8vIENvbG9yIHNwYWNlIGluZm9ybWF0aW9uIHRhZ1xuXG4gICAgICAgIC8vIGltYWdlIGNvbmZpZ3VyYXRpb25cbiAgICAgICAgMHhBMDAyIDogXCJQaXhlbFhEaW1lbnNpb25cIiwgICAgICAgICAvLyBWYWxpZCB3aWR0aCBvZiBtZWFuaW5nZnVsIGltYWdlXG4gICAgICAgIDB4QTAwMyA6IFwiUGl4ZWxZRGltZW5zaW9uXCIsICAgICAgICAgLy8gVmFsaWQgaGVpZ2h0IG9mIG1lYW5pbmdmdWwgaW1hZ2VcbiAgICAgICAgMHg5MTAxIDogXCJDb21wb25lbnRzQ29uZmlndXJhdGlvblwiLCAvLyBJbmZvcm1hdGlvbiBhYm91dCBjaGFubmVsc1xuICAgICAgICAweDkxMDIgOiBcIkNvbXByZXNzZWRCaXRzUGVyUGl4ZWxcIiwgIC8vIENvbXByZXNzZWQgYml0cyBwZXIgcGl4ZWxcblxuICAgICAgICAvLyB1c2VyIGluZm9ybWF0aW9uXG4gICAgICAgIDB4OTI3QyA6IFwiTWFrZXJOb3RlXCIsICAgICAgICAgICAgICAgLy8gQW55IGRlc2lyZWQgaW5mb3JtYXRpb24gd3JpdHRlbiBieSB0aGUgbWFudWZhY3R1cmVyXG4gICAgICAgIDB4OTI4NiA6IFwiVXNlckNvbW1lbnRcIiwgICAgICAgICAgICAgLy8gQ29tbWVudHMgYnkgdXNlclxuXG4gICAgICAgIC8vIHJlbGF0ZWQgZmlsZVxuICAgICAgICAweEEwMDQgOiBcIlJlbGF0ZWRTb3VuZEZpbGVcIiwgICAgICAgIC8vIE5hbWUgb2YgcmVsYXRlZCBzb3VuZCBmaWxlXG5cbiAgICAgICAgLy8gZGF0ZSBhbmQgdGltZVxuICAgICAgICAweDkwMDMgOiBcIkRhdGVUaW1lT3JpZ2luYWxcIiwgICAgICAgIC8vIERhdGUgYW5kIHRpbWUgd2hlbiB0aGUgb3JpZ2luYWwgaW1hZ2Ugd2FzIGdlbmVyYXRlZFxuICAgICAgICAweDkwMDQgOiBcIkRhdGVUaW1lRGlnaXRpemVkXCIsICAgICAgIC8vIERhdGUgYW5kIHRpbWUgd2hlbiB0aGUgaW1hZ2Ugd2FzIHN0b3JlZCBkaWdpdGFsbHlcbiAgICAgICAgMHg5MjkwIDogXCJTdWJzZWNUaW1lXCIsICAgICAgICAgICAgICAvLyBGcmFjdGlvbnMgb2Ygc2Vjb25kcyBmb3IgRGF0ZVRpbWVcbiAgICAgICAgMHg5MjkxIDogXCJTdWJzZWNUaW1lT3JpZ2luYWxcIiwgICAgICAvLyBGcmFjdGlvbnMgb2Ygc2Vjb25kcyBmb3IgRGF0ZVRpbWVPcmlnaW5hbFxuICAgICAgICAweDkyOTIgOiBcIlN1YnNlY1RpbWVEaWdpdGl6ZWRcIiwgICAgIC8vIEZyYWN0aW9ucyBvZiBzZWNvbmRzIGZvciBEYXRlVGltZURpZ2l0aXplZFxuXG4gICAgICAgIC8vIHBpY3R1cmUtdGFraW5nIGNvbmRpdGlvbnNcbiAgICAgICAgMHg4MjlBIDogXCJFeHBvc3VyZVRpbWVcIiwgICAgICAgICAgICAvLyBFeHBvc3VyZSB0aW1lIChpbiBzZWNvbmRzKVxuICAgICAgICAweDgyOUQgOiBcIkZOdW1iZXJcIiwgICAgICAgICAgICAgICAgIC8vIEYgbnVtYmVyXG4gICAgICAgIDB4ODgyMiA6IFwiRXhwb3N1cmVQcm9ncmFtXCIsICAgICAgICAgLy8gRXhwb3N1cmUgcHJvZ3JhbVxuICAgICAgICAweDg4MjQgOiBcIlNwZWN0cmFsU2Vuc2l0aXZpdHlcIiwgICAgIC8vIFNwZWN0cmFsIHNlbnNpdGl2aXR5XG4gICAgICAgIDB4ODgyNyA6IFwiSVNPU3BlZWRSYXRpbmdzXCIsICAgICAgICAgLy8gSVNPIHNwZWVkIHJhdGluZ1xuICAgICAgICAweDg4MjggOiBcIk9FQ0ZcIiwgICAgICAgICAgICAgICAgICAgIC8vIE9wdG9lbGVjdHJpYyBjb252ZXJzaW9uIGZhY3RvclxuICAgICAgICAweDkyMDEgOiBcIlNodXR0ZXJTcGVlZFZhbHVlXCIsICAgICAgIC8vIFNodXR0ZXIgc3BlZWRcbiAgICAgICAgMHg5MjAyIDogXCJBcGVydHVyZVZhbHVlXCIsICAgICAgICAgICAvLyBMZW5zIGFwZXJ0dXJlXG4gICAgICAgIDB4OTIwMyA6IFwiQnJpZ2h0bmVzc1ZhbHVlXCIsICAgICAgICAgLy8gVmFsdWUgb2YgYnJpZ2h0bmVzc1xuICAgICAgICAweDkyMDQgOiBcIkV4cG9zdXJlQmlhc1wiLCAgICAgICAgICAgIC8vIEV4cG9zdXJlIGJpYXNcbiAgICAgICAgMHg5MjA1IDogXCJNYXhBcGVydHVyZVZhbHVlXCIsICAgICAgICAvLyBTbWFsbGVzdCBGIG51bWJlciBvZiBsZW5zXG4gICAgICAgIDB4OTIwNiA6IFwiU3ViamVjdERpc3RhbmNlXCIsICAgICAgICAgLy8gRGlzdGFuY2UgdG8gc3ViamVjdCBpbiBtZXRlcnNcbiAgICAgICAgMHg5MjA3IDogXCJNZXRlcmluZ01vZGVcIiwgICAgICAgICAgICAvLyBNZXRlcmluZyBtb2RlXG4gICAgICAgIDB4OTIwOCA6IFwiTGlnaHRTb3VyY2VcIiwgICAgICAgICAgICAgLy8gS2luZCBvZiBsaWdodCBzb3VyY2VcbiAgICAgICAgMHg5MjA5IDogXCJGbGFzaFwiLCAgICAgICAgICAgICAgICAgICAvLyBGbGFzaCBzdGF0dXNcbiAgICAgICAgMHg5MjE0IDogXCJTdWJqZWN0QXJlYVwiLCAgICAgICAgICAgICAvLyBMb2NhdGlvbiBhbmQgYXJlYSBvZiBtYWluIHN1YmplY3RcbiAgICAgICAgMHg5MjBBIDogXCJGb2NhbExlbmd0aFwiLCAgICAgICAgICAgICAvLyBGb2NhbCBsZW5ndGggb2YgdGhlIGxlbnMgaW4gbW1cbiAgICAgICAgMHhBMjBCIDogXCJGbGFzaEVuZXJneVwiLCAgICAgICAgICAgICAvLyBTdHJvYmUgZW5lcmd5IGluIEJDUFNcbiAgICAgICAgMHhBMjBDIDogXCJTcGF0aWFsRnJlcXVlbmN5UmVzcG9uc2VcIiwgICAgLy9cbiAgICAgICAgMHhBMjBFIDogXCJGb2NhbFBsYW5lWFJlc29sdXRpb25cIiwgICAvLyBOdW1iZXIgb2YgcGl4ZWxzIGluIHdpZHRoIGRpcmVjdGlvbiBwZXIgRm9jYWxQbGFuZVJlc29sdXRpb25Vbml0XG4gICAgICAgIDB4QTIwRiA6IFwiRm9jYWxQbGFuZVlSZXNvbHV0aW9uXCIsICAgLy8gTnVtYmVyIG9mIHBpeGVscyBpbiBoZWlnaHQgZGlyZWN0aW9uIHBlciBGb2NhbFBsYW5lUmVzb2x1dGlvblVuaXRcbiAgICAgICAgMHhBMjEwIDogXCJGb2NhbFBsYW5lUmVzb2x1dGlvblVuaXRcIiwgICAgLy8gVW5pdCBmb3IgbWVhc3VyaW5nIEZvY2FsUGxhbmVYUmVzb2x1dGlvbiBhbmQgRm9jYWxQbGFuZVlSZXNvbHV0aW9uXG4gICAgICAgIDB4QTIxNCA6IFwiU3ViamVjdExvY2F0aW9uXCIsICAgICAgICAgLy8gTG9jYXRpb24gb2Ygc3ViamVjdCBpbiBpbWFnZVxuICAgICAgICAweEEyMTUgOiBcIkV4cG9zdXJlSW5kZXhcIiwgICAgICAgICAgIC8vIEV4cG9zdXJlIGluZGV4IHNlbGVjdGVkIG9uIGNhbWVyYVxuICAgICAgICAweEEyMTcgOiBcIlNlbnNpbmdNZXRob2RcIiwgICAgICAgICAgIC8vIEltYWdlIHNlbnNvciB0eXBlXG4gICAgICAgIDB4QTMwMCA6IFwiRmlsZVNvdXJjZVwiLCAgICAgICAgICAgICAgLy8gSW1hZ2Ugc291cmNlICgzID09IERTQylcbiAgICAgICAgMHhBMzAxIDogXCJTY2VuZVR5cGVcIiwgICAgICAgICAgICAgICAvLyBTY2VuZSB0eXBlICgxID09IGRpcmVjdGx5IHBob3RvZ3JhcGhlZClcbiAgICAgICAgMHhBMzAyIDogXCJDRkFQYXR0ZXJuXCIsICAgICAgICAgICAgICAvLyBDb2xvciBmaWx0ZXIgYXJyYXkgZ2VvbWV0cmljIHBhdHRlcm5cbiAgICAgICAgMHhBNDAxIDogXCJDdXN0b21SZW5kZXJlZFwiLCAgICAgICAgICAvLyBTcGVjaWFsIHByb2Nlc3NpbmdcbiAgICAgICAgMHhBNDAyIDogXCJFeHBvc3VyZU1vZGVcIiwgICAgICAgICAgICAvLyBFeHBvc3VyZSBtb2RlXG4gICAgICAgIDB4QTQwMyA6IFwiV2hpdGVCYWxhbmNlXCIsICAgICAgICAgICAgLy8gMSA9IGF1dG8gd2hpdGUgYmFsYW5jZSwgMiA9IG1hbnVhbFxuICAgICAgICAweEE0MDQgOiBcIkRpZ2l0YWxab29tUmF0aW9uXCIsICAgICAgIC8vIERpZ2l0YWwgem9vbSByYXRpb1xuICAgICAgICAweEE0MDUgOiBcIkZvY2FsTGVuZ3RoSW4zNW1tRmlsbVwiLCAgIC8vIEVxdWl2YWxlbnQgZm9hY2wgbGVuZ3RoIGFzc3VtaW5nIDM1bW0gZmlsbSBjYW1lcmEgKGluIG1tKVxuICAgICAgICAweEE0MDYgOiBcIlNjZW5lQ2FwdHVyZVR5cGVcIiwgICAgICAgIC8vIFR5cGUgb2Ygc2NlbmVcbiAgICAgICAgMHhBNDA3IDogXCJHYWluQ29udHJvbFwiLCAgICAgICAgICAgICAvLyBEZWdyZWUgb2Ygb3ZlcmFsbCBpbWFnZSBnYWluIGFkanVzdG1lbnRcbiAgICAgICAgMHhBNDA4IDogXCJDb250cmFzdFwiLCAgICAgICAgICAgICAgICAvLyBEaXJlY3Rpb24gb2YgY29udHJhc3QgcHJvY2Vzc2luZyBhcHBsaWVkIGJ5IGNhbWVyYVxuICAgICAgICAweEE0MDkgOiBcIlNhdHVyYXRpb25cIiwgICAgICAgICAgICAgIC8vIERpcmVjdGlvbiBvZiBzYXR1cmF0aW9uIHByb2Nlc3NpbmcgYXBwbGllZCBieSBjYW1lcmFcbiAgICAgICAgMHhBNDBBIDogXCJTaGFycG5lc3NcIiwgICAgICAgICAgICAgICAvLyBEaXJlY3Rpb24gb2Ygc2hhcnBuZXNzIHByb2Nlc3NpbmcgYXBwbGllZCBieSBjYW1lcmFcbiAgICAgICAgMHhBNDBCIDogXCJEZXZpY2VTZXR0aW5nRGVzY3JpcHRpb25cIiwgICAgLy9cbiAgICAgICAgMHhBNDBDIDogXCJTdWJqZWN0RGlzdGFuY2VSYW5nZVwiLCAgICAvLyBEaXN0YW5jZSB0byBzdWJqZWN0XG5cbiAgICAgICAgLy8gb3RoZXIgdGFnc1xuICAgICAgICAweEEwMDUgOiBcIkludGVyb3BlcmFiaWxpdHlJRkRQb2ludGVyXCIsXG4gICAgICAgIDB4QTQyMCA6IFwiSW1hZ2VVbmlxdWVJRFwiICAgICAgICAgICAgLy8gSWRlbnRpZmllciBhc3NpZ25lZCB1bmlxdWVseSB0byBlYWNoIGltYWdlXG4gICAgfTtcblxuICAgIHZhciBUaWZmVGFncyA9IEVYSUYuVGlmZlRhZ3MgPSB7XG4gICAgICAgIDB4MDEwMCA6IFwiSW1hZ2VXaWR0aFwiLFxuICAgICAgICAweDAxMDEgOiBcIkltYWdlSGVpZ2h0XCIsXG4gICAgICAgIDB4ODc2OSA6IFwiRXhpZklGRFBvaW50ZXJcIixcbiAgICAgICAgMHg4ODI1IDogXCJHUFNJbmZvSUZEUG9pbnRlclwiLFxuICAgICAgICAweEEwMDUgOiBcIkludGVyb3BlcmFiaWxpdHlJRkRQb2ludGVyXCIsXG4gICAgICAgIDB4MDEwMiA6IFwiQml0c1BlclNhbXBsZVwiLFxuICAgICAgICAweDAxMDMgOiBcIkNvbXByZXNzaW9uXCIsXG4gICAgICAgIDB4MDEwNiA6IFwiUGhvdG9tZXRyaWNJbnRlcnByZXRhdGlvblwiLFxuICAgICAgICAweDAxMTIgOiBcIk9yaWVudGF0aW9uXCIsXG4gICAgICAgIDB4MDExNSA6IFwiU2FtcGxlc1BlclBpeGVsXCIsXG4gICAgICAgIDB4MDExQyA6IFwiUGxhbmFyQ29uZmlndXJhdGlvblwiLFxuICAgICAgICAweDAyMTIgOiBcIllDYkNyU3ViU2FtcGxpbmdcIixcbiAgICAgICAgMHgwMjEzIDogXCJZQ2JDclBvc2l0aW9uaW5nXCIsXG4gICAgICAgIDB4MDExQSA6IFwiWFJlc29sdXRpb25cIixcbiAgICAgICAgMHgwMTFCIDogXCJZUmVzb2x1dGlvblwiLFxuICAgICAgICAweDAxMjggOiBcIlJlc29sdXRpb25Vbml0XCIsXG4gICAgICAgIDB4MDExMSA6IFwiU3RyaXBPZmZzZXRzXCIsXG4gICAgICAgIDB4MDExNiA6IFwiUm93c1BlclN0cmlwXCIsXG4gICAgICAgIDB4MDExNyA6IFwiU3RyaXBCeXRlQ291bnRzXCIsXG4gICAgICAgIDB4MDIwMSA6IFwiSlBFR0ludGVyY2hhbmdlRm9ybWF0XCIsXG4gICAgICAgIDB4MDIwMiA6IFwiSlBFR0ludGVyY2hhbmdlRm9ybWF0TGVuZ3RoXCIsXG4gICAgICAgIDB4MDEyRCA6IFwiVHJhbnNmZXJGdW5jdGlvblwiLFxuICAgICAgICAweDAxM0UgOiBcIldoaXRlUG9pbnRcIixcbiAgICAgICAgMHgwMTNGIDogXCJQcmltYXJ5Q2hyb21hdGljaXRpZXNcIixcbiAgICAgICAgMHgwMjExIDogXCJZQ2JDckNvZWZmaWNpZW50c1wiLFxuICAgICAgICAweDAyMTQgOiBcIlJlZmVyZW5jZUJsYWNrV2hpdGVcIixcbiAgICAgICAgMHgwMTMyIDogXCJEYXRlVGltZVwiLFxuICAgICAgICAweDAxMEUgOiBcIkltYWdlRGVzY3JpcHRpb25cIixcbiAgICAgICAgMHgwMTBGIDogXCJNYWtlXCIsXG4gICAgICAgIDB4MDExMCA6IFwiTW9kZWxcIixcbiAgICAgICAgMHgwMTMxIDogXCJTb2Z0d2FyZVwiLFxuICAgICAgICAweDAxM0IgOiBcIkFydGlzdFwiLFxuICAgICAgICAweDgyOTggOiBcIkNvcHlyaWdodFwiXG4gICAgfTtcblxuICAgIHZhciBHUFNUYWdzID0gRVhJRi5HUFNUYWdzID0ge1xuICAgICAgICAweDAwMDAgOiBcIkdQU1ZlcnNpb25JRFwiLFxuICAgICAgICAweDAwMDEgOiBcIkdQU0xhdGl0dWRlUmVmXCIsXG4gICAgICAgIDB4MDAwMiA6IFwiR1BTTGF0aXR1ZGVcIixcbiAgICAgICAgMHgwMDAzIDogXCJHUFNMb25naXR1ZGVSZWZcIixcbiAgICAgICAgMHgwMDA0IDogXCJHUFNMb25naXR1ZGVcIixcbiAgICAgICAgMHgwMDA1IDogXCJHUFNBbHRpdHVkZVJlZlwiLFxuICAgICAgICAweDAwMDYgOiBcIkdQU0FsdGl0dWRlXCIsXG4gICAgICAgIDB4MDAwNyA6IFwiR1BTVGltZVN0YW1wXCIsXG4gICAgICAgIDB4MDAwOCA6IFwiR1BTU2F0ZWxsaXRlc1wiLFxuICAgICAgICAweDAwMDkgOiBcIkdQU1N0YXR1c1wiLFxuICAgICAgICAweDAwMEEgOiBcIkdQU01lYXN1cmVNb2RlXCIsXG4gICAgICAgIDB4MDAwQiA6IFwiR1BTRE9QXCIsXG4gICAgICAgIDB4MDAwQyA6IFwiR1BTU3BlZWRSZWZcIixcbiAgICAgICAgMHgwMDBEIDogXCJHUFNTcGVlZFwiLFxuICAgICAgICAweDAwMEUgOiBcIkdQU1RyYWNrUmVmXCIsXG4gICAgICAgIDB4MDAwRiA6IFwiR1BTVHJhY2tcIixcbiAgICAgICAgMHgwMDEwIDogXCJHUFNJbWdEaXJlY3Rpb25SZWZcIixcbiAgICAgICAgMHgwMDExIDogXCJHUFNJbWdEaXJlY3Rpb25cIixcbiAgICAgICAgMHgwMDEyIDogXCJHUFNNYXBEYXR1bVwiLFxuICAgICAgICAweDAwMTMgOiBcIkdQU0Rlc3RMYXRpdHVkZVJlZlwiLFxuICAgICAgICAweDAwMTQgOiBcIkdQU0Rlc3RMYXRpdHVkZVwiLFxuICAgICAgICAweDAwMTUgOiBcIkdQU0Rlc3RMb25naXR1ZGVSZWZcIixcbiAgICAgICAgMHgwMDE2IDogXCJHUFNEZXN0TG9uZ2l0dWRlXCIsXG4gICAgICAgIDB4MDAxNyA6IFwiR1BTRGVzdEJlYXJpbmdSZWZcIixcbiAgICAgICAgMHgwMDE4IDogXCJHUFNEZXN0QmVhcmluZ1wiLFxuICAgICAgICAweDAwMTkgOiBcIkdQU0Rlc3REaXN0YW5jZVJlZlwiLFxuICAgICAgICAweDAwMUEgOiBcIkdQU0Rlc3REaXN0YW5jZVwiLFxuICAgICAgICAweDAwMUIgOiBcIkdQU1Byb2Nlc3NpbmdNZXRob2RcIixcbiAgICAgICAgMHgwMDFDIDogXCJHUFNBcmVhSW5mb3JtYXRpb25cIixcbiAgICAgICAgMHgwMDFEIDogXCJHUFNEYXRlU3RhbXBcIixcbiAgICAgICAgMHgwMDFFIDogXCJHUFNEaWZmZXJlbnRpYWxcIlxuICAgIH07XG5cbiAgICAgLy8gRVhJRiAyLjMgU3BlY1xuICAgIHZhciBJRkQxVGFncyA9IEVYSUYuSUZEMVRhZ3MgPSB7XG4gICAgICAgIDB4MDEwMDogXCJJbWFnZVdpZHRoXCIsXG4gICAgICAgIDB4MDEwMTogXCJJbWFnZUhlaWdodFwiLFxuICAgICAgICAweDAxMDI6IFwiQml0c1BlclNhbXBsZVwiLFxuICAgICAgICAweDAxMDM6IFwiQ29tcHJlc3Npb25cIixcbiAgICAgICAgMHgwMTA2OiBcIlBob3RvbWV0cmljSW50ZXJwcmV0YXRpb25cIixcbiAgICAgICAgMHgwMTExOiBcIlN0cmlwT2Zmc2V0c1wiLFxuICAgICAgICAweDAxMTI6IFwiT3JpZW50YXRpb25cIixcbiAgICAgICAgMHgwMTE1OiBcIlNhbXBsZXNQZXJQaXhlbFwiLFxuICAgICAgICAweDAxMTY6IFwiUm93c1BlclN0cmlwXCIsXG4gICAgICAgIDB4MDExNzogXCJTdHJpcEJ5dGVDb3VudHNcIixcbiAgICAgICAgMHgwMTFBOiBcIlhSZXNvbHV0aW9uXCIsXG4gICAgICAgIDB4MDExQjogXCJZUmVzb2x1dGlvblwiLFxuICAgICAgICAweDAxMUM6IFwiUGxhbmFyQ29uZmlndXJhdGlvblwiLFxuICAgICAgICAweDAxMjg6IFwiUmVzb2x1dGlvblVuaXRcIixcbiAgICAgICAgMHgwMjAxOiBcIkpwZWdJRk9mZnNldFwiLCAgICAvLyBXaGVuIGltYWdlIGZvcm1hdCBpcyBKUEVHLCB0aGlzIHZhbHVlIHNob3cgb2Zmc2V0IHRvIEpQRUcgZGF0YSBzdG9yZWQuKGFrYSBcIlRodW1ibmFpbE9mZnNldFwiIG9yIFwiSlBFR0ludGVyY2hhbmdlRm9ybWF0XCIpXG4gICAgICAgIDB4MDIwMjogXCJKcGVnSUZCeXRlQ291bnRcIiwgLy8gV2hlbiBpbWFnZSBmb3JtYXQgaXMgSlBFRywgdGhpcyB2YWx1ZSBzaG93cyBkYXRhIHNpemUgb2YgSlBFRyBpbWFnZSAoYWthIFwiVGh1bWJuYWlsTGVuZ3RoXCIgb3IgXCJKUEVHSW50ZXJjaGFuZ2VGb3JtYXRMZW5ndGhcIilcbiAgICAgICAgMHgwMjExOiBcIllDYkNyQ29lZmZpY2llbnRzXCIsXG4gICAgICAgIDB4MDIxMjogXCJZQ2JDclN1YlNhbXBsaW5nXCIsXG4gICAgICAgIDB4MDIxMzogXCJZQ2JDclBvc2l0aW9uaW5nXCIsXG4gICAgICAgIDB4MDIxNDogXCJSZWZlcmVuY2VCbGFja1doaXRlXCJcbiAgICB9O1xuXG4gICAgdmFyIFN0cmluZ1ZhbHVlcyA9IEVYSUYuU3RyaW5nVmFsdWVzID0ge1xuICAgICAgICBFeHBvc3VyZVByb2dyYW0gOiB7XG4gICAgICAgICAgICAwIDogXCJOb3QgZGVmaW5lZFwiLFxuICAgICAgICAgICAgMSA6IFwiTWFudWFsXCIsXG4gICAgICAgICAgICAyIDogXCJOb3JtYWwgcHJvZ3JhbVwiLFxuICAgICAgICAgICAgMyA6IFwiQXBlcnR1cmUgcHJpb3JpdHlcIixcbiAgICAgICAgICAgIDQgOiBcIlNodXR0ZXIgcHJpb3JpdHlcIixcbiAgICAgICAgICAgIDUgOiBcIkNyZWF0aXZlIHByb2dyYW1cIixcbiAgICAgICAgICAgIDYgOiBcIkFjdGlvbiBwcm9ncmFtXCIsXG4gICAgICAgICAgICA3IDogXCJQb3J0cmFpdCBtb2RlXCIsXG4gICAgICAgICAgICA4IDogXCJMYW5kc2NhcGUgbW9kZVwiXG4gICAgICAgIH0sXG4gICAgICAgIE1ldGVyaW5nTW9kZSA6IHtcbiAgICAgICAgICAgIDAgOiBcIlVua25vd25cIixcbiAgICAgICAgICAgIDEgOiBcIkF2ZXJhZ2VcIixcbiAgICAgICAgICAgIDIgOiBcIkNlbnRlcldlaWdodGVkQXZlcmFnZVwiLFxuICAgICAgICAgICAgMyA6IFwiU3BvdFwiLFxuICAgICAgICAgICAgNCA6IFwiTXVsdGlTcG90XCIsXG4gICAgICAgICAgICA1IDogXCJQYXR0ZXJuXCIsXG4gICAgICAgICAgICA2IDogXCJQYXJ0aWFsXCIsXG4gICAgICAgICAgICAyNTUgOiBcIk90aGVyXCJcbiAgICAgICAgfSxcbiAgICAgICAgTGlnaHRTb3VyY2UgOiB7XG4gICAgICAgICAgICAwIDogXCJVbmtub3duXCIsXG4gICAgICAgICAgICAxIDogXCJEYXlsaWdodFwiLFxuICAgICAgICAgICAgMiA6IFwiRmx1b3Jlc2NlbnRcIixcbiAgICAgICAgICAgIDMgOiBcIlR1bmdzdGVuIChpbmNhbmRlc2NlbnQgbGlnaHQpXCIsXG4gICAgICAgICAgICA0IDogXCJGbGFzaFwiLFxuICAgICAgICAgICAgOSA6IFwiRmluZSB3ZWF0aGVyXCIsXG4gICAgICAgICAgICAxMCA6IFwiQ2xvdWR5IHdlYXRoZXJcIixcbiAgICAgICAgICAgIDExIDogXCJTaGFkZVwiLFxuICAgICAgICAgICAgMTIgOiBcIkRheWxpZ2h0IGZsdW9yZXNjZW50IChEIDU3MDAgLSA3MTAwSylcIixcbiAgICAgICAgICAgIDEzIDogXCJEYXkgd2hpdGUgZmx1b3Jlc2NlbnQgKE4gNDYwMCAtIDU0MDBLKVwiLFxuICAgICAgICAgICAgMTQgOiBcIkNvb2wgd2hpdGUgZmx1b3Jlc2NlbnQgKFcgMzkwMCAtIDQ1MDBLKVwiLFxuICAgICAgICAgICAgMTUgOiBcIldoaXRlIGZsdW9yZXNjZW50IChXVyAzMjAwIC0gMzcwMEspXCIsXG4gICAgICAgICAgICAxNyA6IFwiU3RhbmRhcmQgbGlnaHQgQVwiLFxuICAgICAgICAgICAgMTggOiBcIlN0YW5kYXJkIGxpZ2h0IEJcIixcbiAgICAgICAgICAgIDE5IDogXCJTdGFuZGFyZCBsaWdodCBDXCIsXG4gICAgICAgICAgICAyMCA6IFwiRDU1XCIsXG4gICAgICAgICAgICAyMSA6IFwiRDY1XCIsXG4gICAgICAgICAgICAyMiA6IFwiRDc1XCIsXG4gICAgICAgICAgICAyMyA6IFwiRDUwXCIsXG4gICAgICAgICAgICAyNCA6IFwiSVNPIHN0dWRpbyB0dW5nc3RlblwiLFxuICAgICAgICAgICAgMjU1IDogXCJPdGhlclwiXG4gICAgICAgIH0sXG4gICAgICAgIEZsYXNoIDoge1xuICAgICAgICAgICAgMHgwMDAwIDogXCJGbGFzaCBkaWQgbm90IGZpcmVcIixcbiAgICAgICAgICAgIDB4MDAwMSA6IFwiRmxhc2ggZmlyZWRcIixcbiAgICAgICAgICAgIDB4MDAwNSA6IFwiU3Ryb2JlIHJldHVybiBsaWdodCBub3QgZGV0ZWN0ZWRcIixcbiAgICAgICAgICAgIDB4MDAwNyA6IFwiU3Ryb2JlIHJldHVybiBsaWdodCBkZXRlY3RlZFwiLFxuICAgICAgICAgICAgMHgwMDA5IDogXCJGbGFzaCBmaXJlZCwgY29tcHVsc29yeSBmbGFzaCBtb2RlXCIsXG4gICAgICAgICAgICAweDAwMEQgOiBcIkZsYXNoIGZpcmVkLCBjb21wdWxzb3J5IGZsYXNoIG1vZGUsIHJldHVybiBsaWdodCBub3QgZGV0ZWN0ZWRcIixcbiAgICAgICAgICAgIDB4MDAwRiA6IFwiRmxhc2ggZmlyZWQsIGNvbXB1bHNvcnkgZmxhc2ggbW9kZSwgcmV0dXJuIGxpZ2h0IGRldGVjdGVkXCIsXG4gICAgICAgICAgICAweDAwMTAgOiBcIkZsYXNoIGRpZCBub3QgZmlyZSwgY29tcHVsc29yeSBmbGFzaCBtb2RlXCIsXG4gICAgICAgICAgICAweDAwMTggOiBcIkZsYXNoIGRpZCBub3QgZmlyZSwgYXV0byBtb2RlXCIsXG4gICAgICAgICAgICAweDAwMTkgOiBcIkZsYXNoIGZpcmVkLCBhdXRvIG1vZGVcIixcbiAgICAgICAgICAgIDB4MDAxRCA6IFwiRmxhc2ggZmlyZWQsIGF1dG8gbW9kZSwgcmV0dXJuIGxpZ2h0IG5vdCBkZXRlY3RlZFwiLFxuICAgICAgICAgICAgMHgwMDFGIDogXCJGbGFzaCBmaXJlZCwgYXV0byBtb2RlLCByZXR1cm4gbGlnaHQgZGV0ZWN0ZWRcIixcbiAgICAgICAgICAgIDB4MDAyMCA6IFwiTm8gZmxhc2ggZnVuY3Rpb25cIixcbiAgICAgICAgICAgIDB4MDA0MSA6IFwiRmxhc2ggZmlyZWQsIHJlZC1leWUgcmVkdWN0aW9uIG1vZGVcIixcbiAgICAgICAgICAgIDB4MDA0NSA6IFwiRmxhc2ggZmlyZWQsIHJlZC1leWUgcmVkdWN0aW9uIG1vZGUsIHJldHVybiBsaWdodCBub3QgZGV0ZWN0ZWRcIixcbiAgICAgICAgICAgIDB4MDA0NyA6IFwiRmxhc2ggZmlyZWQsIHJlZC1leWUgcmVkdWN0aW9uIG1vZGUsIHJldHVybiBsaWdodCBkZXRlY3RlZFwiLFxuICAgICAgICAgICAgMHgwMDQ5IDogXCJGbGFzaCBmaXJlZCwgY29tcHVsc29yeSBmbGFzaCBtb2RlLCByZWQtZXllIHJlZHVjdGlvbiBtb2RlXCIsXG4gICAgICAgICAgICAweDAwNEQgOiBcIkZsYXNoIGZpcmVkLCBjb21wdWxzb3J5IGZsYXNoIG1vZGUsIHJlZC1leWUgcmVkdWN0aW9uIG1vZGUsIHJldHVybiBsaWdodCBub3QgZGV0ZWN0ZWRcIixcbiAgICAgICAgICAgIDB4MDA0RiA6IFwiRmxhc2ggZmlyZWQsIGNvbXB1bHNvcnkgZmxhc2ggbW9kZSwgcmVkLWV5ZSByZWR1Y3Rpb24gbW9kZSwgcmV0dXJuIGxpZ2h0IGRldGVjdGVkXCIsXG4gICAgICAgICAgICAweDAwNTkgOiBcIkZsYXNoIGZpcmVkLCBhdXRvIG1vZGUsIHJlZC1leWUgcmVkdWN0aW9uIG1vZGVcIixcbiAgICAgICAgICAgIDB4MDA1RCA6IFwiRmxhc2ggZmlyZWQsIGF1dG8gbW9kZSwgcmV0dXJuIGxpZ2h0IG5vdCBkZXRlY3RlZCwgcmVkLWV5ZSByZWR1Y3Rpb24gbW9kZVwiLFxuICAgICAgICAgICAgMHgwMDVGIDogXCJGbGFzaCBmaXJlZCwgYXV0byBtb2RlLCByZXR1cm4gbGlnaHQgZGV0ZWN0ZWQsIHJlZC1leWUgcmVkdWN0aW9uIG1vZGVcIlxuICAgICAgICB9LFxuICAgICAgICBTZW5zaW5nTWV0aG9kIDoge1xuICAgICAgICAgICAgMSA6IFwiTm90IGRlZmluZWRcIixcbiAgICAgICAgICAgIDIgOiBcIk9uZS1jaGlwIGNvbG9yIGFyZWEgc2Vuc29yXCIsXG4gICAgICAgICAgICAzIDogXCJUd28tY2hpcCBjb2xvciBhcmVhIHNlbnNvclwiLFxuICAgICAgICAgICAgNCA6IFwiVGhyZWUtY2hpcCBjb2xvciBhcmVhIHNlbnNvclwiLFxuICAgICAgICAgICAgNSA6IFwiQ29sb3Igc2VxdWVudGlhbCBhcmVhIHNlbnNvclwiLFxuICAgICAgICAgICAgNyA6IFwiVHJpbGluZWFyIHNlbnNvclwiLFxuICAgICAgICAgICAgOCA6IFwiQ29sb3Igc2VxdWVudGlhbCBsaW5lYXIgc2Vuc29yXCJcbiAgICAgICAgfSxcbiAgICAgICAgU2NlbmVDYXB0dXJlVHlwZSA6IHtcbiAgICAgICAgICAgIDAgOiBcIlN0YW5kYXJkXCIsXG4gICAgICAgICAgICAxIDogXCJMYW5kc2NhcGVcIixcbiAgICAgICAgICAgIDIgOiBcIlBvcnRyYWl0XCIsXG4gICAgICAgICAgICAzIDogXCJOaWdodCBzY2VuZVwiXG4gICAgICAgIH0sXG4gICAgICAgIFNjZW5lVHlwZSA6IHtcbiAgICAgICAgICAgIDEgOiBcIkRpcmVjdGx5IHBob3RvZ3JhcGhlZFwiXG4gICAgICAgIH0sXG4gICAgICAgIEN1c3RvbVJlbmRlcmVkIDoge1xuICAgICAgICAgICAgMCA6IFwiTm9ybWFsIHByb2Nlc3NcIixcbiAgICAgICAgICAgIDEgOiBcIkN1c3RvbSBwcm9jZXNzXCJcbiAgICAgICAgfSxcbiAgICAgICAgV2hpdGVCYWxhbmNlIDoge1xuICAgICAgICAgICAgMCA6IFwiQXV0byB3aGl0ZSBiYWxhbmNlXCIsXG4gICAgICAgICAgICAxIDogXCJNYW51YWwgd2hpdGUgYmFsYW5jZVwiXG4gICAgICAgIH0sXG4gICAgICAgIEdhaW5Db250cm9sIDoge1xuICAgICAgICAgICAgMCA6IFwiTm9uZVwiLFxuICAgICAgICAgICAgMSA6IFwiTG93IGdhaW4gdXBcIixcbiAgICAgICAgICAgIDIgOiBcIkhpZ2ggZ2FpbiB1cFwiLFxuICAgICAgICAgICAgMyA6IFwiTG93IGdhaW4gZG93blwiLFxuICAgICAgICAgICAgNCA6IFwiSGlnaCBnYWluIGRvd25cIlxuICAgICAgICB9LFxuICAgICAgICBDb250cmFzdCA6IHtcbiAgICAgICAgICAgIDAgOiBcIk5vcm1hbFwiLFxuICAgICAgICAgICAgMSA6IFwiU29mdFwiLFxuICAgICAgICAgICAgMiA6IFwiSGFyZFwiXG4gICAgICAgIH0sXG4gICAgICAgIFNhdHVyYXRpb24gOiB7XG4gICAgICAgICAgICAwIDogXCJOb3JtYWxcIixcbiAgICAgICAgICAgIDEgOiBcIkxvdyBzYXR1cmF0aW9uXCIsXG4gICAgICAgICAgICAyIDogXCJIaWdoIHNhdHVyYXRpb25cIlxuICAgICAgICB9LFxuICAgICAgICBTaGFycG5lc3MgOiB7XG4gICAgICAgICAgICAwIDogXCJOb3JtYWxcIixcbiAgICAgICAgICAgIDEgOiBcIlNvZnRcIixcbiAgICAgICAgICAgIDIgOiBcIkhhcmRcIlxuICAgICAgICB9LFxuICAgICAgICBTdWJqZWN0RGlzdGFuY2VSYW5nZSA6IHtcbiAgICAgICAgICAgIDAgOiBcIlVua25vd25cIixcbiAgICAgICAgICAgIDEgOiBcIk1hY3JvXCIsXG4gICAgICAgICAgICAyIDogXCJDbG9zZSB2aWV3XCIsXG4gICAgICAgICAgICAzIDogXCJEaXN0YW50IHZpZXdcIlxuICAgICAgICB9LFxuICAgICAgICBGaWxlU291cmNlIDoge1xuICAgICAgICAgICAgMyA6IFwiRFNDXCJcbiAgICAgICAgfSxcblxuICAgICAgICBDb21wb25lbnRzIDoge1xuICAgICAgICAgICAgMCA6IFwiXCIsXG4gICAgICAgICAgICAxIDogXCJZXCIsXG4gICAgICAgICAgICAyIDogXCJDYlwiLFxuICAgICAgICAgICAgMyA6IFwiQ3JcIixcbiAgICAgICAgICAgIDQgOiBcIlJcIixcbiAgICAgICAgICAgIDUgOiBcIkdcIixcbiAgICAgICAgICAgIDYgOiBcIkJcIlxuICAgICAgICB9XG4gICAgfTtcblxuICAgIGZ1bmN0aW9uIGFkZEV2ZW50KGVsZW1lbnQsIGV2ZW50LCBoYW5kbGVyKSB7XG4gICAgICAgIGlmIChlbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIpIHtcbiAgICAgICAgICAgIGVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihldmVudCwgaGFuZGxlciwgZmFsc2UpO1xuICAgICAgICB9IGVsc2UgaWYgKGVsZW1lbnQuYXR0YWNoRXZlbnQpIHtcbiAgICAgICAgICAgIGVsZW1lbnQuYXR0YWNoRXZlbnQoXCJvblwiICsgZXZlbnQsIGhhbmRsZXIpO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gaW1hZ2VIYXNEYXRhKGltZykge1xuICAgICAgICByZXR1cm4gISEoaW1nLmV4aWZkYXRhKTtcbiAgICB9XG5cblxuICAgIGZ1bmN0aW9uIGJhc2U2NFRvQXJyYXlCdWZmZXIoYmFzZTY0LCBjb250ZW50VHlwZSkge1xuICAgICAgICBjb250ZW50VHlwZSA9IGNvbnRlbnRUeXBlIHx8IGJhc2U2NC5tYXRjaCgvXmRhdGFcXDooW15cXDtdKylcXDtiYXNlNjQsL21pKVsxXSB8fCAnJzsgLy8gZS5nLiAnZGF0YTppbWFnZS9qcGVnO2Jhc2U2NCwuLi4nID0+ICdpbWFnZS9qcGVnJ1xuICAgICAgICBiYXNlNjQgPSBiYXNlNjQucmVwbGFjZSgvXmRhdGFcXDooW15cXDtdKylcXDtiYXNlNjQsL2dtaSwgJycpO1xuICAgICAgICB2YXIgYmluYXJ5ID0gYXRvYihiYXNlNjQpO1xuICAgICAgICB2YXIgbGVuID0gYmluYXJ5Lmxlbmd0aDtcbiAgICAgICAgdmFyIGJ1ZmZlciA9IG5ldyBBcnJheUJ1ZmZlcihsZW4pO1xuICAgICAgICB2YXIgdmlldyA9IG5ldyBVaW50OEFycmF5KGJ1ZmZlcik7XG4gICAgICAgIGZvciAodmFyIGkgPSAwOyBpIDwgbGVuOyBpKyspIHtcbiAgICAgICAgICAgIHZpZXdbaV0gPSBiaW5hcnkuY2hhckNvZGVBdChpKTtcbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gYnVmZmVyO1xuICAgIH1cblxuICAgIGZ1bmN0aW9uIG9iamVjdFVSTFRvQmxvYih1cmwsIGNhbGxiYWNrKSB7XG4gICAgICAgIHZhciBodHRwID0gbmV3IFhNTEh0dHBSZXF1ZXN0KCk7XG4gICAgICAgIGh0dHAub3BlbihcIkdFVFwiLCB1cmwsIHRydWUpO1xuICAgICAgICBodHRwLnJlc3BvbnNlVHlwZSA9IFwiYmxvYlwiO1xuICAgICAgICBodHRwLm9ubG9hZCA9IGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgICAgIGlmICh0aGlzLnN0YXR1cyA9PSAyMDAgfHwgdGhpcy5zdGF0dXMgPT09IDApIHtcbiAgICAgICAgICAgICAgICBjYWxsYmFjayh0aGlzLnJlc3BvbnNlKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcbiAgICAgICAgaHR0cC5zZW5kKCk7XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gZ2V0SW1hZ2VEYXRhKGltZywgY2FsbGJhY2spIHtcbiAgICAgICAgZnVuY3Rpb24gaGFuZGxlQmluYXJ5RmlsZShiaW5GaWxlKSB7XG4gICAgICAgICAgICB2YXIgZGF0YSA9IGZpbmRFWElGaW5KUEVHKGJpbkZpbGUpO1xuICAgICAgICAgICAgaW1nLmV4aWZkYXRhID0gZGF0YSB8fCB7fTtcbiAgICAgICAgICAgIHZhciBpcHRjZGF0YSA9IGZpbmRJUFRDaW5KUEVHKGJpbkZpbGUpO1xuICAgICAgICAgICAgaW1nLmlwdGNkYXRhID0gaXB0Y2RhdGEgfHwge307XG4gICAgICAgICAgICBpZiAoRVhJRi5pc1htcEVuYWJsZWQpIHtcbiAgICAgICAgICAgICAgIHZhciB4bXBkYXRhPSBmaW5kWE1QaW5KUEVHKGJpbkZpbGUpO1xuICAgICAgICAgICAgICAgaW1nLnhtcGRhdGEgPSB4bXBkYXRhIHx8IHt9OyAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaWYgKGNhbGxiYWNrKSB7XG4gICAgICAgICAgICAgICAgY2FsbGJhY2suY2FsbChpbWcpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG5cbiAgICAgICAgaWYgKGltZy5zcmMpIHtcbiAgICAgICAgICAgIGlmICgvXmRhdGFcXDovaS50ZXN0KGltZy5zcmMpKSB7IC8vIERhdGEgVVJJXG4gICAgICAgICAgICAgICAgdmFyIGFycmF5QnVmZmVyID0gYmFzZTY0VG9BcnJheUJ1ZmZlcihpbWcuc3JjKTtcbiAgICAgICAgICAgICAgICBoYW5kbGVCaW5hcnlGaWxlKGFycmF5QnVmZmVyKTtcblxuICAgICAgICAgICAgfSBlbHNlIGlmICgvXmJsb2JcXDovaS50ZXN0KGltZy5zcmMpKSB7IC8vIE9iamVjdCBVUkxcbiAgICAgICAgICAgICAgICB2YXIgZmlsZVJlYWRlciA9IG5ldyBGaWxlUmVhZGVyKCk7XG4gICAgICAgICAgICAgICAgZmlsZVJlYWRlci5vbmxvYWQgPSBmdW5jdGlvbihlKSB7XG4gICAgICAgICAgICAgICAgICAgIGhhbmRsZUJpbmFyeUZpbGUoZS50YXJnZXQucmVzdWx0KTtcbiAgICAgICAgICAgICAgICB9O1xuICAgICAgICAgICAgICAgIG9iamVjdFVSTFRvQmxvYihpbWcuc3JjLCBmdW5jdGlvbiAoYmxvYikge1xuICAgICAgICAgICAgICAgICAgICBmaWxlUmVhZGVyLnJlYWRBc0FycmF5QnVmZmVyKGJsb2IpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICB2YXIgaHR0cCA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpO1xuICAgICAgICAgICAgICAgIGh0dHAub25sb2FkID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgICAgIGlmICh0aGlzLnN0YXR1cyA9PSAyMDAgfHwgdGhpcy5zdGF0dXMgPT09IDApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGhhbmRsZUJpbmFyeUZpbGUoaHR0cC5yZXNwb25zZSk7XG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB0aHJvdyBcIkNvdWxkIG5vdCBsb2FkIGltYWdlXCI7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgaHR0cCA9IG51bGw7XG4gICAgICAgICAgICAgICAgfTtcbiAgICAgICAgICAgICAgICBodHRwLm9wZW4oXCJHRVRcIiwgaW1nLnNyYywgdHJ1ZSk7XG4gICAgICAgICAgICAgICAgaHR0cC5yZXNwb25zZVR5cGUgPSBcImFycmF5YnVmZmVyXCI7XG4gICAgICAgICAgICAgICAgaHR0cC5zZW5kKG51bGwpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9IGVsc2UgaWYgKHNlbGYuRmlsZVJlYWRlciAmJiAoaW1nIGluc3RhbmNlb2Ygc2VsZi5CbG9iIHx8IGltZyBpbnN0YW5jZW9mIHNlbGYuRmlsZSkpIHtcbiAgICAgICAgICAgIHZhciBmaWxlUmVhZGVyID0gbmV3IEZpbGVSZWFkZXIoKTtcbiAgICAgICAgICAgIGZpbGVSZWFkZXIub25sb2FkID0gZnVuY3Rpb24oZSkge1xuICAgICAgICAgICAgICAgIGlmIChkZWJ1ZykgY29uc29sZS5sb2coXCJHb3QgZmlsZSBvZiBsZW5ndGggXCIgKyBlLnRhcmdldC5yZXN1bHQuYnl0ZUxlbmd0aCk7XG4gICAgICAgICAgICAgICAgaGFuZGxlQmluYXJ5RmlsZShlLnRhcmdldC5yZXN1bHQpO1xuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgZmlsZVJlYWRlci5yZWFkQXNBcnJheUJ1ZmZlcihpbWcpO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gZmluZEVYSUZpbkpQRUcoZmlsZSkge1xuICAgICAgICB2YXIgZGF0YVZpZXcgPSBuZXcgRGF0YVZpZXcoZmlsZSk7XG5cbiAgICAgICAgaWYgKGRlYnVnKSBjb25zb2xlLmxvZyhcIkdvdCBmaWxlIG9mIGxlbmd0aCBcIiArIGZpbGUuYnl0ZUxlbmd0aCk7XG4gICAgICAgIGlmICgoZGF0YVZpZXcuZ2V0VWludDgoMCkgIT0gMHhGRikgfHwgKGRhdGFWaWV3LmdldFVpbnQ4KDEpICE9IDB4RDgpKSB7XG4gICAgICAgICAgICBpZiAoZGVidWcpIGNvbnNvbGUubG9nKFwiTm90IGEgdmFsaWQgSlBFR1wiKTtcbiAgICAgICAgICAgIHJldHVybiBmYWxzZTsgLy8gbm90IGEgdmFsaWQganBlZ1xuICAgICAgICB9XG5cbiAgICAgICAgdmFyIG9mZnNldCA9IDIsXG4gICAgICAgICAgICBsZW5ndGggPSBmaWxlLmJ5dGVMZW5ndGgsXG4gICAgICAgICAgICBtYXJrZXI7XG5cbiAgICAgICAgd2hpbGUgKG9mZnNldCA8IGxlbmd0aCkge1xuICAgICAgICAgICAgaWYgKGRhdGFWaWV3LmdldFVpbnQ4KG9mZnNldCkgIT0gMHhGRikge1xuICAgICAgICAgICAgICAgIGlmIChkZWJ1ZykgY29uc29sZS5sb2coXCJOb3QgYSB2YWxpZCBtYXJrZXIgYXQgb2Zmc2V0IFwiICsgb2Zmc2V0ICsgXCIsIGZvdW5kOiBcIiArIGRhdGFWaWV3LmdldFVpbnQ4KG9mZnNldCkpO1xuICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTsgLy8gbm90IGEgdmFsaWQgbWFya2VyLCBzb21ldGhpbmcgaXMgd3JvbmdcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgbWFya2VyID0gZGF0YVZpZXcuZ2V0VWludDgob2Zmc2V0ICsgMSk7XG4gICAgICAgICAgICBpZiAoZGVidWcpIGNvbnNvbGUubG9nKG1hcmtlcik7XG5cbiAgICAgICAgICAgIC8vIHdlIGNvdWxkIGltcGxlbWVudCBoYW5kbGluZyBmb3Igb3RoZXIgbWFya2VycyBoZXJlLFxuICAgICAgICAgICAgLy8gYnV0IHdlJ3JlIG9ubHkgbG9va2luZyBmb3IgMHhGRkUxIGZvciBFWElGIGRhdGFcblxuICAgICAgICAgICAgaWYgKG1hcmtlciA9PSAyMjUpIHtcbiAgICAgICAgICAgICAgICBpZiAoZGVidWcpIGNvbnNvbGUubG9nKFwiRm91bmQgMHhGRkUxIG1hcmtlclwiKTtcblxuICAgICAgICAgICAgICAgIHJldHVybiByZWFkRVhJRkRhdGEoZGF0YVZpZXcsIG9mZnNldCArIDQsIGRhdGFWaWV3LmdldFVpbnQxNihvZmZzZXQgKyAyKSAtIDIpO1xuXG4gICAgICAgICAgICAgICAgLy8gb2Zmc2V0ICs9IDIgKyBmaWxlLmdldFNob3J0QXQob2Zmc2V0KzIsIHRydWUpO1xuXG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIG9mZnNldCArPSAyICsgZGF0YVZpZXcuZ2V0VWludDE2KG9mZnNldCsyKTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICB9XG5cbiAgICB9XG5cbiAgICBmdW5jdGlvbiBmaW5kSVBUQ2luSlBFRyhmaWxlKSB7XG4gICAgICAgIHZhciBkYXRhVmlldyA9IG5ldyBEYXRhVmlldyhmaWxlKTtcblxuICAgICAgICBpZiAoZGVidWcpIGNvbnNvbGUubG9nKFwiR290IGZpbGUgb2YgbGVuZ3RoIFwiICsgZmlsZS5ieXRlTGVuZ3RoKTtcbiAgICAgICAgaWYgKChkYXRhVmlldy5nZXRVaW50OCgwKSAhPSAweEZGKSB8fCAoZGF0YVZpZXcuZ2V0VWludDgoMSkgIT0gMHhEOCkpIHtcbiAgICAgICAgICAgIGlmIChkZWJ1ZykgY29uc29sZS5sb2coXCJOb3QgYSB2YWxpZCBKUEVHXCIpO1xuICAgICAgICAgICAgcmV0dXJuIGZhbHNlOyAvLyBub3QgYSB2YWxpZCBqcGVnXG4gICAgICAgIH1cblxuICAgICAgICB2YXIgb2Zmc2V0ID0gMixcbiAgICAgICAgICAgIGxlbmd0aCA9IGZpbGUuYnl0ZUxlbmd0aDtcblxuXG4gICAgICAgIHZhciBpc0ZpZWxkU2VnbWVudFN0YXJ0ID0gZnVuY3Rpb24oZGF0YVZpZXcsIG9mZnNldCl7XG4gICAgICAgICAgICByZXR1cm4gKFxuICAgICAgICAgICAgICAgIGRhdGFWaWV3LmdldFVpbnQ4KG9mZnNldCkgPT09IDB4MzggJiZcbiAgICAgICAgICAgICAgICBkYXRhVmlldy5nZXRVaW50OChvZmZzZXQrMSkgPT09IDB4NDIgJiZcbiAgICAgICAgICAgICAgICBkYXRhVmlldy5nZXRVaW50OChvZmZzZXQrMikgPT09IDB4NDkgJiZcbiAgICAgICAgICAgICAgICBkYXRhVmlldy5nZXRVaW50OChvZmZzZXQrMykgPT09IDB4NEQgJiZcbiAgICAgICAgICAgICAgICBkYXRhVmlldy5nZXRVaW50OChvZmZzZXQrNCkgPT09IDB4MDQgJiZcbiAgICAgICAgICAgICAgICBkYXRhVmlldy5nZXRVaW50OChvZmZzZXQrNSkgPT09IDB4MDRcbiAgICAgICAgICAgICk7XG4gICAgICAgIH07XG5cbiAgICAgICAgd2hpbGUgKG9mZnNldCA8IGxlbmd0aCkge1xuXG4gICAgICAgICAgICBpZiAoIGlzRmllbGRTZWdtZW50U3RhcnQoZGF0YVZpZXcsIG9mZnNldCApKXtcblxuICAgICAgICAgICAgICAgIC8vIEdldCB0aGUgbGVuZ3RoIG9mIHRoZSBuYW1lIGhlYWRlciAod2hpY2ggaXMgcGFkZGVkIHRvIGFuIGV2ZW4gbnVtYmVyIG9mIGJ5dGVzKVxuICAgICAgICAgICAgICAgIHZhciBuYW1lSGVhZGVyTGVuZ3RoID0gZGF0YVZpZXcuZ2V0VWludDgob2Zmc2V0KzcpO1xuICAgICAgICAgICAgICAgIGlmKG5hbWVIZWFkZXJMZW5ndGggJSAyICE9PSAwKSBuYW1lSGVhZGVyTGVuZ3RoICs9IDE7XG4gICAgICAgICAgICAgICAgLy8gQ2hlY2sgZm9yIHByZSBwaG90b3Nob3AgNiBmb3JtYXRcbiAgICAgICAgICAgICAgICBpZihuYW1lSGVhZGVyTGVuZ3RoID09PSAwKSB7XG4gICAgICAgICAgICAgICAgICAgIC8vIEFsd2F5cyA0XG4gICAgICAgICAgICAgICAgICAgIG5hbWVIZWFkZXJMZW5ndGggPSA0O1xuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIHZhciBzdGFydE9mZnNldCA9IG9mZnNldCArIDggKyBuYW1lSGVhZGVyTGVuZ3RoO1xuICAgICAgICAgICAgICAgIHZhciBzZWN0aW9uTGVuZ3RoID0gZGF0YVZpZXcuZ2V0VWludDE2KG9mZnNldCArIDYgKyBuYW1lSGVhZGVyTGVuZ3RoKTtcblxuICAgICAgICAgICAgICAgIHJldHVybiByZWFkSVBUQ0RhdGEoZmlsZSwgc3RhcnRPZmZzZXQsIHNlY3Rpb25MZW5ndGgpO1xuXG4gICAgICAgICAgICAgICAgYnJlYWs7XG5cbiAgICAgICAgICAgIH1cblxuXG4gICAgICAgICAgICAvLyBOb3QgdGhlIG1hcmtlciwgY29udGludWUgc2VhcmNoaW5nXG4gICAgICAgICAgICBvZmZzZXQrKztcblxuICAgICAgICB9XG5cbiAgICB9XG4gICAgdmFyIElwdGNGaWVsZE1hcCA9IHtcbiAgICAgICAgMHg3OCA6ICdjYXB0aW9uJyxcbiAgICAgICAgMHg2RSA6ICdjcmVkaXQnLFxuICAgICAgICAweDE5IDogJ2tleXdvcmRzJyxcbiAgICAgICAgMHgzNyA6ICdkYXRlQ3JlYXRlZCcsXG4gICAgICAgIDB4NTAgOiAnYnlsaW5lJyxcbiAgICAgICAgMHg1NSA6ICdieWxpbmVUaXRsZScsXG4gICAgICAgIDB4N0EgOiAnY2FwdGlvbldyaXRlcicsXG4gICAgICAgIDB4NjkgOiAnaGVhZGxpbmUnLFxuICAgICAgICAweDc0IDogJ2NvcHlyaWdodCcsXG4gICAgICAgIDB4MEYgOiAnY2F0ZWdvcnknXG4gICAgfTtcbiAgICBmdW5jdGlvbiByZWFkSVBUQ0RhdGEoZmlsZSwgc3RhcnRPZmZzZXQsIHNlY3Rpb25MZW5ndGgpe1xuICAgICAgICB2YXIgZGF0YVZpZXcgPSBuZXcgRGF0YVZpZXcoZmlsZSk7XG4gICAgICAgIHZhciBkYXRhID0ge307XG4gICAgICAgIHZhciBmaWVsZFZhbHVlLCBmaWVsZE5hbWUsIGRhdGFTaXplLCBzZWdtZW50VHlwZSwgc2VnbWVudFNpemU7XG4gICAgICAgIHZhciBzZWdtZW50U3RhcnRQb3MgPSBzdGFydE9mZnNldDtcbiAgICAgICAgd2hpbGUoc2VnbWVudFN0YXJ0UG9zIDwgc3RhcnRPZmZzZXQrc2VjdGlvbkxlbmd0aCkge1xuICAgICAgICAgICAgaWYoZGF0YVZpZXcuZ2V0VWludDgoc2VnbWVudFN0YXJ0UG9zKSA9PT0gMHgxQyAmJiBkYXRhVmlldy5nZXRVaW50OChzZWdtZW50U3RhcnRQb3MrMSkgPT09IDB4MDIpe1xuICAgICAgICAgICAgICAgIHNlZ21lbnRUeXBlID0gZGF0YVZpZXcuZ2V0VWludDgoc2VnbWVudFN0YXJ0UG9zKzIpO1xuICAgICAgICAgICAgICAgIGlmKHNlZ21lbnRUeXBlIGluIElwdGNGaWVsZE1hcCkge1xuICAgICAgICAgICAgICAgICAgICBkYXRhU2l6ZSA9IGRhdGFWaWV3LmdldEludDE2KHNlZ21lbnRTdGFydFBvcyszKTtcbiAgICAgICAgICAgICAgICAgICAgc2VnbWVudFNpemUgPSBkYXRhU2l6ZSArIDU7XG4gICAgICAgICAgICAgICAgICAgIGZpZWxkTmFtZSA9IElwdGNGaWVsZE1hcFtzZWdtZW50VHlwZV07XG4gICAgICAgICAgICAgICAgICAgIGZpZWxkVmFsdWUgPSBnZXRTdHJpbmdGcm9tREIoZGF0YVZpZXcsIHNlZ21lbnRTdGFydFBvcys1LCBkYXRhU2l6ZSk7XG4gICAgICAgICAgICAgICAgICAgIC8vIENoZWNrIGlmIHdlIGFscmVhZHkgc3RvcmVkIGEgdmFsdWUgd2l0aCB0aGlzIG5hbWVcbiAgICAgICAgICAgICAgICAgICAgaWYoZGF0YS5oYXNPd25Qcm9wZXJ0eShmaWVsZE5hbWUpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAvLyBWYWx1ZSBhbHJlYWR5IHN0b3JlZCB3aXRoIHRoaXMgbmFtZSwgY3JlYXRlIG11bHRpdmFsdWUgZmllbGRcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmKGRhdGFbZmllbGROYW1lXSBpbnN0YW5jZW9mIEFycmF5KSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgZGF0YVtmaWVsZE5hbWVdLnB1c2goZmllbGRWYWx1ZSk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBkYXRhW2ZpZWxkTmFtZV0gPSBbZGF0YVtmaWVsZE5hbWVdLCBmaWVsZFZhbHVlXTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGRhdGFbZmllbGROYW1lXSA9IGZpZWxkVmFsdWU7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHNlZ21lbnRTdGFydFBvcysrO1xuICAgICAgICB9XG4gICAgICAgIHJldHVybiBkYXRhO1xuICAgIH1cblxuXG5cbiAgICBmdW5jdGlvbiByZWFkVGFncyhmaWxlLCB0aWZmU3RhcnQsIGRpclN0YXJ0LCBzdHJpbmdzLCBiaWdFbmQpIHtcbiAgICAgICAgdmFyIGVudHJpZXMgPSBmaWxlLmdldFVpbnQxNihkaXJTdGFydCwgIWJpZ0VuZCksXG4gICAgICAgICAgICB0YWdzID0ge30sXG4gICAgICAgICAgICBlbnRyeU9mZnNldCwgdGFnLFxuICAgICAgICAgICAgaTtcblxuICAgICAgICBmb3IgKGk9MDtpPGVudHJpZXM7aSsrKSB7XG4gICAgICAgICAgICBlbnRyeU9mZnNldCA9IGRpclN0YXJ0ICsgaSoxMiArIDI7XG4gICAgICAgICAgICB0YWcgPSBzdHJpbmdzW2ZpbGUuZ2V0VWludDE2KGVudHJ5T2Zmc2V0LCAhYmlnRW5kKV07XG4gICAgICAgICAgICBpZiAoIXRhZyAmJiBkZWJ1ZykgY29uc29sZS5sb2coXCJVbmtub3duIHRhZzogXCIgKyBmaWxlLmdldFVpbnQxNihlbnRyeU9mZnNldCwgIWJpZ0VuZCkpO1xuICAgICAgICAgICAgdGFnc1t0YWddID0gcmVhZFRhZ1ZhbHVlKGZpbGUsIGVudHJ5T2Zmc2V0LCB0aWZmU3RhcnQsIGRpclN0YXJ0LCBiaWdFbmQpO1xuICAgICAgICB9XG4gICAgICAgIHJldHVybiB0YWdzO1xuICAgIH1cblxuXG4gICAgZnVuY3Rpb24gcmVhZFRhZ1ZhbHVlKGZpbGUsIGVudHJ5T2Zmc2V0LCB0aWZmU3RhcnQsIGRpclN0YXJ0LCBiaWdFbmQpIHtcbiAgICAgICAgdmFyIHR5cGUgPSBmaWxlLmdldFVpbnQxNihlbnRyeU9mZnNldCsyLCAhYmlnRW5kKSxcbiAgICAgICAgICAgIG51bVZhbHVlcyA9IGZpbGUuZ2V0VWludDMyKGVudHJ5T2Zmc2V0KzQsICFiaWdFbmQpLFxuICAgICAgICAgICAgdmFsdWVPZmZzZXQgPSBmaWxlLmdldFVpbnQzMihlbnRyeU9mZnNldCs4LCAhYmlnRW5kKSArIHRpZmZTdGFydCxcbiAgICAgICAgICAgIG9mZnNldCxcbiAgICAgICAgICAgIHZhbHMsIHZhbCwgbixcbiAgICAgICAgICAgIG51bWVyYXRvciwgZGVub21pbmF0b3I7XG5cbiAgICAgICAgc3dpdGNoICh0eXBlKSB7XG4gICAgICAgICAgICBjYXNlIDE6IC8vIGJ5dGUsIDgtYml0IHVuc2lnbmVkIGludFxuICAgICAgICAgICAgY2FzZSA3OiAvLyB1bmRlZmluZWQsIDgtYml0IGJ5dGUsIHZhbHVlIGRlcGVuZGluZyBvbiBmaWVsZFxuICAgICAgICAgICAgICAgIGlmIChudW1WYWx1ZXMgPT0gMSkge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gZmlsZS5nZXRVaW50OChlbnRyeU9mZnNldCArIDgsICFiaWdFbmQpO1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIG9mZnNldCA9IG51bVZhbHVlcyA+IDQgPyB2YWx1ZU9mZnNldCA6IChlbnRyeU9mZnNldCArIDgpO1xuICAgICAgICAgICAgICAgICAgICB2YWxzID0gW107XG4gICAgICAgICAgICAgICAgICAgIGZvciAobj0wO248bnVtVmFsdWVzO24rKykge1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFsc1tuXSA9IGZpbGUuZ2V0VWludDgob2Zmc2V0ICsgbik7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHZhbHM7XG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBjYXNlIDI6IC8vIGFzY2lpLCA4LWJpdCBieXRlXG4gICAgICAgICAgICAgICAgb2Zmc2V0ID0gbnVtVmFsdWVzID4gNCA/IHZhbHVlT2Zmc2V0IDogKGVudHJ5T2Zmc2V0ICsgOCk7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGdldFN0cmluZ0Zyb21EQihmaWxlLCBvZmZzZXQsIG51bVZhbHVlcy0xKTtcblxuICAgICAgICAgICAgY2FzZSAzOiAvLyBzaG9ydCwgMTYgYml0IGludFxuICAgICAgICAgICAgICAgIGlmIChudW1WYWx1ZXMgPT0gMSkge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gZmlsZS5nZXRVaW50MTYoZW50cnlPZmZzZXQgKyA4LCAhYmlnRW5kKTtcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICBvZmZzZXQgPSBudW1WYWx1ZXMgPiAyID8gdmFsdWVPZmZzZXQgOiAoZW50cnlPZmZzZXQgKyA4KTtcbiAgICAgICAgICAgICAgICAgICAgdmFscyA9IFtdO1xuICAgICAgICAgICAgICAgICAgICBmb3IgKG49MDtuPG51bVZhbHVlcztuKyspIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhbHNbbl0gPSBmaWxlLmdldFVpbnQxNihvZmZzZXQgKyAyKm4sICFiaWdFbmQpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiB2YWxzO1xuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgY2FzZSA0OiAvLyBsb25nLCAzMiBiaXQgaW50XG4gICAgICAgICAgICAgICAgaWYgKG51bVZhbHVlcyA9PSAxKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBmaWxlLmdldFVpbnQzMihlbnRyeU9mZnNldCArIDgsICFiaWdFbmQpO1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIHZhbHMgPSBbXTtcbiAgICAgICAgICAgICAgICAgICAgZm9yIChuPTA7bjxudW1WYWx1ZXM7bisrKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB2YWxzW25dID0gZmlsZS5nZXRVaW50MzIodmFsdWVPZmZzZXQgKyA0Km4sICFiaWdFbmQpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiB2YWxzO1xuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgY2FzZSA1OiAgICAvLyByYXRpb25hbCA9IHR3byBsb25nIHZhbHVlcywgZmlyc3QgaXMgbnVtZXJhdG9yLCBzZWNvbmQgaXMgZGVub21pbmF0b3JcbiAgICAgICAgICAgICAgICBpZiAobnVtVmFsdWVzID09IDEpIHtcbiAgICAgICAgICAgICAgICAgICAgbnVtZXJhdG9yID0gZmlsZS5nZXRVaW50MzIodmFsdWVPZmZzZXQsICFiaWdFbmQpO1xuICAgICAgICAgICAgICAgICAgICBkZW5vbWluYXRvciA9IGZpbGUuZ2V0VWludDMyKHZhbHVlT2Zmc2V0KzQsICFiaWdFbmQpO1xuICAgICAgICAgICAgICAgICAgICB2YWwgPSBuZXcgTnVtYmVyKG51bWVyYXRvciAvIGRlbm9taW5hdG9yKTtcbiAgICAgICAgICAgICAgICAgICAgdmFsLm51bWVyYXRvciA9IG51bWVyYXRvcjtcbiAgICAgICAgICAgICAgICAgICAgdmFsLmRlbm9taW5hdG9yID0gZGVub21pbmF0b3I7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiB2YWw7XG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgdmFscyA9IFtdO1xuICAgICAgICAgICAgICAgICAgICBmb3IgKG49MDtuPG51bVZhbHVlcztuKyspIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG51bWVyYXRvciA9IGZpbGUuZ2V0VWludDMyKHZhbHVlT2Zmc2V0ICsgOCpuLCAhYmlnRW5kKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGRlbm9taW5hdG9yID0gZmlsZS5nZXRVaW50MzIodmFsdWVPZmZzZXQrNCArIDgqbiwgIWJpZ0VuZCk7XG4gICAgICAgICAgICAgICAgICAgICAgICB2YWxzW25dID0gbmV3IE51bWJlcihudW1lcmF0b3IgLyBkZW5vbWluYXRvcik7XG4gICAgICAgICAgICAgICAgICAgICAgICB2YWxzW25dLm51bWVyYXRvciA9IG51bWVyYXRvcjtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhbHNbbl0uZGVub21pbmF0b3IgPSBkZW5vbWluYXRvcjtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gdmFscztcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGNhc2UgOTogLy8gc2xvbmcsIDMyIGJpdCBzaWduZWQgaW50XG4gICAgICAgICAgICAgICAgaWYgKG51bVZhbHVlcyA9PSAxKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBmaWxlLmdldEludDMyKGVudHJ5T2Zmc2V0ICsgOCwgIWJpZ0VuZCk7XG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgdmFscyA9IFtdO1xuICAgICAgICAgICAgICAgICAgICBmb3IgKG49MDtuPG51bVZhbHVlcztuKyspIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhbHNbbl0gPSBmaWxlLmdldEludDMyKHZhbHVlT2Zmc2V0ICsgNCpuLCAhYmlnRW5kKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gdmFscztcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGNhc2UgMTA6IC8vIHNpZ25lZCByYXRpb25hbCwgdHdvIHNsb25ncywgZmlyc3QgaXMgbnVtZXJhdG9yLCBzZWNvbmQgaXMgZGVub21pbmF0b3JcbiAgICAgICAgICAgICAgICBpZiAobnVtVmFsdWVzID09IDEpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZpbGUuZ2V0SW50MzIodmFsdWVPZmZzZXQsICFiaWdFbmQpIC8gZmlsZS5nZXRJbnQzMih2YWx1ZU9mZnNldCs0LCAhYmlnRW5kKTtcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICB2YWxzID0gW107XG4gICAgICAgICAgICAgICAgICAgIGZvciAobj0wO248bnVtVmFsdWVzO24rKykge1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFsc1tuXSA9IGZpbGUuZ2V0SW50MzIodmFsdWVPZmZzZXQgKyA4Km4sICFiaWdFbmQpIC8gZmlsZS5nZXRJbnQzMih2YWx1ZU9mZnNldCs0ICsgOCpuLCAhYmlnRW5kKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gdmFscztcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICAvKipcbiAgICAqIEdpdmVuIGFuIElGRCAoSW1hZ2UgRmlsZSBEaXJlY3RvcnkpIHN0YXJ0IG9mZnNldFxuICAgICogcmV0dXJucyBhbiBvZmZzZXQgdG8gbmV4dCBJRkQgb3IgMCBpZiBpdCdzIHRoZSBsYXN0IElGRC5cbiAgICAqL1xuICAgIGZ1bmN0aW9uIGdldE5leHRJRkRPZmZzZXQoZGF0YVZpZXcsIGRpclN0YXJ0LCBiaWdFbmQpe1xuICAgICAgICAvL3RoZSBmaXJzdCAyYnl0ZXMgbWVhbnMgdGhlIG51bWJlciBvZiBkaXJlY3RvcnkgZW50cmllcyBjb250YWlucyBpbiB0aGlzIElGRFxuICAgICAgICB2YXIgZW50cmllcyA9IGRhdGFWaWV3LmdldFVpbnQxNihkaXJTdGFydCwgIWJpZ0VuZCk7XG5cbiAgICAgICAgLy8gQWZ0ZXIgbGFzdCBkaXJlY3RvcnkgZW50cnksIHRoZXJlIGlzIGEgNGJ5dGVzIG9mIGRhdGEsXG4gICAgICAgIC8vIGl0IG1lYW5zIGFuIG9mZnNldCB0byBuZXh0IElGRC5cbiAgICAgICAgLy8gSWYgaXRzIHZhbHVlIGlzICcweDAwMDAwMDAwJywgaXQgbWVhbnMgdGhpcyBpcyB0aGUgbGFzdCBJRkQgYW5kIHRoZXJlIGlzIG5vIGxpbmtlZCBJRkQuXG5cbiAgICAgICAgcmV0dXJuIGRhdGFWaWV3LmdldFVpbnQzMihkaXJTdGFydCArIDIgKyBlbnRyaWVzICogMTIsICFiaWdFbmQpOyAvLyBlYWNoIGVudHJ5IGlzIDEyIGJ5dGVzIGxvbmdcbiAgICB9XG5cbiAgICBmdW5jdGlvbiByZWFkVGh1bWJuYWlsSW1hZ2UoZGF0YVZpZXcsIHRpZmZTdGFydCwgZmlyc3RJRkRPZmZzZXQsIGJpZ0VuZCl7XG4gICAgICAgIC8vIGdldCB0aGUgSUZEMSBvZmZzZXRcbiAgICAgICAgdmFyIElGRDFPZmZzZXRQb2ludGVyID0gZ2V0TmV4dElGRE9mZnNldChkYXRhVmlldywgdGlmZlN0YXJ0K2ZpcnN0SUZET2Zmc2V0LCBiaWdFbmQpO1xuXG4gICAgICAgIGlmICghSUZEMU9mZnNldFBvaW50ZXIpIHtcbiAgICAgICAgICAgIC8vIGNvbnNvbGUubG9nKCcqKioqKioqKiBJRkQxT2Zmc2V0IGlzIGVtcHR5LCBpbWFnZSB0aHVtYiBub3QgZm91bmQgKioqKioqKionKTtcbiAgICAgICAgICAgIHJldHVybiB7fTtcbiAgICAgICAgfVxuICAgICAgICBlbHNlIGlmIChJRkQxT2Zmc2V0UG9pbnRlciA+IGRhdGFWaWV3LmJ5dGVMZW5ndGgpIHsgLy8gdGhpcyBzaG91bGQgbm90IGhhcHBlblxuICAgICAgICAgICAgLy8gY29uc29sZS5sb2coJyoqKioqKioqIElGRDFPZmZzZXQgaXMgb3V0c2lkZSB0aGUgYm91bmRzIG9mIHRoZSBEYXRhVmlldyAqKioqKioqKicpO1xuICAgICAgICAgICAgcmV0dXJuIHt9O1xuICAgICAgICB9XG4gICAgICAgIC8vIGNvbnNvbGUubG9nKCcqKioqKioqICB0aHVtYm5haWwgSUZEIG9mZnNldCAoSUZEMSkgaXM6ICVzJywgSUZEMU9mZnNldFBvaW50ZXIpO1xuXG4gICAgICAgIHZhciB0aHVtYlRhZ3MgPSByZWFkVGFncyhkYXRhVmlldywgdGlmZlN0YXJ0LCB0aWZmU3RhcnQgKyBJRkQxT2Zmc2V0UG9pbnRlciwgSUZEMVRhZ3MsIGJpZ0VuZClcblxuICAgICAgICAvLyBFWElGIDIuMyBzcGVjaWZpY2F0aW9uIGZvciBKUEVHIGZvcm1hdCB0aHVtYm5haWxcblxuICAgICAgICAvLyBJZiB0aGUgdmFsdWUgb2YgQ29tcHJlc3Npb24oMHgwMTAzKSBUYWcgaW4gSUZEMSBpcyAnNicsIHRodW1ibmFpbCBpbWFnZSBmb3JtYXQgaXMgSlBFRy5cbiAgICAgICAgLy8gTW9zdCBvZiBFeGlmIGltYWdlIHVzZXMgSlBFRyBmb3JtYXQgZm9yIHRodW1ibmFpbC4gSW4gdGhhdCBjYXNlLCB5b3UgY2FuIGdldCBvZmZzZXQgb2YgdGh1bWJuYWlsXG4gICAgICAgIC8vIGJ5IEpwZWdJRk9mZnNldCgweDAyMDEpIFRhZyBpbiBJRkQxLCBzaXplIG9mIHRodW1ibmFpbCBieSBKcGVnSUZCeXRlQ291bnQoMHgwMjAyKSBUYWcuXG4gICAgICAgIC8vIERhdGEgZm9ybWF0IGlzIG9yZGluYXJ5IEpQRUcgZm9ybWF0LCBzdGFydHMgZnJvbSAweEZGRDggYW5kIGVuZHMgYnkgMHhGRkQ5LiBJdCBzZWVtcyB0aGF0XG4gICAgICAgIC8vIEpQRUcgZm9ybWF0IGFuZCAxNjB4MTIwcGl4ZWxzIG9mIHNpemUgYXJlIHJlY29tbWVuZGVkIHRodW1ibmFpbCBmb3JtYXQgZm9yIEV4aWYyLjEgb3IgbGF0ZXIuXG5cbiAgICAgICAgaWYgKHRodW1iVGFnc1snQ29tcHJlc3Npb24nXSkge1xuICAgICAgICAgICAgLy8gY29uc29sZS5sb2coJ1RodW1ibmFpbCBpbWFnZSBmb3VuZCEnKTtcblxuICAgICAgICAgICAgc3dpdGNoICh0aHVtYlRhZ3NbJ0NvbXByZXNzaW9uJ10pIHtcbiAgICAgICAgICAgICAgICBjYXNlIDY6XG4gICAgICAgICAgICAgICAgICAgIC8vIGNvbnNvbGUubG9nKCdUaHVtYm5haWwgaW1hZ2UgZm9ybWF0IGlzIEpQRUcnKTtcbiAgICAgICAgICAgICAgICAgICAgaWYgKHRodW1iVGFncy5KcGVnSUZPZmZzZXQgJiYgdGh1bWJUYWdzLkpwZWdJRkJ5dGVDb3VudCkge1xuICAgICAgICAgICAgICAgICAgICAvLyBleHRyYWN0IHRoZSB0aHVtYm5haWxcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciB0T2Zmc2V0ID0gdGlmZlN0YXJ0ICsgdGh1bWJUYWdzLkpwZWdJRk9mZnNldDtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciB0TGVuZ3RoID0gdGh1bWJUYWdzLkpwZWdJRkJ5dGVDb3VudDtcbiAgICAgICAgICAgICAgICAgICAgICAgIHRodW1iVGFnc1snYmxvYiddID0gbmV3IEJsb2IoW25ldyBVaW50OEFycmF5KGRhdGFWaWV3LmJ1ZmZlciwgdE9mZnNldCwgdExlbmd0aCldLCB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdHlwZTogJ2ltYWdlL2pwZWcnXG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGJyZWFrO1xuXG4gICAgICAgICAgICBjYXNlIDE6XG4gICAgICAgICAgICAgICAgY29uc29sZS5sb2coXCJUaHVtYm5haWwgaW1hZ2UgZm9ybWF0IGlzIFRJRkYsIHdoaWNoIGlzIG5vdCBpbXBsZW1lbnRlZC5cIik7XG4gICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICBkZWZhdWx0OlxuICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKFwiVW5rbm93biB0aHVtYm5haWwgaW1hZ2UgZm9ybWF0ICclcydcIiwgdGh1bWJUYWdzWydDb21wcmVzc2lvbiddKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICBlbHNlIGlmICh0aHVtYlRhZ3NbJ1Bob3RvbWV0cmljSW50ZXJwcmV0YXRpb24nXSA9PSAyKSB7XG4gICAgICAgICAgICBjb25zb2xlLmxvZyhcIlRodW1ibmFpbCBpbWFnZSBmb3JtYXQgaXMgUkdCLCB3aGljaCBpcyBub3QgaW1wbGVtZW50ZWQuXCIpO1xuICAgICAgICB9XG4gICAgICAgIHJldHVybiB0aHVtYlRhZ3M7XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gZ2V0U3RyaW5nRnJvbURCKGJ1ZmZlciwgc3RhcnQsIGxlbmd0aCkge1xuICAgICAgICB2YXIgb3V0c3RyID0gXCJcIjtcbiAgICAgICAgZm9yICh2YXIgbiA9IHN0YXJ0OyBuIDwgc3RhcnQrbGVuZ3RoOyBuKyspIHtcbiAgICAgICAgICAgIG91dHN0ciArPSBTdHJpbmcuZnJvbUNoYXJDb2RlKGJ1ZmZlci5nZXRVaW50OChuKSk7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIG91dHN0cjtcbiAgICB9XG5cbiAgICBmdW5jdGlvbiByZWFkRVhJRkRhdGEoZmlsZSwgc3RhcnQpIHtcbiAgICAgICAgaWYgKGdldFN0cmluZ0Zyb21EQihmaWxlLCBzdGFydCwgNCkgIT0gXCJFeGlmXCIpIHtcbiAgICAgICAgICAgIGlmIChkZWJ1ZykgY29uc29sZS5sb2coXCJOb3QgdmFsaWQgRVhJRiBkYXRhISBcIiArIGdldFN0cmluZ0Zyb21EQihmaWxlLCBzdGFydCwgNCkpO1xuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICB9XG5cbiAgICAgICAgdmFyIGJpZ0VuZCxcbiAgICAgICAgICAgIHRhZ3MsIHRhZyxcbiAgICAgICAgICAgIGV4aWZEYXRhLCBncHNEYXRhLFxuICAgICAgICAgICAgdGlmZk9mZnNldCA9IHN0YXJ0ICsgNjtcblxuICAgICAgICAvLyB0ZXN0IGZvciBUSUZGIHZhbGlkaXR5IGFuZCBlbmRpYW5uZXNzXG4gICAgICAgIGlmIChmaWxlLmdldFVpbnQxNih0aWZmT2Zmc2V0KSA9PSAweDQ5NDkpIHtcbiAgICAgICAgICAgIGJpZ0VuZCA9IGZhbHNlO1xuICAgICAgICB9IGVsc2UgaWYgKGZpbGUuZ2V0VWludDE2KHRpZmZPZmZzZXQpID09IDB4NEQ0RCkge1xuICAgICAgICAgICAgYmlnRW5kID0gdHJ1ZTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIGlmIChkZWJ1ZykgY29uc29sZS5sb2coXCJOb3QgdmFsaWQgVElGRiBkYXRhISAobm8gMHg0OTQ5IG9yIDB4NEQ0RClcIik7XG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAoZmlsZS5nZXRVaW50MTYodGlmZk9mZnNldCsyLCAhYmlnRW5kKSAhPSAweDAwMkEpIHtcbiAgICAgICAgICAgIGlmIChkZWJ1ZykgY29uc29sZS5sb2coXCJOb3QgdmFsaWQgVElGRiBkYXRhISAobm8gMHgwMDJBKVwiKTtcbiAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfVxuXG4gICAgICAgIHZhciBmaXJzdElGRE9mZnNldCA9IGZpbGUuZ2V0VWludDMyKHRpZmZPZmZzZXQrNCwgIWJpZ0VuZCk7XG5cbiAgICAgICAgaWYgKGZpcnN0SUZET2Zmc2V0IDwgMHgwMDAwMDAwOCkge1xuICAgICAgICAgICAgaWYgKGRlYnVnKSBjb25zb2xlLmxvZyhcIk5vdCB2YWxpZCBUSUZGIGRhdGEhIChGaXJzdCBvZmZzZXQgbGVzcyB0aGFuIDgpXCIsIGZpbGUuZ2V0VWludDMyKHRpZmZPZmZzZXQrNCwgIWJpZ0VuZCkpO1xuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICB9XG5cbiAgICAgICAgdGFncyA9IHJlYWRUYWdzKGZpbGUsIHRpZmZPZmZzZXQsIHRpZmZPZmZzZXQgKyBmaXJzdElGRE9mZnNldCwgVGlmZlRhZ3MsIGJpZ0VuZCk7XG5cbiAgICAgICAgaWYgKHRhZ3MuRXhpZklGRFBvaW50ZXIpIHtcbiAgICAgICAgICAgIGV4aWZEYXRhID0gcmVhZFRhZ3MoZmlsZSwgdGlmZk9mZnNldCwgdGlmZk9mZnNldCArIHRhZ3MuRXhpZklGRFBvaW50ZXIsIEV4aWZUYWdzLCBiaWdFbmQpO1xuICAgICAgICAgICAgZm9yICh0YWcgaW4gZXhpZkRhdGEpIHtcbiAgICAgICAgICAgICAgICBzd2l0Y2ggKHRhZykge1xuICAgICAgICAgICAgICAgICAgICBjYXNlIFwiTGlnaHRTb3VyY2VcIiA6XG4gICAgICAgICAgICAgICAgICAgIGNhc2UgXCJGbGFzaFwiIDpcbiAgICAgICAgICAgICAgICAgICAgY2FzZSBcIk1ldGVyaW5nTW9kZVwiIDpcbiAgICAgICAgICAgICAgICAgICAgY2FzZSBcIkV4cG9zdXJlUHJvZ3JhbVwiIDpcbiAgICAgICAgICAgICAgICAgICAgY2FzZSBcIlNlbnNpbmdNZXRob2RcIiA6XG4gICAgICAgICAgICAgICAgICAgIGNhc2UgXCJTY2VuZUNhcHR1cmVUeXBlXCIgOlxuICAgICAgICAgICAgICAgICAgICBjYXNlIFwiU2NlbmVUeXBlXCIgOlxuICAgICAgICAgICAgICAgICAgICBjYXNlIFwiQ3VzdG9tUmVuZGVyZWRcIiA6XG4gICAgICAgICAgICAgICAgICAgIGNhc2UgXCJXaGl0ZUJhbGFuY2VcIiA6XG4gICAgICAgICAgICAgICAgICAgIGNhc2UgXCJHYWluQ29udHJvbFwiIDpcbiAgICAgICAgICAgICAgICAgICAgY2FzZSBcIkNvbnRyYXN0XCIgOlxuICAgICAgICAgICAgICAgICAgICBjYXNlIFwiU2F0dXJhdGlvblwiIDpcbiAgICAgICAgICAgICAgICAgICAgY2FzZSBcIlNoYXJwbmVzc1wiIDpcbiAgICAgICAgICAgICAgICAgICAgY2FzZSBcIlN1YmplY3REaXN0YW5jZVJhbmdlXCIgOlxuICAgICAgICAgICAgICAgICAgICBjYXNlIFwiRmlsZVNvdXJjZVwiIDpcbiAgICAgICAgICAgICAgICAgICAgICAgIGV4aWZEYXRhW3RhZ10gPSBTdHJpbmdWYWx1ZXNbdGFnXVtleGlmRGF0YVt0YWddXTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGJyZWFrO1xuXG4gICAgICAgICAgICAgICAgICAgIGNhc2UgXCJFeGlmVmVyc2lvblwiIDpcbiAgICAgICAgICAgICAgICAgICAgY2FzZSBcIkZsYXNocGl4VmVyc2lvblwiIDpcbiAgICAgICAgICAgICAgICAgICAgICAgIGV4aWZEYXRhW3RhZ10gPSBTdHJpbmcuZnJvbUNoYXJDb2RlKGV4aWZEYXRhW3RhZ11bMF0sIGV4aWZEYXRhW3RhZ11bMV0sIGV4aWZEYXRhW3RhZ11bMl0sIGV4aWZEYXRhW3RhZ11bM10pO1xuICAgICAgICAgICAgICAgICAgICAgICAgYnJlYWs7XG5cbiAgICAgICAgICAgICAgICAgICAgY2FzZSBcIkNvbXBvbmVudHNDb25maWd1cmF0aW9uXCIgOlxuICAgICAgICAgICAgICAgICAgICAgICAgZXhpZkRhdGFbdGFnXSA9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgU3RyaW5nVmFsdWVzLkNvbXBvbmVudHNbZXhpZkRhdGFbdGFnXVswXV0gK1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFN0cmluZ1ZhbHVlcy5Db21wb25lbnRzW2V4aWZEYXRhW3RhZ11bMV1dICtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBTdHJpbmdWYWx1ZXMuQ29tcG9uZW50c1tleGlmRGF0YVt0YWddWzJdXSArXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgU3RyaW5nVmFsdWVzLkNvbXBvbmVudHNbZXhpZkRhdGFbdGFnXVszXV07XG4gICAgICAgICAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgdGFnc1t0YWddID0gZXhpZkRhdGFbdGFnXTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuXG4gICAgICAgIGlmICh0YWdzLkdQU0luZm9JRkRQb2ludGVyKSB7XG4gICAgICAgICAgICBncHNEYXRhID0gcmVhZFRhZ3MoZmlsZSwgdGlmZk9mZnNldCwgdGlmZk9mZnNldCArIHRhZ3MuR1BTSW5mb0lGRFBvaW50ZXIsIEdQU1RhZ3MsIGJpZ0VuZCk7XG4gICAgICAgICAgICBmb3IgKHRhZyBpbiBncHNEYXRhKSB7XG4gICAgICAgICAgICAgICAgc3dpdGNoICh0YWcpIHtcbiAgICAgICAgICAgICAgICAgICAgY2FzZSBcIkdQU1ZlcnNpb25JRFwiIDpcbiAgICAgICAgICAgICAgICAgICAgICAgIGdwc0RhdGFbdGFnXSA9IGdwc0RhdGFbdGFnXVswXSArXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXCIuXCIgKyBncHNEYXRhW3RhZ11bMV0gK1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFwiLlwiICsgZ3BzRGF0YVt0YWddWzJdICtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcIi5cIiArIGdwc0RhdGFbdGFnXVszXTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB0YWdzW3RhZ10gPSBncHNEYXRhW3RhZ107XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cblxuICAgICAgICAvLyBleHRyYWN0IHRodW1ibmFpbFxuICAgICAgICB0YWdzWyd0aHVtYm5haWwnXSA9IHJlYWRUaHVtYm5haWxJbWFnZShmaWxlLCB0aWZmT2Zmc2V0LCBmaXJzdElGRE9mZnNldCwgYmlnRW5kKTtcblxuICAgICAgICByZXR1cm4gdGFncztcbiAgICB9XG5cbiAgIGZ1bmN0aW9uIGZpbmRYTVBpbkpQRUcoZmlsZSkge1xuXG4gICAgICAgIGlmICghKCdET01QYXJzZXInIGluIHNlbGYpKSB7XG4gICAgICAgICAgICAvLyBjb25zb2xlLndhcm4oJ1hNTCBwYXJzaW5nIG5vdCBzdXBwb3J0ZWQgd2l0aG91dCBET01QYXJzZXInKTtcbiAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuICAgICAgICB2YXIgZGF0YVZpZXcgPSBuZXcgRGF0YVZpZXcoZmlsZSk7XG5cbiAgICAgICAgaWYgKGRlYnVnKSBjb25zb2xlLmxvZyhcIkdvdCBmaWxlIG9mIGxlbmd0aCBcIiArIGZpbGUuYnl0ZUxlbmd0aCk7XG4gICAgICAgIGlmICgoZGF0YVZpZXcuZ2V0VWludDgoMCkgIT0gMHhGRikgfHwgKGRhdGFWaWV3LmdldFVpbnQ4KDEpICE9IDB4RDgpKSB7XG4gICAgICAgICAgIGlmIChkZWJ1ZykgY29uc29sZS5sb2coXCJOb3QgYSB2YWxpZCBKUEVHXCIpO1xuICAgICAgICAgICByZXR1cm4gZmFsc2U7IC8vIG5vdCBhIHZhbGlkIGpwZWdcbiAgICAgICAgfVxuXG4gICAgICAgIHZhciBvZmZzZXQgPSAyLFxuICAgICAgICAgICAgbGVuZ3RoID0gZmlsZS5ieXRlTGVuZ3RoLFxuICAgICAgICAgICAgZG9tID0gbmV3IERPTVBhcnNlcigpO1xuXG4gICAgICAgIHdoaWxlIChvZmZzZXQgPCAobGVuZ3RoLTQpKSB7XG4gICAgICAgICAgICBpZiAoZ2V0U3RyaW5nRnJvbURCKGRhdGFWaWV3LCBvZmZzZXQsIDQpID09IFwiaHR0cFwiKSB7XG4gICAgICAgICAgICAgICAgdmFyIHN0YXJ0T2Zmc2V0ID0gb2Zmc2V0IC0gMTtcbiAgICAgICAgICAgICAgICB2YXIgc2VjdGlvbkxlbmd0aCA9IGRhdGFWaWV3LmdldFVpbnQxNihvZmZzZXQgLSAyKSAtIDE7XG4gICAgICAgICAgICAgICAgdmFyIHhtcFN0cmluZyA9IGdldFN0cmluZ0Zyb21EQihkYXRhVmlldywgc3RhcnRPZmZzZXQsIHNlY3Rpb25MZW5ndGgpXG4gICAgICAgICAgICAgICAgdmFyIHhtcEVuZEluZGV4ID0geG1wU3RyaW5nLmluZGV4T2YoJ3htcG1ldGE+JykgKyA4O1xuICAgICAgICAgICAgICAgIHhtcFN0cmluZyA9IHhtcFN0cmluZy5zdWJzdHJpbmcoIHhtcFN0cmluZy5pbmRleE9mKCAnPHg6eG1wbWV0YScgKSwgeG1wRW5kSW5kZXggKTtcblxuICAgICAgICAgICAgICAgIHZhciBpbmRleE9mWG1wID0geG1wU3RyaW5nLmluZGV4T2YoJ3g6eG1wbWV0YScpICsgMTBcbiAgICAgICAgICAgICAgICAvL01hbnkgY3VzdG9tIHdyaXR0ZW4gcHJvZ3JhbXMgZW1iZWQgeG1wL3htbCB3aXRob3V0IGFueSBuYW1lc3BhY2UuIEZvbGxvd2luZyBhcmUgc29tZSBvZiB0aGVtLlxuICAgICAgICAgICAgICAgIC8vV2l0aG91dCB0aGVzZSBuYW1lc3BhY2VzLCBYTUwgaXMgdGhvdWdodCB0byBiZSBpbnZhbGlkIGJ5IHBhcnNlcnNcbiAgICAgICAgICAgICAgICB4bXBTdHJpbmcgPSB4bXBTdHJpbmcuc2xpY2UoMCwgaW5kZXhPZlhtcClcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICArICd4bWxuczpJcHRjNHhtcENvcmU9XCJodHRwOi8vaXB0Yy5vcmcvc3RkL0lwdGM0eG1wQ29yZS8xLjAveG1sbnMvXCIgJ1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICsgJ3htbG5zOnhzaT1cImh0dHA6Ly93d3cudzMub3JnLzIwMDEvWE1MU2NoZW1hLWluc3RhbmNlXCIgJ1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICsgJ3htbG5zOnRpZmY9XCJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wL1wiICdcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICArICd4bWxuczpwbHVzPVwiaHR0cDovL3NjaGVtYXMuYW5kcm9pZC5jb20vYXBrL2xpYi9jb20uZ29vZ2xlLmFuZHJvaWQuZ21zLnBsdXNcIiAnXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgKyAneG1sbnM6ZXh0PVwiaHR0cDovL3d3dy5nZXR0eWltYWdlcy5jb20veHNsdEV4dGVuc2lvbi8xLjBcIiAnXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgKyAneG1sbnM6ZXhpZj1cImh0dHA6Ly9ucy5hZG9iZS5jb20vZXhpZi8xLjAvXCIgJ1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICsgJ3htbG5zOnN0RXZ0PVwiaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlRXZlbnQjXCIgJ1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICsgJ3htbG5zOnN0UmVmPVwiaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmI1wiICdcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICArICd4bWxuczpjcnM9XCJodHRwOi8vbnMuYWRvYmUuY29tL2NhbWVyYS1yYXctc2V0dGluZ3MvMS4wL1wiICdcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICArICd4bWxuczp4YXBHSW1nPVwiaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL2cvaW1nL1wiICdcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICArICd4bWxuczpJcHRjNHhtcEV4dD1cImh0dHA6Ly9pcHRjLm9yZy9zdGQvSXB0YzR4bXBFeHQvMjAwOC0wMi0yOS9cIiAnXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgKyB4bXBTdHJpbmcuc2xpY2UoaW5kZXhPZlhtcClcblxuICAgICAgICAgICAgICAgIHZhciBkb21Eb2N1bWVudCA9IGRvbS5wYXJzZUZyb21TdHJpbmcoIHhtcFN0cmluZywgJ3RleHQveG1sJyApO1xuICAgICAgICAgICAgICAgIHJldHVybiB4bWwyT2JqZWN0KGRvbURvY3VtZW50KTtcbiAgICAgICAgICAgIH0gZWxzZXtcbiAgICAgICAgICAgICBvZmZzZXQrKztcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH1cblxuICAgIGZ1bmN0aW9uIHhtbDJqc29uKHhtbCkge1xuICAgICAgICB2YXIganNvbiA9IHt9O1xuICAgICAgXG4gICAgICAgIGlmICh4bWwubm9kZVR5cGUgPT0gMSkgeyAvLyBlbGVtZW50IG5vZGVcbiAgICAgICAgICBpZiAoeG1sLmF0dHJpYnV0ZXMubGVuZ3RoID4gMCkge1xuICAgICAgICAgICAganNvblsnQGF0dHJpYnV0ZXMnXSA9IHt9O1xuICAgICAgICAgICAgZm9yICh2YXIgaiA9IDA7IGogPCB4bWwuYXR0cmlidXRlcy5sZW5ndGg7IGorKykge1xuICAgICAgICAgICAgICB2YXIgYXR0cmlidXRlID0geG1sLmF0dHJpYnV0ZXMuaXRlbShqKTtcbiAgICAgICAgICAgICAganNvblsnQGF0dHJpYnV0ZXMnXVthdHRyaWJ1dGUubm9kZU5hbWVdID0gYXR0cmlidXRlLm5vZGVWYWx1ZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICB9XG4gICAgICAgIH0gZWxzZSBpZiAoeG1sLm5vZGVUeXBlID09IDMpIHsgLy8gdGV4dCBub2RlXG4gICAgICAgICAgcmV0dXJuIHhtbC5ub2RlVmFsdWU7XG4gICAgICAgIH1cbiAgICAgIFxuICAgICAgICAvLyBkZWFsIHdpdGggY2hpbGRyZW5cbiAgICAgICAgaWYgKHhtbC5oYXNDaGlsZE5vZGVzKCkpIHtcbiAgICAgICAgICBmb3IodmFyIGkgPSAwOyBpIDwgeG1sLmNoaWxkTm9kZXMubGVuZ3RoOyBpKyspIHtcbiAgICAgICAgICAgIHZhciBjaGlsZCA9IHhtbC5jaGlsZE5vZGVzLml0ZW0oaSk7XG4gICAgICAgICAgICB2YXIgbm9kZU5hbWUgPSBjaGlsZC5ub2RlTmFtZTtcbiAgICAgICAgICAgIGlmIChqc29uW25vZGVOYW1lXSA9PSBudWxsKSB7XG4gICAgICAgICAgICAgIGpzb25bbm9kZU5hbWVdID0geG1sMmpzb24oY2hpbGQpO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgaWYgKGpzb25bbm9kZU5hbWVdLnB1c2ggPT0gbnVsbCkge1xuICAgICAgICAgICAgICAgIHZhciBvbGQgPSBqc29uW25vZGVOYW1lXTtcbiAgICAgICAgICAgICAgICBqc29uW25vZGVOYW1lXSA9IFtdO1xuICAgICAgICAgICAgICAgIGpzb25bbm9kZU5hbWVdLnB1c2gob2xkKTtcbiAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICBqc29uW25vZGVOYW1lXS5wdXNoKHhtbDJqc29uKGNoaWxkKSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICAgIFxuICAgICAgICByZXR1cm4ganNvbjtcbiAgICB9XG5cbiAgICBmdW5jdGlvbiB4bWwyT2JqZWN0KHhtbCkge1xuICAgICAgICB0cnkge1xuICAgICAgICAgICAgdmFyIG9iaiA9IHt9O1xuICAgICAgICAgICAgaWYgKHhtbC5jaGlsZHJlbi5sZW5ndGggPiAwKSB7XG4gICAgICAgICAgICAgIGZvciAodmFyIGkgPSAwOyBpIDwgeG1sLmNoaWxkcmVuLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgICAgICAgICAgdmFyIGl0ZW0gPSB4bWwuY2hpbGRyZW4uaXRlbShpKTtcbiAgICAgICAgICAgICAgICB2YXIgYXR0cmlidXRlcyA9IGl0ZW0uYXR0cmlidXRlcztcbiAgICAgICAgICAgICAgICBmb3IodmFyIGlkeCBpbiBhdHRyaWJ1dGVzKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciBpdGVtQXR0ID0gYXR0cmlidXRlc1tpZHhdO1xuICAgICAgICAgICAgICAgICAgICB2YXIgZGF0YUtleSA9IGl0ZW1BdHQubm9kZU5hbWU7XG4gICAgICAgICAgICAgICAgICAgIHZhciBkYXRhVmFsdWUgPSBpdGVtQXR0Lm5vZGVWYWx1ZTtcblxuICAgICAgICAgICAgICAgICAgICBpZihkYXRhS2V5ICE9PSB1bmRlZmluZWQpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG9ialtkYXRhS2V5XSA9IGRhdGFWYWx1ZTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB2YXIgbm9kZU5hbWUgPSBpdGVtLm5vZGVOYW1lO1xuXG4gICAgICAgICAgICAgICAgaWYgKHR5cGVvZiAob2JqW25vZGVOYW1lXSkgPT0gXCJ1bmRlZmluZWRcIikge1xuICAgICAgICAgICAgICAgICAgb2JqW25vZGVOYW1lXSA9IHhtbDJqc29uKGl0ZW0pO1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICBpZiAodHlwZW9mIChvYmpbbm9kZU5hbWVdLnB1c2gpID09IFwidW5kZWZpbmVkXCIpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIG9sZCA9IG9ialtub2RlTmFtZV07XG5cbiAgICAgICAgICAgICAgICAgICAgb2JqW25vZGVOYW1lXSA9IFtdO1xuICAgICAgICAgICAgICAgICAgICBvYmpbbm9kZU5hbWVdLnB1c2gob2xkKTtcbiAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgIG9ialtub2RlTmFtZV0ucHVzaCh4bWwyanNvbihpdGVtKSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICBvYmogPSB4bWwudGV4dENvbnRlbnQ7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICByZXR1cm4gb2JqO1xuICAgICAgICAgIH0gY2F0Y2ggKGUpIHtcbiAgICAgICAgICAgICAgY29uc29sZS5sb2coZS5tZXNzYWdlKTtcbiAgICAgICAgICB9XG4gICAgfVxuXG4gICAgRVhJRi5lbmFibGVYbXAgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgRVhJRi5pc1htcEVuYWJsZWQgPSB0cnVlO1xuICAgIH1cblxuICAgIEVYSUYuZGlzYWJsZVhtcCA9IGZ1bmN0aW9uKCkge1xuICAgICAgICBFWElGLmlzWG1wRW5hYmxlZCA9IGZhbHNlO1xuICAgIH1cblxuICAgIEVYSUYuZ2V0RGF0YSA9IGZ1bmN0aW9uKGltZywgY2FsbGJhY2spIHtcbiAgICAgICAgaWYgKCgoc2VsZi5JbWFnZSAmJiBpbWcgaW5zdGFuY2VvZiBzZWxmLkltYWdlKVxuICAgICAgICAgICAgfHwgKHNlbGYuSFRNTEltYWdlRWxlbWVudCAmJiBpbWcgaW5zdGFuY2VvZiBzZWxmLkhUTUxJbWFnZUVsZW1lbnQpKVxuICAgICAgICAgICAgJiYgIWltZy5jb21wbGV0ZSlcbiAgICAgICAgICAgIHJldHVybiBmYWxzZTtcblxuICAgICAgICBpZiAoIWltYWdlSGFzRGF0YShpbWcpKSB7XG4gICAgICAgICAgICBnZXRJbWFnZURhdGEoaW1nLCBjYWxsYmFjayk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBpZiAoY2FsbGJhY2spIHtcbiAgICAgICAgICAgICAgICBjYWxsYmFjay5jYWxsKGltZyk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgfVxuXG4gICAgRVhJRi5nZXRUYWcgPSBmdW5jdGlvbihpbWcsIHRhZykge1xuICAgICAgICBpZiAoIWltYWdlSGFzRGF0YShpbWcpKSByZXR1cm47XG4gICAgICAgIHJldHVybiBpbWcuZXhpZmRhdGFbdGFnXTtcbiAgICB9XG4gICAgXG4gICAgRVhJRi5nZXRJcHRjVGFnID0gZnVuY3Rpb24oaW1nLCB0YWcpIHtcbiAgICAgICAgaWYgKCFpbWFnZUhhc0RhdGEoaW1nKSkgcmV0dXJuO1xuICAgICAgICByZXR1cm4gaW1nLmlwdGNkYXRhW3RhZ107XG4gICAgfVxuXG4gICAgRVhJRi5nZXRBbGxUYWdzID0gZnVuY3Rpb24oaW1nKSB7XG4gICAgICAgIGlmICghaW1hZ2VIYXNEYXRhKGltZykpIHJldHVybiB7fTtcbiAgICAgICAgdmFyIGEsXG4gICAgICAgICAgICBkYXRhID0gaW1nLmV4aWZkYXRhLFxuICAgICAgICAgICAgdGFncyA9IHt9O1xuICAgICAgICBmb3IgKGEgaW4gZGF0YSkge1xuICAgICAgICAgICAgaWYgKGRhdGEuaGFzT3duUHJvcGVydHkoYSkpIHtcbiAgICAgICAgICAgICAgICB0YWdzW2FdID0gZGF0YVthXTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gdGFncztcbiAgICB9XG4gICAgXG4gICAgRVhJRi5nZXRBbGxJcHRjVGFncyA9IGZ1bmN0aW9uKGltZykge1xuICAgICAgICBpZiAoIWltYWdlSGFzRGF0YShpbWcpKSByZXR1cm4ge307XG4gICAgICAgIHZhciBhLFxuICAgICAgICAgICAgZGF0YSA9IGltZy5pcHRjZGF0YSxcbiAgICAgICAgICAgIHRhZ3MgPSB7fTtcbiAgICAgICAgZm9yIChhIGluIGRhdGEpIHtcbiAgICAgICAgICAgIGlmIChkYXRhLmhhc093blByb3BlcnR5KGEpKSB7XG4gICAgICAgICAgICAgICAgdGFnc1thXSA9IGRhdGFbYV07XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIHRhZ3M7XG4gICAgfVxuXG4gICAgRVhJRi5wcmV0dHkgPSBmdW5jdGlvbihpbWcpIHtcbiAgICAgICAgaWYgKCFpbWFnZUhhc0RhdGEoaW1nKSkgcmV0dXJuIFwiXCI7XG4gICAgICAgIHZhciBhLFxuICAgICAgICAgICAgZGF0YSA9IGltZy5leGlmZGF0YSxcbiAgICAgICAgICAgIHN0clByZXR0eSA9IFwiXCI7XG4gICAgICAgIGZvciAoYSBpbiBkYXRhKSB7XG4gICAgICAgICAgICBpZiAoZGF0YS5oYXNPd25Qcm9wZXJ0eShhKSkge1xuICAgICAgICAgICAgICAgIGlmICh0eXBlb2YgZGF0YVthXSA9PSBcIm9iamVjdFwiKSB7XG4gICAgICAgICAgICAgICAgICAgIGlmIChkYXRhW2FdIGluc3RhbmNlb2YgTnVtYmVyKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBzdHJQcmV0dHkgKz0gYSArIFwiIDogXCIgKyBkYXRhW2FdICsgXCIgW1wiICsgZGF0YVthXS5udW1lcmF0b3IgKyBcIi9cIiArIGRhdGFbYV0uZGVub21pbmF0b3IgKyBcIl1cXHJcXG5cIjtcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHN0clByZXR0eSArPSBhICsgXCIgOiBbXCIgKyBkYXRhW2FdLmxlbmd0aCArIFwiIHZhbHVlc11cXHJcXG5cIjtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIHN0clByZXR0eSArPSBhICsgXCIgOiBcIiArIGRhdGFbYV0gKyBcIlxcclxcblwiO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gc3RyUHJldHR5O1xuICAgIH1cblxuICAgIEVYSUYucmVhZEZyb21CaW5hcnlGaWxlID0gZnVuY3Rpb24oZmlsZSkge1xuICAgICAgICByZXR1cm4gZmluZEVYSUZpbkpQRUcoZmlsZSk7XG4gICAgfVxuXG4gICAgaWYgKHR5cGVvZiBkZWZpbmUgPT09ICdmdW5jdGlvbicgJiYgZGVmaW5lLmFtZCkge1xuICAgICAgICBkZWZpbmUoJ2V4aWYtanMnLCBbXSwgZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICByZXR1cm4gRVhJRjtcbiAgICAgICAgfSk7XG4gICAgfVxufS5jYWxsKHRoaXMpKTtcbiIsIi8qKioqKioqKioqKioqKioqKioqKioqKioqXG4gKiBDcm9wcGllXG4gKiBDb3B5cmlnaHQgMjAxOFxuICogRm9saW90ZWtcbiAqIFZlcnNpb246IDIuNi4yXG4gKioqKioqKioqKioqKioqKioqKioqKioqKi9cbihmdW5jdGlvbiAocm9vdCwgZmFjdG9yeSkge1xuICAgIGlmICh0eXBlb2YgZGVmaW5lID09PSAnZnVuY3Rpb24nICYmIGRlZmluZS5hbWQpIHtcbiAgICAgICAgLy8gQU1ELiBSZWdpc3RlciBhcyBhbiBhbm9ueW1vdXMgbW9kdWxlLlxuICAgICAgICBkZWZpbmUoWydleHBvcnRzJ10sIGZhY3RvcnkpO1xuICAgIH0gZWxzZSBpZiAodHlwZW9mIGV4cG9ydHMgPT09ICdvYmplY3QnICYmIHR5cGVvZiBleHBvcnRzLm5vZGVOYW1lICE9PSAnc3RyaW5nJykge1xuICAgICAgICAvLyBDb21tb25KU1xuICAgICAgICBmYWN0b3J5KGV4cG9ydHMpO1xuICAgIH0gZWxzZSB7XG4gICAgICAgIC8vIEJyb3dzZXIgZ2xvYmFsc1xuICAgICAgICBmYWN0b3J5KChyb290LmNvbW1vbkpzU3RyaWN0ID0ge30pKTtcbiAgICB9XG59KHRoaXMsIGZ1bmN0aW9uIChleHBvcnRzKSB7XG5cbiAgICAvKiBQb2x5ZmlsbHMgKi9cbiAgICBpZiAodHlwZW9mIFByb21pc2UgIT09ICdmdW5jdGlvbicpIHtcbiAgICAgICAgLyohIHByb21pc2UtcG9seWZpbGwgMy4xLjAgKi9cbiAgICAgICAgIWZ1bmN0aW9uKGEpe2Z1bmN0aW9uIGIoYSxiKXtyZXR1cm4gZnVuY3Rpb24oKXthLmFwcGx5KGIsYXJndW1lbnRzKX19ZnVuY3Rpb24gYyhhKXtpZihcIm9iamVjdFwiIT09dHlwZW9mIHRoaXMpdGhyb3cgbmV3IFR5cGVFcnJvcihcIlByb21pc2VzIG11c3QgYmUgY29uc3RydWN0ZWQgdmlhIG5ld1wiKTtpZihcImZ1bmN0aW9uXCIhPT10eXBlb2YgYSl0aHJvdyBuZXcgVHlwZUVycm9yKFwibm90IGEgZnVuY3Rpb25cIik7dGhpcy5fc3RhdGU9bnVsbCx0aGlzLl92YWx1ZT1udWxsLHRoaXMuX2RlZmVycmVkcz1bXSxpKGEsYihlLHRoaXMpLGIoZix0aGlzKSl9ZnVuY3Rpb24gZChhKXt2YXIgYj10aGlzO3JldHVybiBudWxsPT09dGhpcy5fc3RhdGU/dm9pZCB0aGlzLl9kZWZlcnJlZHMucHVzaChhKTp2b2lkIGsoZnVuY3Rpb24oKXt2YXIgYz1iLl9zdGF0ZT9hLm9uRnVsZmlsbGVkOmEub25SZWplY3RlZDtpZihudWxsPT09YylyZXR1cm4gdm9pZChiLl9zdGF0ZT9hLnJlc29sdmU6YS5yZWplY3QpKGIuX3ZhbHVlKTt2YXIgZDt0cnl7ZD1jKGIuX3ZhbHVlKX1jYXRjaChlKXtyZXR1cm4gdm9pZCBhLnJlamVjdChlKX1hLnJlc29sdmUoZCl9KX1mdW5jdGlvbiBlKGEpe3RyeXtpZihhPT09dGhpcyl0aHJvdyBuZXcgVHlwZUVycm9yKFwiQSBwcm9taXNlIGNhbm5vdCBiZSByZXNvbHZlZCB3aXRoIGl0c2VsZi5cIik7aWYoYSYmKFwib2JqZWN0XCI9PT10eXBlb2YgYXx8XCJmdW5jdGlvblwiPT09dHlwZW9mIGEpKXt2YXIgYz1hLnRoZW47aWYoXCJmdW5jdGlvblwiPT09dHlwZW9mIGMpcmV0dXJuIHZvaWQgaShiKGMsYSksYihlLHRoaXMpLGIoZix0aGlzKSl9dGhpcy5fc3RhdGU9ITAsdGhpcy5fdmFsdWU9YSxnLmNhbGwodGhpcyl9Y2F0Y2goZCl7Zi5jYWxsKHRoaXMsZCl9fWZ1bmN0aW9uIGYoYSl7dGhpcy5fc3RhdGU9ITEsdGhpcy5fdmFsdWU9YSxnLmNhbGwodGhpcyl9ZnVuY3Rpb24gZygpe2Zvcih2YXIgYT0wLGI9dGhpcy5fZGVmZXJyZWRzLmxlbmd0aDtiPmE7YSsrKWQuY2FsbCh0aGlzLHRoaXMuX2RlZmVycmVkc1thXSk7dGhpcy5fZGVmZXJyZWRzPW51bGx9ZnVuY3Rpb24gaChhLGIsYyxkKXt0aGlzLm9uRnVsZmlsbGVkPVwiZnVuY3Rpb25cIj09PXR5cGVvZiBhP2E6bnVsbCx0aGlzLm9uUmVqZWN0ZWQ9XCJmdW5jdGlvblwiPT09dHlwZW9mIGI/YjpudWxsLHRoaXMucmVzb2x2ZT1jLHRoaXMucmVqZWN0PWR9ZnVuY3Rpb24gaShhLGIsYyl7dmFyIGQ9ITE7dHJ5e2EoZnVuY3Rpb24oYSl7ZHx8KGQ9ITAsYihhKSl9LGZ1bmN0aW9uKGEpe2R8fChkPSEwLGMoYSkpfSl9Y2F0Y2goZSl7aWYoZClyZXR1cm47ZD0hMCxjKGUpfX12YXIgaj1zZXRUaW1lb3V0LGs9XCJmdW5jdGlvblwiPT09dHlwZW9mIHNldEltbWVkaWF0ZSYmc2V0SW1tZWRpYXRlfHxmdW5jdGlvbihhKXtqKGEsMSl9LGw9QXJyYXkuaXNBcnJheXx8ZnVuY3Rpb24oYSl7cmV0dXJuXCJbb2JqZWN0IEFycmF5XVwiPT09T2JqZWN0LnByb3RvdHlwZS50b1N0cmluZy5jYWxsKGEpfTtjLnByb3RvdHlwZVtcImNhdGNoXCJdPWZ1bmN0aW9uKGEpe3JldHVybiB0aGlzLnRoZW4obnVsbCxhKX0sYy5wcm90b3R5cGUudGhlbj1mdW5jdGlvbihhLGIpe3ZhciBlPXRoaXM7cmV0dXJuIG5ldyBjKGZ1bmN0aW9uKGMsZil7ZC5jYWxsKGUsbmV3IGgoYSxiLGMsZikpfSl9LGMuYWxsPWZ1bmN0aW9uKCl7dmFyIGE9QXJyYXkucHJvdG90eXBlLnNsaWNlLmNhbGwoMT09PWFyZ3VtZW50cy5sZW5ndGgmJmwoYXJndW1lbnRzWzBdKT9hcmd1bWVudHNbMF06YXJndW1lbnRzKTtyZXR1cm4gbmV3IGMoZnVuY3Rpb24oYixjKXtmdW5jdGlvbiBkKGYsZyl7dHJ5e2lmKGcmJihcIm9iamVjdFwiPT09dHlwZW9mIGd8fFwiZnVuY3Rpb25cIj09PXR5cGVvZiBnKSl7dmFyIGg9Zy50aGVuO2lmKFwiZnVuY3Rpb25cIj09PXR5cGVvZiBoKXJldHVybiB2b2lkIGguY2FsbChnLGZ1bmN0aW9uKGEpe2QoZixhKX0sYyl9YVtmXT1nLDA9PT0tLWUmJmIoYSl9Y2F0Y2goaSl7YyhpKX19aWYoMD09PWEubGVuZ3RoKXJldHVybiBiKFtdKTtmb3IodmFyIGU9YS5sZW5ndGgsZj0wO2Y8YS5sZW5ndGg7ZisrKWQoZixhW2ZdKX0pfSxjLnJlc29sdmU9ZnVuY3Rpb24oYSl7cmV0dXJuIGEmJlwib2JqZWN0XCI9PT10eXBlb2YgYSYmYS5jb25zdHJ1Y3Rvcj09PWM/YTpuZXcgYyhmdW5jdGlvbihiKXtiKGEpfSl9LGMucmVqZWN0PWZ1bmN0aW9uKGEpe3JldHVybiBuZXcgYyhmdW5jdGlvbihiLGMpe2MoYSl9KX0sYy5yYWNlPWZ1bmN0aW9uKGEpe3JldHVybiBuZXcgYyhmdW5jdGlvbihiLGMpe2Zvcih2YXIgZD0wLGU9YS5sZW5ndGg7ZT5kO2QrKylhW2RdLnRoZW4oYixjKX0pfSxjLl9zZXRJbW1lZGlhdGVGbj1mdW5jdGlvbihhKXtrPWF9LFwidW5kZWZpbmVkXCIhPT10eXBlb2YgbW9kdWxlJiZtb2R1bGUuZXhwb3J0cz9tb2R1bGUuZXhwb3J0cz1jOmEuUHJvbWlzZXx8KGEuUHJvbWlzZT1jKX0odGhpcyk7XG4gICAgfVxuXG4gICAgaWYgKCB0eXBlb2Ygd2luZG93LkN1c3RvbUV2ZW50ICE9PSBcImZ1bmN0aW9uXCIgKSB7XG4gICAgICAgIChmdW5jdGlvbigpe1xuICAgICAgICAgICAgZnVuY3Rpb24gQ3VzdG9tRXZlbnQgKCBldmVudCwgcGFyYW1zICkge1xuICAgICAgICAgICAgICAgIHBhcmFtcyA9IHBhcmFtcyB8fCB7IGJ1YmJsZXM6IGZhbHNlLCBjYW5jZWxhYmxlOiBmYWxzZSwgZGV0YWlsOiB1bmRlZmluZWQgfTtcbiAgICAgICAgICAgICAgICB2YXIgZXZ0ID0gZG9jdW1lbnQuY3JlYXRlRXZlbnQoICdDdXN0b21FdmVudCcgKTtcbiAgICAgICAgICAgICAgICBldnQuaW5pdEN1c3RvbUV2ZW50KCBldmVudCwgcGFyYW1zLmJ1YmJsZXMsIHBhcmFtcy5jYW5jZWxhYmxlLCBwYXJhbXMuZGV0YWlsICk7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGV2dDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIEN1c3RvbUV2ZW50LnByb3RvdHlwZSA9IHdpbmRvdy5FdmVudC5wcm90b3R5cGU7XG4gICAgICAgICAgICB3aW5kb3cuQ3VzdG9tRXZlbnQgPSBDdXN0b21FdmVudDtcbiAgICAgICAgfSgpKTtcbiAgICB9XG5cbiAgICBpZiAoIUhUTUxDYW52YXNFbGVtZW50LnByb3RvdHlwZS50b0Jsb2IpIHtcbiAgICAgICAgT2JqZWN0LmRlZmluZVByb3BlcnR5KEhUTUxDYW52YXNFbGVtZW50LnByb3RvdHlwZSwgJ3RvQmxvYicsIHtcbiAgICAgICAgICAgIHZhbHVlOiBmdW5jdGlvbiAoY2FsbGJhY2ssIHR5cGUsIHF1YWxpdHkpIHtcbiAgICAgICAgICAgICAgICB2YXIgYmluU3RyID0gYXRvYiggdGhpcy50b0RhdGFVUkwodHlwZSwgcXVhbGl0eSkuc3BsaXQoJywnKVsxXSApLFxuICAgICAgICAgICAgICAgIGxlbiA9IGJpblN0ci5sZW5ndGgsXG4gICAgICAgICAgICAgICAgYXJyID0gbmV3IFVpbnQ4QXJyYXkobGVuKTtcblxuICAgICAgICAgICAgICAgIGZvciAodmFyIGk9MDsgaTxsZW47IGkrKyApIHtcbiAgICAgICAgICAgICAgICAgICAgYXJyW2ldID0gYmluU3RyLmNoYXJDb2RlQXQoaSk7XG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgY2FsbGJhY2soIG5ldyBCbG9iKCBbYXJyXSwge3R5cGU6IHR5cGUgfHwgJ2ltYWdlL3BuZyd9ICkgKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfVxuICAgIC8qIEVuZCBQb2x5ZmlsbHMgKi9cblxuICAgIHZhciBjc3NQcmVmaXhlcyA9IFsnV2Via2l0JywgJ01veicsICdtcyddLFxuICAgICAgICBlbXB0eVN0eWxlcyA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpLnN0eWxlLFxuICAgICAgICBFWElGX05PUk0gPSBbMSw4LDMsNl0sXG4gICAgICAgIEVYSUZfRkxJUCA9IFsyLDcsNCw1XSxcbiAgICAgICAgQ1NTX1RSQU5TX09SRyxcbiAgICAgICAgQ1NTX1RSQU5TRk9STSxcbiAgICAgICAgQ1NTX1VTRVJTRUxFQ1Q7XG5cbiAgICBmdW5jdGlvbiB2ZW5kb3JQcmVmaXgocHJvcCkge1xuICAgICAgICBpZiAocHJvcCBpbiBlbXB0eVN0eWxlcykge1xuICAgICAgICAgICAgcmV0dXJuIHByb3A7XG4gICAgICAgIH1cblxuICAgICAgICB2YXIgY2FwUHJvcCA9IHByb3BbMF0udG9VcHBlckNhc2UoKSArIHByb3Auc2xpY2UoMSksXG4gICAgICAgICAgICBpID0gY3NzUHJlZml4ZXMubGVuZ3RoO1xuXG4gICAgICAgIHdoaWxlIChpLS0pIHtcbiAgICAgICAgICAgIHByb3AgPSBjc3NQcmVmaXhlc1tpXSArIGNhcFByb3A7XG4gICAgICAgICAgICBpZiAocHJvcCBpbiBlbXB0eVN0eWxlcykge1xuICAgICAgICAgICAgICAgIHJldHVybiBwcm9wO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfVxuXG4gICAgQ1NTX1RSQU5TRk9STSA9IHZlbmRvclByZWZpeCgndHJhbnNmb3JtJyk7XG4gICAgQ1NTX1RSQU5TX09SRyA9IHZlbmRvclByZWZpeCgndHJhbnNmb3JtT3JpZ2luJyk7XG4gICAgQ1NTX1VTRVJTRUxFQ1QgPSB2ZW5kb3JQcmVmaXgoJ3VzZXJTZWxlY3QnKTtcblxuICAgIGZ1bmN0aW9uIGdldEV4aWZPZmZzZXQob3JudCwgcm90YXRlKSB7XG4gICAgICAgIHZhciBhcnIgPSBFWElGX05PUk0uaW5kZXhPZihvcm50KSA+IC0xID8gRVhJRl9OT1JNIDogRVhJRl9GTElQLFxuICAgICAgICAgICAgaW5kZXggPSBhcnIuaW5kZXhPZihvcm50KSxcbiAgICAgICAgICAgIG9mZnNldCA9IChyb3RhdGUgLyA5MCkgJSBhcnIubGVuZ3RoOy8vIDE4MCA9IDIlNCA9IDIgc2hpZnQgZXhpZiBieSAyIGluZGV4ZXNcblxuICAgICAgICByZXR1cm4gYXJyWyhhcnIubGVuZ3RoICsgaW5kZXggKyAob2Zmc2V0ICUgYXJyLmxlbmd0aCkpICUgYXJyLmxlbmd0aF07XG4gICAgfVxuXG4gICAgLy8gQ3JlZGl0cyB0byA6IEFuZHJldyBEdXBvbnQgLSBodHRwOi8vYW5kcmV3ZHVwb250Lm5ldC8yMDA5LzA4LzI4L2RlZXAtZXh0ZW5kaW5nLW9iamVjdHMtaW4tamF2YXNjcmlwdC9cbiAgICBmdW5jdGlvbiBkZWVwRXh0ZW5kKGRlc3RpbmF0aW9uLCBzb3VyY2UpIHtcbiAgICAgICAgZGVzdGluYXRpb24gPSBkZXN0aW5hdGlvbiB8fCB7fTtcbiAgICAgICAgZm9yICh2YXIgcHJvcGVydHkgaW4gc291cmNlKSB7XG4gICAgICAgICAgICBpZiAoc291cmNlW3Byb3BlcnR5XSAmJiBzb3VyY2VbcHJvcGVydHldLmNvbnN0cnVjdG9yICYmIHNvdXJjZVtwcm9wZXJ0eV0uY29uc3RydWN0b3IgPT09IE9iamVjdCkge1xuICAgICAgICAgICAgICAgIGRlc3RpbmF0aW9uW3Byb3BlcnR5XSA9IGRlc3RpbmF0aW9uW3Byb3BlcnR5XSB8fCB7fTtcbiAgICAgICAgICAgICAgICBkZWVwRXh0ZW5kKGRlc3RpbmF0aW9uW3Byb3BlcnR5XSwgc291cmNlW3Byb3BlcnR5XSk7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIGRlc3RpbmF0aW9uW3Byb3BlcnR5XSA9IHNvdXJjZVtwcm9wZXJ0eV07XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGRlc3RpbmF0aW9uO1xuICAgIH1cblxuICAgIGZ1bmN0aW9uIGNsb25lKG9iamVjdCkge1xuICAgICAgICByZXR1cm4gZGVlcEV4dGVuZCh7fSwgb2JqZWN0KTtcbiAgICB9XG5cbiAgICBmdW5jdGlvbiBkZWJvdW5jZShmdW5jLCB3YWl0LCBpbW1lZGlhdGUpIHtcbiAgICAgICAgdmFyIHRpbWVvdXQ7XG4gICAgICAgIHJldHVybiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgY29udGV4dCA9IHRoaXMsIGFyZ3MgPSBhcmd1bWVudHM7XG4gICAgICAgICAgICB2YXIgbGF0ZXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgdGltZW91dCA9IG51bGw7XG4gICAgICAgICAgICAgICAgaWYgKCFpbW1lZGlhdGUpIGZ1bmMuYXBwbHkoY29udGV4dCwgYXJncyk7XG4gICAgICAgICAgICB9O1xuICAgICAgICAgICAgdmFyIGNhbGxOb3cgPSBpbW1lZGlhdGUgJiYgIXRpbWVvdXQ7XG4gICAgICAgICAgICBjbGVhclRpbWVvdXQodGltZW91dCk7XG4gICAgICAgICAgICB0aW1lb3V0ID0gc2V0VGltZW91dChsYXRlciwgd2FpdCk7XG4gICAgICAgICAgICBpZiAoY2FsbE5vdykgZnVuYy5hcHBseShjb250ZXh0LCBhcmdzKTtcbiAgICAgICAgfTtcbiAgICB9XG5cbiAgICBmdW5jdGlvbiBkaXNwYXRjaENoYW5nZShlbGVtZW50KSB7XG4gICAgICAgIGlmIChcImNyZWF0ZUV2ZW50XCIgaW4gZG9jdW1lbnQpIHtcbiAgICAgICAgICAgIHZhciBldnQgPSBkb2N1bWVudC5jcmVhdGVFdmVudChcIkhUTUxFdmVudHNcIik7XG4gICAgICAgICAgICBldnQuaW5pdEV2ZW50KFwiY2hhbmdlXCIsIGZhbHNlLCB0cnVlKTtcbiAgICAgICAgICAgIGVsZW1lbnQuZGlzcGF0Y2hFdmVudChldnQpO1xuICAgICAgICB9XG4gICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgZWxlbWVudC5maXJlRXZlbnQoXCJvbmNoYW5nZVwiKTtcbiAgICAgICAgfVxuICAgIH1cblxuICAgIC8vaHR0cDovL2pzcGVyZi5jb20vdmFuaWxsYS1jc3NcbiAgICBmdW5jdGlvbiBjc3MoZWwsIHN0eWxlcywgdmFsKSB7XG4gICAgICAgIGlmICh0eXBlb2YgKHN0eWxlcykgPT09ICdzdHJpbmcnKSB7XG4gICAgICAgICAgICB2YXIgdG1wID0gc3R5bGVzO1xuICAgICAgICAgICAgc3R5bGVzID0ge307XG4gICAgICAgICAgICBzdHlsZXNbdG1wXSA9IHZhbDtcbiAgICAgICAgfVxuXG4gICAgICAgIGZvciAodmFyIHByb3AgaW4gc3R5bGVzKSB7XG4gICAgICAgICAgICBlbC5zdHlsZVtwcm9wXSA9IHN0eWxlc1twcm9wXTtcbiAgICAgICAgfVxuICAgIH1cblxuICAgIGZ1bmN0aW9uIGFkZENsYXNzKGVsLCBjKSB7XG4gICAgICAgIGlmIChlbC5jbGFzc0xpc3QpIHtcbiAgICAgICAgICAgIGVsLmNsYXNzTGlzdC5hZGQoYyk7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICBlbC5jbGFzc05hbWUgKz0gJyAnICsgYztcbiAgICAgICAgfVxuICAgIH1cblxuICAgIGZ1bmN0aW9uIHJlbW92ZUNsYXNzKGVsLCBjKSB7XG4gICAgICAgIGlmIChlbC5jbGFzc0xpc3QpIHtcbiAgICAgICAgICAgIGVsLmNsYXNzTGlzdC5yZW1vdmUoYyk7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICBlbC5jbGFzc05hbWUgPSBlbC5jbGFzc05hbWUucmVwbGFjZShjLCAnJyk7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICBmdW5jdGlvbiBzZXRBdHRyaWJ1dGVzKGVsLCBhdHRycykge1xuICAgICAgICBmb3IgKHZhciBrZXkgaW4gYXR0cnMpIHtcbiAgICAgICAgICAgIGVsLnNldEF0dHJpYnV0ZShrZXksIGF0dHJzW2tleV0pO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gbnVtKHYpIHtcbiAgICAgICAgcmV0dXJuIHBhcnNlSW50KHYsIDEwKTtcbiAgICB9XG5cbiAgICAvKiBVdGlsaXRpZXMgKi9cbiAgICBmdW5jdGlvbiBsb2FkSW1hZ2Uoc3JjLCBkb0V4aWYpIHtcbiAgICAgICAgdmFyIGltZyA9IG5ldyBJbWFnZSgpO1xuICAgICAgICBpbWcuc3R5bGUub3BhY2l0eSA9IDA7XG4gICAgICAgIHJldHVybiBuZXcgUHJvbWlzZShmdW5jdGlvbiAocmVzb2x2ZSkge1xuICAgICAgICAgICAgZnVuY3Rpb24gX3Jlc29sdmUoKSB7XG4gICAgICAgICAgICAgICAgaW1nLnN0eWxlLm9wYWNpdHkgPSAxO1xuICAgICAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICByZXNvbHZlKGltZyk7XG4gICAgICAgICAgICAgICAgfSwgMSk7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGltZy5yZW1vdmVBdHRyaWJ1dGUoJ2Nyb3NzT3JpZ2luJyk7XG4gICAgICAgICAgICBpZiAoc3JjLm1hdGNoKC9eaHR0cHM/OlxcL1xcL3xeXFwvXFwvLykpIHtcbiAgICAgICAgICAgICAgICBpbWcuc2V0QXR0cmlidXRlKCdjcm9zc09yaWdpbicsICdhbm9ueW1vdXMnKTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgaW1nLm9ubG9hZCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICBpZiAoZG9FeGlmKSB7XG4gICAgICAgICAgICAgICAgICAgIEVYSUYuZ2V0RGF0YShpbWcsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIF9yZXNvbHZlKCk7XG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgX3Jlc29sdmUoKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9O1xuICAgICAgICAgICAgaW1nLnNyYyA9IHNyYztcbiAgICAgICAgfSk7XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gbmF0dXJhbEltYWdlRGltZW5zaW9ucyhpbWcsIG9ybnQpIHtcbiAgICAgICAgdmFyIHcgPSBpbWcubmF0dXJhbFdpZHRoO1xuICAgICAgICB2YXIgaCA9IGltZy5uYXR1cmFsSGVpZ2h0O1xuICAgICAgICB2YXIgb3JpZW50ID0gb3JudCB8fCBnZXRFeGlmT3JpZW50YXRpb24oaW1nKTtcbiAgICAgICAgaWYgKG9yaWVudCAmJiBvcmllbnQgPj0gNSkge1xuICAgICAgICAgICAgdmFyIHg9IHc7XG4gICAgICAgICAgICB3ID0gaDtcbiAgICAgICAgICAgIGggPSB4O1xuICAgICAgICB9XG4gICAgICAgIHJldHVybiB7IHdpZHRoOiB3LCBoZWlnaHQ6IGggfTtcbiAgICB9XG5cbiAgICAvKiBDU1MgVHJhbnNmb3JtIFByb3RvdHlwZSAqL1xuICAgIHZhciBUUkFOU0xBVEVfT1BUUyA9IHtcbiAgICAgICAgJ3RyYW5zbGF0ZTNkJzoge1xuICAgICAgICAgICAgc3VmZml4OiAnLCAwcHgnXG4gICAgICAgIH0sXG4gICAgICAgICd0cmFuc2xhdGUnOiB7XG4gICAgICAgICAgICBzdWZmaXg6ICcnXG4gICAgICAgIH1cbiAgICB9O1xuICAgIHZhciBUcmFuc2Zvcm0gPSBmdW5jdGlvbiAoeCwgeSwgc2NhbGUpIHtcbiAgICAgICAgdGhpcy54ID0gcGFyc2VGbG9hdCh4KTtcbiAgICAgICAgdGhpcy55ID0gcGFyc2VGbG9hdCh5KTtcbiAgICAgICAgdGhpcy5zY2FsZSA9IHBhcnNlRmxvYXQoc2NhbGUpO1xuICAgIH07XG5cbiAgICBUcmFuc2Zvcm0ucGFyc2UgPSBmdW5jdGlvbiAodikge1xuICAgICAgICBpZiAodi5zdHlsZSkge1xuICAgICAgICAgICAgcmV0dXJuIFRyYW5zZm9ybS5wYXJzZSh2LnN0eWxlW0NTU19UUkFOU0ZPUk1dKTtcbiAgICAgICAgfVxuICAgICAgICBlbHNlIGlmICh2LmluZGV4T2YoJ21hdHJpeCcpID4gLTEgfHwgdi5pbmRleE9mKCdub25lJykgPiAtMSkge1xuICAgICAgICAgICAgcmV0dXJuIFRyYW5zZm9ybS5mcm9tTWF0cml4KHYpO1xuICAgICAgICB9XG4gICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgcmV0dXJuIFRyYW5zZm9ybS5mcm9tU3RyaW5nKHYpO1xuICAgICAgICB9XG4gICAgfTtcblxuICAgIFRyYW5zZm9ybS5mcm9tTWF0cml4ID0gZnVuY3Rpb24gKHYpIHtcbiAgICAgICAgdmFyIHZhbHMgPSB2LnN1YnN0cmluZyg3KS5zcGxpdCgnLCcpO1xuICAgICAgICBpZiAoIXZhbHMubGVuZ3RoIHx8IHYgPT09ICdub25lJykge1xuICAgICAgICAgICAgdmFscyA9IFsxLCAwLCAwLCAxLCAwLCAwXTtcbiAgICAgICAgfVxuXG4gICAgICAgIHJldHVybiBuZXcgVHJhbnNmb3JtKG51bSh2YWxzWzRdKSwgbnVtKHZhbHNbNV0pLCBwYXJzZUZsb2F0KHZhbHNbMF0pKTtcbiAgICB9O1xuXG4gICAgVHJhbnNmb3JtLmZyb21TdHJpbmcgPSBmdW5jdGlvbiAodikge1xuICAgICAgICB2YXIgdmFsdWVzID0gdi5zcGxpdCgnKSAnKSxcbiAgICAgICAgICAgIHRyYW5zbGF0ZSA9IHZhbHVlc1swXS5zdWJzdHJpbmcoQ3JvcHBpZS5nbG9iYWxzLnRyYW5zbGF0ZS5sZW5ndGggKyAxKS5zcGxpdCgnLCcpLFxuICAgICAgICAgICAgc2NhbGUgPSB2YWx1ZXMubGVuZ3RoID4gMSA/IHZhbHVlc1sxXS5zdWJzdHJpbmcoNikgOiAxLFxuICAgICAgICAgICAgeCA9IHRyYW5zbGF0ZS5sZW5ndGggPiAxID8gdHJhbnNsYXRlWzBdIDogMCxcbiAgICAgICAgICAgIHkgPSB0cmFuc2xhdGUubGVuZ3RoID4gMSA/IHRyYW5zbGF0ZVsxXSA6IDA7XG5cbiAgICAgICAgcmV0dXJuIG5ldyBUcmFuc2Zvcm0oeCwgeSwgc2NhbGUpO1xuICAgIH07XG5cbiAgICBUcmFuc2Zvcm0ucHJvdG90eXBlLnRvU3RyaW5nID0gZnVuY3Rpb24gKCkge1xuICAgICAgICB2YXIgc3VmZml4ID0gVFJBTlNMQVRFX09QVFNbQ3JvcHBpZS5nbG9iYWxzLnRyYW5zbGF0ZV0uc3VmZml4IHx8ICcnO1xuICAgICAgICByZXR1cm4gQ3JvcHBpZS5nbG9iYWxzLnRyYW5zbGF0ZSArICcoJyArIHRoaXMueCArICdweCwgJyArIHRoaXMueSArICdweCcgKyBzdWZmaXggKyAnKSBzY2FsZSgnICsgdGhpcy5zY2FsZSArICcpJztcbiAgICB9O1xuXG4gICAgdmFyIFRyYW5zZm9ybU9yaWdpbiA9IGZ1bmN0aW9uIChlbCkge1xuICAgICAgICBpZiAoIWVsIHx8ICFlbC5zdHlsZVtDU1NfVFJBTlNfT1JHXSkge1xuICAgICAgICAgICAgdGhpcy54ID0gMDtcbiAgICAgICAgICAgIHRoaXMueSA9IDA7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cbiAgICAgICAgdmFyIGNzcyA9IGVsLnN0eWxlW0NTU19UUkFOU19PUkddLnNwbGl0KCcgJyk7XG4gICAgICAgIHRoaXMueCA9IHBhcnNlRmxvYXQoY3NzWzBdKTtcbiAgICAgICAgdGhpcy55ID0gcGFyc2VGbG9hdChjc3NbMV0pO1xuICAgIH07XG5cbiAgICBUcmFuc2Zvcm1PcmlnaW4ucHJvdG90eXBlLnRvU3RyaW5nID0gZnVuY3Rpb24gKCkge1xuICAgICAgICByZXR1cm4gdGhpcy54ICsgJ3B4ICcgKyB0aGlzLnkgKyAncHgnO1xuICAgIH07XG5cbiAgICBmdW5jdGlvbiBnZXRFeGlmT3JpZW50YXRpb24gKGltZykge1xuICAgICAgICByZXR1cm4gaW1nLmV4aWZkYXRhICYmIGltZy5leGlmZGF0YS5PcmllbnRhdGlvbiA/IG51bShpbWcuZXhpZmRhdGEuT3JpZW50YXRpb24pIDogMTtcbiAgICB9XG5cbiAgICBmdW5jdGlvbiBkcmF3Q2FudmFzKGNhbnZhcywgaW1nLCBvcmllbnRhdGlvbikge1xuICAgICAgICB2YXIgd2lkdGggPSBpbWcud2lkdGgsXG4gICAgICAgICAgICBoZWlnaHQgPSBpbWcuaGVpZ2h0LFxuICAgICAgICAgICAgY3R4ID0gY2FudmFzLmdldENvbnRleHQoJzJkJyk7XG5cbiAgICAgICAgY2FudmFzLndpZHRoID0gaW1nLndpZHRoO1xuICAgICAgICBjYW52YXMuaGVpZ2h0ID0gaW1nLmhlaWdodDtcblxuICAgICAgICBjdHguc2F2ZSgpO1xuICAgICAgICBzd2l0Y2ggKG9yaWVudGF0aW9uKSB7XG4gICAgICAgICAgY2FzZSAyOlxuICAgICAgICAgICAgIGN0eC50cmFuc2xhdGUod2lkdGgsIDApO1xuICAgICAgICAgICAgIGN0eC5zY2FsZSgtMSwgMSk7XG4gICAgICAgICAgICAgYnJlYWs7XG5cbiAgICAgICAgICBjYXNlIDM6XG4gICAgICAgICAgICAgIGN0eC50cmFuc2xhdGUod2lkdGgsIGhlaWdodCk7XG4gICAgICAgICAgICAgIGN0eC5yb3RhdGUoMTgwKk1hdGguUEkvMTgwKTtcbiAgICAgICAgICAgICAgYnJlYWs7XG5cbiAgICAgICAgICBjYXNlIDQ6XG4gICAgICAgICAgICAgIGN0eC50cmFuc2xhdGUoMCwgaGVpZ2h0KTtcbiAgICAgICAgICAgICAgY3R4LnNjYWxlKDEsIC0xKTtcbiAgICAgICAgICAgICAgYnJlYWs7XG5cbiAgICAgICAgICBjYXNlIDU6XG4gICAgICAgICAgICAgIGNhbnZhcy53aWR0aCA9IGhlaWdodDtcbiAgICAgICAgICAgICAgY2FudmFzLmhlaWdodCA9IHdpZHRoO1xuICAgICAgICAgICAgICBjdHgucm90YXRlKDkwKk1hdGguUEkvMTgwKTtcbiAgICAgICAgICAgICAgY3R4LnNjYWxlKDEsIC0xKTtcbiAgICAgICAgICAgICAgYnJlYWs7XG5cbiAgICAgICAgICBjYXNlIDY6XG4gICAgICAgICAgICAgIGNhbnZhcy53aWR0aCA9IGhlaWdodDtcbiAgICAgICAgICAgICAgY2FudmFzLmhlaWdodCA9IHdpZHRoO1xuICAgICAgICAgICAgICBjdHgucm90YXRlKDkwKk1hdGguUEkvMTgwKTtcbiAgICAgICAgICAgICAgY3R4LnRyYW5zbGF0ZSgwLCAtaGVpZ2h0KTtcbiAgICAgICAgICAgICAgYnJlYWs7XG5cbiAgICAgICAgICBjYXNlIDc6XG4gICAgICAgICAgICAgIGNhbnZhcy53aWR0aCA9IGhlaWdodDtcbiAgICAgICAgICAgICAgY2FudmFzLmhlaWdodCA9IHdpZHRoO1xuICAgICAgICAgICAgICBjdHgucm90YXRlKC05MCpNYXRoLlBJLzE4MCk7XG4gICAgICAgICAgICAgIGN0eC50cmFuc2xhdGUoLXdpZHRoLCBoZWlnaHQpO1xuICAgICAgICAgICAgICBjdHguc2NhbGUoMSwgLTEpO1xuICAgICAgICAgICAgICBicmVhaztcblxuICAgICAgICAgIGNhc2UgODpcbiAgICAgICAgICAgICAgY2FudmFzLndpZHRoID0gaGVpZ2h0O1xuICAgICAgICAgICAgICBjYW52YXMuaGVpZ2h0ID0gd2lkdGg7XG4gICAgICAgICAgICAgIGN0eC50cmFuc2xhdGUoMCwgd2lkdGgpO1xuICAgICAgICAgICAgICBjdHgucm90YXRlKC05MCpNYXRoLlBJLzE4MCk7XG4gICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICB9XG4gICAgICAgIGN0eC5kcmF3SW1hZ2UoaW1nLCAwLDAsIHdpZHRoLCBoZWlnaHQpO1xuICAgICAgICBjdHgucmVzdG9yZSgpO1xuICAgIH1cblxuICAgIC8qIFByaXZhdGUgTWV0aG9kcyAqL1xuICAgIGZ1bmN0aW9uIF9jcmVhdGUoKSB7XG4gICAgICAgIHZhciBzZWxmID0gdGhpcyxcbiAgICAgICAgICAgIGNvbnRDbGFzcyA9ICdjcm9wcGllLWNvbnRhaW5lcicsXG4gICAgICAgICAgICBjdXN0b21WaWV3cG9ydENsYXNzID0gc2VsZi5vcHRpb25zLnZpZXdwb3J0LnR5cGUgPyAnY3ItdnAtJyArIHNlbGYub3B0aW9ucy52aWV3cG9ydC50eXBlIDogbnVsbCxcbiAgICAgICAgICAgIGJvdW5kYXJ5LCBpbWcsIHZpZXdwb3J0LCBvdmVybGF5LCBidywgYmg7XG5cbiAgICAgICAgc2VsZi5vcHRpb25zLnVzZUNhbnZhcyA9IHNlbGYub3B0aW9ucy5lbmFibGVPcmllbnRhdGlvbiB8fCBfaGFzRXhpZi5jYWxsKHNlbGYpO1xuICAgICAgICAvLyBQcm9wZXJ0aWVzIG9uIGNsYXNzXG4gICAgICAgIHNlbGYuZGF0YSA9IHt9O1xuICAgICAgICBzZWxmLmVsZW1lbnRzID0ge307XG5cbiAgICAgICAgYm91bmRhcnkgPSBzZWxmLmVsZW1lbnRzLmJvdW5kYXJ5ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgIHZpZXdwb3J0ID0gc2VsZi5lbGVtZW50cy52aWV3cG9ydCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICBpbWcgPSBzZWxmLmVsZW1lbnRzLmltZyA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2ltZycpO1xuICAgICAgICBvdmVybGF5ID0gc2VsZi5lbGVtZW50cy5vdmVybGF5ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG5cbiAgICAgICAgaWYgKHNlbGYub3B0aW9ucy51c2VDYW52YXMpIHtcbiAgICAgICAgICAgIHNlbGYuZWxlbWVudHMuY2FudmFzID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnY2FudmFzJyk7XG4gICAgICAgICAgICBzZWxmLmVsZW1lbnRzLnByZXZpZXcgPSBzZWxmLmVsZW1lbnRzLmNhbnZhcztcbiAgICAgICAgfVxuICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgIHNlbGYuZWxlbWVudHMucHJldmlldyA9IHNlbGYuZWxlbWVudHMuaW1nO1xuICAgICAgICB9XG5cbiAgICAgICAgYWRkQ2xhc3MoYm91bmRhcnksICdjci1ib3VuZGFyeScpO1xuICAgICAgICBib3VuZGFyeS5zZXRBdHRyaWJ1dGUoJ2FyaWEtZHJvcGVmZmVjdCcsICdub25lJyk7XG4gICAgICAgIGJ3ID0gc2VsZi5vcHRpb25zLmJvdW5kYXJ5LndpZHRoO1xuICAgICAgICBiaCA9IHNlbGYub3B0aW9ucy5ib3VuZGFyeS5oZWlnaHQ7XG4gICAgICAgIGNzcyhib3VuZGFyeSwge1xuICAgICAgICAgICAgd2lkdGg6IChidyArIChpc05hTihidykgPyAnJyA6ICdweCcpKSxcbiAgICAgICAgICAgIGhlaWdodDogKGJoICsgKGlzTmFOKGJoKSA/ICcnIDogJ3B4JykpXG4gICAgICAgIH0pO1xuXG4gICAgICAgIGFkZENsYXNzKHZpZXdwb3J0LCAnY3Itdmlld3BvcnQnKTtcbiAgICAgICAgaWYgKGN1c3RvbVZpZXdwb3J0Q2xhc3MpIHtcbiAgICAgICAgICAgIGFkZENsYXNzKHZpZXdwb3J0LCBjdXN0b21WaWV3cG9ydENsYXNzKTtcbiAgICAgICAgfVxuICAgICAgICBjc3Modmlld3BvcnQsIHtcbiAgICAgICAgICAgIHdpZHRoOiBzZWxmLm9wdGlvbnMudmlld3BvcnQud2lkdGggKyAncHgnLFxuICAgICAgICAgICAgaGVpZ2h0OiBzZWxmLm9wdGlvbnMudmlld3BvcnQuaGVpZ2h0ICsgJ3B4J1xuICAgICAgICB9KTtcbiAgICAgICAgdmlld3BvcnQuc2V0QXR0cmlidXRlKCd0YWJpbmRleCcsIDApO1xuXG4gICAgICAgIGFkZENsYXNzKHNlbGYuZWxlbWVudHMucHJldmlldywgJ2NyLWltYWdlJyk7XG4gICAgICAgIHNldEF0dHJpYnV0ZXMoc2VsZi5lbGVtZW50cy5wcmV2aWV3LCB7ICdhbHQnOiAncHJldmlldycsICdhcmlhLWdyYWJiZWQnOiAnZmFsc2UnIH0pO1xuICAgICAgICBhZGRDbGFzcyhvdmVybGF5LCAnY3Itb3ZlcmxheScpO1xuXG4gICAgICAgIHNlbGYuZWxlbWVudC5hcHBlbmRDaGlsZChib3VuZGFyeSk7XG4gICAgICAgIGJvdW5kYXJ5LmFwcGVuZENoaWxkKHNlbGYuZWxlbWVudHMucHJldmlldyk7XG4gICAgICAgIGJvdW5kYXJ5LmFwcGVuZENoaWxkKHZpZXdwb3J0KTtcbiAgICAgICAgYm91bmRhcnkuYXBwZW5kQ2hpbGQob3ZlcmxheSk7XG5cbiAgICAgICAgYWRkQ2xhc3Moc2VsZi5lbGVtZW50LCBjb250Q2xhc3MpO1xuICAgICAgICBpZiAoc2VsZi5vcHRpb25zLmN1c3RvbUNsYXNzKSB7XG4gICAgICAgICAgICBhZGRDbGFzcyhzZWxmLmVsZW1lbnQsIHNlbGYub3B0aW9ucy5jdXN0b21DbGFzcyk7XG4gICAgICAgIH1cblxuICAgICAgICBfaW5pdERyYWdnYWJsZS5jYWxsKHRoaXMpO1xuXG4gICAgICAgIGlmIChzZWxmLm9wdGlvbnMuZW5hYmxlWm9vbSkge1xuICAgICAgICAgICAgX2luaXRpYWxpemVab29tLmNhbGwoc2VsZik7XG4gICAgICAgIH1cblxuICAgICAgICAvLyBpZiAoc2VsZi5vcHRpb25zLmVuYWJsZU9yaWVudGF0aW9uKSB7XG4gICAgICAgIC8vICAgICBfaW5pdFJvdGF0aW9uQ29udHJvbHMuY2FsbChzZWxmKTtcbiAgICAgICAgLy8gfVxuXG4gICAgICAgIGlmIChzZWxmLm9wdGlvbnMuZW5hYmxlUmVzaXplKSB7XG4gICAgICAgICAgICBfaW5pdGlhbGl6ZVJlc2l6ZS5jYWxsKHNlbGYpO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgLy8gZnVuY3Rpb24gX2luaXRSb3RhdGlvbkNvbnRyb2xzICgpIHtcbiAgICAvLyAgICAgdmFyIHNlbGYgPSB0aGlzLFxuICAgIC8vICAgICAgICAgd3JhcCwgYnRuTGVmdCwgYnRuUmlnaHQsIGlMZWZ0LCBpUmlnaHQ7XG5cbiAgICAvLyAgICAgd3JhcCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgIC8vICAgICBzZWxmLmVsZW1lbnRzLm9yaWVudGF0aW9uQnRuTGVmdCA9IGJ0bkxlZnQgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdidXR0b24nKTtcbiAgICAvLyAgICAgc2VsZi5lbGVtZW50cy5vcmllbnRhdGlvbkJ0blJpZ2h0ID0gYnRuUmlnaHQgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdidXR0b24nKTtcblxuICAgIC8vICAgICB3cmFwLmFwcGVuZENoaWxkKGJ0bkxlZnQpO1xuICAgIC8vICAgICB3cmFwLmFwcGVuZENoaWxkKGJ0blJpZ2h0KTtcblxuICAgIC8vICAgICBpTGVmdCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2knKTtcbiAgICAvLyAgICAgaVJpZ2h0ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnaScpO1xuICAgIC8vICAgICBidG5MZWZ0LmFwcGVuZENoaWxkKGlMZWZ0KTtcbiAgICAvLyAgICAgYnRuUmlnaHQuYXBwZW5kQ2hpbGQoaVJpZ2h0KTtcblxuICAgIC8vICAgICBhZGRDbGFzcyh3cmFwLCAnY3Itcm90YXRlLWNvbnRyb2xzJyk7XG4gICAgLy8gICAgIGFkZENsYXNzKGJ0bkxlZnQsICdjci1yb3RhdGUtbCcpO1xuICAgIC8vICAgICBhZGRDbGFzcyhidG5SaWdodCwgJ2NyLXJvdGF0ZS1yJyk7XG5cbiAgICAvLyAgICAgc2VsZi5lbGVtZW50cy5ib3VuZGFyeS5hcHBlbmRDaGlsZCh3cmFwKTtcblxuICAgIC8vICAgICBidG5MZWZ0LmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgZnVuY3Rpb24gKCkge1xuICAgIC8vICAgICAgICAgc2VsZi5yb3RhdGUoLTkwKTtcbiAgICAvLyAgICAgfSk7XG4gICAgLy8gICAgIGJ0blJpZ2h0LmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgZnVuY3Rpb24gKCkge1xuICAgIC8vICAgICAgICAgc2VsZi5yb3RhdGUoOTApO1xuICAgIC8vICAgICB9KTtcbiAgICAvLyB9XG5cbiAgICBmdW5jdGlvbiBfaGFzRXhpZigpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMub3B0aW9ucy5lbmFibGVFeGlmICYmIHdpbmRvdy5FWElGO1xuICAgIH1cblxuICAgIGZ1bmN0aW9uIF9pbml0aWFsaXplUmVzaXplICgpIHtcbiAgICAgICAgdmFyIHNlbGYgPSB0aGlzO1xuICAgICAgICB2YXIgd3JhcCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICB2YXIgaXNEcmFnZ2luZyA9IGZhbHNlO1xuICAgICAgICB2YXIgZGlyZWN0aW9uO1xuICAgICAgICB2YXIgb3JpZ2luYWxYO1xuICAgICAgICB2YXIgb3JpZ2luYWxZO1xuICAgICAgICB2YXIgbWluU2l6ZSA9IDUwO1xuICAgICAgICB2YXIgbWF4V2lkdGg7XG4gICAgICAgIHZhciBtYXhIZWlnaHQ7XG4gICAgICAgIHZhciB2cjtcbiAgICAgICAgdmFyIGhyO1xuXG4gICAgICAgIGFkZENsYXNzKHdyYXAsICdjci1yZXNpemVyJyk7XG4gICAgICAgIGNzcyh3cmFwLCB7XG4gICAgICAgICAgICB3aWR0aDogdGhpcy5vcHRpb25zLnZpZXdwb3J0LndpZHRoICsgJ3B4JyxcbiAgICAgICAgICAgIGhlaWdodDogdGhpcy5vcHRpb25zLnZpZXdwb3J0LmhlaWdodCArICdweCdcbiAgICAgICAgfSk7XG5cbiAgICAgICAgaWYgKHRoaXMub3B0aW9ucy5yZXNpemVDb250cm9scy5oZWlnaHQpIHtcbiAgICAgICAgICAgIHZyID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgICAgICBhZGRDbGFzcyh2ciwgJ2NyLXJlc2l6ZXItdmVydGljYWwnKTtcbiAgICAgICAgICAgIHdyYXAuYXBwZW5kQ2hpbGQodnIpO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKHRoaXMub3B0aW9ucy5yZXNpemVDb250cm9scy53aWR0aCkge1xuICAgICAgICAgICAgaHIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgIGFkZENsYXNzKGhyLCAnY3ItcmVzaXplci1ob3Jpc29udGFsJyk7XG4gICAgICAgICAgICB3cmFwLmFwcGVuZENoaWxkKGhyKTtcbiAgICAgICAgfVxuXG4gICAgICAgIGZ1bmN0aW9uIG1vdXNlRG93bihldikge1xuICAgICAgICAgICAgaWYgKGV2LmJ1dHRvbiAhPT0gdW5kZWZpbmVkICYmIGV2LmJ1dHRvbiAhPT0gMCkgcmV0dXJuO1xuXG4gICAgICAgICAgICBldi5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgaWYgKGlzRHJhZ2dpbmcpIHtcbiAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHZhciBvdmVybGF5UmVjdCA9IHNlbGYuZWxlbWVudHMub3ZlcmxheS5nZXRCb3VuZGluZ0NsaWVudFJlY3QoKTtcblxuICAgICAgICAgICAgaXNEcmFnZ2luZyA9IHRydWU7XG4gICAgICAgICAgICBvcmlnaW5hbFggPSBldi5wYWdlWDtcbiAgICAgICAgICAgIG9yaWdpbmFsWSA9IGV2LnBhZ2VZO1xuICAgICAgICAgICAgZGlyZWN0aW9uID0gZXYuY3VycmVudFRhcmdldC5jbGFzc05hbWUuaW5kZXhPZigndmVydGljYWwnKSAhPT0gLTEgPyAndicgOiAnaCc7XG4gICAgICAgICAgICBtYXhXaWR0aCA9IG92ZXJsYXlSZWN0LndpZHRoO1xuICAgICAgICAgICAgbWF4SGVpZ2h0ID0gb3ZlcmxheVJlY3QuaGVpZ2h0O1xuXG4gICAgICAgICAgICBpZiAoZXYudG91Y2hlcykge1xuICAgICAgICAgICAgICAgIHZhciB0b3VjaGVzID0gZXYudG91Y2hlc1swXTtcbiAgICAgICAgICAgICAgICBvcmlnaW5hbFggPSB0b3VjaGVzLnBhZ2VYO1xuICAgICAgICAgICAgICAgIG9yaWdpbmFsWSA9IHRvdWNoZXMucGFnZVk7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKCdtb3VzZW1vdmUnLCBtb3VzZU1vdmUpO1xuICAgICAgICAgICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ3RvdWNobW92ZScsIG1vdXNlTW92ZSk7XG4gICAgICAgICAgICB3aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcignbW91c2V1cCcsIG1vdXNlVXApO1xuICAgICAgICAgICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ3RvdWNoZW5kJywgbW91c2VVcCk7XG4gICAgICAgICAgICBkb2N1bWVudC5ib2R5LnN0eWxlW0NTU19VU0VSU0VMRUNUXSA9ICdub25lJztcbiAgICAgICAgfVxuXG4gICAgICAgIGZ1bmN0aW9uIG1vdXNlTW92ZShldikge1xuICAgICAgICAgICAgdmFyIHBhZ2VYID0gZXYucGFnZVg7XG4gICAgICAgICAgICB2YXIgcGFnZVkgPSBldi5wYWdlWTtcblxuICAgICAgICAgICAgZXYucHJldmVudERlZmF1bHQoKTtcblxuICAgICAgICAgICAgaWYgKGV2LnRvdWNoZXMpIHtcbiAgICAgICAgICAgICAgICB2YXIgdG91Y2hlcyA9IGV2LnRvdWNoZXNbMF07XG4gICAgICAgICAgICAgICAgcGFnZVggPSB0b3VjaGVzLnBhZ2VYO1xuICAgICAgICAgICAgICAgIHBhZ2VZID0gdG91Y2hlcy5wYWdlWTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgdmFyIGRlbHRhWCA9IHBhZ2VYIC0gb3JpZ2luYWxYO1xuICAgICAgICAgICAgdmFyIGRlbHRhWSA9IHBhZ2VZIC0gb3JpZ2luYWxZO1xuICAgICAgICAgICAgdmFyIG5ld0hlaWdodCA9IHNlbGYub3B0aW9ucy52aWV3cG9ydC5oZWlnaHQgKyBkZWx0YVk7XG4gICAgICAgICAgICB2YXIgbmV3V2lkdGggPSBzZWxmLm9wdGlvbnMudmlld3BvcnQud2lkdGggKyBkZWx0YVg7XG5cbiAgICAgICAgICAgIGlmIChkaXJlY3Rpb24gPT09ICd2JyAmJiBuZXdIZWlnaHQgPj0gbWluU2l6ZSAmJiBuZXdIZWlnaHQgPD0gbWF4SGVpZ2h0KSB7XG4gICAgICAgICAgICAgICAgY3NzKHdyYXAsIHtcbiAgICAgICAgICAgICAgICAgICAgaGVpZ2h0OiBuZXdIZWlnaHQgKyAncHgnXG4gICAgICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgICAgICBzZWxmLm9wdGlvbnMuYm91bmRhcnkuaGVpZ2h0ICs9IGRlbHRhWTtcbiAgICAgICAgICAgICAgICBjc3Moc2VsZi5lbGVtZW50cy5ib3VuZGFyeSwge1xuICAgICAgICAgICAgICAgICAgICBoZWlnaHQ6IHNlbGYub3B0aW9ucy5ib3VuZGFyeS5oZWlnaHQgKyAncHgnXG4gICAgICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgICAgICBzZWxmLm9wdGlvbnMudmlld3BvcnQuaGVpZ2h0ICs9IGRlbHRhWTtcbiAgICAgICAgICAgICAgICBjc3Moc2VsZi5lbGVtZW50cy52aWV3cG9ydCwge1xuICAgICAgICAgICAgICAgICAgICBoZWlnaHQ6IHNlbGYub3B0aW9ucy52aWV3cG9ydC5oZWlnaHQgKyAncHgnXG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIGlmIChkaXJlY3Rpb24gPT09ICdoJyAmJiBuZXdXaWR0aCA+PSBtaW5TaXplICYmIG5ld1dpZHRoIDw9IG1heFdpZHRoKSB7XG4gICAgICAgICAgICAgICAgY3NzKHdyYXAsIHtcbiAgICAgICAgICAgICAgICAgICAgd2lkdGg6IG5ld1dpZHRoICsgJ3B4J1xuICAgICAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICAgICAgc2VsZi5vcHRpb25zLmJvdW5kYXJ5LndpZHRoICs9IGRlbHRhWDtcbiAgICAgICAgICAgICAgICBjc3Moc2VsZi5lbGVtZW50cy5ib3VuZGFyeSwge1xuICAgICAgICAgICAgICAgICAgICB3aWR0aDogc2VsZi5vcHRpb25zLmJvdW5kYXJ5LndpZHRoICsgJ3B4J1xuICAgICAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICAgICAgc2VsZi5vcHRpb25zLnZpZXdwb3J0LndpZHRoICs9IGRlbHRhWDtcbiAgICAgICAgICAgICAgICBjc3Moc2VsZi5lbGVtZW50cy52aWV3cG9ydCwge1xuICAgICAgICAgICAgICAgICAgICB3aWR0aDogc2VsZi5vcHRpb25zLnZpZXdwb3J0LndpZHRoICsgJ3B4J1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBfdXBkYXRlT3ZlcmxheS5jYWxsKHNlbGYpO1xuICAgICAgICAgICAgX3VwZGF0ZVpvb21MaW1pdHMuY2FsbChzZWxmKTtcbiAgICAgICAgICAgIF91cGRhdGVDZW50ZXJQb2ludC5jYWxsKHNlbGYpO1xuICAgICAgICAgICAgX3RyaWdnZXJVcGRhdGUuY2FsbChzZWxmKTtcbiAgICAgICAgICAgIG9yaWdpbmFsWSA9IHBhZ2VZO1xuICAgICAgICAgICAgb3JpZ2luYWxYID0gcGFnZVg7XG4gICAgICAgIH1cblxuICAgICAgICBmdW5jdGlvbiBtb3VzZVVwKCkge1xuICAgICAgICAgICAgaXNEcmFnZ2luZyA9IGZhbHNlO1xuICAgICAgICAgICAgd2luZG93LnJlbW92ZUV2ZW50TGlzdGVuZXIoJ21vdXNlbW92ZScsIG1vdXNlTW92ZSk7XG4gICAgICAgICAgICB3aW5kb3cucmVtb3ZlRXZlbnRMaXN0ZW5lcigndG91Y2htb3ZlJywgbW91c2VNb3ZlKTtcbiAgICAgICAgICAgIHdpbmRvdy5yZW1vdmVFdmVudExpc3RlbmVyKCdtb3VzZXVwJywgbW91c2VVcCk7XG4gICAgICAgICAgICB3aW5kb3cucmVtb3ZlRXZlbnRMaXN0ZW5lcigndG91Y2hlbmQnLCBtb3VzZVVwKTtcbiAgICAgICAgICAgIGRvY3VtZW50LmJvZHkuc3R5bGVbQ1NTX1VTRVJTRUxFQ1RdID0gJyc7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAodnIpIHtcbiAgICAgICAgICAgIHZyLmFkZEV2ZW50TGlzdGVuZXIoJ21vdXNlZG93bicsIG1vdXNlRG93bik7XG4gICAgICAgICAgICB2ci5hZGRFdmVudExpc3RlbmVyKCd0b3VjaHN0YXJ0JywgbW91c2VEb3duKTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChocikge1xuICAgICAgICAgICAgaHIuYWRkRXZlbnRMaXN0ZW5lcignbW91c2Vkb3duJywgbW91c2VEb3duKTtcbiAgICAgICAgICAgIGhyLmFkZEV2ZW50TGlzdGVuZXIoJ3RvdWNoc3RhcnQnLCBtb3VzZURvd24pO1xuICAgICAgICB9XG5cbiAgICAgICAgdGhpcy5lbGVtZW50cy5ib3VuZGFyeS5hcHBlbmRDaGlsZCh3cmFwKTtcbiAgICB9XG5cbiAgICBmdW5jdGlvbiBfc2V0Wm9vbWVyVmFsKHYpIHtcbiAgICAgICAgaWYgKHRoaXMub3B0aW9ucy5lbmFibGVab29tKSB7XG4gICAgICAgICAgICB2YXIgeiA9IHRoaXMuZWxlbWVudHMuem9vbWVyLFxuICAgICAgICAgICAgICAgIHZhbCA9IGZpeCh2LCA0KTtcblxuICAgICAgICAgICAgei52YWx1ZSA9IE1hdGgubWF4KHoubWluLCBNYXRoLm1pbih6Lm1heCwgdmFsKSk7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICBmdW5jdGlvbiBfaW5pdGlhbGl6ZVpvb20oKSB7XG4gICAgICAgIHZhciBzZWxmID0gdGhpcyxcbiAgICAgICAgICAgIHdyYXAgPSBzZWxmLmVsZW1lbnRzLnpvb21lcldyYXAgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKSxcbiAgICAgICAgICAgIHpvb21lciA9IHNlbGYuZWxlbWVudHMuem9vbWVyID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnaW5wdXQnKTtcblxuICAgICAgICBhZGRDbGFzcyh3cmFwLCAnY3Itc2xpZGVyLXdyYXAnKTtcbiAgICAgICAgYWRkQ2xhc3Moem9vbWVyLCAnY3Itc2xpZGVyJyk7XG4gICAgICAgIHpvb21lci50eXBlID0gJ3JhbmdlJztcbiAgICAgICAgem9vbWVyLnN0ZXAgPSAnMC4wMDAxJztcbiAgICAgICAgem9vbWVyLnZhbHVlID0gMTtcbiAgICAgICAgem9vbWVyLnN0eWxlLmRpc3BsYXkgPSBzZWxmLm9wdGlvbnMuc2hvd1pvb21lciA/ICcnIDogJ25vbmUnO1xuICAgICAgICB6b29tZXIuc2V0QXR0cmlidXRlKCdhcmlhLWxhYmVsJywgJ3pvb20nKTtcblxuICAgICAgICBzZWxmLmVsZW1lbnQuYXBwZW5kQ2hpbGQod3JhcCk7XG4gICAgICAgIHdyYXAuYXBwZW5kQ2hpbGQoem9vbWVyKTtcblxuICAgICAgICBzZWxmLl9jdXJyZW50Wm9vbSA9IDE7XG5cbiAgICAgICAgZnVuY3Rpb24gY2hhbmdlKCkge1xuICAgICAgICAgICAgX29uWm9vbS5jYWxsKHNlbGYsIHtcbiAgICAgICAgICAgICAgICB2YWx1ZTogcGFyc2VGbG9hdCh6b29tZXIudmFsdWUpLFxuICAgICAgICAgICAgICAgIG9yaWdpbjogbmV3IFRyYW5zZm9ybU9yaWdpbihzZWxmLmVsZW1lbnRzLnByZXZpZXcpLFxuICAgICAgICAgICAgICAgIHZpZXdwb3J0UmVjdDogc2VsZi5lbGVtZW50cy52aWV3cG9ydC5nZXRCb3VuZGluZ0NsaWVudFJlY3QoKSxcbiAgICAgICAgICAgICAgICB0cmFuc2Zvcm06IFRyYW5zZm9ybS5wYXJzZShzZWxmLmVsZW1lbnRzLnByZXZpZXcpXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuXG4gICAgICAgIGZ1bmN0aW9uIHNjcm9sbChldikge1xuICAgICAgICAgICAgdmFyIGRlbHRhLCB0YXJnZXRab29tO1xuXG4gICAgICAgICAgICBpZihzZWxmLm9wdGlvbnMubW91c2VXaGVlbFpvb20gPT09ICdjdHJsJyAmJiBldi5jdHJsS2V5ICE9PSB0cnVlKXtcbiAgICAgICAgICAgICAgcmV0dXJuIDA7IFxuICAgICAgICAgICAgfSBlbHNlIGlmIChldi53aGVlbERlbHRhKSB7XG4gICAgICAgICAgICAgICAgZGVsdGEgPSBldi53aGVlbERlbHRhIC8gMTIwMDsgLy93aGVlbERlbHRhIG1pbjogLTEyMCBtYXg6IDEyMCAvLyBtYXggeCAxMCB4IDJcbiAgICAgICAgICAgIH0gZWxzZSBpZiAoZXYuZGVsdGFZKSB7XG4gICAgICAgICAgICAgICAgZGVsdGEgPSBldi5kZWx0YVkgLyAxMDYwOyAvL2RlbHRhWSBtaW46IC01MyBtYXg6IDUzIC8vIG1heCB4IDEwIHggMlxuICAgICAgICAgICAgfSBlbHNlIGlmIChldi5kZXRhaWwpIHtcbiAgICAgICAgICAgICAgICBkZWx0YSA9IGV2LmRldGFpbCAvIC02MDsgLy9kZWx0YSBtaW46IC0zIG1heDogMyAvLyBtYXggeCAxMCB4IDJcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgZGVsdGEgPSAwO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB0YXJnZXRab29tID0gc2VsZi5fY3VycmVudFpvb20gKyAoZGVsdGEgKiBzZWxmLl9jdXJyZW50Wm9vbSk7XG5cbiAgICAgICAgICAgIGV2LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICBfc2V0Wm9vbWVyVmFsLmNhbGwoc2VsZiwgdGFyZ2V0Wm9vbSk7XG4gICAgICAgICAgICBjaGFuZ2UuY2FsbChzZWxmKTtcbiAgICAgICAgfVxuXG4gICAgICAgIHNlbGYuZWxlbWVudHMuem9vbWVyLmFkZEV2ZW50TGlzdGVuZXIoJ2lucHV0JywgY2hhbmdlKTsvLyB0aGlzIGlzIGJlaW5nIGZpcmVkIHR3aWNlIG9uIGtleXByZXNzXG4gICAgICAgIHNlbGYuZWxlbWVudHMuem9vbWVyLmFkZEV2ZW50TGlzdGVuZXIoJ2NoYW5nZScsIGNoYW5nZSk7XG5cbiAgICAgICAgaWYgKHNlbGYub3B0aW9ucy5tb3VzZVdoZWVsWm9vbSkge1xuICAgICAgICAgICAgc2VsZi5lbGVtZW50cy5ib3VuZGFyeS5hZGRFdmVudExpc3RlbmVyKCdtb3VzZXdoZWVsJywgc2Nyb2xsKTtcbiAgICAgICAgICAgIHNlbGYuZWxlbWVudHMuYm91bmRhcnkuYWRkRXZlbnRMaXN0ZW5lcignRE9NTW91c2VTY3JvbGwnLCBzY3JvbGwpO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gX29uWm9vbSh1aSkge1xuICAgICAgICB2YXIgc2VsZiA9IHRoaXMsXG4gICAgICAgICAgICB0cmFuc2Zvcm0gPSB1aSA/IHVpLnRyYW5zZm9ybSA6IFRyYW5zZm9ybS5wYXJzZShzZWxmLmVsZW1lbnRzLnByZXZpZXcpLFxuICAgICAgICAgICAgdnBSZWN0ID0gdWkgPyB1aS52aWV3cG9ydFJlY3QgOiBzZWxmLmVsZW1lbnRzLnZpZXdwb3J0LmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpLFxuICAgICAgICAgICAgb3JpZ2luID0gdWkgPyB1aS5vcmlnaW4gOiBuZXcgVHJhbnNmb3JtT3JpZ2luKHNlbGYuZWxlbWVudHMucHJldmlldyk7XG5cbiAgICAgICAgZnVuY3Rpb24gYXBwbHlDc3MoKSB7XG4gICAgICAgICAgICB2YXIgdHJhbnNDc3MgPSB7fTtcbiAgICAgICAgICAgIHRyYW5zQ3NzW0NTU19UUkFOU0ZPUk1dID0gdHJhbnNmb3JtLnRvU3RyaW5nKCk7XG4gICAgICAgICAgICB0cmFuc0Nzc1tDU1NfVFJBTlNfT1JHXSA9IG9yaWdpbi50b1N0cmluZygpO1xuICAgICAgICAgICAgY3NzKHNlbGYuZWxlbWVudHMucHJldmlldywgdHJhbnNDc3MpO1xuICAgICAgICB9XG5cbiAgICAgICAgc2VsZi5fY3VycmVudFpvb20gPSB1aSA/IHVpLnZhbHVlIDogc2VsZi5fY3VycmVudFpvb207XG4gICAgICAgIHRyYW5zZm9ybS5zY2FsZSA9IHNlbGYuX2N1cnJlbnRab29tO1xuICAgICAgICBzZWxmLmVsZW1lbnRzLnpvb21lci5zZXRBdHRyaWJ1dGUoJ2FyaWEtdmFsdWVub3cnLCBzZWxmLl9jdXJyZW50Wm9vbSk7XG4gICAgICAgIGFwcGx5Q3NzKCk7XG5cbiAgICAgICAgaWYgKHNlbGYub3B0aW9ucy5lbmZvcmNlQm91bmRhcnkpIHtcbiAgICAgICAgICAgIHZhciBib3VuZGFyaWVzID0gX2dldFZpcnR1YWxCb3VuZGFyaWVzLmNhbGwoc2VsZiwgdnBSZWN0KSxcbiAgICAgICAgICAgICAgICB0cmFuc0JvdW5kYXJpZXMgPSBib3VuZGFyaWVzLnRyYW5zbGF0ZSxcbiAgICAgICAgICAgICAgICBvQm91bmRhcmllcyA9IGJvdW5kYXJpZXMub3JpZ2luO1xuXG4gICAgICAgICAgICBpZiAodHJhbnNmb3JtLnggPj0gdHJhbnNCb3VuZGFyaWVzLm1heFgpIHtcbiAgICAgICAgICAgICAgICBvcmlnaW4ueCA9IG9Cb3VuZGFyaWVzLm1pblg7XG4gICAgICAgICAgICAgICAgdHJhbnNmb3JtLnggPSB0cmFuc0JvdW5kYXJpZXMubWF4WDtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgaWYgKHRyYW5zZm9ybS54IDw9IHRyYW5zQm91bmRhcmllcy5taW5YKSB7XG4gICAgICAgICAgICAgICAgb3JpZ2luLnggPSBvQm91bmRhcmllcy5tYXhYO1xuICAgICAgICAgICAgICAgIHRyYW5zZm9ybS54ID0gdHJhbnNCb3VuZGFyaWVzLm1pblg7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGlmICh0cmFuc2Zvcm0ueSA+PSB0cmFuc0JvdW5kYXJpZXMubWF4WSkge1xuICAgICAgICAgICAgICAgIG9yaWdpbi55ID0gb0JvdW5kYXJpZXMubWluWTtcbiAgICAgICAgICAgICAgICB0cmFuc2Zvcm0ueSA9IHRyYW5zQm91bmRhcmllcy5tYXhZO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBpZiAodHJhbnNmb3JtLnkgPD0gdHJhbnNCb3VuZGFyaWVzLm1pblkpIHtcbiAgICAgICAgICAgICAgICBvcmlnaW4ueSA9IG9Cb3VuZGFyaWVzLm1heFk7XG4gICAgICAgICAgICAgICAgdHJhbnNmb3JtLnkgPSB0cmFuc0JvdW5kYXJpZXMubWluWTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICBhcHBseUNzcygpO1xuICAgICAgICBfZGVib3VuY2VkT3ZlcmxheS5jYWxsKHNlbGYpO1xuICAgICAgICBfdHJpZ2dlclVwZGF0ZS5jYWxsKHNlbGYpO1xuICAgIH1cblxuICAgIGZ1bmN0aW9uIF9nZXRWaXJ0dWFsQm91bmRhcmllcyh2aWV3cG9ydCkge1xuICAgICAgICB2YXIgc2VsZiA9IHRoaXMsXG4gICAgICAgICAgICBzY2FsZSA9IHNlbGYuX2N1cnJlbnRab29tLFxuICAgICAgICAgICAgdnBXaWR0aCA9IHZpZXdwb3J0LndpZHRoLFxuICAgICAgICAgICAgdnBIZWlnaHQgPSB2aWV3cG9ydC5oZWlnaHQsXG4gICAgICAgICAgICBjZW50ZXJGcm9tQm91bmRhcnlYID0gc2VsZi5lbGVtZW50cy5ib3VuZGFyeS5jbGllbnRXaWR0aCAvIDIsXG4gICAgICAgICAgICBjZW50ZXJGcm9tQm91bmRhcnlZID0gc2VsZi5lbGVtZW50cy5ib3VuZGFyeS5jbGllbnRIZWlnaHQgLyAyLFxuICAgICAgICAgICAgaW1nUmVjdCA9IHNlbGYuZWxlbWVudHMucHJldmlldy5nZXRCb3VuZGluZ0NsaWVudFJlY3QoKSxcbiAgICAgICAgICAgIGN1ckltZ1dpZHRoID0gaW1nUmVjdC53aWR0aCxcbiAgICAgICAgICAgIGN1ckltZ0hlaWdodCA9IGltZ1JlY3QuaGVpZ2h0LFxuICAgICAgICAgICAgaGFsZldpZHRoID0gdnBXaWR0aCAvIDIsXG4gICAgICAgICAgICBoYWxmSGVpZ2h0ID0gdnBIZWlnaHQgLyAyO1xuXG4gICAgICAgIHZhciBtYXhYID0gKChoYWxmV2lkdGggLyBzY2FsZSkgLSBjZW50ZXJGcm9tQm91bmRhcnlYKSAqIC0xO1xuICAgICAgICB2YXIgbWluWCA9IG1heFggLSAoKGN1ckltZ1dpZHRoICogKDEgLyBzY2FsZSkpIC0gKHZwV2lkdGggKiAoMSAvIHNjYWxlKSkpO1xuXG4gICAgICAgIHZhciBtYXhZID0gKChoYWxmSGVpZ2h0IC8gc2NhbGUpIC0gY2VudGVyRnJvbUJvdW5kYXJ5WSkgKiAtMTtcbiAgICAgICAgdmFyIG1pblkgPSBtYXhZIC0gKChjdXJJbWdIZWlnaHQgKiAoMSAvIHNjYWxlKSkgLSAodnBIZWlnaHQgKiAoMSAvIHNjYWxlKSkpO1xuXG4gICAgICAgIHZhciBvcmlnaW5NaW5YID0gKDEgLyBzY2FsZSkgKiBoYWxmV2lkdGg7XG4gICAgICAgIHZhciBvcmlnaW5NYXhYID0gKGN1ckltZ1dpZHRoICogKDEgLyBzY2FsZSkpIC0gb3JpZ2luTWluWDtcblxuICAgICAgICB2YXIgb3JpZ2luTWluWSA9ICgxIC8gc2NhbGUpICogaGFsZkhlaWdodDtcbiAgICAgICAgdmFyIG9yaWdpbk1heFkgPSAoY3VySW1nSGVpZ2h0ICogKDEgLyBzY2FsZSkpIC0gb3JpZ2luTWluWTtcblxuICAgICAgICByZXR1cm4ge1xuICAgICAgICAgICAgdHJhbnNsYXRlOiB7XG4gICAgICAgICAgICAgICAgbWF4WDogbWF4WCxcbiAgICAgICAgICAgICAgICBtaW5YOiBtaW5YLFxuICAgICAgICAgICAgICAgIG1heFk6IG1heFksXG4gICAgICAgICAgICAgICAgbWluWTogbWluWVxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIG9yaWdpbjoge1xuICAgICAgICAgICAgICAgIG1heFg6IG9yaWdpbk1heFgsXG4gICAgICAgICAgICAgICAgbWluWDogb3JpZ2luTWluWCxcbiAgICAgICAgICAgICAgICBtYXhZOiBvcmlnaW5NYXhZLFxuICAgICAgICAgICAgICAgIG1pblk6IG9yaWdpbk1pbllcbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcbiAgICB9XG5cbiAgICBmdW5jdGlvbiBfdXBkYXRlQ2VudGVyUG9pbnQoKSB7XG4gICAgICAgIHZhciBzZWxmID0gdGhpcyxcbiAgICAgICAgICAgIHNjYWxlID0gc2VsZi5fY3VycmVudFpvb20sXG4gICAgICAgICAgICBkYXRhID0gc2VsZi5lbGVtZW50cy5wcmV2aWV3LmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpLFxuICAgICAgICAgICAgdnBEYXRhID0gc2VsZi5lbGVtZW50cy52aWV3cG9ydC5nZXRCb3VuZGluZ0NsaWVudFJlY3QoKSxcbiAgICAgICAgICAgIHRyYW5zZm9ybSA9IFRyYW5zZm9ybS5wYXJzZShzZWxmLmVsZW1lbnRzLnByZXZpZXcuc3R5bGVbQ1NTX1RSQU5TRk9STV0pLFxuICAgICAgICAgICAgcGMgPSBuZXcgVHJhbnNmb3JtT3JpZ2luKHNlbGYuZWxlbWVudHMucHJldmlldyksXG4gICAgICAgICAgICB0b3AgPSAodnBEYXRhLnRvcCAtIGRhdGEudG9wKSArICh2cERhdGEuaGVpZ2h0IC8gMiksXG4gICAgICAgICAgICBsZWZ0ID0gKHZwRGF0YS5sZWZ0IC0gZGF0YS5sZWZ0KSArICh2cERhdGEud2lkdGggLyAyKSxcbiAgICAgICAgICAgIGNlbnRlciA9IHt9LFxuICAgICAgICAgICAgYWRqID0ge307XG5cbiAgICAgICAgY2VudGVyLnkgPSB0b3AgLyBzY2FsZTtcbiAgICAgICAgY2VudGVyLnggPSBsZWZ0IC8gc2NhbGU7XG5cbiAgICAgICAgYWRqLnkgPSAoY2VudGVyLnkgLSBwYy55KSAqICgxIC0gc2NhbGUpO1xuICAgICAgICBhZGoueCA9IChjZW50ZXIueCAtIHBjLngpICogKDEgLSBzY2FsZSk7XG5cbiAgICAgICAgdHJhbnNmb3JtLnggLT0gYWRqLng7XG4gICAgICAgIHRyYW5zZm9ybS55IC09IGFkai55O1xuXG4gICAgICAgIHZhciBuZXdDc3MgPSB7fTtcbiAgICAgICAgbmV3Q3NzW0NTU19UUkFOU19PUkddID0gY2VudGVyLnggKyAncHggJyArIGNlbnRlci55ICsgJ3B4JztcbiAgICAgICAgbmV3Q3NzW0NTU19UUkFOU0ZPUk1dID0gdHJhbnNmb3JtLnRvU3RyaW5nKCk7XG4gICAgICAgIGNzcyhzZWxmLmVsZW1lbnRzLnByZXZpZXcsIG5ld0Nzcyk7XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gX2luaXREcmFnZ2FibGUoKSB7XG4gICAgICAgIHZhciBzZWxmID0gdGhpcyxcbiAgICAgICAgICAgIGlzRHJhZ2dpbmcgPSBmYWxzZSxcbiAgICAgICAgICAgIG9yaWdpbmFsWCxcbiAgICAgICAgICAgIG9yaWdpbmFsWSxcbiAgICAgICAgICAgIG9yaWdpbmFsRGlzdGFuY2UsXG4gICAgICAgICAgICB2cFJlY3QsXG4gICAgICAgICAgICB0cmFuc2Zvcm07XG5cbiAgICAgICAgZnVuY3Rpb24gYXNzaWduVHJhbnNmb3JtQ29vcmRpbmF0ZXMoZGVsdGFYLCBkZWx0YVkpIHtcbiAgICAgICAgICAgIHZhciBpbWdSZWN0ID0gc2VsZi5lbGVtZW50cy5wcmV2aWV3LmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpLFxuICAgICAgICAgICAgICAgIHRvcCA9IHRyYW5zZm9ybS55ICsgZGVsdGFZLFxuICAgICAgICAgICAgICAgIGxlZnQgPSB0cmFuc2Zvcm0ueCArIGRlbHRhWDtcblxuICAgICAgICAgICAgaWYgKHNlbGYub3B0aW9ucy5lbmZvcmNlQm91bmRhcnkpIHtcbiAgICAgICAgICAgICAgICBpZiAodnBSZWN0LnRvcCA+IGltZ1JlY3QudG9wICsgZGVsdGFZICYmIHZwUmVjdC5ib3R0b20gPCBpbWdSZWN0LmJvdHRvbSArIGRlbHRhWSkge1xuICAgICAgICAgICAgICAgICAgICB0cmFuc2Zvcm0ueSA9IHRvcDtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICBpZiAodnBSZWN0LmxlZnQgPiBpbWdSZWN0LmxlZnQgKyBkZWx0YVggJiYgdnBSZWN0LnJpZ2h0IDwgaW1nUmVjdC5yaWdodCArIGRlbHRhWCkge1xuICAgICAgICAgICAgICAgICAgICB0cmFuc2Zvcm0ueCA9IGxlZnQ7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgdHJhbnNmb3JtLnkgPSB0b3A7XG4gICAgICAgICAgICAgICAgdHJhbnNmb3JtLnggPSBsZWZ0O1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG5cbiAgICAgICAgZnVuY3Rpb24gdG9nZ2xlR3JhYlN0YXRlKGlzRHJhZ2dpbmcpIHtcbiAgICAgICAgICBzZWxmLmVsZW1lbnRzLnByZXZpZXcuc2V0QXR0cmlidXRlKCdhcmlhLWdyYWJiZWQnLCBpc0RyYWdnaW5nKTtcbiAgICAgICAgICBzZWxmLmVsZW1lbnRzLmJvdW5kYXJ5LnNldEF0dHJpYnV0ZSgnYXJpYS1kcm9wZWZmZWN0JywgaXNEcmFnZ2luZz8gJ21vdmUnOiAnbm9uZScpO1xuICAgICAgICB9XG5cbiAgICAgICAgZnVuY3Rpb24ga2V5RG93bihldikge1xuICAgICAgICAgICAgdmFyIExFRlRfQVJST1cgID0gMzcsXG4gICAgICAgICAgICAgICAgVVBfQVJST1cgICAgPSAzOCxcbiAgICAgICAgICAgICAgICBSSUdIVF9BUlJPVyA9IDM5LFxuICAgICAgICAgICAgICAgIERPV05fQVJST1cgID0gNDA7XG5cbiAgICAgICAgICAgIGlmIChldi5zaGlmdEtleSAmJiAoZXYua2V5Q29kZSA9PT0gVVBfQVJST1cgfHwgZXYua2V5Q29kZSA9PT0gRE9XTl9BUlJPVykpIHtcbiAgICAgICAgICAgICAgICB2YXIgem9vbSA9IDAuMDtcbiAgICAgICAgICAgICAgICBpZiAoZXYua2V5Q29kZSA9PT0gVVBfQVJST1cpIHtcbiAgICAgICAgICAgICAgICAgICAgem9vbSA9IHBhcnNlRmxvYXQoc2VsZi5lbGVtZW50cy56b29tZXIudmFsdWUsIDEwKSArIHBhcnNlRmxvYXQoc2VsZi5lbGVtZW50cy56b29tZXIuc3RlcCwgMTApXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICB6b29tID0gcGFyc2VGbG9hdChzZWxmLmVsZW1lbnRzLnpvb21lci52YWx1ZSwgMTApIC0gcGFyc2VGbG9hdChzZWxmLmVsZW1lbnRzLnpvb21lci5zdGVwLCAxMClcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgc2VsZi5zZXRab29tKHpvb20pO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZSBpZiAoc2VsZi5vcHRpb25zLmVuYWJsZUtleU1vdmVtZW50ICYmIChldi5rZXlDb2RlID49IDM3ICYmIGV2LmtleUNvZGUgPD0gNDApKSB7XG4gICAgICAgICAgICAgICAgZXYucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgICAgICB2YXIgbW92ZW1lbnQgPSBwYXJzZUtleURvd24oZXYua2V5Q29kZSk7XG5cbiAgICAgICAgICAgICAgICB0cmFuc2Zvcm0gPSBUcmFuc2Zvcm0ucGFyc2Uoc2VsZi5lbGVtZW50cy5wcmV2aWV3KTtcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5ib2R5LnN0eWxlW0NTU19VU0VSU0VMRUNUXSA9ICdub25lJztcbiAgICAgICAgICAgICAgICB2cFJlY3QgPSBzZWxmLmVsZW1lbnRzLnZpZXdwb3J0LmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpO1xuICAgICAgICAgICAgICAgIGtleU1vdmUobW92ZW1lbnQpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBmdW5jdGlvbiBwYXJzZUtleURvd24oa2V5KSB7XG4gICAgICAgICAgICAgICAgc3dpdGNoIChrZXkpIHtcbiAgICAgICAgICAgICAgICAgICAgY2FzZSBMRUZUX0FSUk9XOlxuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIFsxLCAwXTtcbiAgICAgICAgICAgICAgICAgICAgY2FzZSBVUF9BUlJPVzpcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiBbMCwgMV07XG4gICAgICAgICAgICAgICAgICAgIGNhc2UgUklHSFRfQVJST1c6XG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gWy0xLCAwXTtcbiAgICAgICAgICAgICAgICAgICAgY2FzZSBET1dOX0FSUk9XOlxuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIFswLCAtMV07XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9XG5cbiAgICAgICAgZnVuY3Rpb24ga2V5TW92ZShtb3ZlbWVudCkge1xuICAgICAgICAgICAgdmFyIGRlbHRhWCA9IG1vdmVtZW50WzBdLFxuICAgICAgICAgICAgICAgIGRlbHRhWSA9IG1vdmVtZW50WzFdLFxuICAgICAgICAgICAgICAgIG5ld0NzcyA9IHt9O1xuXG4gICAgICAgICAgICBhc3NpZ25UcmFuc2Zvcm1Db29yZGluYXRlcyhkZWx0YVgsIGRlbHRhWSk7XG5cbiAgICAgICAgICAgIG5ld0Nzc1tDU1NfVFJBTlNGT1JNXSA9IHRyYW5zZm9ybS50b1N0cmluZygpO1xuICAgICAgICAgICAgY3NzKHNlbGYuZWxlbWVudHMucHJldmlldywgbmV3Q3NzKTtcbiAgICAgICAgICAgIF91cGRhdGVPdmVybGF5LmNhbGwoc2VsZik7XG4gICAgICAgICAgICBkb2N1bWVudC5ib2R5LnN0eWxlW0NTU19VU0VSU0VMRUNUXSA9ICcnO1xuICAgICAgICAgICAgX3VwZGF0ZUNlbnRlclBvaW50LmNhbGwoc2VsZik7XG4gICAgICAgICAgICBfdHJpZ2dlclVwZGF0ZS5jYWxsKHNlbGYpO1xuICAgICAgICAgICAgb3JpZ2luYWxEaXN0YW5jZSA9IDA7XG4gICAgICAgIH1cblxuICAgICAgICBmdW5jdGlvbiBtb3VzZURvd24oZXYpIHtcbiAgICAgICAgICAgIGlmIChldi5idXR0b24gIT09IHVuZGVmaW5lZCAmJiBldi5idXR0b24gIT09IDApIHJldHVybjtcblxuICAgICAgICAgICAgZXYucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgIGlmIChpc0RyYWdnaW5nKSByZXR1cm47XG4gICAgICAgICAgICBpc0RyYWdnaW5nID0gdHJ1ZTtcbiAgICAgICAgICAgIG9yaWdpbmFsWCA9IGV2LnBhZ2VYO1xuICAgICAgICAgICAgb3JpZ2luYWxZID0gZXYucGFnZVk7XG5cbiAgICAgICAgICAgIGlmIChldi50b3VjaGVzKSB7XG4gICAgICAgICAgICAgICAgdmFyIHRvdWNoZXMgPSBldi50b3VjaGVzWzBdO1xuICAgICAgICAgICAgICAgIG9yaWdpbmFsWCA9IHRvdWNoZXMucGFnZVg7XG4gICAgICAgICAgICAgICAgb3JpZ2luYWxZID0gdG91Y2hlcy5wYWdlWTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHRvZ2dsZUdyYWJTdGF0ZShpc0RyYWdnaW5nKTtcbiAgICAgICAgICAgIHRyYW5zZm9ybSA9IFRyYW5zZm9ybS5wYXJzZShzZWxmLmVsZW1lbnRzLnByZXZpZXcpO1xuICAgICAgICAgICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ21vdXNlbW92ZScsIG1vdXNlTW92ZSk7XG4gICAgICAgICAgICB3aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcigndG91Y2htb3ZlJywgbW91c2VNb3ZlKTtcbiAgICAgICAgICAgIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKCdtb3VzZXVwJywgbW91c2VVcCk7XG4gICAgICAgICAgICB3aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcigndG91Y2hlbmQnLCBtb3VzZVVwKTtcbiAgICAgICAgICAgIGRvY3VtZW50LmJvZHkuc3R5bGVbQ1NTX1VTRVJTRUxFQ1RdID0gJ25vbmUnO1xuICAgICAgICAgICAgdnBSZWN0ID0gc2VsZi5lbGVtZW50cy52aWV3cG9ydC5nZXRCb3VuZGluZ0NsaWVudFJlY3QoKTtcbiAgICAgICAgfVxuXG4gICAgICAgIGZ1bmN0aW9uIG1vdXNlTW92ZShldikge1xuICAgICAgICAgICAgZXYucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgIHZhciBwYWdlWCA9IGV2LnBhZ2VYLFxuICAgICAgICAgICAgICAgIHBhZ2VZID0gZXYucGFnZVk7XG5cbiAgICAgICAgICAgIGlmIChldi50b3VjaGVzKSB7XG4gICAgICAgICAgICAgICAgdmFyIHRvdWNoZXMgPSBldi50b3VjaGVzWzBdO1xuICAgICAgICAgICAgICAgIHBhZ2VYID0gdG91Y2hlcy5wYWdlWDtcbiAgICAgICAgICAgICAgICBwYWdlWSA9IHRvdWNoZXMucGFnZVk7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHZhciBkZWx0YVggPSBwYWdlWCAtIG9yaWdpbmFsWCxcbiAgICAgICAgICAgICAgICBkZWx0YVkgPSBwYWdlWSAtIG9yaWdpbmFsWSxcbiAgICAgICAgICAgICAgICBuZXdDc3MgPSB7fTtcblxuICAgICAgICAgICAgaWYgKGV2LnR5cGUgPT09ICd0b3VjaG1vdmUnKSB7XG4gICAgICAgICAgICAgICAgaWYgKGV2LnRvdWNoZXMubGVuZ3RoID4gMSkge1xuICAgICAgICAgICAgICAgICAgICB2YXIgdG91Y2gxID0gZXYudG91Y2hlc1swXTtcbiAgICAgICAgICAgICAgICAgICAgdmFyIHRvdWNoMiA9IGV2LnRvdWNoZXNbMV07XG4gICAgICAgICAgICAgICAgICAgIHZhciBkaXN0ID0gTWF0aC5zcXJ0KCh0b3VjaDEucGFnZVggLSB0b3VjaDIucGFnZVgpICogKHRvdWNoMS5wYWdlWCAtIHRvdWNoMi5wYWdlWCkgKyAodG91Y2gxLnBhZ2VZIC0gdG91Y2gyLnBhZ2VZKSAqICh0b3VjaDEucGFnZVkgLSB0b3VjaDIucGFnZVkpKTtcblxuICAgICAgICAgICAgICAgICAgICBpZiAoIW9yaWdpbmFsRGlzdGFuY2UpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG9yaWdpbmFsRGlzdGFuY2UgPSBkaXN0IC8gc2VsZi5fY3VycmVudFpvb207XG4gICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgICAgICB2YXIgc2NhbGUgPSBkaXN0IC8gb3JpZ2luYWxEaXN0YW5jZTtcblxuICAgICAgICAgICAgICAgICAgICBfc2V0Wm9vbWVyVmFsLmNhbGwoc2VsZiwgc2NhbGUpO1xuICAgICAgICAgICAgICAgICAgICBkaXNwYXRjaENoYW5nZShzZWxmLmVsZW1lbnRzLnpvb21lcik7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGFzc2lnblRyYW5zZm9ybUNvb3JkaW5hdGVzKGRlbHRhWCwgZGVsdGFZKTtcblxuICAgICAgICAgICAgbmV3Q3NzW0NTU19UUkFOU0ZPUk1dID0gdHJhbnNmb3JtLnRvU3RyaW5nKCk7XG4gICAgICAgICAgICBjc3Moc2VsZi5lbGVtZW50cy5wcmV2aWV3LCBuZXdDc3MpO1xuICAgICAgICAgICAgX3VwZGF0ZU92ZXJsYXkuY2FsbChzZWxmKTtcbiAgICAgICAgICAgIG9yaWdpbmFsWSA9IHBhZ2VZO1xuICAgICAgICAgICAgb3JpZ2luYWxYID0gcGFnZVg7XG4gICAgICAgIH1cblxuICAgICAgICBmdW5jdGlvbiBtb3VzZVVwKCkge1xuICAgICAgICAgICAgaXNEcmFnZ2luZyA9IGZhbHNlO1xuICAgICAgICAgICAgdG9nZ2xlR3JhYlN0YXRlKGlzRHJhZ2dpbmcpO1xuICAgICAgICAgICAgd2luZG93LnJlbW92ZUV2ZW50TGlzdGVuZXIoJ21vdXNlbW92ZScsIG1vdXNlTW92ZSk7XG4gICAgICAgICAgICB3aW5kb3cucmVtb3ZlRXZlbnRMaXN0ZW5lcigndG91Y2htb3ZlJywgbW91c2VNb3ZlKTtcbiAgICAgICAgICAgIHdpbmRvdy5yZW1vdmVFdmVudExpc3RlbmVyKCdtb3VzZXVwJywgbW91c2VVcCk7XG4gICAgICAgICAgICB3aW5kb3cucmVtb3ZlRXZlbnRMaXN0ZW5lcigndG91Y2hlbmQnLCBtb3VzZVVwKTtcbiAgICAgICAgICAgIGRvY3VtZW50LmJvZHkuc3R5bGVbQ1NTX1VTRVJTRUxFQ1RdID0gJyc7XG4gICAgICAgICAgICBfdXBkYXRlQ2VudGVyUG9pbnQuY2FsbChzZWxmKTtcbiAgICAgICAgICAgIF90cmlnZ2VyVXBkYXRlLmNhbGwoc2VsZik7XG4gICAgICAgICAgICBvcmlnaW5hbERpc3RhbmNlID0gMDtcbiAgICAgICAgfVxuXG4gICAgICAgIHNlbGYuZWxlbWVudHMub3ZlcmxheS5hZGRFdmVudExpc3RlbmVyKCdtb3VzZWRvd24nLCBtb3VzZURvd24pO1xuICAgICAgICBzZWxmLmVsZW1lbnRzLnZpZXdwb3J0LmFkZEV2ZW50TGlzdGVuZXIoJ2tleWRvd24nLCBrZXlEb3duKTtcbiAgICAgICAgc2VsZi5lbGVtZW50cy5vdmVybGF5LmFkZEV2ZW50TGlzdGVuZXIoJ3RvdWNoc3RhcnQnLCBtb3VzZURvd24pO1xuICAgIH1cblxuICAgIGZ1bmN0aW9uIF91cGRhdGVPdmVybGF5KCkge1xuICAgICAgICBpZiAoIXRoaXMuZWxlbWVudHMpIHJldHVybjsgLy8gc2luY2UgdGhpcyBpcyBkZWJvdW5jZWQsIGl0IGNhbiBiZSBmaXJlZCBhZnRlciBkZXN0cm95XG4gICAgICAgIHZhciBzZWxmID0gdGhpcyxcbiAgICAgICAgICAgIGJvdW5kUmVjdCA9IHNlbGYuZWxlbWVudHMuYm91bmRhcnkuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCksXG4gICAgICAgICAgICBpbWdEYXRhID0gc2VsZi5lbGVtZW50cy5wcmV2aWV3LmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpO1xuXG4gICAgICAgIGNzcyhzZWxmLmVsZW1lbnRzLm92ZXJsYXksIHtcbiAgICAgICAgICAgIHdpZHRoOiBpbWdEYXRhLndpZHRoICsgJ3B4JyxcbiAgICAgICAgICAgIGhlaWdodDogaW1nRGF0YS5oZWlnaHQgKyAncHgnLFxuICAgICAgICAgICAgdG9wOiAoaW1nRGF0YS50b3AgLSBib3VuZFJlY3QudG9wKSArICdweCcsXG4gICAgICAgICAgICBsZWZ0OiAoaW1nRGF0YS5sZWZ0IC0gYm91bmRSZWN0LmxlZnQpICsgJ3B4J1xuICAgICAgICB9KTtcbiAgICB9XG4gICAgdmFyIF9kZWJvdW5jZWRPdmVybGF5ID0gZGVib3VuY2UoX3VwZGF0ZU92ZXJsYXksIDUwMCk7XG5cbiAgICBmdW5jdGlvbiBfdHJpZ2dlclVwZGF0ZSgpIHtcbiAgICAgICAgdmFyIHNlbGYgPSB0aGlzLFxuICAgICAgICAgICAgZGF0YSA9IHNlbGYuZ2V0KCksXG4gICAgICAgICAgICBldjtcblxuICAgICAgICBpZiAoIV9pc1Zpc2libGUuY2FsbChzZWxmKSkge1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG5cbiAgICAgICAgc2VsZi5vcHRpb25zLnVwZGF0ZS5jYWxsKHNlbGYsIGRhdGEpO1xuICAgICAgICBpZiAoc2VsZi4kICYmIHR5cGVvZiBQcm90b3R5cGUgPT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICBzZWxmLiQoc2VsZi5lbGVtZW50KS50cmlnZ2VyKCd1cGRhdGUuY3JvcHBpZScsIGRhdGEpO1xuICAgICAgICB9XG4gICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgdmFyIGV2O1xuICAgICAgICAgICAgaWYgKHdpbmRvdy5DdXN0b21FdmVudCkge1xuICAgICAgICAgICAgICAgIGV2ID0gbmV3IEN1c3RvbUV2ZW50KCd1cGRhdGUnLCB7IGRldGFpbDogZGF0YSB9KTtcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgZXYgPSBkb2N1bWVudC5jcmVhdGVFdmVudCgnQ3VzdG9tRXZlbnQnKTtcbiAgICAgICAgICAgICAgICBldi5pbml0Q3VzdG9tRXZlbnQoJ3VwZGF0ZScsIHRydWUsIHRydWUsIGRhdGEpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBzZWxmLmVsZW1lbnQuZGlzcGF0Y2hFdmVudChldik7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICBmdW5jdGlvbiBfaXNWaXNpYmxlKCkge1xuICAgICAgICByZXR1cm4gdGhpcy5lbGVtZW50cy5wcmV2aWV3Lm9mZnNldEhlaWdodCA+IDAgJiYgdGhpcy5lbGVtZW50cy5wcmV2aWV3Lm9mZnNldFdpZHRoID4gMDtcbiAgICB9XG5cbiAgICBmdW5jdGlvbiBfdXBkYXRlUHJvcGVydGllc0Zyb21JbWFnZSgpIHtcbiAgICAgICAgdmFyIHNlbGYgPSB0aGlzLFxuICAgICAgICAgICAgaW5pdGlhbFpvb20gPSAxLFxuICAgICAgICAgICAgY3NzUmVzZXQgPSB7fSxcbiAgICAgICAgICAgIGltZyA9IHNlbGYuZWxlbWVudHMucHJldmlldyxcbiAgICAgICAgICAgIGltZ0RhdGEgPSBudWxsLFxuICAgICAgICAgICAgdHJhbnNmb3JtUmVzZXQgPSBuZXcgVHJhbnNmb3JtKDAsIDAsIGluaXRpYWxab29tKSxcbiAgICAgICAgICAgIG9yaWdpblJlc2V0ID0gbmV3IFRyYW5zZm9ybU9yaWdpbigpLFxuICAgICAgICAgICAgaXNWaXNpYmxlID0gX2lzVmlzaWJsZS5jYWxsKHNlbGYpO1xuXG4gICAgICAgIGlmICghaXNWaXNpYmxlIHx8IHNlbGYuZGF0YS5ib3VuZCkgey8vIGlmIHRoZSBjcm9wcGllIGlzbid0IHZpc2libGUgb3IgaXQgZG9lc24ndCBuZWVkIGJpbmRpbmdcbiAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuXG4gICAgICAgIHNlbGYuZGF0YS5ib3VuZCA9IHRydWU7XG4gICAgICAgIGNzc1Jlc2V0W0NTU19UUkFOU0ZPUk1dID0gdHJhbnNmb3JtUmVzZXQudG9TdHJpbmcoKTtcbiAgICAgICAgY3NzUmVzZXRbQ1NTX1RSQU5TX09SR10gPSBvcmlnaW5SZXNldC50b1N0cmluZygpO1xuICAgICAgICBjc3NSZXNldFsnb3BhY2l0eSddID0gMTtcbiAgICAgICAgY3NzKGltZywgY3NzUmVzZXQpO1xuXG4gICAgICAgIGltZ0RhdGEgPSBzZWxmLmVsZW1lbnRzLnByZXZpZXcuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCk7XG5cbiAgICAgICAgc2VsZi5fb3JpZ2luYWxJbWFnZVdpZHRoID0gaW1nRGF0YS53aWR0aDtcbiAgICAgICAgc2VsZi5fb3JpZ2luYWxJbWFnZUhlaWdodCA9IGltZ0RhdGEuaGVpZ2h0O1xuICAgICAgICBzZWxmLmRhdGEub3JpZW50YXRpb24gPSBnZXRFeGlmT3JpZW50YXRpb24oc2VsZi5lbGVtZW50cy5pbWcpO1xuXG4gICAgICAgIGlmIChzZWxmLm9wdGlvbnMuZW5hYmxlWm9vbSkge1xuICAgICAgICAgICAgX3VwZGF0ZVpvb21MaW1pdHMuY2FsbChzZWxmLCB0cnVlKTtcbiAgICAgICAgfVxuICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgIHNlbGYuX2N1cnJlbnRab29tID0gaW5pdGlhbFpvb207XG4gICAgICAgIH1cblxuICAgICAgICB0cmFuc2Zvcm1SZXNldC5zY2FsZSA9IHNlbGYuX2N1cnJlbnRab29tO1xuICAgICAgICBjc3NSZXNldFtDU1NfVFJBTlNGT1JNXSA9IHRyYW5zZm9ybVJlc2V0LnRvU3RyaW5nKCk7XG4gICAgICAgIGNzcyhpbWcsIGNzc1Jlc2V0KTtcblxuICAgICAgICBpZiAoc2VsZi5kYXRhLnBvaW50cy5sZW5ndGgpIHtcbiAgICAgICAgICAgIF9iaW5kUG9pbnRzLmNhbGwoc2VsZiwgc2VsZi5kYXRhLnBvaW50cyk7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICBfY2VudGVySW1hZ2UuY2FsbChzZWxmKTtcbiAgICAgICAgfVxuXG4gICAgICAgIF91cGRhdGVDZW50ZXJQb2ludC5jYWxsKHNlbGYpO1xuICAgICAgICBfdXBkYXRlT3ZlcmxheS5jYWxsKHNlbGYpO1xuICAgIH1cblxuICAgIGZ1bmN0aW9uIF91cGRhdGVab29tTGltaXRzIChpbml0aWFsKSB7XG4gICAgICAgIHZhciBzZWxmID0gdGhpcyxcbiAgICAgICAgICAgIG1pblpvb20gPSAwLFxuICAgICAgICAgICAgbWF4Wm9vbSA9IHNlbGYub3B0aW9ucy5tYXhab29tIHx8IDEuNSxcbiAgICAgICAgICAgIGluaXRpYWxab29tLFxuICAgICAgICAgICAgZGVmYXVsdEluaXRpYWxab29tLFxuICAgICAgICAgICAgem9vbWVyID0gc2VsZi5lbGVtZW50cy56b29tZXIsXG4gICAgICAgICAgICBzY2FsZSA9IHBhcnNlRmxvYXQoem9vbWVyLnZhbHVlKSxcbiAgICAgICAgICAgIGJvdW5kYXJ5RGF0YSA9IHNlbGYuZWxlbWVudHMuYm91bmRhcnkuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCksXG4gICAgICAgICAgICBpbWdEYXRhID0gbmF0dXJhbEltYWdlRGltZW5zaW9ucyhzZWxmLmVsZW1lbnRzLmltZywgc2VsZi5kYXRhLm9yaWVudGF0aW9uKSxcbiAgICAgICAgICAgIHZwRGF0YSA9IHNlbGYuZWxlbWVudHMudmlld3BvcnQuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCksXG4gICAgICAgICAgICBtaW5XLFxuICAgICAgICAgICAgbWluSDtcbiAgICAgICAgaWYgKHNlbGYub3B0aW9ucy5lbmZvcmNlQm91bmRhcnkpIHtcbiAgICAgICAgICAgIG1pblcgPSB2cERhdGEud2lkdGggLyBpbWdEYXRhLndpZHRoO1xuICAgICAgICAgICAgbWluSCA9IHZwRGF0YS5oZWlnaHQgLyBpbWdEYXRhLmhlaWdodDtcbiAgICAgICAgICAgIG1pblpvb20gPSBNYXRoLm1heChtaW5XLCBtaW5IKTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChtaW5ab29tID49IG1heFpvb20pIHtcbiAgICAgICAgICAgIG1heFpvb20gPSBtaW5ab29tICsgMTtcbiAgICAgICAgfVxuXG4gICAgICAgIHpvb21lci5taW4gPSBmaXgobWluWm9vbSwgNCk7XG4gICAgICAgIHpvb21lci5tYXggPSBmaXgobWF4Wm9vbSwgNCk7XG4gICAgICAgIFxuICAgICAgICBpZiAoIWluaXRpYWwgJiYgKHNjYWxlIDwgem9vbWVyLm1pbiB8fCBzY2FsZSA+IHpvb21lci5tYXgpKSB7XG4gICAgICAgICAgICBfc2V0Wm9vbWVyVmFsLmNhbGwoc2VsZiwgc2NhbGUgPCB6b29tZXIubWluID8gem9vbWVyLm1pbiA6IHpvb21lci5tYXgpO1xuICAgICAgICB9XG4gICAgICAgIGVsc2UgaWYgKGluaXRpYWwpIHtcbiAgICAgICAgICAgIGRlZmF1bHRJbml0aWFsWm9vbSA9IE1hdGgubWF4KChib3VuZGFyeURhdGEud2lkdGggLyBpbWdEYXRhLndpZHRoKSwgKGJvdW5kYXJ5RGF0YS5oZWlnaHQgLyBpbWdEYXRhLmhlaWdodCkpO1xuICAgICAgICAgICAgaW5pdGlhbFpvb20gPSBzZWxmLmRhdGEuYm91bmRab29tICE9PSBudWxsID8gc2VsZi5kYXRhLmJvdW5kWm9vbSA6IGRlZmF1bHRJbml0aWFsWm9vbTtcbiAgICAgICAgICAgIF9zZXRab29tZXJWYWwuY2FsbChzZWxmLCBpbml0aWFsWm9vbSk7XG4gICAgICAgIH1cblxuICAgICAgICBkaXNwYXRjaENoYW5nZSh6b29tZXIpO1xuICAgIH1cblxuICAgIGZ1bmN0aW9uIF9iaW5kUG9pbnRzKHBvaW50cykge1xuICAgICAgICBpZiAocG9pbnRzLmxlbmd0aCAhPT0gNCkge1xuICAgICAgICAgICAgdGhyb3cgXCJDcm9wcGllIC0gSW52YWxpZCBudW1iZXIgb2YgcG9pbnRzIHN1cHBsaWVkOiBcIiArIHBvaW50cztcbiAgICAgICAgfVxuICAgICAgICB2YXIgc2VsZiA9IHRoaXMsXG4gICAgICAgICAgICBwb2ludHNXaWR0aCA9IHBvaW50c1syXSAtIHBvaW50c1swXSxcbiAgICAgICAgICAgIC8vIHBvaW50c0hlaWdodCA9IHBvaW50c1szXSAtIHBvaW50c1sxXSxcbiAgICAgICAgICAgIHZwRGF0YSA9IHNlbGYuZWxlbWVudHMudmlld3BvcnQuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCksXG4gICAgICAgICAgICBib3VuZFJlY3QgPSBzZWxmLmVsZW1lbnRzLmJvdW5kYXJ5LmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpLFxuICAgICAgICAgICAgdnBPZmZzZXQgPSB7XG4gICAgICAgICAgICAgICAgbGVmdDogdnBEYXRhLmxlZnQgLSBib3VuZFJlY3QubGVmdCxcbiAgICAgICAgICAgICAgICB0b3A6IHZwRGF0YS50b3AgLSBib3VuZFJlY3QudG9wXG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgc2NhbGUgPSB2cERhdGEud2lkdGggLyBwb2ludHNXaWR0aCxcbiAgICAgICAgICAgIG9yaWdpblRvcCA9IHBvaW50c1sxXSxcbiAgICAgICAgICAgIG9yaWdpbkxlZnQgPSBwb2ludHNbMF0sXG4gICAgICAgICAgICB0cmFuc2Zvcm1Ub3AgPSAoLTEgKiBwb2ludHNbMV0pICsgdnBPZmZzZXQudG9wLFxuICAgICAgICAgICAgdHJhbnNmb3JtTGVmdCA9ICgtMSAqIHBvaW50c1swXSkgKyB2cE9mZnNldC5sZWZ0LFxuICAgICAgICAgICAgbmV3Q3NzID0ge307XG5cbiAgICAgICAgbmV3Q3NzW0NTU19UUkFOU19PUkddID0gb3JpZ2luTGVmdCArICdweCAnICsgb3JpZ2luVG9wICsgJ3B4JztcbiAgICAgICAgbmV3Q3NzW0NTU19UUkFOU0ZPUk1dID0gbmV3IFRyYW5zZm9ybSh0cmFuc2Zvcm1MZWZ0LCB0cmFuc2Zvcm1Ub3AsIHNjYWxlKS50b1N0cmluZygpO1xuICAgICAgICBjc3Moc2VsZi5lbGVtZW50cy5wcmV2aWV3LCBuZXdDc3MpO1xuXG4gICAgICAgIF9zZXRab29tZXJWYWwuY2FsbChzZWxmLCBzY2FsZSk7XG4gICAgICAgIHNlbGYuX2N1cnJlbnRab29tID0gc2NhbGU7XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gX2NlbnRlckltYWdlKCkge1xuICAgICAgICB2YXIgc2VsZiA9IHRoaXMsXG4gICAgICAgICAgICBpbWdEaW0gPSBzZWxmLmVsZW1lbnRzLnByZXZpZXcuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCksXG4gICAgICAgICAgICB2cERpbSA9IHNlbGYuZWxlbWVudHMudmlld3BvcnQuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCksXG4gICAgICAgICAgICBib3VuZERpbSA9IHNlbGYuZWxlbWVudHMuYm91bmRhcnkuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCksXG4gICAgICAgICAgICB2cExlZnQgPSB2cERpbS5sZWZ0IC0gYm91bmREaW0ubGVmdCxcbiAgICAgICAgICAgIHZwVG9wID0gdnBEaW0udG9wIC0gYm91bmREaW0udG9wLFxuICAgICAgICAgICAgdyA9IHZwTGVmdCAtICgoaW1nRGltLndpZHRoIC0gdnBEaW0ud2lkdGgpIC8gMiksXG4gICAgICAgICAgICBoID0gdnBUb3AgLSAoKGltZ0RpbS5oZWlnaHQgLSB2cERpbS5oZWlnaHQpIC8gMiksXG4gICAgICAgICAgICB0cmFuc2Zvcm0gPSBuZXcgVHJhbnNmb3JtKHcsIGgsIHNlbGYuX2N1cnJlbnRab29tKTtcblxuICAgICAgICBjc3Moc2VsZi5lbGVtZW50cy5wcmV2aWV3LCBDU1NfVFJBTlNGT1JNLCB0cmFuc2Zvcm0udG9TdHJpbmcoKSk7XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gX3RyYW5zZmVySW1hZ2VUb0NhbnZhcyhjdXN0b21PcmllbnRhdGlvbikge1xuICAgICAgICB2YXIgc2VsZiA9IHRoaXMsXG4gICAgICAgICAgICBjYW52YXMgPSBzZWxmLmVsZW1lbnRzLmNhbnZhcyxcbiAgICAgICAgICAgIGltZyA9IHNlbGYuZWxlbWVudHMuaW1nLFxuICAgICAgICAgICAgY3R4ID0gY2FudmFzLmdldENvbnRleHQoJzJkJyk7XG5cbiAgICAgICAgY3R4LmNsZWFyUmVjdCgwLCAwLCBjYW52YXMud2lkdGgsIGNhbnZhcy5oZWlnaHQpO1xuICAgICAgICBjYW52YXMud2lkdGggPSBpbWcud2lkdGg7XG4gICAgICAgIGNhbnZhcy5oZWlnaHQgPSBpbWcuaGVpZ2h0O1xuXG4gICAgICAgIHZhciBvcmllbnRhdGlvbiA9IHNlbGYub3B0aW9ucy5lbmFibGVPcmllbnRhdGlvbiAmJiBjdXN0b21PcmllbnRhdGlvbiB8fCBnZXRFeGlmT3JpZW50YXRpb24oaW1nKTtcbiAgICAgICAgZHJhd0NhbnZhcyhjYW52YXMsIGltZywgb3JpZW50YXRpb24pO1xuICAgIH1cblxuICAgIGZ1bmN0aW9uIF9nZXRDYW52YXMoZGF0YSkge1xuICAgICAgICB2YXIgc2VsZiA9IHRoaXMsXG4gICAgICAgICAgICBwb2ludHMgPSBkYXRhLnBvaW50cyxcbiAgICAgICAgICAgIGxlZnQgPSBudW0ocG9pbnRzWzBdKSxcbiAgICAgICAgICAgIHRvcCA9IG51bShwb2ludHNbMV0pLFxuICAgICAgICAgICAgcmlnaHQgPSBudW0ocG9pbnRzWzJdKSxcbiAgICAgICAgICAgIGJvdHRvbSA9IG51bShwb2ludHNbM10pLFxuICAgICAgICAgICAgd2lkdGggPSByaWdodC1sZWZ0LFxuICAgICAgICAgICAgaGVpZ2h0ID0gYm90dG9tLXRvcCxcbiAgICAgICAgICAgIGNpcmNsZSA9IGRhdGEuY2lyY2xlLFxuICAgICAgICAgICAgY2FudmFzID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnY2FudmFzJyksXG4gICAgICAgICAgICBjdHggPSBjYW52YXMuZ2V0Q29udGV4dCgnMmQnKSxcbiAgICAgICAgICAgIHN0YXJ0WCA9IDAsXG4gICAgICAgICAgICBzdGFydFkgPSAwLFxuICAgICAgICAgICAgY2FudmFzV2lkdGggPSBkYXRhLm91dHB1dFdpZHRoIHx8IHdpZHRoLFxuICAgICAgICAgICAgY2FudmFzSGVpZ2h0ID0gZGF0YS5vdXRwdXRIZWlnaHQgfHwgaGVpZ2h0LFxuICAgICAgICAgICAgY3VzdG9tRGltZW5zaW9ucyA9IChkYXRhLm91dHB1dFdpZHRoICYmIGRhdGEub3V0cHV0SGVpZ2h0KSxcbiAgICAgICAgICAgIG91dHB1dFdpZHRoUmF0aW8gPSAxLFxuICAgICAgICAgICAgb3V0cHV0SGVpZ2h0UmF0aW8gPSAxO1xuXG4gICAgICAgIGNhbnZhcy53aWR0aCA9IGNhbnZhc1dpZHRoO1xuICAgICAgICBjYW52YXMuaGVpZ2h0ID0gY2FudmFzSGVpZ2h0O1xuXG4gICAgICAgIGlmIChkYXRhLmJhY2tncm91bmRDb2xvcikge1xuICAgICAgICAgICAgY3R4LmZpbGxTdHlsZSA9IGRhdGEuYmFja2dyb3VuZENvbG9yO1xuICAgICAgICAgICAgY3R4LmZpbGxSZWN0KDAsIDAsIGNhbnZhc1dpZHRoLCBjYW52YXNIZWlnaHQpO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKHNlbGYub3B0aW9ucy5lbmZvcmNlQm91bmRhcnkgIT09IGZhbHNlKSB7XG4gICAgICAgICAgICB3aWR0aCA9IE1hdGgubWluKHdpZHRoLCBzZWxmLl9vcmlnaW5hbEltYWdlV2lkdGgpO1xuICAgICAgICAgICAgaGVpZ2h0ID0gTWF0aC5taW4oaGVpZ2h0LCBzZWxmLl9vcmlnaW5hbEltYWdlSGVpZ2h0KTtcbiAgICAgICAgfVxuICAgIFxuICAgICAgICAvLyBjb25zb2xlLnRhYmxlKHsgbGVmdCwgcmlnaHQsIHRvcCwgYm90dG9tLCBjYW52YXNXaWR0aCwgY2FudmFzSGVpZ2h0IH0pO1xuICAgICAgICBjdHguZHJhd0ltYWdlKHRoaXMuZWxlbWVudHMucHJldmlldywgbGVmdCwgdG9wLCB3aWR0aCwgaGVpZ2h0LCBzdGFydFgsIHN0YXJ0WSwgY2FudmFzV2lkdGgsIGNhbnZhc0hlaWdodCk7XG4gICAgICAgIGlmIChjaXJjbGUpIHtcbiAgICAgICAgICAgIGN0eC5maWxsU3R5bGUgPSAnI2ZmZic7XG4gICAgICAgICAgICBjdHguZ2xvYmFsQ29tcG9zaXRlT3BlcmF0aW9uID0gJ2Rlc3RpbmF0aW9uLWluJztcbiAgICAgICAgICAgIGN0eC5iZWdpblBhdGgoKTtcbiAgICAgICAgICAgIGN0eC5hcmMoY2FudmFzLndpZHRoIC8gMiwgY2FudmFzLmhlaWdodCAvIDIsIGNhbnZhcy53aWR0aCAvIDIsIDAsIE1hdGguUEkgKiAyLCB0cnVlKTtcbiAgICAgICAgICAgIGN0eC5jbG9zZVBhdGgoKTtcbiAgICAgICAgICAgIGN0eC5maWxsKCk7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGNhbnZhcztcbiAgICB9XG5cbiAgICBmdW5jdGlvbiBfZ2V0SHRtbFJlc3VsdChkYXRhKSB7XG4gICAgICAgIHZhciBwb2ludHMgPSBkYXRhLnBvaW50cyxcbiAgICAgICAgICAgIGRpdiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpLFxuICAgICAgICAgICAgaW1nID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnaW1nJyksXG4gICAgICAgICAgICB3aWR0aCA9IHBvaW50c1syXSAtIHBvaW50c1swXSxcbiAgICAgICAgICAgIGhlaWdodCA9IHBvaW50c1szXSAtIHBvaW50c1sxXTtcblxuICAgICAgICBhZGRDbGFzcyhkaXYsICdjcm9wcGllLXJlc3VsdCcpO1xuICAgICAgICBkaXYuYXBwZW5kQ2hpbGQoaW1nKTtcbiAgICAgICAgY3NzKGltZywge1xuICAgICAgICAgICAgbGVmdDogKC0xICogcG9pbnRzWzBdKSArICdweCcsXG4gICAgICAgICAgICB0b3A6ICgtMSAqIHBvaW50c1sxXSkgKyAncHgnXG4gICAgICAgIH0pO1xuICAgICAgICBpbWcuc3JjID0gZGF0YS51cmw7XG4gICAgICAgIGNzcyhkaXYsIHtcbiAgICAgICAgICAgIHdpZHRoOiB3aWR0aCArICdweCcsXG4gICAgICAgICAgICBoZWlnaHQ6IGhlaWdodCArICdweCdcbiAgICAgICAgfSk7XG5cbiAgICAgICAgcmV0dXJuIGRpdjtcbiAgICB9XG5cbiAgICBmdW5jdGlvbiBfZ2V0QmFzZTY0UmVzdWx0KGRhdGEpIHtcbiAgICAgICAgcmV0dXJuIF9nZXRDYW52YXMuY2FsbCh0aGlzLCBkYXRhKS50b0RhdGFVUkwoZGF0YS5mb3JtYXQsIGRhdGEucXVhbGl0eSk7XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gX2dldEJsb2JSZXN1bHQoZGF0YSkge1xuICAgICAgICB2YXIgc2VsZiA9IHRoaXM7XG4gICAgICAgIHJldHVybiBuZXcgUHJvbWlzZShmdW5jdGlvbiAocmVzb2x2ZSwgcmVqZWN0KSB7XG4gICAgICAgICAgICBfZ2V0Q2FudmFzLmNhbGwoc2VsZiwgZGF0YSkudG9CbG9iKGZ1bmN0aW9uIChibG9iKSB7XG4gICAgICAgICAgICAgICAgcmVzb2x2ZShibG9iKTtcbiAgICAgICAgICAgIH0sIGRhdGEuZm9ybWF0LCBkYXRhLnF1YWxpdHkpO1xuICAgICAgICB9KTtcbiAgICB9XG5cbiAgICBmdW5jdGlvbiBfcmVwbGFjZUltYWdlKGltZykge1xuICAgICAgICBpZiAodGhpcy5lbGVtZW50cy5pbWcucGFyZW50Tm9kZSkge1xuICAgICAgICAgICAgQXJyYXkucHJvdG90eXBlLmZvckVhY2guY2FsbCh0aGlzLmVsZW1lbnRzLmltZy5jbGFzc0xpc3QsIGZ1bmN0aW9uKGMpIHsgaW1nLmNsYXNzTGlzdC5hZGQoYyk7IH0pO1xuICAgICAgICAgICAgdGhpcy5lbGVtZW50cy5pbWcucGFyZW50Tm9kZS5yZXBsYWNlQ2hpbGQoaW1nLCB0aGlzLmVsZW1lbnRzLmltZyk7XG4gICAgICAgICAgICB0aGlzLmVsZW1lbnRzLnByZXZpZXcgPSBpbWc7IC8vIGlmIHRoZSBpbWcgaXMgYXR0YWNoZWQgdG8gdGhlIERPTSwgdGhleSdyZSBub3QgdXNpbmcgdGhlIGNhbnZhc1xuICAgICAgICB9XG4gICAgICAgIHRoaXMuZWxlbWVudHMuaW1nID0gaW1nO1xuICAgIH1cblxuICAgIGZ1bmN0aW9uIF9iaW5kKG9wdGlvbnMsIGNiKSB7XG4gICAgICAgIHZhciBzZWxmID0gdGhpcyxcbiAgICAgICAgICAgIHVybCxcbiAgICAgICAgICAgIHBvaW50cyA9IFtdLFxuICAgICAgICAgICAgem9vbSA9IG51bGwsXG4gICAgICAgICAgICBoYXNFeGlmID0gX2hhc0V4aWYuY2FsbChzZWxmKTtcblxuICAgICAgICBpZiAodHlwZW9mIChvcHRpb25zKSA9PT0gJ3N0cmluZycpIHtcbiAgICAgICAgICAgIHVybCA9IG9wdGlvbnM7XG4gICAgICAgICAgICBvcHRpb25zID0ge307XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSBpZiAoQXJyYXkuaXNBcnJheShvcHRpb25zKSkge1xuICAgICAgICAgICAgcG9pbnRzID0gb3B0aW9ucy5zbGljZSgpO1xuICAgICAgICB9XG4gICAgICAgIGVsc2UgaWYgKHR5cGVvZiAob3B0aW9ucykgPT09ICd1bmRlZmluZWQnICYmIHNlbGYuZGF0YS51cmwpIHsgLy9yZWZyZXNoaW5nXG4gICAgICAgICAgICBfdXBkYXRlUHJvcGVydGllc0Zyb21JbWFnZS5jYWxsKHNlbGYpO1xuICAgICAgICAgICAgX3RyaWdnZXJVcGRhdGUuY2FsbChzZWxmKTtcbiAgICAgICAgICAgIHJldHVybiBudWxsO1xuICAgICAgICB9XG4gICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgdXJsID0gb3B0aW9ucy51cmw7XG4gICAgICAgICAgICBwb2ludHMgPSBvcHRpb25zLnBvaW50cyB8fCBbXTtcbiAgICAgICAgICAgIHpvb20gPSB0eXBlb2Yob3B0aW9ucy56b29tKSA9PT0gJ3VuZGVmaW5lZCcgPyBudWxsIDogb3B0aW9ucy56b29tO1xuICAgICAgICB9XG5cbiAgICAgICAgc2VsZi5kYXRhLmJvdW5kID0gZmFsc2U7XG4gICAgICAgIHNlbGYuZGF0YS51cmwgPSB1cmwgfHwgc2VsZi5kYXRhLnVybDtcbiAgICAgICAgc2VsZi5kYXRhLmJvdW5kWm9vbSA9IHpvb207XG5cbiAgICAgICAgcmV0dXJuIGxvYWRJbWFnZSh1cmwsIGhhc0V4aWYpLnRoZW4oZnVuY3Rpb24gKGltZykge1xuICAgICAgICAgICAgX3JlcGxhY2VJbWFnZS5jYWxsKHNlbGYsIGltZyk7XG4gICAgICAgICAgICBpZiAoIXBvaW50cy5sZW5ndGgpIHtcbiAgICAgICAgICAgICAgICB2YXIgbmF0RGltID0gbmF0dXJhbEltYWdlRGltZW5zaW9ucyhpbWcpO1xuICAgICAgICAgICAgICAgIHZhciByZWN0ID0gc2VsZi5lbGVtZW50cy52aWV3cG9ydC5nZXRCb3VuZGluZ0NsaWVudFJlY3QoKTtcbiAgICAgICAgICAgICAgICB2YXIgYXNwZWN0UmF0aW8gPSByZWN0LndpZHRoIC8gcmVjdC5oZWlnaHQ7XG4gICAgICAgICAgICAgICAgdmFyIGltZ0FzcGVjdFJhdGlvID0gbmF0RGltLndpZHRoIC8gbmF0RGltLmhlaWdodDtcbiAgICAgICAgICAgICAgICB2YXIgd2lkdGgsIGhlaWdodDtcblxuICAgICAgICAgICAgICAgIGlmIChpbWdBc3BlY3RSYXRpbyA+IGFzcGVjdFJhdGlvKSB7XG4gICAgICAgICAgICAgICAgICAgIGhlaWdodCA9IG5hdERpbS5oZWlnaHQ7XG4gICAgICAgICAgICAgICAgICAgIHdpZHRoID0gaGVpZ2h0ICogYXNwZWN0UmF0aW87XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICB3aWR0aCA9IG5hdERpbS53aWR0aDtcbiAgICAgICAgICAgICAgICAgICAgaGVpZ2h0ID0gbmF0RGltLmhlaWdodCAvIGFzcGVjdFJhdGlvO1xuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIHZhciB4MCA9IChuYXREaW0ud2lkdGggLSB3aWR0aCkgLyAyO1xuICAgICAgICAgICAgICAgIHZhciB5MCA9IChuYXREaW0uaGVpZ2h0IC0gaGVpZ2h0KSAvIDI7XG4gICAgICAgICAgICAgICAgdmFyIHgxID0geDAgKyB3aWR0aDtcbiAgICAgICAgICAgICAgICB2YXIgeTEgPSB5MCArIGhlaWdodDtcbiAgICAgICAgICAgICAgICBzZWxmLmRhdGEucG9pbnRzID0gW3gwLCB5MCwgeDEsIHkxXTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2UgaWYgKHNlbGYub3B0aW9ucy5yZWxhdGl2ZSkge1xuICAgICAgICAgICAgICAgIHBvaW50cyA9IFtcbiAgICAgICAgICAgICAgICAgICAgcG9pbnRzWzBdICogaW1nLm5hdHVyYWxXaWR0aCAvIDEwMCxcbiAgICAgICAgICAgICAgICAgICAgcG9pbnRzWzFdICogaW1nLm5hdHVyYWxIZWlnaHQgLyAxMDAsXG4gICAgICAgICAgICAgICAgICAgIHBvaW50c1syXSAqIGltZy5uYXR1cmFsV2lkdGggLyAxMDAsXG4gICAgICAgICAgICAgICAgICAgIHBvaW50c1szXSAqIGltZy5uYXR1cmFsSGVpZ2h0IC8gMTAwXG4gICAgICAgICAgICAgICAgXTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgc2VsZi5kYXRhLnBvaW50cyA9IHBvaW50cy5tYXAoZnVuY3Rpb24gKHApIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gcGFyc2VGbG9hdChwKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgaWYgKHNlbGYub3B0aW9ucy51c2VDYW52YXMpIHtcbiAgICAgICAgICAgICAgICBfdHJhbnNmZXJJbWFnZVRvQ2FudmFzLmNhbGwoc2VsZiwgb3B0aW9ucy5vcmllbnRhdGlvbik7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBfdXBkYXRlUHJvcGVydGllc0Zyb21JbWFnZS5jYWxsKHNlbGYpO1xuICAgICAgICAgICAgX3RyaWdnZXJVcGRhdGUuY2FsbChzZWxmKTtcbiAgICAgICAgICAgIGNiICYmIGNiKCk7XG4gICAgICAgIH0pLmNhdGNoKGZ1bmN0aW9uIChlcnIpIHtcbiAgICAgICAgICAgIGNvbnNvbGUuZXJyb3IoXCJDcm9wcGllOlwiICsgZXJyKTtcbiAgICAgICAgfSk7XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gZml4KHYsIGRlY2ltYWxQb2ludHMpIHtcbiAgICAgICAgcmV0dXJuIHBhcnNlRmxvYXQodikudG9GaXhlZChkZWNpbWFsUG9pbnRzIHx8IDApO1xuICAgIH1cblxuICAgIGZ1bmN0aW9uIF9nZXQoKSB7XG4gICAgICAgIHZhciBzZWxmID0gdGhpcyxcbiAgICAgICAgICAgIGltZ0RhdGEgPSBzZWxmLmVsZW1lbnRzLnByZXZpZXcuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCksXG4gICAgICAgICAgICB2cERhdGEgPSBzZWxmLmVsZW1lbnRzLnZpZXdwb3J0LmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpLFxuICAgICAgICAgICAgeDEgPSB2cERhdGEubGVmdCAtIGltZ0RhdGEubGVmdCxcbiAgICAgICAgICAgIHkxID0gdnBEYXRhLnRvcCAtIGltZ0RhdGEudG9wLFxuICAgICAgICAgICAgd2lkdGhEaWZmID0gKHZwRGF0YS53aWR0aCAtIHNlbGYuZWxlbWVudHMudmlld3BvcnQub2Zmc2V0V2lkdGgpIC8gMiwgLy9ib3JkZXJcbiAgICAgICAgICAgIGhlaWdodERpZmYgPSAodnBEYXRhLmhlaWdodCAtIHNlbGYuZWxlbWVudHMudmlld3BvcnQub2Zmc2V0SGVpZ2h0KSAvIDIsXG4gICAgICAgICAgICB4MiA9IHgxICsgc2VsZi5lbGVtZW50cy52aWV3cG9ydC5vZmZzZXRXaWR0aCArIHdpZHRoRGlmZixcbiAgICAgICAgICAgIHkyID0geTEgKyBzZWxmLmVsZW1lbnRzLnZpZXdwb3J0Lm9mZnNldEhlaWdodCArIGhlaWdodERpZmYsXG4gICAgICAgICAgICBzY2FsZSA9IHNlbGYuX2N1cnJlbnRab29tO1xuXG4gICAgICAgIGlmIChzY2FsZSA9PT0gSW5maW5pdHkgfHwgaXNOYU4oc2NhbGUpKSB7XG4gICAgICAgICAgICBzY2FsZSA9IDE7XG4gICAgICAgIH1cblxuICAgICAgICB2YXIgbWF4ID0gc2VsZi5vcHRpb25zLmVuZm9yY2VCb3VuZGFyeSA/IDAgOiBOdW1iZXIuTkVHQVRJVkVfSU5GSU5JVFk7XG4gICAgICAgIHgxID0gTWF0aC5tYXgobWF4LCB4MSAvIHNjYWxlKTtcbiAgICAgICAgeTEgPSBNYXRoLm1heChtYXgsIHkxIC8gc2NhbGUpO1xuICAgICAgICB4MiA9IE1hdGgubWF4KG1heCwgeDIgLyBzY2FsZSk7XG4gICAgICAgIHkyID0gTWF0aC5tYXgobWF4LCB5MiAvIHNjYWxlKTtcblxuICAgICAgICByZXR1cm4ge1xuICAgICAgICAgICAgcG9pbnRzOiBbZml4KHgxKSwgZml4KHkxKSwgZml4KHgyKSwgZml4KHkyKV0sXG4gICAgICAgICAgICB6b29tOiBzY2FsZSxcbiAgICAgICAgICAgIG9yaWVudGF0aW9uOiBzZWxmLmRhdGEub3JpZW50YXRpb25cbiAgICAgICAgfTtcbiAgICB9XG5cbiAgICB2YXIgUkVTVUxUX0RFRkFVTFRTID0ge1xuICAgICAgICAgICAgdHlwZTogJ2NhbnZhcycsXG4gICAgICAgICAgICBmb3JtYXQ6ICdwbmcnLFxuICAgICAgICAgICAgcXVhbGl0eTogMVxuICAgICAgICB9LFxuICAgICAgICBSRVNVTFRfRk9STUFUUyA9IFsnanBlZycsICd3ZWJwJywgJ3BuZyddO1xuXG4gICAgZnVuY3Rpb24gX3Jlc3VsdChvcHRpb25zKSB7XG4gICAgICAgIHZhciBzZWxmID0gdGhpcyxcbiAgICAgICAgICAgIGRhdGEgPSBfZ2V0LmNhbGwoc2VsZiksXG4gICAgICAgICAgICBvcHRzID0gZGVlcEV4dGVuZChjbG9uZShSRVNVTFRfREVGQVVMVFMpLCBjbG9uZShvcHRpb25zKSksXG4gICAgICAgICAgICByZXN1bHRUeXBlID0gKHR5cGVvZiAob3B0aW9ucykgPT09ICdzdHJpbmcnID8gb3B0aW9ucyA6IChvcHRzLnR5cGUgfHwgJ2Jhc2U2NCcpKSxcbiAgICAgICAgICAgIHNpemUgPSBvcHRzLnNpemUgfHwgJ3ZpZXdwb3J0JyxcbiAgICAgICAgICAgIGZvcm1hdCA9IG9wdHMuZm9ybWF0LFxuICAgICAgICAgICAgcXVhbGl0eSA9IG9wdHMucXVhbGl0eSxcbiAgICAgICAgICAgIGJhY2tncm91bmRDb2xvciA9IG9wdHMuYmFja2dyb3VuZENvbG9yLFxuICAgICAgICAgICAgY2lyY2xlID0gdHlwZW9mIG9wdHMuY2lyY2xlID09PSAnYm9vbGVhbicgPyBvcHRzLmNpcmNsZSA6IChzZWxmLm9wdGlvbnMudmlld3BvcnQudHlwZSA9PT0gJ2NpcmNsZScpLFxuICAgICAgICAgICAgdnBSZWN0ID0gc2VsZi5lbGVtZW50cy52aWV3cG9ydC5nZXRCb3VuZGluZ0NsaWVudFJlY3QoKSxcbiAgICAgICAgICAgIHJhdGlvID0gdnBSZWN0LndpZHRoIC8gdnBSZWN0LmhlaWdodCxcbiAgICAgICAgICAgIHByb207XG5cbiAgICAgICAgaWYgKHNpemUgPT09ICd2aWV3cG9ydCcpIHtcbiAgICAgICAgICAgIGRhdGEub3V0cHV0V2lkdGggPSB2cFJlY3Qud2lkdGg7XG4gICAgICAgICAgICBkYXRhLm91dHB1dEhlaWdodCA9IHZwUmVjdC5oZWlnaHQ7XG4gICAgICAgIH0gZWxzZSBpZiAodHlwZW9mIHNpemUgPT09ICdvYmplY3QnKSB7XG4gICAgICAgICAgICBpZiAoc2l6ZS53aWR0aCAmJiBzaXplLmhlaWdodCkge1xuICAgICAgICAgICAgICAgIGRhdGEub3V0cHV0V2lkdGggPSBzaXplLndpZHRoO1xuICAgICAgICAgICAgICAgIGRhdGEub3V0cHV0SGVpZ2h0ID0gc2l6ZS5oZWlnaHQ7XG4gICAgICAgICAgICB9IGVsc2UgaWYgKHNpemUud2lkdGgpIHtcbiAgICAgICAgICAgICAgICBkYXRhLm91dHB1dFdpZHRoID0gc2l6ZS53aWR0aDtcbiAgICAgICAgICAgICAgICBkYXRhLm91dHB1dEhlaWdodCA9IHNpemUud2lkdGggLyByYXRpbztcbiAgICAgICAgICAgIH0gZWxzZSBpZiAoc2l6ZS5oZWlnaHQpIHtcbiAgICAgICAgICAgICAgICBkYXRhLm91dHB1dFdpZHRoID0gc2l6ZS5oZWlnaHQgKiByYXRpbztcbiAgICAgICAgICAgICAgICBkYXRhLm91dHB1dEhlaWdodCA9IHNpemUuaGVpZ2h0O1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG5cbiAgICAgICAgaWYgKFJFU1VMVF9GT1JNQVRTLmluZGV4T2YoZm9ybWF0KSA+IC0xKSB7XG4gICAgICAgICAgICBkYXRhLmZvcm1hdCA9ICdpbWFnZS8nICsgZm9ybWF0O1xuICAgICAgICAgICAgZGF0YS5xdWFsaXR5ID0gcXVhbGl0eTtcbiAgICAgICAgfVxuXG4gICAgICAgIGRhdGEuY2lyY2xlID0gY2lyY2xlO1xuICAgICAgICBkYXRhLnVybCA9IHNlbGYuZGF0YS51cmw7XG4gICAgICAgIGRhdGEuYmFja2dyb3VuZENvbG9yID0gYmFja2dyb3VuZENvbG9yO1xuXG4gICAgICAgIHByb20gPSBuZXcgUHJvbWlzZShmdW5jdGlvbiAocmVzb2x2ZSwgcmVqZWN0KSB7XG4gICAgICAgICAgICBzd2l0Y2gocmVzdWx0VHlwZS50b0xvd2VyQ2FzZSgpKVxuICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgIGNhc2UgJ3Jhd2NhbnZhcyc6XG4gICAgICAgICAgICAgICAgICAgIHJlc29sdmUoX2dldENhbnZhcy5jYWxsKHNlbGYsIGRhdGEpKTtcbiAgICAgICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgICAgY2FzZSAnY2FudmFzJzpcbiAgICAgICAgICAgICAgICBjYXNlICdiYXNlNjQnOlxuICAgICAgICAgICAgICAgICAgICByZXNvbHZlKF9nZXRCYXNlNjRSZXN1bHQuY2FsbChzZWxmLCBkYXRhKSk7XG4gICAgICAgICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgICAgIGNhc2UgJ2Jsb2InOlxuICAgICAgICAgICAgICAgICAgICBfZ2V0QmxvYlJlc3VsdC5jYWxsKHNlbGYsIGRhdGEpLnRoZW4ocmVzb2x2ZSk7XG4gICAgICAgICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgICAgIGRlZmF1bHQ6XG4gICAgICAgICAgICAgICAgICAgIHJlc29sdmUoX2dldEh0bWxSZXN1bHQuY2FsbChzZWxmLCBkYXRhKSk7XG4gICAgICAgICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICAgICAgcmV0dXJuIHByb207XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gX3JlZnJlc2goKSB7XG4gICAgICAgIF91cGRhdGVQcm9wZXJ0aWVzRnJvbUltYWdlLmNhbGwodGhpcyk7XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gX3JvdGF0ZShkZWcpIHtcbiAgICAgICAgaWYgKCF0aGlzLm9wdGlvbnMudXNlQ2FudmFzIHx8ICF0aGlzLm9wdGlvbnMuZW5hYmxlT3JpZW50YXRpb24pIHtcbiAgICAgICAgICAgIHRocm93ICdDcm9wcGllOiBDYW5ub3Qgcm90YXRlIHdpdGhvdXQgZW5hYmxlT3JpZW50YXRpb24gJiYgRVhJRi5qcyBpbmNsdWRlZCc7XG4gICAgICAgIH1cblxuICAgICAgICB2YXIgc2VsZiA9IHRoaXMsXG4gICAgICAgICAgICBjYW52YXMgPSBzZWxmLmVsZW1lbnRzLmNhbnZhcyxcbiAgICAgICAgICAgIG9ybnQ7XG5cbiAgICAgICAgc2VsZi5kYXRhLm9yaWVudGF0aW9uID0gZ2V0RXhpZk9mZnNldChzZWxmLmRhdGEub3JpZW50YXRpb24sIGRlZyk7XG4gICAgICAgIGRyYXdDYW52YXMoY2FudmFzLCBzZWxmLmVsZW1lbnRzLmltZywgc2VsZi5kYXRhLm9yaWVudGF0aW9uKTtcbiAgICAgICAgX3VwZGF0ZVpvb21MaW1pdHMuY2FsbChzZWxmKTtcbiAgICAgICAgX29uWm9vbS5jYWxsKHNlbGYpO1xuICAgICAgICBjb3B5ID0gbnVsbDtcbiAgICB9XG5cbiAgICBmdW5jdGlvbiBfZGVzdHJveSgpIHtcbiAgICAgICAgdmFyIHNlbGYgPSB0aGlzO1xuICAgICAgICBzZWxmLmVsZW1lbnQucmVtb3ZlQ2hpbGQoc2VsZi5lbGVtZW50cy5ib3VuZGFyeSk7XG4gICAgICAgIHJlbW92ZUNsYXNzKHNlbGYuZWxlbWVudCwgJ2Nyb3BwaWUtY29udGFpbmVyJyk7XG4gICAgICAgIGlmIChzZWxmLm9wdGlvbnMuZW5hYmxlWm9vbSkge1xuICAgICAgICAgICAgc2VsZi5lbGVtZW50LnJlbW92ZUNoaWxkKHNlbGYuZWxlbWVudHMuem9vbWVyV3JhcCk7XG4gICAgICAgIH1cbiAgICAgICAgZGVsZXRlIHNlbGYuZWxlbWVudHM7XG4gICAgfVxuXG4gICAgaWYgKHdpbmRvdy5qUXVlcnkpIHtcbiAgICAgICAgdmFyICQgPSB3aW5kb3cualF1ZXJ5O1xuICAgICAgICAkLmZuLmNyb3BwaWUgPSBmdW5jdGlvbiAob3B0cykge1xuICAgICAgICAgICAgdmFyIG90ID0gdHlwZW9mIG9wdHM7XG5cbiAgICAgICAgICAgIGlmIChvdCA9PT0gJ3N0cmluZycpIHtcbiAgICAgICAgICAgICAgICB2YXIgYXJncyA9IEFycmF5LnByb3RvdHlwZS5zbGljZS5jYWxsKGFyZ3VtZW50cywgMSk7XG4gICAgICAgICAgICAgICAgdmFyIHNpbmdsZUluc3QgPSAkKHRoaXMpLmRhdGEoJ2Nyb3BwaWUnKTtcblxuICAgICAgICAgICAgICAgIGlmIChvcHRzID09PSAnZ2V0Jykge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gc2luZ2xlSW5zdC5nZXQoKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgZWxzZSBpZiAob3B0cyA9PT0gJ3Jlc3VsdCcpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHNpbmdsZUluc3QucmVzdWx0LmFwcGx5KHNpbmdsZUluc3QsIGFyZ3MpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBlbHNlIGlmIChvcHRzID09PSAnYmluZCcpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHNpbmdsZUluc3QuYmluZC5hcHBseShzaW5nbGVJbnN0LCBhcmdzKTtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICByZXR1cm4gdGhpcy5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGkgPSAkKHRoaXMpLmRhdGEoJ2Nyb3BwaWUnKTtcbiAgICAgICAgICAgICAgICAgICAgaWYgKCFpKSByZXR1cm47XG5cbiAgICAgICAgICAgICAgICAgICAgdmFyIG1ldGhvZCA9IGlbb3B0c107XG4gICAgICAgICAgICAgICAgICAgIGlmICgkLmlzRnVuY3Rpb24obWV0aG9kKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgbWV0aG9kLmFwcGx5KGksIGFyZ3MpO1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKG9wdHMgPT09ICdkZXN0cm95Jykge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICQodGhpcykucmVtb3ZlRGF0YSgnY3JvcHBpZScpO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgdGhyb3cgJ0Nyb3BwaWUgJyArIG9wdHMgKyAnIG1ldGhvZCBub3QgZm91bmQnO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gdGhpcy5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGkgPSBuZXcgQ3JvcHBpZSh0aGlzLCBvcHRzKTtcbiAgICAgICAgICAgICAgICAgICAgaS4kID0gJDtcbiAgICAgICAgICAgICAgICAgICAgJCh0aGlzKS5kYXRhKCdjcm9wcGllJywgaSk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH07XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gQ3JvcHBpZShlbGVtZW50LCBvcHRzKSB7XG4gICAgICAgIGlmIChlbGVtZW50LmNsYXNzTmFtZS5pbmRleE9mKCdjcm9wcGllLWNvbnRhaW5lcicpID4gLTEpIHtcbiAgICAgICAgICAgIHRocm93IG5ldyBFcnJvcihcIkNyb3BwaWU6IENhbid0IGluaXRpYWxpemUgY3JvcHBpZSBtb3JlIHRoYW4gb25jZVwiKTtcbiAgICAgICAgfVxuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLm9wdGlvbnMgPSBkZWVwRXh0ZW5kKGNsb25lKENyb3BwaWUuZGVmYXVsdHMpLCBvcHRzKTtcblxuICAgICAgICBpZiAodGhpcy5lbGVtZW50LnRhZ05hbWUudG9Mb3dlckNhc2UoKSA9PT0gJ2ltZycpIHtcbiAgICAgICAgICAgIHZhciBvcmlnSW1hZ2UgPSB0aGlzLmVsZW1lbnQ7XG4gICAgICAgICAgICBhZGRDbGFzcyhvcmlnSW1hZ2UsICdjci1vcmlnaW5hbC1pbWFnZScpO1xuICAgICAgICAgICAgc2V0QXR0cmlidXRlcyhvcmlnSW1hZ2UsIHsnYXJpYS1oaWRkZW4nIDogJ3RydWUnLCAnYWx0JyA6ICcnIH0pO1xuICAgICAgICAgICAgdmFyIHJlcGxhY2VtZW50RGl2ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQucGFyZW50Tm9kZS5hcHBlbmRDaGlsZChyZXBsYWNlbWVudERpdik7XG4gICAgICAgICAgICByZXBsYWNlbWVudERpdi5hcHBlbmRDaGlsZChvcmlnSW1hZ2UpO1xuICAgICAgICAgICAgdGhpcy5lbGVtZW50ID0gcmVwbGFjZW1lbnREaXY7XG4gICAgICAgICAgICB0aGlzLm9wdGlvbnMudXJsID0gdGhpcy5vcHRpb25zLnVybCB8fCBvcmlnSW1hZ2Uuc3JjO1xuICAgICAgICB9XG5cbiAgICAgICAgX2NyZWF0ZS5jYWxsKHRoaXMpO1xuICAgICAgICBpZiAodGhpcy5vcHRpb25zLnVybCkge1xuICAgICAgICAgICAgdmFyIGJpbmRPcHRzID0ge1xuICAgICAgICAgICAgICAgIHVybDogdGhpcy5vcHRpb25zLnVybCxcbiAgICAgICAgICAgICAgICBwb2ludHM6IHRoaXMub3B0aW9ucy5wb2ludHNcbiAgICAgICAgICAgIH07XG4gICAgICAgICAgICBkZWxldGUgdGhpcy5vcHRpb25zWyd1cmwnXTtcbiAgICAgICAgICAgIGRlbGV0ZSB0aGlzLm9wdGlvbnNbJ3BvaW50cyddO1xuICAgICAgICAgICAgX2JpbmQuY2FsbCh0aGlzLCBiaW5kT3B0cyk7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICBDcm9wcGllLmRlZmF1bHRzID0ge1xuICAgICAgICB2aWV3cG9ydDoge1xuICAgICAgICAgICAgd2lkdGg6IDEwMCxcbiAgICAgICAgICAgIGhlaWdodDogMTAwLFxuICAgICAgICAgICAgdHlwZTogJ3NxdWFyZSdcbiAgICAgICAgfSxcbiAgICAgICAgYm91bmRhcnk6IHsgfSxcbiAgICAgICAgb3JpZW50YXRpb25Db250cm9sczoge1xuICAgICAgICAgICAgZW5hYmxlZDogdHJ1ZSxcbiAgICAgICAgICAgIGxlZnRDbGFzczogJycsXG4gICAgICAgICAgICByaWdodENsYXNzOiAnJ1xuICAgICAgICB9LFxuICAgICAgICByZXNpemVDb250cm9sczoge1xuICAgICAgICAgICAgd2lkdGg6IHRydWUsXG4gICAgICAgICAgICBoZWlnaHQ6IHRydWVcbiAgICAgICAgfSxcbiAgICAgICAgY3VzdG9tQ2xhc3M6ICcnLFxuICAgICAgICBzaG93Wm9vbWVyOiB0cnVlLFxuICAgICAgICBlbmFibGVab29tOiB0cnVlLFxuICAgICAgICBlbmFibGVSZXNpemU6IGZhbHNlLFxuICAgICAgICBtb3VzZVdoZWVsWm9vbTogdHJ1ZSxcbiAgICAgICAgZW5hYmxlRXhpZjogZmFsc2UsXG4gICAgICAgIGVuZm9yY2VCb3VuZGFyeTogdHJ1ZSxcbiAgICAgICAgZW5hYmxlT3JpZW50YXRpb246IGZhbHNlLFxuICAgICAgICBlbmFibGVLZXlNb3ZlbWVudDogdHJ1ZSxcbiAgICAgICAgdXBkYXRlOiBmdW5jdGlvbiAoKSB7IH1cbiAgICB9O1xuXG4gICAgQ3JvcHBpZS5nbG9iYWxzID0ge1xuICAgICAgICB0cmFuc2xhdGU6ICd0cmFuc2xhdGUzZCdcbiAgICB9O1xuXG4gICAgZGVlcEV4dGVuZChDcm9wcGllLnByb3RvdHlwZSwge1xuICAgICAgICBiaW5kOiBmdW5jdGlvbiAob3B0aW9ucywgY2IpIHtcbiAgICAgICAgICAgIHJldHVybiBfYmluZC5jYWxsKHRoaXMsIG9wdGlvbnMsIGNiKTtcbiAgICAgICAgfSxcbiAgICAgICAgZ2V0OiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgZGF0YSA9IF9nZXQuY2FsbCh0aGlzKTtcbiAgICAgICAgICAgIHZhciBwb2ludHMgPSBkYXRhLnBvaW50cztcbiAgICAgICAgICAgIGlmICh0aGlzLm9wdGlvbnMucmVsYXRpdmUpIHtcbiAgICAgICAgICAgICAgICBwb2ludHNbMF0gLz0gdGhpcy5lbGVtZW50cy5pbWcubmF0dXJhbFdpZHRoIC8gMTAwO1xuICAgICAgICAgICAgICAgIHBvaW50c1sxXSAvPSB0aGlzLmVsZW1lbnRzLmltZy5uYXR1cmFsSGVpZ2h0IC8gMTAwO1xuICAgICAgICAgICAgICAgIHBvaW50c1syXSAvPSB0aGlzLmVsZW1lbnRzLmltZy5uYXR1cmFsV2lkdGggLyAxMDA7XG4gICAgICAgICAgICAgICAgcG9pbnRzWzNdIC89IHRoaXMuZWxlbWVudHMuaW1nLm5hdHVyYWxIZWlnaHQgLyAxMDA7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICByZXR1cm4gZGF0YTtcbiAgICAgICAgfSxcbiAgICAgICAgcmVzdWx0OiBmdW5jdGlvbiAodHlwZSkge1xuICAgICAgICAgICAgcmV0dXJuIF9yZXN1bHQuY2FsbCh0aGlzLCB0eXBlKTtcbiAgICAgICAgfSxcbiAgICAgICAgcmVmcmVzaDogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgcmV0dXJuIF9yZWZyZXNoLmNhbGwodGhpcyk7XG4gICAgICAgIH0sXG4gICAgICAgIHNldFpvb206IGZ1bmN0aW9uICh2KSB7XG4gICAgICAgICAgICBfc2V0Wm9vbWVyVmFsLmNhbGwodGhpcywgdik7XG4gICAgICAgICAgICBkaXNwYXRjaENoYW5nZSh0aGlzLmVsZW1lbnRzLnpvb21lcik7XG4gICAgICAgIH0sXG4gICAgICAgIHJvdGF0ZTogZnVuY3Rpb24gKGRlZykge1xuICAgICAgICAgICAgX3JvdGF0ZS5jYWxsKHRoaXMsIGRlZyk7XG4gICAgICAgIH0sXG4gICAgICAgIGRlc3Ryb3k6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHJldHVybiBfZGVzdHJveS5jYWxsKHRoaXMpO1xuICAgICAgICB9XG4gICAgfSk7XG5cbiAgICBleHBvcnRzLkNyb3BwaWUgPSB3aW5kb3cuQ3JvcHBpZSA9IENyb3BwaWU7XG59KSk7Il19