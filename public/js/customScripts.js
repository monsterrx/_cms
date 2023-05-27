$(document).ready(function () {
    let url = window.location.pathname;

    let dashboard = $("#Dashboard");
    let awards = $("#Awards");
    let messages = $("#Messages");
    let employees = $("#Employees");
    let radioOne = $("#Radio1");
    let music = $("#Music");
    let charts = $("#Charts");
    let digital = $("#Digital");
    let programs = $("#Programs");
    let education = $("#Education");
    let indiegrounds = $('#Indiegrounds');
    let giveaway = $("#Giveaway");
    let users = $("#Users");
    let logs = $("#Logs");
    let recovery = $("#Recovery");

    $('#year_level, #start_year, #end_year').on('keypress', function(event){
        if (event.which !== 8 && event.which < 48 || event.which > 57)
        {
            event.preventDefault();
        }
    });

    switch (url) {
        // dashboard
        case '/system-views':
            dashboard.addClass('active');
            break;
        // awards
        case '/system-views/awards':
            awards.addClass('active');
            break;
        // messages
        case '/system-views/messages':
            messages.addClass('active');
            break;
        // employees
        case '/system-views/employees':
            employees.addClass('active');
            break;
        case '/system-views/designations':
            employees.addClass('active');
            break;
        case '/system-views/jocks':
            employees.addClass('active');
            break;
        // radio-1
        case '/system-views/radioOne/batches':
            radioOne.addClass('active');
        break;
        case '/system-views/radioOne/jocks':
            radioOne.addClass('active');
            break;
        case '/system-views/radioOne/positions':
            radioOne.addClass('active');
            break;
        // songs
        case '/system-views/artists':
            music.addClass('active');
            break;
        case '/system-views/albums':
            music.addClass('active');
            break;
        case '/system-views/songs':
            music.addClass('active');
            break;
        case '/system-views/genres':
            music.addClass('active');
            break;
        case '/system-views/charts':
            charts.addClass('active');
            break;
        case '/system-views/dropouts':
            charts.addClass('active');
            break;
        // digital content
        case '/system-views/articles':
            digital.addClass('active');
            break;
        case '/system-views/categories':
            digital.addClass('active');
            break;
        case '/system-views/sliders':
            digital.addClass('active');
            break;
        // programs
        case '/system-views/shows':
            programs.addClass('active');
            break;
        case '/system-views/timeslots':
            programs.addClass('active');
            break;
        case '/system-views/podcasts':
            programs.addClass('active');
            break;
        // monster education
        case '/system-views/schools':
            education.addClass('active');
            break;
        case '/system-views/gimikboards':
            education.addClass('active');
            break;
        case '/system-views/batch':
            education.addClass('active');
            break;
        case '/system-views/students':
            education.addClass('active');
            break;
        case '/system-views/sponsors':
            education.addClass('active');
            break;
        // indiegrounds
        case '/system-views/indiegrounds':
            indiegrounds.addClass('active');
            break;
        case '/system-views/featured':
            indiegrounds.addClass('active');
            break;
        // monster giveaways
        case '/system-views/giveaways':
            giveaway.addClass('active');
            break;
        case '/system-views/contestants':
            giveaway.addClass('active');
            break;
        // logs & archives
        case '/system-views/users':
            users.addClass('active');
            break;
        case '/system-views/employee_logs':
            logs.addClass('active');
            break;
        case '/system-views/deletedData':
            recovery.addClass('active');
            break;
        default:
        break;
    }

    // for double submission
    $('#login_post, #delete_post, #employee_post, #designation_post, #user_post, #artist_post, #album_post, #category_post, #location_post, #add_category_post, #platform_post, #song_post, #jock_post,#school_post, #gimik_post, #add_jock,#show_post, #delete_post2, #article_post, #sub_post, #award_post, #profile_post, #image_post, #facts_post, #link_post, #slider_post, #indie_post, #podcast_post, #student_post').on('submit', function(){
        let self = $(this),
            button = self.find('input[type="submit"], button'),
            submitValue = button.data('submit-value');
        button.attr('disabled', 'disabled').val((submitValue) ? submitValue: 'loading...');
    });

    $('#genericTable, #songList').dataTable();

    $('#website').on('change', function () {
        let optionVal = $('#website option:selected').val().toLowerCase();
        let linkString = $('#url');

        switch (optionVal) {
            case 'facebook':
                linkString.val(optionVal+'.com/');
                break;
            case 'instagram':
                linkString.val(optionVal+'.com/');
                break;
            case 'twitter':
                linkString.val(optionVal+'.com/');
                break;
            case 'youtube':
                linkString.val(optionVal+'.com/');
                break;
            case 'tumblr':
                linkString.val(optionVal+'.com/');
                break;
            default:
                linkString.val('');
                break;
        }
    });

    function makeId(length) {
        let result           = '';
        let characters       = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        let charactersLength = characters.length;
        for (let i = 0; i < length; i++ ) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }

    $("#new_giveaway").on('click', function () {
        $("#code").val(makeId(10));
    });

    $('.owl-carousel').owlCarousel();
    $('[data-toggle="tooltip"]').tooltip();

    // for button that already has a data-toggle attribute
    $('[data-help="tooltip"]').tooltip();
    $('#tooltip').tooltip();

    $('#phone_number').inputmask('+639999999999');

    $(function() {
        $('.nav-link').on('click',function() {
            // remove classes from all
            $('.nav-link').removeClass("active");
            // add class to the one we clicked
            $(this).addClass("active");
        });
    });

    /*// tinymce editor
    let editor_id = "";
    tinymce.PluginManager.add('instagram', function(editor, url) {
        // Add a button that opens a window
        editor.addButton('instagram', {
            text: 'Instagram',
            icon: false,
            onclick: function() {
                // Open window
                editor.windowManager.open({
                    title: 'Instagram Embed',
                    body: [
                        {   type: 'textbox',
                            size: 40,
                            height: '100px',
                            name: 'instagram',
                            label: 'instagram'
                        }
                    ],
                    onsubmit: function(e) {
                        // Insert content when the window form is submitted
                        tinymce.activeEditor.insertContent('<iframe frameborder="0" src="'+e.data.instagram+'embed" frameborder="0"></iframe>')

                        /!*let embedCode = e.data.instagram;
                        let script = embedCode.match(/<script.*<\/script>/)[0];
                        let scriptSrc = script.match(/".*\.js/)[0].split("\"")[1];
                        let sc = document.createElement("script");
                        sc.setAttribute("src", scriptSrc);
                        sc.setAttribute("type", "text/javascript");

                        let iframe = document.getElementById(editor_id + "_ifr");
                        let iframeHead = iframe.contentWindow.document.getElementsByTagName('head')[0];
                        iframeHead.appendChild(sc);

                        tinyMCE.activeEditor.insertContent(e.data.instagram);*!/
                        // editor.insertContent('Title: ' + e.data.title);
                    }
                });
            }
        });
    });
    tinymce.PluginManager.add('twitter_url', function(editor, url) {
        let icon_url=window.location.protocol + "//" + window.location.host + '/images/Twitter-color.svg';

        editor.on('init', function (args) {
            editor_id = args.target.id;

        });
        editor.addButton('twitter_url',
            {
                text:"Twitter",
                icon: false,
                onclick: function () {

                    editor.windowManager.open({
                        title: 'Twitter Embed',

                        body: [
                            {   type: 'textbox',
                                size: 40,
                                height: '100px',
                                name: 'twitter',
                                label: 'twitter'
                            }
                        ],
                        onsubmit: function(e) {

                            let tweetEmbedCode = e.data.twitter;

                            $.ajax({
                                url: "https://publish.twitter.com/oembed?url="+tweetEmbedCode,
                                dataType: "jsonp",
                                async: false,
                                success: function(data){
                                    // $("#embedCode").val(data.html);
                                    // $("#preview").html(data.html)
                                    tinyMCE.activeEditor.insertContent(
                                        '<div class="div_border" contenteditable="false">'
                                        +data.html+
                                        '</div>');

                                },
                                error: function (jqXHR, exception) {
                                    let msg = '';
                                    if (jqXHR.status === 0) {
                                        msg = 'Not connect.\n Verify Network.';
                                    } else if (jqXHR.status === 404) {
                                        msg = 'Requested page not found. [404]';
                                    } else if (jqXHR.status === 500) {
                                        msg = 'Internal Server Error [500].';
                                    } else if (exception === 'parsererror') {
                                        msg = 'Requested JSON parse failed.';
                                    } else if (exception === 'timeout') {
                                        msg = 'Time out error.';
                                    } else if (exception === 'abort') {
                                        msg = 'Ajax request aborted.';
                                    } else {
                                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                                    }
                                    alert(msg);
                                },
                            });
                            setTimeout(function() {
                                iframe.contentWindow.twttr.widgets.load();

                            }, 1000);
                        }
                    });
                }
            });
    });*/
});
