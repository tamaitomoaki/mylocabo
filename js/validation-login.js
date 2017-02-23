var url = window.location;
    url = url.origin;
$("#dummy-btn").on("click", function(e){
    $(".alert-email").empty();
    $(".alert-password").empty();
    var email = $("#email").val();
    var password = $(":password").val();

    $.post(
        url+"/L/validation/check-login.php", {
            email: email,
            password: password,
        }
    ).done(
        function(data) {
             console.log(data);
            switch(data){
              case "0" : $('#login-btn').trigger("click"); break;
              case "1" :       
                    $(".alert-email").empty();
                    $(".alert-email").append(
                        'メールアドレスを入力してください。'
                    );
                    break;
              case "2" :
                    $(".alert-password").empty();
                    $(".alert-password").append(
                        'パスワードを入力してください。'
                    );
                    break;
              case "3" :
                    $(".alert-email").empty();
                    $(".alert-password").empty();
                    $(".alert-email").append(
                        'メールアドレスを入力してください。'
                    );
                    $(".alert-password").append(
                        'パスワードを入力してください。'
                    );
                    break;
              case "4" :   
                    $(".modal-alert").empty();
                    $(".modal-alert").append(
                        '<div class="alert alert-danger alert-dismissible alert-login" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>メールアドレスあるいはパスワードに誤りがあります。</div>'
                    );
                    break;
              case "5" : 
                    $(".modal-alert").empty();
                    $(".modal-alert").append(
                        '<div class="alert alert-danger alert-dismissible alert-login" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>メールアドレスあるいはパスワードに誤りがあります。</div>'
                    );
                    break;
              case "6" : 
                    e.preventDefault();
                    $(".modal-alert").empty();
                    $(".modal-alert").append(
                        '<div class="alert alert-danger alert-dismissible alert-login" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>データベースエラー</div>'
                    );
                    break;
              default : alert("エラーです。もう一度ログインしてください。"); break;
            }
        }
    );



});
