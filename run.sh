#!/bin/bash

if [[ "$1" == "start" ]]; 
	then
		echo -e "[»] Uruchamianie instancji wykonawczych. (w toku)"
		screen -dmS powerBot_1 php core.php -i 1
		screen -dmS powerBot_2 php core.php -i 2
		screen -dmS powerBot_3 php core.php -i 3
		screen -dmS powerBot_4 php core.php -i 4
		screen -dmS powerBot_help_center php core.php -i 6
		screen -dmS powerBot_data_handler php core.php -i 7
		screen -dmS powerBot_website php core.php -i 8
		echo -e "[»] Instancje zostały uruchomione!"
elif [[ "$1" == "stop" ]]; 
	then
		echo -e "[»] Wyłączanie instancji wykonawczych. (w toku)"
		screen -X -S powerBot_1 quit
		screen -X -S powerBot_2 quit
		screen -X -S powerBot_3 quit
		screen -X -S powerBot_4 quit
		screen -X -S powerBot_help_center quit
		screen -X -S powerBot_data_handler quit
		screen -X -S powerBot_website quit
		echo -e "[»] Instancje zostały wyłączone!"
elif [[ "$1" == "restart" ]]; 
	then
		echo -e "[»] Restartowanie instancji wykonawczych. (w toku)"
		screen -X -S powerBot_1 quit
		screen -X -S powerBot_2 quit
		screen -X -S powerBot_3 quit
		screen -X -S powerBot_4 quit
		screen -X -S powerBot_help_center quit
		screen -X -S powerBot_data_handler quit
		screen -X -S powerBot_website quit
		
		screen -dmS powerBot_1 php core.php -i 1
		screen -dmS powerBot_2 php core.php -i 2
		screen -dmS powerBot_3 php core.php -i 3
		screen -dmS powerBot_4 php core.php -i 4
		screen -dmS powerBot_help_center php core.php -i 6
		screen -dmS powerBot_data_handler php core.php -i 7
		screen -dmS powerBot_website php core.php -i 8
		echo -e "[»] Instancje zostały zrestartowane!"
else
		echo -e "[»] Nie wiem co mam zrobić! Wpisz:"
		echo -e "[»] ./run.sh start - aby włączyć aplikację"
		echo -e "[»] ./run.sh stop - aby wyłączyć aplikację"
		echo -e "[»] ./run.sh restart - aby zrestartować aplikację"
	fi