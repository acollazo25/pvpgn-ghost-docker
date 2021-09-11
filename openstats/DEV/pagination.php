<?PHP


    if (isset($_GET['heroes'])) {$prefix = "heroes";}
	if (isset($_GET['items'])) {$prefix = "items";}
	if (isset($_GET['bans'])) {$prefix = "bans";}
	if (isset($_GET['addnews'])) {$prefix = "addnews";}
	if (isset($_GET['games'])) {$prefix = "games&check";}
	if (isset($_GET['admins'])) {$prefix = "admins";}
	if (isset($_GET['games']) AND isset($_GET['winner'])) {$prefix = "games&check&winner=$_GET[winner]";}
	if (isset($_GET['l'])) {$l = "&l=".EscapeStr($_GET['l']);} else {$l = "";}
	if (isset($_GET['order'])) {$prefix.= "&order=".safeEscape($_GET['order']);}
	if (isset($_GET['sort'])) {$prefix.= "&sort=".safeEscape($_GET['sort']);}

              $totalpages = ceil($numrows / $rowsperpage);
              if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                  $currentpage = (int)$_GET['page'];
              } else {
                  $currentpage = 1;
              }
              if ($currentpage > $totalpages) {
                  $currentpage = $totalpages;
              }
              if ($currentpage < 1) {
                  $currentpage = 1;
              }
              if ($totalpages <= 1) {
                  $totalpages = 1;
              }

              $offset = ($currentpage - 1) * $rowsperpage;
              if (!isset($_GET['page'])) {
                  $current_page = 1;
              } else {  $current_page = EscapeStr($_GET['page']); }
			  
              $range = 9;
              if ($range >= $totalpages) {
                  $range = $totalpages;
              }
              echo '<table><tr><td align="right" class="pagination"> <b>Page ' . $current_page . ' of ' . $totalpages . '</b> [' . $numrows . ' matches]  &nbsp; &nbsp;';
              if ($currentpage > 1) {
                  echo " <a title= 'First page' href='{$_SERVER['PHP_SELF']}?$prefix".$l."&page=1'><<</a> ";
                  $prevpage = $currentpage - 1;
                  echo " <a title='Previous page' href='{$_SERVER['PHP_SELF']}?$prefix".$l."&page=$prevpage'><</a> ";
              }
              for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
                  if (($x > 0) && ($x <= $totalpages)) {
                      if ($x == $currentpage) {
                          echo " [<b>$x</b>] ";
                      } else {
                          echo " <a title='Go to page " . $x . "' href='{$_SERVER['PHP_SELF']}?$prefix".$l."&page=$x'>$x</a> ";
                      }
                  }
              }
              if ($currentpage != $totalpages) {
                  $nextpage = $currentpage + 1;
                  echo " <a title='Next page' href='{$_SERVER['PHP_SELF']}?$prefix".$l."&page=$nextpage'>></a> ";
                  echo " <a title='Last page' href='{$_SERVER['PHP_SELF']}?$prefix".$l."&page=$totalpages'>>></a> ";
              }
              echo '</td></tr></table>';
			  
			  ?>