<?php

return [
    'domain' => ($_ENV['DOMAIN'] ?: ''),
    'user.passwordResetTokenExpire' => 3600,
    'apipath' => ($_ENV['APIPATH'] ?: ''),

    'adminEmail' => ($_ENV['ADMIN_EMAIL'] ?: ''),
    'supportEmail' => ($_ENV['SUPPORT_EMAIL'] ?: ''),
    'infoEmail' => ($_ENV['INFO_EMAIL'] ?: ''),
    'senderName' => ($_ENV['SENDER_NAME'] ?: ''),
    'senderEmail' => ($_ENV['SENDER_EMAIL'] ?: ''),
    'contactEmail' => ($_ENV['CONTACT_EMAIL'] ?: ''),
    'applicationEmail' => (
        $_ENV['application_email']
        ?: ($_SERVER['application_email']
        ?: (getenv('application_email')
        ?: ($_ENV['APPLICATION_EMAIL']
        ?: ($_SERVER['APPLICATION_EMAIL']
        ?: getenv('APPLICATION_EMAIL')))))
    ),
    'applicationCcEmail' => (
        $_ENV['application_cc_email']
        ?: ($_SERVER['application_cc_email']
        ?: (getenv('application_cc_email')
        ?: ($_ENV['APPLICATION_CC_EMAIL']
        ?: ($_SERVER['APPLICATION_CC_EMAIL']
        ?: getenv('APPLICATION_CC_EMAIL')))))
    ),
    'project_display_name' => 'Sunrise Car',

    'ImagePath' => ($_ENV['IMAGEPATH'] ?: ''),

    'bsVersion' => '4.x',

    'web_url' => ($_ENV['WEBURL'] ?: ''),
    'web_site_url' => ($_ENV['WEBSITEURL'] ?: ''),
    'twitter_url' => '',
    'linkedIn_url' => '',
    'instagram_url' => '',
    'facebook_url' => '',
    'youtube_url' => '',
    'snapchat_url' => '',
    'payment_stripe_mode' => ($_ENV['PAYMENT_STRIPE_MODE'] ?: ''),
    'payment_stripe_live_secret_key' => ($_ENV['PAYMENT_STRIPE_LIVE_SECRET_KEY'] ?: ''),
    'payment_stripe_test_secret_key' => ($_ENV['PAYMENT_STRIPE_TEST_SECRET_KEY'] ?: ''),
    'floraeditor' => "
    heightMin: 200,
        fontFamilySelection: true,
        fontSize:['8', '9', '10', '11', '12','13', '14','15', '16','17', '18','19','20','22', '24', '36']",

    'summernote' => "dialogsInBody: true,
    
    fontSizes: ['8', '9', '10', '11', '12','13', '14','15', '16','17', '18','19','20','22', '24', '36'],
    //colors: [
    //     ['red', 'green', 'blue','#418840','#e53d2f'], //first line of colors
    //     ['#000000','#424242','#636363','#9c9c94','#CEC6CE','EFEFEF','F7F7F7','FFFFFF'],
    //     ['#EA1885','#0F218B','#FF9C00','#FFFF00','#00FF00','#0000FF','9c00ff','FF00FF'],
    //     ['#F7C6CE','#FFE7CE','#FFEFC6','#D6EFD6','#CEDEE7','CEE7F7','D6D6E7','E7D6DE'],
    //     ['#E79C9C','#FFC69C','FFE79C','#B5D6A5','#A5C6CE','#9CC6EF','B5A5D6','D6A5BD'],
    //     ['#E76363','#F7AD6B','FFD663','#94BD7B','#73A5AD','#6BADDE','8C7BC6','C67BA5'],
    //     ['#82875e','#b06533','#c09639','#212721','#44797b','#51534a'],
    // ],
    toolbar: [
        ['style', ['style']],
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['font', ['strikethrough', 'superscript', 'subscript']],
        ['fontsize', ['fontsize']],
        ['fontname', ['fontname']],
        ['color', ['color']],
        //['colors', [['#418840', '#e53d2f']]],
        ['para', ['ul', 'ol', 'paragraph']],
        ['insertNode', ['quote']],
        ['height', ['height']],
        ['table', ['table']],
        //['insert', ['picture','pdf']],
        //['insert', ['link', 'picture', 'video','pdf']],
        ['insert', ['link', 'picture','pdf']],
        //['insert', ['link']],
        ['view', ['fullscreen', 'codeview', 'help']]
    ],
    toolbarButtons: [['bold', 'italic', 'underline'], ['embedly','html']],
    height: 250",

];
