#!/usr/bin/env bash

cd ~/.runner-scripts/ && git reset --hard  && git pull &&
sudo rm /usr/bin/runners* &&
sudo chmod +x ~/.runner-scripts/run.php &&
sudo chmod +x ~/.runner-scripts/update.sh &&
sudo chmod +x ~/.runner-scripts/unistall.sh &&
sudo chmod +x ~/.runner-scripts/template-install &&
sudo ln -s ~/.runner-scripts/run.php /usr/bin/runners &&
sudo ln -s ~/.runner-scripts/update.sh /usr/bin/runners-update &&
sudo ln -s ~/.runner-scripts/unistall.sh /usr/bin/runners-unistall &&
sudo ln -s ~/.runner-scripts/template-install.php /usr/bin/runners-template-install
