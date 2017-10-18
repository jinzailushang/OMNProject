/**
 * Created by MyPC on 2016/6/22.
 */

var intv;
function smsDownTime(o, t) {
  window.clearInterval(intv);
  if (t < 1) {
    o.text('获取验证码').prop('disabled', false);
  } else {
    o.text(t + '秒后可重发');
    intv = window.setInterval(function () {
      smsDownTime(o, --t);
    }, 1000);
  }
}

//验证登录表单
$("#form_login")
// .prop('action', window.api_url)
  .validate({
    errorPlacement: function (error, element) {
      var inputTip = element.parent().parent().find(".input-tip");
      error.appendTo(inputTip);
    },
    rules: {
      user_name: {
        required: true
      },
      password: {
        required: true
      }
    },
    messages: {
      user_name: {
        required: "*账号不能为空"
      },
      password: {
        required: "*密码不能为空"
      }
    },
    submitHandler:function (form) {
      var form = $(form);
      $.ajax({
        url: form.find('[name=SiteUrl]').val(),
        type: 'post',
        data: {
          form_submit:form.find('[name=form_submit]').val(),
          nchash: form.find('[name=nchash]').val(),
          user_name: form.find('[name=user_name]').val(),
          password: form.find('[name=password]').val(),
          captcha: form.find('[name=captcha]').val()
        },
        dataType: 'json',
        success: function(res) {
          if (!res.status) {
            var newCaptcha = false;
            if (res.msg=='验证码不正确,请重新输入') {
              if ($('#login-verfiy-code-field #codeimage').length < 1) {
                newCaptcha = true;
                var html = $('<label id="password-label" for="TPL_password_1"> \
                  <i class="ico ico-valid" title="验证码"></i> \
                </label> \
                <input name="captcha" type="text" class="login-text" id="captcha" placeholder="输入验证" pattern="[A-z0-9]{4}" title="输入验证" autocomplete="off" maxlength="4"> \
                <div class="code"> \
                  <div class="arrow"></div> \
                  <div class="code-img">\
                    <a href="javaScript:void(0);" onclick=document.getElementById("codeimage").src="index.php?act=code&amp;op=makecode&amp;admin=1&amp;nchash=&amp;t="+Math.random(); class="change" title="刷新">\
                      <img src="index.php?act=code&amp;op=makecode&amp;admin=1&amp;nchash=" name="codeimage" id="codeimage" border="0" width="81" height="36">\
                    </a>\
                  </div>\
                </div>');
                $('#login-verfiy-code-field').append(html);
                setTimeout(function(){
                  form.find('[name=captcha]').focus();
                },10);
              }
            }
            if (!newCaptcha) {
              if ($('#login-verfiy-code-field #codeimage').length > 0) {
                form.find('.code-img > a').trigger('click');
                form.find('[name=captcha]').val('');
              }
              $('#login-msg').text(res.msg);
              setTimeout(function () {
                $('#login-msg').text('');
              }, 3000);
            }
          } else {
            location.href="index.php?act=welcome&op=index";
          }
        }
      });
      return false;
    }
  });

// 验证手机注册表单
$("#ph_form_reg")
  .validate({
    errorPlacement: function (error, element) {
      var inputTip = element.parent().parent().find(".input-tip");
      error.appendTo(inputTip);
    },
    rules: {
      ph_phone: {
        required: true
      },
      ph_captcha: {
        required: true
      },
      ph_password: {
        required: true
      },
      ph_re_pwd: {
        required: true,
        equalTo: "#ph_password"
      },
      agree: "required"
    },
    messages: {
      ph_phone: {
        required: "*手机号码不能为空"
      },
      ph_captcha: {
        required: "*验证码不能为空"
      },
      ph_password: {
        required: "*密码不能为空"
      },
      ph_re_pwd: {
        required: "*确认密码不能为空",
        equalTo: "两次输入密码不一致"
      },
      agree: "*请同意《威廉服务协议》"
    },
     submitHandler:function (form) {
     //        手机注册成功页面

     //$("#ph-resiger").hide();
     //$("#register-success").show();
       $('#loading-mask').show();
       var phone = $("#ph_name").val(),
         password = $('#ph_password').val(),
         ph_re_pwd = $('#ph_re_pwd').val(),
         ph_captcha = $('#ph_captcha').val();
       $.ajax({
         url: '/site/index.php?act=login&op=register',
         type: 'post',
         data: {form_submit: $('[name=form_submit]').val(),nchash: $('[name=nchash]').val(),ph_phone: phone, ph_password: password, ph_re_pwd: ph_re_pwd, ph_captcha: ph_captcha},
         dataType: 'json',
         success: function (res) {
           $('#loading-mask').hide();
           if (res.status == 1) {
             layer.alert(res.msg, {icon:1}, function (index) {
               location.href = "index.php?act=login&op=login";
             });
           } else {
             layer.alert(res.msg, {icon: 2}, function (index) {
               layer.close(index);
             });
           }
         }
       });
       return false;
     }
  });
// 验证邮箱注册表单
$("#pb_form_reg")
  .validate({
    errorPlacement: function (error, element) {
      var inputTip = element.parent().parent().find(".input-tip");
      error.appendTo(inputTip);
    },
    rules: {
      u_name: {
        required: true
      },
      pb_password: {
        required: true
      },
      pb_re_pwd: {
        required: true,
        equalTo: "#pb_password"
      },
      TPL_password_1: {
        required: true
      },
      pb_agree: "required"
    },
    messages: {
      u_name: {
        required: "*电子邮箱不能为空"
      },
      pb_password: {
        required: "*密码不能为空"
      },
      pb_re_pwd: {
        required: "*确认密码不能为空 ",
        equalTo: "两次输入密码不一致"
      },
      TPL_password_1: {
        required: "*验证码不能为空"
      },
      pb_agree: "*请同意《威廉服务协议》"
    },
     //        验证邮箱页面
     submitHandler:function (form) {
       $('#loading-mask').show();
       var u_name = $("#u_name").val(),
           pb_password = $('#pb_password').val(),
           pb_re_pwd = $('#pb_re_pwd').val(),
           captcha = $(form).find('[name=captcha]').val();
       $.ajax({
         url: '/site/index.php?act=login&op=register',
         type: 'post',
         data: {form_submit: $('[name=form_submit]').val(),nchash: $('[name=nchash]').val(),u_name: u_name, pb_password: pb_password, pb_re_pwd: pb_re_pwd, captcha: captcha},
         dataType: 'json',
         success: function (res) {
           $('#loading-mask').hide();
           if (res.status == 1) {
             layer.alert(res.msg, {icon:1}, function (index) {
               location.href = "index.php?act=login&op=login";
             });
           } else {
             layer.alert(res.msg, {icon: 2}, function (index) {
               layer.close(index);
               $(form).find('.code-img>a').trigger('click');
               if (res.msg=='验证码不正确') {
                 $(form).find('[name=captcha]').focus();
               }
             });
           }
         }
       });
       return false;
     }
  });

// 邮箱找回
$("#form-resetpassword-email")
  .validate({
    errorPlacement: function (error, element) {
      var inputTip = element.parent().parent().find(".input-tip");
      error.appendTo(inputTip);
    },
    rules: {
      ph_name_email: {
        required: true
      }
    },
    messages: {
      ph_name_email: {
        required: "*电子邮箱不能为空"
      }
    },
    submitHandler: function (form) {
      //            激活邮箱
      $('#loading-mask').show();
      var value = $("#ph_name_email").val();
      $.ajax({
        url: '/site/index.php?act=login&op=resetPasswordByEmail',
        type: 'post',
        data: {email: value},
        dataType: 'json',
        success: function (res) {
          $('#loading-mask').hide();
          if (res.status == 1) {
            $(".find-back").hide();
            $(".pb-password-reset-succeed").show();
            //先存值，再获取
            $(".pb-reg-address").text(value);
          } else {
            layer.alert(res.msg, {icon: 2}, function (index) {
              layer.close(index);
            });
          }
        }
      });
      return false;
    }
  });
// 密码重置
$("#form-resetpassword-reset")
  .validate({
    errorPlacement: function (error, element) {
      var inputTip = element.parent().parent().find(".input-tip");
      error.appendTo(inputTip);
    },
    // rules: {
    //   cz_password: {
    //     required: true
    //   },
    //   re_pwd: {
    //     required: true,
    //     equalTo: "#cz_password"
    //   }
    // },
    // messages: {
    //   cz_password: {
    //     required: "*密码不能为空"
    //   },
    //   re_pwd: {
    //     required: "*确认密码不能为空",
    //     equalTo: "两次输入密码不一致"
    //   }
    // },
    //            重置成功
    submitHandler: function (form) {
      if ($('#cz-password').val() != $('#re_pwd').val()) {
        layer.alert('两次密码不一致', {icon: 2}, function (index) {
          layer.close(index);
          console.log(555)
        });
        return false;
      }
      $('#loading-mask').show();
      var value = $("#ph_name_phone").val();
      $.ajax({
        url: '/site/index.php?act=login&op=resetPasswordFinnal',
        type: 'post',
        data: {password: $('#cz-password').val()},
        dataType: 'json',
        success: function (res) {
          $('#loading-mask').hide();
          if (res.status == 1) {
            $(".password-reset").hide();
            $(".password-reset-succeed").show();
          } else {
            layer.alert(res.msg, {icon: 2}, function (index) {
              layer.close(index);
            });
          }
        }
      });
    }
  });
//手机找回
$("#form-resetpassword-phone")
  .validate({
    errorPlacement: function (error, element) {
      var inputTip = element.parent().parent().find(".input-tip");
      error.appendTo(inputTip);
    },
    // rules: {
    //   ph_name_phone: {
    //     required: true
    //   },
    //   captcha: {
    //     required: true
    //   }
    // },
    // messages: {
    //   ph_name_phone: {
    //     required: "*手机号码不能为空"
    //   },
    //   captcha: {
    //     required: "*验证码不能为空"
    //   }
    // },
    submitHandler: function (form) {
      $('#loading-mask').show();
      var value = $("#ph_name_phone").val();
      $.ajax({
        url: '/site/index.php?act=login&op=resetPasswordByPhone',
        type: 'post',
        data: {phone: value, code: $('#captcha').val()},
        dataType: 'json',
        success: function (res) {
          $('#loading-mask').hide();
          if (res.status == 1) {
            $(".find-back").hide();
            $(".password-reset").show();
          } else {
            layer.alert(res.msg, {icon: 2}, function (index) {
              layer.close(index);
            });
          }
        }
      });
      return false;
    }
  });

