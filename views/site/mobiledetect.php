<html>

<head>
  <title>App Link</title>
  <meta property="al:ios:url" content="https://apps.apple.com/app/premier-padel-official-app/id6504236153" />
  <meta property="al:ios:app_name" content="Premier Padel" />
  <meta property="al:ios:app_store_id" content="6504236153" />
  <meta property="al:android:package" content="com.premierpadel" />
  <meta property="al:android:app_name" content="Premier Padel" />
  <meta property="al:android:url" content="https://play.google.com/store/apps/details?id=com.premierpadel" />
  <meta property="al:web:should_fallback" content="false" />
  <meta http-equiv="refresh" content="0;url=https://play.google.com/store/apps/details?id=com.premierpadel" />
</head>

<body>
  <?php
  $iPod    = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
  $iPhone  = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
  $iPad    = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
  $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
  $webOS   = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");

  if ($iPod || $iPhone) {
    $url = 'https://apps.apple.com/app/premier-padel-official-app/id6504236153';
    header('Location: ' . $url);
    //return $this->redirect('');
  } else if ($Android) {
    $url = 'https://play.google.com/store/apps/details?id=com.premierpadel';
    header('Location: ' . $url);
    //return $this->redirect('https://play.google.com/store/apps/details?id=com.leap_dating&hl=en_US');
  } else {
    $url = 'https://premierpadel.com/';
    header('Location: ' . $url);
  }

  ?>

</body>

</html>