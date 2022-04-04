<?php

$linkUrl = "/forumWebsite";

$meta = "<meta charset='UTF-8'>
<meta http-equiv='X-UA-Compatible' content='IE=edge'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<link rel='icon' type='image/x-icon' href=$linkUrl/static/logo.png>";




$links = "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3' crossorigin='anonymous'>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js' integrity='sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p' crossorigin='anonymous'></script>
<link rel='stylesheet' href='https://pro.fontawesome.com/releases/v5.10.0/css/all.css' integrity='sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p' crossorigin='anonymous'/>
<link rel='stylesheet' href='$linkUrl/static/logo.css'>
<link rel='stylesheet' href='$linkUrl/static/notification.css'>
<script defer src='$linkUrl/static/logo.js'></script>
<script defer src='$linkUrl/partials/sessionChecker.js'></script>
<script defer src='$linkUrl/partials/logOutCheck.js'></script>
<script defer src='$linkUrl/partials/notificationChecker.js'></script>
<script> 
setTimeout(() => {
    localStorage.setItem('isLoggedIn','neutral')
}, 1000); 
</script>
";

echo "$meta $links";
?>
