<?php
echo '<script type="text/javascript">
            const path = window.location.pathname;
            const query = window.location.search;
           window.location.href = "' . home_url($token ? "/" : "login") . '?path="+path+"&query="+query;
      </script>';
exit;
