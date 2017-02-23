<?php
session_start();
include("../function.php");
sessionCheck();//セッションの入れ替え

$profileimg = $_SESSION["profileimg"];

$pagetype =  $_SERVER["REQUEST_URI"];
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>マイロカボ</title>
    <!-- BootstrapのCSS読み込み -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/jumbotron.css" rel="stylesheet">
    <link href="../css/custom.css" rel="stylesheet">
    <link href="../css/menu.css" rel="stylesheet">
    <link href="../css/cropper.min.css" rel="stylesheet">
    <link href="../css/cropper.main.css" rel="stylesheet">
    <!-- jQuery読み込み -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- BootstrapのJS読み込み -->
    <script src="../js/bootstrap.js"></script>
    <style>
        .jumbotron {
            background: white;
        }

        .mypage-header {
            background: #444444;
        }

        .mypage-header a {
            color: gray;
            padding-top: 10px;
            padding-bottom: 6px;
            display: inline-block;
            margin-right: 20px;
        }

        .mypage-header .mypage-active {
            color: white;
            border-bottom: 4px solid #7ebb45;
        }
        /*        リンク装飾*/

        a:link {
            text-decoration: none;
        }

        a:visited {
            text-decoration: none;
        }

        a:hover {
            text-decoration: none;
        }

        a:active {
            text-decoration: none;
        }

        .avatar-view {
            border-radius: 220px;
            ;
        }

        .test {
            box-shadow: 0 0 5px rgba(0, 0, 0, .15);
        }
    </style>
  </head>
<body>
    <!-- container -->
    <div id="index-main">
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="../index.php">マイロカボ</a>
                    <!--小さいデバイス時のメニューボタン-->
                    <?php include( '../External/menu-btn-small.php'); ?>
                </div>
                <!--PCデバイス時のメニューボタン-->
                <?php include( '../External/menu-btn-large.php'); ?>

            </div>
        </nav>
        <!--    メニュー-->
        <div class="mypage-header">
            <div class="container">
                <a href="./profile-edit.php">プロフィール編集</a>
                <a href="#" class="mypage-active">プロフィール画像変更</a>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                    <div class="page-header">
                        <h3>プロフィール画像変更</h3>
                    </div>
                    <div id="crop-avatar">

                    <!-- Current avatar -->
                        <div class="avatar-view" title="Change the avatar">
                          <img src="../img/profileimg/<?=$profileimg?>" alt="Avatar">
                        </div>
                    <!-- Cropping modal -->
                        <div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
                          <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                              <form class="avatar-form" action="crop.php" enctype="multipart/form-data" method="post">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                  <h4 class="modal-title" id="avatar-modal-label">プロフィール画像変更</h4>
                                </div>
                                <div class="modal-body">
                                  <div class="avatar-body">

                                    <!-- Upload image and data -->
                                    <div class="avatar-upload">
                                      <input type="hidden" class="avatar-src" name="avatar_src">
                                      <input type="hidden" class="avatar-data" name="avatar_data">
                                      <label for="avatarInput">画像を選択</label>
                                      <input type="file" class="avatar-input" id="avatarInput" name="avatar_file">
                                    </div>

                                    <!-- Crop and preview -->
                                    <div class="row">
                                      <div class="col-md-9">
                                        <div class="avatar-wrapper"></div>
                                      </div>
                                      <div class="col-md-3">
                                        <div class="avatar-preview preview-md"></div>
                                      </div>
                                    </div>
                                    <div class="row avatar-btns">
                                        <div class="col-md-9">
                                            <div class="btn-group">
                                              <button type="button" class="btn btn-primary" data-method="rotate" data-option="-90" title="Rotate -90 degrees">左回転</button>
                                              <button type="button" class="btn btn-primary" data-method="rotate" data-option="-15">１５度</button>
                                              <button type="button" class="btn btn-primary" data-method="rotate" data-option="-30">３０度</button>
                                              <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45">４５度</button>
                                            </div>
                                            <div class="btn-group">
                                              <button type="button" class="btn btn-primary" data-method="rotate" data-option="90" title="Rotate 90 degrees">右回転</button>
                                              <button type="button" class="btn btn-primary" data-method="rotate" data-option="15">１５度</button>
                                              <button type="button" class="btn btn-primary" data-method="rotate" data-option="30">３０度</button>
                                              <button type="button" class="btn btn-primary" data-method="rotate" data-option="45">４５度</button>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-primary btn-block avatar-save">変更する</button>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                        </div><!-- /.modal -->
                    <!-- Loading state -->
                        <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /container -->

<!--スマホサイズのメニュー画面展開画面-->
<?php include( '../External/menu-content-small.php'); ?>
<!--メニュー画面展開スクリプト-->
<script type="text/javascript" src="../js/menu.js"></script> 

<!--場所検索フォームスクリプト-->
<script type="text/javascript" src="../js/search-place.js"></script>


<script src="../js/cropper.min.js"></script>
<script>
    
    (function (factory) {
      if (typeof define === 'function' && define.amd) {
        // AMD. Register as anonymous module.
        define(['jquery'], factory);
      } else if (typeof exports === 'object') {
        // Node / CommonJS
        factory(require('jquery'));
      } else {
        // Browser globals.
        factory(jQuery);
      }
    })(function ($) {

      'use strict';

      var console = window.console || { log: function () {} };

      function CropAvatar($element) {
        this.$container = $element;

        this.$avatarView = this.$container.find('.avatar-view');
        this.$avatar = this.$avatarView.find('img');
        this.$avatarModal = this.$container.find('#avatar-modal');
        this.$loading = this.$container.find('.loading');

        this.$avatarForm = this.$avatarModal.find('.avatar-form');
        this.$avatarUpload = this.$avatarForm.find('.avatar-upload');
        this.$avatarSrc = this.$avatarForm.find('.avatar-src');
        this.$avatarData = this.$avatarForm.find('.avatar-data');
        this.$avatarInput = this.$avatarForm.find('.avatar-input');
        this.$avatarSave = this.$avatarForm.find('.avatar-save');
        this.$avatarBtns = this.$avatarForm.find('.avatar-btns');

        this.$avatarWrapper = this.$avatarModal.find('.avatar-wrapper');
        this.$avatarPreview = this.$avatarModal.find('.avatar-preview');

        this.init();
      }

      CropAvatar.prototype = {
        constructor: CropAvatar,

        support: {
          fileList: !!$('<input type="file">').prop('files'),
          blobURLs: !!window.URL && URL.createObjectURL,
          formData: !!window.FormData
        },

        init: function () {
          this.support.datauri = this.support.fileList && this.support.blobURLs;

          if (!this.support.formData) {
            this.initIframe();
          }

          this.initTooltip();
          this.initModal();
          this.addListener();
        },

        addListener: function () {
          this.$avatarView.on('click', $.proxy(this.click, this));
          this.$avatarInput.on('change', $.proxy(this.change, this));
          this.$avatarForm.on('submit', $.proxy(this.submit, this));
          this.$avatarBtns.on('click', $.proxy(this.rotate, this));
        },

        initTooltip: function () {
          this.$avatarView.tooltip({
            placement: 'bottom'
          });
        },

        initModal: function () {
          this.$avatarModal.modal({
            show: false
          });
        },

        initPreview: function () {
          var url = this.$avatar.attr('src');

          this.$avatarPreview.html('<img src="' + url + '">');
        },

        initIframe: function () {
          var target = 'upload-iframe-' + (new Date()).getTime();
          var $iframe = $('<iframe>').attr({
                name: target,
                src: ''
              });
          var _this = this;

          // Ready ifrmae
          $iframe.one('load', function () {

            // respond response
            $iframe.on('load', function () {
              var data;

              try {
                data = $(this).contents().find('body').text();
              } catch (e) {
                console.log(e.message);
              }

              if (data) {
                try {
                  data = $.parseJSON(data);
                } catch (e) {
                  console.log(e.message);
                }

                _this.submitDone(data);
              } else {
                _this.submitFail('Image upload failed!');
              }

              _this.submitEnd();

            });
          });

          this.$iframe = $iframe;
          this.$avatarForm.attr('target', target).after($iframe.hide());
        },

        click: function () {
          this.$avatarModal.modal('show');
          this.initPreview();
        },

        change: function () {
          var files;
          var file;

          if (this.support.datauri) {
            files = this.$avatarInput.prop('files');

            if (files.length > 0) {
              file = files[0];

              if (this.isImageFile(file)) {
                if (this.url) {
                  URL.revokeObjectURL(this.url); // Revoke the old one
                }

                this.url = URL.createObjectURL(file);
                this.startCropper();
              }
            }
          } else {
            file = this.$avatarInput.val();

            if (this.isImageFile(file)) {
              this.syncUpload();
            }
          }
        },

        submit: function () {
          if (!this.$avatarSrc.val() && !this.$avatarInput.val()) {
            return false;
          }

          if (this.support.formData) {
            this.ajaxUpload();
            return false;
          }
        },

        rotate: function (e) {
          var data;

          if (this.active) {
            data = $(e.target).data();

            if (data.method) {
              this.$img.cropper(data.method, data.option);
            }
          }
        },

        isImageFile: function (file) {
          if (file.type) {
            return /^image\/\w+$/.test(file.type);
          } else {
            return /\.(jpg|jpeg|png|gif)$/.test(file);
          }
        },

        startCropper: function () {
          var _this = this;

          if (this.active) {
            this.$img.cropper('replace', this.url);
          } else {
            this.$img = $('<img src="' + this.url + '">');
            this.$avatarWrapper.empty().html(this.$img);
            this.$img.cropper({
              aspectRatio: 1,
              preview: this.$avatarPreview.selector,
              crop: function (e) {
                var json = [
                      '{"x":' + e.x,
                      '"y":' + e.y,
                      '"height":' + e.height,
                      '"width":' + e.width,
                      '"rotate":' + e.rotate + '}'
                    ].join();

                _this.$avatarData.val(json);
              }
            });

            this.active = true;
          }

          this.$avatarModal.one('hidden.bs.modal', function () {
            _this.$avatarPreview.empty();
            _this.stopCropper();
          });
        },

        stopCropper: function () {
          if (this.active) {
            this.$img.cropper('destroy');
            this.$img.remove();
            this.active = false;
          }
        },

        ajaxUpload: function () {
          var url = this.$avatarForm.attr('action');
          var data = new FormData(this.$avatarForm[0]);
          var _this = this;

          $.ajax(url, {
            type: 'post',
            data: data,
            dataType: 'json',
            processData: false,
            contentType: false,

            beforeSend: function () {
                console.log("test");
              _this.submitStart();
            },

            success: function (data) {
                console.log(data);
              _this.submitDone(data);
            },

            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest);
                console.log(textStatus);
                console.log(errorThrown);
              _this.submitFail(textStatus || errorThrown);
            },

            complete: function () {
              _this.submitEnd();
            }
          });
        },

        syncUpload: function () {
          this.$avatarSave.click();
        },

        submitStart: function () {
          this.$loading.fadeIn();
        },

        submitDone: function (data) {
          console.log(data);
          console.log(data.result);
            var url = data.result;
            var test = $(".profileimg-navbar img").attr('src', url);
            console.log(test);

          if ($.isPlainObject(data) && data.state === 200) {
            if (data.result) {
              this.url = data.result;

              if (this.support.datauri || this.uploaded) {
                this.uploaded = false;
                this.cropDone();
              } else {
                this.uploaded = true;
                this.$avatarSrc.val(this.url);
                this.startCropper();
              }

              this.$avatarInput.val('');
            } else if (data.message) {
              this.alert(data.message);
            }
          } else {
            this.alert('Failed to response');
          }
        },

        submitFail: function (msg) {
          this.alert(msg);
        },

        submitEnd: function () {
          this.$loading.fadeOut();
        },

        cropDone: function () {
          this.$avatarForm.get(0).reset();
          this.$avatar.attr('src', this.url);
          this.stopCropper();
          this.$avatarModal.modal('hide');
        },

        alert: function (msg) {
          var $alert = [
                '<div class="alert alert-danger avatar-alert alert-dismissable">',
                  '<button type="button" class="close" data-dismiss="alert">&times;</button>',
                  msg,
                '</div>'
              ].join('');

          this.$avatarUpload.after($alert);
        }
      };

      $(function () {
        return new CropAvatar($('#crop-avatar'));
      });

    }); 
</script>  
</body>
</html>