#!/usr/bin/env bash

cd ~/.runner-scripts/ && git reset --hard  && git pull &&
sudo chmod +x ~/.runner-scripts/run &&
sudo chmod +x ~/.runner-scripts/update.sh &&
sudo chmod +x ~/.runner-scripts/unistall.sh &&
sudo chmod +x ~/.runner-scripts/init &&
./init