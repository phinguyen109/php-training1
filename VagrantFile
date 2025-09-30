# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
  # Box Ubuntu
  config.vm.box = "ubuntu/focal64"
  config.vm.box_version = "20240821.0.1"

  # Network
  # Forward port từ guest VM -> host máy bạn
  config.vm.network "forwarded_port", guest: 8080, host: 8080   # web-backend
  config.vm.network "forwarded_port", guest: 8081, host: 8081   # phpMyAdmin
  config.vm.network "forwarded_port", guest: 3306, host: 3306   # MySQL

  # Sync folder
  config.vm.synced_folder "./sources", "/vagrant"

  # VirtualBox provider
  config.vm.provider "virtualbox" do |vb|
    vb.gui = true
    vb.memory = "4096"
    vb.cpus = 2
  end

  # Provision script
  config.vm.provision "shell", inline: <<-SHELL
    sudo apt-get update -y
    sudo apt-get install -y apache2 docker.io docker-compose git make net-tools
    sudo usermod -aG docker vagrant
  SHELL

  # Timeout boot
  config.vm.boot_timeout = 600
end
