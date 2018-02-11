<?php


namespace App\Utils;

class Check
{
    //
    public static function isEmailLegal($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }
	
    public static function isNotBanCidr()
    {
        $tfile = "../config/Bcidr.txt";

        if ( false == is_file($tfile))
        {
            return true;
        }

        $ipaddr = $_SERVER["REMOTE_ADDR"];
        $fh = fopen($tfile, "r");

        while (! feof($fh))
        {
            $mklen = 32;
            $line = fgets($fh);
            $line = str_replace(PHP_EOL, '', $line);

            if ((false == empty($line))
            && (strpos($line, "/") > 0))
            {
                list($line, $mklen) = explode("/", $line);
                
		if (( 0 <= $mklen ) && ( $mklen <= 32 ))
                {
		    $ip = ip2long($line);
		    $rip = ip2long($ipaddr);
		    $rlen = (32 - $mklen);

		    if (($rip >> $rlen) == ($ip >> $rlen))
		    {
			fclose($fh);
			return false;
		    }
		}
	    }
        }

        fclose($fh);
        return true;
   }

    public static function isBanIP()
    {
	$tfile = "../config/Bip.txt"; 
	
	if ( false == is_file($tfile))
	{
		return true;
	}

	$fh = fopen($tfile, "r");
	
	while (! feof($fh))
        {
                $line = fgets($fh);
                $line = str_replace(PHP_EOL, '', $line);

                if ( 0 == strcmp($line, $_SERVER["REMOTE_ADDR"]))
                {
                        fclose($fh);
                        return false;
                }
        }

	fclose($fh);
	return true;
   }

    public static function isBanEmail($email)
    {
	$tfile = "../config/Bemail.txt"; 
	
	if ( false == is_file($tfile))
	{
		return true;
	}
	
	$asterisk ="*";
        $at = "@";

        preg_match('/@.*/i', $email, $bmc);
	$bemail = $bmc[0];        

	$fh = fopen($tfile, "r");
        
	while (! feof($fh))
        {
		$line = fgets($fh);
                $line = str_replace(PHP_EOL, '', $line);

                $len = strlen($line);

                if (0 == strcmp($asterisk, $line[0]))
                {
                        $line = substr($line, (~($len - 1) + 1));
                }
                else if (0 == strcmp($asterisk, $line[-1]))
                {
                        $line = substr($line, (~$len + 1), ($len - 1));
                }
		
		if (false == (empty($line))
                && (false != stristr($bemail, $line)))
                {
			fclose($fh);
			return false;
                }
        }
		
	fclose($fh);
	return true;
    }
}

