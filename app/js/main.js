


    $(document).ready(function() {

        function getCountPages() {
            $.ajax({
                type: 'get',
                url: '/count'
            }).done(function (data) {
                count = parseInt(data);
                if (count > 1) {
                    var array = window.location.href.split("/");
                    var num = array[array.length - 1];
                    for (var i = 1; i <= count; i++) {
                        if (i == num) {
                            $(".pages").append("<li class='active'>" + i.toString() + "</li>");
                        }
                        else {
                            $(".pages").append("<li>" + i.toString() + "</li>");
                        }
                    }
                }
            });
        }
        getCountPages();
        $('ul.pages').on('click', 'li', function() {
            window.location = "/get/"+ $(this).html();

        });

        $(".ad i").click(function() {
            $(this).closest(".ad").next().slideToggle(200);
        });

        $(".carousel").owlCarousel({
            items: 3
        });

        $(".fancybox").fancybox(

        );

        $(".arrow_wrap i").click(function() {
            $(this).toggleClass("fa-chevron-down fa-chevron-up");
            $(".categories").slideToggle();
        });
        var open = false;

        $(".item.fancybox").fancybox({
            afterClose: function() {
                open = true;
                var a = "#" + $(this.element).closest("form").attr("id");
                 $("button[href='"  +  a + "'" +  "]").click();
            }
        });

    $(".fancybox.detail").fancybox({
        width: 600,
        height: 400,
        autoDimensions: true,
        fitToView: false,
        scrolling: 'no',
        helpers: {
            overlay: {closeClick: false}
        },
        beforeLoad: function () {

            console.log("beforeLoad");
            if (open==false) {
                var self = $(this.element).parent().parent();
                $.ajax({
                    type: 'post',
                    url: '/details',
                    data: {id: self.children("input").attr("value")}
                }).done(function (data) {
                    dataParsed = JSON.parse(data);
                    if (dataParsed.success) {
                        console.log("sdasd");
                        var a = self.children("a").children("span");
                        a.html(parseInt(a.html()) + 1);
                    }
                })
            }
        },
        beforeClose: function() {
           open=false;
        }
    });


        $("#register").submit(function() {
            $.ajax({
                type: 'post',
                url: '/register',
                data: $("#register").serialize(),
                dataType : "json"
            }).done(function(data) {

                console.log("gol");
                console.log(data);
                console.log("gol");
                data = JSON.parse(data);
                $("#register .login_errors ,#register .pass_errors, #register .email_errors").html("");
                if (!data.success) {
                    alertify.notify("Обнаружены ошибки",'error');
                    if (data.errors.login!=null)
                    {
                        $("#register .login_errors").html(data.errors.login);
                    }

                    if (data.errors.password!=null) {

                        $("#register .pass_errors").html(data.errors.password);
                    }
                    if (data.errors.email!=null)
                    {
                        $("#register .email_errors").html(data.errors.email);
                    }
                }
                else {

                    $("#register").trigger("reset");
                    $.fancybox.close();
                    alertify.notify("Регистрация прошла успешно",'success');
                    setTimeout(function(){location.href="/get/1"} , 1000);

                }   
            }).fail(function(jqXHR, textStatus, error) {
               console.log("fail");
                console.log(jqXHR,textStatus,error);
            });
        return false;
        });

        $("#enter").submit(function() {
            $.ajax({
                type: 'post',
                url: '/enter',
                data: $("#enter").serialize(),
                dateType: 'json'
            }).done(function(data) {
                var dataParsed = JSON.parse(data);
                console.log(dataParsed);

                if (!dataParsed.success) {
                    alertify.error("Неправильный логин или пароль");
                }
                else {
                    alertify.success("Аторизация прошла успешно");
                    $("#enter").trigger("reset");
                    $.fancybox.close();
                    setTimeout(function(){location.href="/get/1"} , 1000);
                }
            });
            return false;
        });

        $("form#create").submit(function(e) {
            var formData = new FormData($(this)[0]);
            console.dir(formData.toString());
            $.ajax({
                type: 'POST',
                url: '/create',
                data: formData,
                cache: false,
                contentType: false,
                processData: false
            }).done(function(data) {
                dataParsed = JSON.parse(data);

                if ($("#container .container_item").children().length==12)
                {
                    $("#container  .container_item:last").remove();
                }
                $('#container').prepend(dataParsed.html);
                alertify.success("Объявление успешно добавлено");
                $("#create").trigger("reset");
                $.fancybox.close();
                getCountPages();
            });
            return false;
        });

        $(".advert i").click(function() {
            console.log($(this).parent().parent().attr("class"));
            $(this).parent().parent().remove();
        });
        $(".edit_advert").submit(function(e) {
            var formData = new FormData($(this)[0]);
            $.ajax({
                type: 'POST',
                url: '/update',
                data: formData,
                cache: false,
                contentType: false,
                processData: false
            }).done(function(data) {
                alertify.notify("Объявление успешно изменено",'success');
                setTimeout(function(){location.href="/profile"} , 1000);
            });
            return false;
        });

	});
