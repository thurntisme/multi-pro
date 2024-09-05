<?php
echo '<script type="text/javascript">
           window.location.href = "' . home_url($token ? "/" : "login") . '";
      </script>';
exit;
