<?php
session_start();
include 'db.php';
?>
    <!doctype html>
    <html lang="en">

    <head>
        <title>Get Logo</title>
        <?php include 'includes/head.php'; ?>
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <style>
            input[type="search"]::-webkit-search-cancel-button {
                -webkit-appearance: searchfield-cancel-button;
            }
        </style>
        <link rel="stylesheet" href="assets/js/jquery.range.css">
    </head>

    <body>
        <?php $page='getlogo'; include 'includes/nav.php'; ?>
        <div class="list">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">

                        <h5>
                            Get Youtube Channel Logo
                        </h5>

                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                         <div class="alert alert-danger" id="error" style="display:none">
                          <strong>Danger!</strong> Indicates a dangerous or potentially negative action.
                        </div>
                        <div class="form-group">
                            <form action="" id="searchform">
                                <input autocomplete="off" id="search" type="search" class="form-control" placeholder="Search and select channel from suggestions list...">
                            </form>
                        </div>
                        <div class="form-group">
                            <input onclick="this.select()" readonly id="imageurl" type="text" class="form-control" placeholder="Logo Url Appears Here">
                        </div>
                        <div class="form-group">
                            <input onclick="this.select()" readonly id="videourl" type="text" class="form-control" placeholder="Video Url Appears Here">
                        </div>
                        <div class="form-group">
                            <div id="searchloader" style="display:none">
                                <center><i class="fa fa-spinner fa-2x fa-spin"></i></center>
                            </div>
                            <div id="video">

                            </div>

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <img id="logo" width="380" src="https://image.flaticon.com/icons/svg/124/124015.svg" class="img-fluid" alt="">
                        </div>
                        <button id="downloadbtn" class="btn btn-danger" style="display:none">Download</button>
                    </div>
                </div>
                <div class="row">
                    <form action="downloadlogo" id="downloadform" method="post" style="display:none">
                        <input type="hidden" name="url" id="urlfield">
                        <input type="hidden" name="name" id="namefield">
                    </form>
                </div>
            </div>
        </div>
    </body>
    <?php include 'includes/scripts.php'; ?>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            $("#search").autocomplete({
                source: "ajax/autocomplete",
                minLength: 2,
                select: function(event, ui) {
                    if(ui.item.value==''){  return false;  }
                    $('#searchloader').show();
                    $("#video").empty();
                    $.ajax({
                        url: "ajax/process",
                        data: {
                            channelid: ui.item.channelid
                        },
                        type: "POST",
                        dataType: 'json',
                        success: function(data) {
                            $('#searchloader').hide();
                            console.log(data.url);
                            $("#logo").attr('src', data.url);
                            $('#downloadbtn').show();
                            $("#video").html('<iframe width="100%" height="250" src="https://www.youtube.com/embed/' + data.videoid + '" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>');
                            $("#videourl").val('https://www.youtube.com/watch?v=' + data.videoid);
                            $("#imageurl").val(data.url);

                        },
                    });
                },response: function(event, ui) {
                    if (ui.content.length==0) {
                        var noResult = { value:"",label:"No results found for "+this.value };
                        ui.content.push(noResult);
                    }
                }
            });
        });


        $("#searchform").submit(function(e) {
            e.preventDefault();
            //$("#error").show();
            //alert('Please select the channel from the suggestions list');
            //$(".ui-menu-item").show();
            //$("#search").val('');
        });


        $("#downloadbtn").click(function() {
            var url = $("#logo").attr('src');
            var name = $("#search").val();
            $("#urlfield").val(url);
            $("#namefield").val(name);
            $("#downloadform").submit();
        });
    </script>

    </html>