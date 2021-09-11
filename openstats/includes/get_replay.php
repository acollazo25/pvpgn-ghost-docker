<?PHP
      //REPLAY NAME HANDLING CODE
    $replaygamename=str_ireplace("|","_",str_ireplace(">","_",str_ireplace("<","_",str_ireplace("?","_",str_ireplace("*","_",str_ireplace(":","_",str_ireplace("/","_",str_ireplace("\\","_",$gamename))))))));
	
    $replayloc="GHost++ ".$gametimenew." ".$replaygamename." (".replayDuration($duration).").w3g";

    if(!file_exists($replayLocation.'/'.$replayloc))
    {													//Time handling isn't perfect. Check time + 1 and time - 1
	$replayloc="GHost++ ".$gametimenew." ".$replaygamename." (".replayDuration($duration-1).").w3g";
	
	    if(!file_exists($replayLocation.'/'.$replayloc))
	    {
		$replayloc="GHost++ ".$gametimenew." ".$replaygamename." (".replayDuration($duration+1).").w3g";
		    if(!file_exists($replayLocation.'/'.$replayloc))
		    {
			$replayloc="GHost++ ".$gametimenew." ".$replaygamename.".w3g";
		    }
	    }
    }
	
    $replayurl = $replayLocation.'/'.str_ireplace("#","%23", str_ireplace("\\","_",str_ireplace("/","_",str_ireplace(" ","%20",$replayloc))));
	
    $replayloc = $replayLocation.'/'.str_ireplace("\\","_",str_ireplace("/","_",$replayloc));
	
    $txtReplay = substr($replayloc,0,-4).".html";
?>