#!/usr/bin/env bash

main() {
  # Use colors, but only if connected to a terminal, and that terminal
  # supports them.
  if which tput >/dev/null 2>&1; then
      ncolors=$(tput colors)
  fi
  if [ -t 1 ] && [ -n "$ncolors" ] && [ "$ncolors" -ge 8 ]; then
    RED="$(tput setaf 1)"
    GREEN="$(tput setaf 2)"
    YELLOW="$(tput setaf 3)"
    BLUE="$(tput setaf 4)"
    BOLD="$(tput bold)"
    NORMAL="$(tput sgr0)"
  else
    RED=""
    GREEN=""
    YELLOW=""
    BLUE=""
    BOLD=""
    NORMAL=""
  fi

  # Only enable exit-on-error after the non-critical colorization stuff,
  # which may fail on systems lacking tput or terminfo
  set -e

  RUNNERS=~/.runner-scripts

  if [ -d "$RUNNERS" ]; then
    printf "${YELLOW}You already have Oh My Zsh installed.${NORMAL}\n"
    printf "You'll need to remove $RUNNERS if you want to re-install.\n"
    exit
  fi

  # Prevent the cloned repository from having insecure permissions. Failing to do
  # so causes compinit() calls to fail with "command not found: compdef" errors
  # for users with insecure umasks (e.g., "002", allowing group writability). Note
  # that this will be ignored under Cygwin by default, as Windows ACLs take
  # precedence over umasks except for filesystems mounted with option "noacl".
  umask g-w,o-w

  printf "${BLUE}Cloning Runner Scripts...${NORMAL}\n"
  command -v git >/dev/null 2>&1 || {
    echo "Error: git is not installed"
    exit 1
  }
  command -v composer >/dev/null 2>&1 || {
    echo "Error: composer is not installed"
    exit 1
  }

  env git clone --depth=1 https://github.com/drlenux/runner-scripts.git "$RUNNERS" || {
    printf "Error: git clone of runner scripts repo failed\n"
    exit 1
  }

  cd ~/.runner-scripts/ && composer install

  sudo chmod +x ~/.runner-scripts/run.php
  sudo chmod +x ~/.runner-scripts/update.sh
  sudo chmod +x ~/.runner-scripts/unistall.sh
  sudo chmod +x ~/.runner-scripts/template-install.php
  sudo ln -s ~/.runner-scripts/run.php /usr/bin/runners
  sudo ln -s ~/.runner-scripts/update.sh /usr/bin/runners-update
  sudo ln -s ~/.runner-scripts/unistall.sh /usr/bin/runners-unistall
  sudo ln -s ~/.runner-scripts/template-install.php /usr/bin/runners-template-install

  printf "${NORMAL}"
}

main