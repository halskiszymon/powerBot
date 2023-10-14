<?php
class license
{	
	public function checkLicense($key)
	{
		echo "[»] Weryfikowanie licencji aplikacji. (w toku)\n";
		$result = file_get_contents("http://apps.futurepower.pl/powerBot/check_license.php?key=".$key);
		if(!$result)
		{
			echo "[Blad] Nie udało się połączyć do serwera licencyjnego.\n";
			echo "[Info] Prosimy o szybki kontakt z administratorem aplikacji.\n";
			echo "[»] Wyłączanie aplikacji. (w toku)\n";
			exit();
		}
		$license = json_decode($result, true);
		if(!$license['result']['success'])
		{
			if($license['result']['code'] == "101")
			{
				echo "    > [Blad] Taka licencja nie została zarejestrowana.\n";
				echo "[»] Wyłączanie aplikacji. (w toku)\n";
				exit();
			}
			if($license['result']['code'] == "102")
			{
				echo "    > [Blad] Taka licencja nie została zarejestrowana.\n";
				echo "[»] Wyłączanie aplikacji. (w toku)\n";
				exit();
			}
			if($license['result']['code'] == "201")
			{
				echo "    > [Blad] Wystąpił wewnętrzny błąd serwera.\n";
				echo "    > [Info] Powiadom administratora aplikacji.\n";
				echo "[»] Wyłączanie aplikacji. (w toku)\n";
				exit();
			}
			if($license['result']['code'] == "301")
			{
				echo "    > [Blad] Taka licencja nie została zarejestrowana.\n";
				echo "[»] Wyłączanie aplikacji. (w toku)\n";
				exit();
			}
		}
		else if($license['result']['success'])
		{
			if($license['result']['license_active'])
			{
				$ip = exec("hostname -I");
				$ip = explode(" ", $ip);
				if($license['result']['license_server_ip'] == $ip[0])
				{
					if($license['result']['license_app_version'] == "1.0")
					{
						echo "    > Witaj, ".$license['result']['license_owner_name']."!\n";
						echo "[Sukces] Licencja została poprawnie zweryfikowana.\n\n";
					}
					else
					{
						echo "    > [Blad] Ta licencja jest zarejestrowana na inną wersję tej aplikacji.\n";
						echo "    > [Info] Skontaktuj sie z administratorem aplikacji.\n";
						echo "[»] Wyłączanie aplikacji. (w toku)\n";
						exit();
					}
				}
				else
				{
					echo "    > [Blad] Ta licencja jest zarejestrowana na inny adres ip.\n";
					echo "    > [Info] Skontaktuj sie z administratorem aplikacji.\n";
					echo "[»] Wyłączanie aplikacji. (w toku)\n";
					exit();
				}
			}
			else
			{
				if($license['result']['license_banned'])
				{
					echo "    > [Blad] Ta licencja została zbanowana.\n";
					echo "    > [Info] Skontaktuj sie z administratorem aplikacji.\n";
					echo "[»] Wyłączanie aplikacji. (w toku)\n";
					exit();
				}
				else
				{
					echo "    > [Blad] Ta licencja jest nieaktywna bądz wygasła.\n";
					echo "    > [Info] Skontaktuj sie z administratorem aplikacji.\n";
					echo "[»] Wyłączanie aplikacji. (w toku)\n";
					exit();
				}
			}
		}
	}
}